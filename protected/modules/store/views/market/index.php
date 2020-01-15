<?php
  $this->pageTitle = Yii::t('StoreMarket', 'Наши магазины');
  $this->breadcrumbs[] = $this->pageTitle;


  $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$provider,
    'ajaxUpdate'=>false,
    'template'=>'{items} {pager} {summary}',
    'itemView'=>'_market_preview',
    'sortableAttributes'=>array(
      'name'
    ),
  ));
?>
