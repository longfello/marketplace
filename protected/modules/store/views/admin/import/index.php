<?php
  $this->pageHeader = Yii::t('StoreModule', 'Импорт товаров');

  $this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('StoreModule', 'Импорт товаров'),
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

  $this->widget('ext.sgridview.SGridView', array(
    'dataProvider'=>$dataProvider,
    'id'=>'importSourceListGrid',
    'filter'=>$model,
    'columns'=>array(
      array(
        'name'   => 'market_id',
        'type'   => 'raw',
        'value'  => '$data->market_id ? CHtml::encode(StoreMarket::model()->findByPk($data->market_id)->name) : ""',
        'filter' => CHtml::listData(StoreMarket::model()->orderByName()->onlyOwn()->findAll(),'id', 'name')
      ),
      array(
        'name'=>'status',
        'value'=>'"<span class=\"status-{$data->status}\">".StoreImportSources::model()->getStatuses()[$data->status]."</span>";',
        'type'   => 'raw',
        'filter'=>StoreImportSources::model()->getStatuses()
      ),
      array(
        'name'=>'name',
        'type'   => 'raw',
        'value'=>'"<a href=\'/admin/store/import/update/id/{$data->id}\'>{$data->name}</a>"'
      ),
      'sourceURL',
      'comment',
      array(
        'class'=>'CButtonColumn',
        'template'=>'{update}{delete}{test}{assign}{run}',
        'buttons' => array(
          'test' => array(
            'label'=>'Проверить',
            'url'=>'Yii::app()->createUrl("/admin/store/import/test/".$data->id)',
            'imageUrl'=>'/img/site/verify.gif',
            'visible'=>'in_array($data->status, array(StoreImportSources::STATUS_NEW,StoreImportSources::STATUS_ERRORS))',
          ),
          'assign' => array(
            'label'=>'Проверить',
            'url'=>'Yii::app()->createUrl("/admin/store/import/assign/".$data->id)',
            'imageUrl'=>'/img/site/verify.gif',
            'visible'=>'($data->status == StoreImportSources::STATUS_ASSIGN) || (($data->status == StoreImportSources::STATUS_CONFIRMATION) && Yii::app()->user->getIsSuperuser())',
          ),
          'run' => array(
            'label'=>'Запустить',
            'url'=>'Yii::app()->createUrl("/admin/store/import/run/".$data->id)',
            'imageUrl'=>'/img/site/go.png',
            'visible'=>'in_array($data->status, array(StoreImportSources::STATUS_APPROVED))',
          )
        )
      ),
    ),
  ));
?>
