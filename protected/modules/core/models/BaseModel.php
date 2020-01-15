<?php

class BaseModel extends CActiveRecord {

	/**
	 * Initialize component
	 */
	public function init(){
    if (!Helper::isCli()) {
      SModelEventManager::attachEvents($this);
      $this->attachBehavior('Manager', 'application.extensions.ManagerBehavior');
    }
    parent::init();
	}

}