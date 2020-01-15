<?php /* @var $this cityPicker */
Yii::app()->clientScript->registerScript('city-autocomplete', "
$(document).ready(function(){
    $('#city-ac').autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: '".Yii::app()->createUrl('/api/acCity')."',
          type: 'get',
          dataType: 'json',
          data: {'for': request.term},
          success: function( data ) {
            response( $.map( data, function( item ) {
              return {
                label: item.name,
                id: item.id,
                country: item.country,
                region: item.region
              }
            }));
          }
        });
      },
      minLength: 2,
      select: function( event, ui ) {
        if (ui.item) {
          $('#".$this->el_id."').val(ui.item.id);
        }
      },
      create: function () {
        $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
          return $('<li>')
            .append('<a>' + item.label +' <span class=\"region\">' + item.region + '</span>'+ '<span class=\"country\">' + item.country + '</span></a>')
            .appendTo(ul);
        };
      }
    });
});
", CClientScript::POS_END);
?>
<input type="hidden" id="<?=$this->el_id?>" name="<?=get_class($this->model)?>[<?=$this->field?>]" value="<?=$this->model->{$this->field}?>">
<input type='text' id="city-ac" value="<?= Yii::app()->user->location->city->name ?>" class="form-control">