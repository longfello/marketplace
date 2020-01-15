<?php

/**
 * Site start view
 */
?>

<div class="banners" align="center">
	<a href="/products/search/q/Apple"><img src="/themes/default/assets/images/mainPageBanner.png"></a>
</div>

<div class="wide_line">
	<span><?=Yii::t('StoreModule', 'Популярные товары')?></span>
</div>

<div class="products_list">
	<?php
		foreach($popular as $p)
			$this->renderPartial('_product', array('data'=>$p));
	?>
</div>

<?php $this->beginClip('underFooter'); ?>
<div style="clear:both;"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#shares .share_list ul li a").click(function(){
			$("#shares .share_list ul li").removeClass('active');
			$(this).parent().addClass('active');
			$("#shares .products_list").load('/store/index/renderProductsBlock/'+$(this).attr('href'));
			return false;
		});
	});
</script>

<div id="shares">
	<div class="shared_products">
		<div class="share_list">
			<ul>
				<li class="active"><a href="newest"><?=Yii::t('StoreModule', 'Новинки')?></a></li>
				<li><a href="added_to_cart"><?=Yii::t('StoreModule', 'Хиты продаж')?></a></li>
			</ul>
		</div>

		<div style="clear:both;"></div>

		<div class="products_list">
			<?php
			foreach($newest as $p)
				$this->renderPartial('_product', array('data'=>$p));
			?>
		</div>
	</div>
</div>


<div class="centered">
	<div class="wide_line">
		<span><?=Yii::t('StoreModule', 'Новости')?></span>

	</div>

	<ul class="news">
		<?php foreach($news as $n): ?>
		<li>
			<span class="date"><?php echo $n->created ?></span>
			<a href="<?php echo $n->viewUrl ?>" class="title"><?php echo $n->title ?></a>
			<p><?php echo $n->short_description ?></p>
		</li>
		<?php endforeach; ?>
	</ul>

	<div class="all_news">
		<a href="<?=$n->category->viewUrl?>"><?=Yii::t('StoreModule', 'Все новости')?></a>
	</div>
</div>
<?php $this->endClip(); ?>