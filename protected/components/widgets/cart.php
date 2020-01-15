<?php

  class cart extends CWidget {
    public $layout = 'small';

    function run(){

      $this->render('cart/'.$this->layout, array(
        'items' => Yii::app()->cart->getDataWithModels()
      ));
    }
  }