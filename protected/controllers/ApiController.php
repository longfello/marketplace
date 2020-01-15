<?php

class ApiController extends Controller
{
  var $layout = '';

	public function actionIndex(){
    echo(':-)');
    die();
	}

  public function actionLoadRegions(){
    $id = Helper::getGet('cid', 0, type_int);
    $regions = NetRegions::model()->findAllByAttributes(array(
      'country_id' => $id
    ));

    $html = '';
    foreach($regions as $region) {
      $html .= "<option value=\"{$region->id}\">{$region->name}</option>";
    }
    echo($html);
  }

  public function actionLoadCities(){
    $region_id = Helper::getGet('rid', 0, type_int);
    $cities = NetCity::model()->findAllByAttributes(array(
      'region_id' => $region_id
    ));

    $html = '';
    foreach($cities as $city) {
      $html .= "<option value=\"{$city->id}\">{$city->name}</option>";
    }
    echo($html);
  }

  public function actionAcCity(){
    $search_for = Helper::GetPost('for', Helper::getGet('for', false));
    $condition = new CDbCriteria();
    $condition->distinct = true;
    $condition->addSearchCondition('t.name_ru', $search_for, true, 'OR');
    $condition->addSearchCondition('t.name_en', $search_for, true, 'OR');
    $condition->limit = 10;
    $cities = NetCity::model()->with('country')->findAll($condition);

    $res = array();
    foreach($cities as $city) {
      $res[] = array(
        'name'   => $city->name,
        'id'     => $city->id,
        'region' => $city->region->name,
        'country' => $city->country->name,
      );
    }

    echo CJSON::encode($res);
  }

  public function actionSetCity(){
    $id = Helper::getGet('id', 0, type_int);
    $res = Yii::app()->user->location->set($id);
    echo($res?"Ok":"Failed");
  }
}