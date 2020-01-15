<?php

/**
 * Base admin module 
 * 
 * @uses CWebModule
 * @package Admin
 * @version $id$
 */
class BaseBootstrapModule extends BaseModule {

  public function beforeControllerAction($controller, $action){
    if(parent::beforeControllerAction($controller, $action)){
      if(strpos($controller->getRoute(), 'admin') !== FALSE) {
        Helper::registerJS('bootstrap.min.js', CClientScript::POS_HEAD);
        Helper::registerCSS('bootstrap.css');
        Helper::registerCSS('bootstrap-theme.min.css');
      }
      return true;
    } else return false;
  }

}
