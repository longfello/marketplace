<?php

  class Helper {

    static function registerCSS($file) {
      Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/css/'.$file));
    }
    static function registerJS($file, $position = CClientScript::POS_END) {
      Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/'.$file), $position);
    }
    static  function register_img($file){
      return Yii::app()->assetManager->publish(
        Yii::getPathOfAlias(Yii::getPathOfAlias('webroot').'/img/site/'.$file)
      );
    }
    static  function img($file, $params = array('width' => 100, 'height' => 100)){
      $litera = self::translit(mb_substr(basename($file), 0, 1));
      $uri = '/img/c/'.$litera.'/';
      $uri_params = array();
      $width  = isset($params['width'])?$params['width']:0;
      $height = isset($params['height'])?$params['height']:0;
      foreach($params as $key => $param) {
/*
        switch ($param) {
          case 'width':
            $width = intval($param);
            break;
          case 'height':
            $height = intval($param);
            break;
        }
*/
        switch($key) {
          case 'crop':
            $uri_params[] = $param?'crop':'nocrop';
            break;
          case 'enlarge':
            $uri_params[] = $param?'enlarge':'noenlarge';
            break;
        }
      }
      if ($width && $height) {
        $uri_params[] = $width.'x'.$height;
      } elseif ($width){
        $uri_params[] = $width.'x';
      } elseif($height){
        $uri_params[] = 'x'.$height;
      }
      $uri .= implode('-',$uri_params);

      return Yii::app()->getBaseUrl(true).$uri.'/'.$file;
    }
    public static function getIP() {
      if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
        $ip = getenv("HTTP_CLIENT_IP");
      } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
      } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
        $ip = getenv("REMOTE_ADDR");
      } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
        $ip = $_SERVER['REMOTE_ADDR'];
      } else {
        $ip = 'unknown';
      }
      $ip = explode(',', $ip);
      $ip = trim($ip[0]);
      return $ip;
    } // GetIP
    public static function redirect($whereto) {
      header("location: $whereto");
    }
    public static function getRequest($name = FALSE, $def = null, $type = type_string, $allow_scripts = FALSE) {
      return self::GetGet($name, self::GetPost($name, $def, $type, $allow_scripts), $type, $allow_scripts);
    }
    public static function getGet($name = FALSE, $def = null, $type = type_string, $allow_scripts = FALSE) {
      if ($name) {
        if (!is_null($def)) {
          $val = isset($_GET[$name]) ? self::castType($_GET[$name], $type, $allow_scripts) : $def;
          return $val;
        } else {
          $val = isset($_GET[$name]) ? $_GET[$name] : trigger_error("Ошибка передачи GET-параметра $name.", E_USER_ERROR);
          $val = self::castType($val, $type, $allow_scripts);
          return $val;
        }
      } else {
        $arr = new stdClass;
        foreach ($_GET as $key => $value) {
          $arr->$key = self::castType($value, $type, $allow_scripts);
        }
        return $arr;
      }
    }
    public static function getImplode($name, $def = null, $type = type_string) {
      $val = self::getGet($name, null, type_string);
      if(is_array($val)) {
        $val = implode('', $val);
      }
      return $val;
    }
    public static function castType($val, $type, $allow_scripts = FALSE) {
      if (is_array($val)) {
        foreach ($val as $key => $one) {
          $val[$key] = self::castType($one, $type);
        }
      } else if (is_object($val)) {
        if ($type != type_object) {
          foreach ($val as $key => $one) {
            $val->$key = self::castType($one, $type);
          }
        }
      } else {
        switch ($type) {
          case type_string  :
            $val = $allow_scripts ? $val : self::xss_clean($val);
            break;
          case type_float   :
            $val = floatval($val);
            break;
          case type_int     :
            $val = intval($val);
            break;
          case type_bool    :
            $val = (bool)$val;
            break;
          case type_object  :
            $val = (object)$val;
            break;
          default           :
            trigger_error('Unknow type of variable for type-cast: ' . $type, E_USER_ERROR);
        } // switch
      }
      return $val;
    }
    public static function xss_clean($data) {
      // Fix &entity\n;
      $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
      $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
      $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
      $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

      // Remove any attribute starting with "on" or xmlns
      $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

      // Remove javascript: and vbscript: protocols
      $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
      $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
      $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

      // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
      $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
      $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
      $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

      // Remove namespaced elements (we do not need them)
      $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

      do {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
      } while ($old_data !== $data);

      // we are done...
      return $data;
    }
    public static function getPost($name = FALSE, $def = null, $type = type_string, $allow_scripts = FALSE) {
      if ($name) {
        if (!is_null($def)) {
          $val = isset($_POST[$name]) ? self::castType($_POST[$name], $type, $allow_scripts) : $def;
          return $val;
        } else {
          $val = isset($_POST[$name]) ? $_POST[$name] : trigger_error("Ошибка передачи POST-параметра $name.", E_USER_ERROR);
          $val = self::castType($val, $type, $allow_scripts);
          return $val;
        }
      } else {
        $arr = new stdClass;
        foreach ($_POST as $key => $value) {
          $arr->$key = self::castType($value, $type, $allow_scripts);
        }
        return $arr;
      }
    }
    public static function getObject($class, $name, $def = null, $type = type_string, $allow_scripts = FALSE) {
      if (!is_null($def)) {
        if (is_object($class)) {
          $val = property_exists($class, $name) ? self::castType($class->$name, $type, $allow_scripts) : $def;
        } else {
          $val = $def;
        }
        return $val;
      } else {
        $val = property_exists($class, $name) ? self::castType($class->$name, $type, $allow_scripts) : trigger_error("Ошибка объектного параметра $name.", E_USER_ERROR);
        $val = self::castType($val, $type, $allow_scripts);
        return $val;
      }
    }
    public static function getSession($name = FALSE, $def = null, $type = type_string) {
      if ($name) {
        if (!is_null($def)) {
          $val = isset($_SESSION[$name]) ? self::castType($_SESSION[$name], $type, TRUE) : $def;
          return $val;
        } else {
          $val = isset($_SESSION[$name]) ? $_SESSION[$name] : trigger_error("Ошибка передачи SESSION-параметра $name.", E_USER_ERROR);
          $val = self::castType($val, $type, TRUE);
          return $val;
        }
      } else {
        $arr = new stdClass;
        foreach ($_SESSION as $key => $value) {
          $arr->$key = self::castType($value, $type, TRUE);
        }
        return $arr;
      }
    }
    public static function yiiTerminate(){
      foreach (Yii::app()->log->routes as $route) {
        if($route instanceof CWebLogRoute) {
          $route->enabled = false; // disable any weblogroutes
        }
      }
      Yii::app()->end();
    }
    public static function translit($cyr_str, $allow_dot = false) {
      $tr = array("Ґ" => "G", "Ё" => "YO", "Є" => "E", "Ї" => "YI", "І" => "I",
        "і" => "i", "ґ" => "g", "ё" => "yo", "№" => "#", "є" => "e",
        "ї" => "yi", "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
        "Д" => "D", "Е" => "E", "Ж" => "ZH", "З" => "Z", "И" => "I",
        "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
        "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
        "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
        "Ш" => "SH", "Щ" => "SCH", "Ъ" => "'", "Ы" => "YI", "Ь" => "",
        "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
        "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "zh",
        "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
        "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
        "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
        "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "",
        "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
        " " => "-", "," => "", "\r" => "-", "\n" => "-", "*" => 'ALL',
        ";" => "-", "'" => "-", '"' => "-", "(" => "-",  ")" => "-",
        "/" => "", ":" => "",  "?" => "-"
      );
      if (!$allow_dot) {
        $tr['.'] = '-';
      }
      return strtr($cyr_str, $tr);
    }
    public static function genCode($length, $type = 0) {
      $chars[0] = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ123456789_%*";
      $chars[1] = "ABCDEFGHIJKLMNOPRQSTUVWXYZ123456789";
      $chars = $chars[$type];
      $code = "";
      $clen = strlen($chars) - 1;
      while (strlen($code) < $length)
      {
        $code .= $chars[mt_rand(0,$clen)];
      }
      return $code;
    }
    public static function toArray($data) {
      if ((! is_array($data)) and (! is_object($data))) return array(); //$data;
      $result = array();
      foreach ($data as $key => $value) {
        if (is_object($value)) $value = self::toArray($value);
        if (is_array($value))
          $result[$key] = self::toArray($value);
        else
          $result[$key] = $value;
      }
      return $result;
    }
    public static function getYouYubeCode($url){
      $code = $url;
      $code = preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+|(?<=youtube.com/embed/)[^&\n]+#", $code, $matches);
      $code = isset($matches[0])?$matches[0]:'';
      return $code;
    }
    public static function probability($percent = 50) {
      return (rand(1, 100) < $percent);
    }
    public static function curl_get($url) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.1) Gecko/2008070208');
      $ss = curl_exec($ch);
      curl_close($ch);
      return $ss;
    }
    public static function print_r($arr, $return = false, $html = false) {
      $out = array();
      $oldtab = "    ";
      $newtab = $html?"&nbsp;&nbsp;":"  ";

      $lines = explode("\n", print_r($arr, true));

      foreach ($lines as $line) {

        //remove numeric indexes like "[0] =>" unless the value is an array
        if (substr($line, -5) != "Array") {	$line = preg_replace("/^(\s*)\[[0-9]+\] => /", "$1", $line, 1); }

        //garbage symbols
        foreach (array(
                   "Array" => "",
                   "["     => "",
                   "]"     => "",
                   " =>"   => ":",
                 ) as $old => $new) {
          $out = str_replace($old, $new, $out);
        }

        //garbage lines
        if (in_array(trim($line), array("Array", "(", ")", ""))) continue;

        //indents
        $indent = "";
        $indents = floor((substr_count($line, $oldtab) - 1) / 2);
        if ($indents > 0) { for ($i = 0; $i < $indents; $i++) { $indent .= $newtab; } }

        $line = $indent . trim($line);
        $out[] = $html?"<nobr>$line</nobr>":$line;
      }

      $nl  = $html?"<br>":"\n";
      $out = implode($nl, $out) . $nl;
      if ($return == true) return $out;
      echo $out;
    }
    public static function isCli(){
      return (php_sapi_name() === 'cli');
    }
    public static function passwordStrong($password){
      $strong = 0;
      // Если нет недопустимх символов в пароле
      if(!preg_match("/([\s])/", $password)){
        //Проверяем, есть ли в пароле числа
        if(preg_match("/([0-9]+)/", $password))
        {
          $strong++;
        }
        //Проверяем, есть ли в пароле буквы в нижнем регистре
        if(preg_match("/([a-z]+)/", $password))
        {
          $strong++;
        }
        //Проверяем, есть ли в пароле буквы в верхнем регистре
        if(preg_match("/([A-Z]+)/", $password))
        {
          $strong++;
        }
        //Проверяем, есть ли в пароле спецсимволы
        if(preg_match("/\W/", $password))
        {
          $strong++;
        }
      }
      return $strong;
    }
    public static function getCityIDByIp(){
      // IP-адрес, который нужно проверить

      $ip = self::getIP();
      $ip = ($ip == '192.168.1.200')?"195.24.147.106":$ip;
      // Преобразуем IP в число
      $int = sprintf("%u", ip2long($ip));

      $city = false;

      // Ищем по российским и украинским городам
      $sql = "select city_id from (select city_id, end_ip from net_ru where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int";
      $result = Yii::app()->db->createCommand($sql)->queryRow();
      if ($result){
        $city    = NetCity::model()->findByPk($result['city_id']);
      }

      // Если не нашли - ищем страну и город по всему миру
      if (!$city) {
        // Ищем город в глобальной базе
        $sql = "select * from (select * from net_city_ip where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        if ($result){
          $city    = NetCity::model()->findByPk($result['city_id']);
        }
      }

      if (!$city) {
        $city    = NetCity::model()->findByPk($result['city_id']);
        if (!$city) {
          $city = NetCity::model()->findByPk(UserLocation::defaultCity);
        }
      }
