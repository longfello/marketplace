<?php

/**
 * This is the model class for table "StoreImportSources".
 *
 * The followings are the available columns in table 'StoreImportSources':
 * @property string $id
 * @property string $market_id
 * @property string $name
 * @property string $sourceURL
 * @property string $status
 * @property string $type
 * @property string $comment
 * @property int $lastRun
 *
 * @property StoreImportDataCurrencies $currencies
 * @property StoreImportAssignCategory $assignCategory
 * @property StoreImportDataCategories $categories
 * @property StoreImportDataProduct    $products
 * @property StoreImportDataVendors    $vendors
 * @property StoreImportDataOptions    $options
 *
 * @method StoreImportSources  onApproving()
 */
class StoreImportSources extends BaseModel
{
  const STATUS_NEW = 'new';
  const STATUS_ERRORS = 'errors';
  const STATUS_ASSIGN = 'assign';
  const STATUS_CONFIRMATION = 'confirmation';
  const STATUS_APPROVED = 'approved';
  const STATUS_BLOCKED = 'blocked';

  const TYPE_YML = 'yml';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'StoreImportSources';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sourceURL, type', 'required'),
			array('id, market_id', 'length', 'max'=>20),
			array('sourceURL, name, comment', 'length', 'max'=>255),
      array('status', 'in', 'range'=>array_keys(self::getStatuses())),
      array('type', 'in', 'range'=>array_keys(self::getTypes())),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, market_id, name, sourceURL, approved, type, comment', 'safe', 'on'=>'search'),
		);
	}

  public static function getStatuses() {
    return array(
      self::STATUS_NEW=>Yii::t('StoreModule', 'Новый'),
      self::STATUS_ERRORS=>Yii::t('StoreModule', 'Содержит ошибки'),
      self::STATUS_ASSIGN=>Yii::t('StoreModule', 'Сопоставление данных'),
      self::STATUS_CONFIRMATION=>Yii::t('StoreModule', 'На проверке'),
      self::STATUS_APPROVED=>Yii::t('StoreModule', 'Подтвержден'),
      self::STATUS_BLOCKED=>Yii::t('StoreModule', 'Заблокирован'),
    );
  }

  public static function getTypes() {
    return array(
      self::TYPE_YML=>'Yandex Market YML',
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
//      'market'          => array(self::BELONGS_TO, 'StoreMarket', 'market_id'),
      'currencies'      => array(self::HAS_MANY, 'StoreImportDataCurrencies', 'sid'),
      'assignCategory'  => array(self::HAS_MANY, 'StoreImportAssignCategory', 'sid'),
      'categories'      => array(self::HAS_MANY, 'StoreImportDataCategories', 'sid'),
      'products'        => array(self::HAS_MANY, 'StoreImportDataProducts', 'sid'),
      'vendors'         => array(self::HAS_MANY, 'StoreImportDataVendors', 'sid'),
      'options'         => array(self::HAS_MANY, 'StoreImportDataOptions', 'sid'),
		);
	}

  public function scopes()
  {
    return array(
      'onApproving' => array(
        'condition'=> Yii::app()->user->getIsManager()
            ?"0=1"
            :$this->getTableAlias().".status = '".self::STATUS_CONFIRMATION."'"),
    );
  }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('StoreModule', 'ID'),
			'market_id' => Yii::t('StoreModule', 'Магазин'),
			'name' => Yii::t('StoreModule', 'Название источника'),
			'sourceURL' => Yii::t('StoreModule', 'Ссылка на файл'),
			'status' => Yii::t('StoreModule', 'Статус источника'),
			'type' => Yii::t('StoreModule', 'Тип ресурса'),
			'comment' => Yii::t('StoreModule', 'Комментарий'),
			'lastRun' => Yii::t('StoreModule', 'Последний запуск'),
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
	public function search($params = array(), $additionalCriteria = null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('market_id',$this->market_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sourceURL',$this->sourceURL,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StoreImportSources the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function getDbConnection(){
    return Yii::app()->db_import;
  }

  public function beforeSave(){
    if (parent::beforeSave()) {
      if ($this->isNewRecord) {
        $this->status = self::STATUS_NEW;
      } else {
        if ($this->status == self::STATUS_APPROVED) {
          $this->applyAssignedData();
        }
      }
      return true;
    }
    return false;
  }

  private function applyAssignedData(){
    // apply categories

    $models = StoreImportDataCategories::model()->withSolutions()->findAllByAttributes(array(
      'sid' => $this->id
    ));
    foreach($models as $category) {
      /* @var $category StoreImportDataCategories */
      StoreImportAssignCategory::assignData($category);
    }

    // apply vendors
    $models = StoreImportDataVendors::model()->withSolutions()->findAllByAttributes(array(
      'sid' => $this->id
    ));
    foreach($models as $vendor) {
      /* @var $vendor StoreImportDataVendors */
      StoreImportAssignVendors::assignData($vendor);
    }

    // apply options
    $models = StoreImportDataOptions::model()->withSolutions()->findAllByAttributes(array(
      'sid' => $this->id
    ));
    foreach($models as $option) {
      /* @var $option StoreImportDataOptions */
      StoreImportAssignOptions::assignData($option);
    }


  }
}
