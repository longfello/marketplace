<?php

/**
 * Admin login form
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe=false;

	public $_config;

	private $_identity;

	public function getConfig()
	{
		return $config = array(
			'elements'=>array(
				'username'=>array(
					'label'=>Yii::t('AdminModule', 'Логин'),
					'type'=>'text',
					'maxlength'=>32,
				),
				'password'=>array(
					'label'=>Yii::t('AdminModule', 'Пароль'),
					'type'=>'password',
					'maxlength'=>32,
				),
				'rememberMe'=>array(
					'label'=>Yii::t('AdminModule', 'Запомнить меня'),
					'type'=>'checkbox',
				)
			),

			'buttons'=>array(
				'login'=>array(
					'type'=>'submit',
					'label'=>Yii::t('AdminModule', 'Вход')
				)
			),
		);
	}

	/**
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('username, password', 'required'),
			array('rememberMe', 'boolean'),
			array('password', 'authenticate'),
		);
	}

	/**
	 * Authenticate user
	 */
	public function authenticate()
	{
		$this->_identity=new UserIdentity($this->username,$this->password);
		if(!$this->_identity->authenticate())
			$this->addError('password',Yii::t('AdminModule', 'Неправильное имя пользователя или пароль.'));
	}

	/**
	 * @return mixed
	 */
	public function getIdentity()
	{
		return $this->_identity;
	}
}
