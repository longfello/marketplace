<?php

class DefaultController extends Controller {
  public $layout='//layouts/home';

  /**
   * @var CActiveRecord the currently loaded data model instance.
   */
  private $_model;

  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
      'accessControl', // perform access control for CRUD operations
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules()
  {
    return array(
      array('allow',  // allow all users to access 'index' and 'view' actions.
        'actions'=>array('index','view'),
        'users'=>array('*'),
      ),
      array('allow', // allow authenticated users to access all actions
        'users'=>array('@'),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  /**
   * Displays a particular model.
   */
  public function actionView($id)
  {
    $post=$this->loadModel($id);
    //		$comment=$this->newComment($post);
    $this->render('view',array('model'=>$post));
  }

  /**
   * Lists all models.
   */
  public function actionIndex(){
    $criteria=new CDbCriteria(array(
      'condition'=>'status='.News::STATUS_PUBLISHED,
      'order'=>'update_time DESC',
    ));

    $dataProvider=new CActiveDataProvider('News', array(
      'pagination'=>array(
        'pageSize'=>Yii::app()->settings->get('core','postsPerPage'),
      ),
      'criteria'=>$criteria,
    ));

    $this->render('index',array(
      'dataProvider'=>$dataProvider,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   */
  public function loadModel($id = false, $scope = NULL, $with = NULL)
  {
    if($this->_model===null)
    {
      if($id)
      {
        if(Yii::app()->user->isGuest)
          $condition='status='.News::STATUS_PUBLISHED.' OR status='.News::STATUS_ARCHIVED;
        else
          $condition='';
        $this->_model=News::model()->findByPk($id, $condition);
      }
      if($this->_model===null)
        throw new CHttpException(404,'The requested page does not exist.');
    }
    return $this->_model;
  }
}