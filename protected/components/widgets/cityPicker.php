<?php

  class cityPicker extends CWidget {
    public $layout = 'index';
    public $field = 'city_id';
    /* @var UserProfile */
    public $model;
    public $el_id;

    function run(){
      $this->el_id = get_class($this->model).'_'.$this->field;

      $this->render('cityPicker/'.$this->layout);
    }
  }