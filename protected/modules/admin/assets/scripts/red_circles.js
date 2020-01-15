/**
 * Display and prosess counters for orders and comments.
 */
$(function(){
    $('.hasRedCircle').each(function(i, el){
        $(el).append('<div class="circle_label"></div>');
    });

    setInterval(function(){
        reloadCounters();
    }, 5000);

    function reloadCounters()
    {
        $.getJSON('/admin/core/ajax/getCounters?' + Math.random(), function(data){
            if(data.orders > 0)
            {
                $('.circle-orders .circle_label').html(data.orders).show();
            }else{
                $('.circle-orders .circle_label').hide();
            }

            if(data.comments > 0)
            {
                $('.circle-comments .circle_label').html(data.comments).show();
            }else{
                $('.circle-comments .circle_label').hide();
            }

            var overall_catalog = 0;
            var el = $('.store-admin-import-menu-item span');
            if (el.size()){
              if (!$(el).find('i').size()) {
                $(el).append('<i style="float: right; display: block;"></i>');
              }
              if(data.catalog)
              {
                overall_catalog += data.catalog;
                $(el).find('i').html(data.catalog).show();
              } else {
                $(el).find('i').hide();
              }
            }
            var el = $('.store-admin-market-menu-item span');
            if (el.size()){
              if (!$(el).find('i').size()) {
                $(el).append('<i style="float: right; display: block;"></i>');
              }
              if(data.market)
              {
                overall_catalog += data.market;
                $(el).find('i').html(data.market).show();
              } else {
                $(el).find('i').hide();
              }
            }

            if (overall_catalog) {
              $('.circle-catalog .circle_label').html(overall_catalog).show();
            } else {
              $('.circle-catalog .circle_label').hide();
            }
        });
    }

    reloadCounters();
});