<?php
class ManagerBehavior extends CActiveRecordBehavior
{
  public function beforeSave($event) {
    return $this->addmagazine();
  }

  public function beforeDelete($event) {
    return $this->filter();
  }

  public function beforeFind($event) {
    return $this->filter();
  }

  private function filter() {
    if (array_key_exists('market_id', $this->owner->attributes)) {
      if (Yii::app()->user->getIsManager()) {
        $this->owner->getDbCriteria()->addInCondition("market_id", Yii::app()->user->getUserMarketsIds());
      }
    }
    if ($this->owner->tableName() === 'StoreMarket') {
      if (Yii::app()->user->getIsManager()) {
        $this->owner->getDbCriteria()->addInCondition($this->owner->tableAlias.".id", Yii::app()->user->getUserMarketsIds());
      }
    }
    return $this->owner;
  }

  private function addmagazine() {
    if (array_key_exists('market_id', $this->owner->attributes) && $this->owner->tableName() !== 'Order') {
      if (Yii::app()->user->getIsManager()) {
        $markets = Yii::app()->user->getUserMarketsIds();
        if (!$this->owner->market_id || !in_array($this->owner->market_id, $markets)) {
          $this->owner->market_id = array_pop($markets);
        }
      } else {
        if (!$this->owner->market_id) {
          $this->owner->market_id = StoreMarket::ADMIN_MARKET;
        }
      }
    }
    if ($this->owner->tableName() === 'StoreMarket') {
      if (Yii::app()->user->getIsManager()) {
        $this->owner->user_id = Yii::app()->user->id;
        $this->owner->is_active = 0;
        $this->owner->status_comment = 'Магазин создан';
      }
    }
    return $this->owner;
  }

}