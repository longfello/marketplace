<?php

Yii::import('orders.OrdersModule');

/**
 * Admin menu items for pages module
 */
return array(
	'orders'=>array(
		'label'       => Yii::t('OrdersModule', 'Заказы'),
		'url'         => array('/orders/admin/orders'),
		'position'    => 2,
    'ManagerAccess' => true,
		'itemOptions' => array(
			'class'       => 'hasRedCircle circle-orders',
		),
		'items' => array(
			array(
				'label'       => Yii::t('OrdersModule', 'Все заказы'),
				'url'         => array('/orders/admin/orders'),
				'position'    => 1,
        'ManagerAccess' => true
			),
			array(
				'label'    => Yii::t('OrdersModule', 'Создать заказ'),
				'url'      => array('/orders/admin/orders/create'),
				'position' => 2,
        'ManagerAccess' => true
			),
			array(
				'label'    => Yii::t('OrdersModule', 'Статусы'),
				'url'      => array('/orders/admin/statuses'),
				'position' => 3
			)
		),
	),
);