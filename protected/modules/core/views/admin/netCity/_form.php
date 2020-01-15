<?php
/* @var $this NetCityController */
/* @var $model NetCity */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'net-city-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

  <div class="row">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#name_ru" data-toggle="tab"><?php echo $form->labelEx($model,'name_ru'); ?></a></li>
      <li>               <a href="#name_en" data-toggle="tab"><?php echo $form->labelEx($model,'name_en'); ?></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="name_ru"><?php echo $form->textField($model,'name_ru',array('size'=>60,'maxlength'=>100)); ?></div>
      <div class="tab-pane" id="name_en"><?php echo $form->textField($model,'name_en',array('size'=>60,'maxlength'=>100)); ?></div>
    </div>
    <?php echo $form->error($model,'name_ru'); ?>
    <?php echo $form->error($model,'name_en'); ?>
  </div>

	<div class="row">
		<?php echo $form->labelEx($model,'country_id'); ?>
    <?php echo $form->dropDownList($model,'country_id', NetCountry::model()->getFilter(), array(
      'ajax' => array(
        'type'=>'POST', //request type
        'url'=>$this->createUrl('regions'), //url to call.
        //Style: CController::createUrl('currentController/methodToCall')
        'update'=>'#NetCity_region_id', //selector to update
        //'data'=>'js:javascript statement'
        //leave out the data key to pass all form values through
      ))); ?>
		<?php echo $form->error($model,'country_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'region'); ?>
    <?php echo $form->dropDownList($model,'region_id', NetRegions::model()->getFilter($model)); ?>
		<?php echo $form->error($model,'region'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postal_code'); ?>
		<?php echo $form->textField($model,'postal_code',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'postal_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'latitude'); ?>
		<?php echo $form->textField($model,'latitude',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'latitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'longitude'); ?>
		<?php echo $form->textField($model,'longitude',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'longitude'); ?>
	</div>

	<div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('CoreModule','Create') : Yii::t('CoreModule','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->