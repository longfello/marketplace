<?php

/**
 * User create/update form data 
 */
Yii::import('zii.widgets.jui.CJuiDatePicker');

return array(
	'id'=>'userUpdateForm',
	'showErrorSummary'=>true,
	'elements'=>array(
		'user'=>array(
			'type'=>'form',
			'title'=>'',
			'elements'=>array(
				'username'=>array(
					'type'=>'text',
				),
        'password'=>array('type'=>'password',),
				'email'=>array('type'=>'text',),
				'created_at'=>array(
					'type'=>'CJuiDatePicker',
					'options'=>array(
						'dateFormat'=>'yy-mm-dd '.date('H:i:s'),
					),
				),
				'last_login'=>array(
					'type'=>'CJuiDatePicker',
					'options'=>array(
						'dateFormat'=>'yy-mm-dd '.date('H:i:s'),
					),
				),
				'login_ip'=>array('type'=>'text',),
				'discount'=>array('type'=>'text',),
				'new_password'=>array(
					'type'=>'password',
				),
				'banned'=>array(
					'type'=>'checkbox'
				),
			),
		),
		'profile'=>array(
			'type'=>'form',
			'title'=>Yii::t('UsersModule', 'Данные профиля'),
			'elements'=>array(
				'full_name'=>array(
					'type'=>'text'
				),
        'person'=>array(
          'type'=>'text',
        ),
				'phone'=>array(
					'type'=>'text',
				),
				'delivery_address'=>array(
					'type'=>'textarea',
				),
			),
		),
	),
);
