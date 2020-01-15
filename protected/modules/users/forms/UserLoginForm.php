<?php

/**
 * User login form
 */
class UserLoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe=false;

	private $_identity;

	/**
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('username, password', 'required'),
			array('password', 'authenticate'),
			array('rememberMe', 'boolean'),
		);
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'username'   => Yii::t('widget-login', 'Login'),
			'password'   => Yii::t('widget-login', 'Password'),
			'rememberMe' => Yii::t('widget-login', 'Remember Me'),
		);
	}

	/**
	 * Try to authenticate user
	 */
	public function authenticate()
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password',Yii::t('UsersModule', 'Неправильное имя пользователя или пароль.'));
		}
	}

	/**
	 * @return mixed
	 */
	public function getIdentity()
	{
		return $this->_identity;
	}
}
