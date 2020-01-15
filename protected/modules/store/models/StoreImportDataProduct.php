<?php

/**
 * This is the model class for table "StoreImportDataProduct".
 *
 * The followings are the available columns in table 'StoreImportDataProduct':
 * @property string $sid
 * @property string $id
 * @property string $name
 * @property double $price
 * @property string $cid
 * @property string $picture
 * @property string $description
 * @property integer $available
 * @property string $vendor
 * @property string $article
 */
class StoreImportDataProduct extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'StoreImportDataProduct';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sid, id, name, price, cid, picture, description', 'required'),
			array('available', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('sid, id, cid', 'length', 'max'=>20),
			array('name, picture, vendor, article', 'length', 'max'=>255),
			array('description', 'length', 'max'=>1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sid, id, name, price, cid, picture, description, available, vendor, article', 'safe', 'on'=>'search'),
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
      'category' => array(self::HAS_ONE, 'StoreImportDataCategories', 'cid'),
      'source' => array(self::HAS_ONE, 'StoreImportSources', 'sid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
      'sid' => Yii::t('StoreModule', 'id источника'),
      'id' => Yii::t('StoreModule', 'id в источнике'),
			'name' => Yii::t('StoreModule', 'Наименование'),
			'price' => Yii::t('StoreModule', 'Цена'),
      'cid' => Yii::t('StoreModule', 'id родительской категории'),
			'picture' => Yii::t('StoreModule', 'Ссылка на изображение'),
			'description' => Yii::t('StoreModule', 'Описание'),
			'available' => Yii::t('StoreModule', 'Досупность'),
			'vendor' => Yii::t('StoreModule', 'Производитель'),
			'article' => Yii::t('StoreModule', 'Артикул'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('cid',$this->cid,true);
		$criteria->compare('picture',$this->picture,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('available',$this->available);
		$criteria->compare('vendor',$this->vendor,true);
		$criteria->compare('article',$this->article,true);

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
	 * @return StoreImportDataProduct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