/*
      // Выводим результат поиска
      if (!$country) {
        echo "Страна не определена";
      } else {
        // Название страны
        // Выводим
        echo $country->id." ".$country->name_ru;
      }

      echo "<br>";

      if (!$city) {
        echo "Город не определен";
      } else {
        echo $city->id." ".$city->name_ru;
      }
*/
      return $city;
    }


    public static function SearchEntityAutoComplete($search_for){
      $table = 'ri_autocomplete';
      $s = Yii::app()->sphinx;
      $s->setMatchMode(SPH_MATCH_EXTENDED2);
      $s->setMaxQueryTime(300);
      $s->SetRankingMode(SPH_RANK_SPH04);
      $s->SetSortMode(SPH_SORT_RELEVANCE);
      $s->SetLimits(0, 50, 50);
      $query = $search_for.'*';
      $result = $s->Query($query, $table);
      $res = array($search_for);
      if (isset($result['matches']) && is_array($result['matches'])) {
        foreach($result['matches'] as $one) {
//          print_r($one);
          $word = $one['attrs']['name'];
          $query_len = mb_strlen($search_for);

          $pos  = mb_stripos($word, $search_for);
          $word = mb_substr($word, $pos, 1000);
          $word = self::cut_string($word, $query_len);
          $word = trim($word);
          $word = self::cut_string($word, $query_len+20);

          $word_count = count(explode(' ', $word));
          $limit = 2700*$word_count;

          if ($one['weight'] > $limit) {
            $res[$one['weight']] = trim($word);
          } else {
//            $res[$one['weight']] = '-'.trim($word).$one['weight'];
          }
        }
      }
      $res = array_unique(array_map('mb_strtolower', $res));
      krsort($res);
      $res = array_slice($res, 0, 10);
      return $res;
    }
    public static function GetSphinxKeyword($sQuery)
    {
      $aKeyword = array();
      $sQuery = str_replace(array('"', '.', '/', '+', '-', '@', '!', '(', ')'),' ', $sQuery);
      $aRequestString=preg_split('/[\t\s,-]+/', $sQuery, 10);
//      Helper::print_r($aRequestString,false,true);
      if ($aRequestString) {
        foreach ($aRequestString as $sValue)
        {
          if (strlen($sValue)>3)
          {
            $aKeyword[] .= "(".$sValue." | *".$sValue."*)";
          }
        }
      }
//      Helper::print_r($aKeyword,false,true);
      if ($aKeyword) {
        $words = count($aKeyword);
        $santificatior = ($words <= 6)?4:($words - ceil($words*0.3));
//        $santificatior = 4;
        $sSphinxKeyword = '(('.implode(" MAYBE ", $aKeyword).') | ("'.$sQuery.'"/'.$santificatior.'))';
      } else {
        $sSphinxKeyword = '(("'.$sQuery.'") | ("*'.$sQuery.'*"))';
      }
      return $sSphinxKeyword;
    }
    public static function cut_string($string, $length = 200){
      $str = strip_tags($string);
      if ($length && mb_strlen($str) > $length){
        $pos = mb_stripos($str, ' ', $length-1);
        if ($pos) {
          $str = mb_substr($str, 0, $pos);
        }
      }
      $str = trim($str, ' ,.-');
      return $str;
    }


    public static function mergeMU($modelValues, $stringFields = array()){
      $return = array();
      foreach($modelValues as $attrs){
        foreach($attrs as $key => $attr) {
          if (!isset($return[$key])) {
            $return[$key] = $attr;
            continue;
          }
          if ($return[$key] !== $attr) {
            $return[$key] .= ' | '.$attr;
          }
        }
      }
      foreach($return as $key => $attr) {
        if (!$attr) {
          if (in_array($key, $stringFields)) {
            $return[$key] = '';
          } else {
            $return[$key] = 0;
          }
        }
      }
      return $return;
    }
    public static function CategoryNodesToArray($data){
      $arr = array();
      foreach($data as $node){
        $one = array(
          'id'   => $node['id'],
          'name' => $node['name'],
          'hasChildren' => $node['hasChildren'],
        );
        if ($node['hasChildren']) {
          $one['children'] = self::CategoryNodesToArray($node['children']);
        }
        $arr[$one['id']] = $one;
      }
      return $arr;
    }
    public static function CategoryNodesArrayInsert(&$data, $what){
      $founded = false;
      foreach($data as &$node){
        if ($node['id'] == $what['spid']) {
          if (!$node['hasChildren']) {
            $node['hasChildren'] = true;
            $node['children'] = array();
          }
          $founded = true;
          $node['children'][$what['cid']] = array(
            'id'   => -$what['cid'],
            'class' => 'item-inserted',
            'name' => $what['elaboration'],
            'hasChildren' => false,
          );
          break;
        } else {
          if ($node['hasChildren']) {
            $founded = self::CategoryNodesArrayInsert($node['children'], $what);
          }
        }
      }
      return $founded;
    }


    static function updateTree($sid,StoreImportAssignCategory $el){
      $tree = self::getTree($sid);
      $hash = $el->tableName().'_import_tree_'.$sid;
      if ($el->solution == StoreImportAssignCategory::SOLUTION_CREATE) {
        self::insertIntoTree($tree, array($el));
      } else {
        self::removeFromTree($tree, $el);
      }
      Yii::app()->cache->set($hash, $tree, 300);
    }

    static function removeFromTree(&$tree, StoreImportAssignCategory $el){
      foreach($tree as $key => &$one) {
        if ($one['id'] == $el->cid) {
          unset($tree[$key]);
        } else {
          if ($one['hasChildren']) {
            self::removeFromTree($one['children'], $el);
          }
        }
      }
    }
    static function insertIntoTree(&$tree, $els) {
      $list = $els; $iterations = count($els)+1;
      while ($list && $iterations > 0) {
        $iterations--;
        $list = array();
        foreach($els as $one){
          $founded = Helper::CategoryNodesArrayInsert($tree, Helper::toArray($one));
          if (!$founded) {
            $list[] = $one;
          }
        }
        $els = $list;
      }
    }

    static function getTree($sid){
      $hash = StoreImportAssignCategory::model()->tableName().'_import_tree_'.$sid;
      $tree = Yii::app()->cache->get($hash);
      if (!$tree) {
        $tree = StoreCategoryNode::fromArray(StoreCategory::model()->findAllByPk(1));
        $tree = Helper::CategoryNodesToArray($tree);

        $for_insert = StoreImportAssignCategory::model()->findAllByAttributes(array(
          'sid' => $sid,
          'solution' => StoreImportAssignCategory::SOLUTION_CREATE
        ));
        self::insertIntoTree($tree, $for_insert);
        Yii::app()->cache->set($hash, $tree, 300);
      }
      return $tree;
    }
  }



  // Приведение типов
  define('type_int', 'type_int', true);
  define('type_float', 'type_float', true);
  define('type_string', 'type_string', true);
  define('type_bool', 'type_bool', true);
  define('type_object', 'type_object', true);

  /*

  $info = new hello();
  $text = '';

  // Вводная часть
  if (($info->clientHour > 5)&&($info->clientHour<12)) $text .= "Доброе утро";
  elseif (($info->clientHour > 11)&&($info->clientHour<18)) $text .= "Привет";
  elseif (($info->clientHour > 17)&&($info->clientHour<23)) $text .= "Добрый вечер";
  else $text .= "Привет кому не спится";

  // Кажеться сегодня
  if (!is_null($info->cloudinessID) && $info->cityName) {
    $text .= ", кажется, сегодня $info->cityName $info->cloudinessText. ";
    switch ($info->cloudinessID) {
      case 0:
      case 1:
        switch ($info->device) {
          case 'PC':
            if (($info->clientHour > 5)&&($info->clientHour < 18)) $text .= "Бросайте Ваш ПК и выходите гулять :) ";
            else $text .= "Надеемся, Вы комфортно устроились за Вашим компьютером. ";
            break;
          case 'iPod':
            $text .= "У Вас iPod? Здорово! Ниже кое-что интересное. ";
            break;
          case 'iPhone':
            $text .= "О, iPhone? Хороший выбор! ";
            break;
          case 'iPad':
            $text .= "У Вас iPad? Здорово! Ниже кое-что интересное. ";
            break;
          case 'Android':
            $text .= "О, у Вас Android? Мы их обожаем! ";
            break;
        }
        break;
      case 2:
      case 3:
      case 4:
        switch ($info->device) {
          case 'PC':
            $text .= 'В такую погоду посидеть за ПК не самый плохой вариант :) Ниже кое-что интересное! ';
            break;
          case 'iPod':
            $text .= "О, iPod? Здорово! Ниже кое-что интересное. ";
            break;
          case 'iPhone':
            $text .= "О, iPhone? Беспроигрышный вариант :) Смотрите, что у нас есть: ";
            break;
          case 'iPad':
            $text .= "О, iPad? Здорово! Ниже кое-что интересное. ";
            break;
          case 'Android':
            $text .= "О, Android? Класс! Смотрите, что у нас есть: ";
            break;
        }
        switch ($info->clientOS) {
          case 'Windows':
            $text .= "Вау, $info->clientOS? Добро пожаловать! ";
            break;
        }
        break;
      case 5:
      case 6:
      case 7:
        $text .= "Не забыли зонт? ";
        switch ($info->device) {
          case 'PC':
            $text .= 'Не самое плохое время, что бы посидеть за ПК в интернете :) ';
            break;
          case 'iPod':
          case 'iPhone':
          case 'iPad':
          case 'Android':
            $text .= "Не промочите Ваш $info->device :) ";
            break;
        }
        switch ($info->clientOS) {
          case 'Windows':
            $text .= "Не промочите Ваш $info->clientOS :) ";
            break;
        }
        break;
      case 8:
      case 9:
      case 10:
        $text .= "Не забыли зонт? ";
        switch ($info->device) {
          case 'PC':
            $text .= 'Не самое плохое время, что бы посидеть за ПК в интернете :) ';
            break;
          case 'iPod':
          case 'iPhone':
          case 'iPad':
          case 'Android':
            $text .= "Не промочите Ваш $info->device :) ";
            break;
        }
        switch ($info->clientOS) {
          case 'Windows':
            $text .= "Не промочите Ваш $info->clientOS :) ";
            break;
        }
        break;
    }
  } else {
    $device = ($info->device == 'PC')?'компьютером':$info->device;
    $text .=". Надеемся, Вы комфортно устроились за Вашим $device. Смотрите, что у нас есть: ";
  }


  echo($text);
//  echo "Вот здесь наш варьирующийся текст. Например: Добрый вечер, $info->cityName кажется сегодня $info->cloudinessText? Не промочите Ваш $info->device ($info->clientOS).";



  //
  // mb_ucfirst - преобразует первый символ в верхний регистр
  // @param string $str - строка
  // @param string $encoding - кодировка, по-умолчанию UTF-8
  // @return string
  //

  function mb_ucfirst22($str, $encoding='UTF-8'){
    $str = mb_ereg_replace('^[\ ]+', '', $str);
    $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding). mb_substr($str, 1, mb_strlen($str), $encoding);
    return $str;
  }
  function GetIP() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
      $ip = getenv("HTTP_CLIENT_IP");
    } elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
      $ip = getenv("HTTP_X_FORWARDED_FOR");
    } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
      $ip = getenv("REMOTE_ADDR");
    } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
      $ip = $_SERVER['REMOTE_ADDR'];
    } else {
      $ip = 'unknown';
    }
    return $ip;
  } // GetIP

  class hello {
    var $ip;
    var $cityISO = '';
    var $cityName = '';
    var $cityID = 0;
    var $weather;
    var $clientHour;
    var $clientOS;
    var $cloudinessText = 'непонятно';
    var $cityISOUrl  = 'http://api.ipinfodb.com/v3/ip-city/?key=de9e0eac62cc5b3d1fb79a9ef3daf812521501441cd3a338c03b6f77561f778c&ip=%IP%&format=xml';
    var $cityNameUrl = "http://xml.weather.co.ua/1.2/city/?search=%cityISO%";
    var $weatherUrl  = "http://xml.weather.co.ua/1.2/forecast/%cityID%?dayf=1&userid=webpr_pp_ua&lang=ru";
    var $device = 'PC';
    var $deviceCase = array(
      'PC' => '',
      'iPod' => 'iPod',
      'iPhone' => 'iPhone',
      'iPad' =>  'iPad',
      'Android'  => 'Android'
    );
    var $OSList = array (
      // Match user agent string with operating systems
      'Windows' => '(Windows)|(Win16)|(Windows 95)|(Win95)|(Windows_95)|(Windows 98)|(Win98)|(Windows NT 5.0)|(Windows 2000)|(Windows NT 5.1)|(Windows XP)|(Windows NT 5.2)|(Windows NT 6.0)|(Windows NT 7.0)|(Windows NT 6.1)|(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)|(Windows ME)',
      'Open BSD' => 'OpenBSD',
      'Sun OS' => 'SunOS',
      'Andriod' => '(Andriod)',
      'Linux' => '(Linux)|(X11)',
      'Mac OS' => '(Mac_PowerPC)|(Macintosh)|(Mac OS)',
      'QNX' => 'QNX',
      'BeOS' => 'BeOS',
      'OS/2' => 'OS\/2',
      'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves\/Teoma)|(ia_archiver)'
    );
    var $cloudinessID = null;
    var $cloudiness  = array(
      /*
            0 => 'хорошая погода',
            1 => 'малооблачно',
            2 => 'облачно',
            3 => 'пасмурно',
            4 => 'кратковременный дождь',
            5 => 'дождь',
            6 => 'гроза',
            7 => 'град',
            8 => 'мокрый снег',
            9 => 'снегопад',
            10 => 'сильный снегопад'
      */ /*
      0 => 'хорошая погода',
      1 => 'хорошая погода',
      2 => 'пасмурно',
      3 => 'пасмурно',
      4 => 'пасмурно',
      5 => 'дождливо',
      6 => 'дождливо',
      7 => 'дождливо',
      8 => 'возможен снегопад',
      9 => 'снегопад',
      10 => 'снегопад'
    );
    function __construct(){
      $this->ip = isset($_GET['ip'])?$_GET['ip']:GetIP();
      //      echo($this->ip.'  ');
      //      $_POST['nav'] = "Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B329 Safari/8536.25";
      //      $this->ip = '195.24.147.106';
      //      $this->ip = '25.24.147.106';
      //      $this->ip = '5.24.147.106';
      $this->getISOTown();
      $this->getTownName();
      $this->getWeather();
      $this->getDevice();
      $this->getClientHour();
      $this->getClientOS();
    }

    function loadXML($url) {
      //      echo($url.'<br>');
      $fp = fopen($url, "r");
      $data = fread($fp, 80000);
      fclose($fp);
      return simplexml_load_string($data);
    }

    // Получаем ISO-имя города по ip
    function getISOTown(){
      $file = str_replace('%IP%', $this->ip, $this->cityISOUrl);
      $xml  = $this->loadXML($file);
      $this->cityISO = $xml->cityName;
      return $this->cityISO;
    }

    // Погода - поиск города
    function getTownName(){
      $file = str_replace('%cityISO%', urlencode($this->cityISO), $this->cityNameUrl);
      $xml  = $this->loadXML($file);
      if (!isset($xml->city)) return false;
      //      $this->cityName = $xml->city->name;
      $this->cityID = $xml->city->attributes()->id;

      $translator = new GoogleTranslater();
      $this->cityName = $translator->translateText("in ".mb_ucfirst22(mb_strtolower($this->cityISO, 'UTF-8')));
    }

    // Погода
    function getWeather() {
      $file = str_replace('%cityID%',$this->cityID, $this->weatherUrl);
      $xml  = $this->loadXML($file);
      $this->weather = $xml->current;
      if (!isset($this->weather->cloud)) return;
      $this->cloudinessID = floor($this->weather->cloud / 10);
      //      $this->cloudinessID = 7;
      if (isset($this->cloudiness[$this->cloudinessID])) $this->cloudinessText = $this->cloudiness[$this->cloudinessID];
    }

    // Устройство
    function getDevice(){
      $nav = isset($_POST['nav'])?$_POST['nav']:$_SERVER['HTTP_USER_AGENT'];
      foreach($this->deviceCase as $device => $Match)
      {
        // Find a match
        if ($Match && preg_match('/'.$Match.'/i', $nav))
        {
          // We found the correct match
          $this->device = $device;
          break;
        }
      }
    }

    // Время
    function getClientHour(){
      $this->clientHour =  isset($_POST['h'])?$_POST['h']:Date('G');
    }

    function getClientOS(){
      $nav = isset($_POST['nav'])?$_POST['nav']:$_SERVER['HTTP_USER_AGENT'];
      foreach($this->OSList as $CurrOS => $Match)
      {
        // Find a match
        if (preg_match('/'.$Match.'/i', $nav))
        {
          // We found the correct match
          break;
        }
      }
      $this->clientOS = $CurrOS;
    }
  }

/**
 * GoogleTranslater is PHP interface for http://translate.google.com/
 * It send request to google translate service, get response and provide
 * translated text.
 *
 * @author Andrew Kulakov <avk@8xx8.ru>
 * @version 1.0.0
 * @license https://github.com/Andrew8xx8/GoogleTranslater/blob/master/MIT-LICENSE.txt
 * @copyright Andrew Kulakov (c) 2011
 */
