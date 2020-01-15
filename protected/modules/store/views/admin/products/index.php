<?php

/**
 * Display products list
 * @var StoreProduct $model
 **/

$this->pageHeader = Yii::t('StoreModule', 'Продукты');

$this->sidebarContent = $this->renderPartial('_sidebar', array(), true);

// Register scripts
Yii::app()->clientScript->registerScriptFile(
	$this->module->assetsUrl.'/admin/products.index.js',
	CClientScript::POS_END
);

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('StoreModule', 'Продукты'),
);

$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
	'template'=>array('create', 'showSidebar'),
	'elements'=>array(
		'create'=>array(
			'link'=>$this->createUrl('create'),
			'title'=>Yii::t('StoreModule', 'Создать продукт'),
			'options'=>array(
				'icons'=>array('primary'=>'ui-icon-plus')
			)
		),
		'showSidebar'=>array(
			'link'=>'#',
			'title'=>Yii::t('StoreModule', 'Переключить сайдбар'),
			'onclick'=>'js:function(){productToggleSidebar()}',
			'options'=>array(
				'text'=>false,
				'icons'=>array('primary'=>'ui-icon-triangle-2-e-w')
			)
		),
	),
));

$this->widget('ext.sgridview.SGridView', array(
	'dataProvider'  => $dataProvider,
	'id'            => 'productsListGrid',
	'ajaxUpdate'    => true,
	'filter'        => $model,
	'customActions' => array(
		array(
			'label'=>Yii::t('StoreModule', 'Активен'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setProductsStatus(1, this);',
			),
		),
		array(
			'label'=>Yii::t('StoreModule', 'Не активен'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setProductsStatus(0, this);',
			),
		),
		array(
			'label'=>Yii::t('StoreModule', 'Назначить категории'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return showCategoryAssignWindow(this);',
			),
		),
		array(
			'label'=>Yii::t('StoreModule', 'Копировать'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return showDuplicateProductsWindow(this);',
			),
		)
	 ),
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
			'value'=>'CHtml::link(CHtml::encode($data->name), array("/store/admin/products/update", "id"=>$data->id))',
		),
//		array(
//			'name'=>'url',
//			'type'=>'raw',
//			'value'=>'CHtml::link(CHtml::encode($data->url), array("/store/frontProduct/view", "url"=>$data->url), array("target"=>"_blank"))',
//		),
		'sku',
		'price',
		array(
			'name'   => 'manufacturer_id',
			'type'   => 'raw',
			'value'  => '$data->manufacturer ? CHtml::encode($data->manufacturer->name) : ""',
			'filter' => CHtml::listData(StoreManufacturer::model()->orderByName()->findAll(),'id', 'name')
		),
		array(
			'name'=>'type_id',
			'type'=>'raw',
			'value'=>'CHtml::encode($data->type->name)',
			'filter'=>CHtml::listData(StoreProductType::model()->findAll(), "id", "name"),
		),
		array(
			'name'=>'is_active',
			'filter'=>array(1=>Yii::t('StoreModule', 'Да'), 0=>Yii::t('StoreModule', 'Нет')),
			'value'=>'$data->is_active ? Yii::t("StoreModule", "Да") : Yii::t("StoreModule", "Нет")'
		),
    array(
      'name'   => 'market_id',
      'type'   => 'raw',
      'value'  => '$data->market ? CHtml::encode($data->market->name) : ""',
      'filter' => CHtml::listData(StoreMarket::model()->orderByName()->findAll(),'id', 'name')
    ),
    // Buttons
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
));