<?php

  // require_once dirname(__FILE__).'/../vendor/Rediska/library/Rediska.php';

  class RediskaConnection
  {
    public $options = array();

    /**
     * @var Redis
     */
    private $_redis;


    public function init()
    {
      $this->_redis = new Redis();
      $this->_redis->connect($this->options['host'],$this->options['port']);
    }


    public function getConnection()
    {
      return $this->_redis;
    }
  }