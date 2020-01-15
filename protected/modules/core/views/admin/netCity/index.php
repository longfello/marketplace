<?php
/* @var $this NetCityController */

  $this->pageHeader = Yii::t('CoreModule', 'Города');

  $this->breadcrumbs=array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('CoreModule', 'Города'),
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

<?php $this->widget('ext.sgridview.SGridView', array(
  'dataProvider'=>$model->search(),
  'id'=>'CitiesListGrid',
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
    array(
      'name' => 'region_id',
      'value' => '$data->region->name',
      'filter' => NetRegions::model()->getFilter()
    ),
    array(
      'name' => 'country_id',
      'value' => '$data->country->name',
      'filter' => NetCountry::model()->getFilter()
    ),
    'latitude',
    'longitude',
    'postal_code',
    array(
      'class'=>'CButtonColumn',
      'template'=>'{update}{delete}',
    ),
  )
)); ?>
