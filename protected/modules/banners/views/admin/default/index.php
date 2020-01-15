<table width="100%" cellpadding="0" cellspacing="1" class="contentList1">
        <tr class="odd">
                <td>
                        <?=CHtml::link(Yii::t('AdminModule', 'Добавить баннер'),array('/admin/banners/default/update'), array('class' => 'btn btn-primary'));?>
                        <!--&nbsp;|&nbsp;<?//=CHtml::link('Статистика',array('admin/stat'));?> -->
                </td>
        </tr>
        <tr class="even">
                <td>
                        <? $this->renderPartial('_bannersListGrid',array('model'=>$model)); ?>
                </td>
        </tr>
</table>

