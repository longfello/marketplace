<?php
  /* @var Controller $this */
  $this->pageHeader = Yii::t('StoreModule', 'Импорт товаров | запуск импорта');

  $this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('StoreModule', 'Импорт товаров') => $this->createUrl('/admin/store/import'),
    Yii::t('StoreModule', 'Запуск импорта'),
  );

  $this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
    'template'=>array('create'),
    'elements'=>array(
      'create'=>array(
        'link'=>$this->createUrl('update'),
        'title'=>Yii::t('StoreModule', 'Создать источник импорта'),
        'options'=>array(
          'icons'=>array('primary'=>'ui-icon-plus')
        )
      ),
    ),
  ));
?>

<div class="form wide padding-all">
  <p class="lead">Запуск импорта с источника: <?=$model->name?></p>
  <div id="preamb">
    <p>Для начала импорта нажмите кнопку "приступить"</p>
    <br>
    <button type="button" id="start_test" class="btn btn-primary" data-actions='<?=$actions?>'>Приступить</button>
  </div>

  <div id='import_log_wrapper' class="hidden" data-url="<?=$this->createUrl('/admin/store/import/run/'.$model->id);?>">
    <div class="progress progress-striped active">
      <div class="progress-bar-import progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div>
    </div>
    <div class="progress progress-striped active">
      <div class="progress-bar-import2 progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div>
    </div>
    <div id="import_log"></div>
  </div>
</div>