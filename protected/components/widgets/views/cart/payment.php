<div class="inner-cart">
  <a class="cart-top" href="<?= Yii::app()->createUrl('/delivery')?>"><?=Yii::t('widget-cart','Способы оплаты')?></a>

  <a href="<?=Yii::app()->createUrl('/cart')?>" class="cart-bottom">
    <div class="cart-text"><?=Yii::t('widget-cart','Корзина')?></div>
    <div class="cart-count" href="#"><?=count($items)?></div>
  </a>
</div>
<div class="clear"></div>