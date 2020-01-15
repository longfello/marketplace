<div class="form-group">
  <label class="col-sm-2 control-label">Meta Title</label>
  <div class="col-sm-10">
    <ul class="nav nav-tabs">
      <?php
        foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
          ?>
          <li <?=($index == Yii::app()->i18n->active->code)?'class="active"':''?>><a href="#meta_title-<?=$lang['code']?>" data-toggle="tab"><?=$lang['name']?></a></li>
        <?php
        }
      ?>
    </ul>
    <div class="tab-content">
      <?php
        foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
          ?>
          <div id="meta_title-<?=$lang['code']?>" class="tab-pane <?=($index == Yii::app()->i18n->active->code)?'active':''?>">
            <?php echo $form->textField($model,'meta_title_'.$lang['code'],array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
          </div>
          <?php echo $form->error($model,'meta_title_'.$lang['code'], array('class' => 'alert alert-danger')); ?>
        <?php
        }
      ?>
    </div>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">Meta Description</label>
  <div class="col-sm-10">
    <ul class="nav nav-tabs">
      <?php
        foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
          ?>
          <li <?=($index == Yii::app()->i18n->active->code)?'class="active"':''?>><a href="#meta_description-<?=$lang['code']?>" data-toggle="tab"><?=$lang['name']?></a></li>
        <?php
        }
      ?>
    </ul>
    <div class="tab-content">
      <?php
        foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
          ?>
          <div id="meta_description-<?=$lang['code']?>" class="tab-pane <?=($index == Yii::app()->i18n->active->code)?'active':''?>">
            <?php echo $form->textArea($model,'meta_description_'.$lang['code'],array('size'=>80,'maxlength'=>255,'class'=>'form-control')); ?>
          </div>
          <?php echo $form->error($model,'meta_description_'.$lang['code'], array('class' => 'alert alert-danger')); ?>
        <?php
        }
      ?>
    </div>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">Meta Keywords</label>
  <div class="col-sm-10">
    <ul class="nav nav-tabs">
      <?php
        foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
          ?>
          <li <?=($index == Yii::app()->i18n->active->code)?'class="active"':''?>><a href="#meta_keywords-<?=$lang['code']?>" data-toggle="tab"><?=$lang['name']?></a></li>
        <?php
        }
      ?>
    </ul>
    <div class="tab-content">
      <?php
        foreach(Yii::app()->i18n->supportedLanguages as $index => $lang) {
          ?>
          <div id="meta_keywords-<?=$lang['code']?>" class="tab-pane <?=($index == Yii::app()->i18n->active->code)?'active':''?>">
            <?php echo $form->textArea($model,'meta_keywords_'.$lang['code'],array('size'=>80,'maxlength'=>255,'class'=>'form-control')); ?>
          </div>
          <?php echo $form->error($model,'meta_keywords_'.$lang['code'], array('class' => 'alert alert-danger')); ?>
        <?php
        }
      ?>
    </div>
  </div>
</div>
