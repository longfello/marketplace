<?php

/**
 * This is the model class for table "StoreImportDataOptions".
 *
 * The followings are the available columns in table 'StoreImportDataOptions':
 * @property int $id
 * @property int $sid
 * @property string $name
 * @property string $list
 */
class StoreImportDataOptions extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'StoreImportDataOptions';
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
			array('list', 'length', 'max'=>65535),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sid, name, list', 'safe', 'on'=>'search'),
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
      'solution' => array(self::HAS_ONE, 'StoreImportAssignOptions', array('sid' => 'sid', 'cid' => 'id')),
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
			'list' => 'Attribute values list',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('sid',$this->sid,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('list',$this->list,true);

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
	 * @return StoreImportDataOptions the static model class
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
    $els = StoreImportDataOptions::model()->withSolutions()->findAll(StoreImportDataOptions::model()->getTableAlias().'.sid = :sid', array(':sid' => $sid));
    $tree = array();
    foreach($els as $el) {
      $tree[$el->id] = array_merge(Helper::toArray($el->solution),Helper::toArray($el));
      $tree[$el->id]['list'] = " <span class='text-muted'>(".mb_substr(implode(',',unserialize($el->list)),0,100).")</span>";
      $tree[$el->id]['level'] = 1;
      $tree[$el->id]['name'] = $el->name;
      $tree[$el->id]['real_name'] = $el->name;
      $tree[$el->id]['isNew'] = ($el->solution)?$el->solution->isNew:1;
    }
    return $tree;
  }

}
