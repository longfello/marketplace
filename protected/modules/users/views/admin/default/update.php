<?php
	// User create/edit view

	$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
		'form'=>$form,
		'deleteAction'=>$this->createUrl('/users/admin/default/delete', array('id'=>$model->id))
	));

	$title = ($model->isNewRecord) ? Yii::t('UsersModule', 'Создание пользователя') : Yii::t('UsersModule', 'Редактирование пользователя');

	$this->breadcrumbs = array(
		'Home'=>$this->createUrl('/admin'),
		Yii::t('UsersModule', 'Пользователи')=>$this->createUrl('index'),
		($model->isNewRecord) ? Yii::t('UsersModule', 'Создание пользователя') : CHtml::encode($model->username),
	);

	$this->pageHeader = $title;
	$this->sidebarContent = $this->renderPartial('_sidebar', array(
		'model'=>$model,
	), true);
?>

<div class="form wide padding-all">
	<?php echo $form; ?>
</div>

