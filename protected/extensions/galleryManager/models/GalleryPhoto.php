<?php

/**
 * This is the model class for table "gallery_photo".
 *
 * The followings are the available columns in table 'gallery_photo':
 * @property integer $id
 * @property integer $gallery_id
 * @property integer $rank
 * @property string $name_ru
 * @property string $description_ru
 * @property string $name_en
 * @property string $description_en
 * @property string $file_name
 * @property string $link_ru
 * @property string $link_en
 *
 * The followings are the available model relations:
 * @property Gallery $gallery
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */
class GalleryPhoto extends CMultiLangActiveRecord
{

    public $MultiLangFields = array('name', 'description', 'link');

    /** @var string Extensions for gallery images */
    public $galleryExt = 'jpg';
    /** @var string directory in web root for galleries */
    public $galleryDir = 'img/site/gallery';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GalleryPhoto the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        if ($this->dbConnection->tablePrefix !== null)
            return '{{gallery_photo}}';
        else
            return 'gallery_photo';

    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('gallery_id', 'required'),
//            array('gallery_id, rank', 'numerical', 'integerOnly' => true),
            array('name_ru, name_en', 'length', 'max' => 512),
            array('file_name', 'length', 'max' => 128),
            array('link_ru, link_en', 'length', 'max' => 128),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, gallery_id, rank, name, description_ru, description_en, file_name, link', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'gallery' => array(self::BELONGS_TO, 'Gallery', 'gallery_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'gallery_id' => 'Gallery',
            'rank' => 'Rank',
            'name_ru' => 'Name',
            'name_en' => 'Name',
            'description_ru' => 'Description',
            'description_en' => 'Description',
            'file_name' => 'File Name',
            'link_ru' => 'Link',
            'link_en' => 'Link'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('gallery_id', $this->gallery_id);
        $criteria->compare('rank', $this->rank);
        $criteria->compare('name_ru', $this->name_ru, true);
        $criteria->compare('name_en', $this->name_en, true);
        $criteria->compare('description_ru', $this->description_ru, true);
        $criteria->compare('description_en', $this->description_en, true);
        $criteria->compare('file_name', $this->file_name, true);
        $criteria->compare('link_ru', $this->link_ru, true);
        $criteria->compare('link_en', $this->link_en, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function save($runValidation = true, $attributes = null)
    {
        parent::save($runValidation, $attributes);
        if ($this->rank == null) {
            $this->rank = $this->id;
            $this->setIsNewRecord(false);
            $this->save(false);
        }
        return true;
    }

    public function getPreview()
    {
        return Yii::app()->request->baseUrl . '/' . $this->galleryDir . '/_' . $this->getFileName('') . '.' . $this->galleryExt;
    }

    private function getFileName($version = '')
    {
        return $this->id . $version;
    }

    public function getUrl($version = '')
    {
        return Yii::app()->request->baseUrl . '/' . $this->galleryDir . '/' . $this->getFileName($version) . '.' . $this->galleryExt;
    }

    public function setImage($path)
    {
        //save image in original size
        Yii::app()->image->load($path)->save(Yii::getPathOfAlias('webroot') . '/' .$this->galleryDir . '/' . $this->getFileName('') . '.' . $this->galleryExt);
        //create image preview for gallery manager
        Yii::app()->image->load($path)->resize(300, null)->save(Yii::getPathOfAlias('webroot') . '/' .$this->galleryDir . '/_' . $this->getFileName('') . '.' . $this->galleryExt);

        $this->updateImages();
    }

    public function delete()
    {
        $this->removeFile(Yii::getPathOfAlias('webroot') . '/' . $this->galleryDir . '/' . $this->getFileName('') . '.' . $this->galleryExt);
        $this->removeFile(Yii::getPathOfAlias('webroot') . '/' . $this->galleryDir . '/_' . $this->getFileName('') . '.' . $this->galleryExt);

        $this->removeImages();
        return parent::delete();
    }

    private function removeFile($fileName)
    {
        if (file_exists($fileName))
            @unlink($fileName);
    }

    public function removeImages()
    {
        foreach ($this->gallery->versions as $version => $actions) {
            $this->removeFile(Yii::getPathOfAlias('webroot') . '/' .$this->galleryDir . '/' . $this->getFileName($version) . '.' . $this->galleryExt);
        }
    }

    /**
     * Regenerate image versions
     */
    public function updateImages()
    {
        foreach ($this->gallery->versions as $version => $actions) {
            $this->removeFile(Yii::getPathOfAlias('webroot') . '/' .$this->galleryDir . '/' . $this->getFileName($version) . '.' . $this->galleryExt);

            $image = Yii::app()->image->load(Yii::getPathOfAlias('webroot') . '/' .$this->galleryDir . '/' . $this->getFileName('') . '.' . $this->galleryExt);
            foreach ($actions as $method => $args) {
                call_user_func_array(array($image, $method), is_array($args) ? $args : array($args));
            }
            $image->save(Yii::getPathOfAlias('webroot') . '/' .$this->galleryDir . '/' . $this->getFileName($version) . '.' . $this->galleryExt);
        }
    }


}