<?php

/**
 * Usage:
 *     $this->widget('application.modules.feedback.widgets.feedback', array(
 *        'layout'=>'index'
 *    ));
 */
class Feedback extends CWidget
{
  public $layout  = 'feedback';
  public $message = '';

	/**
	 * Render attributes table
	 */
	public function run(){
    Yii::import('feedback.FeedbackModule');
    if ($this->layout == 'callback') {
      Yii::import('feedback.models.CallbackForm');
      $model = new CallbackForm;
    } else {
      Yii::import('feedback.models.FeedbackForm');
      $model = new FeedbackForm;
    }

    if(isset($_POST['FeedbackForm']))
      $model->attributes = $_POST['FeedbackForm'];

    if(Yii::app()->request->isPostRequest && $model->validate())
    {
      $model->sendMessage();
      $this->message = Yii::t('FeedbackModule', 'Ваше сообщение отправлено');
    }

    $this->render($this->layout, array(
      'model'=>$model
    ));
	}
}
