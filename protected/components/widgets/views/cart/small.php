<a href="<?=Yii::app()->createUrl('/cart')?>" class="cart button green-button">
  <div class="cart-text"><?=Yii::t('widget-cart','Корзина')?></div>
  <div class="cart-count"><?=count($items)?></div>
</a>
<div class="clear"></div>