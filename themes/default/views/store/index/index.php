<?php

/**
 * Site start view
 */
?>
  <?php $this->widget('application.components.widgets.slider',array('layout'=>"home", 'id' => 1)); ?>
  <?php $this->widget('application.components.widgets.lists',array('lists'=>array('Newest', 'Popular', 'Sale'))); ?>

  <div class="slider-middle">
    <div class="slider-title"></div>
    <?php $this->widget('application.components.widgets.slider',array('layout'=>"middle", 'id' => '2')); ?>
  </div>
  <?php $this->widget('application.components.widgets.lists',array('lists'=>array('Viewed', 'Comment', 'Buyed'))); ?>
<?php
