<?php

Yii::import('orders.models.*');
Yii::import('store.models.*');

/**
 * Cart controller
 * Display user cart and create new orders
 */
class CartController extends Controller
{
  public $hasRightColumn = 'xlite';

	/**
	 * @var OrderCreateForm
	 */
	public $form;

	/**
	 * @var bool
	 */
	protected $_errors = false;

	/**
	 * Display list of product added to cart
	 */
	public function actionIndex()
	{
		// Recount
		if(Yii::app()->request->isPostRequest && Yii::app()->request->getPost('recount') && !empty($_POST['quantities']))
			$this->processRecount();

		$this->form = new OrderCreateForm;

		// Make order
		if(Yii::app()->request->isPostRequest && Yii::app()->request->getPost('create'))
		{
			if(isset($_POST['OrderCreateForm']))
			{
				$this->form->attributes = $_POST['OrderCreateForm'];

				if($this->form->validate())
				{
					$orders = $this->createOrders();
					Yii::app()->cart->clear();
					$this->addFlashMessage(Yii::t('OrdersModule', 'Спасибо. Ваш заказ принят.'));
					//Yii::app()->request->redirect($this->createUrl('view', array('secret_key'=>$order->secret_key)));
			    $secret_key_array = '';
          foreach ($orders as $one) {
            $secret_key_array[] = $one->secret_key;
          }
          $secret_key = implode('-', $secret_key_array);
          Yii::app()->request->redirect($this->createUrl('view', array('secret_key'=>$secret_key)));
			  }
			}
		}
    /*
		$deliveryMethods = StoreDeliveryMethod::model()
			->applyTranslateCriteria()
			->active()
			->orderByName()
			->findAll();
    */
    $items = Yii::app()->cart->getDataWithModels();

    $items_with_markets = array();
    foreach ($items as $index=>$one) {
      $id = $one['model']->market_id;
      if (!isset($items_with_markets[$id]))
      {
        $items_with_markets[$id] = array();
        $items_with_markets[$id]['items'] = array();
        $items_with_markets[$id]['price'] = 0;
      }
      $one['index'] = $index;
      $items_with_markets[$id]['items'][] = $one;
      $price = StoreProduct::calculatePrices($one['model'], $one['variant_models'], $one['configurable_id']);
      $items_with_markets[$id]['price'] += Yii::app()->currency->convert($price * $one['quantity']);
    }

    foreach ($items_with_markets as $key=>$one) {
      $criteria = new CDbCriteria;
      $criteria->addCondition('market_id = '.$key);
      $deliveryMethods = StoreDeliveryMethod::model()
        ->applyTranslateCriteria()
        ->active()
        ->orderByName()
        ->findAll($criteria);
      $items_with_markets[$key]['delivery'] = $deliveryMethods;
      $items_with_markets[$key]['market'] = StoreMarket::model()->findByPk($key);
    }


		$this->render('index', array(
			'markets'           => $items_with_markets,
			'totalPrice'      => Yii::app()->currency->convert(Yii::app()->cart->getTotalPrice()),
      'other_markets'   => count($items_with_markets)>1
		));
	}

	/**
	 * Find order by secret_key and display.
	 * @throws CHttpException
	 */
	public function actionView()
	{
		$secret_key = Yii::app()->request->getParam('secret_key');
    $orders_key = explode('-', $secret_key);
    $orders = array();
    foreach ($orders_key as $one) {
      $model = Order::model()->find('secret_key=:secret_key', array(':secret_key'=>$one));
      if(!$model)
        throw new CHttpException(404, Yii::t('OrdersModule', 'Ошибка. Заказ не найден.'));

      $orders[] = $model;
    }



		$this->render('view', array(
			'orders'=>$orders,
		));
	}

	/**
	 * Validate POST data and add product to cart
	 */
	public function actionAdd()
	{
		$variants = array();

		// Load product model
		$model = StoreProduct::model()
			->active()
			->findByPk(Yii::app()->request->getPost('product_id', 0));

		// Check product
		if(!isset($model))
			$this->_addError(Yii::t('OrdersModule', 'Ошибка. Продукт не найден'), true);

		// Update counter
		$model->saveCounters(array('added_to_cart_count'=>1));

		// Process variants
		if(!empty($_POST['eav']))
		{
			foreach($_POST['eav'] as $attribute_id=>$variant_id)
			{
				if(!empty($variant_id))
				{
					// Check if attribute/option exists
					if(!$this->_checkVariantExists($_POST['product_id'], $attribute_id, $variant_id))
						$this->_addError(Yii::t('OrdersModule', 'Ошибка. Вариант продукта не найден.'));
					else
						array_push($variants, $variant_id);
				}
			}
		}

		// Process configurable products
		if($model->use_configurations)
		{
			// Get last configurable item
			$configurable_id = Yii::app()->request->getPost('configurable_id', 0);

			if(!$configurable_id || !in_array($configurable_id , $model->configurations))
				$this->_addError(Yii::t('OrdersModule', 'Ошибка. Выберите вариант продукта.'), true);
		}else
			$configurable_id  = 0;

		Yii::app()->cart->add(array(
			'product_id'      => $model->id,
			'variants'        => $variants,
			'configurable_id' => $configurable_id,
			'quantity'        => (int) Yii::app()->request->getPost('quantity', 1),
			'price'           => $model->price,
		));

		$this->_finish();
	}

