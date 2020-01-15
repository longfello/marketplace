<?php
/* @var Controller $this */
/* @var Gallery $gallery */

?>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#g1" data-toggle="tab"><?=Yii::t('GalleryModule', 'Главный слайдер')?></a></li>
    <li><a href="#g2" data-toggle="tab"><?=Yii::t('GalleryModule', 'Средний слайдер')?></a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="g1">
      <?php
        $this->widget('ext.galleryManager.GalleryManager', array(
          'gallery' => $gallery,
          'controllerRoute' => '/admin/gallery/default', //route to gallery controller
        ));

      ?>
    </div>
    <div class="tab-pane" id="g2">
      <?php
        $this->widget('ext.galleryManager.GalleryManager', array(
          'gallery' => $gallery2,
          'controllerRoute' => '/admin/gallery/default', //route to gallery controller
        ));
      ?>
    </div>
  </div>