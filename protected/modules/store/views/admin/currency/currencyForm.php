<?php

/**
 * Currency form
 */
return array(
	'id'=>'currencyUpdateForm',
	'elements'=>array(
		'tab1'=>array(
			'type'=>'form',
			'title'=>'',
			'elements'=>array(
				'name'=>array(
					'type'=>'text',
				),
				'main'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						0=>Yii::t('StoreModule', 'Нет'),
						1=>Yii::t('StoreModule', 'Да')
					),
					'hint'=>Yii::t('StoreModule', 'Все цены на сайте указаны в этой валюте.')
				),
				'default'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						0=>Yii::t('StoreModule', 'Нет'),
						1=>Yii::t('StoreModule', 'Да')
					),
					'hint'=>Yii::t('StoreModule', 'Валюта будет назначена пользователю при первом посещении сайта.')
				),
				'iso'=>array(
					'type'=>'text',
				),
				'symbol'=>array(
					'type'=>'text',
				),
				'rate'=>array(
					'type'=>'text',
					'hint'=>Yii::t('StoreModule', 'Курс по отношению к главной валюте сайта.')
				),
			),
		),
	),
);
