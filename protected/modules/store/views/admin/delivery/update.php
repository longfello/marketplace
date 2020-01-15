<?php

/**
 * @var $this Controller
 *
 * Create/update delivery methods
 */

$this->topButtons = $this->widget('admin.widgets.SAdminTopButtons', array(
	'form'=>$form,
	'langSwitcher'=>!$model->isNewRecord,
	'deleteAction'=>$this->createUrl('/store/admin/delivery/delete', array('id'=>$model->id))
));

$title = ($model->isNewRecord) ? Yii::t('StoreModule', 'Создание способа доставки') :
	Yii::t('StoreModule', 'Редактирование способа доставки');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('StoreModule', 'Способы доставки')=>$this->createUrl('index'),
	($model->isNewRecord) ? Yii::t('StoreModule', 'Создание способа доставки') : CHtml::encode($model->name),
);

$this->pageHeader = $title;

  Yii::import('ext.sidebartabs.*');

  $tabs = array(
    Yii::t('StoreModule', 'Основная информация') => $form,
    Yii::t('StoreModule', 'Регионы') => $this->renderPartial('regions', array(
        'this'  => $this,
        'model' => $model
      ), true),
  );

?>

<div class="form wide padding-all">
  <?php $this->widget('ext.sidebartabs.SAdminSidebarTabs', array('tabs' => $tabs)); ?>
</div>