<?php /* @var News $news */ ?>
<?php /* @var Controller $this */ ?>

<div class="button gradient"><?= Yii::t('widget-newsTeaser','News title')?></div>

<div class="news-block">
  <?php foreach($news as $one): ?>
    <div class="news">
      <div class="news-title"><?=$one->title?></div>
      <div class="news-body"><?=strip_tags($one->teaser)?></div>
      <div class="read-more"><a href="<?=$one->getUrl()?>"><?= Yii::t('widget-newsTeaser','read more')?></a></div>
    </div>
  <? endforeach ?>
  <div class="full"><a href="<?=Yii::app()->controller->createUrl('/news')?>"><?= Yii::t('widget-newsTeaser','read all')?></a></div>
</div>
