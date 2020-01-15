<?php
/* @var $this NetCityController */
/* @var $model NetCity */

$this->breadcrumbs=array(
	'Net Cities'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List NetCity', 'url'=>array('index')),
	array('label'=>'Create NetCity', 'url'=>array('create')),
	array('label'=>'Update NetCity', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete NetCity', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NetCity', 'url'=>array('admin')),
);
?>

<h1>View NetCity #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'country_id',
		'name_ru',
		'name_en',
		'region',
		'postal_code',
		'latitude',
		'longitude',
	),
)); ?>
