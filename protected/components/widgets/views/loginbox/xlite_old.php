<div id='m_login' class="login <?=$isGuest?'guest':'user'?>">
  <div class='form hidden'>
    <?php if ($isGuest) { ?>
      <?php $form = $this->beginWidget('CActiveForm',array(
        'id'     =>'login-form'
      )) ?>

      <label><?php echo Yii::t('widget-login','Login') ?></label>
      <?php echo $form->textField($model, 'login', array('autocomplete'=>'off', 'id'=>'input-login')) ?>
      <?php echo $form->error($model, 'login') ?>

      <label><?php echo Yii::t('widget-login','Password') ?></label>
      <?php echo $form->passwordField($model, 'password') ?>
      <?php echo $form->error($model, 'password') ?>

      <label class="remember-checkbox"><?php echo Yii::t('widget-login','Remember Me') ?></label>
      <?php echo $form->checkBox($model, 'rememberMe') ?>

      <div class='row buttons'>
        <?php echo CHtml::submitButton(Yii::t("widget-login","Sign In"),array('class'=>'login-button gradient')) ?>
      </div>
      <a class="register" href="<?=Yii::app()->createUrl('site/registration')?>">
        <input type="button" class="login-button gradient" value="<?=Yii::t('widget-login', 'Sign Up')?>">
      </a>

      <?php $this->endWidget() ?>
    <?php } else { ?>
      <div class="row">
        <?=Yii::t('widget-login', 'hello')?>, <?= Yii::app()->user->name ?>
        <a class="profile" href="<?=Yii::app()->createUrl('UserAdmin/profile/personal')?>">
          <input type="button" class="login-button gradient" value="<?=Yii::t('widget-login', 'profile')?>">
        </a>
        <?php if (User::checkRole('Admin')) { ?>
          <a class="profile" href="<?=Yii::app()->createUrl('UserAdmin')?>">
            <input type="button" class="login-button gradient" value="<?=Yii::t('widget-login', 'administrator link')?>">
          </a>
        <?php } ?>
        <a class="logout" href="<?=Yii::app()->createUrl('/logout')?>">
          <input type="button" class="login-button gradient" value="<?=Yii::t('widget-login', 'logout')?>">
        </a>
      </div>
    <?php } ?>
  </div>
  <div class="form form-helper">
    <a class="show-login" href="#">
      <?php if ($isGuest) { ?>
          <input type="button" class="login-button gradient" value="<?=Yii::t('widget-login', 'Sign In')?>">
      <?php } else { ?>
          <input type="button" class="login-button gradient" value="<?=Yii::app()->user->name?>">
      <?php } ?>
    </a>
  </div>
</div>

<?php
  Yii::app()->clientScript->registerScript('xlite-loginbox', "
  $('.show-login').on('mouseover', function(e){
    e.preventDefault();
    $('#sidebar-last').addClass('loginbox-in-popup');
    $(this).parents('.form-helper').addClass('hidden');
    $(this).parents('.form-helper').siblings('.form').removeClass('hidden');
    $(this).parents('.form-helper').siblings('.form').one('mouseleave', function(){
      $(this).siblings('.form-helper').removeClass('hidden');
      $(this).addClass('hidden');
      $('#sidebar-last').removeClass('loginbox-in-popup');
    });
  });
");
?>