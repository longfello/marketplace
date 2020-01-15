<?php

/**
 * This is the model class for table "StoreMarketMarkers".
 *
 * The followings are the available columns in table 'StoreMarketMarkers':
 * @property integer $id
 * @property integer $market_id
 * @property double $lat
 * @property double $lng
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property integer $city_id
 */
class StoreMarketMarkers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'StoreMarketMarkers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('market_id, lat, lng', 'required'),
			array('id, market_id, city_id', 'numerical', 'integerOnly'=>true),
			array('lat, lng', 'numerical'),
			array('name, address', 'length', 'max'=>100),
			array('phone', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, market_id, lat, lng, name, address, phone, city_id', 'safe', 'on'=>'search'),
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
      'city' => array(self::HAS_ONE, 'NetCity', array('id' => 'city_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('StoreModule','ID'),
			'market_id' => Yii::t('StoreModule','Market'),
			'lat' => Yii::t('StoreModule','Lat'),
			'lng' => Yii::t('StoreModule','Lng'),
			'name' => Yii::t('StoreModule','Name'),
			'address' => Yii::t('StoreModule','Address'),
			'phone' => Yii::t('StoreModule','Phone'),
      'city_id' => Yii::t('StoreModule','City')
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

		$criteria->compare('market_id',$this->market_id);
		$criteria->compare('lat',$this->lat);
		$criteria->compare('lng',$this->lng);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('city_id',$this->city_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreMarketMarkers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function getView(){
    $city = $this->city?"<span class='marker-city'>{$this->city->name}</span>,":'';
    return "
<div class='market-marker-title'>{$this->name}</div>
<div class='market-marker-address'>
  ".$city."
  <span class='marker-address'>{$this->address}</span>
</div>
<div class='market-marker-phone'>{$this->phone}</div>
";
  }
}
