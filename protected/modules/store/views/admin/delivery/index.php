<?php

/**
 * Display delivery methods list
 **/

$this->pageHeader = Yii::t('StoreModule', 'Способы доставки');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('StoreModule', 'Способы доставки'),
);

$this->topButtons = $this->widget('admin.widgets.SAdminTopButtons', array(
	'template'=>array('create'),
	'elements'=>array(
		'create'=>array(
			'link'=>$this->createUrl('create'),
			'title'=>Yii::t('StoreModule', 'Создать способ доставки'),
			'options'=>array(
				'icons'=>array('primary'=>'ui-icon-plus')
			)
		),
	),
));

$this->widget('ext.sgridview.SGridView', array(
	'dataProvider'=>$dataProvider,
	'id'=>'deliveryMethodsListGrid',
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
			'value'=>'CHtml::link(CHtml::encode($data->name), array("/store/admin/delivery/update", "id"=>$data->id))',
		),
		'price',
		'free_from',
		'position',
		array(
      'name' => 'market_id',
      'value' => 'StoreMarket::model()->findByPk($data->market_id)->name',
      'filter' => StoreMarket::model()->getFilter()
    ),
		array(
			'name'=>'active',
			'filter'=>array(1=>Yii::t('StoreModule', 'Да'), 0=>Yii::t('StoreModule', 'Нет')),
			'value'=>'$data->active ? Yii::t("StoreModule", "Да") : Yii::t("StoreModule", "Нет")'
		),
		// Buttons
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
));