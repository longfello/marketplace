<?php

/**
 * Realize user register
 */
class RegisterController extends Controller
{

	/**
	 * @return string
	 */
	public function allowedActions()
	{
		return 'register, registerManager';
	}

	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}

	/**
	 * Creates account for new users
	 */
	public function actionRegister()
	{
		if(!Yii::app()->user->isGuest)
			Yii::app()->request->redirect('/');

		$user = new User('register');
		$profile = new UserProfile;

		if(Yii::app()->request->isPostRequest && isset($_POST['User'], $_POST['UserProfile']))
		{
			$user->attributes = $_POST['User'];
			$profile->attributes = $_POST['UserProfile'];

			$valid = $user->validate();
			$valid = $profile->validate() && $valid;

			if($valid)
			{
				$user->save();
				$profile->save();
        $profile->setUser($user);

				// Add user to authenticated group
				Yii::app()->authManager->assign('Authenticated', $user->id);

				$this->addFlashMessage(Yii::t('UsersModule', 'Спасибо за регистрацию на нашем сайте.'));

        $siteName = Yii::app()->settings->get('core', 'siteName');
        $host     = $_SERVER['HTTP_HOST'];
        $theme = Yii::t('UsersModule', 'Регистрация на сайте {site_name}',array('{site_name}' => $siteName));
        $lang     = Yii::app()->language;


        // Authenticate user
        $identity = new UserIdentity($user->username, $_POST['User']['password']);
        if($identity->authenticate())
        {
          Yii::app()->user->login($identity, Yii::app()->user->rememberTime);

          $mailer           = Yii::app()->mail;
          $mailer->From     = 'robot@'.$host;
          $mailer->FromName = Yii::app()->settings->get('core', 'siteName');
          $mailer->Subject  = $theme;
          $mailer->Body     = $this->renderFile(Yii::getPathOfAlias("application.emails.$lang").'/registration.php',array('sitename' => $siteName, 'user' => $user, 'profile' => $profile),true);
          $mailer->AddAddress($user->email);
          $mailer->AddReplyTo(Yii::app()->params['adminEmail']);
          $mailer->isHtml(true);
          $mailer->Send();
          $mailer->ClearAddresses();

          Yii::app()->request->redirect($this->createUrl('/users/profile/index'));
        } else triggerError($identity->errorMessage);

			}
		}

		$this->render('register', array(
			'user'    => $user,
			'profile' => $profile
		));
	}

  public function actionRegisterManager() {
    if(!Yii::app()->user->isGuest) {
      $this->addFlashMessage(Yii::t('UsersModule','Нажмите на кнопку <b>Выйти</b> для регистрации новой учетной записи менеджера магазина.'));
      Yii::app()->request->redirect('/');
    }

    $user = new User('register');
    $profile = new UserProfile('register_manager');
    $market = new StoreMarket('register_manager');

    Yii::import('application.modules.staticpages.models.*');

    $page = StaticPage::model()->findByAttributes(array('slug' => 'manager_register'));
    $this->pageTitle = $page->page_title;
    $this->metaDescription = $page->meta_description;
    $this->metaKeywords = $page->meta_keywords;
    $this->metaTitle = $page->meta_title;
    $static = $page->content;

    //$page = new Page();
    //$manager_text = Page::model()->find("slug = 'manager_register'");


    if(Yii::app()->request->isPostRequest && isset($_POST['User'], $_POST['UserProfile'], $_POST['StoreMarket']))
    {
      $user->attributes = $_POST['User'];
      $profile->attributes = $_POST['UserProfile'];
      $market->attributes = $_POST['StoreMarket'];

      $market->is_active = 0;

      $valid = $user->validate();
      $valid = $market->validate() && $valid;
      $valid = $profile->validate() && $valid;

      if($valid)
      {
        $user->save();
        $profile->save();
        $profile->setUser($user);
        $market->user_id = $user->id;
        $market->save();

        // Add user to authenticated group
        Yii::app()->authManager->assign('RegisteredManager', $user->id);

        $this->addFlashMessage(Yii::t('UsersModule', 'Спасибо за регистрацию на нашем сайте.'));

        $siteName = Yii::app()->settings->get('core', 'siteName');
        $host     = $_SERVER['HTTP_HOST'];
        $theme = Yii::t('UsersModule', 'Регистрация на сайте {site_name}',array('{site_name}' => $siteName));
        $lang     = Yii::app()->language;

        $identity = new UserIdentity($user->username, $_POST['User']['password']);
        if($identity->authenticate())
        {
          Yii::app()->user->login($identity, Yii::app()->user->rememberTime);

          $mailer           = Yii::app()->mail;
          $mailer->From     = 'robot@'.$host;
          $mailer->FromName = Yii::app()->settings->get('core', 'siteName');
          $mailer->Subject  = $theme;
          $mailer->Body     = $this->renderFile(Yii::getPathOfAlias("application.emails.$lang").'/registration.php',array('sitename' => $siteName),true);
          $mailer->AddAddress($user->email);
          $mailer->AddReplyTo(Yii::app()->params['adminEmail']);
          $mailer->isHtml(true);
          $mailer->Send();
          $mailer->ClearAddresses();

          Yii::app()->request->redirect($this->createUrl('/users/profile/index'));
        } else triggerError($identity->errorMessage);
      }
    }

    $this->render('register_manager', array(
      'user'    => $user,
      'profile' => $profile,
      'market'  => $market,
      'text'    => $static
    ));
  }

}
