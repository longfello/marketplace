<?php

/**
 * Create/update attribute
 */

	$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
		'form'=>$form,
		'langSwitcher'=>!$model->isNewRecord,
		'deleteAction'=>$this->createUrl('/store/admin/attribute/delete', array('id'=>$model->id))
	));

	$title = ($model->isNewRecord) ? Yii::t('StoreModule', 'Создание атрибута') :
		Yii::t('StoreModule', 'Редактирование атрибута');

	$this->breadcrumbs = array(
		'Home'=>$this->createUrl('/admin'),
		Yii::t('StoreModule', 'Атрибуты')=>$this->createUrl('index'),
		($model->isNewRecord) ? Yii::t('StoreModule', 'Создание атрибута') : CHtml::encode($model->name),
	);

	$this->pageHeader = $title;

?>

<div class="form wide padding-all">
	<?php echo $form->asTabs(); ?>
</div>