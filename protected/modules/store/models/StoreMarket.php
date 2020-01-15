<?php

Yii::import('application.modules.store.StoreModule');
Yii::import('application.modules.store.models.StoreMarketTranslate');
Yii::import('application.modules.users.models.User');
/**
 * This is the model class for table "StoreMarket".
 *
 * The followings are the available columns in table 'StoreProduct':
 * @property integer $id
 * @property integer $user_id
 * @property integer $is_active
 * @property string $name
 * @property string $status_comment
 * @property string $description
 * @property string $delivery_desc
 * @property string $url
 * @property StoreMarketMarkers $markers
 * @method StoreMarket orderByName()
 */
class StoreMarket extends BaseModel
{
  const ADMIN_MARKET = 1;

	/**
	 * @var string
	 */
	public $translateModelName = 'StoreMarketTranslate';

	/**
	 * Multilingual attrs
	 */
	public $name;
	public $description;
	public $delivery_desc;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className
	 * @return StoreProduct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'StoreMarket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('user_id, is_active', 'numerical', 'integerOnly'=>true),
			array('name', 'required'),
			array('name, status_comment, url', 'length', 'max'=>255),
			array('description, delivery_desc', 'type', 'type'=>'string'),
      array('url', 'required', 'on'=>'register_manager'),
			// Search
			array('id, user_id, name, description, delivery_desc, status_comment, is_active, url', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'owner'            => array(self::BELONGS_TO, 'User', 'user_id'),
			'market_translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
      'markers'          => array(self::HAS_MANY, 'StoreMarketMarkers', array('market_id'=>'id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                     => 'ID',
			'user_id'                => Yii::t('StoreModule', 'Владелец'),
			'is_active'              => Yii::t('StoreModule', 'Статус'),
      'status_comment'         => Yii::t('StoreModule', 'Комментарий к статусу'),
			'name'                   => Yii::t('StoreModule', 'Название магазина'),
			'description'            => Yii::t('StoreModule', 'Описание'),
			'delivery_desc'          => Yii::t('StoreModule', 'Описание доставки'),
			'url'                    => Yii::t('StoreModule', 'Адрес сайта магазина'),
		);
	}

  public function scopes()
  {
    return array(
      'orderByName' => array('order'=> 'market_translate.name'),
      'onlyOwn'     => array('condition'=> Yii::app()->user->getIsManager()?$this->getTableAlias().".id IN (".implode(',',Yii::app()->user->getUserMarketsIds()).')':""),
    );
  }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @param $params
	 * @param $additionalCriteria
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($params = array(), $additionalCriteria = null){
		$criteria=new CDbCriteria;
    $criteria->with = array(
      'market_translate',
    );

    if($additionalCriteria !== null)
      $criteria->mergeWith($additionalCriteria);

    $ids=$this->id;
    // Adds ability to accepts id as "1,2,3" string
    if(false !== strpos($ids, ','))
    {
      $ids = explode(',', $this->id);
      $ids = array_map('trim', $ids);
    }

    $criteria->compare('t.id', $ids);
    $criteria->compare('market_translate.name',$this->name,true);
    $criteria->compare('t.is_active',$this->is_active);
    $criteria->compare('market_translate.description',$this->description,true);
    $criteria->compare('url',$this->url,true);
    $criteria->compare('market_translate.delivery_desc',$this->delivery_desc,true);
    $criteria->compare('status_comment',$this->status_comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria
		));
	}

	/**
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'STranslateBehavior'=>array(
				'class'=>'ext.behaviors.STranslateBehavior',
        'relationName'=>'market_translate',
				'translateAttributes'=>array(
					'name',
					'description',
          'delivery_desc'
				),
			),
      'comments' => array(
        'class'       => 'application.modules.comments.components.CommentBehavior',
        'class_name'  => 'application.modules.store.models.StoreMarkets',
        'owner_title' => 'name', // Attribute name to present comment owner in admin panel
      ),
		);
	}

	/**
	 * Delete related data.
	 */
	public function afterDelete()
	{
		// Delete related products
		StoreProduct::model()->deleteAll('market_id=:id', array('id'=>$this->id));
		return parent::afterDelete();
	}
	/**
	 * @return CSort to use in gridview, listview, etc...
	 */
	public static function getCSort()
	{
		$sort = new CSort;
		$sort->defaultOrder = 'market_translate.name';
		$sort->attributes=array(
			'*',
			'name' => array(
				'asc'   => 'market_translate.name',
				'desc'  => 'market_translate.name DESC',
			),
		);
		return $sort;
	}

  public function getFilter(){
    $list = $this->model()->onlyOwn()->orderByName()->findAll();
    $ret  = array();
    foreach($list as $item){
      $ret[$item->id] = $item->name;
    }

    return $ret;
  }

}