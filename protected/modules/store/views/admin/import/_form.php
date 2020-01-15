<?php /* @var $this Controller */ ?>
<?php /* @var $form CActiveForm */ ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => array('class'=>'form-horizontal', 'role'=>'form'))); ?>
	<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

  <div class="form-group">
    <label class="col-sm-2 control-label"><?= $form->label($model, 'name')?></label>
    <div class="col-sm-10">
      <?php echo $form->textField($model,'name',array('maxlength'=>255,'class'=>'form-control')); ?>
      <?php echo $form->error($model,'name', array('class' => 'alert alert-danger')); ?>
    </div>
  </div>
  <div class="form-group">

    <!-- Tab panes -->
    <label class="col-sm-2 control-label"><?= $form->label($model, 'sourceURL')?></label>
    <div class="col-sm-10">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#url" id='tab-link-url' data-toggle="tab"><?= CHtml::label(Yii::t('main', 'Ссылка'),'StoreImportSources_sourceURL')?></a></li>
        <li><a href="#file" data-toggle="tab"><?= CHtml::label(Yii::t('main', 'Загрузить файл'),'StoreImportSources_Download')?></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="url">
          <?php echo $form->textField($model,'sourceURL',array('maxlength'=>255,'class'=>'form-control')); ?>
          <?php echo $form->error($model,'sourceURL', array('class' => 'alert alert-danger')); ?>
        </div>
        <div class="tab-pane" id="file">
          <?php
            $this->widget('application.extensions.plupload.PluploadWidget', array(
              'config' => array(
                'runtimes' => 'browserplus,gears,flash,html5,silverlight',
                'url' => $this->createUrl('/admin/store/import/upload'),
                'max_file_size' => str_replace("M", "mb", ini_get('upload_max_filesize')),
                'chunk_size' => '1mb',
                'unique_names' => true,
                'filters' => array(
                  array('title' => Yii::t('main', 'XML'), 'extensions' => 'xml,yml'),
                ),
                'language' => Yii::app()->language,
                'max_file_number' => 1,
                'autostart' => true,
                'jquery_ui' => true,
                'reset_after_upload' => true,
                'multipart_params' => array(
                  Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                )
              ),
              'callbacks' => array(
                'FileUploaded' => 'function(up,file,response){ $("#tab-link-url").click(); var data = $.parseJSON(response.response); $("#StoreImportSources_sourceURL").val(data.url); console.log(data)}',
              ),
              'id' => 'uploader',
            ));
          ?>
        </div>
      </div>
    </div>
  </div>

	<div class="form-group">
    <label class="col-sm-2 control-label"><?= $form->label($model, 'type')?></label>
    <div class="col-sm-10">
      <?php echo $form->dropDownList($model,'type',array('yml'=>'Yandex Market YML'), array('class'=>'form-control')); ?>
      <?php echo $form->error($model,'type', array('class' => 'alert alert-danger')); ?>
    </div>
	</div>

  <? if (Yii::app()->user->getIsSuperuser()) { ?>
    <div class="form-group">
      <label class="col-sm-2 control-label"><?= $form->label($model, 'status')?></label>
      <div class="col-sm-10">
        <?php echo $form->dropDownList($model,'status',StoreImportSources::getStatuses() , array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'status', array('class' => 'alert alert-danger')); ?>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label"><?= $form->label($model, 'comment')?></label>
      <div class="col-sm-10">
        <?php echo $form->textArea($model,'comment', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'comment', array('class' => 'alert alert-danger')); ?>
      </div>
    </div>
 <?php } ?>

	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		  <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
		  <?php echo CHtml::link('Отмена', $this->createUrl('/admin/store/import'),array('class'=>'btn btn-default')); ?>
    </div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
