<?php

/**
 * Manage Markets
 */
class MarketController extends SAdminController
{
  /**
   * Display list of markets
   */
  public function actionIndex()
  {
    $model = new StoreMarket('search');
    $model->unsetAttributes();  // clear any default values
    $model->is_active = '';

    if (!empty($_GET['StoreMarket']))
      $model->attributes = $_GET['StoreMarket'];

    $dataProvider = $model->search();
    $dataProvider->pagination->pageSize = 20;

    $this->render('index', array(
      'model'=>$model,
      'dataProvider'=>$dataProvider,
    ));
  }

  /**
   * Create market
   */
  public function actionCreate()
  {
    $this->actionUpdate(true);
  }


  /**
   * Create/update market
   * @param bool $new
   * @throws CHttpException
   */
  public function actionUpdate($new = false)
  {
    Yii::import('application.modules.users.models.UserProfile');

    if ($new === true)
      $model = new StoreMarket();
    else
      $model = StoreMarket::model()->language($_GET)->findByPk($_GET['id']);

    if (!$model)
      throw new CHttpException(404, Yii::t('StoreModule.admin', 'Магазин не найден.'));

    if(isset($_GET['StoreMarket']))
      $model->attributes = $_GET['StoreMarket'];

    $form = new CForm('application.modules.store.views.admin.market.marketForm', $model);

    if (Yii::app()->request->isPostRequest)
    {
      $model->attributes = $_POST['StoreMarket'];

      if ($model->validate())
      {
        if (Yii::app()->user->getIsManager()) {
          $model->is_active='0';
          if ($new === true) {
            $model->status_comment='Магазин создан';
          } else {
            $model->status_comment='Магазин отредактирован';
          }
        }

        $model->save();

        StoreMarketMarkers::model()->deleteAll('market_id='.$model->id);

        if (isset($_POST['marker'])) {
          $markers = $_POST['marker'];
          foreach ($markers as $one) {
            $m_model = new StoreMarketMarkers();
            $m_model->attributes = $one;
            $m_model->market_id = $model->id;
            $m_model->save();
          }
        }

        $this->setFlashMessage(Yii::t('StoreModule.admin', 'Изменения успешно сохранены'));

        if (isset($_POST['REDIRECT']))
          $this->smartRedirect($model);
        else
          $this->redirect(array('index'));
      }
    }

    $markers = ($new !== true)?StoreMarketMarkers::model()->findAll('market_id='.$model->id):array();

    $this->render('update', array(
      'model'=>$model,
      'form'=>$form,
      'markers'=>$markers
    ));
  }

  /**
   * Mass market update is_active
   */
  public function actionUpdateIsActive()
  {
    $ids       = Yii::app()->request->getPost('ids');
    $status    = (int)Yii::app()->request->getPost('status');
    $models    = StoreMarket::model()->findAllByPk($ids);
    foreach($models as $market)
    {
      if(in_array($status, array(0,1)))
      {
        $market->is_active=$status;
        $market->save();
      }
    }
    echo Yii::t('StoreModule.admin', 'Изменения успешно сохранены.');
  }

  /**
   * Delete markets
   * @param array $id
   */
  public function actionDelete($id = array())
  {
    if (Yii::app()->request->isPostRequest)
    {
      $model = StoreMarket::model()->findAllByPk($_REQUEST['id']);

      if (!empty($model))
      {
        foreach($model as $page)
          $page->delete();
      }

      if (!Yii::app()->request->isAjaxRequest)
        $this->redirect('index');
    }
  }
}
