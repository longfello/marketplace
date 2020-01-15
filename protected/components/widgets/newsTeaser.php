<?php

  class newsTeaser extends CWidget {
    public $layout = 'index';

    function run(){
      $hash = 'widget-newsTeaser-'.$this->layout.'-'.Yii::app()->i18n->activeLanguage['code'];
      $val = Yii::app()->cache->get($hash);
      if (!$val) {
        Yii::import('application.modules.news.models.News');
        $criteria=new CDbCriteria(array(
          'condition'=>'status='.News::STATUS_PUBLISHED,
          'order'    =>'create_time DESC',
          'limit'    => 3
        ));

        $news = News::model()->findAll($criteria);

        $val = $this->render('newsTeaser/'.$this->layout, array(
          'news' => $news
        ), true);
        Yii::app()->cache->set($hash, $val, 3600);
      }
      echo $val;
    }
  }