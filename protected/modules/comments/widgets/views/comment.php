<?php

/**
 * @var $this Controller
 */
?>

<div class="feedback-form form">
  <div class="button gradient"><a href="#"><?=Yii::t('CommentsModule', 'Оставить отзыв')?></a></div>
<?php if ($this->message) { ?>
  <?=$this->message ?>
<?php } else { ?>
  <?php
  $form=$this->beginWidget('CActiveForm', array(
    'id'                     =>'comment-create-form',
    'enableAjaxValidation'   =>false,
    'enableClientValidation' =>true,
    'htmlOptions'            => array('class' => 'leave-feedback'),
    //      'action'                 =>$currentUrl.'#comment-create-form',
  )); ?>
    <div class="row">
      <div class="stars">
        <?php $this->widget('CStarRating',array(
            'name'=>'Comment[rate]',
            'id'=>'market_comment_rate',
            'allowEmpty'=>false,
            'readOnly'=>false,
            'minRating'=>-2,
            'maxRating'=>2,
            'htmlOptions' => array(
                'class' => 'stars'
            ),
            'value'=>0
        )); ?>
      </div>
    </div>
    <?php if(Yii::app()->user->isGuest): ?>
    <div class="row">
      <?php echo $form->textField($model,'name', array('placeholder'=>Yii::t('CommentsModule', "Ваше имя"), 'title'=>strip_tags($form->error($model,'name')))); ?>
    </div>
    <div class="row">
      <?php echo $form->textField($model,'email', array('placeholder'=>Yii::t('CommentsModule', "Электронная почта"), 'title' => strip_tags($form->error($model,'email')))); ?>
    </div>
  <?php endif; ?>
    <?php echo $form->textArea($model,'text', array('rows'=>5, 'placeholder'=>Yii::t('CommentsModule', "Ваш отзыв"), 'title' => strip_tags($form->error($model,'text')))); ?>
    <?php if(Yii::app()->user->isGuest): ?>
    <div class="row">
      <div class="vvedite-text"><?=Yii::t('CommentsModule', 'Введите текст с картинки:')?></div>
      <?php $this->widget('CCaptcha', array(
        'clickableImage'=>true,
        'showRefreshButton'=>false,
      )) ?>
      <?php echo CHtml::activeTextField($model, 'verifyCode', array('title' => strip_tags($form->error($model,'verifyCode'))))?>
    </div>
  <?php endif ?>
    <?php echo CHtml::submitButton(Yii::t('CommentsModule', 'Отправить'), array('class'=>"submit-button")); ?>
  <?php $this->endWidget(); ?><!-- /form -->
<?php } ?>
  <div class="clear"></div>

</div>

