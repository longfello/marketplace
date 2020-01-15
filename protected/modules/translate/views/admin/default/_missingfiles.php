<h6><?=Yii::t('TranslateModule', 'Следующие файлы перевода отсутствуют:')?></h6><br/>

<table>
    <tbody>
        <?php
        $missingfiles = $module->missingFiles($languageid);

        if (count($missingfiles) > 0) {
            foreach ($missingfiles as $key => $value) {
                $link = CHtml::ajaxLink('Initialize', Yii::app()->createUrl('admin/translate/default/initfile'), array(// ajaxOptions
                            'type' => 'POST',
                            'data' => array('fileid' => $key, 'languageid' => $languageid, 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                            'update' => '#translationfiles',
                                ), array(//htmlOptions
                            'href' => '#'
                        ));

                echo <<<TABLE
            <tr>
                <td>$value</td>
                <td>$link</td>
            </tr>
TABLE;
            }
        } else {
            echo Yii::t('TranslateModule', 'Нет отсутствующих файлов');
        }
        ?>

    </tbody>
</table>