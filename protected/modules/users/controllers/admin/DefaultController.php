<?php
/**
 * User controller
 */
class DefaultController extends SAdminController
{ 

	/**
	 * Display users list
	 */
	public function actionIndex()
	{
		$model = new User('search');
		$model->unsetAttributes();

		if (!empty($_GET['User']))
			$model->attributes = $_GET['User'];

		$dataProvider = $model->search();
		$dataProvider->pagination->pageSize = Yii::app()->settings->get('core', 'productsPerPageAdmin');

		$this->render('list', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Create new user
	 */
	public function actionCreate()
	{
		$this->actionUpdate(true);
	}

	/**
	 * Create/update user
	 * @param boolean $new
	 */
	public function actionUpdate($new = false)
	{
		if($new === true)
		{
			$model = new User;
			$model->profile = new UserProfile;
		}
		else
			$model = User::model()->findByPk($_GET['id']);

		if (!$model)
			throw new CHttpException(400, 'Bad request.');

		$form = new SAdminForm('application.modules.users.views.admin.default.userForm', $model);

		$form['user']->model = $model;
		$form['profile']->model = $model->profile;

		if(Yii::app()->request->isPostRequest)
		{
			$model->attributes = $_POST['User'];
			$model->profile->attributes = $_POST['UserProfile'];

			$valid = $model->validate() && $model->profile->validate();

			if($valid)
			{
				$model->save();
				if(!$model->profile->user_id)
					$model->profile->user_id=$model->id;
				$model->profile->save();

				$this->setFlashMessage(Yii::t('UsersModule', 'Изменения успешно сохранены'));

				if (isset($_POST['REDIRECT']))
					$this->smartRedirect($model);
				else
					$this->redirect(array('index'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
			'form'=>$form,
		));
	}

	/**
	 * Delete user by Pk
	 */
	public function actionDelete()
	{
		if (Yii::app()->request->isPostRequest)
		{
			$ids    = Yii::app()->request->getParam("id");
			$models = User::model()->findAllByPk($ids);

			if(!empty($models))
			{
				foreach ($models as $user)
				{
					if ($user && ($user->id != Yii::app()->user->id))
						$user->delete();
				}
			}

			if (!Yii::app()->request->isAjaxRequest)
				$this->redirect('index');
		}
	}

  public function actionTracker() {
    $model= new TrackerThemes('search');
    $model->dbCriteria->order='last_msg DESC';
    $model->unsetAttributes();

    if (!empty($_GET['TrackerThemes']))
      $model->attributes = $_GET['TrackerThemes'];

    $dataProvider = $model->search();
    $dataProvider->pagination->pageSize = Yii::app()->settings->get('core', 'productsPerPageAdmin');

    $this->render('listtracker', array(
      'model'=>$model,
      'dataProvider'=>$dataProvider,
    ));
  }

  public function  actionTrackerview() {
    $id = Helper::getGet('id', 0, type_int);
    $request = Yii::app()->request;

    $theme = TrackerThemes::model()->findByPk($id);

    $message = new TrackerMessages();
    if(Yii::app()->request->isPostRequest)
    {
      if($request->getPost('TrackerThemes')) {
        $theme->attributes = $request->getPost('TrackerThemes');
        if($theme->validate())
        {
          if ($theme->save()){

          } else {trigger_error(print_r($theme->getErrors(), true)); }
        }
      }

      if($request->getPost('TrackerMessages'))
      {
        $message->attributes = $request->getPost('TrackerMessages');

        if($message->validate())
        {
          $message->theme_id = $id;
          if ($message->save()){
            $message = new TrackerMessages();
          } else {trigger_error(print_r($message->getErrors(), true)); }
        }
      }
    }

    $this->render('viewtracker',
      array(
        'theme'=>$theme,
        'message'=>$message
      ));
  }

}
