<?php
  /**
   * @var $this catalogFilter
   */
?>
<div class="catalog-block-filters">
  <div class="ul-title"><?=Yii::t('widget-catalogFilter','Показать')?></div>
  <?php if ($manufacturers['filters']){ ?>
    <div class="ul-title"><span><?=Yii::t('widget-catalogFilter','Брэнды')?></span></div>
    <ul>
      <?php
        foreach($manufacturers['filters'] as $filter) {
          echo CHtml::openTag('li', array('class' => $filter['active']?'active':''));

          $url = Yii::app()->request->addUrlParam('/store/category/view', array($filter['queryKey'] => $filter['queryParam']), $manufacturers['selectMany']);
          $queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));
          // Create link to clear current filter

          if(in_array($filter['queryParam'], $queryData)){
            $url = Yii::app()->request->removeUrlParam('/store/category/view', $filter['queryKey'], $filter['queryParam']);
          }
          $title = CHtml::link($filter['title'], $url); //.' ('.$filter['count'].')';
          echo CHtml::link($title, $url, array('style'=>'color:green'));

          echo CHtml::closeTag('li');
        }
      ?>
    </ul>
  <?php } ?>

  <?php if (isset($items['items'])) { ?>
  <div class="ul-title"><span><?=Yii::t('widget-catalogFilter','Категории')?></span></div>
  <ul>
    <?php
      foreach($items['items'] as $item) {
        ?>
        <li><a href="/<?=$item['url']['url']?>"><?=$item['label']?></a></li>
      <?php
      }
    ?>
  </ul>
  <? } ?>


  <?php

    foreach($attributes as $attrData)
    {
      $overall_count = 0;
      foreach($attrData['filters'] as $filter) {
        $overall_count += $filter['count'];
      }

      if ($overall_count > 0) {
        echo CHtml::openTag('div', array('class'=>'ul-title'));
        echo CHtml::openTag('span');
        echo CHtml::encode($attrData['title']);
        echo CHtml::closeTag('span');
        echo CHtml::closeTag('div');

        echo CHtml::openTag('ul', array('class'=>'filter_links', 'style'=>"display: none;"));

        foreach($attrData['filters'] as $filter){
          if ($filter['count'] > 0) {
            $url = Yii::app()->request->addUrlParam('/store/category/view', array($filter['queryKey'] => $filter['queryParam']), $attrData['selectMany']);
            $queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));

            echo CHtml::openTag('li', in_array($filter['queryParam'], $queryData)?array('class' => 'active'):array());
            // Filter link was selected.
            if(in_array($filter['queryParam'], $queryData))
            {
              // Create link to clear current filter
              $url = Yii::app()->request->removeUrlParam('/store/category/view', $filter['queryKey'], $filter['queryParam']);
              echo CHtml::link($filter['title'], $url);
            }
            elseif($filter['count'] > 0)
              echo CHtml::link($filter['title'], $url).' ('.$filter['count'].')';
            else
              echo $filter['title'].' (0)';

            echo CHtml::closeTag('li');
          }
        }
        echo CHtml::closeTag('ul');
      }
    }
  ?>


  <div class="ul-title"><span><?=Yii::t('widget-catalogFilter','Рейтинг')?></span></div>
  <ul class="ul-rating">
    <?php
      foreach($fRating['filters'] as $filter) {
        $url = Yii::app()->request->addUrlParam('/store/category/view', array($filter['queryKey'] => $filter['queryParam']), true);
        $queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));
        // Create link to clear current filter

        if(in_array($filter['queryParam'], $queryData)){
          $url = Yii::app()->request->removeUrlParam('/store/category/view', $filter['queryKey'], $filter['queryParam']);
        }
        $class = $filter['active']?'active':'';
    ?>
    <li class="<?=$class?>">
      <a class="rating" href="<?=$url?>">
        <span><?=$filter['title']?></span>
        <i class="star star-<?=$filter['queryParam']?>"></i>
      </a>
    </li>
    <?php } ?>
  </ul>

  <!--
  <div class="ul-title"><span>Доступность</span></div>
  <ul>
    <li><a href="#">На складе</a></li>
    <li><a href="#">Под заказ</a></li>
    <li><a href="#">Нет в продаже</a></li>
  </ul>
  !-->

</div>