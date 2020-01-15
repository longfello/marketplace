<?php

/**
 * Create/update market
 *
 * @var $model StoreMarket
 */
$this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
  'form'=>$form,
  'langSwitcher'=>!$model->isNewRecord,
  'deleteAction'=>$this->createUrl('/store/admin/market/delete', array('id'=>$model->id))
));


Yii::app()->clientScript->registerScriptFile(
  "http://maps.google.com/maps/api/js?sensor=false&amp;language=en"
);

Yii::app()->clientScript->registerScriptFile(
  $this->module->assetsUrl.'/admin/gmap3.min.js'
);
Yii::app()->clientScript->registerScriptFile(
  $this->module->assetsUrl.'/admin/gmap3-menu.js'
);

Yii::app()->clientScript->registerCssFile(
  $this->module->assetsUrl.'/admin/gmap3-menu.css'
);

Yii::app()->clientScript->registerScriptFile(
  $this->module->assetsUrl.'/admin/market.map.js',
  CClientScript::POS_END
);

$title = ($model->isNewRecord) ? Yii::t('StoreModule.admin', 'Создание магазина') :
  Yii::t('StoreModule.admin', 'Редактирование магазина');

$this->breadcrumbs = array(
  'Home'=>$this->createUrl('/admin'),
  Yii::t('StoreModule.admin', 'Магазины')=>$this->createUrl('index'),
  ($model->isNewRecord) ? Yii::t('StoreModule.admin', 'Создание магазина') : CHtml::encode($model->name),
);

$this->pageHeader = $title;

?>

<div class="form wide padding-all">
  <?php
    echo $form;
  ?>
</div>

<div id="store_markers">
  <?php
    foreach($markers as $one) {
      if ($one->city_id) {
        echo "<input type='hidden' class='one_marker' data-lat='".$one->lat."' data-lng='".$one->lng."' data-name='".$one->name."' data-address='".$one->address."' data-phone='".$one->phone."' data-city='$one->city_id' data-cityname='{$one->city->name}' >";
      } else {
        echo "<input type='hidden' class='one_marker' data-lat='".$one->lat."' data-lng='".$one->lng."' data-name='".$one->name."' data-address='".$one->address."' data-phone='".$one->phone."' data-city='' data-cityname='' >";
      }
    }
  ?>
</div>

<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
        <h4 class='modal-title' id='myModalLabel'>Добавление маркера</h4>
      </div>
      <div class='modal-body'>
        <form role='form' id='marker_form'>
          <div class='form-group'>
            <label for='InputName'>Название</label>
            <input type='text' class='form-control' id='InputName' name='name' placeholder='Введите название'>
          </div>
          <div class='form-group'>
            <label for='InputAddress'>Адрес</label>
            <input type='text' class='form-control' id='InputAddress' name='address' placeholder='Введите адрес'>
          </div>
          <div class='form-group'>
            <label for='InputPhone'>Телефон</label>
            <input type='text' class='form-control' id='InputPhone' name='phone' placeholder='Введите телефон'>
          </div>
          <div class='form-group form-group-ac'>
            <label for='InputCity'>Город</label>
            <?php $this->widget('application.components.widgets.cityPicker', array('model' => new UserProfile(), 'field' => 'city_id','layout'=>'admin')); ?>
          </div>
        </form>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-default' data-dismiss='modal'>Закрыть</button>
        <button type='button' class='btn btn-primary' onclick='addMarker();'>Добавить</button>
      </div>
    </div>
  </div>
</div>
