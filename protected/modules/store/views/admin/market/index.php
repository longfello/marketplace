<?php

/**
 * Display products list
 * @var StoreProduct $model
 **/

$this->pageHeader = Yii::t('StoreModule.admin', 'Магазины');

// Register scripts
Yii::app()->clientScript->registerScriptFile(
  $this->module->assetsUrl.'/admin/market.index.js',
  CClientScript::POS_END
);

$this->breadcrumbs = array(
  'Home'=>$this->createUrl('/admin'),
  Yii::t('StoreModule.admin', 'Магазины'),
);

$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
  'template'=>array('create'),
  'elements'=>array(
    'create'=>array(
      'link'=>$this->createUrl('create'),
      'title'=>Yii::t('StoreModule.admin', 'Создать магазин'),
      'options'=>array(
        'icons'=>array('primary'=>'ui-icon-plus')
      )
    )
  )
));

if (Yii::app()->user->getIsManager()) {
  $this->widget('ext.sgridview.SGridView', array(
    'dataProvider'  => $dataProvider,
    'id'            => 'marketsListGrid',
    'ajaxUpdate'    => true,
    'filter'        => $model,
    'customActions' => array(),
    'columns'=>array(
      array(
        'class'=>'CCheckBoxColumn',
      ),
      array(
        'class'=>'SGridIdColumn',
        'name'=>'id'
      ),
      array(
        'name'=>'name',
        'type'=>'raw',
        'value'=>'CHtml::link(CHtml::encode($data->name), array("/store/admin/market/update", "id"=>$data->id))',
      ),
      'description',
      'url',
      array(
        'name'=>'is_active',
        'filter'=>array('0'=>Yii::t('StoreModule.admin', 'Выключен'), '1'=>Yii::t('StoreModule.admin', 'Включен'), '2'=>Yii::t('StoreModule.admin', 'Заблокирован')),
        'value'=>'$data->is_active=="0" ? Yii::t("StoreModule.admin", "Выключен") : ($data->is_active=="1" ?Yii::t("StoreModule.admin", "Включен"): Yii::t("StoreModule.admin", "Заблокирован"))'
      ),
      'status_comment',
      // Buttons
      array(
        'class'=>'CButtonColumn',
        'template'=>'{update}{delete}',
      ),
    ),
  ));
} else {
  $this->widget('ext.sgridview.SGridView', array(
    'dataProvider'  => $dataProvider,
    'id'            => 'marketsListGrid',
    'ajaxUpdate'    => true,
    'filter'        => $model,
    'customActions' => array(
      array(
        'label'=>Yii::t('StoreModule.admin', 'Активен'),
        'url'=>'#',
        'linkOptions'=>array(
          'onClick'=>'return setMarketStatus(1, this);',
        ),
      ),
      array(
        'label'=>Yii::t('StoreModule.admin', 'Не активен'),
        'url'=>'#',
        'linkOptions'=>array(
          'onClick'=>'return setMarketStatus(0, this);',
        ),
      )
    ),
    'columns'=>array(
      array(
        'class'=>'CCheckBoxColumn',
      ),
      array(
        'class'=>'SGridIdColumn',
        'name'=>'id'
      ),
      array(
        'name'=>'name',
        'type'=>'raw',
        'value'=>'CHtml::link(CHtml::encode($data->name), array("/store/admin/market/update", "id"=>$data->id))',
      ),
      'description',
      array(
        'name'=>'user_id',
        'type'=>'raw',
        'value'=>'$data->owner ? CHtml::link(CHtml::encode($data->owner->username), array("/admin/users/default/update", "id"=>$data->user_id)) : ""'
      ),
      'url',
      array(
        'name'=>'is_active',
        'filter'=>array(1=>Yii::t('StoreModule.admin', 'Включен'), 0=>Yii::t('StoreModule.admin', 'Выключен'), 2=>Yii::t('StoreModule.admin', 'Заблокирован')),
        'value'=>'$data->is_active=="0" ? Yii::t("StoreModule.admin", "Выключен") : ($data->is_active=="1" ?Yii::t("StoreModule.admin", "Включен"): Yii::t("StoreModule.admin", "Заблокирован"))'
      ),
      'status_comment',
      // Buttons
      array(
        'class'=>'CButtonColumn',
        'template'=>'{update}{delete}',
      ),
    ),
  ));
}
