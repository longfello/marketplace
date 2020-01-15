<?php
  Yii::import('application.modules.csv.components.CsvImage');
  Yii::import('application.modules.csv.components.CsvAttributesProcessor');
  Yii::import('application.modules.store.StoreModule');
  Yii::import('application.modules.store.models.*');
  Yii::import('application.modules.store.components.*');
  Yii::import('application.modules.staticpages.components.*');
  Yii::import('application.components.validators.*');

  abstract class transport_prototype {
    const CBRF = 'CBRF';

    protected $_errors = array();
    protected $_messages = array();
    protected $_filename;
    protected $_dir;
    protected $_result = true;
    protected $isSilentMode = false;
    protected $percent = 0;

    /**
     * @var WorkerJob
     */
    private $jobWorker;

    // Валюты и курсы валют магазина
    public $system_currencies = array();
    // Основная валюта магазина
    public $system_default_currency = false;

    // Валюты и курсы валют источника
    public $currencies = array();
    // Основная валюта источника
    public $default_currency = 'Не определена';

    // Валюта, выбранная базовой для конвертации курсов валют источника к основной валюте магазина
    public $base_currency = false;

    // Приведенные курсы валют
    public $currency_info = array();

    public $params = array();
    public $vendors = array();
    public $categories = array();

    public $productsCount = 0;

    public $market_currencies;
    public $market_default_currency;

    public $document;

    public function __construct(StoreImportSources $source, $jobWorker) {
      $this->_source   = $source;
      $this->jobWorker = $jobWorker;
      $this->getFilename();
      $this->getMarketDefaultCurrency();
      $this->market_currencies = StoreCurrency::model()->findAll();

      // получение системных валют
      $currencies = StoreCurrency::model()->findAll();
      foreach($currencies as $currency) {
        $this->system_currencies[$currency->iso] = $currency->rate;
      }
      $this->system_default_currency = StoreCurrency::model()->findByAttributes(array('default' => 1))->iso;
    }

    private function getMarketDefaultCurrency(){
      $currency = StoreCurrency::model()->findByAttributes(array('default'=>1));
      $this->market_default_currency = $currency->iso;
    }

    public function getError(){
      return $this->_errors;
    }

    abstract function load();
    abstract function check();

    protected function sendStatus($numerator, $denominator, $force = false){
      if ($this->jobWorker && (!$this->isSilentMode || $force)) {
        $save = true;
        if ($denominator) {
          $p = round(100*$numerator/$denominator);
          $save = ($p != $this->percent) || ($numerator == $denominator);
          $this->percent = $p;
        }
        if ($save) {
          echo($this->percent."% complete ($numerator/$denominator)\n");
          $this->jobWorker->sendStatus($numerator, $denominator);
        }
      }
    }

    protected function sendData($data){
      if ($this->jobWorker) {
        echo($data."\n");
        $this->jobWorker->sendData($data);
      }
    }

    protected function setError($code, $message, $additionalInfo=array()){
      $this->_result = false;
      $this->_errors[] = array('code' => $code, 'message' => $message, 'additionalInfo' => $additionalInfo);
    }

    public function getMessages(){
      return $this->_messages;
    }

    public function setSilent($isSilentMode = true) {
      $this->isSilentMode = $isSilentMode;
    }

    protected function setMessages($message, $additionalInfo=array()){
      if (!$this->isSilentMode) {
        $this->_messages[] = array('message' => $message, 'additionalInfo' => $additionalInfo);
      }
    }

    /**
     *
     */
    protected function getFilename(){
      $this->_dir = Yii::getPathOfAlias('webroot.uploads.import');
      $this->_dir .= DIRECTORY_SEPARATOR.$this->_source->market_id.DIRECTORY_SEPARATOR;
      if (!is_dir($this->_dir)) {
        mkdir($this->_dir, 0777, true);
      }
      $this->_filename = $this->_dir.Helper::translit($this->_source->sourceURL).'.xml';
    }

    public function preprocessInit(){
      $this->setSilent();
      $this->load();
      $this->sendStatus(1,3,true);
      $this->check();
      $this->sendStatus(2,3,true);
      $this->setSilent(false);
      if (!$this->_errors){
        $this->update_currency();
      }
      $this->sendStatus(3,3);
      return $this->_result;
    }
    public function preprocessCategory(){
      $this->setSilent();
      $this->load();
      $this->sendStatus(1,3,true);
      $this->check();
      $this->sendStatus(2,3,true);
      $this->setSilent(false);
      if (!$this->_errors){
        $this->update_categories();
      }
      $this->sendStatus(3,3,true);
      return $this->_result;
    }
    public function preprocessParams(){
      $this->setSilent();
      $this->load();
      $this->sendStatus(1,3,true);
      $this->check();
      $this->sendStatus(2,3,true);
      $this->setSilent(false);
      if (!$this->_errors){
        $this->update_params();
      }
      $this->sendStatus(3,3,true);
      return $this->_result;
    }
    public function preprocessVendors(){
      $this->setSilent();
      $this->load();
      $this->sendStatus(1,3,true);
      $this->check();
      $this->sendStatus(2,3,true);
      $this->setSilent(false);
      if (!$this->_errors){
        $this->update_vendors();
      }
      $this->sendStatus(3,3,true);
      return $this->_result;
    }
    public function preprocessProducts(){
      $this->setSilent();
      $this->load();
      $this->check();
      $this->setSilent(false);
      if (!$this->_errors){
        $this->update_products();
      }
      return $this->_result;
    }
    public function runProducts(){
      $condition = new CDbCriteria();
      $condition->addCondition('sid = '.$this->_source->id);
      $products = StoreImportDataProduct::model()->findAll($condition);
      $count = 0; $overall = count($products);
      foreach($products as $product) {
        $count++;
        $newProduct = false;
        /* @var $product StoreImportDataProduct */
        $model = StoreProduct::model()->findByAttributes(array(
          'source_id' => $this->_source->id,
          'source_pk' => $product->id
        ));
        if (!$model) {
          $newProduct = true;
          $model = new StoreProduct();
          $model->source_id = $this->_source->id;
          $model->source_pk = $product->id;
        }
        $model->name = $product->name;
        $model->price = $product->price;
        $model->full_description = $product->description;
        $model->sku = $product->article;
        $model->is_active = 1;
        $model->availability = ($product->available && (floatval($product->price)>0.01))?1:0;
        $model->type_id = StoreProductType::IMPORT_TYPE_ID;
        $model->market_id = $this->_source->market_id;

        // Manufacturer
        if ($product->vendor) {
          $model_man = StoreImportDataVendors::model()->withSolutions()->findByAttributes(array(
            'sid'  => $this->_source->id,
            'name' => $product->vendor
          ));
          /* @var $model_man StoreImportDataVendors */
          if ($model_man && $model_man->solution && $model_man->solution->isNew == 0) {
            switch ($model_man->solution->solution) {
              case StoreImportAssignVendors::SOLUTION_ASSIGN:
                $model->manufacturer_id = $model_man->solution->spid;
                break;
            }
          }
        }

        $model_category = StoreImportAssignCategory::model()->findByAttributes(array(
          'sid' => $product->sid,
          'cid' => $product->cid,
          'isNew' => 0
        ));
        if (!$model_category || !in_array($model_category->solution, array(StoreImportAssignCategory::SOLUTION_ASSIGN))) {
          continue;
        }
        $category_id = $model_category->spid;

        if($model->validate()) {
          $categories=array($category_id);
          if(!$newProduct) {
            foreach($model->categorization as $c) {
              if ($c->category > 1) $categories[]=$c->category;
            }
            $categories=array_unique($categories);
          }

          $this->processAttributes($model);

          // Update categories
          if ($category_id > 1) {
            $model->setCategories($categories, $category_id);
          }

          if($product->picture){
            $image=CsvImage::create($product->picture);
            if($image && $model->mainImage===null) {
              $model->addImage($image);
            } /*
            else {
              $image->getError();
            } */
          }

          $model->save();

        } else StoreImportErrors::add($this->_source->id, 'Product add error:', array(
            Yii::t('main','Ошибка') => $model->getErrors(),
            Yii::t('main','Данные') => Helper::toArray($model)
          ));

        $this->sendStatus($count, $overall);
      }
      return $this->_result;
    }
    public function runProductsOptions(){
      return $this->_result;
    }

    public function processAttributes(StoreProduct $product){
      $pid = $product->source_pk;
      $sid = $product->source_id;
      $models = StoreImportDataProductOptions::model()->findAllByAttributes(array(
        'sid' => $sid,
        'pid' => $pid
      ));
      foreach($models as $model) {
        /* @var $model StoreImportDataProductOptions */
        $attrOption = $this->findAttribute($model);
        if (!$attrOption) continue;
        $model->moid = $attrOption->spid;
        $model->save();

        $attr = StoreAttribute::model()->findByPk($attrOption->oid);
        /* @var $attr StoreAttribute */
        switch($attr->type){
          case StoreAttribute::TYPE_DROPDOWN:
            $data = array($attr->name => $attrOption->spid);
            break;
          case StoreAttribute::TYPE_YESNO:
            $data = array($attr->name => $attrOption->spid);
            break;
          case StoreAttribute::TYPE_TEXT:
            $data = array($attr->name => $model->attr);
            break;
          default:
            $data = null;
        }
        $product->setEavAttributes($data, true);
        $product->save();
      }
    }

    /**
     * @param StoreImportDataProductOptions $productOption
     * @return bool|StoreImportAssignProductOptions
     */
    public function findAttribute(StoreImportDataProductOptions $productOption){
      $assocAttribute  = StoreImportAssignOptions::model()->findByAttributes(array(
        'sid' => $productOption->sid,
        'cid' => $productOption->oid,
      ));

      /* @var $assocAttribute StoreImportAssignOptions */
      if ($assocAttribute) {
        if ($assocAttribute->solution == StoreImportAssignOptions::SOLUTION_ASSIGN) {
          $assocOption = StoreImportAssignProductOptions::model()->findByAttributes(array(
            'oid'   => $assocAttribute->spid,
            'value' => $productOption->attr
          ));
          /* @var $assocOption StoreImportAssignProductOptions */
          if ($assocOption) {
            return $assocOption;
          }
        }
      }
      return false;
    }

    public function update_vendors(){
      $ids = array(0);
      foreach($this->vendors as $name) {
        $model = StoreImportDataVendors::model()->findByAttributes(array(
          'sid' => $this->_source->id,
          'name' => $name
        ));
        if (!$model) {
          $this->setMessages(Yii::t('main','Новый производитель').' '.$name);
          $model = new StoreImportDataVendors();
          $model->sid = $this->_source->id;
          $model->name = $name;
        }
        if (!$model->save()) {
          $this->setError(Importer::ERROR_TRANSPORT, 'Error executing query', $model->getErrors());
        }
        $ids[] = $model->id;
      }

      StoreImportDataVendors::model()->deleteAll("sid = :sid AND id NOT IN (".implode(',',$ids).")", array(':sid'=>$this->_source->id));
      StoreImportAssignVendors::model()->deleteAll("sid = :sid AND cid NOT IN (".implode(',',$ids).")", array(':sid'=>$this->_source->id));
    }
    public function update_params(){
      $ids = array(0);
      foreach($this->params as $name => $list) {
        $model = StoreImportDataOptions::model()->findByAttributes(array(
          'sid' => $this->_source->id,
          'name' => $name
        ));
        if (!$model) {
          $this->setMessages(Yii::t('main','Новый атрибут').') '.$name);
          $model = new StoreImportDataOptions();
          $model->sid = $this->_source->id;
          $model->name = $name;
        }
        $model->list = serialize($list);

        if (!$model->save()) {
          $this->setError(Importer::ERROR_TRANSPORT, 'Error executing query', $model->getErrors());
        }
        $ids[] = $model->id;
      }

      StoreImportDataOptions::model()->deleteAll("sid = :sid AND id NOT IN (".implode(',',$ids).")", array(':sid'=>$this->_source->id));
      StoreImportAssignOptions::model()->deleteAll("sid = :sid AND cid NOT IN (".implode(',',$ids).")", array(':sid'=>$this->_source->id));
    }
    public function update_products(){
      StoreImportDataProduct::model()->deleteAllByAttributes(array('sid' => $this->_source->id));
      StoreImportDataProductOptions::model()->deleteAllByAttributes(array('sid' => $this->_source->id));
      $count = count($this->categories);
      $i = 0;
      foreach($this->categories as $info) {
        foreach($info['products'] as $product) {
          $model = StoreImportDataProduct::model()->findByAttributes(array(
            'sid' => $this->_source->id,
            'id' => $product['id']
          ));
          if (!$model) {
            $model = new StoreImportDataProduct();
            $model->sid = $this->_source->id;
            $model->id = $product['id'];
          }
          $model->attributes = $product;
          $model->price = $this->convertCurrency($product['iso']) * $model->price;

          if ($model->save()){
            // process Attributes
            foreach($product['options'] as $option_name => $options){
              $optionData = StoreImportDataOptions::model()->findByAttributes(array(
                'sid' => $this->_source->id,
                'name' => $option_name,
              ));
              if ($optionData) {
                foreach($options as $option) {
                  $modelOption = new StoreImportDataProductOptions();
                  $modelOption->pid   = $model->id;
                  $modelOption->sid   = $this->_source->id;
                  $modelOption->oid   = $optionData->id;
                  $modelOption->attr  = $option;
                  if (!$modelOption->save()) {
                    trigger_error(print_r($modelOption->getErrors(), true));
                  }
                }
              }
            }
          }
        }
        $i++;
        $this->sendStatus($i,$count,true);
      }
    }
    public function update_categories(){
      StoreImportDataCategories::model()->deleteAllByAttributes(array('sid' => $this->_source->id));
      foreach($this->categories as $cid => $info) {
        $model = new StoreImportDataCategories();
        $model->sid   = $this->_source->id;
        $model->id    = $cid;
        $model->pid   = $info['parent'];
        $model->name  = $info['name'];
        $model->level = $this->getCategoryLevel($cid);
        $model->save();
      }

      Yii::app()->db_import->createCommand("
DELETE FROM StoreImportAssignCategory WHERE sid = '{$this->_source->id}' AND cid NOT IN (
  SELECT id FROM StoreImportDataCategories WHERE sid = '{$this->_source->id}'
);
")->execute();
      Yii::app()->cache->delete(StoreImportDataCategories::model()->tableName().'_plainTree_'.$this->_source->id);
      Yii::app()->cache->delete(StoreImportAssignCategory::model()->tableName().'_import_tree_'.$this->_source->id);
    }
    public function update_currency(){
      foreach($this->currency_info as $iso => $info) {
        $model = StoreImportDataCurrencies::model()->findByAttributes(array(
          'sid' => $this->_source->id,
          'iso' => $iso
        ));
        if (!$model) {
          $model = new StoreImportDataCurrencies();
          $model->sid = $this->_source->id;
          $model->iso = $iso;
        }
        $model->rate = $info['rate'];
        $model->save();
      }
    }

    protected function getCategoryLevel($cid){
      if (isset($this->categories[$cid])) {
        if (isset($this->categories[$cid]['level'])){
          return $this->categories[$cid]['level'];
        } else {
          $this->categories[$cid]['level'] = 1 + $this->getCategoryLevel($this->categories[$cid]['parent']);
          return $this->categories[$cid]['level'];
        }
      } else {
        return 0;
      }
    }
    protected function convertCurrency($fromISO){
      return $this->currencies[$fromISO]/$this->currencies[$this->base_currency]
      * $this->system_currencies[$this->base_currency]/$this->system_currencies[$this->system_default_currency];
    }
  }