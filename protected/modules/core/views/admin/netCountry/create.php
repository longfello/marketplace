<?php
/* @var $this NetCountryController */
/* @var $model NetCountry */

$this->breadcrumbs=array(
	'Net Countries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List NetCountry', 'url'=>array('index')),
	array('label'=>'Manage NetCountry', 'url'=>array('admin')),
);
?>

<h1>Create NetCountry</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>