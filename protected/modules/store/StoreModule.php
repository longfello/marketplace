<?php

class StoreModule extends BaseBootstrapModule
{
	public $moduleName = 'store';

	public function init()
	{
		$this->setImport(array(
			'store.models.*',
			'store.components.*'
		));
	}
}
