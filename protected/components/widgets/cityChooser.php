<?php

  class cityChooser extends CWidget {
    public $layout = 'index';
    /* @var UserLocation */
    public $location;
    /* @var NetCity[]*/
    public $defaultCities;
    public $defaultCitiesId = array(23541, 32497, 258544, 374482, 39930, 52282, 39921, 51867, 50349, 53112, 284360, 39011);

    function run(){
      $criteria = new CDbCriteria();
      $criteria->addInCondition("id", $this->defaultCitiesId);
      $this->defaultCities = NetCity::model()->findAll($criteria);

      Yii::app()->clientScript->registerScript('city-autocomplete2', "
$(document).ready(function(){
    $('.choose-city')
      .on('click', '.yes', function(){ $('.choose-city').hide(); $('body').removeClass('popup'); })
      .on('click', '.no', function(){ $('.choose-city .yes-ext').hide(); $('.choose-city .no-ext').show(); });
    $('.current-location').on('click', function(e){
      e.preventDefault();
      $('body').addClass('popup');
      $('.choose-city').show();
    });

    $('.cityChooser-list a').on('click', function(e){
      e.preventDefault();
      applyCityChoose($(this).data('id'));
    })

    $('#city-acp').autocomplete({
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
          $('#city-acp-id').val(ui.item.id);
          applyCityChoose(ui.item.id);
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
  function applyCityChoose(id){
    $.get('/api/setCity/id/'+id, function(){
      location.reload();
    });
  }
", CClientScript::POS_END);
      $this->location = Yii::app()->user->location;
      $this->render('cityChooser/'.$this->layout);
    }
  }