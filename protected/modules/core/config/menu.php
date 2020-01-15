<?php

// Import module to make translation available.
Yii::import('application.modules.core.CoreModule');

/**
 * Admin menu items for core module
 */
return array(
	'users'=>array(
		'items'=>array(
			array(
				'label'=>Yii::t('CoreModule', 'Настройки'),
				'url'=>Yii::app()->createUrl('core/admin/systemSettings'),
				'position'=>0
			),
			array(
				'label'=>Yii::t('CoreModule', 'Модули'),
				'url'=>Yii::app()->createUrl('core/admin/systemModules'),
				'position'=>3
			),
			array(
				'label'=>Yii::t('CoreModule', 'Языки'),
				'url'=>Yii::app()->createUrl('core/admin/systemLanguages'),
				'position'=>4
			),
			array(
				'label'=>Yii::t('CoreModule', 'Обработчики задач'),
				'url'=>Yii::app()->createUrl('core/admin/gearman'),
				'position'=>5
			),
			array(
				'label'=>Yii::t('CoreModule', 'Города'),
				'url'=>Yii::app()->createUrl('core/admin/netCity'),
				'position'=>6
			),
			array(
				'label'=>Yii::t('CoreModule', 'Области'),
				'url'=>Yii::app()->createUrl('core/admin/netRegions'),
				'position'=>7
			),
			array(
				'label'=>Yii::t('CoreModule', 'Страны'),
				'url'=>Yii::app()->createUrl('core/admin/netCountry'),
				'position'=>8
			),
			array(
				'label'=>Yii::t('CoreModule', 'Сброс кеша'),
				'url'=>Yii::app()->createUrl('core/admin/clearCache'),
				'position'=>9
			),
		),
	),
);