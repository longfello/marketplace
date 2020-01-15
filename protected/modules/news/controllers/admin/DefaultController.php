<?php

class DefaultController extends SAdminController
{
  var $modelName = 'News';
	public function actionIndex(){
    $model=new News('search');
    $params = Yii::app()->request->getParam('News');
    if($params) $model->attributes=$params;
    $this->render('index',array(
      'model'=>$model,
    ));
	}

  /**
   * Displays a particular model.
   */
  public function actionView($id)
  {
    $news=$this->loadModel($id);
    $this->render('view',array(
      'model'=>$news
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   */
  public function actionUpdate($id)
  {
    $model=$this->loadModel($id);
    if(isset($_POST['News']))
    {
      $model->attributes=$_POST['News'];
      if($model->save())
        $this->redirect(array('index','id'=>$model->id));
    }

    $this->render('update',array(
      'model'=>$model,
    ));
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $model=new News;
    if(isset($_POST['News']))
    {
      $model->attributes=$_POST['News'];
      if($model->save())
        $this->redirect(array('index','id'=>$model->id));
    }

    $this->render('create',array(
      'model'=>$model,
    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   */
  public function actionDelete($id)
  {
    if(Yii::app()->request->isPostRequest)
    {
      // we only allow deletion via POST request
      $this->loadModel($id)->delete();

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if(!isset($_GET['ajax']))
        $this->redirect(array('index'));
    }
    else
      throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
  }


}