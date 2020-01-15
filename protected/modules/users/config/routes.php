<?php

return array(
	'users/login'=>'users/login/login',
	'users/register'=>'users/register/register',
	'users/registerManager'=>'users/register/registerManager',
	'users/register/captcha/*'=>'users/register/captcha',
	'users/profile'=>'users/profile/index',
	'users/profile/orders'=>'users/profile/orders',
	'users/logout'=>'users/login/logout',
	'users/remind/activatePassword/<key>'=>array('users/remind/activatePassword'),
);