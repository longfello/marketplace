<?php

/**
 * @var $profile UserProfile
 * @var $user User
 * @var $form CActiveForm
 * @var $changePasswordForm ChangePasswordForm
 */

$this->pageTitle=Yii::t('UsersModule', 'Личный кабинет');
?>

<div class="welcome">
  <?=Yii::t('UsersModule', 'Добро пожаловать');?>, <?php echo $profile->full_name; ?>! <img src="<?=Yii::app()->theme->baseUrl;?>/assets/images/smile.png" alt="">
  <a href="<?=Yii::app()->createUrl('users/logout')?>" class="logout"><?=Yii::t('widget-login', 'logout');?></a>
</div>

<div class="person-info">
  <div class="profile-nav">
    <div class="ul-title"><span><?=Yii::t('UsersModule', 'Персональные данные');?></span><a class="edit-info" href="#"><?=Yii::t('UsersModule', 'Свернуть');?></a></div>
    <img src="<?=$profile->getPhotoUrl(64)?>">
    <ul>
      <li><?=Yii::t('UsersModule', 'Вас зовут');?>: <span><?=$profile->full_name;?></span><a class="edit-info" href="<?=Yii::app()->createUrl('users/profile/edit')?>"><?=Yii::t('UsersModule', 'Изменить');?></a></li>
      <li><?=Yii::t('UsersModule', 'Логин');?>: <span><?=$user->email;?></span><a class="edit-info" href="<?=Yii::app()->createUrl('users/profile/edit')?>"><?=Yii::t('UsersModule', 'Изменить');?></a></li>
      <li><?=Yii::t('UsersModule', 'Телефон контакта');?>: <span><?=$profile->phone;?></span><a class="edit-info" href="<?=Yii::app()->createUrl('users/profile/edit')?>"><?=Yii::t('UsersModule', 'Изменить');?></a></li>
      <li><?=Yii::t('UsersModule', 'Адрес доставки');?>: <span><?=$profile->delivery_address;?></span><a class="edit-info" href="<?=Yii::app()->createUrl('users/profile/edit')?>"><?=Yii::t('UsersModule', 'Изменить');?></a></li>
      <li><?=Yii::t('UsersModule', 'Дата рождения');?>: <span><?=$profile->birthday;?></span><a class="edit-info" href="<?=Yii::app()->createUrl('users/profile/edit')?>"><?=Yii::t('UsersModule', 'Изменить');?></a></li>
    </ul>
  </div>
</div>

<div class="person-support">
  <div class="profile-nav">
    <div class="ul-title"><span>Поддержка</span><a class="edit-info" href="#">Свернуть</a></div>
    <ul>
      <li>У вас есть вопросы? <a href="<?=Yii::app()->createUrl('users/profile/managercall')?>" class="phone-ico">Заказать звонок менеджера</a></li>
      <li>Есть пожелания к сервису Rivori? <a href="mailto:foo@mail.com?subject=Запрос в службу тех. поддержки" class="convert-ico">Написать в службу тех. поддержки</a></li>
      <li>Нужна консультация по товарам? <a href="javascript: $('#sh_button').click();" class="man-ico">Обратиться к онлайн консультанту</a></li>
    </ul>
  </div>
</div>

<!-- Start SiteHeart code -->
<script>
  (function(){
    var widget_id = 693142;
    _shcp =[{widget_id : widget_id}];
    var lang =(navigator.language || navigator.systemLanguage
      || navigator.userLanguage ||"en")
      .substr(0,2).toLowerCase();
    var url ="widget.siteheart.com/widget/sh/"+ widget_id +"/"+ lang +"/widget.js";
    var hcc = document.createElement("script");
    hcc.type ="text/javascript";
    hcc.async =true;
    hcc.src =("https:"== document.location.protocol ?"https":"http")
      +"://"+ url;
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hcc, s.nextSibling);
  })();
</script>
<!-- End SiteHeart code -->