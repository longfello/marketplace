<div id='m_login' class="login <?=$isGuest?'guest':'user'?>">
  <div class='form hidden'>
    <?php if ($isGuest) { ?>
      <?php
      echo CHtml::form(Yii::app()->createUrl('/users/login'),'post', array('id'=>'login-form'));
      echo CHtml::errorSummary($model);
      ?>

      <div class="row">
        <?php echo CHtml::activeLabel($model,'username', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($model,'username', array('autocomplete'=>'off', 'id'=>'input-login')); ?>
      </div>

      <div class="row">
        <?php echo CHtml::activeLabel($model,'password', array('required'=>true)); ?>
        <?php echo CHtml::activePasswordField($model,'password'); ?>
      </div>

      <div class="row">
        <?php echo CHtml::activeLabel($model,'rememberMe', array('class'=>'remember-checkbox')); ?>
        <?php echo CHtml::activeCheckBox($model,'rememberMe', array('id'=>'ULoginForm_rememberMe')); ?>
      </div>

      <div class="row buttons">
        <input type="submit" class="login-button gradient" value="<?php echo Yii::t('widget-login','Sign In'); ?>">
      </div>

      <div class="row buttons">
        <?php echo CHtml::link(Yii::t('widget-login', 'Sign Up'), array('/users/register'), array('class'=>'register')) ?><br>
        <?php echo CHtml::link(Yii::t('widget-login', 'remind'), array('/users/remind'), array('class'=>'register')) ?>
      </div>
      <?php echo CHtml::endForm(); ?>
    <?php } else { ?>
      <div class="row">
        <?=Yii::t('widget-login', 'hello')?>, <?= Yii::app()->user->name ?>
        <a class="profile" href="<?=Yii::app()->createUrl('users/profile')?>">
          <input type="button" class="login-button gradient" value="<?=Yii::t('widget-login', 'profile')?>">
        </a>
        <?php if (Yii::app()->user->checkAccess('AdminEntrance')) { ?>
          <a class="profile" href="<?=Yii::app()->createUrl('admin')?>">
            <input type="button" class="login-button gradient" value="<?=Yii::t('widget-login', 'administrator link')?>">
          </a>
        <?php } ?>
        <a class="logout" href="<?=Yii::app()->createUrl('users/logout')?>">
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