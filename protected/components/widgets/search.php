<?php

  class search extends CWidget {
    public $layout = 'index';
    // public $currentCategory;
    public $currentBaseCategory;

    function run(){
      $itemsCount = Yii::app()->cache->get('productsCount');
      if (!$itemsCount) {
        Yii::import('application.modules.store.models.StoreProduct');
        $itemsCount = StoreProduct::model()->active()->count();
        Yii::app()->cache->set('productsCount', $itemsCount, 3600);
      }
      $this->render('search/'.$this->layout, array(
        'itemsCount'   => $itemsCount,
        'query'        => Yii::app()->request->getQuery('q', '')
      ));
    }

  }