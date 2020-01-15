<?php

/**
 * Class BaseUser
 *
 * @method BaseUser getUser
 * @property UserLocation $location
 *
 */
class BaseUser extends RWebUser {

	/**
	 * @var int
	 */
	public $rememberTime = 2622600;

	/**
	 * @var User model
	 */
	private $_model;

  /* @var UserLocation */
  private $location;

	/**
	 * @return string user email
	 */
	public function getEmail()
	{
		$this->_loadModel();
		return $this->_model->email;
	}

	/**
	 * @return string username
	 */
	public function getUsername()
	{
		$this->_loadModel();
		return $this->_model->username;
	}

	/**
	 * Load user model
	 */
	private function _loadModel()
	{
		if(!$this->_model)
			$this->_model = User::model()->findByPk($this->id);
	}

  public function getUserMarketsIds(){
    $ids = array();
    $this->_loadModel();
    foreach($this->_model->markets as $market) {
      $ids[] = $market->id;
    }
    return $ids;
  }

  public function getIsManager(){
    return ($this->checkAccess('ViewManager') && !$this->getIsSuperuser());
  }

	public function getModel()
	{
		$this->_loadModel();
		return $this->_model;
	}

  public function getLocation(){
    if (!$this->location) {
      $this->location = new UserLocation();
    }
    return $this->location;
  }

}
