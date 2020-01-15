<?php

Yii::import('application.modules.users.models.User');

/**
 * This is the model class for table "user_profile".
 *
 * The followings are the available columns in table 'user_profile':
 * @property integer $id
 * @property integer $user_id
 * @property integer $city_id
 * @property string $full_name
 * @property string $phone
 * @property string $delivery_address
 * @property string $person
 * @property CUploadedFile $photo
 * @property string $birthday
 */
class UserProfile extends BaseModel {
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserProfile the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('full_name', 'required'),
			array('full_name, delivery_address, person, photo', 'length', 'max'=>255),
			array('birthday', 'length', 'max'=>10),
			array('phone', 'length', 'max'=>20),
      array('phone, person', 'required', 'on'=>'register_manager'),
      array('photo', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
			// Search
			array('id, user_id, city_id, full_name, phone, delivery_address, person, birthday', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id'          => Yii::t('UsersModule', 'Пользователь'),
			'full_name'        => Yii::t('UsersModule', 'Полное Имя'),
			'phone'            => Yii::t('UsersModule', 'Номер телефона'),
			'delivery_address' => Yii::t('UsersModule', 'Адрес доставки'),
			'person'           => Yii::t('UsersModule', 'Юр. лицо'),
			'photo'            => Yii::t('UsersModule', 'Фотография'),
			'birthday'         => Yii::t('UsersModule', 'Дата рождения'),
			'city_id'          => Yii::t('UsersModule', 'Город'),
		);
	}

	/**
	 * Connect profile to user
	 * @param UserProfile $user
	 */
	public function setUser(User $user)
	{
		$this->user_id = $user->id;
		$this->save(false);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('delivery_address',$this->delivery_address,true);
		$criteria->compare('person',$this->person,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


  public function afterSave(){
    parent::afterSave();
    if ($this->photo && isset($this->photo->name) && !$this->photo->error) {
      $this->photo->saveAs($this->getPhotoPath().'/'.$this->photo->name);
    }

    Yii::app()->user->location->save($this->city_id);
  }

  public function beforeSave(){
    $bd = strtotime($this->birthday);
    $this->birthday = $bd?date('Y-m-d', $bd):null;

    if (!$this->city_id) {
      $city = Helper::getCityIDByIp();
      $this->city_id = $city->id;
    }
    return parent::beforeSave();
  }

  public function afterFind(){
    $bd = strtotime($this->birthday);
    $this->birthday = $bd?date('d.m.Y', $bd):null;
  }

  /**
   * Get url to product image. Enter $size to resize image.
   * @param mixed $size New size of the image. e.g. '150x150'
   * @param mixed $resizeMethod Resize method name to override config. resize/adaptiveResize
   * @param mixed $random Add random number to the end of the string
   * @return string
   */
  public function getPhotoUrl($size = false, $resizeMethod = false, $random = false){
    $dirAlias = 'webroot.assets.photos';

    if($size !== false){
      $thumbPath = Yii::getPathOfAlias($dirAlias).'/'.$size;
      if(!file_exists($thumbPath))
        mkdir($thumbPath, 0777, true);

      $name = $this->photo;
      // Path to source image
      $fullPath  = $this->getPhotoPath().'/'.$name;
      // Path to thumb
      $thumbPath = $thumbPath.'/'.$name;


      if (!is_file($fullPath)) {
        $fullPath = Yii::app()->basePath.'/../img/site/nophoto.gif';
        $name = 'nophoto.gif';
        $thumbPath = $thumbPath.'/'.$name;
      }

      if(!file_exists($thumbPath))
      {
        // Resize if needed
        Yii::import('ext.phpthumb.PhpThumbFactory');
        $sizes  = explode('x', $size);
        if (count ($sizes) == 1) $sizes[1] = $sizes[0];
        $thumb  = PhpThumbFactory::create($fullPath);

        if($resizeMethod === false)
          $resizeMethod = 'adaptiveResize'; // resize || adaptiveResize
        $thumb->$resizeMethod($sizes[0],$sizes[1])->save($thumbPath);
      }
      return Yii::app()->assetManager->baseUrl.'/photos/'.$size.'/'.$name;
    }

    if ($random === true)
      return StoreImagesConfig::get('url').$this->name.'?'.rand(1, 10000);
    return StoreImagesConfig::get('url').$this->name;
  }

  protected function getPhotoPath(){
    $quantifier = substr($this->id, 0, 1);
    $path = Yii::app()->getBasePath().'/../uploads/user/'.$quantifier.'/'.$this->id;

    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }
    return $path;
  }

}