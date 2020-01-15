<div class="slider-controller">
  <div class="front-sort">
    <?php
      $first = true;
      foreach($lists as $list) {
        if ($list['content']) {
          ?><a href="#list-<?=$list['slug']?>" class="fresh <?=$first?'sort-active':''?> gradient-grey"><?=$list['title']?></a><?php
          $first = false;
        }
      }

    ?>
  </div>
  <div class="lists-wrapper">
    <?php
      $first = true;
      foreach($lists as $list) {
        if ($list['content']) {
          ?>
          <div class="items <?=$first?'sort-active':''?>" id="list-<?=$list['slug']?>">
            <div class="slider-wrapper">
              <?php
                foreach($list['content'] as $p)
                  Yii::app()->controller->renderPartial('_product', array('data'=>$p));
              ?>
            </div>
          </div>
          <?php
          $first = false;
        }
      }

    ?>
  </div>
</div>
