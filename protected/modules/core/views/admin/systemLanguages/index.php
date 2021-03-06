<?php

	/** Display pages list **/
	$this->pageHeader = Yii::t('CoreModule', 'Языки');

	$this->breadcrumbs = array(
		'Home'=>$this->createUrl('/admin'),
		Yii::t('CoreModule', 'Языки'),
	);

	$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
		'template'=>array('create'),
		'elements'=>array(
			'create'=>array(
				'link'=>$this->createUrl('create'),
				'title'=>Yii::t('CoreModule', 'Создать язык'),
				'icon'=>'plus',
			),
		),
	));

	$this->widget('ext.sgridview.SGridView', array(
		'dataProvider'=>$model->search(),
		'id'=>'languagesListGrid',
		'filter'=>$model,
		//'ajaxUpdate'=>false,
		'columns'=>array(
			array(
				'class'=>'CCheckBoxColumn',
			),
			array(
				'class'=>'SGridIdColumn',
				'name'=>'id',
			),
			array(
				'name'=>'name',
				'type'=>'raw',
				'value'=>'CHtml::link(CHtml::encode($data->name), array("update", "id"=>$data->id))',
			),
			array(
				'name'=>'default',
				'type'=>'text',
				'filter'=>array(
					'1'=>Yii::t('CoreModule', 'Да'),
					'0'=>Yii::t('CoreModule', 'Нет')
				),
				'value'=>strtr('$data->default ? "{yes}":"{no}"', array(
					'{yes}'=>Yii::t('CoreModule', 'Да'),
					'{no}'=>Yii::t('CoreModule', 'Нет')
				)),
			),
			'code',
			'locale',
			// Buttons
			array(
				'class'=>'CButtonColumn',
				'template'=>'{update}{delete}',
			),
		),
	));
