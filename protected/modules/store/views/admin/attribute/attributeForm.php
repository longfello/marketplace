<?php

return array(
	'id'=>'attributeUpdateForm',
	'showErrorSummary'=>true,
	'elements'=>array(
		'content'=>array(
			'type'=>'form',
			'title'=>Yii::t('StoreModule', 'Параметры'),
			'elements'=>array(
				'title'=>array(
					'type'=>'text',
				),
				'name'=>array(
					'type'=>'text',
					'hint'=>Yii::t('StoreModule', 'Укажите уникальный идентификатор')
				),
					'required'=>array(
						'type'=>'checkbox',
					),
				'type'=>array(
					'type'=>'dropdownlist',
					'items'=>StoreAttribute::getTypesList()
				),
				'display_on_front'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						1=>Yii::t('StoreModule', 'Да'),
						0=>Yii::t('StoreModule', 'Нет')
					),
				),
				'use_in_filter'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						1=>Yii::t('StoreModule', 'Да'),
						0=>Yii::t('StoreModule', 'Нет'),
					),
					'hint'=>Yii::t('StoreModule', 'Использовать свойство для поиска продуктов')
				),
				'select_many'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						0=>Yii::t('StoreModule', 'Нет'),
						1=>Yii::t('StoreModule', 'Да')
					),
					'hint'=>Yii::t('StoreModule', 'Позволяет искать продукты по более чем одному параметру одновременно')
				),
				'use_in_variants'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						0=>Yii::t('StoreModule', 'Нет'),
						1=>Yii::t('StoreModule', 'Да'),
					),
				),
				'use_in_compare'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						1=>Yii::t('StoreModule', 'Да'),
						0=>Yii::t('StoreModule', 'Нет'),
					),
				),
				'position'=>array(
					'type'=>'text',
				),
			),
		),
	),
);