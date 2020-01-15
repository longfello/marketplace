<?php

/**
 * StoreModule routes
 */
return array(
  'product/captcha'                         => array('store/frontProduct/captcha'),
	'product/<url>'                           => array('store/frontProduct/view', 'urlSuffix'=>'.html'),
	'store/ajax/activateCurrency/<id>'        => array('store/ajax/activateCurrency'),
	'store/ajax/rateProduct/<id>'             => array('store/ajax/rateProduct'),
	'store/index/renderProductsBlock/<scope>' => array('store/index/renderProductsBlock'),
	array(
		'class' => 'application.modules.store.components.SStoreCategoryUrlRule'
	),
	'products/search/*'                       => array('store/category/search'),
	'products/ac/*'                           => array('store/category/ac'),
	// Compare
	'products/compare'             => array('store/compare/index'),
	'products/compare/add/<id>'    => array('store/compare/add'),
	'products/compare/remove/<id>' => array('store/compare/remove'),
	// WishList
	'wishlist'             => array('store/wishlist/index'),
	'wishlist/add/<id>'    => array('store/wishlist/add'),
	'wishlist/remove/<id>' => array('store/wishlist/remove'),
	'wishlist/view/<key>'  => array('store/wishlist/view'),
	'store/index'          => 'site/error',
	'store/compare'        => 'site/error',
	'store/wishlist'       => 'site/error',
	'manufacturer/<url>'   => array('/store/manufacturer/index'),
	'market'               => array('/store/market/index'),
	'market/<id>'          => array('/store/market/view'),
);