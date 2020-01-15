<?php

class DefaultController extends SAdminController
{
  var $modelName = 'Banners';
  var $pageTitle = 'Управление баннерами';

  public function actionIndex() {
    $model = new Banners('search');
    if (Yii::app()->getRequest()->getQuery('Banners')) {
      $model->attributes = Yii::app()->getRequest()->getQuery('Banners');
      $this->renderPartial('_bannersListGrid', array('model' => $model));
    } else {
      $this->render('index', array('model' => $model));
    }
  }

  public function actionStat() {
  }

  public function actionDelete() {
    $id = Yii::app()->getRequest()->getQuery('id', NULL);
    $model = Banners::model()->findByPK($id);
    if ($model !== NULL) {
      $model->delete();
    }
    $this->redirect($this->createUrl("/admin/banners"));
  }

  public function actionUpdate() {
    $id = Yii::app()->getRequest()->getQuery('id', NULL);

    if ($id !== NULL) {
      $model = Banners::model()->findbyPk($id); // загружаем данные по модели
      if ($model === NULL) // если данные в модели нет - вызываем ошибку
      {
        throw new CHttpException(404, 'The requested page does not exist.');
      }
    } else {
      $model = new Banners;
    }

    //$model->webFolder=Yii::app()->params['bannersWebFolder'];

    if (isset($_POST['Banners'])) // если к нам пришел POST
    {
      $model->oldFile = $model->bnrFile;
      $model->attributes = $_POST['Banners']; //присваиваем данные из POST в модель
      if ($model->validate()) //валидируем данные
      {
        $bnrFile = CUploadedFile::getInstance($model, 'bnrFile'); //а вдруг нам загрузили картинку к категории?
        if (is_object($bnrFile) && get_class($bnrFile) === 'CUploadedFile') //да, картинку нам все таки загрузили
        {
          $model->bnrFile = $bnrFile; // присваиваем данные
        } else {
          //картинку нам не дали, восстанавливаем старую картинку
          $model->bnrFile = $model->oldFile;
        }
        if ($model->save()) //сохраняем модель
        {
          $this->redirect($this->createUrl("/admin/banners"));
        }
      }
    }

    $this->render('update', array(
      'model' => $model,
      'update' => TRUE
    ));
  }

  public function actionCopyBanner() {
    $id = Yii::app()->getRequest()->getQuery('id', NULL);
    $model = Banners::model()->findbyPk($id); // загружаем данные по модели
    if ($model !== FALSE) {
      $newBanner = new Banners();
      $newBanner->attributes = $model->attributes;
      unset($newBanner->id);
      $newBanner->bnrVisible = 0;
      $newBanner->save();
      $this->redirect($this->createUrl("/admin/banners/index"));
    }
  }
}