<?php if (isset($items['items'])) {
  /*
  echo('<pre>');
    print_r($items['items']);
  echo('</pre>');
  */
  ?>
  <div class="catalog-block">
    <ul>
      <?php
        foreach($items['items'] as $item) {
          $class  = ($current == $item['id'])?'active':'appended';
          ?>
           <li <?=$class?"class='$class'":"";?>><a href="/<?=$item['url']['url']?>"><?=$item['label']?></a></li>
        <?php
        }
      ?>
    </ul>
    <?php if (count($items['items']) > 9) { ?>
      <div class="widget-catalog full"><a href="#"><?=Yii::t('widget-catalog','Раскрыть каталог')?></a></div>
    <?php } ?>
  </div>
<?php } ?>
