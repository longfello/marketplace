<?php

Yii::app()->clientScript->registerScriptFile(
  "http://maps.google.com/maps/api/js?sensor=false&amp;language=en"
);

Yii::app()->clientScript->registerScriptFile(
  $this->module->assetsUrl.'/admin/gmap3.min.js'
);

Yii::app()->clientScript->registerScriptFile(
  $this->module->assetsUrl.'/market_view.map.js',
  CClientScript::POS_END
);
?>


<div class="market-item">
  <h1><?=$this->model->name?></h1>
  <div class="description"><?=$this->model->description?></div>
  <div class="site"><?php if ($this->model->url) echo "<a href='".$this->model->url."'>".$this->model->url."</a>";?></div>

  <?php if (count($markers)>0) {
    echo "<div id='store_markers'>";
    foreach ($markers as $one) {
      if ($one->city_id) {
        echo "<input type='hidden' class='one_marker' data-lat='".$one->lat."' data-lng='".$one->lng."' data-name='".$one->name."' data-address='".$one->address."' data-phone='".$one->phone."' data-cityname='{$one->city->name}'>";
      } else {
        echo "<input type='hidden' class='one_marker' data-lat='".$one->lat."' data-lng='".$one->lng."' data-name='".$one->name."' data-address='".$one->address."' data-phone='".$one->phone."' data-cityname=''>";
      }
    }
    echo "</div>";
    echo "<div id='map' style='margin: 0 auto;'></div>";
  } ?>


  <?php
    if ($provider->totalItemCount) {
      ?> <h3><?= Yii::t('Comment', 'Пользователи о магазине'); ?></h3> <?php
      $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$provider,
        'ajaxUpdate'=>false,
        'template'=>'{items} {pager} {summary}',
        'itemView'=>'_market_comment',
        'sortableAttributes'=>array('created'),
      ));
    }
  ?>

</div>
