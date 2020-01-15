<a href="<?=Yii::app()->createUrl('users/profile/addmessage')?>"><?=Yii::t('UsersModule', 'Добавить');?></a>

<?php foreach ($themes as $one) { ?>
  <div class="one_theme">
    <div class="theme_name"><a href="<?=Yii::app()->createUrl('users/profile/viewmessage', array('id' => $one->id))?>"><?= $one->name; ?></a></div>
    <div class="theme_last_msg"><?= substr(strip_tags($one->messages[0]->text),0,200); if(strlen($one->messages[0]->text)>200) echo "...";?></div>
    <div class="theme_last_date"><?=$one->last_msg;?></div>
  </div>
<?php }?>