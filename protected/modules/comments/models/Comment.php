<?php

/**
 * This is the model class for table "Comments".
 *
 * The followings are the available columns in table 'Comments':
 * @property integer $id
 * @property integer $user_id
 * @property string $class_name
 * @property integer $object_pk
 * @property integer $status
 * @property string $email
 * @property string $name
 * @property string $text
 * @property string $created
 * @property string $updated
 * @property string $ip_address
 * @method approved()
 * @method orderByCreatedAsc()
 * @method orderByCreatedDesc()
 */
class Comment extends BaseModel
{

	const STATUS_WAITING = 0;
	const STATUS_APPROVED = 1;
	const STATUS_SPAM = 2;

  const CLASS_PRODUCT = 'application.modules.store.models.StoreProduct';
  const CLASS_SITE    = 'application.modules.comments.models.Comment';
  const CLASS_MARKET  = 'application.modules.store.models.StoreMarket';

	/**
	 * @var string
	 */
	public $verifyCode;

	/**
	 * @var int status for new comments
	 */
	public $defaultStatus;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Initialize
	 */
	public function init()
	{
		$this->defaultStatus = Comment::STATUS_WAITING;
		return parent::init();
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Comments';
	}

	public function scopes()
	{
		$alias = $this->getTableAlias();
		return array(
			'orderByCreatedAsc'=>array(
				'order'=>$alias.'.created ASC',
			),
			'orderByCreatedDesc'=>array(
				'order'=>$alias.'.created DESC',
			),
			'waiting'=>array(
				'condition'=>$alias.'.status='.self::STATUS_WAITING,
			),
			'approved'=>array(
				'condition'=>$alias.'.status='.self::STATUS_APPROVED,
			),
			'spam'=>array(
				'condition'=>$alias.'.status='.self::STATUS_SPAM,
			)
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$codeEmpty=!Yii::app()->user->isGuest;
    /*
		if(YII_DEBUG) // For tests
			$codeEmpty=true;
    */
		return array(
			array('email, name, text', 'required'),
      array('rate', 'numerical', 'integerOnly'=>true,'allowEmpty'=>true),
      array('email', 'email'),
			array('status, created, updated', 'required', 'on'=>'update'),
			array('name', 'length', 'max'=>50),
			array('verifyCode','captcha','allowEmpty'=>$codeEmpty),
			// Search
			array('id, user_id, class_name, status, email, name, text, created, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'         => 'ID',
			'user_id'    => Yii::t('CommentsModule','Автор'),
			'class_name' => Yii::t('CommentsModule','Модель'),
			'status'     => Yii::t('CommentsModule','Статус'),
			'email'      => Yii::t('CommentsModule','Почта'),
			'name'       => Yii::t('CommentsModule','Имя'),
			'text'       => Yii::t('CommentsModule','Комментарий'),
			'created'    => Yii::t('CommentsModule','Дата создания'),
			'updated'    => Yii::t('CommentsModule','Дата обновления'),
			'owner_title'=> Yii::t('CommentsModule','Владелец'),
			'verifyCode' => Yii::t('CommentsModule','Код проверки'),
			'ip_address' => Yii::t('CommentsModule','IP адрес'),
			'rate'       => Yii::t('CommentsModule','Рейтинг'),
		);
	}

	/**
	 * Before save.
	 */
	public function beforeSave()
	{
		if($this->isNewRecord)
		{
			$this->status = $this->defaultStatus;
			$this->ip_address = Yii::app()->request->userHostAddress;
			$this->created = date('Y-m-d H:i:s');
		}
		$this->updated = date('Y-m-d H:i:s');
		return parent::beforeSave();
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('class_name',$this->class_name);
		$criteria->compare('object_pk',$this->object_pk);
		$criteria->compare('status',$this->status);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		if($this->user_id)
			$criteria->compare('user_id',$this->user_id);

		$sort=new CSort;
		$sort->defaultOrder = $this->getTableAlias().'.created DESC';

    if (Yii::app()->user->getIsManager()) {
      $criteria->join = "LEFT JOIN StoreProduct p ON p.id = ".$this->tableAlias.".object_pk";
      $criteria->addCondition($this->tableAlias.".class_name='".self::CLASS_PRODUCT."' AND p.market_id IN (".implode(',',Yii::app()->user->getUserMarketsIds()).")");
      $criteria->addCondition($this->tableAlias.".class_name='".self::CLASS_MARKET."' AND ".$this->tableAlias.".object_pk IN (".implode(',',Yii::app()->user->getUserMarketsIds()).")", "OR");
    }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
		));
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getStatuses()
	{
		return array(
			self::STATUS_WAITING  => Yii::t('CommentsModule', 'Ждет одобрения'),
			self::STATUS_APPROVED => Yii::t('CommentsModule', 'Подтвержден'),
			self::STATUS_SPAM     => Yii::t('CommentsModule', 'Спам'),
		);
	}

	/**
	 * @return string status title
	 */
	public function getStatusTitle()
	{
		$statuses = self::getStatuses();
		return $statuses[$this->status];
	}

	/**
	 * @return string
	 */
	public function getOwner_title()
	{
		if(!$this->isNewRecord)
		{
			try{
				$className = Yii::import($this->class_name, true);
			}catch(CException $e){
				return null;
			}

      if ($this->object_pk) {
        $model = $className::model()->findByPk($this->object_pk);
        if($model)
          return $model->getOwnerTitle();
      } else {
        switch ($this->class_name) {
          case Comment::CLASS_SITE:
            return Yii::t('CommentsModule', '< Отзыв о сайте >');
        }
      }
		}
		return '';
	}

	public static function truncate(Comment $model, $limit)
	{
		$result = $model->text;
		$length = mb_strlen($result,'utf-8');
		if($length > $limit)
		{
			return mb_substr($result,0,$limit,'utf-8').'...';
		}
		return $result;
	}

	/**
	 * Load object comments
	 * @static
	 * @param CActiveRecord $model
	 * @return array
	 */
	public static function getObjectComments(CActiveRecord $model)
	{
		return Comment::model()
			->approved()
			->orderByCreatedAsc()
			->findAllByAttributes(array(
				'class_name'=>$model->getClassName(),
				'object_pk'=>$model->id
		));
	}
}