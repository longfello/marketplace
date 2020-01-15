<?php

define('VERSION', '1.5');


  // change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
// require_once(dirname(__FILE__).'/protected/components/CRivoriWebApplication.php');

require 'protected/components/SWebApplication.php';

// $app = Yii::createApplication('CRivoriWebApplication', $config);
$app = Yii::createApplication('SWebApplication', $config);
//  Yii::createApplication('SWebApplication', $config)->run();

$app->run();
