<?php

/**
 * This is the model class for table "StoreImportDataVendors".
 *
 * The followings are the available columns in table 'StoreImportDataVendors':
 * @property string $id
 * @property string $sid
 * @property string $name
 * @property StoreImportAssignVendors $solution
 */
class StoreImportDataVendors extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'StoreImportDataVendors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sid, name', 'required'),
			array('sid, id', 'length', 'max'=>20),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sid, name', 'safe', 'on'=>'search'),
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
      'source' => array(self::HAS_ONE, 'StoreImportSources', 'sid'),
      'solution' => array(self::HAS_ONE, 'StoreImportAssignVendors', array('sid' => 'sid', 'cid' => 'id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'id',
			'sid' => 'Sid',
			'name' => 'Name',
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
		$criteria->compare('name',$this->name,true);

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
	 * @return StoreImportDataVendors the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function withSolutions(){
    $this->with(array(
      'solution' => array(
        'select' => 'solution, elaboration, spid, comment, isNew'
      )
    ));

    return $this;
  }

  public static function getPlainTree($sid){
    $els = StoreImportDataVendors::model()->withSolutions()->findAll(StoreImportDataVendors::model()->getTableAlias().'.sid = :sid', array(':sid' => $sid));
    $tree = array();
    foreach($els as $el) {
      $tree[$el->id] = array_merge(Helper::toArray($el->solution),Helper::toArray($el));
      $tree[$el->id]['level'] = 1;
      $tree[$el->id]['name'] = $el->name;
      $tree[$el->id]['real_name'] = $el->name;
    }
    return $tree;
  }

}
