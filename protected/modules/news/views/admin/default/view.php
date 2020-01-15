<?php
$this->breadcrumbs=array(
  'Home'=>$this->createUrl('/admin'),
  Yii::t('NewsModule', 'Новости')=>$this->createUrl('/admin/news'),
	$model->title,
);
  $this->pageHeader = Yii::t('NewsModule', "Просмотр новости")." | ".$model->title;
  $this->pageTitle=$model->title;
?>

<?php $this->renderPartial('_view', array(
	'data'=>$model,
)); ?>

