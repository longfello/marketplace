<?php

/**
 * Display currency list
 **/

$this->pageHeader = Yii::t('StoreModule', 'Валюты');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('StoreModule', 'Валюты'),
);

$this->topButtons = $this->widget('admin.widgets.SAdminTopButtons', array(
	'template'=>array('create'),
	'elements'=>array(
		'create'=>array(
			'link'=>$this->createUrl('create'),
			'title'=>Yii::t('StoreModule', 'Создать валюту'),
			'options'=>array(
				'icons'=>array('primary'=>'ui-icon-plus')
			)
		),
	),
));

$this->widget('ext.sgridview.SGridView', array(
	'dataProvider'=>$dataProvider,
	'id'=>'productsListGrid',
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
			'value'=>'CHtml::link(CHtml::encode($data->name), array("/store/admin/currency/update", "id"=>$data->id))',
		),
		'rate',
		'symbol',
		array(
			'name'=>'main',
			'filter'=>array(1=>Yii::t('StoreModule', 'Да'), 0=>Yii::t('StoreModule', 'Нет')),
			'value'=>'$data->main ? Yii::t("StoreModule", "Да") : Yii::t("StoreModule", "Нет")'
		),
		array(
			'name'=>'default',
			'filter'=>array(1=>Yii::t('StoreModule', 'Да'), 0=>Yii::t('StoreModule', 'Нет')),
			'value'=>'$data->default ? Yii::t("StoreModule", "Да") : Yii::t("StoreModule", "Нет")'
		),
		// Buttons
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
));