<?php

/**
 * Admin menu items for pages module
 */
return array(
	'cms'=>array(
		'position'=>5,
		'items'=>array(
			array(
				'label'=>Yii::t('NewsModule', 'Новости'),
				'url'=>array('/admin/news'),
				'position'=>4
			),
		),
	),
);