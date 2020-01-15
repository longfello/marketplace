<link rel="stylesheet"
      type="text/css"
      href="<?php echo $this->module->assetsUrl; ?>/main.css"/>

<?php

$params = array(
  'module' => $module,
  'languageid' => $languageid,
  'search' => $search
);

if (!in_array($languageid, $module->languages)){
  Yii::app()->user->setFlash('error', Yii::t('TranslateModule', "Выбранный язык не найден"));
  $this->redirect(array(
    'index',
  ));
}


?>

<div id="moduletitle">
  <?=Yii::t('TranslateModule', 'Поиск')?> "<?php echo $search ?>" <?=Yii::t('TranslateModule', 'по переводу:')?> <span><?php echo $languageid ?> (<?php echo $module->languagesnames[$languageid] ?>)</span>
  <a href="<?php echo Yii::app()->createUrl('admin/translate/default/index') ?>"><?=Yii::t('TranslateModule', 'выбрать другой язык')?></a>
</div>

<form action="<?php echo Yii::app()->createUrl('admin/translate/default/search/languageid/'.$languageid)?>" method="POST">

  <div class="input-group">
    <input class="form-control" name="search" value="<?php echo $search ?>" placeholder="<?=Yii::t('TranslateModule', 'Поиск по переводам')?>">
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
  <div id="existingfiles">
    <h6><?=Yii::t('TranslateModule', 'Следующие файлы переводов найдены:')?></h6><br/>

    <table>
      <tbody>
      <?php
      $existingfiles = $module->findFilesSearch($languageid,$search);

      foreach ($existingfiles as $key => $value) {

        $missing = $module->countTranslation($value, $languageid);
        $complete = $missing[0] > 0 ? $missing[0] . Yii::t('TranslateModule', ' отсутствует') : Yii::t('TranslateModule', 'завершен');
        $class = $missing[0] > 0 ? 'class="error"' : 'class="sucess"';

        $link = CHtml::Link(
          Yii::t('TranslateModule', 'Перевод'), Yii::app()->createUrl('admin/translate/default/translate', array('filename' => $value, 'languageid' => $languageid,)));

        echo <<<TABLE
            <tr>
                <td>$value</td>
                <td>$link</td>
                <td><span $class>$complete</span> ($missing[1] total)</td>
            </tr>
TABLE;
      }
      ?>

      </tbody>
    </table>
  </div>
</div>

