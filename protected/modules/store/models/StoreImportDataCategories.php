<?php

/**
 * This is the model class for table "StoreImportDataCategories".
 *
 * The followings are the available columns in table 'StoreImportDataCategories':
 * @property string $sid
 * @property string $id
 * @property string $pid
 * @property string $name
 * @property integer $level
 *
 * @property StoreImportDataCategories $parent
 * @property StoreImportSources $source
 * @property StoreImportAssignCategory $solution
 */
class StoreImportDataCategories extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'StoreImportDataCategories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sid, id, pid, name, level', 'required'),
			array('level', 'numerical', 'integerOnly'=>true),
			array('sid, id, pid', 'length', 'max'=>20),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sid, id, pid, name, level', 'safe', 'on'=>'search'),
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
      'parent' => array(self::HAS_ONE, 'StoreImportDataCategories', 'pid'),
      'source' => array(self::HAS_ONE, 'StoreImportSources', 'sid'),
      'solution' => array(self::HAS_ONE, 'StoreImportAssignCategory', array('sid' => 'sid', 'cid' => 'id')),
      'products' => array(self::HAS_MANY, 'StoreImportDataProduct', array('sid' => 'sid', 'cid' => 'id')),
		);
	}

  public function withSolutions(){
    $this->with(array(
      'solution' => array(
        'select' => 'solution, elaboration, spid, comment, isNew'
      )
    ));

    return $this;
  }
  public function withProducts(){
    $this->with('products');
    return $this;
  }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'sid' => Yii::t('StoreModule', 'id источника'),
			'id' => Yii::t('StoreModule', 'id в источнике'),
			'pid' => Yii::t('StoreModule', 'id родительской категории'),
			'name' => Yii::t('StoreModule', 'Название'),
			'level' => Yii::t('StoreModule', 'Уровень вложенности'),
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
		$criteria->compare('id',$this->id,true);
		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('level',$this->level);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_import;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreImportDataCategories the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public static function getTree($sid){
    $els = StoreImportDataCategories::model()->withSolutions()->findAll(StoreImportDataCategories::model()->getTableAlias().'.sid = :sid', array(':sid' => $sid));
    return self::getTreeChild(0, $els);
  }

  static function getTreeChild($parent, $els){
    $res = array();
    foreach($els as $el) {
      if ($el->pid == $parent) {
        $childs = self::getTreeChild($el->id, $els);
        $res[$el->id] = array(
          'id'          => $el->id,
          'name'        => $el->name,
          'hasChildren' => (bool)$childs,
          'children'    => $childs
        );
      }
    }
    return $res;
  }
  public static function getPlainTree($sid){
    $hash = self::model()->tableName().'_plainTree_'.$sid;
    $tree = Yii::app()->cache->get($hash);
    if (true || !$tree)
    {
      $els = StoreImportDataCategories::model()->withSolutions()->findAll(StoreImportDataCategories::model()->getTableAlias().'.sid = :sid', array(':sid' => $sid));
      $tree = self::getPlainTreeChild(0, $els);
      Yii::app()->cache->set($hash, $tree, 5*60);
    }
    return $tree;
  }

  static function getPlainTreeChild($parent, $els, $level = 1){
    $res = array();
    foreach($els as $el) {
      if ($el->pid == $parent) {
        $res[$el->id] = array_merge(Helper::toArray($el->solution),Helper::toArray($el));
        $res[$el->id]['level'] = $level;
        $res[$el->id]['count'] = StoreImportDataProduct::model()->cache(60,null,60)->countByAttributes(array('sid'=>$el->sid, 'cid'=>$el->id));
        $res[$el->id]['name']  = str_repeat('-', $level-1) . $el->name;
        $res[$el->id]['real_name']  = $el->name;
        $res[$el->id]['isNew']  = ($el->solution)?$el->solution->isNew:1;
        $childs = self::getPlainTreeChild($el->id, $els, $level+1);
        foreach($childs as $child) {
          $res[$child['id']] = $child;
        }
      }
    }
    return $res;
  }
}
