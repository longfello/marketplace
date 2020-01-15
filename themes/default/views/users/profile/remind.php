<div class="login_box rc5">
  <div class="form wide">
    <?php
    echo CHtml::form();
    echo CHtml::errorSummary($model);
    ?>

    <div class="row">
      <?php echo CHtml::activeLabel($model,'email', array('required'=>true)); ?>
      <?php echo CHtml::activeTextField($model,'email'); ?>
    </div>

    <div class="row buttons">
      <input type="submit" class="blue_button" value="<?php echo Yii::t('UsersModule','Напомнить'); ?>">
    </div>

    <?php echo CHtml::endForm(); ?>
  </div>
</div>
