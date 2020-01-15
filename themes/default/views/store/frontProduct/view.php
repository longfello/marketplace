<?php
/**
 * Product view
 * @var StoreProduct $model
 * @var $this Controller
 */

// Set meta tags
$this->pageTitle = ($model->meta_title) ? $model->meta_title : $model->name;
$this->pageKeywords = $model->meta_keywords;
$this->pageDescription = $model->meta_description;

// Register main script
Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/product.view.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl.'/product.view.configurations.js', CClientScript::POS_END);

// Create breadcrumbs
if($model->mainCategory)
{
	$ancestors = $model->mainCategory->excludeRoot()->ancestors()->findAll();

	foreach($ancestors as $c)
		$this->breadcrumbs[$c->name] = $c->getViewUrl();

	// Do not add root category to breadcrumbs
	if ($model->mainCategory->id != 1)
		$this->breadcrumbs[$model->mainCategory->name] = $model->mainCategory->getViewUrl();
}

// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
));

?>

<div class="product">
	<?php
		$this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		));
	?>
    <div class="sravnit">
        <!--<div class="silver_clean silver_button">
            <button title="<?=Yii::t('core','Сравнить')?>" onclick="return addProductToCompare(<?php echo $model->id ?>);"><span class="icon compare"></span>Сравнить</button>
        </div>
        -->

        <?php
          if (!Yii::app()->user->isGuest) {
            $wishlist = new SWishList;
            $class = $wishlist->inList($model->id)?"already":'';
        ?>
        <div class="silver_clean silver_button">
            <button class='btn_add2wishlist <?=$class?>' title="<?=Yii::t('core','В список желаний')?>" onclick="return addProductToWishList(<?php echo $model->id ?>);"></button>
        </div>
        <?php } ?>
    </div>
    <h1><?php echo CHtml::encode($model->name); ?></h1>
    <div class="tover-price-buy">
        <?php echo CHtml::form(array('/orders/cart/add')) ?>
        <?php $this->renderPartial('_configurations', array('model'=>$model)); ?>
        <div class="errors" id="productErrors"></div>
        <div class="priceProduct">
            <span id="productPrice"><?php echo StoreProduct::formatPrice($model->toCurrentCurrency()); ?></span>
            <?php echo Yii::app()->currency->active->symbol; ?>
        </div>
        <div class="actions">
            <?php
            echo CHtml::hiddenField('product_id', $model->id);
            echo CHtml::hiddenField('product_price', $model->price);
            echo CHtml::hiddenField('use_configurations', $model->use_configurations);
            echo CHtml::hiddenField('currency_rate', Yii::app()->currency->active->rate);
            echo CHtml::hiddenField('configurable_id', 0);
            echo CHtml::hiddenField('quantity', 1);

            if($model->isAvailable)
            {
                echo CHtml::ajaxSubmitButton(Yii::t('StoreModule','Купить'), array('/orders/cart/add'), array(
                    'dataType' => 'json',
                    'success'  => 'js:function(data, textStatus, jqXHR){processCartResponse(data, textStatus, jqXHR)}',
                ), array(
                    'id'=>'buyButton',
                    'class'=>'blue_button'
                ));
            }
            else
            {
                echo CHtml::link(Yii::t('StoreModule','Сообщить о появлении'), '#', array(
                  'onclick' => 'showNotifierPopup('.$model->id.'); return false;',
                  'class'   => 'notify_link buy',
                ));
            }

            echo CHtml::endForm();
            ?>


        </div>
        <div style="clear: both;font-size: 14px;">
            <?php
            if($model->appliedDiscount)
                echo '<span style="color:red; "><s>'.$model->toCurrentCurrency('originalPrice').' '.Yii::app()->currency->active->symbol.'</s></span>';
            ?>
        </div>
      <?php
        echo CHtml::link($model->market->name, $this->createUrl('/market/'.$model->market_id));
      ?>
    </div>
  <div class="stars">
    <?php $this->widget('CStarRating',array(
        'name'=>'rating_'.$model->id,
        'id'=>'rating_'.$model->id,
        'allowEmpty'=>false,
        'readOnly'=>true,
        'minRating'=>-2,
        'maxRating'=>2,
        'value'=>$model->getRate(),
        'htmlOptions' => array(
          'class' => 'stars'
        )
    )); ?>
  </div>
	<div class="images">
		<div class="image_row">

			<div class="main">
				<?php
					// Main product image
					if($model->mainImage)
						echo CHtml::link(CHtml::image($model->mainImage->getUrl('340x250', 'resize'), $model->mainImage->title), $model->mainImage->getUrl(), array('class'=>'thumbnail'));
					else
						echo CHtml::link(CHtml::image('http://placehold.it/340x250'), '#', array('class'=>'thumbnail'));
				?>
			</div>

		</div>
		<div class="additional">
			<ul>
			<?php
			// Display additional images
			foreach($model->imagesNoMain as $image)
			{
				echo CHtml::openTag('li', array('class'=>'span2'));
				echo CHtml::link(CHtml::image($image->getUrl('160x120'), $image->title), $image->getUrl(), array('class'=>'thumbnail'));
				echo CHtml::closeTag('li');
			}
			?>
			</ul>
		</div>
	</div>

	<div style="clear:both;"></div>



  <div class="social-network-widgets">
    <?php $this->widget('application.components.widgets.likes'); ?>
  </div>


    <div class="clear"></div>
    <div class="border_tovar"></div>

    <div class="info">
      <?php if ($model->full_description) { ?>
        <div class="desc"><?php echo $model->full_description; ?></div>
        <div class="border_tovar"></div>
      <?php } elseif ($model->short_description) { ?>
        <div class="desc"><?php echo $model->short_description; ?></div>
        <div class="border_tovar"></div>
      <?php } ?>
    </div>

  <?php
    if ($model->market->markers) {
      ?>
      <div class="market-addresses">
        <div class="market-addresses-title"><?=Yii::t('StoreModule', 'Представительства магазина').' '.$model->market->name ?></div>
        <ul>
          <?php foreach($model->market->markers as $marker){ ?>
            <li>
              <?php /* @var $marker StoreMarketMarkers*/ ?>
              <?= $marker->view ?>
            </li>
          <?php }  ?>
        </ul>
      </div>
      <div class="border_tovar"></div>
    <?php
    }
  ?>

	<?php
		$tabs = array();

		// EAV tab
		if($model->getEavAttributes())
		{
      $this->renderPartial('_attributes', array('model'=>$model));
		}
    ?>

    <?php $this->widget('application.components.widgets.someProducts', array('model' => $model )); ?>

    <?php
		// Comments tab
		$this->renderPartial('comments.views.comment.create', array('model'=>$model));

		// Related products tab
    /*
		if($model->relatedProductCount)
		{
			$tabs[Yii::t('StoreModule', 'Сопутствующие продукты').' ('.$model->relatedProductCount.')'] = array(
				'id'=>'related_products_tab',
				'content'=>$this->renderPartial('_related', array(
					'model'=>$model,
				), true));
		}
    */

		// Render tabs
    /*
		$this->widget('zii.widgets.jui.CJuiTabs', array(
			'id'=>'tabs',
			'tabs'=>$tabs
		));

		// Fix tabs opening by anchor
		Yii::app()->clientScript->registerScript('tabSelector', '
			$(function() {
				var anchor = $(document).attr("location").hash;
				var result = $("#tabs").find(anchor).parents(".ui-tabs-panel");
				if($(result).length)
				{
					$("#tabs").tabs("select", "#"+$(result).attr("id"));
				}
			});
		');
    */
	?>
    <div class="border_tovar"></div>
  <div class="social-share">
    <?php
      $imgHref = ($model->mainImage)?Yii::app()->getBaseUrl(true).$model->mainImage->getUrl('340x250', 'resize'):'http://placehold.it/340x250';
      Yii::app()->clientScript->registerLinkTag("image_src", null, $imgHref);
      $this->widget('application.components.widgets.likes', array('layout' => 'share', 'image' => $imgHref));
    ?>
  </div>
</div>
