<?php

  class categoryLinks extends CWidget {
    public $layout = 'index';
    public $slug;

    function run(){
      $hash = 'widget-categoryLinks-'.$this->layout.'-'.Yii::app()->language;
      $val = Yii::app()->cache->get($hash);
      if (!$val) {
        $val = '';
        $items = StoreCategory::model()->findByPk(1)->asCMenuArray();
        foreach($items['items'] as $item) {
          $val .= ('<li>'.CHtml::link($item['label'], $item['url']['url']).'</li>');
        }
        Yii::app()->cache->set($hash, $val, 3600);
      }
      echo($val);
    }

  }