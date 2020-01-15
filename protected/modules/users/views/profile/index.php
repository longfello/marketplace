<?php

/**
 * @var $profile UserProfile
 * @var $user User
 * @var $form CActiveForm
 * @var $changePasswordForm ChangePasswordForm
 * @var $this Controller
 */

$this->pageTitle=Yii::t('UsersModule', 'Личный кабинет');
?>
<h1 class="has_background"><?php echo Yii::t('UsersModule', 'Личный кабинет'); ?></h1>
<div class="form wide padding-all">
  <a href="<?=$this->createUrl('/user/profile/orders')?>"><?php echo Yii::t('UsersModule', 'Мои заказы'); ?></a>
</div>
<div class="form wide padding-all">
	<?php $form=$this->beginWidget('CActiveForm'); ?>

	<?php echo $form->errorSummary(array($profile, $user)); ?>

	<div class="row">
		<?php echo $form->label($profile,'full_name'); ?>
		<?php echo $form->textField($profile,'full_name') ?>
		<span class="required"> *</span>
	</div>

	<div class="row">
		<?php echo $form->label($user,'email'); ?>
		<?php echo $form->textField($user,'email') ?>
		<span class="required"> *</span>
	</div>

	<div class="row">
		<?php echo $form->label($profile,'phone'); ?>
		<?php echo $form->textField($profile,'phone') ?>
	</div>

	<div class="row">
		<?php echo $form->label($profile,'delivery_address'); ?>
		<?php echo $form->textArea($profile,'delivery_address') ?>
	</div>

  <div class="row">
    <?php echo $form->label($profile,'city_id'); ?>
    <?php echo $form->hiddenField($profile,'city_id') ?>
    <input class="ui-autocomplete" id="city" value="<?= Yii::app()->user->location->city->name ?>">
  </div>

	<div class="row submit">
		<label>&nbsp;</label>
		<?php echo CHtml::submitButton(Yii::t('UsersModule', 'Сохранить')); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->

<div style="clear: both;"></div>

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
