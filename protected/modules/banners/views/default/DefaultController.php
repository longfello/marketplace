<?php

  class DefaultController extends Controller {
    public function actionClick(){

      $bnrUrl=Banners::model()->bannersClick(array('id'=>Yii::app()->getRequest()->getQuery('id')));
      if(isset($bnrUrl) and !empty($bnrUrl))
        $this->redirect($bnrUrl);
      else
        $this->redirect("/");
    }
  }