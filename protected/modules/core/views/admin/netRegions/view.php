<?php
/* @var $this NetRegionsController */
/* @var $model NetRegions */

$this->breadcrumbs=array(
	'Net Regions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List NetRegions', 'url'=>array('index')),
	array('label'=>'Create NetRegions', 'url'=>array('create')),
	array('label'=>'Update NetRegions', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete NetRegions', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NetRegions', 'url'=>array('admin')),
);
?>

<h1>View NetRegions #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'country_id',
		'code',
		'name_ru',
		'name_en',
	),
)); ?>
