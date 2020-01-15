<?php

Yii::import('application.modules.notifier.NotifierModule');

/**
 * Notifications module info
 */ 
return array(
	'name'        => Yii::t('NotifierModule', 'Сообщить о появлении'),
	'author'      => 'firstrow@gmail.com',
	'version'     => '0.2',
	'description' => Yii::t('NotifierModule', 'Помогает рассылать сообщения пользователям, когда продукт появился в наличии.'),
	'config_url'  => Yii::app()->createUrl('/notifier/admin/default'),
	'url'         => '',
);
