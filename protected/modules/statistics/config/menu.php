<?php

Yii::import('application.modules.statistics.StatisticsModule');

return array(
	'statistics'=>array(
		'label'    => Yii::t('StatisticsModule', 'Статистика'),
		'url'      => Yii::app()->createUrl('/statistics/admin/default'),
		'position' => 10,
    'ManagerAccess' => true,
		'items'=>array(
			array(
				'label'    => Yii::t('StatisticsModule', 'Заказы'),
				'url'      => Yii::app()->createUrl('/statistics/admin/default'),
				'position' => 1,
        'ManagerAccess' => true
			)
		),
	),
);