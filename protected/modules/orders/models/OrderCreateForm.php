<?php

Yii::import('store.models.StoreDeliveryMethod');

/**
 * Used in cart to create new order.
 */
class OrderCreateForm extends CFormModel
{
	public $name;
	public $email;
	public $phone;
	public $address;
	public $comment;
	public $delivery_id;

	public function init()
	{
		if(!Yii::app()->user->isGuest)
		{
			$profile=Yii::app()->user->getModel()->profile;
			$this->name=$profile->full_name;
			$this->phone=$profile->phone;
			$this->address=$profile->delivery_address;
			$this->email=Yii::app()->user->email;
		}
	}

	/**
	 * Validation
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('name, email', 'required'),
			array('email', 'email'),
			array('comment', 'length', 'max'=>'500'),
			array('address', 'length', 'max'=>'255'),
			array('email', 'length', 'max'=>'100'),
			array('phone', 'length', 'max'=>'30'),
			array('delivery_id', 'validateDelivery'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'name'        => Yii::t('OrdersModule', 'Имя'),
			'email'       => Yii::t('OrdersModule', 'Email'),
			'comment'     => Yii::t('OrdersModule', 'Комментарий'),
			'address'     => Yii::t('OrdersModule', 'Адрес доставки'),
			'phone'       => Yii::t('OrdersModule', 'Номер телефона'),
			'delivery_id' => Yii::t('OrdersModule', 'Способ доставки'),
		);
	}

	/**
	 * Check if delivery method exists
	 */
	public function validateDelivery()
	{
    $did = Yii::app()->request->getPost('delivery_id');
    $did = is_array($did) && $did ? $did : array(0);
    $ok = 1;

    foreach($did as $one) {
      $ok *= $one;
    }
		// if(StoreDeliveryMethod::model()->countByAttributes(array('id'=>$this->delivery_id)) == 0)
		if($ok == 0)
			$this->addError('delivery_id', Yii::t('OrdersModule', 'Необходимо выбрать способ доставки.'));
	}
}
