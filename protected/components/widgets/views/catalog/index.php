<?php if (isset($items['items'])) {  ?>
  <div class="catalog-block">
    <ul>
      <?php
        $i = 0;
        foreach($items['items'] as $item) {
          $i++;
          $class  = ($current == $item['id'])?'active':'';
          $class .= ($i > 9)?' appended':'';
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
