<?php

  /**
   * This is the model class for table "StoreImportDataProductOptions".
   *
   * The followings are the available columns in table 'StoreImportDataProductOptions':
   * @property int $sid
   * @property int $pid
   * @property int $oid
   * @property int $moid
   * @property string $attr
   */
  class StoreImportDataProductOptions extends BaseModel
  {
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
      return 'StoreImportDataProductOptions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
      // NOTE: you should only define rules for those attributes that
      // will receive user inputs.
      return array(
        array('sid, pid, oid, attr', 'required'),
        array('sid, pid, oid, moid', 'length', 'max'=>20),
        array('attr', 'length', 'max'=>255),
        // The following rule is used by search().
        // @todo Please remove those attributes that should not be searched.
        array('sid, pid, oid, moid, attr', 'safe', 'on'=>'search'),
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
      );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
      return array(
        'sid' => 'Sid',
        'pid' => 'Pid',
        'oid' => 'Oid',
        'attr' => 'Value',
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
      $criteria->compare('pid',$this->pid,true);
      $criteria->compare('oid',$this->oid,true);
      $criteria->compare('moid',$this->moid,true);
      $criteria->compare('attr',$this->attr,true);

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
     * @return StoreImportDataProductOptions the static model class
     */
    public static function model($className=__CLASS__)
    {
      return parent::model($className);
    }
  }