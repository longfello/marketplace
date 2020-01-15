<?php
/* @var $this NetCountryController */
/* @var $model NetCountry */

$this->breadcrumbs=array(
	'Net Countries'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List NetCountry', 'url'=>array('index')),
	array('label'=>'Create NetCountry', 'url'=>array('create')),
	array('label'=>'Update NetCountry', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete NetCountry', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage NetCountry', 'url'=>array('admin')),
);
?>

<h1>View NetCountry #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name_ru',
		'name_en',
		'code',
	),
)); ?>
