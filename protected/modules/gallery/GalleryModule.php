<?php

class GalleryModule extends BaseBootstrapModule {

	public function init(){
		$this->setImport(array(
			'gallery.models.*',
			'gallery.components.*',
		));
	}
}
