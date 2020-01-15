<?php
$this->breadcrumbs=array(
  'Home'=>$this->createUrl('/admin'),
  Yii::t('NewsModule', 'Новости')=>$this->createUrl('/admin/news'),
  Yii::t('NewsModule', 'Добавление новости'),
);
  $this->pageHeader = Yii::t('NewsModule', "Добавление новости");

?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>