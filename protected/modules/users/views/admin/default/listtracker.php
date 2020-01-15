<?php
// Display list of users

$this->pageHeader = Yii::t('UsersModule', 'Список тем сообщений');

$this->breadcrumbs = array(
  'Home'=>$this->createUrl('/admin'),
  Yii::t('UsersModule', 'Соощения'),
);

$this->widget('zii.widgets.grid.CGridView', array(
  'dataProvider'=>$dataProvider,
  'id'=>'themesListGrid',
  'filter'=>$model,
  'columns'=>array(
    'id',
    array(
      'name'=>'name',
      'type'=>'raw',
      'value'=>'CHtml::link(CHtml::encode($data->name),array("trackerview","id"=>$data->id))',
    ),
    'created',
    'last_msg',
    'status'
  ),
));

