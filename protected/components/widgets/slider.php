<?php

  class slider extends CWidget {
    public $layout = 'home';
    public $id = 1;

    function run(){
      Helper::registerCSS('jquery.bxslider.css');
      Helper::registerJS('jquery.bxslider.min.js', CClientScript::POS_HEAD);
      Yii::app()->clientScript->registerScript('widget-slider', "
$('.bxslider').bxSlider({
  mode: 'fade',
  captions: true,
  auto: true
});
");
      $model = Gallery::model()->findByPk($this->id);
      $this->render('slider/'.$this->layout, array(
        'gallery' => $model
      ));
    }
  }