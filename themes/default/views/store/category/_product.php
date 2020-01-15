<?php

/**
 * @var StoreProduct $data
 */
?>

<div class="product_block item">
  <div class="type">
    <div class="type-text"><?=$data->mainCategory->name?></div>
    <div class="type-back"></div>
  </div>
	<div class="image">
		<?php
		if($data->mainImage)
			$imgSource = $data->mainImage->getUrl('190x150');
		else
			$imgSource = 'http://placehold.it/190x150';
		echo CHtml::link(CHtml::image($imgSource, $data->mainImageTitle), array('frontProduct/view', 'url'=>$data->url), array('class'=>'thumbnail'));
		?>
	</div>
	<div class="name">
    <?php echo CHtml::link(CHtml::encode($data->name), array('frontProduct/view', 'url'=>$data->url)) ?>
	</div>
  <div class="rating">
    <i class="star star-<?=$data->votes?round($data->rating/$data->votes):0?>"></i>
  </div>
  <div class="reviews">
    <?php echo CHtml::link(Yii::t('StoreModule','Отзывы'), Yii::app()->createUrl('/product/'.$data->url).'#comments') ?>
  </div>
	<div class="price">
		<?php
		if($data->appliedDiscount)
			echo '<span style="color:red; "><s>'.$data->toCurrentCurrency('originalPrice').'</s></span>';
		?>
		<?php echo $data->priceRange() ?>
	</div>

	<div class="actions">
			<?php
				echo CHtml::form(array('/orders/cart/add'));
				echo CHtml::hiddenField('product_id', $data->id);
				echo CHtml::hiddenField('product_price', $data->price);
				echo CHtml::hiddenField('use_configurations', $data->use_configurations);
				echo CHtml::hiddenField('currency_rate', Yii::app()->currency->active->rate);
				echo CHtml::hiddenField('configurable_id', 0);
				echo CHtml::hiddenField('quantity', 1);

		if($data->getIsAvailable())
		{
			echo CHtml::ajaxSubmitButton(Yii::t('StoreModule','Купить'), array('/orders/cart/add'), array(
				'id'=>'addProduct'.$data->id,
				'dataType'=>'json',
				'success'=>'js:function(data, textStatus, jqXHR){processCartResponseFromList(data, textStatus, jqXHR, "'.Yii::app()->createAbsoluteUrl('/store/frontProduct/view', array('url'=>$data->url)).'")}',
			), array('class'=>'blue_button'));
		}
		else
		{
			echo CHtml::link(Yii::t('StoreModule','Нет в наличии'), '#', array(
				'onclick' => 'showNotifierPopup('.$data->id.'); return false;',
				'class'   => 'notify_link buy',
			));
		}


			?>
			<button class="small_silver_button" title="<?=Yii::t('core','Сравнить')?>" onclick="return addProductToCompare(<?php echo $data->id ?>);"><span class="compare">&nbsp</span></button>
			<?php echo CHtml::endForm() ?>
	</div>

  <?php
  if (!Yii::app()->user->isGuest) {
    $wishlist = new SWishList;
    $class = $wishlist->inList($data->id)?"already":'';
    ?>
    <div class="silver_clean silver_button">
      <button class='btn_add2wishlist <?=$class?>' title="<?=Yii::t('core','В список желаний')?>" onclick="return addProductToWishList(<?php echo $data->id ?>);"></button>
    </div>
  <?php } ?>

  <div class="social-share social-share-test">
    <?php
      $imgHref = ($data->mainImage)?Yii::app()->getBaseUrl(true).$data->mainImage->getUrl('340x250', 'resize'):'http://placehold.it/340x250';
      Yii::app()->clientScript->registerLinkTag("image_src", null, $imgHref);
      $this->widget('application.components.widgets.likes', array('layout' => 'share-product', 'image' => $imgHref, 'link' => $this->createUrl('frontProduct/view', array('url'=>$data->url))));
    ?>
  </div>


</div>