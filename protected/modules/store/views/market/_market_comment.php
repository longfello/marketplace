<?php

/**
 * @var StoreProduct $data
 */
?>

<div class="feedback">
  <div class="feedback-author">
    <?=$data->name?>
    <div class="feedback-date"><?=date('d.m.Y,  H:i', CDateTimeParser::parse($data->created,'yyyy-MM-dd hh:mm:ss')); ?></div>
  </div>
  <div class="feedback-body">
    <div class="shape"></div>
    <?=$data->text?>
  </div>
</div>