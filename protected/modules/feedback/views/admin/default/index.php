<?php

/**
 * Feedback configuration view
 *
 * @var $form CActiveForm
 */

$this->pageHeader = Yii::t('FeedbackModule', 'Обратная связь');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('FeedbackModule', 'Модули')=>Yii::app()->createUrl('/core/admin/systemModules'),
	Yii::t('FeedbackModule', 'Обратная связь')
);

?>

<div class="form wide padding-all">
	<?php $form=$this->beginWidget('CActiveForm'); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->label($model,'admin_email'); ?>
		<?php echo $form->textField($model,'admin_email') ?>
		<span class="required"> *</span>
		<div class="hint"><?php echo Yii::t('FeedbackModule', 'Укажите email куда отправлять новые сообщения.'); ?></div>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_message_length'); ?>
		<?php echo $form->textField($model,'max_message_length') ?>
		<span class="required"> *</span>
		<div class="hint"><?php echo Yii::t('FeedbackModule', 'Укажите максимальную длину сообщения.'); ?></div>
	</div>

	<div class="row">
		<?php echo $form->label($model,'enable_captcha'); ?>
		<?php echo $form->checkBox($model,'enable_captcha') ?>
		<div class="hint"><?php echo Yii::t('FeedbackModule', 'Использовать код протекции для защиты от спама.'); ?></div>
	</div>

	<div class="row submit">
		<label>&nbsp;</label>
		<?php echo CHtml::submitButton(Yii::t('FeedbackModule', 'Сохранить')); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
