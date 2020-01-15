<?php

  class ExtImgController extends \CController {
    private $cache_folder = 'c';
    private $not_found_file = '/img/site/nophoto.png';
    private $uri = array();

    // settings
    private $width   = 100;
    private $height  = 100;
    private $crop    = false;
    private $enlarge = false;

    private $source_file;
    private $target_file;

    public function beforeAction($action){
      $uri = Yii::app()->request->requestUri;
      $this->target_file = Yii::app()->getBasePath().'/..'.$uri;
      $uri = explode('/', $uri);
      // remove empty
      array_shift($uri);
      // remove img
      array_shift($uri);
      $this->uri = $uri;


      if ($uri[0] == $this->cache_folder) {
        // remove cache marker
        array_shift($this->uri);
        // remove literal
        array_shift($this->uri);

        $this->getParams();
        if (!is_file($this->source_file) || !is_readable($this->source_file)) {
          $this->action_notFound();
        }
      } else {
        $this->action_notFound();
      }
      return true;
    }

    private function getParams(){
      if (count($this->uri) > 1) {
        $params = $this->uri[0];
        array_shift($this->uri);

        $params = explode('-', $params);
        foreach($params as $param) {
          switch ($param) {
            case 'crop':
              $this->crop = true;
              break;
            case 'nocrop':
              $this->crop = false;
              break;
            case 'enlarge':
              $this->enlarge = true;
              break;
            case 'noenlarge':
              $this->enlarge = false;
              break;
            default:
              if (strpos($param, 'x')) {
                list($this->width, $this->height) = explode('x', $param);
                $this->width  = intval($this->width);
                $this->height = intval($this->height);
              }
              if (is_int($param)) {
                $this->width  = $param;
                $this->height = $param;
              }
          }
        }
      }
      $this->source_file = Yii::app()->getBasePath().'/../img/'.implode('/', $this->uri);
    }

    public function actionIndex(){
      header( 'Content-Type: image/jpg' );
      acResizeImage::make_picture_preview($this->source_file, $this->target_file, $this->width, $this->height, 80, $this->crop);
      readfile($this->target_file);
      Helper::yiiTerminate();
    }

    private function action_notFound(){
      $this->getParams();
      header( 'Content-Type: image/png' );
      readfile(Yii::app()->getBasePath().'/..'.$this->not_found_file);
      Helper::yiiTerminate();
    }
  }