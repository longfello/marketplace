<?php

  // change the following paths if necessary
  $yii = __DIR__."/framework/yii.php";
  $config=__DIR__."/protected/config/worker.php";

  defined('YII_DEBUG') or define('YII_DEBUG',true);
  // specify how many levels of call stack should be shown in each log message
  defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
  define('YII_ENABLE_ERROR_HANDLER', false);
  define('YII_ENABLE_EXCEPTION_HANDLER', false);

  require_once($yii);
  require_once("protected/extensions/worker/WorkerApplication.php");

  echo("Worker started with PID = ".getmypid()."\n");

  Yii::createApplication("WorkerApplication", $config)->run();