	/**
	 * Remove product from cart and redirect
	 */
	public function actionRemove($index)
	{
		Yii::app()->cart->remove($index);

		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->request->redirect($this->createUrl('index'));
	}

	/**
	 * Clear cart
	 */
	public function actionClear()
	{
		Yii::app()->cart->clear();

		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->request->redirect($this->createUrl('index'));
	}

	/**
	 * Render data to display in theme header.
	 */
	public function actionRenderSmallCart()
	{
		$this->renderPartial('_small_cart');
	}

	/**
	 * Create new order
	 * @return Order
	 */
	public function createOrder()
	{
		if(Yii::app()->cart->countItems() == 0)
			return false;

		$order = new Order;

		// Set main data
		$order->user_id      = Yii::app()->user->isGuest ? null : Yii::app()->user->id;
		$order->user_name    = $this->form->name;
		$order->user_email   = $this->form->email;
		$order->user_phone   = $this->form->phone;
		$order->user_address = $this->form->address;
		$order->user_comment = $this->form->comment;
		$order->delivery_id  = $this->form->delivery_id;

		if($order->validate())
			$order->save();
		else
			throw new CHttpException(503, Yii::t('OrdersModule', 'Ошибка создания заказа'));

		// Process products
		foreach(Yii::app()->cart->getDataWithModels() as $item)
		{
			$ordered_product = new OrderProduct;
			$ordered_product->order_id        = $order->id;
			$ordered_product->product_id      = $item['model']->id;
			$ordered_product->configurable_id = $item['configurable_id'];
			$ordered_product->name            = $item['model']->name;
			$ordered_product->quantity        = $item['quantity'];
			$ordered_product->sku             = $item['model']->sku;
			$ordered_product->price           = StoreProduct::calculatePrices($item['model'], $item['variant_models'], $item['configurable_id']);

			// Process configurable product
			if(isset($item['configurable_model']) && $item['configurable_model'] instanceof StoreProduct)
			{
				$configurable_data=array();

				$ordered_product->configurable_name = $item['configurable_model']->name;
				// Use configurable product sku
				$ordered_product->sku = $item['configurable_model']->sku;
				// Save configurable data

				$attributeModels = StoreAttribute::model()->findAllByPk($item['model']->configurable_attributes);
				foreach($attributeModels as $attribute)
				{
					$method = 'eav_'.$attribute->name;
					$configurable_data[$attribute->title]=$item['configurable_model']->$method;
				}
				$ordered_product->configurable_data=serialize($configurable_data);
			}

			// Save selected variants as key/value array
			if(!empty($item['variant_models']))
			{
				$variants = array();
				foreach($item['variant_models'] as $variant)
					$variants[$variant->attribute->title] = $variant->option->value;
				$ordered_product->variants = serialize($variants);
			}

			$ordered_product->save();
		}

		// Reload order data.
		$order->refresh();

		// All products added. Update delivery price.
		$order->updateDeliveryPrice();

		// Send email to user.
		$this->sendEmail($order);

		return $order;
	}

