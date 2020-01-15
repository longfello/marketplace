<?php
  /**
   * @var StoreMarket $data
   * @var Controller $this
   */
?>

<div class="market-item">
  <div class="title"><a href="<?=$this->createUrl('view', array('id'=>$data->id))?>"><?=$data->name?></a></div>
  <div class="description"><?=$data->description?></div>
</div>