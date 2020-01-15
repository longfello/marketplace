<?php

  class loginbox extends CWidget {
    public $layout = 'full';

    function run(){
      //Yii::import('application.modules.UserAdmin.*');
      //Yii::import('application.modules.UserAdmin.models.forms.*');

      // if (! Yii::app()->user->isGuest)
        // $this->redirect(Yii::app()->homeUrl);

      //$model = new ULoginForm;

      Yii::import('application.modules.users.forms.UserLoginForm');
      $model = new UserLoginForm;

      if (Yii::app()->request->getIsPostRequest() && isset($_POST['ULoginForm']))
      {
        $model->attributes = $_POST['ULoginForm'];

        if ($model->validate())
        {
          /*
          $currentUserHomePage = User::getCurrentUserHomePage();

          // If user have role and this role have home page
          // then we redirect user there
          if ($currentUserHomePage) Yii::app()->controller->redirect($currentUserHomePage);
                               else Yii::app()->controller->redirect(Yii::app()->user->returnUrl);
          */
          // Authenticate user and redirect to the dashboard
          if($model->rememberMe)
            $duration = Yii::app()->user->rememberTime; // Remember for one week
          else
            $duration = 0;

          // TODO: Use backtop param
          //if(Yii::app()->user->returnUrl && Yii::app()->user->returnUrl!=='/index.php')
          //	$url=Yii::app()->user->returnUrl;
          //else
          $url='/';

          Yii::app()->user->login($model->getIdentity(), $duration);
          Yii::app()->request->redirect($url);
        }
      }


      $this->render('loginbox/'.$this->layout, array(
        'model' => $model,
        'isGuest' => Yii::app()->user->isGuest
      ));
    }
  }