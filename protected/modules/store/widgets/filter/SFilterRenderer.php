<?php

/**
 * Base class to render filters by:
 *  Manufacturer
 *  Price
 *  Eav attributes
 *
 * Usage:
 * $this->widget('application.modules.store.widgets.SFilterRenderer', array(
 *      // StoreCategory model. Used to create url
 *      'model'=>$model,
 *  ));
 *
 * @method CategoryController getOwner()
 */
class SFilterRenderer extends CWidget
{

	/**
	 * @var array of StoreAttribute models
	 */
	public $attributes;

	/**
	 * @var StoreCategory
	 */
	public $model;

	/**
	 * @var array html option to apply to `Clear attributes` link
	 */
	public $clearLinkOptions = array('class'=>'clearOptions');

	/**
	 * @var array of options to apply to 'active filters' menu
	 */
	public $activeFiltersHtmlOptions = array('class'=>'filter_links current');

	/**
	 * @var string default view to render results
	 */
	public $view = 'default';

	/**
	 * @var string min price in the query
	 */
	private $_currentMinPrice = null;

	/**
	 * @var string max price in the query
	 */
	private $_currentMaxPrice = null;

  public $allowedPageLimit = array();

	/**
	 * Render filters
	 */
	public function run()
	{
    $this->allowedPageLimit=explode(',',Yii::app()->settings->get('core', 'productsPerPage'));
		$this->render($this->view, array(
//			'manufacturers'=>$this->getCategoryManufacturers(),
//			'attributes'=>$this->getCategoryAttributes(),
//      'rating' => $this->getRating()
		));
	}

	/**
	 * Get active/applied filters to make easier to cancel them.
	 */
	public function getActiveFilters()
	{
		// Render links to cancel applied filters like prices, manufacturers, attributes.
		$menuItems = array();
    /*
		$manufacturers = array_filter(explode(';', Yii::app()->request->getQuery('manufacturer')));
		$manufacturers = StoreManufacturer::model()->findAllByPk($manufacturers);
    */

		if(Yii::app()->request->getQuery('min_price')){
			array_push($menuItems, array(
				'label'=> Yii::t('StoreModule', 'от {minPrice} {c}', array('{minPrice}'=>(int)$this->getCurrentMinPrice(), '{c}'=>Yii::app()->currency->active->symbol)),
				'url'  => Yii::app()->request->removeUrlParam('/store/category/view', 'min_price'),
        'type' => 'min_price'
			));
		}
		if(Yii::app()->request->getQuery('max_price')){
			array_push($menuItems, array(
				'label'=> Yii::t('StoreModule', 'до {maxPrice} {c}', array('{maxPrice}'=>(int)$this->getCurrentMaxPrice(), '{c}'=>Yii::app()->currency->active->symbol)),
				'url'  => Yii::app()->request->removeUrlParam('/store/category/view', 'max_price'),
        'type' => 'max_price'
			));
		}
/*
		if(!empty($manufacturers))
		{
			foreach($manufacturers as $manufacturer)
			{
				array_push($menuItems, array(
					'label'=> $manufacturer->name,
					'url'  => Yii::app()->request->removeUrlParam('/store/category/view', 'manufacturer', $manufacturer->id),
          'type' => 'manufacturer'
				));
			}
		}
*/
		// Process eav attributes
/*
		$activeAttributes = $this->getOwner()->activeAttributes;
		if(!empty($activeAttributes))
		{
			foreach($activeAttributes as $attributeName=>$value)
			{
				if(isset($this->getOwner()->eavAttributes[$attributeName]))
				{
					$attribute = $this->getOwner()->eavAttributes[$attributeName];
					foreach($attribute->options as $option)
					{
						if(isset($activeAttributes[$attribute->name]) && in_array($option->id, $activeAttributes[$attribute->name]))
						{
							array_push($menuItems, array(
								'label'=> $option->value,
								'url'  => Yii::app()->request->removeUrlParam('/store/category/view', $attribute->name, $option->id),
                'type' => 'attrs'
							));
						}
					}
				}
			}
		}
*/
		return $menuItems;
	}

	/**
	 * @return mixed
	 */
	public function getCurrentMinPrice()
	{
		if($this->_currentMinPrice!==null)
			return $this->_currentMinPrice;

		if(Yii::app()->request->getQuery('min_price'))
			$this->_currentMinPrice=Yii::app()->request->getQuery('min_price');
		else
			$this->_currentMinPrice=Yii::app()->currency->convert($this->controller->getMinPrice());

		return $this->_currentMinPrice;
	}

	/**
	 * @return mixed
	 */
	public function getCurrentMaxPrice()
	{
		if($this->_currentMaxPrice!==null)
			return $this->_currentMaxPrice;

		if(Yii::app()->request->getQuery('max_price'))
			$this->_currentMaxPrice=Yii::app()->request->getQuery('max_price');
		else
			$this->_currentMaxPrice=Yii::app()->currency->convert($this->controller->getMaxPrice());

		return $this->_currentMaxPrice;
	}

