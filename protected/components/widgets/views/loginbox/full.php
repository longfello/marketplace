<div id='m_login' class="login <?=$isGuest?'guest':'user'?>">
  <div class='form'>
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

      <div class="row buttons r-b-btn">
        <?php echo CHtml::link(Yii::t('widget-login', 'Sign Up'), array('/users/register'), array('class'=>'register')) ?><br>
        <?php echo CHtml::link(Yii::t('widget-login', 'remind'), array('/users/remind'), array('class'=>'register')) ?>
      </div>
      <?php echo CHtml::endForm(); ?>
    <?php } else { ?>
      <div class="row">
        <?=Yii::t('widget-login', 'hello')?>, <?= Yii::app()->user->name ?>
        <a class="profile l_btn" href="<?=Yii::app()->createUrl('users/profile')?>">Кабинет</a>
        <a class="logout l_btn" href="<?=Yii::app()->createUrl('users/logout')?>">Выйти</a>
      </div>
    <?php } ?>
  </div>
</div>
