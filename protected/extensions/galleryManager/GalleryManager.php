<?php
/**
 * Widget to manage gallery.
 * Requires Twitter Bootstrap styles to work.
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */
class GalleryManager extends CWidget
{
    /** @var Gallery Model of gallery to manage */
    public $gallery;
    /** @var string Route to gallery controller */
    public $controllerRoute = false;
    public $assets;

    public function init()
    {
        $this->assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
    }


    public $htmlOptions = array();


    /** Render widget */
    public function run()
    {
        /** @var $cs CClientScript */
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($this->assets . '/galleryManager.css');

        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('jquery.ui');

        $cs->registerScriptFile($this->assets . '/jquery.iframe-transport.js');
        Helper::registerJS('jquery.galleryManager.js');

        if ($this->controllerRoute === null)
            throw new CException('$controllerRoute must be set.', 500);

        $photos = array();
        foreach ($this->gallery->galleryPhotos as $photo) {
            $photos[] = array(
                'id' => $photo->id,
                'rank' => $photo->rank,
                'name_ru' => (string)$photo->name_ru,
                'name_en' => (string)$photo->name_en,
                'description_ru' => (string)$photo->description_ru,
                'description_en' => (string)$photo->description_en,
                'link_ru' => (string)$photo->link_ru,
                'link_en' => (string)$photo->link_en,
                'preview' => $photo->getPreview(),
            );
        }

        $lang = Yii::app()->i18n->supportedLanguages;
        sort($lang);
        $opts = array(
            'uploadUrl' => Yii::app()->createUrl($this->controllerRoute . '/ajaxUpload', array('gallery_id' => $this->gallery->id)),
            'deleteUrl' => Yii::app()->createUrl($this->controllerRoute . '/delete'),
            'updateUrl' => Yii::app()->createUrl($this->controllerRoute . '/changeData'),
            'arrangeUrl' => Yii::app()->createUrl($this->controllerRoute . '/order'),
            'nameLabel' => Yii::t('galleryManager.main', 'Name'),
            'descriptionLabel' => Yii::t('galleryManager.main', 'Description'),
            'photos' => $photos,
            'lang' => $lang
        );

        if (Yii::app()->request->enableCsrfValidation) {
            $opts['csrfTokenName'] = Yii::app()->request->csrfTokenName;
            $opts['csrfToken'] = Yii::app()->request->csrfToken;
        }
        $opts = CJavaScript::encode($opts);
        $cs->registerScript('galleryManager#' . $this->id, "$('#{$this->id}').galleryManager({$opts});");

        $this->htmlOptions['id'] = $this->id;
        $this->htmlOptions['class'] = 'GalleryEditor';

        $this->render('galleryManager');
    }

}
