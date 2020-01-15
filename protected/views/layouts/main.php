<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
  <meta name="language" content="en"/>
  <meta name="title" content="<?=CHtml::encode($this->metaTitle?$this->metaTitle:$this->pageTitle)?>">
  <meta name="description" content="<?php echo CHtml::encode($this->metaDescription); ?>" />
  <meta name="keywords" content="<?php echo CHtml::encode($this->metaKeywords); ?>" />
  <link href="<?=Yii::app()->getBaseUrl(true);?>/img/site/logo.png" rel="image_src">

  <!--[if lt IE 9]>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection"/>
  <![endif]-->

  <?php
    /*
    Helper::registerCSS('bootstrap.min.css');
    Helper::registerCSS('bootstrap-theme.min.css');
    Helper::registerCSS('main.css');
    Helper::registerCSS('form.css');
    */

    Yii::import('application.modules.store.components.SCompareProducts');
    Yii::import('application.modules.store.models.wishlist.StoreWishlist');
    $assetsManager = Yii::app()->clientScript;
    $assetsManager->registerCoreScript('jquery');
    $assetsManager->registerCoreScript('jquery.ui');
    $assetsManager->registerCoreScript('jquery-migrate');
  $assetsManager->registerScript('popapClose', "
$(document).ready(function(){
  $('.background_popup').on('click', function(e){
    e.preventDefault();
    $('.choose-city').hide();
    $('body').removeClass('popup');
  })
});", CClientScript::POS_END);

    // jGrowl notifications
    Yii::import('ext.jgrowl.Jgrowl');
    Jgrowl::register();

    // Disable jquery-ui default theme
    $assetsManager->scriptMap=array(
      'jquery-ui.css'=>false,
    );

  ?>

  <?php Helper::registerCSS('style.css'); ?>
  <?php Helper::registerCSS('jquery-ui-1.8.19.custom.css'); ?>
  <?php Helper::registerJS('script.js', CClientScript::POS_HEAD); ?>
  <?php Helper::registerJS('common.js', CClientScript::POS_END); ?>

  <title><?php
      $page = (int)Yii::app()->request->getQuery('page', 1);
      echo CHtml::encode($this->pageTitle?$this->pageTitle:($this->pageHeader?$this->pageHeader:Yii::app()->name) . ($page > 1 ? ' - '.Yii::t('main', 'Страница').' ' . $page : ''));
  ?></title>
</head>

<body class="front <?= ($this->hasLeftColumn)?'lc-'.$this->hasLeftColumn:'' ?> <?= ($this->hasRightColumn)?'rc-'.$this->hasRightColumn:'' ?>">
  <?php
    // Notifier module form
    Yii::import('application.modules.notifier.NotifierModule');
    NotifierModule::renderDialog();
  ?>
  <div id="page-wrapper">
    <div id="page">
      <div class="wrapper-for-bg">
        <div id="header">
          <div id="header-inner">
            <a href="/"><img src="/img/site/logo.png" alt="<?=Yii::t('core','Интернет-магазин Ривори')?>" class="logo"/></a>
            <?php $this->widget('application.components.widgets.top_menu'); ?>
            <?php $this->widget('application.components.widgets.chooseLanguage'); ?>
            <?php $this->widget('application.components.widgets.cityChooser'); ?>
          </div>
        </div>
        <div id="content">
          <div id="content-inner">
            <div id="slogan">
              <span><?=Yii::t('core','Интернет-магазин, в котором вы найдете все:')?></span>
              <img src="/img/site/top/computer.png" alt=""/>
              <img src="/img/site/top/headphones.png" alt=""/>
              <img src="/img/site/top/ipod.png" alt=""/>
              <img src="/img/site/top/cd.png" alt=""/>
              <img src="/img/site/top/iphone.png" alt=""/>
              <img src="/img/site/top/camera.png" alt=""/>
              <img src="/img/site/top/trolley.png" alt=""/>
              <img src="/img/site/top/house.png" alt=""/>
              <img src="/img/site/top/compass.png" alt=""/>
              <img src="/img/site/top/spanner.png" alt=""/>
              <img src="/img/site/top/coffee.png" alt=""/>
              <img src="/img/site/top/bookmark.png" alt=""/>
            </div>
            <?php if ($this->hasLeftColumn): ?>
              <div id="sidebar-first"><?php $this->renderPartial('application.views.partial.leftColumn.'.$this->hasLeftColumn) ?></div>
            <?php endif ?>

            <div id="main-content">
              <?php $this->widget('application.components.widgets.search'); ?>
              <div class="main-content"><?php echo $content; ?></div>
            </div>

            <?php if ($this->hasRightColumn): ?>
              <div id="sidebar-last" class="<?=$this->hasRightColumn?> <?php if (Yii::app()->user->isGuest) echo "user_guest"; ?>"><?php $this->renderPartial('application.views.partial.rightColumn.'.$this->hasRightColumn) ?></div>
            <?php endif ?>

            <?php if ($this->postContent): ?>
              <div id="post-content">
                <?=$this->postContent ?>
              </div>
            <?php endif ?>



          </div>
        </div>
      </div>
      <div id="footer-back"></div>
    </div>
    <div id="footer">
      <div id="footer-inner">
        <ul>
          <li><?php $this->widget('application.components.widgets.staticPageLink', array('slug' => 'how-to-become-a-partner')); ?></li>
          <li><?php $this->widget('application.components.widgets.staticPageLink', array('slug' => 'user-agreement')); ?></li>
          <li><a href="<?= Yii::app()->createUrl('/market');?>"><?=Yii::t('main',Yii::t('core','Наши магазины'))?></a></li>
          <?php $this->widget('application.components.widgets.categoryLinks'); ?>
        </ul>

        <div class="footer-logo"><img src="/img/site/footer-logo.png" alt="<?=Yii::t('core','Ривори')?>"/></div>
        <div class="cr footer-bottom"><?=Yii::t('core', 'Все права принадлежат компании "Rivori" <span>&#174;</span>')?></div>

        <div class="mistake footer-bottom"><?php $this->widget('application.components.widgets.orphus'); ?></div>
        <div class="share">
          <?php $this->widget('application.components.widgets.likes', array('layout' => 'share', 'text' => Yii::t('widget-like', 'Share Site'), 'link'=>'/', 'title' => Yii::app()->name)); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>

<?php /*
<div class="container" id="page">
	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->
<?php */ ?>

<?php if(($messages = Yii::app()->user->getFlash('messages'))): ?>

  <?php
    if(is_array($messages)) $messages = implode('<br>', $messages);
    $messages = json_encode($messages);
  ?>
  <script type="text/javascript">
    $(document).ready(function(){
      $.jGrowl($.parseJSON('<?=$messages?>'), { position: 'center'});
    });
  </script>
  </div>
<?php endif; ?>


</body>
</html>
