<div class="form wide padding-all">
  <?php $form=$this->beginWidget('CActiveForm'); ?>

  <?php echo $form->errorSummary(array($theme, $message)); ?>

  <div class="row">
    <?php echo $form->label($theme,'name'); ?>
    <?php echo $form->textField($theme,'name') ?>
    <span class="required"> *</span>
  </div>

  <div class="row">
    <?php echo $form->label($message,'text'); ?>
    <?php echo $form->textArea($message,'text') ?>
    <span class="required"> *</span>
  </div>

  <div class="row submit">
    <label>&nbsp;</label>
    <?php echo CHtml::submitButton(Yii::t('UsersModule.admin', 'Добавить')); ?>
  </div>

  <?php $this->endWidget(); ?>
</div><!-- form -->