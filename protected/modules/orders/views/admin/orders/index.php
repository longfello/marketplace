<?php

Yii::import('orders.components.SProductsPreviewColumn');

/**
 * Display orders list
 **/

$this->pageHeader = Yii::t('OrdersModule', 'Заказы');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('OrdersModule', 'Заказы'),
);

$this->topButtons = $this->widget('admin.widgets.SAdminTopButtons', array(
	'template'=>array('create'),
	'elements'=>array(
		'create'=>array(
			'link'=>$this->createUrl('create'),
			'title'=>Yii::t('OrdersModule', 'Создать заказ'),
			'options'=>array(
				'icons'=>array('primary'=>'ui-icon-cart')
			)
		),
	),
));

$this->widget('ext.sgridview.SGridView', array(
	'dataProvider'=>$dataProvider,
	'id'=>'ordersListGrid',
	'filter'=>$model,
	'columns'=>array(
		array(
			'class'=>'CCheckBoxColumn',
		),
		array(
			'class'=>'SGridIdColumn',
			'name'=>'id'
		),
		array(
			'name'=>'user_name',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->user_name), array("/orders/admin/orders/update", "id"=>$data->id))',
		),
		'user_email',
		'user_phone',
		array(
			'name'=>'status_id',
			'filter'=>CHtml::listData(OrderStatus::model()->orderByPosition()->findAll(), 'id', 'name'),
			'value'=>'$data->status_name'
		),
		array(
			'name'=>'delivery_id',
			'filter'=>CHtml::listData(StoreDeliveryMethod::model()->orderByPosition()->findAll(), 'id', 'name'),
			'value'=>'$data->delivery_name'
		),
		array(
			'class'=>'SProductsPreviewColumn'
		),
		array(
			'type'=>'raw',
			'name'=>'full_price',
			'value'=>'StoreProduct::formatPrice($data->full_price)',
		),
		'created',
		// Buttons
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
));
