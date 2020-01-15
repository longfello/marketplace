<?php

  class catalog extends CWidget {
    public $layout = 'index';
    // public $currentCategory;
    public $currentBaseCategory;

    function run(){
      $this->currentBaseCategory = $this->getCurrentBaseCategory();
      $hash = 'widget-catalog-'.$this->layout.'-'.$this->currentBaseCategory;
      $val = Yii::app()->cache->get($hash);
      if (!$val) {
        Yii::import('application.modules.store.models.StoreCategory');
        $items = StoreCategory::model()->findByPk(1)->asCMenuArray();

        // $this->currentCategory = $this->getCurrentCategory();

        $val = $this->render('catalog/'.$this->layout, array(
          'items'   => $items,
          'current' => $this->currentBaseCategory
        ), true);
        Yii::app()->cache->set($hash, $val, 3600);
      }

      Yii::app()->clientScript->registerScript('catalog-behavor', '
  $(document).ready(function(){
    $(".widget-catalog.full a").on("click", function(e){
      e.preventDefault();
      $(this).parents(".catalog-block").find(".appended").slideToggle();
      $(this).parent().hide();
    });
  });
');
      echo($val);
    }

    function getCurrentBaseCategory(){
      Yii::import('application.modules.store.models.StoreCategory');
      switch(get_class(Yii::app()->controller)) {
        case 'CategoryController':
          $modelCurrent = Yii::app()->controller->model;
          break;
        case 'FrontProductController':
          if(Yii::app()->controller->model->mainCategory) {
            $categoryID = Yii::app()->controller->model->mainCategory->id;
            $modelCurrent = StoreCategory::model()->findByPk($categoryID);
          } else $modelCurrent = false;
          break;
        default:
          $modelCurrent = false;
      }

      $categoryID = 1;
      if ($modelCurrent) {
        $model = StoreCategory::model()->find("lft <= :lft AND rgt >= :rgt AND level = 2", array(':lft' => $modelCurrent->lft, ':rgt' => $modelCurrent->rgt));
        if ($model) {
          $categoryID = $model->id;
        }
      }
      return $categoryID;
    }

  }
