<?php

  class UserLocation {
    const COOKIE = 1;
    const SESSION = 2;
    const DATABASE = 3;
    const GEOIP = 10;

    const defaultCity = 50003;

    private $cookie  = 'uloc';
    private $session = 'UserLocation';

    /* @var NetCity */
    public $city;
    public $mode = self::SESSION;
    public $market_ids = array();

    public function __construct(){
      $location = Yii::app()->session->get($this->session, false);
      if (!$location) {
        $this->mode = self::COOKIE;
        $location = isset(Yii::app()->request->cookies[$this->cookie]) ? Yii::app()->request->cookies[$this->cookie]->value : false;
        if (!$location) {
          if (Yii::app()->user->isGuest) {
            $this->city = Helper::getCityIDByIp();
            $location = $this->city?$this->city->id:false;
            $this->mode = self::GEOIP;
          } else {
            $location = $location = Yii::app()->user->getModel()->profile->city_id;
            $this->mode = self::DATABASE;
            if (!$location) {
              $this->city = Helper::getCityIDByIp();
              $location = $this->city?$this->city->id:false;
              $this->mode = self::GEOIP;
            }
          }
          Yii::app()->request->cookies[$this->cookie] = new CHttpCookie($this->cookie, $location);
        }
        Yii::app()->session->add($this->session, $location);
      }

      if (!($this->city instanceof NetCity)) {
        $this->city = NetCity::model()->findByPk($location);
      }
      if (!$this->city) {
        $this->city = NetCity::model()->findByPk(self::defaultCity);
      }

      $cache_hash = 'user-location-market-ids-for-city-'.$this->city->id;
      $this->market_ids = Yii::app()->cache->get($cache_hash);
      if ($this->market_ids === false) {
        $this->market_ids = Yii::app()->db->commandBuilder->createSqlCommand("
SELECT DISTINCT sdm.market_id FROM StoreDeliveryMethod sdm
JOIN StoreDeliveryRegion sdr ON sdr.delivery_id = sdm.id
WHERE
sdm.active AND
( sdr.type = 'all' OR
 (sdr.type = 'country' AND sdr.object_id = {$this->city->country_id}) OR
 (sdr.type = 'region'  AND sdr.object_id = {$this->city->region_id}) OR
 (sdr.type = 'city'    AND sdr.object_id = {$this->city->id})
)
")->queryColumn(array('market_id'));
        $this->market_ids[] = 0;
        Yii::app()->cache->set($cache_hash, $this->market_ids, 300);
      }
    }

    function set($id_or_NetCity){
      $id = ($id_or_NetCity instanceof NetCity)?$id_or_NetCity->id:$id_or_NetCity;
      $city = NetCity::model()->findByPk($id);
      if ($city) {
        $this->city = $city;
        $this->save($id);
        if (!Yii::app()->user->isGuest) {
          Yii::app()->user->getModel()->profile->city_id = $id;
          Yii::app()->user->getModel()->profile->save();
        }
        return  true;
      }
      return false;
    }

    function clearCache(){
      Yii::app()->session->remove($this->session);
      unset(Yii::app()->request->cookies[$this->cookie]);
    }

    public function save($id){
      Yii::app()->session->add($this->session, $id);
      Yii::app()->request->cookies[$this->cookie] = new CHttpCookie($this->cookie, $id);
    }
  }