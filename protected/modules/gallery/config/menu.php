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
				'label'=>Yii::t('GalleryModule', 'Слайдер на главной'),
				'url'=>array('/admin/gallery'),
				'position'=>2
			),
		),
	),
);