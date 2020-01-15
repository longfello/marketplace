<div class="feedback">
  <div class='feedback-author'>
    <?php
    switch ($data['class_name']) {
    case Comment::CLASS_PRODUCT:
    ?>
    <?=Yii::t('StoreModule', 'Отзыв о товаре') ?>
    <?php
    break;
    case Comment::CLASS_SITE:
    ?>
    <?= Yii::t('StoreModule', 'Отзыв о сайте') ?>
    <?php
    break;
    case Comment::CLASS_MARKET:
    $market = StoreMarket::model()->findByPk($data['object_pk']);
    ?>
    <?=Yii::t('StoreModule', 'Отзыв о магазине') ?> <a href="<?=$this->createUrl('/market/view', array('id'=>$market->id))?>"><?=$market->name?></a>
    <?php
    break;
    }
    ?>
    <div class="feedback-date"><?=date('d.m.Y,  H:i', CDateTimeParser::parse($data->created,'yyyy-MM-dd hh:mm:ss')); ?></div>
  </div>
  <div class="feedback-body">
    <div class="shape"></div>
    <?php if ($data['class_name']==Comment::CLASS_PRODUCT) {?>
      <div class="product_block_review">
        <div class="image">
          <?php
          $product = StoreProduct::model()->findByPk($data['object_pk']);
          if($product->mainImage)
            $imgSource = $product->mainImage->getUrl('190x150');
          else
            $imgSource = 'http://placehold.it/190x150';
          echo CHtml::link(CHtml::image($imgSource, $product->mainImageTitle), array('/store/frontProduct/view', 'url'=>$product->url), array('class'=>'thumbnail'));
          ?>
        </div>
        <div class="name">
          <?php echo CHtml::link(CHtml::encode($product->name), array('/store/frontProduct/view', 'url'=>$product->url)) ?>
        </div>
        <div class="price">
          <?php
          if($product->appliedDiscount)
            echo '<span style="color:red; "><s>'.$product->toCurrentCurrency('originalPrice').'</s></span>';
          ?>
          <?php echo $product->priceRange() ?>
        </div>
      </div>
    <?php } ?>
    <?=$data->text?>
  </div>
</div>