/*
class GoogleTranslater
{
  private $_errors = "";

  public function _construct()
  {
    if (!function_exists('curl_init'))
      $this->_errors = "No CURL support";
  }

  /**
   * Translate text.
   * @param  string $text          Source text to translate
   * @param  string $fromLanguage  Source language
   * @param  string $toLanguage    Destenation language
   * @param  bool   $translit      If true function return transliteration of source text
   * @return string|bool           Translated text or false if exists errors
   *
  public function translateText($text, $fromLanguage = "en", $toLanguage = "ru", $translit = false)
  {
    if (empty($this->_errors)) {
      $result = "";
      for($i = 0; $i < strlen($text); $i += 1000)
      {
        $subText = substr($text, $i, 1000);

        $response = $this->_curlToGoogle("http://translate.google.com/translate_a/t?client=te&text=".urlencode($subText)."&hl=ru&sl=$fromLanguage&tl=i$toLanguage&multires=1&otf=1&ssel=0&tsel=0&uptl=ru&sc=1");
        $result .= $this->_parceGoogleResponse($response, $translit);
        //                sleep(1);
      }
      return $result;
    } else
      return false;
  }

  /**
   * Translate array.
   * @param  array  $array         Array with source text to translate
   * @param  string $fromLanguage  Source language
   * @param  string $toLanguage    Destenation language
   * @param  bool   $translit      If true function return transliteration of source text
   * @return array|bool            Array  with translated text or false if exists errors
   *
  public function translateArray($array, $fromLanguage = "en", $toLanguage = "ru", $translit = false)
  {
    if (empty($this->_errors)) {
      $text = implode("[<#>]", $array);
      $response = $this->translateText($text, $fromLanguage, $toLanguage, $translit);
      return $this->_explode($response);
    } else
      return false;
  }

  public function getLanguages()
  {
    if (empty($this->_errors)) {
      $page = $this->_curlToGoogle('http://translate.google.com/');
      preg_match('%<select[^<]*?tl[^<]*?>(.*?)</select>%is', $page, $match);
      preg_match_all("%<option.*?value=\"(.*?)\">(.*?)</option>%is", $match[0], $languages);
      $result = Array();
      for($i = 0; $i < count($languages[0]); $i++){
        $result[$languages[1][$i]] = $languages[2][$i];
      }
      return $result;
    } else
      return false;
  }

  public function getLanguagesHTML()
  {
    if (empty($this->_errors)) {
      $page = $this->_curlToGoogle('http://translate.google.com/');
      preg_match('%<select[^<]*?tl[^<]*?>(.*?)</select>%is', $page, $match);
      return $match[1];
    } else
      return false;
  }

  public function getErrors()
  {
    return $this->_errors;
  }

  private function _explode($text)
  {
    $text = preg_replace("%\[\s*<\s*#\s*>\s*\]%", "[<#>]", $text);
    return array_map('trim', explode('[<#>]', $text));
  }

  private function _curlToGoogle($url)
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    if (isset($_SERVER['HTTP_REFERER'])) {
      curl_setopt($curl, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
    }
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.71 Safari/534.24");
    $response = curl_exec($curl);
    // Check if any error occured
    if(curl_errno($curl))
    {
      $this->_errors .=  "Curl Error: ".curl_error($curl);
      return false;
    }
    curl_close($curl);
    return $response;
  }

  private function _parceGoogleResponse($response, $translit = false)
  {
    if (empty($this->_errors)) {
      $result = "";
      $json = json_decode($response);
      foreach ($json->sentences as $sentence) {
        $result .= $translit ? $sentence->translit : $sentence->trans;
      }
      return $result;
    }  else {
      return false;
    }
  }
}
*/