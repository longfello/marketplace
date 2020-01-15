<?php
/* @var $this NetCountryController */
/* @var $dataProvider CActiveDataProvider */

  $this->pageHeader = Yii::t('CoreModule', 'Страны');

  $this->breadcrumbs=array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('CoreModule', 'Страны'),
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

<?php $this->widget('ext.sgridview.SGridView', array(
  'dataProvider'=>$model->search(),
  'id'=>'CountriesListGrid',
  'filter'=>$model,

  'columns'=>array(
    array(
      'class'=>'CCheckBoxColumn',
    ),
    array(
      'class'=>'SGridIdColumn',
      'name'=>'id',
    ),
    'name_ru', 'name_en',
    'code',
    array(
      'class'=>'CButtonColumn',
      'template'=>'{update}{delete}',
    ),
  )
)); ?>
