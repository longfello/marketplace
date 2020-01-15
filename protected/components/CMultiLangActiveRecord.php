<?php

  class CMultiLangActiveRecord extends CActiveRecord {
    public $MultiLangFields = array();

    public function __get($name) {
      if (in_array($name, $this->MultiLangFields))
        $name = $this->getMultiLangFieldName($name);
      return parent::__get($name);
    }

    public function __set($name, $value) {
      if (in_array($name, $this->MultiLangFields))
        $name = $this->getMultiLangFieldName($name);
      return parent::__set($name, $value);
    }

    private function getMultiLangFieldName($name) {
      return $name.'_'.Yii::app()->getLanguage();
    }
  }