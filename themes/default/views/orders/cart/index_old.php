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

if(empty($items))
{
	echo CHtml::openTag('h2');
	echo Yii::t('OrdersModule', 'Корзина пуста');
	echo CHtml::closeTag('h2');
	return;
}
?>

<h1 class="has_background"><?php echo Yii::t('OrdersModule', 'Оформление заказа') ?></h1>

<?php echo CHtml::form() ?>
<div class="order_products">
	<div>

		<?php foreach($items as $index=>$product): ?>
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
                <div>Добавлено:</div>
				<button class="small_silver_button plus">+</button>
				<?php echo CHtml::textField("quantities[$index]", $product['quantity'], array('class'=>'count', 'maxLength'=>'4')) ?>
				<button class="small_silver_button minus">&minus;</button>
			</div>
			<div class="cart-item_price">
                <span>Цена:</span>
				<?php
				echo CHtml::openTag('span', array('class'=>'price'));
				echo StoreProduct::formatPrice(Yii::app()->currency->convert($price * $product['quantity']));
				echo ' '.Yii::app()->currency->active->symbol;
				echo CHtml::closeTag('span');
				?>
			</div>
              <div class="cart-item_close">
                  <?php echo CHtml::link('', array('/orders/cart/remove', 'index'=>$index), array('class'=>'remove')) ?>
              </div>
          </div>
		<?php endforeach ?>

	</div>

	<div class="recount">
		<div class="silver_clean silver_button">
			<button class="recount gradient" name="recount" type="submit" value="1">Пересчитать</button>
		</div>
		<!--<span class="total">Всего:</span>
		<span id="total">
			<?php echo StoreProduct::formatPrice($totalPrice) ?>
			<?php echo Yii::app()->currency->active->symbol ?>
		</span>-->
	</div>

</div>

<div class="order_data">
	<div class="left">
		<div class="delivery rc5">
			<h2>Способ доставки</h2>
			<ul>
				<?php foreach($deliveryMethods as $delivery): ?>
				<li>
					<label class="radio">
						<?php
						echo CHtml::activeRadioButton($this->form, 'delivery_id', array(
							'checked'        => ($this->form->delivery_id == $delivery->id),
							'uncheckValue'   => null,
							'value'          => $delivery->id,
							'data-price'     => Yii::app()->currency->convert($delivery->price),
							'data-free-from' => Yii::app()->currency->convert($delivery->free_from),
							'onClick'        => 'recountOrderTotalPrice(this);',
						));
						?>
						<span><?php echo CHtml::encode($delivery->name) ?></span>
					</label>
					<p><?=$delivery->description?></p>
				</li>
				<?php endforeach; ?>
		</div>
	</div>

	<div class="user_data rc5">
		<h2>Адрес получателя</h2>

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
        <div class="total-text">Итог</div>
        <div class="total-back"></div>
    </div>
	<span id="orderTotalPrice" class="total"><span>= </span> <?php echo StoreProduct::formatPrice($totalPrice) ?></span>
	<span class="current_currency">
		<?php echo Yii::app()->currency->active->symbol; ?>
	</span>
	<button class="button-order" type="submit" name="create" value="1">Оформить</button>
</div>
<?php if ($other_markets) {?>
  <div class="warning">
    Внимание! Вы добавили товары из разных магазинов - при оформлении заказа товары будут разделены на отдельные заказы по магазинам.
  </div>
<?php } ?>

<?php echo CHtml::endForm() ?>