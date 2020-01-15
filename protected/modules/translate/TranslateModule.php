<?php

class TranslateModule extends BaseBootstrapModule {
  //BasePath for messages: Yii::app()->messages->basePath
  public $messagepath;
  //Yii defined language: Yii::app()->language
  public $language;
  //Index number of $languages[$language]
  public $languageindex;
  //list of directories found under $messagepath
  public $languages;
  //names of the languages extracted from Yii->locale
  public $languagesnames;
  //list of files found under $messagepath / $language
  public $fileslist;
  //encoding used to save messages
  public $encoding = 'UTF-8';
  //directories to exclude
  public $excludedirs = array();
  //files to exclude
  public $excludefiles = array();
  //dont edit the variables below
  public $_assetsUrl;
  public $version = '0.5';

  public function init() {
    // this method is called when the module is being created
    // you may place code here to customize the module or the application
    // import the module-level models and components
    $this->setImport(array(
      'translate.models.*',
      'translate.components.*',
    ));
    $this->messagepath = Yii::app()->messages->basePath;
    $this->language = Yii::app()->language;
    $this->languages = $this->findLanguages();
    $this->languagesnames = $this->languagesNames();
    $this->fileslist = $this->findFiles($this->language);

    $this->getAssetsUrl();
  }

  public function beforeControllerAction($controller, $action) {
    if (parent::beforeControllerAction($controller, $action)) {
      // this method is called before any module controller action is performed
      // you may place customized code here
      return true;
    } else {
      //            return false;
      return true;
    }
  }

  /**
   * publishes assets
   */
  public function getAssetsUrl() {
    //        if ($this->_assetsUrl === null)
    $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
      Yii::getPathOfAlias('translate.assets'));
    return $this->_assetsUrl;
  }

  /**
   * finds directories under Yii::app()->messages->basePath
   * @return array with directories/languages names
   */
  private function findLanguages() {
    $languages = array();
    $i = 0;

    if ($handle = opendir($this->messagepath)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          //self::$_items[$type][$model->code]=$model->name;
          $languages[$i] = $entry;
          $i++;
        }
      }
      closedir($handle);
    }

    if (!empty($this->excludedirs)) {
      $languages = $this->excludes('dirs', $languages);
    }
    return $languages;
  }

  /**
   * finds languages names for directories under Yii::app()->messages->basePath
   * @return array containg the names for all found languages
   */
  private function languagesNames() {
    $languagesnames = array();

    foreach ($this->languages as $key => $value) {
      $languagesnames[$value] = Yii::App()->locale->getLocaleDisplayName($value);
    }

    return $languagesnames;
  }

  /**
   * @param string $language, usualy a language code
   * @return string with the name for the given code
   */
  public function languageName($language) {
    return Yii::App()->locale->getLocaleDisplayName($language);
  }

  /**
   * returns file list for given directory under Yii::app()->messages->basePath
   * @param string $dir the name of the directory to list, usualy a language code
   * @return array with the found files
   */
  private function findFiles($dir) {
    $files = array();
    $i = 0;

    if ($handle = @opendir($this->messagepath . '/' . $dir)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          //self::$_items[$type][$model->code]=$model->name;
          $files[$i] = $entry;
          $i++;
        }
      }
      closedir($handle);
    }
    if (!empty($this->excludefiles)) {
      $files = $this->excludes('files', $files);
    }

    return $files;
  }

  /**
   * returns file list for given directory under Yii::app()->messages->basePath
   * @param string $dir the name of the directory to list, usualy a language code
   * @return array with the found files
   */
  public function findFilesSearch($dir, $str) {
    $files = array();
    $i = 0;

    if ($handle = @opendir($this->messagepath . '/' . $dir)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          //self::$_items[$type][$model->code]=$model->name;
          $content = file_get_contents($this->messagepath . '/' . $dir.'/'.$entry);
          if (preg_match('/'.$str.'/',$content)) {
            $files[$i] = $entry;
            $i++;
          }
        }
      }
      closedir($handle);
    }
    if (!empty($this->excludefiles)) {
      $files = $this->excludes('files', $files);
    }

    return $files;
  }

  /**
   * excludes entries from given array
   * @param string $type, what verification to do(dirs or files)
   * @param array $list, array to exclude from
   * @return array after exclusions
   */
  private function excludes($type, $list) {

    if ($type == 'dirs') {
      $diff = array_diff($list, $this->excludedirs);
      return $diff;
    } else if ($type == 'files') {
      $diff = array_diff($list, $this->excludefiles);
      return $diff;
    }
  }

  /**
   * returns final content for message file
   * @param array $data containing the translations pairs
   * @return string final result ready to save
   */
  public function composeMessageFile($data) {
    $version = '//created with TranslatePhpMessage module ' . $this->version . ' at ' . date("F j, Y, G:i:s");
    /*
      $array2text = $this->array2text($data);
      $output = "<?php\n" . $version . "\nreturn array(\n" . $array2text . ")\n?>";
     */
    $array2text = var_export($data, true);
    $output = "<?php\n" . $version . "\nreturn " . $array2text . "\n?>";

    return $output;
  }

  /**
   * saves message file $this->messagepath / $language directory
   * @param string $language used with messagepath to obtain full path
   * @param string $filename of message file
   * @param string $data content to be saved
   */
  public function saveMessageFile($language, $filename, $data) {

    $messagefile = $this->messagepath . '/' . $language . '/' . $filename;
    file_put_contents($messagefile, $data);
  }

  //    public function sanitize($data) {
  //        $clean = htmlspecialchars($data, ENT_NOQUOTES, $this->encoding);
  //        return $clean;
  //    }

  /**
   * Computes array_diff of files between yii->language and given language
   * @param string $translate - language to be translated
   * @return array of missing files
   */
  public function missingFiles($translate) {
    $original = $this->findFiles($this->language);
    $translation = $this->findFiles($translate);
    $diff = array_diff($original, $translation);

    return $diff;
  }

  /**
   * Computes array_diff of files between yii->language and given language
   * @param string $translate - language to be translated
   * @return array of existing files
   */
  public function existingFiles($translate) {
    $original = $this->findFiles($this->language);
    $translation = $this->findFiles($translate);
    $diff = array_diff($original, $translation);
    $diff = array_diff($original, $diff);

    return $diff;
  }

  /**
   * Count an array of items where value is blank.
   * @param array $input
   * @return numeric
   */
  public function countWhat($input) {
    $input = is_array($input) ? $input : (array) $input;
    $i = 0;

    foreach ($input as $current) {
      $match = null;

      if ($current != '') {
        $match = true;
      }

      $i = $match ? $i + 1 : $i;
    }

    return $i;
  }

  /**
   * Counts missing/total keys on target translation file
   * @param string $filename - file to be checked
   * @param string $languageid - language code
   * @return array (missing, total)
   */
  public function countTranslation($filename, $languageid) {

    $sourcelanguage = include($this->messagepath . '/' . $this->language . '/' . $filename);
    $targetlanguage = include($this->messagepath . '/' . $languageid . '/' . $filename);
    $countsource = count(array_keys($sourcelanguage));
    $counttarget = $this->countWhat($targetlanguage);
    $missing = $countsource - $counttarget;

    return array($missing, $countsource);
  }

}
