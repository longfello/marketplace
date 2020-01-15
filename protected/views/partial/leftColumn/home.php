<div class="button catalog-button gradient"><?=Yii::t('main','Каталог товаров')?></div>

<?php $this->widget('application.components.widgets.catalog'); ?>

<?php

  if (!Yii::app()->user->getIsManager() && !Yii::app()->user->getIsSuperuser()) {
    ?>
      <div class="join">
        <a href="<?=Yii::app()->createUrl('/users/registerManager')?>">
          <?=Yii::t('main','У вас есть магазин?<br/>Присоединяйтесь!')?>
        </a>
      </div>
    <?php
  }
?>

<?php $this->widget('application.components.widgets.newsTeaser'); ?>
