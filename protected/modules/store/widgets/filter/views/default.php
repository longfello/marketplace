<?php

/**
 * @var $this SFilterRenderer
 */

/**
 * Render filters based on the next array:
 * $data[attributeName] = array(
 *	    'title'=>'Filter Title',
 *	    'selectMany'=>true, // Can user select many filter options
 *	    'filters'=>array(array(
 *	        'title'      => 'Title',
 *	        'count'      => 'Products count',
 *	        'queryKey'   => '$_GET param',
 *	        'queryParam' => 'many',
 *	    ))
 *  );
 */

// Render active filters
  $active = $this->getActiveFilters();
if(!empty($active)) {
  echo CHtml::openTag('div', array('class'=>'clear-filter'));
  /*
		echo CHtml::openTag('div', array('class'=>'filter_header'));
		echo Yii::t('StoreModule', 'Текущие фильтры');
		echo CHtml::closeTag('div');

		$this->widget('zii.widgets.CMenu', array(
			'htmlOptions'=>$this->activeFiltersHtmlOptions,
			'items'=>$active
		));
  */

  echo CHtml::link(Yii::t('StoreModule','Сбросить фильтр'), $this->getOwner()->model->viewUrl, array('class'=>'cancel_filter'));
	echo CHtml::closeTag('div');
}
?>


<div class="rounded price_slider">
	<div class="filter_header">
		<?php echo Yii::t('StoreModule', 'Цена') ?>
	</div>
<?php
	$cm=Yii::app()->currency;
	echo $this->widget('zii.widgets.jui.CJuiSlider', array(
		'options'=>array(
			'range'=>true,
			'min'=>(int)floor($cm->convert($this->controller->getMinPrice())),
			'max'=>(int)ceil($cm->convert($this->controller->getMaxPrice())),
			'disabled'=>(int)$this->controller->getMinPrice()===(int)$this->controller->getMaxPrice(),
			'values'=>array($this->currentMinPrice, $this->currentMaxPrice),
			'slide'=>'js: function( event, ui ) {
				$("#min_price").val(ui.values[0]);
				$("#max_price").val(ui.values[1]);
			}',
			'change'=>'js: function( event, ui ) {
				$(".price_slider form").submit();
			}',
		),
		'htmlOptions'=>array(
			'style'=>'margin:5px',
		),
	), true);
?>
<?php echo CHtml::form() ?>
  <?=Yii::t('StoreModule', 'от')?> <?php echo CHtml::textField('min_price', (isset($_GET['min_price'])) ? (int)$this->getCurrentMinPrice():null ) ?>
  <?=Yii::t('StoreModule', 'до')?> <?php echo CHtml::textField('max_price', (isset($_GET['max_price'])) ? (int)$this->getCurrentMaxPrice():null ) ?>
	<?php echo Yii::app()->currency->active->symbol ?>
	<button class="filter_submit" type="submit"><?=Yii::t('StoreModule', 'OK')?></button>
<?php echo CHtml::endForm() ?>

</div>

<?php
/*
  if(!empty($manufacturers['filters']) || !empty($attributes)) echo CHtml::openTag('div', array('class'=>'rounded filter-by-param'));

	// Display attributes
	foreach($attributes as $attrData)
	{
    $overall_count = 0;
    foreach($attrData['filters'] as $filter) {
      $overall_count += $filter['count'];
    }

    if ($overall_count > 0) {
      echo CHtml::openTag('div', array('class'=>'filter_item'));
      echo CHtml::openTag('div', array('class'=>'filter_header'));
      echo CHtml::encode($attrData['title']);
      echo CHtml::closeTag('div');

      echo CHtml::openTag('ul', array('class'=>'filter_links'));

      foreach($attrData['filters'] as $filter){
        if ($filter['count'] > 0) {
          $url = Yii::app()->request->addUrlParam('/store/category/view', array($filter['queryKey'] => $filter['queryParam']), $attrData['selectMany']);
          $queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));

          echo CHtml::openTag('li', in_array($filter['queryParam'], $queryData)?array('class' => 'active'):array());
          // Filter link was selected.
          if(in_array($filter['queryParam'], $queryData))
          {
            // Create link to clear current filter
            $url = Yii::app()->request->removeUrlParam('/store/category/view', $filter['queryKey'], $filter['queryParam']);
            echo CHtml::link($filter['title'], $url);
          }
          elseif($filter['count'] > 0)
            echo CHtml::link($filter['title'], $url).' ('.$filter['count'].')';
          else
            echo $filter['title'].' (0)';

          echo CHtml::closeTag('li');
        }
      }
      echo CHtml::closeTag('ul');
      echo CHtml::closeTag('div');
    }
	}

  if(!empty($manufacturers['filters']) || !empty($attributes))
    echo CHtml::closeTag('div');
*/
  ?>
  <div class="actions">
    <?php
      echo Yii::t('StoreModule', 'Сортировать:');
      echo CHtml::dropDownList('sorter', Yii::app()->request->url, array(
        Yii::app()->request->removeUrlParam('/store/category/view', 'sort')  => '---',
        Yii::app()->request->addUrlParam('/store/category/view', array('sort'=>'price'))  => Yii::t('StoreModule', 'Сначала дешевые'),
        Yii::app()->request->addUrlParam('/store/category/view', array('sort'=>'price.desc')) => Yii::t('StoreModule', 'Сначала дорогие'),
        Yii::app()->request->addUrlParam('/store/category/view', array('sort'=>'views_count.desc')) => Yii::t('StoreModule', 'Сначала популярные'),
        Yii::app()->request->addUrlParam('/store/category/view', array('sort'=>'created.desc')) => Yii::t('StoreModule', 'Сначала последние добавленные'),
      ), array('onchange'=>'applyCategorySorter(this)'));
    ?>

  <?php
    $limits=array(Yii::app()->request->removeUrlParam('/store/category/view', 'per_page')  => $this->allowedPageLimit[0]);
    array_shift($this->allowedPageLimit);
    foreach($this->allowedPageLimit as $l)
      $limits[Yii::app()->request->addUrlParam('/store/category/view', array('per_page'=> $l))]=$l;

    echo Yii::t('StoreModule', 'На странице:');
    echo CHtml::dropDownList('per_page', Yii::app()->request->url, $limits, array('onchange'=>'applyCategorySorter(this)'));
  ?>
  </div>
<?php
