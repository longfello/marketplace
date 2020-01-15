<?php
/* @var $this NetCountryController */
/* @var $model NetCountry */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'net-country-form',
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
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('CoreModule','Create') : Yii::t('CoreModule','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->