<?php

class DefaultController extends SAdminController{
  var $modelName = 'Gallery';
	public function actionIndex(){
    $gallery  = Gallery::model()->findByPk(1);
    $gallery2 = Gallery::model()->findByPk(2);

    $this->render('index',array(
      'gallery' =>$gallery,
      'gallery2'=>$gallery2,
    ));
	}

  /**
   * Removes image with ids specified in post request.
   * On success returns 'OK'
   */
  public function actionDelete()
  {
    $id = $_POST['id'];
    /** @var $photos GalleryPhoto[] */
    $photos = GalleryPhoto::model()->findAllByPk($id);
    foreach ($photos as $photo) {
      if ($photo !== null) $photo->delete();
      else throw new CHttpException(400, 'Photo, not found');
    }
    echo 'OK';
  }

  /**
   * Method to handle file upload thought XHR2
   * On success returns JSON object with image info.
   * @param $gallery_id string Gallery Id to upload images
   * @throws CHttpException
   */
  public function actionAjaxUpload($gallery_id = null)
  {
    $model = new GalleryPhoto();
    $model->gallery_id = $gallery_id;
    $imageFile = CUploadedFile::getInstanceByName('image');
    $model->file_name = $imageFile->getName();
    $model->save();

    $model->setImage($imageFile->getTempName());
    header("Content-Type: application/json");
    echo CJSON::encode(
      array(
        'id' => $model->id,
        'rank' => $model->rank,
        'name' => (string)$model->name_ru,
        'description' => (string)$model->description_ru,
        'preview' => $model->getPreview(),
      ));
  }

  /**
   * Saves images order according to request.
   * Variable $_POST['order'] - new arrange of image ids, to be saved
   * @throws CHttpException
   */
  public function actionOrder()
  {
    if (!isset($_POST['order'])) throw new CHttpException(400, 'No data, to save');
    $gp = $_POST['order'];
    $orders = array();
    $i = 0;
    foreach ($gp as $k => $v) {
      if (!$v) $gp[$k] = $k;
      $orders[] = $gp[$k];
      $i++;
    }
    sort($orders);
    $i = 0;
    $res = array();
    foreach ($gp as $k => $v) {
      /** @var $p GalleryPhoto */
      $p = GalleryPhoto::model()->findByPk($k);
      $p->rank = $orders[$i];
      $res[$k]=$orders[$i];
      $p->save(false);
      $i++;
    }

    echo CJSON::encode($res);

  }

  /**
   * Method to update images name/description via AJAX.
   * On success returns JSON array od objects with new image info.
   * @throws CHttpException
   */
  public function actionChangeData()
  {
    if (!isset($_POST['photo'])) throw new CHttpException(400, 'Nothing, to save');
    $data = $_POST['photo'];
    $criteria = new CDbCriteria();
    $criteria->index = 'id';
    $criteria->addInCondition('id', array_keys($data));
    /** @var $models GalleryPhoto[] */
    $models = GalleryPhoto::model()->findAll($criteria);
    foreach ($data as $id => $attributes) {
      $models[$id]->attributes=$attributes;

      if (isset($attributes['name_ru'])) $models[$id]->name_ru = $attributes['name_ru'];
      if (isset($attributes['name_en'])) $models[$id]->name_en = $attributes['name_en'];
      if (isset($attributes['description_ru'])) $models[$id]->description_ru = $attributes['description_ru'];
      if (isset($attributes['description_en'])) $models[$id]->description_en = $attributes['description_en'];
      if (isset($attributes['link_ru'])) $models[$id]->link_ru = $attributes['link_ru'];
      if (isset($attributes['link_en'])) $models[$id]->link_en = $attributes['link_en'];

      $models[$id]->save();
    }
    $resp = array();
    foreach ($models as $model) {
      $resp[] = array(
        'id' => $model->id,
        'rank' => $model->rank,
        'name' => (string)$model->name_ru,
        'description' => (string)$model->description_ru,
        'preview' => $model->getPreview(),
      );
    }
    echo CJSON::encode($resp);
  }

}