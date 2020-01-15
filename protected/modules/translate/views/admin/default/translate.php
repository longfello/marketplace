<link rel="stylesheet"
      type="text/css"
      href="<?php echo $this->module->assetsUrl; ?>/main.css"/>

<?php
$filename = $_GET['filename'];
$languageid = $_GET['languageid'];
$count = $module->countTranslation($filename, $languageid);
if (
        (file_exists($module->messagepath . '/' . $languageid . '/' . $filename)) AND
        (file_exists($module->messagepath . '/' . $module->language . '/' . $filename))
) {
    $sourcelanguage = include($module->messagepath . '/' . $module->language . '/' . $filename);
    $targetlanguage = include($module->messagepath . '/' . $languageid . '/' . $filename);
} else {
    //missing file
    Yii::app()->user->setFlash('error', Yii::t('TranslateModule', "Файл не найден: ") . $module->messagepath . '/' . $languageid . '/' . $filename);
    $this->redirect(array(
        'edit',
        'languageid' => $languageid,
    ));
}

if (!is_array($sourcelanguage)) {
    Yii::app()->user->setFlash('error', Yii::t('TranslateModule', "Ошибка формата файла (не массив)"));
    $this->redirect(array(
        'edit',
        'languageid' => $languageid,
    ));
}
?>

<div id="moduletitle">
    <?=Yii::t('TranslateModule', 'Редактирование файла перевода:')?> <span><?php echo $filename; ?> ( <?php echo $languageid . ' - ' . $module->languagesnames[$languageid]; ?> )</span>

    <a href=<?php echo Yii::app()->createUrl('admin/translate/default/edit', array('languageid' => $languageid)) ?>><?=Yii::t('TranslateModule', 'выбрать другой файл')?></a>

</div>

<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
}
?>


<div id="translationfiles">

    <br/><br/>
    <table id="translationtable">
        <thead>
            <tr>
                <th colspan="2"><?=Yii::t('TranslateModule', 'Исходный язык:')?> <?php echo $module->language . ' - ' . $module->languagesnames[$module->language] ?></th>
                <th><?=Yii::t('TranslateModule', 'Редактируемый язык:')?> <?php echo $languageid . ' - ' . $module->languagesnames[$languageid]; ?></th>
            </tr>
            <tr>
                <th><?=Yii::t('TranslateModule', 'Ключ')?></th>
                <th><?=Yii::t('TranslateModule', 'Значение')?></th>
                <th><?=Yii::t('TranslateModule', 'Перевод')?> (<?php echo $count[0] > 0 ? $count[0] : '0'; ?> <?=Yii::t('TranslateModule', 'отсутствует')?>)</th>
            </tr>
        </thead>
        <tbody>


            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'action' => Yii::app()->createUrl('admin/translate/default/savefile'),
                'method' => 'post',
                'id' => 'translate',
            ));


            foreach ($sourcelanguage as $key => $value) {
                $value == '' ? $sourceclass = 'class="missing"' : $sourceclass = '';
                $value == '' ? $value = Yii::t('TranslateModule', 'Исходный перевод отсутствует!') : '';
                (array_key_exists($key, $targetlanguage)) ? $targetkey = $targetlanguage[$key] : $targetkey = '';

                $targetkey == '' ? $class = 'class="missing"' : $class = '';
                echo '
    <tr>
        <td>' . $key . '</td>
        <td ' . $sourceclass . '>' . $value . '</td>
        <td ' . $class . '>' . CHtml::textArea("translate[$key]", $targetkey, array('cols' => '40', 'rows' => '3'
                )) . '</td>
    </tr>';
            }
            ?>

        </tbody>
    </table>

    <?php
    echo Chtml::hiddenField('file[filename]', $filename);
    echo Chtml::hiddenField('file[languageid]', $languageid);

    echo CHtml::submitButton(Yii::t('TranslateModule', 'Сохранить'), array('class' => 'btn btn-primary'));
    $this->endWidget();
    ?>

</div>


<table id="translationinsert">
    <thead>
        <tr>
            <th colspan="2"><?=Yii::t('TranslateModule', 'Вставить новую строку в исходный язык')?></th>
        </tr>
        <tr>
            <th><?=Yii::t('TranslateModule', 'Ключ')?></th>
            <th><?=Yii::t('TranslateModule', 'Значение')?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'action' => Yii::app()->createUrl('admin/translate/default/insertline'),
            'method' => 'post',
            'id' => 'insertline',
        ));
        ?>
        <tr>
            <td><?php echo CHtml::textField("newline[key]", '', array('size' => '60', 'maxlength' => '128')); ?></td>
            <td><?php echo CHtml::textArea("newline[value]", '', array('cols' => '40', 'rows' => '3')); ?></td>
        </tr><br>

    </tbody>
</table>

<?php
echo Chtml::hiddenField('newline[filename]', $filename);
echo Chtml::hiddenField('newline[languageid]', $languageid);
echo CHtml::submitButton(Yii::t('TranslateModule', 'Добавить строку'), array('class' => 'btn btn-primary'));
$this->endWidget();
?>