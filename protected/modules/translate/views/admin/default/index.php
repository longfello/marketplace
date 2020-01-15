<link rel="stylesheet"
      type="text/css"
      href="<?php echo $this->module->assetsUrl; ?>/main.css"/>
<?php
$langfound = count($module->languages);

switch ($langfound) {
    case 0:
        echo '<div id="modulewarning">';
        echo Yii::t('TranslateModule', 'Языки не найдены, вы должны создать папку "') . $module->language . Yii::t('TranslateModule', '" в:');
        echo '<pre> ' . $module->messagepath . '</pre>';
        echo '</div>';
        break;
    case 1:
        echo '<div id="modulewarning">';
        if ($module->countWhat($module->languages, '==', $module->language) == 1) {
            echo Yii::t('TranslateModule', 'Других языков не найдено, создайте подпапки для других языков в:');
            echo '<pre>' . $module->messagepath . '</pre>';
        } else {
            echo Yii::t('TranslateModule', 'Папка для языка не найдена, создайте папку "') . $module->language . Yii::t('TranslateModule', '" в:');
            echo '<pre>' . $module->messagepath . '</pre>';
        }

        echo '</div>';
        break;
    default:
        break;
}
?>

<div id="modulesettings">
    <h6><?=Yii::t('TranslateModule', 'Модуль переводов определил следующие настройки (измените конфигурацию, если он не верны):')?></h6>
    <br/>
    <p>
       <?=Yii::t('TranslateModule', 'Язык по-умолчанию:')?> <pre><?php echo $module->language ?> (<?php echo $module->languageName($module->language); ?>)</pre><br/>
    <hr/>
    <?=Yii::t('TranslateModule', 'Найдены следующие языки в папке')?> <pre><?php echo $module->messagepath ?>:</pre>
    <ul>
        <?php
        foreach ($module->languagesnames as $key => $value) {
            echo '<li>' . $key . ' (' . $value . ')</li>';
        }
        ?>
    </ul>

    <br/>
    <hr/>
    <br/>

    <?=Yii::t('TranslateModule', 'Файлы переводов в папке')?> <pre><?php echo $module->messagepath . '/' . $module->language ?>;</pre>
    <ul>
        <?php
        foreach ($module->fileslist as $value) {
            echo '<li>' . $value . '</li>';
        }
        ?>
    </ul>

</div>

<div id="moduleactions">
    <h5><?=Yii::t('TranslateModule', 'Действия')?></h5>
    <?php if (count($module->fileslist) > 0): ?>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'action' => Yii::app()->createUrl('admin/translate/default/edit'),
            'method' => 'post',
            'id' => 'translate',
        ));
        ?>

        <?=Yii::t('TranslateModule', 'Перевести')?> <?php echo $module->languageName($module->language) ?> <?=Yii::t('TranslateModule', 'на')?> <?php echo CHtml::dropDownList('LanguagesNames', $module->language, $module->languagesnames); ?><br/>

        <?php echo CHtml::submitButton(Yii::t('TranslateModule', 'Перевести'), array('class' => 'btn btn-primary')); ?>

        <?php $this->endWidget(); ?>

    <?php else: ?>

        <?=Yii::t('TranslateModule', 'Файлы переводов для выбранного языка не найдены.
        Создайте файл со следующим содержимым:')?>
        <pre>
            <?php
            echo <<<'help'
&lt;?php
    return array()
?&gt;
help;
            ?>
        </pre>


    <?php endif; ?>

</div>