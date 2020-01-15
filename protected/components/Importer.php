<?php

  /**
   * Class Importer
   */
  class Importer {
    /**
     *
     */
    const SOURCE_TYPE_YML = 'yml';
    const ERROR_NOT_FOUND = 404;
    const ERROR_SERVER    = 500;
    const ERROR_TRANSPORT = 503;

    /**
     * @var StoreImportSources
     */
    private $_source;

    private $_transports = array(
      self::SOURCE_TYPE_YML => null
    );

    /**
     * @var array
     */
    private $_errors = array();
    private $_result = true;
    private $jobWorker = false;



    /**
     * @param StoreImportSources $source
     */
    public function __construct(StoreImportSources $source, $backgroundJobWorker = false) {
      include_once(__DIR__.'/importer/transport_prototype.php');
      include_once(__DIR__.'/importer/yml.php');
      $this->_source   = $source;
      $this->jobWorker = $backgroundJobWorker;
      $this->_transports[self::SOURCE_TYPE_YML] = new importer_yml($this->_source, $this->jobWorker);
    }

    /**
     *
     */
    public function load(){
      if (!$this->transProcess('load')) {
        $this->setError($this->transProcess('getError'));
      }
      return $this->getProcessResults();
    }

    public function check(){
      if (!$res = $this->transProcess('check')) {
        $this->setError($this->transProcess('getError'));
      } else {
        $this->_source->status = StoreImportSources::STATUS_ASSIGN;
      }
      $this->_source->save();
      return $this->getProcessResults();
    }

    public function preProcessInit(){
      if (!$this->transProcess('preprocessInit')) {
        $this->setError($this->transProcess('getError'));
      }
      return $this->_result;
    }
    public function preProcessCategory(){
      if (!$this->transProcess('preprocessCategory')) {
        $this->setError($this->transProcess('getError'));
      }
      return $this->_result;
    }
    public function preProcessParams(){
      if (!$this->transProcess('preprocessParams')) {
        $this->setError($this->transProcess('getError'));
      }
      return $this->_result;
    }
    public function preProcessVendors(){
      if (!$this->transProcess('preprocessVendors')) {
        $this->setError($this->transProcess('getError'));
      }
      return $this->_result;
    }
    public function preProcessProducts(){
      if (!$this->transProcess('preprocessProducts')) {
        $this->setError($this->transProcess('getError'));
      }
      return $this->_result;
    }

    public function runCategories(){
      return true;
    }

    public function runVendors(){
      return true;
    }

    public function runOptions(){
      return true;
    }

    public function runImages(){
      return true;
    }

    public function runProducts(){
      if (!$this->transProcess('runProducts')) {
        $this->setError($this->transProcess('getError'));
      }
      return $this->_result;
    }
    public function runProductsOptions(){
      return true;
    }

    public function process(){
      if (!$this->transProcess('process')) {
        $this->setError($this->transProcess('getError'));
      }
      return $this->_result;
    }

    public function getProcessResults(){
      return array(
        'errors'   => $this->transProcess('getError'),
        'messages' => $this->transProcess('getMessages')
      );
    }

    public function getErrors(){
      return $this->_errors;
    }

    private function transProcess($function){
      $type = $this->_source->type;
      if(isset($this->_transports[$type])) {
        if (method_exists($this->_transports[$type], $function)) {
          return $this->_transports[$type]->{$function}();
        } else $this->setError(self::ERROR_SERVER, "Незавершенное действие ($function) транспорта $type");
      } else $this->setError(self::ERROR_SERVER, "Неизвестный транспорт $type");
      return false;
    }

    /**
     * @param       $code
     * @param       $message
     * @param array $additionalInfo
     */
    public function setError($code, $message = null, $additionalInfo=array()){
      $this->_result = false;

      $this->_source->status = StoreImportSources::STATUS_ERRORS;
      $this->_source->save();

      $sourceError = new StoreImportErrors();
      $sourceError->sid = $this->_source->id;
      $sourceError->message = $message;
      $sourceError->data = Helper::print_r($additionalInfo, true, true);
      $sourceError->save();


      if (is_array($code)) {
        $this->_errors[] = $code;
      } else $this->_errors[] = array('code' => $code, 'message' => $message, 'additionalInfo' => $additionalInfo);
    }
  }