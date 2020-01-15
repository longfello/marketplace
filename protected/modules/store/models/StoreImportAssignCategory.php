<?php

/**
 * This is the model class for table "StoreImportAssignCategory".
 *
 * The followings are the available columns in table 'StoreImportAssignCategory':
 * @property int $sid
 * @property int $cid
 * @property int $spid
 * @property string $solution
 * @property string $elaboration
 * @property string $comment
 * @property bool $isNew
 * @property string $updated
 */
class StoreImportAssignCategory extends BaseModel
{
  const SOLUTION_NONE     = 'none';
  const SOLUTION_ASSIGN   = 'assign';
  const SOLUTION_SKIP     = 'skip';
  const SOLUTION_CREATE   = 'create';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'StoreImportAssignCategory';
	}

  public static function getIsConfirmed($getTitle = null){
    $titles = array(
      '0' => Yii::t('StoreModule', 'Правило подтверждено'),
      '1' => Yii::t('StoreModule', 'Новое правило'),
    );
    if (is_null($getTitle)) {
      return $titles;
    } else {
      return isset($titles[$getTitle])?$titles[$getTitle]:'?';
    }
  }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sid, cid, solution', 'required'),
			array('sid, cid, spid', 'length', 'max'=>20),
			array('isNew', 'length', 'max'=>1),
      array('solution', 'in', 'range'=>array_keys(self::getSolutions())),
			array('elaboration, comment', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sid, cid, spid, solution, isNew, elaboration, comment, updated', 'safe', 'on'=>'search'),
		);
	}

  public function getSolutions(){
    return array(
      self::SOLUTION_NONE   => Yii::t('StoreModule', 'Нет решения'),
      self::SOLUTION_ASSIGN => Yii::t('StoreModule', 'Сопоставить'),
      self::SOLUTION_SKIP   => Yii::t('StoreModule', 'Пропустить'),
      self::SOLUTION_CREATE => Yii::t('StoreModule', 'Создать'),
    );
  }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
    return array(
      'source'           => array(self::BELONGS_TO, 'StoreImportSource', 'sid'),
    );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'sid' => Yii::t('StoreModule', 'ID источника импорта'),
			'cid' => Yii::t('StoreModule', 'ID категории'),
			'spid' => Yii::t('StoreModule', 'ID категории в структуре Rivori'),
			'solution' => Yii::t('StoreModule', 'Решение'),
			'elaboration' => Yii::t('StoreModule', 'Уточнение'),
			'comment' => Yii::t('StoreModule', 'Комментарий'),
			'updated' => Yii::t('StoreModule', 'Обновлено'),
			'isNew' => Yii::t('StoreModule', 'Новое правило'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('sid',$this->sid,true);
		$criteria->compare('cid',$this->cid,true);
		$criteria->compare('solution',$this->solution,true);
		$criteria->compare('elaboration',$this->elaboration,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('isNew',$this->isNew,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

  public function getDbConnection(){
    return Yii::app()->db_import;
  }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreImportAssignCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function beforeSave(){
    if (parent::beforeSave()) {
      if ($this->isNewRecord) {
        if (!$this->solution) {
          $this->solution = self::SOLUTION_NONE;
        }
        $this->isNew = in_array($this->solution, array(self::SOLUTION_ASSIGN, self::SOLUTION_CREATE))?1:0;
      }
      return true;
    } return false;
  }

  public static function assignData(StoreImportDataCategories $category){
    switch($category->solution->solution) {
      case StoreImportAssignCategory::SOLUTION_CREATE:
        $model = new StoreCategory;
        $model->name = $category->solution->elaboration;
        $model->url = mb_strtolower(Helper::translit($category->solution->elaboration));
        $model->description = '';

        if ($model->validate()) {
          $parentID = $category->solution->spid;
          if ($parentID < 0) {
            $parentCategory = StoreImportDataCategories::model()->withSolutions()->findByAttributes(array(
              'sid' => $category->sid,
              'id'  => -$parentID
            ));
            $parentID = self::assignData($parentCategory);
          }
          $parent=StoreCategory::model()->findByPk($parentID);
          $model->appendTo($parent);

          $category->solution->solution = StoreImportAssignCategory::SOLUTION_ASSIGN;
          $category->solution->spid = $model->id;
          $category->solution->isNew = 0;
          $category->solution->save();

          $childs = StoreImportAssignCategory::model()->findByAttributes(array(
            'sid'  => $category->sid,
            'spid' => -$category->id
          ));
          foreach($childs as $child) {
            $child->spid = $category->id;
            $child->save();
          }

          return $model->id;
        } else {
          StoreImportErrors::add($category->sid, Yii::t('StoreModule', "Category validation error:"), array(
            Yii::t('StoreModule', 'Ошибки')=> $model->getErrors(),
            Yii::t('StoreModule', 'Данные')  => Helper::toArray($model)
          ));
        }
        break;
      case StoreImportAssignCategory::SOLUTION_ASSIGN:
        $category->solution->isNew = 0;
        $category->solution->save();

        $childs = StoreImportAssignCategory::model()->findByAttributes(array(
          'sid'  => $category->sid,
          'spid' => -$category->id
        ));
        foreach($childs as $child) {
          $child->spid = $category->id;
          $child->save();
        }

        return $category->solution->spid;
        break;
    }
    return false;
  }

}
