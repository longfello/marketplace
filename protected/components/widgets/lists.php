<?php

  class lists extends CWidget {
    public $layout = 'index';
    public $lists = array();
    public $available_blocks = array(
      'Popular', 'AddedToCart', 'Newest', 'Sale', 'Viewed', 'Buyed', 'Comment'
    );

    function run(){
      $hash = "widget-lists-for-".implode('&',$this->lists).'-'.$this->layout.'-'.Yii::app()->user->location->city->id;
      $content = Yii::app()->cache->get($hash);
      if (true || !$content) {
        $lists = array();
        foreach($this->lists as $list) {
          if (in_array($list, $this->lists)) {
            $method = 'get'.$list;
            $lists[] = array(
              'slug'    => $list,
              'title'   => Yii::t('widget-lists', 'list-'.$list),
              'content' => $this->$method(6)
            );
          }
        }
        $content = $this->render('lists/'.$this->layout, array(
          'lists' => $lists
        ), true);
        Yii::app()->cache->set($hash, $content, 3600);
      }
      Yii::app()->clientScript->registerScript('slider-group', "
$(document).ready(function(){
  $('.front-sort a').on('click', function(e){
    e.preventDefault();
    $(this).addClass('sort-active').siblings('a').removeClass('sort-active');
    $($(this).attr('href')).addClass('sort-active').siblings('div').removeClass('sort-active');
    init_slider();
  });
  init_slider();
});

function init_slider(){
  $('.lists-wrapper .sort-active .slider-wrapper').each(function(){
    if ($(this).parents('.bx-wrapper').size() == 0)
    {
      if ($(this).find('.item').size()>3)
      {
        $(this).bxSlider({
          responsive: true,
          auto: false,
          minSlides: 1,
          maxSlides: 3,
          slideWidth: 189,
          moveSlides: 1,
          autoHover: true,
        });
      }
    }
  });
}
", CClientScript::POS_END);
      echo($content);
    }

    /**
     * @param $limit
     * @return array
     */
    protected function getPopular($limit)
    {
      return StoreProduct::model()
        ->active()
        ->byViews()
        ->findAll(array('limit'=>$limit));
    }
    /**
     * @param $limit
     * @return array
     */
    protected function getNewest($limit)
    {
      $condition = new CDbCriteria();
      $condition->limit = $limit;
      $condition->addCondition("categorization.category > 1");
      return StoreProduct::model()
        ->active()
        ->newest()
        ->with('mainCategory')
        ->findAll($condition);
    }
    /**
     * @param $limit
     * @return array
     */
    protected function getSale($limit)
    {
      return StoreProduct::model()
        ->active()
        ->discount()
        ->newest()
        ->findAll(array('limit'=>$limit));
    }
    /**
     * @param $limit
     * @return array
     */
    protected function getViewed($limit)
    {
      return StoreProduct::model()
        ->active()
        ->viewed()
        ->findAll(array('limit'=>$limit));
    }
    /**
     * @param $limit
     * @return array
     */
    protected function getBuyed($limit)
    {
      return StoreProduct::model()
        ->active()
        ->byAddedToCart()
        ->findAll(array('limit'=>$limit));
    }
    /**
     * @param $limit
     * @return array
     */
    protected function getComment($limit) {
      return
        StoreProduct::model()
          ->active()
          ->commented()
          ->findAll(array('limit'=>$limit));
    }
    /**
     * @param $limit
     * @return array
     */
    protected function getByAddedToCart($limit)
    {
      return StoreProduct::model()
        ->active()
        ->byAddedToCart()
        ->findAll(array('limit'=>$limit));
    }


  }