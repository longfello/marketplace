<?php
  /* @var Controller $this */
  /* @var Banners $model */
?>
  <h1><?= CHtml::Label($model->getIsNewRecord() ? Yii::t('AdminModule', 'Добавление баннера') : Yii::t('AdminModule', 'Редактирование баннера'), ''); ?></h1>
  <? if (CHtml::errorSummary($model)): ?>
    <div class="alert alert-danger">
      <?= CHtml::errorSummary($model); ?>
    </div>
  <? endif; ?>

  <table class="table table-striped table-hover ">
    <?= CHtml::beginForm('', 'post', array('enctype' => 'multipart/form-data')); ?>
    <?= CHtml::activeHiddenField($model, 'id', array('value' => $model->id)); ?>
    <tr>
      <td><?= CHtml::activeLabel($model, 'bnrDefault', array('title' => Yii::t('AdminModule', 'Когда баннеры для текущей позиции не найдены - будет отображен баннер по-умолчанию'))); ?></td>
      <td><?= CHtml::activeCheckBox($model, 'bnrDefault', array()); ?></td>
    </tr>
    <tr>
      <td><?= CHtml::activeLabel($model, 'bnrTag'); ?></td>
      <td><?= CHtml::activeTextField($model, 'bnrTag', array('size' => 70, 'class' => 'form-control')); ?></td>
    </tr>
    <tr>
      <td><?= CHtml::activeLabel($model, 'bnrTyp'); ?></td>
      <td><?= CHtml::activeDropDownList($model, 'bnrTyp', Banners::model()->bannersTyp, array('class' => 'form-control')); ?></td>
    </tr>
    <tr>
      <td><?= CHtml::activeLabel($model, 'position'); ?></td>
      <td><?= CHtml::activeDropDownList($model, 'position', Banners::model()->bannersPosition, array('class' => 'form-control')); ?></td>
    </tr>
    <tr>
      <td>
        <?= CHtml::activeLabel($model, 'bnrFile'); ?>
        <br />
        <? if ($model->bnrTyp == Banners::BANNERS_TYP_TEXT and !empty($model->bnrDescr)): ?>
        <?= $model->bnrDescr; ?>
        <? elseif ($model->bnrTyp == Banners::BANNERS_TYP_IMG and !empty($model->bnrFile)): ?>
          <img width="<?= $model->bnrWidth; ?>" height="<?= $model->bnrHeight; ?>" src="<?= Yii::app()->params['bannersWebFolder'] . $model->bnrFile; ?>">
        <? elseif ($model->bnrTyp == Banners::BANNERS_TYP_SWF and !empty($model->bnrFile)): ?>
            <embed width="<?= $model->bnrWidth; ?>" height="<?= $model->bnrHeight; ?>"
                   type="application/x-shockwave-flash"
                   src="<?= Yii::app()->params['bannersWebFolder'] . $model->bnrFile; ?>"
                   pluginspage="http://www.adobe.com/go/getflashplayer"/>
        <? endif; ?>
      </td>
      <td><?= CHtml::activeFileField($model, 'bnrFile'); ?></td>
    </tr>
    <!--tr>
                <td><?= CHtml::activeLabel($model, 'bnrWidth'); ?></td>
                <td><?= CHtml::activeTextField($model, 'bnrWidth', array('size' => 5)); ?></td>
        </tr>
        <tr>
                <td><?= CHtml::activeLabel($model, 'bnrHeight'); ?></td>
                <td><?= CHtml::activeTextField($model, 'bnrHeight', array('size' => 5)); ?></td>
        </tr-->
    <tr>
      <td><?= CHtml::activeLabel($model, 'bnrUrl'); ?></td>
      <td><?= CHtml::activeTextField($model, 'bnrUrl', array('size' => 70, 'class' => 'form-control')); ?></td>
    </tr>
    <tr>
      <td><?= CHtml::activeLabel($model, 'bnrVisible'); ?></td>
      <td><?= CHtml::activeCheckBox($model, 'bnrVisible'); ?></td>
    </tr>
    <!--tr>
                <td><?= CHtml::activeLabel($model, 'bnrVisibleLimit'); ?> (0 - без ограничений)</td>
                <td><?= CHtml::activeTextField($model, 'bnrVisibleLimit', array('size' => 70)); ?></td>
        </tr>
        <tr>
                <td><?= CHtml::activeLabel($model, 'dropViewed'); ?></td>
                <td><?= CHtml::activeCheckBox($model, 'dropViewed'); ?></td>
        </tr-->
    <tr>
      <td><?= CHtml::activeLabel($model, 'bnrVisibleTo'); ?></td>
      <td colspan="2">
        <!--
                        from
                        <? $this->widget('zii.widgets.jui.CJuiDatePicker', array('name' => 'bnrVisibleFrom',
            'model' => $model,
            'attribute' => 'bnrVisibleFrom',
            'options' => array('dateFormat' => 'dd.mm.yy',
              'changeMonth' => 'true',
              'changeYear' => 'true',
              'showButtonPanel' => 'true',
              'showAnim' => 'fold'),
            //'htmlOptions'=>array('style'=>'height:130px;')
          )); ?>
                        to
                        -->
        <? $this->widget('zii.widgets.jui.CJuiDatePicker', array('name' => 'bnrVisibleTo',
            'model' => $model,
            'attribute' => 'bnrVisibleTo',
            'options' => array('dateFormat' => 'dd.mm.yy',
              'changeMonth' => 'true',
              'changeYear' => 'true',
              'showButtonPanel' => 'true',
              'showAnim' => 'fold'),
            //'htmlOptions'=>array('style'=>'height:130px;')
          )); ?>
      </td>
    </tr>

    <tr>
      <td><?= CHtml::activeLabel($model, 'bnrDescr'); ?></td>
      <td><?= CHtml::activeTextArea($model, 'bnrDescr', array('rows' => 10, 'cols' => 55, 'class' => 'form-control')); ?></td>
    </tr>
    <tr>
      <td></td>
      <td>
        <?= CHtml::submitButton(Yii::t('AdminModule', 'Сохранить'), array('class' =>'btn btn-primary')); ?>
        <?= CHtml::link(Yii::t('AdminModule', 'Отмена'), Yii::app()->createUrl('/admin/banners'), array('class' =>'btn btn-default')); ?>
      </td>
    </tr>
    <?= CHtml::endForm(); ?>
  </table>