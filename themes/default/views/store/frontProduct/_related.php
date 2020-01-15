<?php

/**
 * Display related product
 * @var StoreProduct $model
 */
?>
<div class="products_list">
	<?php foreach($model->relatedProducts as $data):  ?>
		<div class="product_block">
			<div class="image">
				<?php
				if($data->mainImage)
					$imgSource = $data->mainImage->getUrl('190x150');
				else
					$imgSource = 'http://placehold.it/190x150';
				echo CHtml::link(CHtml::image($imgSource), array('frontProduct/view', 'url'=>$data->url), array('class'=>'thumbnail'));
				?>
			</div>
			<div class="name">
				<?php echo CHtml::link(CHtml::encode($data->name), array('frontProduct/view', 'url'=>$data->url)) ?>
			</div>
      <div class="rating">
        <i class="star star-<?=$data->votes?round($data->rating/$data->votes):0?>"></i>
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

				echo CHtml::ajaxSubmitButton(Yii::t('StoreModule','Купить'), array('/orders/cart/add'), array(
					'id'=>'addProduct'.$data->id,
					'dataType'=>'json',
					'success'=>'js:function(data, textStatus, jqXHR){processCartResponseFromList(data, textStatus, jqXHR, "'.Yii::app()->createAbsoluteUrl('/store/frontProduct/view', array('url'=>$data->url)).'")}',
				), array('class'=>'blue_button'));
				?>
				<button class="small_silver_button" title="<?=Yii::t('core','Сравнить')?>" onclick="return addProductToCompare(<?php echo $data->id ?>);"><span class="compare">&nbsp</span></button>
				<?php echo Chtml::endForm() ?>
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
		</div>
	<?php endforeach; ?>
</div>


<div style="clear: both;"></div>