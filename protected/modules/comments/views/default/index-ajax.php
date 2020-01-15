<?php
  /**
   * Display comments list
   *
   * @var $model Comment
   * @var $pages CPagination
   **/
  foreach($models as $model) {
    ?>
    <div class="feedback">
      <div class="feedback-author">
        <?=$model->name?>
        <?php
        switch ($model->class_name) {
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
            $market = StoreMarket::model()->findByPk($model->object_pk);
            ?>
            <?=Yii::t('StoreModule', 'Отзыв о магазине') ?> <a href="<?=$this->createUrl('/market/view', array('id'=>$market->id))?>"><?=$market->name?></a>
            <?php
            break;
        }
        ?>
        <div class="feedback-date"><?=Yii::app()->dateFormatter->formatDateTime(strtotime($model->created),'medium', 'short')?></div>
      </div>
      <div class="feedback-body">
        <div class="shape"></div>
        <?php if ($model->class_name==Comment::CLASS_PRODUCT) {?>
          <div class="product_block_review">
            <div class="image">
              <?php
              $product = StoreProduct::model()->findByPk($model->object_pk);
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
        <?=$model->text?>
      </div>
    </div>
    <?php
  }

  if (($pages->currentPage+1) < $pages->pageCount) {
    ?>
    <a class="load-more" data-page="<?= ($pages->currentPage+2) ?>" data-type="<?= $type ?>"><span><?= Yii::t('CommentsModule', 'Загрузить еще')?></span></a>
  <?php
  }

