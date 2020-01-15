<?php

Yii::import('application.modules.comments.models.Comment');

class MarketController extends Controller
{
  public $hasLeftColumn = 'comments';
  public $hasRightColumn = 'feedback-market';

  /**
   * @var StoreManufacturer
   */
	public $model;
  /**
   * @var Comment
   */
	public $model_comment;

	/**
	 * @var array
	 */
	public $allowedPageLimit;

  public $message;

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
	 * Sets page limits
	 *
	 * @return bool
	 */
	public function beforeAction($action)
	{
		$this->allowedPageLimit=explode(',',Yii::app()->settings->get('core', 'productsPerPage'));
    $this->model_comment = new Comment();
    if(Yii::app()->request->isPostRequest){

      if(isset($_POST['Comment']))
        $this->model_comment->attributes = $_POST['Comment'];

      $this->model_comment->object_pk = Helper::getGet('id', 0, type_int);
      if (!Yii::app()->user->isGuest) {
        $this->model_comment->name = Yii::app()->user->name;
        $this->model_comment->email = Yii::app()->user->getEmail();
        $this->model_comment->user_id = Yii::app()->user->id;
      }
      $this->model_comment->class_name = Comment::CLASS_MARKET;

      if ($this->model_comment->validate()) {
        if ($this->model_comment->save()) {
          $this->message = Yii::t('Comment', 'Ваш отзыв добавлен и отправлен на модерацию');
        }
      }
    }
		return true;
	}

	/**
	 * Display products by manufacturer
	 *
	 * @param $url
	 * @throws CHttpException
	 */
	public function actionIndex(){
    $criteria = new CDbCriteria();
    $criteria->addCondition('is_active = 1');
    $query = new StoreMarket(null);
    $query->applyScopes($criteria);

		$provider = new CActiveDataProvider($query, array(
			'id'=>false,
			'pagination'=>array(
				'pageSize'=>$this->allowedPageLimit[0],
			)
		));

		$this->render('index', array(
			'provider'=>$provider,
		));
	}

	public function actionView($id)
	{
		$this->model = StoreMarket::model()->findByPk($id, 'is_active = 1');

		if (!$this->model)
			throw new CHttpException(404, Yii::t('StoreModule', 'Магазин не найден.'));

    $markers = StoreMarketMarkers::model()->findAll('market_id='.$id);

    $condition = new CDbCriteria();
    $condition->order = 'created DESC';
    $condition->addCondition("class_name = :class_name");
    $condition->addCondition("object_pk = :id");
    $condition->addCondition("status = 1");
    $condition->params = array(
      ':class_name' => Comment::CLASS_MARKET,
      ':id' => $id
    );

    $query = new Comment(null);
    $query->getDbCriteria()->mergeWith($condition);

    $provider = new CActiveDataProvider($query, array(
      'id'=>false,
      'pagination' => array(
        'pageSize' => 20,
      )
    ));

		$this->render('view', array(
      'provider' => $provider,
      'markers' => $markers
		));
	}
}
