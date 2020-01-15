<?php

class BannersModule extends BaseBootstrapModule {

	public function init(){
		$this->setImport(array(
			'banners.models.*',
			'banners.components.*',
		));
	}
}
