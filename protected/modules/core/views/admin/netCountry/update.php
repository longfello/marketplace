<?php
/* @var $this NetCountryController */
/* @var $model NetCountry */

  $this->pageHeader = Yii::t('CoreModule', 'Страны');

  $this->breadcrumbs=array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('CoreModule', 'Страны') => array('index'),
    Yii::t('CoreModule', 'Редактирование').': '.$model->name
  );

  $this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
    'template'=>array('create', 'manage'),
    'elements'=>array(
      'create'=>array(
        'link'=>$this->createUrl('create'),
        'title'=>Yii::t('CoreModule', 'Добавить страну'),
        'icon'=>'plus',
      ),
      'manage'=>array(
        'link'=>$this->createUrl('index'),
        'title'=>Yii::t('CoreModule', 'К перечню стран'),
        'icon'=>'plus',
      ),
    ),
  ));

?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>