  /**
   * Create new order
   * @return Order
   */
  public function createOrders()
  {
    if(Yii::app()->cart->countItems() == 0)
      return false;

    $orders = array();
    $delivery = Yii::app()->request->getPost('delivery_id');
    foreach(Yii::app()->cart->getDataWithModels() as $one)
    {
      $id = $one['model']->market_id;
      if (!isset($orders[$id])) {
        $orders[$id] = new Order;
        // Set main data
        $orders[$id]->user_id      = Yii::app()->user->isGuest ? null : Yii::app()->user->id;
        $orders[$id]->user_name    = $this->form->name;
        $orders[$id]->user_email   = $this->form->email;
        $orders[$id]->user_phone   = $this->form->phone;
        $orders[$id]->user_address = $this->form->address;
        $orders[$id]->user_comment = $this->form->comment;
        $orders[$id]->delivery_id  = $delivery[$id];
        $orders[$id]->market_id    = $id;

        if($orders[$id]->validate())
          $orders[$id]->save();
        else
          throw new CHttpException(503, Yii::t('OrdersModule', 'Ошибка создания заказа'));
      }
    }

    // Process products
    foreach(Yii::app()->cart->getDataWithModels() as $item)
    {
      $ordered_product = new OrderProduct;
      $ordered_product->order_id        = $orders[$item['model']->market_id]->id;
      $ordered_product->product_id      = $item['model']->id;
      $ordered_product->configurable_id = $item['configurable_id'];
      $ordered_product->name            = $item['model']->name;
      $ordered_product->quantity        = $item['quantity'];
      $ordered_product->sku             = $item['model']->sku;
      $ordered_product->price           = StoreProduct::calculatePrices($item['model'], $item['variant_models'], $item['configurable_id']);

      // Process configurable product
      if(isset($item['configurable_model']) && $item['configurable_model'] instanceof StoreProduct)
      {
        $configurable_data=array();

        $ordered_product->configurable_name = $item['configurable_model']->name;
        // Use configurable product sku
        $ordered_product->sku = $item['configurable_model']->sku;
        // Save configurable data

        $attributeModels = StoreAttribute::model()->findAllByPk($item['model']->configurable_attributes);
        foreach($attributeModels as $attribute)
        {
          $method = 'eav_'.$attribute->name;
          $configurable_data[$attribute->title]=$item['configurable_model']->$method;
        }
        $ordered_product->configurable_data=serialize($configurable_data);
      }

      // Save selected variants as key/value array
      if(!empty($item['variant_models']))
      {
        $variants = array();
        foreach($item['variant_models'] as $variant)
          $variants[$variant->attribute->title] = $variant->option->value;
        $ordered_product->variants = serialize($variants);
      }

      $ordered_product->save();
    }


    foreach ($orders as $one) {
      // Reload order data.
      $one->refresh();

      // All products added. Update delivery price.
      $one->updateDeliveryPrice();

      // Send email to user.
      $this->sendEmail($one);
    }

    return $orders;
  }

	/**
	 * Check if product variantion exists
	 * @param $product_id
	 * @param $attribute_id
	 * @param $variant_id
	 * @return string
	 */
	protected function _checkVariantExists($product_id, $attribute_id, $variant_id)
	{
		return StoreProductVariant::model()->countByAttributes(array(
			'id'           => $variant_id,
			'product_id'   => $product_id,
			'attribute_id' => $attribute_id
		));
	}

	/**
	 * Recount product quantity and redirect
	 */
	public function processRecount()
	{
		Yii::app()->cart->recount(Yii::app()->request->getPost('quantities'));

		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->request->redirect($this->createUrl('index'));
	}

	/**
	 * Add message to errors array.
	 * @param string $message
	 * @param bool $fatal finish request
	 */
	protected function _addError($message, $fatal = false)
	{
		if($this->_errors === false)
			$this->_errors = array();

		array_push($this->_errors, $message);

		if($fatal === true)
			$this->_finish();
	}

	/**
	 * Process result and exit!
	 */
	protected function _finish()
	{
		echo CJSON::encode(array(
			'errors'=>$this->_errors,
			'message'=>Yii::t('OrdersModule','Продукт успешно добавлен в {cart}', array(
				'{cart}'=>CHtml::link(Yii::t('OrdersModule', 'корзину'), array('/orders/cart/index'))
			)),
		));
		exit;
	}

	/**
	 * Sends email to user after create new order.
	 */
	private function sendEmail(Order $order)
	{
		$theme=Yii::t('OrdersModule', 'Ваш заказ #').$order->id;

		$lang=Yii::app()->language;
		$emailBodyFile=Yii::getPathOfAlias("application.emails.$lang").DIRECTORY_SEPARATOR.'new_order.php';
		
		// If template file does not exists use default russian translation
		if(!file_exists($emailBodyFile))
			$emailBodyFile=Yii::getPathOfAlias("application.emails.ru").DIRECTORY_SEPARATOR.'new_order.php';
		$body = $this->renderFile($emailBodyFile, array('order'=>$order), true);

		$mailer           = Yii::app()->mail;
		$mailer->From     = Yii::app()->params['adminEmail'];
		$mailer->FromName = Yii::app()->settings->get('core', 'siteName');
		$mailer->Subject  = $theme;
		$mailer->Body     = $body;
		$mailer->AddAddress($order->user_email);
		$mailer->AddReplyTo(Yii::app()->params['adminEmail']);
		$mailer->isHtml(true);
		$mailer->Send();
		$mailer->ClearAddresses();
	}
}
