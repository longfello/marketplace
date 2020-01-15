<?php

/**
 * Display products list
 **/

$this->pageHeader = Yii::t('DiscountsModule', 'Скидки');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('DiscountsModule', 'Скидки'),
);

$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
	'template'=>array('create'),
	'elements'=>array(
		'create'=>array(
			'link'=>$this->createUrl('create'),
			'title'=>Yii::t('DiscountsModule', 'Создать скидку'),
			'options'=>array(
				'icons'=>array('primary'=>'ui-icon-plus')
			)
		),
	),
));

$this->widget('ext.sgridview.SGridView', array(
	'dataProvider'=>$dataProvider,
	'id'=>'discountsListGrid',
	'afterAjaxUpdate'=>"function(){registerDatePickers()}",
	'filter'=>$model,
	'columns'=>array(
		array(
			'class'=>'CCheckBoxColumn',
		),
		array(
			'class'=>'SGridIdColumn',
			'name'=>'id'
		),
		array(
			'name'=>'name',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->name), array("/discounts/admin/default/update", "id"=>$data->id))',
		),
		'sum',
		array(
			'name'=>'active',
			'filter'=>array(1=>Yii::t('DiscountsModule', 'Да'), 0=>Yii::t('DiscountsModule', 'Нет')),
			'value'=>'$data->active ? Yii::t("DiscountsModule", "Да") : Yii::t("DiscountsModule", "Нет")'
		),
		'start_date',
		'end_date',
		// Buttons
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
));

Yii::app()->clientScript->registerScript("discountDatepickers", "
function registerDatePickers(){
    jQuery('input[name=\"Discount[start_date]\"]').datepicker({'dateFormat':'yy-mm-dd',});
    jQuery('input[name=\"Discount[end_date]\"]').datepicker({'dateFormat':'yy-mm-dd',});
}
registerDatePickers();
");