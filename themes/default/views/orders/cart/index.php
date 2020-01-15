<?php

/**
 * Display cart
 * @var Controller $this
 * @var SCart $cart
 * @var $totalPrice integer
 */

Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/cart.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('cartScript', "var orderTotalPrice = '$totalPrice';", CClientScript::POS_HEAD);

$this->pageTitle = Yii::t('OrdersModule', 'Оформление заказа');

if(empty($markets))
{
	echo CHtml::openTag('h2');
	echo Yii::t('OrdersModule', 'Корзина пуста');
	echo CHtml::closeTag('h2');
	return;
}
?>

<h1 class="has_background"><?php echo Yii::t('OrdersModule', 'Оформление заказа') ?></h1>

<?php echo CHtml::form() ?>
<?php foreach ($markets as $market_id=>$market) {?>
    <div class="market"><?php echo Yii::t('OrdersModule', 'Продавец') ?>: <?php echo $market['market']->name;?></div>
    <div class="order_products">
      <div>
      <?php foreach($market['items'] as $product): ?>
        <div class="cart-item">

          <div class="cart-item_img">
            <?php
            // Display image
            if($product['model']->mainImage)
              $imgSource = $product['model']->mainImage->getUrl('100x100');
            else
              $imgSource = 'http://placehold.it/100x100';
            echo CHtml::image($imgSource, '', array('class'=>'thumbnail'));
            ?>
          </div>
          <div class="cart-item_titleprice">
            <?php
            $price = StoreProduct::calculatePrices($product['model'], $product['variant_models'], $product['configurable_id']);

            // Display product name with its variants and configurations
            echo CHtml::link(CHtml::encode($product['model']->name), array('/store/frontProduct/view', 'url'=>$product['model']->url)).'<br/>';

            // Price
            echo CHtml::openTag('span');
            echo StoreProduct::formatPrice(Yii::app()->currency->convert($price));
            echo ' '.Yii::app()->currency->active->symbol;
            echo CHtml::closeTag('span');

            // Display variant options
            if(!empty($product['variant_models']))
            {
              echo CHtml::openTag('span', array('class'=>'cartProductOptions'));
              foreach($product['variant_models'] as $variant)
                echo ' - '.$variant->attribute->title.': '.$variant->option->value.'<br/>';
              echo CHtml::closeTag('span');
            }

            // Display configurable options
            if(isset($product['configurable_model']))
            {
              $attributeModels = StoreAttribute::model()->findAllByPk($product['model']->configurable_attributes);
              echo CHtml::openTag('span', array('class'=>'cartProductOptions'));
              foreach($attributeModels as $attribute)
              {
                $method = 'eav_'.$attribute->name;
                echo ' - '.$attribute->title.': '.$product['configurable_model']->$method.'<br/>';
              }
              echo CHtml::closeTag('span');
            }
            ?>
          </div>
          <div class="cart-item_kol">
            <div><?=Yii::t('OrdersModule', 'Добавлено:')?></div>
            <?php /* <button class="small_silver_button plus">+</button> */ ?>
            <?php echo CHtml::textField("quantities[{$product['index']}]", $product['quantity'], array('class'=>'count', 'maxLength'=>'4', 'data-kol'=>$product['quantity'], 'onChange'=>'checkRecount(this);', 'onFocus'=>'showRecount(this);', 'onBlur'=>'checkRecount(this);')) ?>
            <?php /* <button class="small_silver_button minus">&minus;</button> */ ?>
            <button class="recount gradient hidden" name="recount" type="submit" value="1"></button>

          </div>
          <div class="cart-item_price">
            <span><?=Yii::t('OrdersModule', 'Цена:')?></span>
            <?php
            echo CHtml::openTag('span', array('class'=>'price'));
            echo StoreProduct::formatPrice(Yii::app()->currency->convert($price * $product['quantity']));
            echo ' '.Yii::app()->currency->active->symbol;
            echo CHtml::closeTag('span');
            ?>
          </div>
          <div class="cart-item_close">
            <?php echo CHtml::link('', array('/orders/cart/remove', 'index'=>$product['index']), array('class'=>'remove')) ?>
          </div>
        </div>
      <?php endforeach ?>
    </div>
  </div>
  <div class="order_data">
    <div class="left">
      <div class="delivery_desc">
        <?php echo $market['market']->delivery_desc;?>
      </div>
      <a href="#" style="display:none;" data-on="<?=Yii::t('OrdersModule', 'Развернуть')?>" data-off="<?=Yii::t('OrdersModule', 'Свернуть')?>" class="toggle_delivery_desc"></a>
      <div class="delivery rc5">
        <h3><?=Yii::t('OrdersModule', 'Способ доставки')?></h3>
        <select name="delivery_id[<?= $market_id; ?>]" class="order_delivery" onchange="recountDeliveryPrice();">
        <option data-market="<?= $market_id; ?>" data-price="0" data-free-from="0" value="0"><?=Yii::t('OrdersModule', 'Выберите способ доставки')?></option>
        <?php
          foreach($market['delivery'] as $delivery){
            $selected = ($this->form->delivery_id == $delivery->id)?'selected':'';
            echo "<option value=".$delivery->id." data-market=".$market_id." data-price=".Yii::app()->currency->convert($delivery->price)." data-free-from=".Yii::app()->currency->convert($delivery->free_from)." ".$selected.">".$delivery->name." ".$delivery->description." ".$delivery->price." ".Yii::app()->currency->active->symbol."</option>";
          }
        ?>
        </select>
        <div class="order_price">
          <?=Yii::t('OrdersModule', 'Цена заказа:')?>
          <span id="market_price_<?= $market_id; ?>" data-price="<?= $market['price']; ?>"><?= StoreProduct::formatPrice($market['price'])."</span> ".Yii::app()->currency->active->symbol; ?>
        </div>
      </div>
    </div>

  </div>
  <div class="clear"></div>

<?php } ?>

