<?php
  Yii::app()->clientScript->registerScriptFile('//yandex.st/share/share.js', CClientScript::POS_END);
  Yii::app()->clientScript->registerScript('ya-share-correct', "$('body').append('<style>.b-share_theme_counter .b-share__handle {padding:5px !important;}</style>')");

  $title = $title?$title:Yii::app()->controller->getPageTitle().' - '.Yii::t('widget-likes', 'Ривори знает, чего я хочу!');
?>
<div class="share-title">Здесь дешевле/лучше/быстрее? Расскажи друзьям</div>
<div class="share-buttons">
    <div class="yashare-auto-init"
      <?php if ($this->image) {echo ('data-yashareImage="'.$this->image.'"');} ?>
      <?php if ($link) {echo ('data-yashareLink="'.$link.'"');} ?>
      <?php if ($title) {echo ('data-yashareTitle="'.$title.'"');} ?>
      data-yashareL10n="<?=Yii::app()->i18n->activeLanguage['code']?>"
      data-yashareQuickServices="vkontakte,facebook,twitter,gplus,moimir,odnoklassniki"
      data-yashareTheme="counter"
    ></div>
</div>
<div class="clear"></div>
