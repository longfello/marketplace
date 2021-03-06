<?php

/**
 * Import
 */

$this->pageHeader = Yii::t('CsvModule', 'Импорт продуктов');

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('CsvModule', 'Модули')=>Yii::app()->createUrl('/core/admin/systemModules'),
	Yii::t('CsvModule', 'Импорт')
);

?>

<style type="text/css">
	.attributesTable tr td {
		padding:3px 3px 3px 3px;
		border:1px solid silver;
	}
	.attributesTable {
		margin: 5px 0 5px 0;
		border:1px solid silver;
	}
	.attributesTable tr:hover{
		background-color: #F9F9F9;
	}
	.importDescription ul li {
		list-style: decimal;
		margin-left: 20px;
	}
</style>

<div class="padding-all form wide">
	<!-- Left column -->
	<div class="yui-u first">

		<?php if($importer->hasErrors()): ?>
		<div class="errorSummary"><p>Ошибки импорта:</p>
			<ul>
				<?php
					$i=0;
					foreach($importer->getErrors() as $error)
					{
						if($i<10)
						{
							if($error['line']>0)
								echo "<li>".Yii::t('CsvModule','Строка').": ".$error['line'].". ".$error['error']."</li>";
							else
								echo "<li>".$error['error']."</li>";
						}
						else
						{
							$n=count($importer->getErrors())-$i;
							echo '<li>'.Yii::t('CsvModule','и еще({n}).', array('{n}'=>$n)).'</li>';
							break;
						}
						$i++;
					}
				?>
			</ul>
		</div>
		<?php endif; ?>

		<?php if($importer->stats['created']>0 OR $importer->stats['updated']>0) :?>
		<div class="successSummary">
			<?php echo Yii::t('CsvModule','Создано продуктов: ').$importer->stats['created']; ?><br/>
			<?php echo Yii::t('CsvModule','Обновлено продуктов: ').$importer->stats['updated']; ?>
		</div>
		<?php endif ?>

		<?php $form = $this->beginWidget('CActiveForm', array(
			'id'=>'fileUploadForm',
			'htmlOptions'=>array('enctype'=>'multipart/form-data')
		)); ?>

		<div class="row">
			<input type="file" name="file" class="file">
			<input type="submit" value="<?php echo Yii::t('CsvModule', 'Начать импорт') ?>">
		</div>

		<div class="row" style="height: 25px;">
			<label style="width: 300px"><input type="checkbox" name="create_dump" value="1" checked="checked" /> Создать резервную копию БД.</label>
		</div>

		<div class="row">
			<label style="width: 300px;"><input type="checkbox" name="remove_images" value="1" checked="checked" /> Удалить загруженные картинки</label>
		</div>

		<?php $this->endWidget(); ?>

		<div style="clear: both;">
			<hr>
		</div>

		<div class="importDescription">
			<ul>
				<li><?php echo Yii::t('CsvModule','Первой строкой файла должны быть указаны колонки для импорта.')?></li>
				<li><?php echo Yii::t('CsvModule','Разделитель поля - точка с запятой(;).')?></li>
				<li><?php echo Yii::t('CsvModule','Колонки name, category, type, price - обязательны.')?></li>
				<li><?php echo Yii::t('CsvModule','Файл должен иметь кодировку UTF-8 или CP1251.')?></li>
			</ul>
			<br/>
			<a href="<?php echo $this->createUrl('sample') ?>"><?php echo Yii::t('CsvModule','Пример файла')?></a>
		</div>

	</div>

	<!-- Right column -->
	<div class="yui-u">
		<b><?php echo Yii::t('CsvModule', 'Доступны следующие поля:') ?></b>
		<table class="attributesTable">
			<?php
			foreach($importer->getImportableAttributes() as $k=>$v)
			{
				echo '<tr>';
				echo '<td width="200px">'.CHtml::encode($v).'</td>';
				echo '<td>'.$k.'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
</div>
