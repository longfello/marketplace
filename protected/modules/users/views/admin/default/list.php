<?php
	// Display list of users

	$this->pageHeader = Yii::t('UsersModule', 'Список пользователей');

	$this->breadcrumbs = array(
		'Home'=>$this->createUrl('/admin'),
		Yii::t('UsersModule', 'Пользователи'),
	);

	$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
		'template'=>array('new'),
		'elements'=>array(
			'new'=>array(
				'link'=>$this->createUrl('create'),
				'title'=>Yii::t('UsersModule', 'Создать пользователя'),
				'options'=>array(
					'icons'=>array('primary'=>'ui-icon-person')
				)
			),
		),
	));

	$this->widget('ext.sgridview.SGridView', array(
		'dataProvider'=>$dataProvider,
		'id'=>'usersListGrid',
		'filter'=>$model,
		'columns'=>array(
			array(
				'class'=>'SGridIdColumn',
				'name'=>'id',
			),
			array(
				'name'=>'username',
				'type'=>'raw',
				'value'=>'CHtml::link(CHtml::encode($data->username),array("update","id"=>$data->id))',
			),
			array(
        'name' => 'email',
        'type' => 'raw',
        'value' => 'CHtml::link($data->email,"mailto://".$data->email)'
      ),
			'discount',
			array(
				'name'=>'created_at',
			),
			array(
				'name'=>'banned',
				'filter'=>array(1=>Yii::t('UsersModule', 'Да'), 0=>Yii::t('UsersModule', 'Нет')),
				'value'=>'$data->banned ? Yii::t("UsersModule", "Да") : Yii::t("UsersModule", "Нет")'
			),
			array(
				'name'=>'last_login',
			),
			array(
				'class'=>'CButtonColumn',
				'template'=>'{update}{delete}',
			),
		),
	));

