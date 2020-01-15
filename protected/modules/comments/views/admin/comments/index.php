<?php

/**
 * Display comments list
 *
 * @var $model Comment
 **/

Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/admin/comments.index.js');

$this->pageHeader = Yii::t('CommentsModule', 'Комментарии');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('CommentsModule', 'Комментарии'),
);

$this->widget('ext.sgridview.SGridView', array(
	'dataProvider' => $dataProvider,
	'id'           => 'commentsListGrid',
	'filter'       => $model,
	'customActions'=>array(
		array(
			'label'=>Yii::t('CommentsModule', 'Подтвержден'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setCommentsStatus(1, this);',
			)
		),
		array(
			'label'=>Yii::t('CommentsModule', 'Ждет одобрения'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setCommentsStatus(0, this);',
			)
		),
		array(
			'label'=>Yii::t('CommentsModule', 'Спам'),
			'url'=>'#',
			'linkOptions'=>array(
				'onClick'=>'return setCommentsStatus(2, this);',
			)
		),
	),
	'columns' => array(
		array(
			'class'=>'CCheckBoxColumn',
		),
		array(
			'class'=>'SGridIdColumn',
			'name'=>'id',
		),
		array(
			'name'  => 'name',
			'type'  => 'raw',
			'value' => 'CHtml::link(CHtml::encode($data->name), array("update", "id"=>$data->id))',
		),
		array(
			'name'=>'email',
		),
		array(
			'name'=>'text',
			'value'=>'Comment::truncate($data, 100)'
		),
		array(
			'name'=>'status',
			'filter'=>Comment::getStatuses(),
			'value'=>'$data->statusTitle',
		),
		array(
			'name'=>'owner_title',
			'filter'=>false
		),
		'ip_address',
		array(
			'name'=>'created',
		),
		// Buttons
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
));