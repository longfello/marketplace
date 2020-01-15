<?php

  class WorkerController extends AbstractWorkerController
  {
    public $id;
    /* @var $importer Importer */
    public $importer;

    public function actionGetpid(WorkerJob $job){
      $job->sendComplete(getmypid());
    }

    public function actionPreProcessCategory(WorkerJob $job) {
      $result = $this->doImportWork($job, 'preProcessCategory');
      if($result) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionPreProcessProducts(WorkerJob $job) {
      if($this->doImportWork($job, 'preProcessProducts')) {
        $response = array('status' => 'redirect', 'url' => "/admin/store/import/assignCategories/{$this->id}.html", 'result'=>$this->importer->getProcessResults());
//        $response = array('status' => 'load', 'url' => "/admin/store/import/assign/{$this->id}?action=assign-categories");
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionPreProcessProducts2(WorkerJob $job) {
      if($this->doImportWork($job, 'preProcessProducts')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionPreProcessVendors(WorkerJob $job) {
      if($this->doImportWork($job, 'preProcessVendors')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionPreProcessParams(WorkerJob $job) {
      if($this->doImportWork($job, 'preProcessParams')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionPreProcessInit(WorkerJob $job) {
      if($this->doImportWork($job, 'preProcessInit')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionImportCheck(WorkerJob $job) {
      if($this->doImportWork($job, 'check')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionImportLoad(WorkerJob $job) {
      if($this->doImportWork($job, 'load')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }

    public function actionImportRunCategories(WorkerJob $job) {
      if($this->doImportWork($job, 'runCategories')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionImportRunVendors(WorkerJob $job) {
      if($this->doImportWork($job, 'runVendors')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionImportRunOptions(WorkerJob $job) {
      if($this->doImportWork($job, 'runOptions')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionImportRunImages(WorkerJob $job) {
      if($this->doImportWork($job, 'runImages')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionImportRunProducts(WorkerJob $job) {
      if($this->doImportWork($job, 'runProducts')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }
    public function actionImportRunProductsOptions(WorkerJob $job) {
      if($this->doImportWork($job, 'runProductsOptions')) {
        $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
      } else {
        $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      }
      $job->sendComplete($response);
    }

    public function actionImportRunCron(WorkerJob $job){
      if ($this->doImportWork($job, 'load')) {
        if ($this->doImportWork($job, 'preProcessInit')) {
          if ($this->doImportWork($job, 'preProcessCategory')) {
            if ($this->doImportWork($job, 'preProcessParams')) {
              if ($this->doImportWork($job, 'preProcessVendors')) {
                if ($this->doImportWork($job, 'preProcessProducts')) {
                  if ($this->doImportWork($job, 'runProducts')) {
                    $response = array('status' => 'ok', 'result'=>$this->importer->getProcessResults());
                  } else $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
                } else $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
              } else $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
            } else $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
          } else $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
        } else $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      } else $response = array('status' => 'error', 'errors' => $this->importer->getErrors());
      $job->sendComplete($response);
    }

    protected function doImportWork(WorkerJob $job, $func) {
      $this->id = intval($job->getWorkload());
      if (!$this->id) {$job->sendComplete('No ID given'); return;}

      $model = StoreImportSources::model()->findByPk($this->id);
      if (!$model) {
        $job->sendComplete(array('status' => 'error', 'errors' => array(Yii::t('StoreModule', 'Источник импорта не найден.'))));
        return false;
      }

      $this->importer = new Importer($model, $job);
      if (method_exists($this->importer, $func)) {
        return $this->importer->{$func}();
      } else {
        $this->importer->setError(Importer::ERROR_NOT_FOUND, Yii::t('Worker', 'Функция не определена: ').$func);
        return false;
      }
    }
  }