<?php
/* @var $this NetCityController */
/* @var $model NetCity */

  $this->pageHeader = Yii::t('CoreModule', 'Города');

  $this->breadcrumbs=array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('CoreModule', 'Города') => array('index'),
    Yii::t('CoreModule', 'Добавление')
  );

  $this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
    'template'=>array('create', 'manage'),
    'elements'=>array(
      'create'=>array(
        'link'=>$this->createUrl('create'),
        'title'=>Yii::t('CoreModule', 'Создать город'),
        'icon'=>'plus',
      ),
      'manage'=>array(
        'link'=>$this->createUrl('index'),
        'title'=>Yii::t('CoreModule', 'К перечню городов'),
        'icon'=>'plus',
      ),
    ),
  ));

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>