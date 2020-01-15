<?php

class DefaultController extends Controller {
  public $hasLeftColumn  = 'xlite';
  public $hasRightColumn = 'xlite';

  /**
   * @return array
   */
  public function actions()
  {
    return array(
      'captcha'=>array(
        'class'=>'CCaptchaAction',
      ),
    );
  }

	public function actionView()
	{

		$page = $this->loadModel(Yii::app()->request->getQuery('id'));

		$this->render('view', array(
			'page' => $page,
		));
	}

  public function actionFeedback(){
    $this->actionView();
  }

	public function loadModel($id = NULL, $scope = NULL, $with = NULL)
	{
		$model = StaticPage::model()->published()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'Запрашиваемая страница не существует.');
		return $model;
	}

}