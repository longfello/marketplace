<?php

Yii::import('application.modules.logger.LoggerModule');

/**
 * Saves admin logs
 * This is the model class for table "ActionLog".
 *
 * The followings are the available columns in table 'ActionLog':
 * @property integer $id
 * @property string $username
 * @property string $event
 * @property string $model_name
 * @property string $model_title
 * @property string $datetime
 */
class ActionLog extends BaseModel
{

	/**
	 * Actions
	 */
	const ACTION_CREATE = 1;
	const ACTION_UPDATE = 2;
	const ACTION_DELETE = 3;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ActionLog the static model class
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
		return 'ActionLog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('id, username, event, model_name, model_title, datetime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => Yii::t('LoggerModule', 'Пользователь'),
			'event' => Yii::t('LoggerModule', 'Действие'),
			'model_name' => Yii::t('LoggerModule', 'Обьект'),
			'model_title' => Yii::t('LoggerModule', 'Название'),
			'datetime' => Yii::t('LoggerModule', 'Дата'),
		);
	}

	/**
	 * @return mixed
	 */
	public function getActionTitle()
	{
		if($this->event)
		{
			return $this->eventNames[$this->event];
		}
	}

	/**
	 * @return array
	 */
	public static function getEventNames()
	{
		return array(
			self::ACTION_CREATE=>Yii::t('LoggerModule', 'Создание'),
			self::ACTION_UPDATE=>Yii::t('LoggerModule', 'Обновление'),
			self::ACTION_DELETE=>Yii::t('LoggerModule', 'Удаление'),
		);
	}

	public function getHumanModelName()
	{
		return $this->logClasses[$this->model_name]['human_name'];
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getLogClasses()
	{
		return array(
			'StoreProduct'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Продукт')
			),
			'StoreCategory'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Категория')
			),
			'StoreManufacturer'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Производитель')
			),
			'StoreAttribute'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Атрибут')
			),
			'StoreProductType'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Тип продукта')
			),
			'StoreDeliveryMethod'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Доставка')
			),
			'StorePaymentMethod'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Оплата')
			),
			'StoreCurrency'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Валюта')
			),
			'Discount'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Скидка')
			),
			'Order'=>array(
				'title_attribute'=>'id',
				'human_name'=>Yii::t('LoggerModule', 'Заказ')
			),
			'OrderStatus'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Статус заказа')
			),
			'User'=>array(
				'title_attribute'=>'username',
				'human_name'=>Yii::t('LoggerModule', 'Пользователь')
			),
			'SSystemLanguage'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Язык')
			),
			'SystemModules'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Модуль')
			),
			'Page'=>array(
				'title_attribute'=>'title',
				'human_name'=>Yii::t('LoggerModule', 'Страница')
			),
			'PageCategory'=>array(
				'title_attribute'=>'name',
				'human_name'=>Yii::t('LoggerModule', 'Категория страниц')
			),
			'Comment'=>array(
				'title_attribute'=>'text',
				'human_name'=>Yii::t('LoggerModule', 'Комментарий')
			),
		);
	}

	public function getModelNameFilter()
	{
		$result = array();
		foreach($this->logClasses as $class=>$data)
			$result[$class]=$data['human_name'];
		return $result;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('event',$this->event,true);
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('model_title',$this->model_title,true);
		$criteria->compare('datetime',$this->datetime,true);

		$sort = new CSort;
		$sort->defaultOrder = 't.datetime DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}