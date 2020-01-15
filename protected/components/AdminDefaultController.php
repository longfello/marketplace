<?php

class AdminDefaultController extends RController
{
  public $layout = 'application.modules.UserAdmin.views.layouts.admin';
  public $modelName;
  public $ajaxValidation = true;

  public $freeAccess = false;
  public $freeAccessActions = array();

  // You can copy it in inherited controller, to choose, which actions will be moderated
  //public $moderatedActions = array('index', 'view', 'create', 'update', 'admin', 'delete', 'deleteSelected', 'toggleState', 'sorterUpdate');

  public $noPages = array();
  public $createRedirect = array();
  public $updateRedirect = 'goBack';



}