<!--
<div class="recount">
  <div class="silver_clean silver_button">
    <button class="recount gradient" name="recount" type="submit" value="1"><?=Yii::t('OrdersModule', 'Пересчитать')?></button>
  </div>
</div>
-->
<div class="order_data">
  <div class="user_data rc5">
    <h2><?=Yii::t('OrdersModule', 'Адрес получателя')?></h2>

    <div class="form wide">
      <?php echo CHtml::errorSummary($this->form); ?>

      <div class="row">
        <?php echo CHtml::activeLabel($this->form,'name', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($this->form,'name'); ?>
      </div>

      <div class="row">
        <?php echo CHtml::activeLabel($this->form,'email', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($this->form,'email'); ?>
      </div>

      <div class="row">
        <?php echo CHtml::activeLabel($this->form,'phone'); ?>
        <?php echo CHtml::activeTextField($this->form,'phone'); ?>
      </div>

      <div class="row">
        <?php echo CHtml::activeLabel($this->form,'address'); ?>
        <?php echo CHtml::activeTextField($this->form,'address'); ?>
      </div>

      <div class="row">
        <?php echo CHtml::activeLabel($this->form,'comment'); ?>
        <?php echo CHtml::activeTextArea($this->form,'comment'); ?>
      </div>
    </div>
  </div>
</div>

<div style="clear:both;"></div>

  <div class="has_background confirm_order">
    <div class="total-title">
      <div class="total-text"><?=Yii::t('OrdersModule', 'Итог')?></div>
      <div class="total-back"></div>
    </div>
    <span id="orderTotalPrice" class="total"><span>= </span> <?php echo StoreProduct::formatPrice($totalPrice) ?></span>
	  <span class="current_currency">
		  <?php echo Yii::app()->currency->active->symbol; ?>
	  </span>
    <button class="button-order" type="submit" name="create" value="1"><?=Yii::t('OrdersModule', 'Оформить')?></button>
  </div>
<?php if ($other_markets) {?>
  <div class="warning">
    <?=Yii::t('OrdersModule', 'Внимание! Вы добавили товары из разных магазинов - при оформлении заказа товары будут разделены на отдельные заказы по магазинам.')?>
  </div>
<?php } ?>

<?php echo CHtml::endForm() ?>