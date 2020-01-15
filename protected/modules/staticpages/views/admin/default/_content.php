<?
 Helper::registerJS('urlify.js');
  Yii::app()->clientScript->registerScript('translit', "
$('#translit-btn').click(function() {
	$('#Page_slug').val(URLify($('#Page_page_title').val()));
});
");
?>
<br>
<?= $form->dropDownListRow($model, 'parent_id', $model->selectList(), array('class'=>'form-control', 'empty' => ' < нет > ')) ?>

<div class="form-group">
  <label class="col-sm-2 control-label">Заголовок</label>
  <div class="col-sm-10">
    <ul class="nav nav-tabs">
      <?php
        foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
          ?>
          <li <?=($index == Yii::app()->i18n->active->code)?'class="active"':''?>><a href="#page_title-<?=$lang['code']?>" data-toggle="tab"><?=$lang['name']?></a></li>
        <?php
        }
      ?>
    </ul>
    <div class="tab-content">
      <?php
        foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
          ?>
          <div id="page_title-<?=$lang['code']?>" class="tab-pane <?=($index == Yii::app()->i18n->active->code)?'active':''?>">
            <?php echo $form->textField($model,'page_title_'.$lang['code'],array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
          </div>
          <?php echo $form->error($model,'page_title_'.$lang['code'], array('class' => 'alert alert-danger')); ?>
        <?php
        }
      ?>
    </div>
  </div>
</div>

<div class="form-group">
	<?= $form->labelEx($model, 'slug', array('class' => 'control-label col-sm-2', 'label' => 'Псевдоним')) ?>
	<div class="col-sm-10">
		<div class="input-append">
			<?= $form->textField($model, 'slug', array('class' => 'form-control', 'maxlength' => 127)) ?><button class="btn btn-default btn-xs" type="button" id="translit-btn">Транслит</button>
		</div>
	</div>
</div>

<div class="form-group">
  <?= $form->labelEx($model, 'is_published', array('class' => 'control-label col-sm-2')) ?>
  <div class="col-sm-10">
    <div class="input-append">
      <?= $form->checkBox($model, 'is_published', array('class' => 'span5')) ?>
    </div>
  </div>
</div>

<div class="hidden">
  <?= $form->dropDownListRow($model, 'layout', array(
    'column1' => 'Одна колонка',
    'column2' => 'Две колонки',
  ), array('empty' => 'По умолчанию', 'class' => 'span3')) ?>
</div>

<div class="form-group">
  <label class="col-sm-2 control-label">Контент</label>
  <div class="col-sm-10">
    <ul class="nav nav-tabs">
      <?php
        foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
          ?>
          <li <?=($index == Yii::app()->i18n->active->code)?'class="active"':''?>><a href="#content-<?=$lang['code']?>" data-toggle="tab"><?=$lang['name']?></a></li>
        <?php
        }
      ?>
    </ul>
    <div class="tab-content">
      <?php
        foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
          ?>
          <div id="content-<?=$lang['code']?>" class="tab-pane <?=($index == Yii::app()->i18n->active->code)?'active':''?>">
            <?php $this->widget('application.extensions.ckeditor.CKEditor', array(
              'model'=>$model,
              'attribute'=>'content_'.$lang['code'],
              'editorTemplate'=>'full',
              'height'=>'800px',
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
