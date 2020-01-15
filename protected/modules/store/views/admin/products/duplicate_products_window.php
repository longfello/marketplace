
<form action="">
	<ul>
		<li><label><input type="checkbox" name="copy[]" value="images" checked/> <?php echo Yii::t('StoreModule', 'Изображения') ?></label></li>
		<li><label><input type="checkbox" name="copy[]" value="attributes" checked/> <?php echo Yii::t('StoreModule', 'Характеристики') ?></label></li>
		<li><label><input type="checkbox" name="copy[]" value="related" checked/> <?php echo Yii::t('StoreModule', 'Сопутствующие продукты') ?></label></li>
		<li><label><input type="checkbox" name="copy[]" value="variants" checked/> <?php echo Yii::t('StoreModule', 'Варианты') ?></label></li>
	</ul>

	<div style="padding: 5px 0px 0px 0px;">
		<input type="checkbox" value="1" checked="checked"/>
		<a href="#" style="color: #309bbf" onclick="return checkAllDuplicateAttributes(this);"><?=Yii::t('StoreModule', 'Отметить все')?></a>
	</div>
</form>