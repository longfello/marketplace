<?php

/**
 * Admin menu items for pages module
 */
return array(
	'cms'=>array(
		'position'=>5,
		'items'=>array(
			array(
				'label'=>Yii::t('AdminModule', 'Баннера'),
				'url'=>array('/admin/banners'),
				'position'=>5
			),
		),
	),
);