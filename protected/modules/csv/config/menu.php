<?php

Yii::import('application.modules.csv.CsvModule');

return array(
	'csv'=>array(
		'label'=>Yii::t('CsvModule', 'Автоматизация'),
		'position'=>5,
		'items'=>array(
			array(
				'label'=>Yii::t('CsvModule', 'Импорт'),
				'url'=>Yii::app()->createUrl('/csv/admin/default/import'),
				'position'=>1
			),
			array(
				'label'=>Yii::t('CsvModule', 'Экспорт'),
				'url'=>Yii::app()->createUrl('/csv/admin/default/export'),
				'position'=>2
			),
		),
	),
);