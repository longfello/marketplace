<?php

  class chooseLanguage extends CWidget {
    public $layout = 'index';

    function run(){
      $this->render('chooseLanguage/'.$this->layout, array('language' => Yii::app()->getLanguage()));
    }
  }