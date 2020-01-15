<?php

  class orphus extends CWidget {
    public $layout = 'index';

    function run(){
      Helper::registerJS('orphus-'.Yii::app()->language.'.js', CClientScript::POS_END);
      $this->render('orphus/'.$this->layout, array(
      ));
    }
  }