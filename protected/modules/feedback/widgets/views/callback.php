<?php

/**
 * @var $this Controller
 */
?>

<?php
  if (!$this->message) {
?>

<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm'); ?>

		<!-- Display errors  -->
		<?php echo $form->errorSummary($model); ?>

		<div class="inline-row"><?php echo CHtml::activeHiddenField($model,'name'); ?></div>
		<div class="inline-row"><?php echo CHtml::activeHiddenField($model,'email'); ?></div>
		<div class="inline-row"><?php echo CHtml::activeTextField($model,'message', array('placeholder' => Yii::t('FeedbackModule', 'Ваш номер телефона'))); ?></div>
    <?php if(Yii::app()->settings->get('feedback', 'enable_captcha')): ?>
      <div class="inline-row">
        <label><?php $this->widget('CCaptcha', array('clickableImage'=>true,'showRefreshButton'=>false)) ?></label>
        <?php echo CHtml::activeTextField($model, 'code', array('placeholder' => Yii::t('FeedbackModule', 'Код проверки')))?>
      </div>
    <?php endif; ?>

		<div class="inline-row">
			<button type="submit" class=""><?php echo Yii::t('FeedbackModule', 'Отправить') ?></button>
		</div>
  <div class="clearfix"></div>
<?php $this->endWidget(); ?>
</div>

<?php } else { ?>
  <div class="alert alert-info"><?= $this->message; ?></div>
<?php } ?>
