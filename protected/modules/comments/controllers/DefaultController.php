<?php

class DefaultController extends Controller {

  public $hasLeftColumn = 'comments';
  public $hasRightColumn = 'feedback';

  public $message = '';
  public $model;

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

	/**
	 * Display feedback form
	 */
	public function actionIndex()	{

    $comments = array(
      /*
      Comment::CLASS_SITE => array(
        'title' => Yii::t('StoreModule', 'Отзывы о сайте')
      ),
      */
      Comment::CLASS_MARKET => array(
        'title' => Yii::t('StoreModule', 'Отзывы о магазинах')
      )/*,
      Comment::CLASS_PRODUCT => array(
        'title' => Yii::t('StoreModule', 'Отзывы о товарах')
      ),*/
    );

    foreach ($comments as $key=>&$one)
    {
      $criteria = new CDbCriteria;
      $criteria->addCondition("status = 1");
      $criteria->addCondition("class_name = '".$key."'");

      $count = Comment::model()->count($criteria);

      $one['pages'] = new CPagination($count);
      $one['pages']->setPageSize(10);
      $one['pages']->applyLimit($criteria);

      $criteria->order = 'created DESC';
      $one['models'] = Comment::model()->findAll($criteria);
    }
    $this->model = new Comment();
    if(Yii::app()->request->isPostRequest){

      if(isset($_POST['Comment']))
        $this->model->attributes = $_POST['Comment'];

      if (!Yii::app()->user->isGuest) {
        $this->model->name = Yii::app()->user->name;
        $this->model->email = Yii::app()->user->getEmail();
        $this->model->user_id = Yii::app()->user->id;
      }

      if ($this->model->validate()) {
        $this->model->class_name = Comment::CLASS_SITE;
        if ($this->model->save()) {
          $this->message = Yii::t('Comment', 'Ваш отзыв добавлен и отправлен на модерацию');
        }
      }
    }

    //$dataProvider = new CArrayDataProvider($result);

    if (Yii::app()->request->isAjaxRequest) {
      $type = $_GET['type'];
      $this->renderPartial('index-ajax', array(
        'models' => $comments[$type]['models'],
        'pages' => $comments[$type]['pages'],
        'type' => $type,
        'message' => $this->message
      ));
    } else {
      $this->render('index', array(
        'comments' => $comments,
        'message' => $this->message
      ));
    }
	}

}
