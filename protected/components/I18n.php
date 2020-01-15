<?php

  class I18n extends CApplicationComponent
  {

    public $supportedLanguages;
    public $urlRulesPath;
    public $activeLanguage;
    public $active;
    public $default;
    public $languages = array();

    public function init(){
      if (true === (bool)preg_match("/^(?<protocol>(http|https):\/\/)(((?<languageCode>[a-z]{2})\.)*)((.*\.)*(?<domain>.+\.[a-z]+))$/", Yii::app()->request->hostInfo, $matches)){

        if (2 === strlen($matches['languageCode']) && false !== ($language = $this->isSupportedLanguage($matches['languageCode'])))
        {
          $this->activeLanguage = $language;
        }
        else{
          $this->activeLanguage = $this->guessClientLanguage();
          //$redirectUrl = "{$matches['protocol']}{$this->activeLanguage['code']}.{$matches['domain']}";
          // $redirectUrl = "{$matches['protocol']}{$this->activeLanguage['code']}.".Yii::app()->params['domain'];
          if (true !== isset($this->activeLanguage['fallback']) || false === $this->activeLanguage['fallback']) {
            if (!$this->detectBot(Yii::app()->request->getUserAgent())) {
              $subdomain   = $this->getSubdomain($this->activeLanguage['code']);
              $redirectUrl = "{$matches['protocol']}{$subdomain}".Yii::app()->params['domain'];
            }
          }
        }

      } else {
        throw new CException("Unable to parse host info - please request this page with a domain (eg http://example.com/) instead of an ip-address!");
      }
      $this->setActiveLanguage();
      Yii::app()->urlManager->rules = include("{$this->urlRulesPath}/{$this->activeLanguage['code']}.php");
      if (false !== Yii::app()->urlManager->cacheID && null !== ($cache = Yii::app()->getComponent(Yii::app()->urlManager->cacheID))){
        $keyPrefix = $cache->keyPrefix;
        $cache->keyPrefix = "{$keyPrefix}.{$this->activeLanguage['code']}";
        Yii::app()->urlManager->init();
        $cache->keyPrefix = $keyPrefix;
      } else {
        Yii::app()->urlManager->init();
      }
      if (true === isset($redirectUrl)) {Yii::app()->request->redirect($redirectUrl);}
    }

    public function setActiveLanguage(){
      $this->default = $this->active = $this->toObject($this->activeLanguage);
      Yii::app()->setLanguage($this->activeLanguage['code']);
      $this->languages = array();
      foreach($this->supportedLanguages as $one){
        $this->languages[] = $this->toObject($one);
      }
    }

    private function toObject($array) {
      $obj = new stdClass();
      foreach($array as $key => $value){
        $obj->{$key} = $value;
      }
      return $obj;
    }

    public function guessClientLanguage()
    {

      if (false === isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) || false === (bool)preg_match("/([a-z]{2}+)[-_][A-z]{2}+/", $_SERVER['HTTP_ACCEPT_LANGUAGE'], $match) || false === ($language = $this->isSupportedLanguage($match[1])))
      {
        if (false === ($language = $this->getFallbackLanguage()))
        {
          throw new CException("Unable to return the fallback language - please set it in config!");
        }
      }

      return $language;

    }

    public function getFallbackLanguage()
    {

      foreach ($this->supportedLanguages as $supportedLanguage)
      {
        if (true === isset($supportedLanguage['fallback']) && true === $supportedLanguage['fallback'])
        {
          return $supportedLanguage;
        }
      }

      return false;

    }

    public function isSupportedLanguage($languageCode){
      foreach ($this->supportedLanguages as $supportedLanguage){
        if ($supportedLanguage['code'] === $languageCode){
          return $supportedLanguage;
        }
      }
      return false;
    }

    public function getSubdomain($languageCode){
      foreach ($this->supportedLanguages as $supportedLanguage){
        if ($supportedLanguage['code'] === $languageCode){
          return $supportedLanguage['subdomain']?$supportedLanguage['subdomain'].".":'';
        }
      }
      return false;
    }

    /**
     * Get system languages
     * @return array
     */
    public function getLanguages()
    {
      return $this->supportedLanguages;
    }

    /**
     * Get language codes
     * @return array array('en','ru',...)
     */
    public function getCodes()
    {
      return array_keys($this->supportedLanguages);
    }

    /**
     * Get language by its id
     * @param integer $langId Language id
     * @return mixed SSystemLanguage if lang found. Null if not.
     */
    public function getById($langId)
    {
      foreach($this->languages as $lang)
      {
        if ($lang->id == $langId)
          return $lang;
      }
    }

    /* Detects some common web bots */
    public function detectBot($USER_AGENT) {
      $crawlers = array(
        'bloglines subscriber', 'dumbot','sosoimagespider','qihoobot','fast-webcrawler','superdownloads spiderman',
        'linkwalker','msnbot','aspseek','webalta crawler','lycos','feedfetcher-google','yahoo','youdaobot',
        'adsbot-google','googlebot','scooter','gigabot','charlotte','estyle','aciorobot','geonabot','msnbot-media',
        'baidu','cococrawler','google','charlotte t','yahoo! slurp china','sogou web spider','yodaobot','msrbot',
        'abachobot','sogou head spider','altavista','idbot','sosospider','yahoo! slurp','java vm','dotbot','litefinder',
        'yeti','rambler','scrubby','baiduspider','accoona', 'yandex');
      foreach($crawlers as $crawler) {
        if (strpos(strtolower($USER_AGENT), trim($crawler)) !== false) {
          return true;
        }
      }
      return false;
    }

    /**
     * Get language prefix to create url.
     * If current language is default prefix will be empty.
     * @return string Url prefix
     */
    public function getUrlPrefix()
    {
      return '';
/*
      if ($this->_active !== $this->_default)
        return $this->_active;
*/
    }

  }