<?php

/**
 * Category view
 * @var $this CategoryController
 * @var $model StoreCategory
 * @var $provider CActiveDataProvider
 * @var $categoryAttributes
 */

// Set meta tags
$this->pageTitle = ($this->model->meta_title) ? $this->model->meta_title : $this->model->name;
$this->pageKeywords = $this->model->meta_keywords;
$this->pageDescription = $this->model->meta_description;

// Create breadcrumbs
$ancestors = $this->model->excludeRoot()->ancestors()->findAll();

foreach($ancestors as $c)
	$this->breadcrumbs[$c->name] = $c->getViewUrl();

$this->breadcrumbs[] = $this->model->name;

?>

<div class="catalog_with_sidebar">
  <?php  if ($provider->totalItemCount) { ?>
    <div id="filter">
      <?php
        $this->widget('application.modules.store.widgets.filter.SFilterRenderer', array(
          'model'=>$this->model,
          'attributes'=>$this->eavAttributes,
        ));
      ?>
        <div class="clear"></div>
    </div>
  <?php } ?>

    <br>
  <?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
      'links'=>$this->breadcrumbs,
    ));
  ?>

	<div class="products_list">
		<h1><?php echo CHtml::encode($this->model->name); ?></h1>

		<?php
      if ($provider->totalItemCount) {
        $this->widget('zii.widgets.CListView', array(
          'dataProvider'=>$provider,
          'ajaxUpdate'=>false,
          'template'=>'{items} {pager} {summary}',
          'itemView'=>$itemView,
          'sortableAttributes'=>array(
            'name', 'price'
          ),
        ));
      }
		?>

	</div>
</div><!-- catalog_with_sidebar end -->
