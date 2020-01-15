<?php

  /**
   * This is the model class for table "StoreImportAssignProductOptions".
   *
   * The followings are the available columns in table 'StoreImportAssignProductOptions':
   * @property string $oid
   * @property string $value
   * @property string $solution
   * @property string $elaboration
   * @property string $spid
   * @property string $comment
   * @property integer $isNew
   * @property string $updated
   */
  class StoreImportAssignProductOptions extends BaseModel
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
      return 'StoreImportAssignProductOptions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
      // NOTE: you should only define rules for those attributes that
      // will receive user inputs.
      return array(
        array('oid, value', 'required'),
        array('isNew', 'numerical', 'integerOnly'=>true),
        array('oid, spid', 'length', 'max'=>20),
        array('value, elaboration, comment', 'length', 'max'=>255),
        array('solution', 'length', 'max'=>6),
        // The following rule is used by search().
        // @todo Please remove those attributes that should not be searched.
        array('oid, value, solution, elaboration, spid, comment, isNew, updated', 'safe', 'on'=>'search'),
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
    public function relations(){
      // NOTE: you may need to adjust the relation name and the related
      // class name for the relations automatically generated below.
      return array(
        'attributes' => array(self::BELONGS_TO, 'StoreAttribute', 'oid'),
      );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
      return array(
        'oid' => 'Oid',
        'value' => 'Value',
        'solution' => 'Solution',
        'elaboration' => 'Elaboration',
        'spid' => 'Spid',
        'comment' => 'Comment',
        'isNew' => 'Is New',
        'updated' => 'Updated',
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

      $criteria->compare('oid',$this->oid,true);
      $criteria->compare('value',$this->value,true);
      $criteria->compare('solution',$this->solution,true);
      $criteria->compare('elaboration',$this->elaboration,true);
      $criteria->compare('spid',$this->spid,true);
      $criteria->compare('comment',$this->comment,true);
      $criteria->compare('isNew',$this->isNew);
      $criteria->compare('updated',$this->updated,true);

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
     * @return StoreImportAssignProductOptions the static model class
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

    static public function getPlainTree($sid){
      $datas = Yii::app()->db_import->createCommand("
SELECT attr, spid FROM StoreImportDataProductOptions po
LEFT JOIN StoreImportDataOptions o ON o.id = po.oid
LEFT JOIN StoreImportAssignOptions ao ON ao.cid = o.id
WHERE po.sid = '$sid' AND ao.solution = 'assign' AND ao.isNew = 0
")->queryAll();
      foreach($datas as $data) {
        $model = StoreImportAssignProductOptions::model()->findByAttributes(array(
          'oid' => $data['spid'],
          'value' => $data['attr'],
        ));
        if (!$model) {
          $model = new StoreImportAssignProductOptions();
          $model->oid   = $data['spid'];
          $model->value = $data['attr'];
          if (!$model->save()) {
            print_r($model->errors);
            die();
          }
        }
      }

      $models = self::model()->findAll();
      $data   = array();
      foreach($models as $model) {
        $attr = StoreAttribute::model()->findByPk($model->oid);
        if ($attr && in_array($attr->type, array(StoreAttribute::TYPE_DROPDOWN, StoreAttribute::TYPE_YESNO))) {
          $info = array_merge(Helper::toArray($model), Helper::toArray($attr));
          $info['slug'] = " ($attr->name)";
          $info['title'] = $attr->title;
          $data[] = $info;
        }
      }
      return $data;
    }

  }

