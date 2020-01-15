<div class="button catalog-button gradient"><?=Yii::t('main','Каталог товаров')?></div>

<?php $this->widget('application.components.widgets.catalog', array('layout' => 'lite')); ?>
<?php $this->widget('application.components.widgets.catalogFilter', array(
  'model'=>$this->model,
  'attributes'=>$this->eavAttributes,
)); ?>
