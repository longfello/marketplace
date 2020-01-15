<?php

  class importer_yml extends transport_prototype {
    private $cacheTTL = 3600;
//    private $cacheTTL = 0;

    public function load(){
      if (!file_exists($this->_filename) || ((filectime($this->_filename)+$this->cacheTTL) < time())) {
        $this->sendStatus(1,3);
        $content = file_get_contents($this->_source->sourceURL);
        if ($content) {
          $this->sendStatus(2,3);
          $config = array(
            'input-encoding' => 3,
            'output-encoding' => 3,
            'language' => 'ru',
            'indent'     => false,
            'input-xml'  => true,
            'output-xml' => true,
            'wrap'       => false,
          );
          // Tidy
          $tidy = new tidy;
          $tidy->parseString($content, $config);
          $tidy->cleanRepair();
          $this->sendStatus(3,3);
          file_put_contents($this->_filename, ''.$tidy);
        } else {
          $this->setError(Importer::ERROR_NOT_FOUND, Yii::t('main','Ошибка загрузки файла'), array(array('filename' => $this->_source->sourceURL)));
        }
      } else {
        $this->sendStatus(1,1);
        $this->setMessages(Yii::t('main','Загружено из кеша'));
      }
      return $this->_result;
    }
    public function check(){
      libxml_use_internal_errors(true);
      libxml_clear_errors();
      if ($content = file_get_contents($this->_filename)) {
        $this->sendStatus(1,9);
        if ($this->document = simplexml_load_string($content)) {
          $this->sendStatus(2,9);
          // Получение валют
          $this->currency_info = $this->loadCurrency();
          // Проверка наличия валют
          if (count($this->currencies) == 0) {
            $this->setError(Importer::ERROR_TRANSPORT, Yii::t('main','Отсутствует информация о валютах!'));
          } else {
            if (!$this->default_currency) {
              $this->setMessages(Importer::ERROR_TRANSPORT, Yii::t('main','Отсутствует информация об основной валюте магазина!'));
            }
          }
          $this->sendStatus(3,9);

          // Проверка наличия валюты магазина
          $present = false; $isos = array();
          foreach($this->market_currencies as $currency) {
            $isos[] = $currency->iso;
            if (isset($this->currencies[$currency->iso])) {
              $present = true;
              break;
            }
          }
          if (!$present) {
            $this->setError(Importer::ERROR_TRANSPORT, Yii::t('main','В источнике отсустствует информация о валюте, существующей в магазине:').' '.implode(',', $isos));
          }
          $this->sendStatus(4,9);


          // Загрузка дерева категорий
          $this->loadCategories();
          // Проверка категорий
          $this->setMessages(Yii::t('main','Найдено категорий:').' '.count($this->categories));
          $this->sendStatus(5,9);

          // Загрузка дерева товаров
          $this->loadProducts();
          // Проверка категорий
          $this->setMessages(Yii::t('main','Найдено товаров:').' '.$this->productsCount);
          $this->sendStatus(6,9);

          // Загрузка параметров
          $this->loadParams();
          // Проверка параметров
          $this->setMessages(Yii::t('main','Найдено характеристик:').' '.count($this->params), $this->params);
          $this->sendStatus(7,9);

          // Загрузка производителей
          $this->loadVendors();
          // Проверка параметров
          $this->setMessages(Yii::t('main','Найдено производителей:').' '.count($this->vendors), $this->vendors);
          $this->sendStatus(8,9);

        } else $this->setError(Importer::ERROR_TRANSPORT, Yii::t('main','Не корректный YML-файл'), libxml_get_errors());
      } else $this->setError(Importer::ERROR_TRANSPORT, Yii::t('main','Невозможно загрузить файл'));

      $this->setMessages('');
      $this->setMessages(Yii::t('main','Ошибок:').' '.count($this->_errors));

      if (!$this->_errors) {
        $this->setMessages('<br><a href="'.Yii::app()->createUrl('/admin/store/import/assign/'.$this->_source->id).'" class="btn btn-primary">'.Yii::t('main','Перейти к сопоставлению данных').'</a>');
      }
      $this->sendStatus(9,9);

      return $this->_result;
    }

    protected function loadProducts(){
      foreach ($this->document->shop->offers->offer as $product) {
        $categoryId = intval($product->categoryId);
        /* @var SimpleXMLElement $product */
        if ($categoryId) {
          $params = array();
          foreach ($product->param as $param){
            $name  = trim($param['name']);
            $unit  = trim($param['unit']);
            $value = trim($param);
            $value .= $unit?$unit:'';
            if(!isset($params[$name])) {
              $params[$name] = array();
            }
            $params[$name][] = $value;
          }

          $this->categories[$categoryId]['products'][] = array(
            'id'          => intval($product['id']),
            'name'        => trim($product->name),
            'price'       => floatval($product->price),
            'cid'         => intval($product->categoryId),
            'picture'     => trim($product->picture),
            'iso'         => trim($product->currencyId),
            'description' => trim($product->description),
            'available'   => (bool)$product['available']?1:0,
            'article'     => trim($product->vendorCode),
            'vendor'      => trim($product->vendor),
            'options'     => $params,
          );
          $this->productsCount++;
        }
      }
    }
    protected function loadCategories(){
      foreach ($this->document->shop->categories->category as $category) {
        $this->categories[intval($category['id'])] = array(
          'parent'   => intval($category['parentId'])?intval($category['parentId']):0,
          'name'     => trim($category),
          'products' => array()
        );
      }
    }
    protected function loadParams(){
      foreach ($this->document->shop->offers->offer as $product) {
        //тут проверка на то, нет ли уже такого параметра, и, в случае отсуствия, добавление его в конец. С подтипом и прочим.
        foreach ($product->param as $param){

          $name  = trim($param['name']);
          $unit  = trim($param['unit']);
          $value = trim($param);
          $value .= $unit?$unit:'';
          if(!isset($this->params[$name])) {
            $this->params[$name] = array();
          }
          $this->params[$name][] = $value;
        }
      }
    }
    protected function loadVendors(){
      foreach ($this->document->shop->offers->offer as $offer) {
        $vendor = trim($offer->vendor);
        if ($vendor && !in_array($vendor, $this->vendors)) {
          $this->vendors[] = $vendor;
        }
      }
    }
    protected function loadCurrency(){

      foreach($this->document->shop->currencies->currency as $currency) {
        $currency['id'] = mb_strtoupper($currency['id']);
        if (floatval($currency['rate']) > 0) {
          $this->currencies[trim($currency['id'])] = floatval($currency['rate']);
          if (floatval($currency['rate']) == 1) {
            $this->default_currency = trim($currency['id']);
          }
        } elseif (trim($currency['rate']) == self::CBRF) {
          $this->currencies[trim($currency['id'])] = self::CBRF;
        }
      }

      foreach($this->currencies as $iso => $rate) {
        if ($rate == self::CBRF) {
          $this->currencies[$iso] = Yii::app()->cbrf->getRate($iso);
        }
      }

      $this->setMessages(Yii::t('main','Количество валют магазина:').' '.count($this->system_currencies).', '.Yii::t('main','основная валюта:').' '.$this->system_default_currency);
      foreach($this->system_currencies as $iso => $rate) {
        $this->setMessages('&nbsp;&nbsp;'.$iso.': '.$rate);
      }

      $this->setMessages(Yii::t('main','Количество валют в источнике:').' '.count($this->currencies).', '.Yii::t('main','основная валюта:').' '.$this->default_currency);
      foreach($this->currencies as $iso => $rate) {
        $this->setMessages('&nbsp;&nbsp;'.$iso.': '.$rate);
      }

      $currencies_info = array();
      // Существующие валюты
      foreach($this->_source->currencies as $currency){
        /* @var StoreImportDataCurrencies $currency */
        $currencies_info[$currency->iso] = array(
          'rate'   => $currency->rate,
          'status' => StoreImportDataCurrencies::STATUS_NONE
        );
      }

      // Сохранение / обновление валют
      foreach($this->currencies as $iso => $rate) {
        if (isset($currencies_info[$iso])) {
          $currencies_info[$iso]['rate'] = $rate;
          $currencies_info[$iso]['status'] = StoreImportDataCurrencies::STATUS_OLD;
        } else {
          $currencies_info[$iso] = array(
            'rate'   => $rate,
            'status' => StoreImportDataCurrencies::STATUS_NEW
          );
        }
      }

      $this->base_currency = false;
      if (isset($this->system_currencies[$this->default_currency])) {
        $this->base_currency = $this->default_currency;
      } else {
        foreach($currencies_info as $iso => $one) {
          if (isset($this->system_currencies[$iso])) {
            $this->base_currency = $iso;
            break;
          }
        }
        $this->setMessages(Yii::t('main','Валюта, выбранная базовой для конвертации:').' '.$this->base_currency);
      }

      if ($this->base_currency) {

        foreach($this->currencies as $iso => $rate) {
          $currencies_info[$iso]['rate'] = $this->convertCurrency($iso);
        }
      } else {
        $this->setError(Importer::ERROR_TRANSPORT, Yii::t('main','Не обнаружено соответствие валют.'));
      }

      $this->setMessages(Yii::t('main','Приведенные курсы валют: '));
      foreach($currencies_info as $iso => $info) {
        $this->setMessages('&nbsp;&nbsp;'.$iso.': '.$info['rate']);
      }

      return $currencies_info;

    }

  }