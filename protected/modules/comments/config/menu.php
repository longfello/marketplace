<?php

/**
 * @var $this SSystemMenu
 */

Yii::import('comments.CommentsModule');

/**
 * Admin menu items for pages module
 */
return array(
	array(
		'label'    => Yii::t('CommentsModule', 'Комментарии'),
		'url'      => array('/comments/admin/index'),
		'position' => 4,
    'ManagerAccess' => true,
		'itemOptions' => array(
			'class'       => 'hasRedCircle circle-comments',
		),
	),
);