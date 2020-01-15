<?php

  class likes extends CWidget {
    public $layout = 'index';
    public $link = false;
    public $text = false;
    public $title = false;
    public $image = false;
    // public $currentCategory;
    public $currentBaseCategory;

    function run(){
      $url = Yii::app()->request->url;

      $link = Yii::app()->createAbsoluteUrl($this->link?$this->link:Yii::app()->request->url);
      $link = str_replace('.html.html', '.html', $link);

      $this->render('likes/'.$this->layout, array(
        'url' => $url,
        'link' => $link,
        'text' => $this->text?$this->text:Yii::t('widget-like', 'Share'),
        'title' => $this->title,
      ));
    }

  }