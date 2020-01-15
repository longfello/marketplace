<?php
$this->breadcrumbs=array(
  'Home'=>$this->createUrl('/admin'),
  Yii::t('NewsModule', 'Новости')=>$this->createUrl('/admin/news'),
  Yii::t('NewsModule', 'Редактирование'),
);
  $this->pageHeader = Yii::t('NewsModule', "Редактирование новости")." | ".$model->title;
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>