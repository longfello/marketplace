<?php

Yii::import('application.modules.notifier.NotifierModule');

return array(
		'notifier'=>array(
			'label'    => Yii::t('NotifierModule', 'Уведомления'),
			'url'      => array('/admin/notifier'),
			'position' => 9,
      'ManagerAccess' => true,
		),
);