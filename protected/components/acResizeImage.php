<?php

  /**
   * Ресайз изображений
   */
  class acResizeImage {
    /**
     * ширина изображения
     * @var int
     */
    public $width;
    /**
     * высота изображения
     * @var int
     */
    public $height;
    /**
     * Идентификатор ресурса обрабатываемого изображения
     * @var resource|bool
     */
    private $image;
    /**
     * тип изображения
     * @var string
     */
    private $type;

    /**
     * Инициализация объекта
     * @param $file string  Путь к временному файлу
     */
    function __construct($file) {
      if (@!file_exists($file)) {
        $file = Yii::app()->getBasePath().'/../img/site/nophoto.png';
      }
      if (!$this->setType($file)) {
        trigger_error("File is not an image");
      }
      $this->openImage($file);
      $this->setSize();
    }

    /**
     * Приватная функция, записывающая в поле type тип исходного изображения
     *
     * @param $file string  Путь исходного файла
     * @return boolean    TRUE, если файл является изображением. FALSE - в противном случае.
     */
    private function setType($file) {
      $info = getimagesize($file);
      switch ($info['mime']) {
        case 'image/jpeg':
          $this->type = "jpg";
          return TRUE;
        case 'image/png':
          $this->type = "png";
          return TRUE;
        case 'image/gif':
          $this->type = "gif";
          return TRUE;
        default:
          return FALSE;
      }
    }

    /**
     * Приватная функция, "открывающая" файл в зависимости от типа изображения.
     *
     * @param $file string  Путь исходного файла
     */
    private function openImage($file) {
      switch ($this->type) {
        case 'jpg':
          $this->image = @imagecreatefromjpeg($file);
          break;
        case 'png':
          $this->image = @imagecreatefrompng($file);
          break;
        case 'gif':
          $this->image = @imagecreatefromgif($file);
          break;
        default:
          exit("File is not an image");
      }
    }

    /**
     * Приватная функция, записывающая размеры исходного изображения
     */
    private function setSize() {
      $this->width = imagesx($this->image);
      $this->height = imagesy($this->image);
    }

    /**
     * Изменение размеров изображения
     *
     * Оба принимаемых параметра необязательны.
     * Если переданы и ширина и высота, изображение ужмётся в рамки.
     * Если передана лишь ширина - изображение сжимается по ней (аналогично и с высотой)
     *
     * @param bool|int $width  integer  Новая ширина изображения
     * @param bool|int $height integer  Новая высота изображения
     *
     * @return object       Текущий объект класса
     */
    function resize($width = FALSE, $height = FALSE, $enlarge = false) {
      /**
       * В зависимости от типа ресайза, запишем в $newSize новые размеры изображения.
       */
      if (is_numeric($width) && is_numeric($height) && $width > 0 && $height > 0) {
        $newSize = $this->getSizeByFramework($width, $height, $enlarge);
      } else if (is_numeric($width) && $width > 0) {
        $newSize = $this->getSizeByWidth($width);
      } else if (is_numeric($height) && $height > 0) {
        $newSize = $this->getSizeByHeight($height);
      } else {
        $newSize = array($this->width,
          $this->height);
      }
      $newImage = imagecreatetruecolor($newSize[0], $newSize[1]);
      //      $bg_color = imagecolorallocate($newImage, 255, 255, 255);
      //      imagefill($newImage, 1, 1, $bg_color);
      //завернуть в отдельную функцию, сжимающую изображение поэтапно
      imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $newSize[0], $newSize[1], $this->width, $this->height);
      $this->image = $newImage;
      $this->setSize();
      return $this;
    }

    /**
     * Приватная функция, определяющая размеры нового изображения при вписывании его в рамки.
     * Сжатие происходит пропорционально.
     *
     * @param $width  integer    Максимальная ширина нового изображения
     * @param $height integer  Максимальная высота нового изображения
     * @return array      Массив, содержащий размеры нового изображения
     */
    private function getSizeByFramework($width, $height, $enlarge = false) {
      if ($this->width <= $width && $this->height <= $height && !$enlarge) {
        return array($this->width,
          $this->height);
      }
      if ($this->width / $width > $this->height / $height) {
        $newSize[0] = $width;
        $newSize[1] = round($this->height * $width / $this->width);
      } else {
        $newSize[1] = $height;
        $newSize[0] = round($this->width * $height / $this->height);
      }
      return $newSize;
    }

    /**
     * Приватная функция, определяющая размеры нового изображения при сжатии по ширине.
     * Сжатие происходит пропорционально.
     *
     * @param $width integer    Максимальная ширина нового изображения
     * @return array      Массив, содержащий размеры нового изображения
     */
    private function getSizeByWidth($width) {
      if ($width >= $this->width) {
        return array($this->width,
          $this->height);
      }
      $newSize[0] = $width;
      $newSize[1] = round($this->height * $width / $this->width);
      return $newSize;
    }

    /**
     * Приватная функция, определяющая размеры нового изображения при сжатии по высоте.
     * Сжатие происходит пропорционально.
     *
     * @param $height integer  Максимальная высота нового изображения
     * @return array      Массив, содержащий размеры нового изображения
     */
    private function getSizeByHeight($height) {
      if ($height >= $this->height) {
        return array($this->width,
          $this->height);
      }
      $newSize[1] = $height;
      $newSize[0] = round($this->width * $height / $this->height);
      return $newSize;
    }

    /**
     * Функция для обрезки изображения.
     * Функция cropSave сохраняет обрезанное изображение и возвращает текущий объект класса acResizeImage.
     *
     * @param          $x0 integer  Координата x точки начала обрезки
     * @param          $y0 integer  Координата y точки начала обрезки
     * @param bool|int $w  integer  Ширина вырезаемой области
     * @param bool|int $h  integer  Высота вырезаемой области
     * @return object               Текущий объект класса
     */
    function crop($x0 = 0, $y0 = 0, $w = FALSE, $h = FALSE) {
      if (!is_numeric($x0) || $x0 < 0 || $x0 >= $this->width) {
        if ($this->width < $w) {
          $x0 = 0;
        } else {
          $x0 = round(($this->width - $w) / 2);
        }
      }
      if (!is_numeric($y0) || $y0 < 0 || $y0 >= $this->height) {
        if ($this->height < $h) {
          $y0 = 0;
        } else {
          $y0 = round(($this->height - $h) / 2);
        }
      }
      if (!is_numeric($w) || $w <= 0 || $w > $this->width - $x0) {
        $w = $this->width - $x0;
      }
      if (!is_numeric($h) || $h <= 0 || $h > $this->height - $y0) {
        $h = $this->height - $y0;
      }
      return $this->cropSave($x0, $y0, $w, $h);
    }

    /**
     * Приватная функция, сохраняющая обрезанное изображение.
     *
     * @param int $x0 смещение X
     * @param int $y0 смещение Y
     * @param int $w  ширина
     * @param int $h  высота
     * @return object Текущий объект класса
     */
    private function cropSave($x0, $y0, $w, $h) {
      $newImage = imagecreatetruecolor($w, $h);

      imagecopyresampled($newImage, $this->image, 0, 0, $x0, $y0, $w, $h, $w, $h);
      $this->image = $newImage;
      $this->setSize();
      return $this;
    }

    /**
     * Функция, вырезающая квадратную область из исходного изображения
     * Функция cropSave сохраняет обрезанное изображение и возвращает текущий объект класса acResizeImage.
     * Все параметры необязательны.
     * Если не было ничего передано, будет вырезана центральная максимальная квадратная область.
     *
     * @param bool|int $x0   integer    Координата x точки начала обрезки (по умолчанию - false)
     * @param bool|int $y0   integer    Координата y точки начала обрезки (по умолчанию - false)
     * @param bool|int $size integer    Сторона вырезаемой квадратной области
     * @return object                   Текущий объект класса
     */
    function cropSquare($x0 = FALSE, $y0 = FALSE, $size = FALSE) {
      if (!is_numeric($size) || $size <= 0) {
        $size = FALSE;
      }
      if (!is_numeric($x0) && !is_numeric($y0)) {
        if ($this->width < $this->height) {
          $x0 = 0;
          if (!$size || $size < $this->height) {
            $size = $this->width;
            $y0 = round(($this->height - $size) / 2);
          } else {
            $y0 = 0;
          }
        } else {
          $y0 = 0;
          if (!$size || $size < $this->width) {
            $size = $this->height;
            $x0 = round(($this->width - $size) / 2);
          } else {
            $x0 = 0;
          }
        }
      } else {
        if (!is_numeric($x0) || $x0 <= 0 || $x0 >= $this->width) {
          $x0 = 0;
        }
        if (!is_numeric($y0) || $y0 <= 0 || $y0 >= $this->height) {
          $y0 = 0;
        }
        if (!$size || $this->width < $size + $x0) {
          $size = $this->width - $x0;
        }
        if (!$size || $this->height < $size + $y0) {
          $size = $this->height - $y0;
        }
      }
      if ($this->height < $size && $this->width < $size) {
        return $this;
      } else {
        return $this->cropSave($x0, $y0, $size, $size);
      }
    }

    /**
     * Функция для создания миниатюр изображений.
     * Функция getSizeByThumbnail возвращает новые размеры изображения.
     *
     * @param $width  integer    Ширина миниатюры
     * @param $height integer    Высота миниатюры
     * @param $c      integer        Коэффициент превышения...
     * @return object            Текущий объект класса
     */
    function thumbnail($width, $height, $c = 2, $enlarge = false) {
      if (!is_numeric($width) || $width <= 0) {
        $width = $this->width;
      }
      if (!is_numeric($height) || $height <= 0) {
        $height = $this->height;
      }
      if (!is_numeric($c) || $c <= 1) {
        $c = 2;
      }
      $newSize = $this->getSizeByThumbnail($width, $height, $c, $enlarge = false);
      //    $newImage = imagecreatetruecolor($width, $height);
      $newImage = imagecreatetruecolor(min($width, $newSize[0]), min($newSize[1], $height));
      $white = imagecolorallocate($newImage, 255, 255, 255);
      imagefill($newImage, 1, 1, $white);
      imagecopyresampled($newImage, $this->image, (int)((min($width, $newSize[0]) - $newSize[0]) / 2), (int)((min($width, $newSize[1]) - $newSize[1]) / 2), 0, 0, $newSize[0], $newSize[1], $this->width, $this->height);
      $this->image = $newImage;
      $this->setSize();
      return $this;
    }

    /**
     * Приватная функция, определяющая размеры нового изображения при создании миниатюры.
     * Функция вписывает изображение в указанную область, стараясь максимально заполнить её.
     * Если после ужатия по одной из сторон, вторая сторона привышает размеры области более чем в $c раз,
     * то ужатие происходит по второй стороне.
     * Если же вторая сторона превышает размер области в 2 * $c раз, то стороны уменьшаются в 2 раза.
     * Если одна из сторон заведомо меньше соответствующей стороны области,
     * проверяется, не превышает ли другая сторона соответствующую сторону области в $c раз и 2 * $c.
     * Если ресайз производится не по ширине, а по высоте, переменные, содержащие размеры исходного изображения
     * и размеры миниатюры, меняются местами.
     * Также местами меняются мирина и высота в возвращаемом массиве.
     *
     * @param $width  integer    Максимальная ширина нового изображения
     * @param $height integer  Максимальная высота нового изображения
     * @param $c
     * @internal param $ $с integer      Коэффициент превышения...
     * @return array       Массив, содержащий размеры нового изображения
     */
    private function getSizeByThumbnail($width, $height, $c, $enlarge = false) {
      if ($this->width <= $width && $this->height <= $height && !$enlarge) {
        return array($this->width,
          $this->height);
      }
      $realW = $this->width;
      $realH = $this->height;

      $rotate = FALSE;

      if ($width / $realW >= $height / $realH) {
        $t = $realH;
        $realH = $realW;
        $realW = $t;
        $t = $width;
        $width = $height;
        $height = $t;
        $rotate = TRUE;
      }

      // $limX = $c * $width;
      $limY = $c * $height;
      $possH = $realH * $width / $realW;

      if ($realW > $width) {
        if ($possH <= $limY) {
          $newSize[0] = $width;
          $newSize[1] = round($possH);
        } else {
          if ($possH <= 2 * $limY) {
            $newSize[1] = $limY;
            $newSize[0] = $realW * $newSize[1] / $realH;
          } else {
            $newSize[0] = $width / 2;
            $newSize[1] = $realH * $newSize[0] / $realW;
          }
        }
      } else {
        if ($realH <= $limY) {
          $newSize[0] = $realW;
          $newSize[1] = $realH;
        } else {
          if ($realH <= 2 * $limY) {
            if ($realW * $limY / $realH >= $width / 2) {
              $newSize[1] = $limY;
              $newSize[0] = $realW * $limY / $realH;
            } else {
              $newSize[0] = $width / 2;
              $newSize[1] = $realH * $newSize[0] / $realW;
            }
          } else {
            $newSize[0] = $width / 2;
            $newSize[1] = $realH * $newSize[0] / $realW;
          }
        }
      }

      if (($possH < $height)) {
        //      $newSize[0] = $newSize[0]*$c;
        //      $newSize[1] = $newSize[1]*$c;
        //      $newSize[0] = ceil($newSize[0]*$height/$possH);
        //      $newSize[1] = ceil($newSize[1]*$height/$possH);
      }

      if ($rotate) {
        $t = $newSize[0];
        $newSize[0] = $newSize[1];
        $newSize[1] = $t;
      }
      return $newSize;
    }

    /**
     * Функция, сохраняющая изображение в файл
     *
     * @param             $path     string    Путь, по которому следует сохранить файл
     * @param             $fileName string  Имя нового файла
     * @param bool|string $type     string    Тип файла (по умолчанию (false) - тот же, что и у исходного изображения)
     * @param             $rewrite  boolean  Флаг, определяющий, можно ли перезаписывать файлы с одинаковыми именами
     * @param             $quality  integer  Качество изображения для JPG-файлов
     * @return string      Адрес нового файла
     */
    function save($path = '', $fileName, $type = FALSE, $rewrite = FALSE, $quality = 100) {
      if (trim($fileName) == '' || $this->image === FALSE) {
        return FALSE;
      }
      $type = strtolower($type);
      $savePath = $path . trim($fileName);
      switch ($type) {
        case FALSE:
          switch ($this->type) {
            case 'jpg':
              if (!$rewrite && @file_exists($savePath)) {
                return FALSE;
              }
              if (!is_numeric($quality) || $quality < 0 || $quality > 100) {
                $quality = 100;
              }
              imagejpeg($this->image, $savePath, $quality);
              return $savePath;
            case 'png':
              if (!$rewrite && @file_exists($savePath)) {
                return FALSE;
              }
              imagepng($this->image, $savePath);
              return $savePath;
            case 'gif':
              if (!$rewrite && @file_exists($savePath)) {
                return FALSE;
              }
              imagegif($this->image, $savePath);
              return $savePath;
            default:
              return FALSE;
          }
          break;
        case 'jpg':
//          $savePath = $path . trim($fileName) . "." . $type;
          if (!$rewrite && @file_exists($savePath)) {
            return FALSE;
          }
          if (!is_numeric($quality) || $quality < 0 || $quality > 100) {
            $quality = 100;
          }
          imagejpeg($this->image, $savePath, $quality);
          return $savePath;
        case 'png':
//          $savePath = $path . trim($fileName) . "." . $type;
          if (!$rewrite && @file_exists($savePath)) {
            return FALSE;
          }
          imagepng($this->image, $savePath);
          return $savePath;
        case 'gif':
//          $savePath = $path . trim($fileName) . "." . $type;
          if (!$rewrite && @file_exists($savePath)) {
            return FALSE;
          }
          imagegif($this->image, $savePath);
          return $savePath;
        default:
          return FALSE;
      }
    }

    public static function getCacheDir($filename){
      $cache_dir = dirname($filename);
      if(!is_dir($cache_dir)) {
        mkdir($cache_dir, 0777, true);
      }
      $cache_dir .= '/';
      return $cache_dir;
    }

    /**
     * Создать превью изображения
     *
     * @param string   $file     имя исходного файла
     * @param int      $width    ширина превью
     * @param int      $height   высота превью
     * @param int      $quality  качество
     * @param bool     $crop     обрезать?
     *
     * @return string            имя файла превью
     */
    public static function make_picture_preview($source, $target, $width = 660, $height = 300, $quality = 80, $crop = false, $enlarge = false) {
      $file      = $source;

      $img = new acResizeImage($file); //создали экземпляр класса

      if ($crop){
        if ($img->width > $img->height)
        {
          $img->resize(false, $width, $enlarge);
        } else $img->resize($width, false, $enlarge);
        $img->crop(false, false, $width, $height);
      }
      else
      {
        if ($img->width > $width) $img->resize($width, $height, $enlarge);
        $img->thumbnail($width, $height, 1.5, $enlarge); //вырезали квадратную область
      }
      $img->save(self::getCacheDir($target), basename($target), 'jpg', true, $quality); //сохранили
    }

    public static function put_picture_answer($sPath) {
      $efileinode = fileinode($sPath);
      $efilesize  = filesize($sPath);
      $efilemtime = filemtime($sPath);
      $efile_lm   = date('D, d M Y H:i:s \G\M\T', $efilemtime);
      $eTag = sprintf('%x-%x-%x', $efileinode, $efilesize, $efilemtime);
      $eTag = "\"$eTag\"";
      header("Etag: $eTag");
      header("Last-Modified: " . $efile_lm); // Время жизни сутки
      $head = getallheaders();
      if(!isset($head['If-None-Match']) || $head['If-None-Match'] != $eTag)
      {
        header('Content-Type: image/jpeg');
        readfile($sPath);
      } else {
        header('Not Modified',true,304);
      }
    }

  }