<?php
/* @var $this NetRegionsController */
/* @var $model NetRegions */

  $this->pageHeader = Yii::t('CoreModule', 'Города');

  $this->breadcrumbs=array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('CoreModule', 'Области') => array('index'),
    $model->name=>array('view','id'=>$model->id),
    Yii::t('CoreModule', 'Добавление')
  );

  $this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
    'template'=>array('create', 'manage'),
    'elements'=>array(
      'create'=>array(
        'link'=>$this->createUrl('create'),
        'title'=>Yii::t('CoreModule', 'Добавить область'),
        'icon'=>'plus',
      ),
      'manage'=>array(
        'link'=>$this->createUrl('index'),
        'title'=>Yii::t('CoreModule', 'К перечню областей'),
        'icon'=>'plus',
      ),
    ),
  ));

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>