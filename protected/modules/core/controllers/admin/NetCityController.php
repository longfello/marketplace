<?php

class NetCityController extends SAdminController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new NetCity;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NetCity']))
		{
			$model->attributes=$_POST['NetCity'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NetCity']))
		{
			$model->attributes=$_POST['NetCity'];
			if($model->save())
        $this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
    $model = new NetCity('search');

    if (!empty($_GET['NetCity']))
      $model->attributes = $_GET['NetCity'];

		$this->render('index',array(
      'model' => $model
		));
	}

  public function actionRegions(){
    $params = Helper::getPost('NetCity');

    $cr = new CDbCriteria();
    $cr->addCondition("country_id = ".(int)$params['country_id']);
    $cr->order = "name_ru";

    $data=NetRegions::model()->findAll($cr);
    foreach($data as $item){
      echo CHtml::tag('option', array('value'=>$item->id),CHtml::encode($item->name),true);
    }
  }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new NetCity('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['NetCity']))
			$model->attributes=$_GET['NetCity'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return NetCity the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id = NULL, $scope = NULL, $with = NULL)
	{
		$model=NetCity::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param NetCity $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='net-city-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
