<?php

/**
 * Profile, order and other user data.
 */
class ProfileController extends Controller
{
   public $hasLeftColumn = 'cabinet';
	/**
	 * Check if user is authenticated
	 * @return bool
	 * @throws CHttpException
	 */
	public function beforeAction($action)
	{
		if(Yii::app()->user->isGuest)
			throw new CHttpException(403, Yii::t('UsersModule', 'Ошибка доступа.'));
		return true;
	}

  public function actions()
  {
    return array(
      'captcha'=>array(
        'class'=>'CCaptchaAction',
      ),
    );
  }

	/**
	 * Display profile start page
	 */
	public function actionIndex()
	{
		$user=Yii::app()->user->getModel();
		$profile=$user->profile;

		$this->render('index', array(
			'user'=>$user,
			'profile'=>$profile
		));
	}

  public function actionedit() {
    $request=Yii::app()->request;

    $user=Yii::app()->user->getModel();
    $profile=$user->profile;

    if(Yii::app()->request->isPostRequest)
    {
      if($request->getPost('UserProfile') || $request->getPost('User')){
        $profile->attributes = $request->getPost('UserProfile');
        $user->email=isset($_POST['User']['email']) ? $_POST['User']['email'] : null;

        $valid=$profile->validate();
        $valid=$user->validate() && $valid;

        $photo = CUploadedFile::getInstance($profile,'photo');
        $profile->photo = $photo?$photo:$profile->photo;

        if($valid)
        {
          $user->save();
          $profile->save();

          $this->addFlashMessage(Yii::t('UsersModule', 'Изменения успешно сохранены.'));
          $this->redirect($this->createUrl('/users/profile'));
        }
      }
    }

    $this->render('edit', array(
      'user'=>$user,
      'profile'=>$profile
    ));
  }

  public function actioneditPassword() {
    Yii::import('application.modules.users.forms.ChangePasswordForm');
    $request=Yii::app()->request;

    $user=Yii::app()->user->getModel();
    $profile=$user->profile;

		$changePasswordForm=new ChangePasswordForm();
		$changePasswordForm->user=$user;

		if(Yii::app()->request->isPostRequest)
		{
			if($request->getPost('ChangePasswordForm'))
			{
				$changePasswordForm->attributes=$request->getPost('ChangePasswordForm');
				if($changePasswordForm->validate())
				{
					$user->password=User::encodePassword($changePasswordForm->new_password);
					$user->save(false);
					$this->addFlashMessage(Yii::t('UsersModule', 'Пароль успешно изменен.'));
          $this->redirect($this->createUrl('/users/profile'));
				}
			}
		}


    $this->render('editPassword', array(
      'user'=>$user,
      'profile'=>$profile,
			'changePasswordForm'=>$changePasswordForm
    ));
  }

  public function actionremind() {
    Yii::import('application.modules.users.forms.RemindPasswordForm');
    $model=new RemindPasswordForm;

    if(Yii::app()->request->isPostRequest)
    {
      $model->attributes=$_POST['RemindPasswordForm'];
      if($model->validate())
      {
        $model->sendRecoveryMessage();
        $this->addFlashMessage(Yii::t('UsersModule','На вашу почту отправлены инструкции по активации нового пароля.'));
        $this->redirect($this->createUrl('/users/profile'));
      }
    }

    $this->render('remind', array(
      'model'=>$model
    ));
  }

	/**
	 * Display user orders
	 */
	public function actionOrders()
	{
		Yii::import('application.modules.orders.models.*');
		Yii::import('application.modules.store.models.*');

		$orders=new Order('search');
		$orders->user_id=Yii::app()->user->getId();

		$this->render('orders', array(
			'orders'=>$orders,
		));
	}

