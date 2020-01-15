<?php

/**
 * Create/update currency
 */

$this->topButtons = $this->widget('admin.widgets.SAdminTopButtons', array(
	'form'=>$form,
	//'langSwitcher'=>!$model->isNewRecord,
	'deleteAction'=>$this->createUrl('/store/admin/currency/delete', array('id'=>$model->id))
));

$title = ($model->isNewRecord) ? Yii::t('StoreModule', 'Создание валюты') :
	Yii::t('StoreModule', 'Редактирование валюты');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('StoreModule', 'Валюты')=>$this->createUrl('index'),
	($model->isNewRecord) ? Yii::t('StoreModule', 'Создание валюты') : CHtml::encode($model->name),
);

$this->pageHeader = $title;

?>

<div class="form wide padding-all">
	<?php echo $form; ?>
</div>