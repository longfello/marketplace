<tr class="<?=$class?>">
	<td style="width: 250px">
    <?php if (Yii::app()->user->getIsManager()) { ?>
      <?=$data->username?>
    <? } else  { ?>
      <a href="<?=Yii::app()->createUrl('/admin/users/default/update', array('id'=>$data->user_id))?>"><?=$data->username?></a>
    <?php } ?>
		<br>
		<span class="date"><?=$data->created?></span>
	</td>
	<td style="width: 400px">
		<?php
			foreach($data->getDataBefore() as $key=>$val)
				echo CHtml::encode("$key: {$val}").'<br>';
		?>
	</td>
	<td style="width: 400px">
		<?php
			foreach($data->getDataAfter() as $key=>$val)
				echo CHtml::encode("$key: {$val}").'<br>';
		?>
	</td>
</tr>