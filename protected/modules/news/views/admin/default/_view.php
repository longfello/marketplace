<div class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
	</div>
	<div class="author">
		<?= /* Yii::t('NewsModule', 'Опубликовал')?> <?php echo $data->author->login . ', ' . */ date('d.m.Y H:i:s',$data->create_time); ?>
	</div>
	<div class="content">
		<?php
			$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
			echo $data->content;
			$this->endWidget();
		?>
	</div>
	<div class="nav">
		<?=Yii::t('NewsModule', 'Последнее редактирование')?> <?php echo date('d.m.Y H:i:s',$data->update_time); ?>
	</div>
</div>
