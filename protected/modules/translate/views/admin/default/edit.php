<link rel="stylesheet"
      type="text/css"
      href="<?php echo $this->module->assetsUrl; ?>/main.css"/>

<?php

$params = array(
            'module' => $module,
            'languageid' => $languageid,
        );

if (!in_array($languageid, $module->languages)){
    Yii::app()->user->setFlash('error', Yii::t('TranslateModule', "Выбранный язык не найден"));
    $this->redirect(array(
        'index',
    ));
}
    

?>

<div id="moduletitle">
    <?=Yii::t('TranslateModule', 'Редактирование перевода:')?> <span><?php echo $languageid ?> (<?php echo $module->languagesnames[$languageid] ?>)</span>
     <a href=<?php echo Yii::app()->createUrl('admin/translate/default/index') ?>><?=Yii::t('TranslateModule', 'выбрать другой язык')?></a>
</div>

<form action=<?php echo Yii::app()->createUrl('admin/translate/default/search/languageid/'.$languageid)?> method="POST">
  <div class="input-group">
    <input class="form-control" name="search" value="" placeholder="<?=Yii::t('TranslateModule', 'Поиск по переводам')?>">
    <span class="input-group-addon">
      <input type="submit" value="<?=Yii::t('TranslateModule', 'Поиск')?>" class="btn btn-default btn-xs">
    </span>
  </div>
</form>

<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>

<div id="translationfiles">
    <?php echo $this->renderPartial('_translationfiles', $params); ?>
</div>

