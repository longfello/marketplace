<?php
$this->pageTitle = Yii::t('StoreModule', 'Отзывы');
?>

<h1 class="has_background"><?php echo Yii::t('StoreModule', 'Отзывы') ?>
</h1>

<?php

$this->widget('zii.widgets.CListView', array(
  'dataProvider'=>$provider,
  'ajaxUpdate'=>false,
  'template'=>'{items} {pager} {summary}',
  'itemView'=>'_one_review',
  'sortableAttributes'=>array('created'),
));
?>

