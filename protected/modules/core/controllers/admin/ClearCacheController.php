<?php

class ClearCacheController extends SAdminController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function actionIndex()
	{
    Yii::app()->cache->flush();
    $this->setFlashMessage(Yii::t('CoreModule', 'Кеш очищен.'));
    $this->redirect(Yii::app()->request->getUrlReferrer());
	}
}
