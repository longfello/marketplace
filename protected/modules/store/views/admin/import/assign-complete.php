<?php
  /* @var Controller $this */
  $this->pageHeader = Yii::t('StoreModule', 'Импорт товаров - сопоставление данных:').' '.
    CHtml::link(Yii::t('StoreModule','Старт'), $this->createUrl('/admin/store/import/assign/'.$sid)).' > '.
    CHtml::link(Yii::t('StoreModule','Категории'), $this->createUrl('/admin/store/import/assignCategories/'.$sid)).' > '.
    CHtml::link(Yii::t('StoreModule','Производители'), $this->createUrl('/admin/store/import/assignVendors/'.$sid)).' > '.
    CHtml::link(Yii::t('StoreModule','Атрибуты'), $this->createUrl('/admin/store/import/assignOptions/'.$sid)).' > '.
    CHtml::link(Yii::t('StoreModule','Финиш'));

  $this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('StoreModule', 'Импорт товаров') => $this->createUrl('/admin/store/import'),
    Yii::t('StoreModule', 'Сопоставление данных'),
  );
?>

<div class="form wide padding-all">
  <?= Yii::t('StoreModule', 'Вступительный текст завершения настройки источника импорта') ?>
  <ul class="padding-all">
    <?php foreach($totals as $name => $count) { ?>
      <li><?=$name?> <?=$count?></li>
    <?php } ?>
  </ul>
    <?php if (Yii::app()->user->getIsSuperuser()) { ?>
      <?php
      echo CHtml::form($this->createUrl('/admin/store/import/assignComplete/id/'.$sid.'/approve/send'));
      ?>
      <div class="row-fluid">
        <label for="solution-comment">Пояснения к решению</label>
        <textarea name="comment" id='solution-comment' class="form-control"><?=$model->comment?></textarea>
      </div>
      <br>
      <a class="btn btn-success" href="<?=$this->createUrl('/admin/store/import')?>">&lAarr; Вернутся к перечню источников</a>
      <button class="btn btn-danger"  type='submit' name='solution' value='<?=StoreImportSources::STATUS_ERRORS?>'>На доработку</button>
      <button class="btn btn-primary"  type='submit' name='solution' value='<?=StoreImportSources::STATUS_APPROVED?>'>Утвердить</button>
      <?php
        echo CHtml::endForm();
      ?>
    <?php } else { ?>
      <a class="btn btn-success" href="<?=$this->createUrl('/admin/store/import')?>">&lAarr; Вернутся к перечню источников</a>
      <a class="btn btn-primary" href="<?=$this->createUrl('/admin/store/import/assignComplete/id/'.$sid.'/approve/send')?>">Отправить на утвержднние &rAarr;</a>
    <?php } ?>

</div>
