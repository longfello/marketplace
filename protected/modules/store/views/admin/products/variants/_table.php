<?php
/**
 * Represent attribute row on "Variants" tab
 *
 * @var StoreAttribute $attribute
 */
?>

<table class="variantsTable" id="variantAttribute<?php echo $attribute->id ?>">
	<thead>
	<tr>
		<td colspan="6">
			<h4>
				<?php
					echo CHtml::encode($attribute->title);
					echo CHtml::link(' +', '#', array(
						'rel'=>'#variantAttribute'.$attribute->id,
						'onclick'=>'js: return cloneVariantRow($(this));'
				));
				?>
			</h4>
			<?php
				echo CHtml::link(Yii::t('StoreModule', ' Добавить опцию'), '#', array(
					'rel'       => $attribute->id,
					'onclick'   => 'js: return addNewOption($(this));',
					'data-name' => $attribute->getIdByName(),
			));
			?>
		</td>
	</tr>
	<tr>
		<td><?=Yii::t('StoreModule', 'Значение')?></td>
		<td><?=Yii::t('StoreModule', 'Цена')?></td>
		<td><?=Yii::t('StoreModule', 'Тип цены')?></td>
		<td><?=Yii::t('StoreModule', 'Артикул')?></td>
		<td></td>
	</tr>
	</thead>
	<tbody>
	<?php
	if(!isset($options)):
		?>
	<tr>
		<td>
			<?php
			echo CHtml::dropDownList('variants['.$attribute->id.'][option_id][]', null, CHtml::listData($attribute->options, 'id', 'value'), array('class'=>'options_list'));
			?>
		</td>
		<td>
			<input type="text" name="variants[<?php echo $attribute->id ?>][price][]">
		</td>
		<td>
			<?php echo CHtml::dropDownList('variants['.$attribute->id.'][price_type][]', null, array(0=>Yii::t('StoreModule', 'Фиксированная'), 1=>Yii::t('StoreModule', 'Процент'))); ?>
		</td>
		<td>
			<input type="text" name="variants[<?php echo $attribute->id ?>][sku][]">
		</td>
		<td>
			<a href="#" onclick="return deleteVariantRow($(this));"><?=Yii::t('StoreModule', 'Удалить')?></a>
		</td>
	</tr>
		<?php
	endif;
	?>
	<?php
	if(isset($options)):
		foreach($options as $o):
			?>
		<tr>
			<td>
				<?php
				echo CHtml::dropDownList('variants['.$attribute->id.'][option_id][]', $o->option->id, CHtml::listData($attribute->options, 'id', 'value'), array('class'=>'options_list'));
				?>
			</td>
			<td>
				<input type="text" name="variants[<?php echo $attribute->id ?>][price][]" value="<?php echo $o->price ?>">
			</td>
			<td>
				<?php echo CHtml::dropDownList('variants['.$attribute->id.'][price_type][]', $o->price_type, array(0=>Yii::t('StoreModule', 'Фиксированная'), 1=>Yii::t('StoreModule', 'Процент'))); ?>
			</td>
			<td>
				<input type="text" name="variants[<?php echo $attribute->id ?>][sku][]" value="<?php echo $o->sku ?>">
			</td>
			<td>
				<a href="#" onclick="return deleteVariantRow($(this));"><?=Yii::t('StoreModule', 'Удалить')?></a>
			</td>
		</tr>
			<?php
		endforeach;
	endif;
	?>
	</tbody>
</table>