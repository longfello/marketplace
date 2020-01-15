<div class="main-search">
  <form action="<?=Yii::app()->createUrl('/store/category/search')?>" method="get" id="main_search_form">
    <div class="placeholder"><?=Yii::t('widget-search','Поиск по')?> <span><?=$itemsCount?></span> <?=Yii::t('widget-search','товарам')?></div>
    <input type="text" name="q" class="search" id="search_ac" value="<?=$query?>"/>
    <input type="submit" value="" class="search-icon"/>
  </form>
</div>

<?php

Yii::app()->clientScript->registerScript('search-autocomplete', "
$(document).ready(function(){
    $('#search_ac').autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: '".Yii::app()->createUrl('/store/category/ac')."',
          type: 'get',
          dataType: 'json',
          data: {
            maxRows: 12,
            'for': request.term
          },
          success: function( data ) {
            response( $.map( data, function( item ) {
              return {
                label: item,
                value: item
              }
            }));
          }
        });
      },
      minLength: 2,
      select: function( event, ui ) {
        if (ui.item) {
          $('#search_ac').val(ui.item.value);
          $('#main_search_form').submit();
        }
      }
    });
});
", CClientScript::POS_END);

