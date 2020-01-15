<div class="form wide padding-all">
  <?php $form=$this->beginWidget('CActiveForm'); ?>

  <?php echo $form->errorSummary($changePasswordForm); ?>

  <div class="row">
    <label></label>
    <b><?php echo Yii::t('UsersModule', 'Изменить пароль'); ?></b>
  </div>

  <div class="row">
    <?php echo $form->label($changePasswordForm,'current_password'); ?>
    <?php echo $form->passwordField($changePasswordForm,'current_password') ?>
  </div>

  <div class="row">
    <?php echo $form->label($changePasswordForm,'new_password'); ?>
    <?php echo $form->passwordField($changePasswordForm,'new_password') ?>
  </div>

  <div class="row submit">
    <label>&nbsp;</label>
    <?php echo CHtml::submitButton(Yii::t('UsersModule', 'Изменить')); ?>
  </div>

  <?php $this->endWidget(); ?>
</div><!-- form -->