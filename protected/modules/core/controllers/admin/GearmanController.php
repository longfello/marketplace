<?php

/**
 * Manage system languages
 * @package core.systemLanguages
 */
class GearmanController extends SAdminController
{
  public function beforeAction($action) {
    Helper::registerJS('bootstrap.min.js', CClientScript::POS_HEAD);
    Helper::registerCSS('bootstrap.css');
    Helper::registerCSS('bootstrap-theme.min.css');
    return parent::beforeAction($action);
  }

	public function actionIndex(){
    $client = Yii::app()->gearman;

    $t = array();
    $tasks = GearmanTasks::model()->findAll();
    foreach($tasks as $task){
      $status = $client->getTaskStatus($task->tid);
      if (!$status->done) {
        $t[$task->tid] = $status;
      }
    }
    foreach($client->servers as $key => $server) {
      $client->servers[$key]['pids'] = $client->getWorkersPids($key);
    }

		$this->render('index', array(
			'statuses' => $client->getStatus(),
      'tasks'    => $t,
      'servers'  => $client->servers
		));
    // echo(555);
    /*
    */
	}

  public function actionKill($pid, $server){
    /* @var $client Gearmand */
    $client = Yii::app()->gearman;

    $client->stopWorker($pid, $server);
    $this->redirect('/admin/core/gearman');
  }

  public function actionStart($server){
    /* @var $client Gearmand */
    $client = Yii::app()->gearman;
    $client->startWorker($server);
    $this->redirect('/admin/core/gearman');
  }

  public function actionRestart($server = null){
    /* @var $client Gearmand */
    $client = Yii::app()->gearman;

    if (is_null($server)) {
      foreach($client->servers as $key => $server) {
        $client->restartWorkers($key);
      }
      $this->setFlashMessage(Yii::t('Gearman', "Потоки на всех серверах перезапущены"));
    } else {
      $client->restartWorkers($server);
      $this->setFlashMessage(Yii::t('Gearman', "Потоки на сервере перезапущены"));
    }

    $this->redirect('/admin/core/gearman');
  }

}