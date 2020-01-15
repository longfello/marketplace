<?php

Yii::import('application.modules.store.StoreModule');

/**
 * Admin menu items for store module
 */
return array(
	'catalog'=>array(
		'position'=>3,
    'ManagerAccess' => true,
    'itemOptions' => array(
      'class'       => 'hasRedCircle circle-catalog',
    ),
		'items'=>array(
			array(
				'label'=>Yii::t('StoreModule', 'Продукты'),
				'url'=>Yii::app()->createUrl('store/admin/products'),
				'position'=>1,
        'ManagerAccess' => true
			),
			array(
				'label'=>Yii::t('StoreModule', 'Категории'),
				'url'=>Yii::app()->createUrl('store/admin/category/create'),
				'position'=>2
			),
			array(
				'label'=>Yii::t('StoreModule', 'Производители'),
				'url'=>Yii::app()->createUrl('store/admin/manufacturer'),
				'position'=>3,
			),
			array(
				'label'=>Yii::t('StoreModule', 'Атрибуты'),
				'url'=>Yii::app()->createUrl('store/admin/attribute'),
				'position'=>4,
			),
			array(
				'label'=>Yii::t('StoreModule', 'Типы продуктов'),
				'url'=>Yii::app()->createUrl('store/admin/productType'),
				'position'=>5,
			),
			array(
				'label'=>Yii::t('StoreModule', 'Доставка'),
				'url'=>Yii::app()->createUrl('store/admin/delivery'),
				'position'=>6,
        'ManagerAccess' => true
			),
			array(
				'label'=>Yii::t('StoreModule', 'Оплата'),
				'url'=>Yii::app()->createUrl('store/admin/paymentMethod'),
				'position'=>7
			),
			array(
				'label'=>Yii::t('StoreModule', 'Валюты'),
				'url'=>Yii::app()->createUrl('store/admin/currency'),
				'position'=>8
			),
			array(
				'label'=>Yii::t('StoreModule', 'Импорт'),
				'url'=>Yii::app()->createUrl('store/admin/import'),
				'position'=>9,
        'ManagerAccess' => true,
        'itemOptions' => array(
          'class' => 'store-admin-import-menu-item'
        ),
			),
      array(
        'label'=>Yii::t('StoreModule', 'Магазин'),
        'url'=>Yii::app()->createUrl('store/admin/market'),
        'position'=>10,
        'ManagerAccess' => true,
        'itemOptions' => array(
          'class' => 'store-admin-market-menu-item'
        ),
      ),
		),
	),
);