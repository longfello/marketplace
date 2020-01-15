<h2><?= $theme->name ?></h2>
<div class="form wide padding-all">
  <?php $form=$this->beginWidget('CActiveForm'); ?>

  <?php echo $form->errorSummary($message); ?>

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
<div class="messages clear">
  <?php foreach ($theme->messages as $one) {?>
    <div class="one_message">
      <div class="msg_owner"><?php if ($one->is_user=='1') { echo Yii::t('UsersModule.admin', 'Вы'); } else { echo Yii::t('UsersModule.admin', 'Администратор'); } ?></div>
      <div class="msg_text"><?=$one->text;?></div>
      <div class="msg_data"><?=$one->created;?></div>
    </div>
  <?php } ?>
</div>