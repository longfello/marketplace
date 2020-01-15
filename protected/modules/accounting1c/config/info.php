<?php

Yii::import('application.modules.accounting1c.Accounting1cModule');

/**
 * Module info
 */ 
return array(
	'name'        => Yii::t('Accounting1cModule', 'Поддержка 1С бухгалтерии.'),
	'author'      => 'firstrow@gmail.com',
	'version'     => '0.1',
	'config_url'  => Yii::app()->createUrl('/accounting1c/admin/default/index'),
	'description' => Yii::t('Accounting1cModule', 'Обмен товарами.'),
	'url'         => '',
);