	/**
	 * Proxy to SCurrencyManager::activeToMain
	 * @param $sum
	 */
	public function convertCurrency($sum)
	{
		$cm=Yii::app()->currency;
		if($cm->active->id!=$cm->main->id)
			return $cm->activeToMain($sum);
		return $sum;
	}

  /**
   * @return array of attributes used in category
   */
  /*
	public function getCategoryAttributes()
	{
		$data = array();

		foreach($this->attributes as $attribute)
		{
			$data[$attribute->name] = array(
				'title'      => $attribute->title,
				'selectMany' => (boolean) $attribute->select_many,
				'filters'    => array()
			);
			foreach($attribute->options as $option)
			{
				$data[$attribute->name]['filters'][] = array(
					'title'      => $option->value,
					'count'      => $this->countAttributeProducts($attribute, $option),
					'queryKey'   => $attribute->name,
					'queryParam' => $option->id,
				);
			}
		}
		return $data;
	}
*/
  /**
   * Count products by attribute and option
   * @param StoreAttribute $attribute
   * @param string $option option id to search
   * @todo Optimize attributes merging
   * @return string
   */
  /*
	public function countAttributeProducts($attribute, $option){
		$model = new StoreProduct(null);
		$model->attachBehaviors($model->behaviors());
		$model->active()
			->applyCategoriesRecursive($this->model)
			->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')))
			->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')))
      ->applyRating(Yii::app()->request->getQuery('rating'));

		if(Yii::app()->request->getParam('manufacturer'))
			$model->applyManufacturers(explode(';', Yii::app()->request->getParam('manufacturer')));

		// $data = array($attribute->name=>$option->id);
		$current = $this->getOwner()->activeAttributes;

		$newData = array();

		foreach($current as $key=>$row){
			if(!isset($newData[$key])) $newData[$key] = array();
			if(is_array($row)){
				foreach($row as $v)	$newData[$key][] = $v;
			}	else $newData[$key][] = $row;
		}

		$newData[$attribute->name][] = $option->id;
		return $model->withEavAttributes($newData)->count();
	}
  */
  /**
   * @return array of category manufacturers
   */
  /*
	public function getCategoryManufacturers()
	{
		$cr = new CDbCriteria;
		$cr->select = 't.manufacturer_id, t.id';
		$cr->group  = 't.manufacturer_id';
		$cr->addCondition('t.manufacturer_id IS NOT NULL');

		//@todo: Fix manufacturer translation
		$manufacturers = StoreProduct::model()
			->active()
			->applyCategoriesRecursive($this->model, null)
			->with(array(
				'manufacturer'=>array(
					'with'=>array(
						'productsCount'=>array(
							'scopes'=>array(
								'active',
								'applyCategoriesRecursive' => array($this->model, null),
								'applyAttributes' => array($this->getOwner()->activeAttributes),
								'applyMinPrice'   => array($this->convertCurrency(Yii::app()->request->getQuery('min_price'))),
								'applyMaxPrice'   => array($this->convertCurrency(Yii::app()->request->getQuery('max_price'))),
                'applyRating'     => array((Yii::app()->request->getQuery('rating')))
							))
					),
				)))
			->findAll($cr);

		$data = array(
			'title'      => Yii::t('StoreModule', 'Производитель'),
			'selectMany' => true,
			'filters'    => array()
		);

		if($manufacturers)
		{
			foreach($manufacturers as $m)
			{
				$m = $m->manufacturer;
				if($m)
				{
					$model = new StoreProduct(null);
					$model->attachBehaviors($model->behaviors());
					$model->active()
						->applyCategoriesRecursive($this->model)
						->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')))
						->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')))
						->applyAttributes($this->getOwner()->activeAttributes)
						->applyManufacturers($m->id)
            ->applyRating(Yii::app()->request->getQuery('rating'));

					$data['filters'][] = array(
						'title'      => $m->name,
						'count'      => $model->count(),
						'queryKey'   => 'manufacturer',
						'queryParam' => $m->id,
					);
				}
			}
		}

		return $data;
	}
*/
  /*
  public function getRating()
  {
    $data = array(
      'filters' => array(
        '5' => array('title' => '5+', 'queryKey' => 'rating', 'queryParam' => 5),
        '4' => array('title' => '4', 'queryKey' => 'rating', 'queryParam' => 4),
        '3' => array('title' => '3', 'queryKey' => 'rating', 'queryParam' => 3),
        '2' => array('title' => '2', 'queryKey' => 'rating', 'queryParam' => 2),
        '1' => array('title' => '1', 'queryKey' => 'rating', 'queryParam' => 1),
      ),
    );
    return $data;
  }
*/

}
