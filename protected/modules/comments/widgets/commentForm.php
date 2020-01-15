<?php

/**
 * Usage:
 *     $this->widget('application.modules.feedback.widgets.feedback', array(
 *        'layout'=>'index'
 *    ));
 */
class CommentForm extends CWidget
{
  public $layout  = 'comment';
  public $message = '';
  public $model;

	/**
	 * Render attributes table
	 */
	public function run(){
    Yii::import('comments.models.Comment');
    Yii::import('comments.CommentsModule');

    $this->render($this->layout, array(
      'model'=>$this->model
    ));

	}
}
