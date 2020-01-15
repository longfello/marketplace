<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
  'Home'=>$this->createUrl('/admin'),
  Yii::t('NewsModule', 'Новости')
);
  $this->pageHeader = Yii::t('NewsModule', "Новости");
?>

<a href="<?=$this->createUrl('create')?>" class="btn btn-info"><i class="icon-plus-sign icon-white"></i><?=Yii::t('NewsModule', 'Добавить')?></a>

<?php $this->widget('zii.widgets.grid.CGridView', array(
  'dataProvider' => $model->search(),
  'ajaxUpdate'   => true,
  'ajaxType'     => 'POST',
  'filter'       => $model,
  'pager' => array(
    'header' => '',
    'cssFile' => false,
    'maxButtonCount' => 25,
    'selectedPageCssClass' => 'active',
    'hiddenPageCssClass' => 'disabled',
    'firstPageCssClass' => 'previous',
    'lastPageCssClass' => 'next',
    'firstPageLabel' => '<<',
    'lastPageLabel' => '>>',
    'prevPageLabel' => '<',
    'nextPageLabel' => '>',
  ),
  'columns'=>array(
    array(
      'name'=>'title_ru',
      'type'=>'raw',
      'value'=>'CHtml::link(CHtml::encode($data->title_ru), $data->url)'
    ),
    array(
      'name'=>'status',
      'value'=>'Lookup::item("PostStatus",$data->status)',
      'filter'=>Lookup::items('PostStatus'),
    ),
    array(
      'name'=>'create_time',
      'type'=>'datetime',
      'filter'=>false,
    ),
    array(
      'class'=>'CButtonColumn',
    ),
  ),
)); ?>
