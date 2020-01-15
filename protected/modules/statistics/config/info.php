<?php

Yii::import('application.modules.statistics.StatisticsModule');

/**
 * Module info
 */
return array(
	'name'        => Yii::t('StatisticsModule', 'Статистика'),
	'author'      => 'firstrow@gmail.com',
	'version'     => '0.1',
	'description' => Yii::t('StatisticsModule', 'Модуль статистики.'),
	'config_url'  => Yii::app()->createUrl('/statistics/admin/default'),
	'url'         => '',
);