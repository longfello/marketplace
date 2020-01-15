<?php

/**
 * This is the model class for table "StoreImportDataCurrencies".
 *
 * The followings are the available columns in table 'StoreImportDataCurrencies':
 * @property string $sid
 * @property string $iso
 * @property double $rate
 */
class StoreImportDataCurrencies extends BaseModel
{
  const STATUS_NONE   = 'none';
  const STATUS_OLD    = 'old';
  const STATUS_NEW    = 'new';
  const STATUS_REMOVE = 'remove';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'StoreImportDataCurrencies';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sid, iso, rate', 'required'),
			array('rate', 'numerical'),
			array('sid', 'length', 'max'=>20),
			array('iso', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sid, iso, rate', 'safe', 'on'=>'search'),
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
			'sid' => Yii::t('StoreModule', 'id источника импорта'),
			'iso' => Yii::t('StoreModule', 'Iso-код валюты'),
			'rate' => Yii::t('StoreModule', 'Курс валюты'),
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
		$criteria->compare('iso',$this->iso,true);
		$criteria->compare('rate',$this->rate);

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
	 * @return StoreImportDataCurrencies the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
