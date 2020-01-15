<?php
  /* @var $this SiteController */

  $this->pageTitle = Yii::app()->name;
?>
  <?php $this->widget('application.components.widgets.slider',array('layout'=>"home")); ?>


<?php /*
<img src="<?= Helper::img('site/test.jpg', array('width'=>1000, 'height'=>100, 'crop' => true));?>">
*/