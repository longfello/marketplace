<?php

Yii::import('application.modules.pages.PagesModule');

/**
 * Admin menu items for pages module
 */
return array(
	'cms'=>array(
		'position'=>5,
		'items'=>array(
			array(
				'label'=>'Статические страницы',
				'url'=>array('/admin/staticpages'),
				'position'=>1
			),
		),
	),
);