  public function actionmanagercall()
  {
    Yii::import('feedback.models.FeedbackForm');
    Yii::import('feedback.FeedbackModule');
    $model = new FeedbackForm;

    if(isset($_POST['FeedbackForm']))
      $model->attributes = $_POST['FeedbackForm'];

    if(Yii::app()->request->isPostRequest && $model->validate())
    {
      $model->sendMessage();
      $this->message = Yii::t('FeedbackModule', 'Ваше сообщение отправлено');
    }

    $this->render('managercall', array(
      'model'=>$model
    ));
  }

  public function actionmessages() {

    $criteria = new CDbCriteria;
    $criteria->order = "last_msg DESC";
    $themes=TrackerThemes::model()->findAllByAttributes(array('user_id'=>Yii::app()->user->getId(), 'status'=>'new'), $criteria);

    $this->render('messages', array(
      'themes'=>$themes
    ));
  }

  public function actionviewmessage() {
    $id = Helper::getGet('id', 0, type_int);
    $request = Yii::app()->request;

    $theme = TrackerThemes::model()->findByPk($id);
    if (!$theme || $theme->user_id!=Yii::app()->user->getId()) throw new CHttpException(404, Yii::t('UsersModule', 'Ошибка доступа.'));

    $message = new TrackerMessages();
    if(Yii::app()->request->isPostRequest)
    {
      if($request->getPost('TrackerMessages'))
      {
        $message->attributes=$request->getPost('TrackerMessages');

        if($message->validate())
        {
          $message->theme_id = $id;
          $message->is_user = 1;
          if ($message->save()){
            $message = new TrackerMessages();
            $this->addFlashMessage(Yii::t('UsersModule', 'Сообщение успешно добавлено.'));
          } else {trigger_error(print_r($message->getErrors(), true)); }
        }
      }
    }

    $this->render('viewmessage',
      array(
        'theme'=>$theme,
        'message'=>$message
      ));
  }

  public function actionaddmessage() {
    $request = Yii::app()->request;

    $theme   = new TrackerThemes();
    $message = new TrackerMessages();

    if(Yii::app()->request->isPostRequest)
    {
      if($request->getPost('TrackerThemes') && $request->getPost('TrackerMessages'))
      {
        $theme->attributes=$request->getPost('TrackerThemes');
        $message->attributes=$request->getPost('TrackerMessages');
        $theme->user_id = Yii::app()->user->id;

        $valid=$theme->validate();
        $valid=$message->validate() && $valid;

        if($valid)
        {
          if ($theme->save()){
            $message->theme_id = $theme->id;
            $message->is_user = 1;
            if ($message->save()){
              $this->addFlashMessage(Yii::t('UsersModule', 'Сообщение успешно добавлено.'));
              $this->redirect($this->createUrl('/users/profile/messages'));
            } else {trigger_error(print_r($message->getErrors(), true)); }
          } else {trigger_error(print_r($theme->getErrors(), true)); }
        }
      }
    }
    $this->render('addmessage', array(
      'theme' => $theme,
      'message' => $message
    ));
  }

  public function actionwishlist() {

    Yii::import('store.components.SWishList');
    $model = new SWishList();

    $this->render('wishlist', array(
      'model' => $model
    ));
  }

  public function actionwishlistremove($id) {
    Yii::import('store.components.SWishList');
    $model = new SWishList();

    $model->remove($id);
    if(!Yii::app()->request->isAjaxRequest)
      $this->redirect($this->createUrl('/users/profile/wishlist'));
  }

  public function actionreviews() {
    Yii::import('comments.models.Comment');
    Yii::import('store.models.StoreProduct');
    Yii::import('store.models.StoreMarket');

    $criteria = new CDbCriteria;
    $criteria->addCondition("status = 1");
    $criteria->addCondition("user_id = ".Yii::app()->user->id);
    $criteria->order = "created DESC";

    $query = new Comment(null);
    $query->getDbCriteria()->mergeWith($criteria);

    $provider = new CActiveDataProvider($query, array(
      'id'=>false,
      'pagination' => array(
        'pageSize' => 20,
      )
    ));

    $this->render('reviews', array('provider'=>$provider));
  }
}
