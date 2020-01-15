<?php

Yii::import('application.modules.users.UsersModule');

/**
 * Admin menu for users module.
 */
return array(
    'users'=>array(
        'items'=>array(
            array(
                'label'=>Yii::t('UsersModule', 'Пользователи'),
                'url'=>array('/admin/users'), 
                'position'=>3
            ),
        ),
    ),
  'tracker'=>array(
    'label'    => Yii::t('UsersModule', 'Сообщения'),
    'url'      => array('/admin/users/default/tracker'),
    'position' => 9
  ),
);