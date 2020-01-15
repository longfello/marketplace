<?php

  class staticPageLink extends CWidget {
    public $layout = 'index';
    public $slug;

    function run(){
      Yii::import('application.modules.staticpages.models.*');
      $model = StaticPage::model()->findByAttributes(array('slug' => $this->slug));
      if ($model) {
        echo(CHtml::link($model->page_title, Yii::app()->createUrl('/'.$model->slug)));
      } else {
        echo('<a href="/">Orphan link</a>');
      }
    }

  }