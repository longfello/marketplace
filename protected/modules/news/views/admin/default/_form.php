<?php /* @var $this Controller */ ?>
<?php /* @var $form CActiveForm */ ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => array('class'=>'form-horizontal', 'role'=>'form'))); ?>
	<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

  <div class="form-group">
    <label class="col-sm-2 control-label"><?=Yii::t('NewsModule', 'Заголовок')?></label>
    <div class="col-sm-10">
      <ul class="nav nav-tabs">
        <?php
          foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
            ?>
            <li <?=$index == Yii::app()->language?'class="active"':''?>><a href="#title-<?=$lang['code']?>" data-toggle="tab"><?=$lang['name']?></a></li>
          <?php
          }
        ?>
      </ul>
      <div class="tab-content">
        <?php
          foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
            ?>
            <div id="title-<?=$lang['code']?>" class="tab-pane <?=$index == Yii::app()->language?'active':''?>">
              <?php echo $form->textField($model,'title_'.$lang['code'],array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
            </div>
            <?php echo $form->error($model,'title_'.$lang['code'], array('class' => 'alert alert-danger')); ?>
          <?php
          }
        ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label"><?=Yii::t('NewsModule', 'Тизер новости')?></label>
    <div class="col-sm-10">
      <ul class="nav nav-tabs">
        <?php
          foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
            ?>
            <li <?=$index == Yii::app()->language?'class="active"':''?>><a href="#teaser-<?=$lang['code']?>" data-toggle="tab"><?=$lang['name']?></a></li>
          <?php
          }
        ?>
      </ul>
      <div class="tab-content">
        <?php
          foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
            ?>
            <div id="teaser-<?=$lang['code']?>" class="tab-pane <?=$index == Yii::app()->language?'active':''?>">
              <?php $this->widget('application.extensions.ckeditor.CKEditor', array(
                'model'=>$model,
                'attribute'=>'teaser_'.$lang['code'],
                'editorTemplate'=>'full',
                'height'=>'500px',
//                'assetsDir' => '/img'
              )); ?>
            </div>
            <?php echo $form->error($model,'teaser_'.$lang['code'], array('class' => 'alert alert-danger')); ?>
          <?php
          }
        ?>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label"><?=Yii::t('NewsModule', 'Текст новости')?></label>
    <div class="col-sm-10">
      <ul class="nav nav-tabs">
        <?php
          foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
            ?>
            <li <?=$index == Yii::app()->language?'class="active"':''?>><a href="#content-<?=$lang['code']?>" data-toggle="tab"><?=$lang['name']?></a></li>
          <?php
          }
        ?>
      </ul>
      <div class="tab-content">
        <?php
          foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
            ?>
            <div id="content-<?=$lang['code']?>" class="tab-pane <?=$index == Yii::app()->language?'active':''?>">
              <?php $this->widget('application.extensions.ckeditor.CKEditor', array(
                'model'=>$model,
                'attribute'=>'content_'.$lang['code'],
                'editorTemplate'=>'full',
                'height'=>'500px',
//                'assetsDir' => '/img'
              )); ?>
            </div>
            <?php echo $form->error($model,'content_'.$lang['code'], array('class' => 'alert alert-danger')); ?>
          <?php
          }
        ?>
      </div>
    </div>
  </div>

	<div class="form-group">
    <label class="col-sm-2 control-label"><?=Yii::t('NewsModule', 'Статус')?></label>
    <div class="col-sm-10">
      <?php echo $form->dropDownList($model,'status',Lookup::items('PostStatus'), array('class'=>'form-control')); ?>
      <?php echo $form->error($model,'status', array('class' => 'alert alert-danger')); ?>
    </div>
	</div>

	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		  <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('NewsModule', 'Добавить') : Yii::t('NewsModule', 'Сохранить'), array('class'=>'btn btn-primary')); ?>
		  <?php echo CHtml::link('Отмена', $this->createUrl('/admin/news'),array('class'=>'btn btn-default')); ?>
    </div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
