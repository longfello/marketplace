<?php /* @var $language */ ?>
<div class="choose-lang">
  <?= Yii::t('widget-chooseLanguage', 'Language:') ?>
  <?php
    foreach(Yii::app()->i18n->supportedLanguages as $one) {
      $href   = ($language !== $one['code'])?Yii::app()->i18n->getSubdomain($one['code']) . Yii::app()->params->domain:'';
      $href   = $href?"href='//$href'":'';
      $class  = "language-{$one['code']}";
      $class .= ($language == $one['code'])?" active":"";
    ?>
      <a <?=$href?> class="<?=$class?>" title="<?=$one['name']?>"></a>
    <?php
    }
  ?>
</div>
