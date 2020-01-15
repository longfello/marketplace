<?php

Yii::import('application.modules.discounts.DiscountsModule');

/**
 * Admin menu items for discounts module
 */
return array(
	'discounts'=>array(
		'label'=>Yii::t('DiscountsModule', 'Скидки'),
		'url'=>Yii::app()->createUrl('/discounts/admin/default'),
		'position'=>4,
    'ManagerAccess' => true,
		'items'=>array(
			array(
				'label'=>Yii::t('DiscountsModule', 'Все скидки'),
				'url'=>Yii::app()->createUrl('/discounts/admin/default'),
				'position'=>1,
        'ManagerAccess' => true
			),
		),
	),
);