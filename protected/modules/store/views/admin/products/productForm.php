<?php

return array(
	'id'=>'productUpdateForm',
	'showErrorSummary'=>true,
	'enctype'=>'multipart/form-data',
	'elements'=>array(
		'content'=>array(
			'type'=>'form',
			'title'=>Yii::t('StoreModule', 'Общая информация'),
			'elements'=>array(
				'name'=>array(
					'type'=>'text',
				),
				'price'=>array(
					'type'=>$this->model->use_configurations ? 'hidden' : 'text',
				),
				'url'=>array(
					'type'=>'text',
				),
				'main_category_id'=>array(
					'type'=>'dropdownlist',
					'items'=>StoreCategory::flatTree(),
					'empty'=>'---',
				),
				'is_active'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						1=>Yii::t('StoreModule', 'Да'),
						0=>Yii::t('StoreModule', 'Нет')
					),
					'hint'=>Yii::t('StoreModule', 'Отображать товар на сайте')
				),
				'manufacturer_id'=>array(
					'type'=>'dropdownlist',
					'items'=>CHtml::listData(StoreManufacturer::model()->findAll(), 'id', 'name'),
					'empty'=>Yii::t('StoreModule', 'Выберите производителя'),
				),
				'short_description'=>array(
					'type'=>'SRichTextarea',
				),
				'full_description'=>array(
					'type'=>'SRichTextarea',
				),
			),
		),
		'warehouse'=>array(
			'type'=>'form',
			'title'=>Yii::t('StoreModule', 'Склад'),
			'elements'=>array(
				'sku'=>array(
					'type'=>'text',
				),
				'quantity'=>array(
					'type'=>'text',
				),
				'discount'=>array(
					'type'=>'text',
					'hint'=>Yii::t('StoreModule', 'Укажите целое число или процент. Например 10%.'),
				),
				'auto_decrease_quantity'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						1=>Yii::t('StoreModule', 'Да'),
						0=>Yii::t('StoreModule', 'Нет')
					),
					'hint'=>Yii::t('StoreModule', 'Автоматически уменьшать количество при создании заказа'),
				),
				'availability'=>array(
					'type'=>'dropdownlist',
					'items'=>StoreProduct::getAvailabilityItems()
				),
			),
		),
		'seo'=>array(
			'type'=>'form',
			'title'=>Yii::t('StoreModule', 'Мета данные'),
			'elements'=>array(
				'meta_title'=>array(
					'type'=>'text',
				),
				'meta_keywords'=>array(
					'type'=>'textarea',
				),
				'meta_description'=>array(
					'type'=>'textarea',
				),
			),
		),
		'design'=>array(
			'type'=>'form',
			'title'=>Yii::t('StoreModule', 'Дизайн'),
			'elements'=>array(
				'layout'=>array(
					'type'=>'text',
					'hint'=>Yii::t('StoreModule', 'Пример: application.views.layouts.file_name'),
				),
				'view'=>array(
					'type'=>'text',
					'hint'=>Yii::t('StoreModule', 'Пример: view_name')
				),
			),
		),
	),
);

