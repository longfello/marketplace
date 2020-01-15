<?php

Yii::import('application.modules.orders.models.Order');
Yii::import('application.modules.comments.models.Comment');

class AjaxController extends SAdminController
{
	public function actionGetCounters()
	{
    $importSourcesCount = StoreImportSources::model()->onApproving()->count();

    $cr = new CDbCriteria;
    $cr_comm = new CDbCriteria;
    if (Yii::app()->user->getIsManager()) {

      $cr->addInCondition(Order::model()->tableAlias.".market_id", Yii::app()->user->getUserMarketsIds());

      $cr_comm->join = "LEFT JOIN StoreProduct p ON p.id = ".Comment::model()->tableAlias.".object_pk";
      $cr_comm->addCondition(Comment::model()->tableAlias.".class_name='".Comment::CLASS_PRODUCT."' AND p.market_id IN (".implode(',',Yii::app()->user->getUserMarketsIds()).")");
      $cr_comm->addCondition(Comment::model()->tableAlias.".class_name='".Comment::CLASS_MARKET."' AND ".Comment::model()->tableAlias.".object_pk IN (".implode(',',Yii::app()->user->getUserMarketsIds()).")", "OR");

    }

    $criteria = new CDbCriteria();
    $criteria->addCondition('is_active = 0');
    $marketCount = (Yii::app()->user->getIsManager())?0:StoreMarket::model()->count($criteria);

		echo json_encode(array(
			'comments' => (int ) Comment::model()->waiting()->count($cr_comm),
			'orders'   => (int ) Order::model()->new()->count($cr),
			'catalog'  => (int) $importSourcesCount,
			'market'   => (int) $marketCount,
		));
	}
}