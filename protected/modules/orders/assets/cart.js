
/**
 * Activate +/- buttons
 */

$(document).ready(function(){
    $('button.plus').click(function(){
        var count = $(this).next('.count');
        $(count).val(parseInt($(count).val())+1);
        return false;
    });
    $('button.minus').click(function(){
        var count = $(this).prev('.count');
        var val   = parseInt($(count).val())-1;
        if(val < 1) val = 1;
        $(count).val(val);
        return false;
    });

    $(".order_data .delivery_desc").each(function(i,el){
      if ($(el).height() > 165) {
        $(el).addClass('shorten');
        var btn = $(el).parents('.order_data').find('.toggle_delivery_desc')
        $(btn).html($(btn).data('on')).show();
      }
    });

    $(".order_data .toggle_delivery_desc").click(function(e){
      e.preventDefault();
      var desc = $(this).parents('.order_data').find('.delivery_desc');
      $(desc).toggleClass('shorten');
      if ($(desc).hasClass('shorten')) {
        $(this).html($(this).data('on'));
      } else {
        $(this).html($(this).data('off'));
        $("html,body").animate({scrollTop: $(desc).offset().top}, 500);
      }
    });
});

/**
 * Recount total price on change delivery method
 * @param el
 */
function recountOrderTotalPrice(el)
{
    var total          = parseFloat(orderTotalPrice);
    var delivery_price = parseFloat($(el).attr('data-price'));
    var free_from      = parseFloat($(el).attr('data-free-from'));

    if(delivery_price > 0)
    {
        if(free_from > 0 && total > free_from)
        {
            // free delivery
        }else{
            total = total + delivery_price;
        }
    }

    $('#orderTotalPrice').html(total.toFixed(2));
}

function recountDeliveryPrice()
{
    var total = parseFloat(orderTotalPrice);
    $(".order_delivery option:selected").each(function(i,el) {

    var delivery_price = parseFloat($(el).attr('data-price'));
    var free_from      = parseFloat($(el).attr('data-free-from'));
    var market         = parseInt($(el).attr('data-market'));
    var market_price   = parseFloat($("#market_price_"+market).attr('data-price'));
    if(delivery_price > 0)
    {
      if(free_from > 0 && market_price > free_from)
      {
        // free delivery
      }else{
        market_price = market_price + delivery_price;
        total = total + delivery_price;
      }
    }
    $("#market_price_"+market).html(market_price.toFixed(2));
  });
  $('#orderTotalPrice').html(total.toFixed(2));
}

function showRecount(el) {
  $(el).parent('.cart-item_kol').find('.recount').removeClass('hidden');
}

function checkRecount(el) {
  var def = parseInt($(el).attr('data-kol'));
  var kol = parseInt($(el).val());
  if (def!=kol) {
    $(el).parent('.cart-item_kol').find('.recount').removeClass('hidden');
  } else {
    $(el).parent('.cart-item_kol').find('.recount').addClass('hidden');
  }
}