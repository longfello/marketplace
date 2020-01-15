<?php

/**
 * @var UserProfile $profile
 * @var User $user
 * @var Controller $this
 */
?>

<h1 class="has_background"><?php echo $this->pageTitle; ?></h1>

<?php echo($text); ?>

<div class="login_box rc5">
	<div class="form wide">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'user-register-form',
			'enableAjaxValidation'=>false,
		)); ?>

		<?php echo $form->errorSummary(array($user, $profile)); ?>

		<div class="row">
			<?php echo $form->labelEx($user,'username'); ?>
			<?php echo $form->textField($user,'username'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($user,'password'); ?>
			<?php echo $form->passwordField($user,'password'); ?>
		</div>

    <div class="row">
      <?php echo $form->labelEx($market,'name'); ?>
      <?php echo $form->textField($market,'name'); ?>
    </div>

    <div class="row">
      <?php echo $form->labelEx($market,'url'); ?>
      <?php echo $form->textField($market,'url'); ?>
    </div>

    <div class="row">
      <?php echo $form->labelEx($profile,'full_name'); ?>
      <?php echo $form->textField($profile,'full_name'); ?>
    </div>

    <div class="row">
      <?php echo $form->labelEx($profile,'person'); ?>
      <?php echo $form->textField($profile,'person'); ?>
    </div>

		<div class="row">
			<?php echo $form->labelEx($user,'email'); ?>
			<?php echo $form->textField($user,'email'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($profile,'phone'); ?>
			<?php echo $form->textField($profile,'phone'); ?>
		</div>

		<?php if(CCaptcha::checkRequirements()): ?>
			<div class="row">
				<?php echo $form->labelEx($user,'verifyCode'); ?>
				<?php echo $form->textField($user,'verifyCode'); ?>
				<?php echo $form->error($user,'verifyCode'); ?>
			</div>

			<div class="row">
				<label></label>
				<?php $this->widget('CCaptcha', array(
					'clickableImage' => true,
					'showRefreshButton' => false
				)); ?>
			</div>
		<?php endif; ?>

		<div class="row buttons">
			<?php echo CHtml::submitButton(Yii::t('UsersModule', 'Отправить')); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::link(Yii::t('UsersModule', 'Авторизация'), array('login/login')) ?><br>
			<?php echo CHtml::link(Yii::t('UsersModule', 'Напомнить пароль'), array('/users/remind')) ?>
		</div>

		<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>
