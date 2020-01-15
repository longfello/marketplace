<?php

Yii::import('application.modules.store.models.StoreManufacturer');
Yii::import('application.modules.discounts.components.DiscountHelper');
Yii::import('zii.widgets.jui.CJuiDatePicker');

return array(
	'id'=>'discountUpdateForm',
	'elements'=>array(
		'common_info'=>array(
			'type'=>'form',
			'title'=>Yii::t('DiscountsModule', 'Общая информация'),
			'elements'=>array(
				'name'=>array(
					'type'=>'text',
				),
				'active'=>array(
					'type'=>'checkbox',
				),
				'sum'=>array(
					'type'=>'text',
					'hint'=>Yii::t('DiscountsModule', 'Укажите целое число или процент. Например 10%.'),
				),
				'start_date'=>array(
					'type'=>'CJuiDatePicker',
					'options'=>array(
						'dateFormat'=>'yy-mm-dd '.date('H:i:s'),
					),
				),
				'end_date'=>array(
					'type'=>'CJuiDatePicker',
					'options'=>array(
						'dateFormat'=>'yy-mm-dd '.date('H:i:s'),
					),
				),
				'manufacturers'=>array(
					'type'=>'dropdownlist',
					'items'=>CHtml::listData(StoreManufacturer::model()->orderByName()->findAll(), 'id', 'name'),
					'multiple'=>'multiple',
					'data-placeholder'=>Yii::t('DiscountsModule', 'Выберите производителя'),
				),
				'userRoles'=>array(
					'type'=>'dropdownlist',
					'items'=>DiscountHelper::getRoles(),
					'multiple'=>'multiple',
					'data-placeholder'=>Yii::t('DiscountsModule', 'Выберите роли'),
					'hint'=>Yii::t('DiscountsModule', '<b>Внимание:</b> Скидки для администраторов запрещены.'),
				),
			)
		)
	),
);

