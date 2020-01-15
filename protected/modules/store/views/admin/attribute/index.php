<?php

/**
 * Display attributes list
 **/

$this->pageHeader = Yii::t('StoreModule', 'Атрибуты');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('StoreModule', 'Атрибуты'),
);

$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
	'template'=>array('create'),
	'elements'=>array(
		'import'=>array(
			'link'=>$this->createUrl('/admin/store/import/assignProductOptions'),
			'title'=>Yii::t('StoreModule', 'Настройки импорта'),
			'options'=>array(
				'icons'=>array('primary'=>'ui-icon-document')
			)
		),
		'create'=>array(
			'link'=>$this->createUrl('create'),
			'title'=>Yii::t('StoreModule', 'Создать атрибут'),
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
			'name'=>'title',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->title), array("/store/admin/attribute/update", "id"=>$data->id))',
		),
		'name',
		array(
			'name'=>'type',
			'filter'=>StoreAttribute::getTypesList(),
			'value'=>'CHtml::encode(StoreAttribute::getTypeTitle($data->type))'
		),
		'position',
		// Buttons
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
));