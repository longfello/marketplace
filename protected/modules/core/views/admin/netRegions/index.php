<?php
/* @var $this NetRegionsController */

  $this->pageHeader = Yii::t('CoreModule', 'Регионы');

  $this->breadcrumbs=array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('CoreModule', 'Области'),
  );

  $this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
    'template'=>array('create', 'manage'),
    'elements'=>array(
      'create'=>array(
        'link'=>$this->createUrl('create'),
        'title'=>Yii::t('CoreModule', 'Создать область'),
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

<?php $this->widget('ext.sgridview.SGridView', array(
  'dataProvider'=>$model->search(),
  'id'=>'RegionsListGrid',
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
      'name' => 'country_id',
      'value' => '$data->country->name',
      'filter' => NetCountry::model()->getFilter()
    ),
    array(
      'class'=>'CButtonColumn',
      'template'=>'{update}{delete}',
    ),
  )
)); ?>
