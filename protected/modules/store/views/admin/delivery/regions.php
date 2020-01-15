<?php
  /*
   * var $this Controller
   * var $model StoreDeliveryMethod
   *
   */
?>

  <div class="DeliveryRegions">
    <ul class="current-regions">
      <?php
        foreach($model->regions as $region) {
          /* @var $region StoreDeliveryRegion */
          ?>
            <li>
              <input type="hidden" name="StoreDeliveryRegion[type][]" value="<?=$region->type?>">
              <input type="hidden" name="StoreDeliveryRegion[object_id][]" value="<?=$region->object_id?>">
              <?=$region->name?>
              <a href="#" class="remove"></a>
            </li>
          <?php
        }
      ?>
    </ul>

    <a class="add btn btn-primary"><?=Yii::t('StoreMarket', 'Добавить регион')?></a>
    <div class="modal fade" id="addModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?=Yii::t('StoreModule', 'Добавить регион')?></h4>
          </div>
          <div class="modal-body">
            <form class="form" role="form">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"><?=Yii::t('StoreModule', 'Тип доставки')?></label>
                <div class="col-sm-10">
                  <select class="form-control" id="region_type">
                    <option value="<?=StoreDeliveryRegion::TYPE_ALL?>"><?=Yii::t('StoreModule', 'Доставка во все регионы')?></option>
                    <option value="<?=StoreDeliveryRegion::TYPE_COUNTRY?>"><?=Yii::t('StoreModule', 'Доставка по стране')?></option>
                    <option value="<?=StoreDeliveryRegion::TYPE_REGION?>"><?=Yii::t('StoreModule', 'Доставка по региону')?></option>
                    <option value="<?=StoreDeliveryRegion::TYPE_CITY?>"><?=Yii::t('StoreModule', 'Доставка по городу')?></option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-10">
                  <select class="hidden" id="region_country">
                    <?php
                      $countries = NetCountry::model()->orderByName()->findAll();
                      foreach($countries as $country){
                    ?>
                      <option value="<?=$country->id?>"><?=$country->name?></option>
                    <?php } ?>
                  </select>
                  <select class="hidden" id="region_region"></select>
                  <select class="hidden" id="region_city"></select>
                </div>
              </div>
            </form>
            <div class="clearfix"></div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=Yii::t('StoreModule', 'Закрыть')?></button>
            <button type="button" class="btn btn-primary btn-add"><?=Yii::t('StoreModule', 'Добавить')?></button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>
<?php

  Yii::app()->clientScript->registerScript('deliveryRegions', "
$(document).ready(function(){
  applyRegions();
  $('.DeliveryRegions').on('click', 'a.remove', function(e){
    e.preventDefault();
    $(this).parents('li').remove();
    applyRegions();
  })
  $('.DeliveryRegions a.add').on('click', function(e){
    e.preventDefault();
    $('#region_type').val('".StoreDeliveryRegion::TYPE_ALL."');
    $('#addModal').modal();
  })
  $('.DeliveryRegions .btn-add').on('click', function(e){
    var t = $('#region_type').val();
    var o = 0;
    var name = '".Yii::t('StoreModule', 'Доставка во все регионы')."';
    switch(t){
      case '".StoreDeliveryRegion::TYPE_COUNTRY."':
        name = '".Yii::t('StoreModule', 'Доставка по стране')."' + ' (' + $('#region_country option:selected').text() +')';
        o = $('#region_country').val();
        break;
      case '".StoreDeliveryRegion::TYPE_REGION."':
        name = '".Yii::t('StoreModule', 'Доставка по региону')."' + ' (' + $('#region_region option:selected').text() +')';
        o = $('#region_region').val();
        break;
      case '".StoreDeliveryRegion::TYPE_CITY."':
        name = '".Yii::t('StoreModule', 'Доставка по городу')."' + ' (' + $('#region_city option:selected').text() +')';
        o = $('#region_city').val();
        break;
    }
    $('ul.current-regions').append($('<li>').append(
      '<input type=\"hidden\" name=\"StoreDeliveryRegion[type][]\" value=\"'+t+'\">'+
      '<input type=\"hidden\" name=\"StoreDeliveryRegion[object_id][]\" value=\"'+o+'\">'+
      name+
      '<a href=\"#\" class=\"remove\"></a>'));
    applyRegions();
  });
  $('#region_type').on('change', function(e){
    switch($(this).val()){
      case '".StoreDeliveryRegion::TYPE_ALL."':
        $('#region_country, #region_region, #region_city').addClass('hidden');
        break;
      case '".StoreDeliveryRegion::TYPE_COUNTRY."':
        $('#region_country').removeClass('hidden');
        $('#region_region, #region_city').addClass('hidden');
        break;
      case '".StoreDeliveryRegion::TYPE_REGION."':
        $('#region_country, #region_region').removeClass('hidden');
        $('#region_city').addClass('hidden');
        break;
      case '".StoreDeliveryRegion::TYPE_CITY."':
        $('#region_country, #region_region, #region_city').removeClass('hidden');
        break;
    }
  });

  $('#region_country').on('change', function(e){
    reload_region();
  });

  $('#region_region').on('change', function(e){
    reload_city();
  });
});

  function reload_region(){
    $('#region_region').load('".$this->createUrl('/api/loadRegions')."?cid='+$('#region_country').val(), function(){
      reload_city();
    });
  }

  function reload_city(){
    $('#region_city').load('".$this->createUrl('/api/loadCities')."?rid='+$('#region_region').val());
  }

  function applyRegions(){
    $('#deliveryUpdateForm .regions-data').remove();
    $('#deliveryUpdateForm').append('<div class=\"regions-data hidden\"></div>');
    $('.DeliveryRegions .current-regions input').clone().appendTo('#deliveryUpdateForm .regions-data');
  }
");