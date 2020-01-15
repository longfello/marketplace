<div class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
	</div>
	<div class="author">
		<?=Yii::t('news', 'posted by')?> <?php echo $data->author->login . ', ' . date('d.m.Y H:i:s',$data->create_time); ?>
	</div>
	<div class="content">
		<?=$data->teaser?$data->teaser:$data->content;?>
	</div>
</div>
