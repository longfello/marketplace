<?php
/* @var $this Controller */
/* @var $form CActiveForm */
?>
<div class="form wide padding-all">
  <?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'miscellaneous-pages-form',
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array(
          'enctype'=>'multipart/form-data'
        )
      )
    );
  ?>

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
    <? echo $form->labelEx($profile, 'photo'); ?>
    <img src="<?=$profile->getPhotoUrl(64)?>">
    <? echo $form->fileField($profile, 'photo'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($profile,'birthday'); ?>

    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
      'name'=>'UserProfile[birthday]',
      'id'=>'UserProfile_Birthdate',
      'model'=>$profile,
      'value'=>$profile->birthday,
      'language' => Yii::app()->i18n->activeLanguage['code'],
      // additional javascript options for the date picker plugin
      'options'=>array(
        'showAnim'=>'slideDown',
        'changeMonth'=>true,
        'changeYear'=>true,
        'dateFormat' => 'dd.mm.yy'
      ),
      'htmlOptions'=>array(
        'style'=>'height:20px;',
      ),
    ));

    ?>
  </div>

  <div class="row">
    <?php echo $form->label($profile,'city_id'); ?>
    <?php $this->widget('application.components.widgets.cityPicker', array('model' => $profile, 'field' => 'city_id')); ?>
  </div>

  <div class="row submit">
    <label>&nbsp;</label>
    <?php echo CHtml::submitButton(Yii::t('UsersModule', 'Сохранить')); ?>
  </div>

  <?php $this->endWidget(); ?>
</div><!-- form -->

<?php

