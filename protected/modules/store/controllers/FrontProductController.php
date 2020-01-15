<?php

/**
 * Display product view page.
 */
class FrontProductController extends Controller
{

	/**
     * @var StoreProduct
	 */
	public $model;

	/**
	 * @return array
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
			),
		);
	}

	/**
	 * Display product
	 * @param string $url product url
	 */
	public function actionView($url)
	{

    $this->metaTitle = '';
		$this->_loadModel($url);
		$view = $this->setDesign($this->model, 'view');

    if($this->model->relatedProductCount)
    {
      Helper::registerCSS('jquery.bxslider.css');
      Helper::registerJS('jquery.bxslider.min.js', CClientScript::POS_HEAD);
      Yii::app()->clientScript->registerScript('product-slider', "
        $('#post-content .products_list').bxSlider({
          responsive: false,
          auto: true,
          minSlides: 5,
          maxSlides: 5,
          slideWidth: 152,
          moveSlides: 1
        });
      ");
      $this->postContent = $this->renderPartial('_related', array('model'=>$this->model), true);
    }


		$this->render($view, array(
			'model' => $this->model,
		));
	}

	/**
	 * Load StoreProduct model by url
	 * @param $url
	 * @return StoreProduct
	 * @throws CHttpException
	 */
	protected function _loadModel($url)
	{
		$this->model = StoreProduct::model()
			->active()
			->withUrl($url)
			->find();

		if (!$this->model)
			throw new CHttpException(404, Yii::t('StoreModule', 'Продукт не найден.'));

		$this->model->saveCounters(array('views_count'=>1));
    $this->model->addToLastViewedList();
		return $this->model;
	}


	/**
	 * Get data to render dropdowns for configurable product.
	 * Used on product view.
	 * array(
	 *      'attributes' // Array of StoreAttribute models used for configurations
	 *      'prices'     // Key/value array with configurations prices array(product_id=>price)
	 *      'data'       // Array to render dropdowns. array(color=>array('Green'=>'1/3/5/', 'Silver'=>'7/'))
	 * )
	 * @todo Optimize. Cache queries.
	 * @return array
	 */
	public function getConfigurableData()
	{
		$attributeModels = StoreAttribute::model()->findAllByPk($this->model->configurable_attributes);
		$models = StoreProduct::model()->findAllByPk($this->model->configurations);

		$data = array();
		$prices = array();
		foreach($attributeModels as $attr)
		{
			foreach($models as $m)
			{
				$prices[$m->id] = $m->price;
				if(!isset($data[$attr->name]))
					$data[$attr->name] = array('---'=>'0');

				$method = 'eav_'.$attr->name;
				$value = $m->$method;

				if(!isset($data[$attr->name][$value]))
					$data[$attr->name][$value] = '';

				$data[$attr->name][$value] .= $m->id.'/';
			}
		}

		return array(
			'attributes'=>$attributeModels,
			'prices'=>$prices,
			'data'=>$data,
		);
	}
}