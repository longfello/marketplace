<?php

class CoreModule extends BaseBootstrapModule {
	
	public $moduleName = 'core';

	public function init()
	{
		$this->setImport(array(
			'application.modules.core.models.*',
		));
	}

}