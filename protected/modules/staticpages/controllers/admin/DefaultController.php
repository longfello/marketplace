<?php

class DefaultController extends SAdminController{

	public function actionCreate()
	{
		$model = new StaticPage();

		$this->performAjaxValidation($model);

		if (isset($_POST['StaticPage']))
		{
			$model->attributes = $_POST['StaticPage'];

			if ($model->parent_id)
			{
				$parent = StaticPage::model()->findByPk($model->parent_id);
				if ($parent !== null)
					$model->appendTo($parent);
			}

			if ($model->saveNode())
			{
				Yii::app()->user->setFlash('success', "Страница «{$model->page_title}» успешно создана.");
				$this->redirect(array('update', 'id' => $model->id));
			}
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		$this->performAjaxValidation($model);

		if (isset($_POST['StaticPage']))
		{
			$model->attributes = $_POST['StaticPage'];

			if ((int) $model->parent_id !== (int) $model->_parent_id)
			{
				if (empty($model->parent_id))
					$result = $model->moveAsRoot();
				else
				{
					$parent = StaticPage::model()->findByPk($model->parent_id);
					if ($parent !== null)
						$result = $model->moveAsFirst($parent);
				}
			}

			if ($model->saveNode())
			{
				Yii::app()->user->setFlash('success', "Страница «{$model->page_title}» успешно изменена.");
				$this->redirect(array('index'));
			}
		}

		$this->render('update', array(
			'model' => $model,
		));
	}

	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest)
		{
			$this->loadModel($id)->deleteNode();

			if ( ! isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
	}

	public function actionIndex()
	{
		$model = new StaticPage('search');
		$model->unsetAttributes();
    $params = Yii::app()->request->getParam('StaticPage');
    if($params) $model->attributes=$params;

		$this->render('index', array(
			'model' => $model,
		));
	}

	public function actionToggle($id, $attribute)
	{
		if (Yii::app()->request->isPostRequest)
		{
			$model = $this->loadModel($id);
			$model->$attribute = ($model->$attribute == 0) ? 1 : 0;
			$model->saveNode(false);

			if ( ! isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
	}

	public function loadModel($id = NULL, $scope = NULL, $with = NULL)
  {
		$model = StaticPage::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'page-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
