<?php
  Yii::import('zii.widgets.CPortlet');

  class top_menu extends CPortlet {
    public $layout = 'index';

    protected function renderContent(){
      $this->render('top_menu/'.$this->layout);
    }
  }