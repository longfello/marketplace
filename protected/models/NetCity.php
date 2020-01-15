<?php

/**
 * This is the model class for table "net_city".
 *
 * The followings are the available columns in table 'net_city':
 * @property integer $id
 * @property integer $country_id
 * @property integer $region_id
 * @property string $name
 * @property string $name_ru
 * @property string $name_en
 * @property string $postal_code
 * @property string $latitude
 * @property string $longitude
 */
class NetCity extends CMultiLangActiveRecord
{
  public $MultiLangFields = array('name');

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'net_city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('country_id, region_id', 'numerical', 'integerOnly'=>true),
			array('name_ru, name_en', 'length', 'max'=>100),
			array('postal_code, latitude, longitude', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, country_id, region_id, name_ru, name_en, postal_code, latitude, longitude', 'safe', 'on'=>'search'),
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
      'country' => array(self::HAS_ONE, 'NetCountry', array('id' => 'country_id')),
      'region'  => array(self::HAS_ONE, 'NetRegions', array('id' => 'region_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'          => Yii::t('LocationTables','ID'),
			'country_id'  => Yii::t('LocationTables','Country'),
			'region_id'   => Yii::t('LocationTables','Region'),
			'name_ru'     => Yii::t('LocationTables','Name Ru'),
			'name_en'     => Yii::t('LocationTables','Name En'),
			'postal_code' => Yii::t('LocationTables','Postal Code'),
			'latitude'    => Yii::t('LocationTables','Latitude'),
			'longitude'   => Yii::t('LocationTables','Longitude'),
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

		$criteria->compare('id',$this->id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('region_id',$this->region_id);
		$criteria->compare('name_ru',$this->name_ru,true);
		$criteria->compare('name_en',$this->name_en,true);
		$criteria->compare('postal_code',$this->postal_code,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination'=>array('pageSize'=>25)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NetCity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
