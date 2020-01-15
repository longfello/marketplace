<?php

  /**
   * Display comments list
   *
   * @var $this Controller
   * @var $model Comment
   **/

  $this->pageHeader = $this->pageTitle = Yii::t('CommentsModule', 'Комментарии');

  $this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('CommentsModule', 'Комментарии'),
  );

  ?>
  <div class="link-to-markets-feedback">
    <a class="flr" href="<?=$this->createUrl('/market')?>"><?=Yii::t('CommentsModule', 'Отзывы о магазинах')?></a>
  </div>
  <div class="clear"></div>

  <?php $tabs = array(); ?>
  <?php foreach ($comments as $key=>$one) {
    $content = "<div class='feed'>";
      foreach($one['models'] as $model) {
        $content .= "<div class='feedback'>";
          $content .="<div class='feedback-author'>";
          $content .= $model->name." ";
          switch ($model->class_name) {
            case Comment::CLASS_PRODUCT:
              $content .= Yii::t('StoreModule', 'Отзыв о товаре');
              break;
            case Comment::CLASS_SITE:
              $content .= Yii::t('StoreModule', 'Отзыв о сайте');
              break;
            case Comment::CLASS_MARKET:
              $market = StoreMarket::model()->findByPk($model->object_pk);
              if ($market) {
                $content .= Yii::t('StoreModule', 'Отзыв о магазине')." <a href='".$this->createUrl('/market/view', array('id'=>$market->id))."'>".$market->name."</a>";
              } else {
                $content .= Yii::t('StoreModule', 'Отзыв о магазине');
              }
              break;
          }
          $content .= "<div class='feedback-date'>".Yii::app()->dateFormatter->formatDateTime(strtotime($model->created),'medium', 'short')."</div>";
        $content .= "</div>";
        $content .= "<div class='feedback-body'>";
          $content .= "<div class='shape'></div>";
          if ($model->class_name==Comment::CLASS_PRODUCT) {
            $content .= "<div class='product_block_review'>";
              $content .= "<div class='image'>";
                $product = StoreProduct::model()->findByPk($model->object_pk);
                if($product->mainImage) $imgSource = $product->mainImage->getUrl('190x150'); else $imgSource = 'http://placehold.it/190x150';
                $content .= CHtml::link(CHtml::image($imgSource, $product->mainImageTitle), array('/store/frontProduct/view', 'url'=>$product->url), array('class'=>'thumbnail'));
              $content .= "</div>";
              $content .= "<div class='name'>";
                $content .= CHtml::link(CHtml::encode($product->name), array('/store/frontProduct/view', 'url'=>$product->url));
              $content .= "</div>";
              $content .= "<div class='price'>";
                if($product->appliedDiscount) $content .= "<span style='color:red; '><s>".$product->toCurrentCurrency('originalPrice')."</s></span>";
                $content .= $product->priceRange();
              $content .= "</div>";
            $content .= "</div>";
          }
          $content .= $model->text;
        $content .= "</div>";
      $content .= "</div>";
      }
    $content .= "</div>";

    if (($one['pages']->currentPage+1) < $one['pages']->pageCount) {
      $content .= "<a class='load-more' data-page='".($one['pages']->currentPage+2)."' data-type='".$key."'><span>".Yii::t('CommentsModule', 'Загрузить еще')."</span></a>";
    }

    $tabs[$one['title']] = array('content'=>$content);
  }

  $this->widget('zii.widgets.jui.CJuiTabs', array(
    'id'=>'tabs',
    'tabs'=>$tabs
  ));
  ?>

    <?php


    Yii::app()->clientScript->registerScript('feedback-load-more', "
      $(document).ready(function(){
        $('.main-content').on('click', '.load-more', function(){
          var el = this;
          var url = '".$this->createUrl('')."?page='+$(this).data('page')+'&type='+$(this).data('type');
          $.get(url, function(data){
            $(el).replaceWith(data);
          });
        });
      });
    ");
  ?>
