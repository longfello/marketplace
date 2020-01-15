<?php

class NewsModule extends BaseBootstrapModule {

	public function init(){
		$this->setImport(array(
			'news.models.*',
			'news.components.*',
		));
	}
}
