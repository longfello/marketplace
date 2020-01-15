jQuery(document).ready(function($) {
	$(".ul-title a.edit-info").click(function (e) {
		e.preventDefault();
		if ($(this).text() == "Свернуть") {
			$(this).text("Развернуть");
		}
		else {
			$(this).text("Свернуть");
		}
		
		
		$(this).parent().next("ul").toggle(500);
	});

  $('.filter_links').each(function(){
    if ($(this).find('.active').size() > 0) {
      $(this).show();
    }
  });

  $(".catalog-block-filters .ul-title span").click(function (e) {
		e.preventDefault();
		
		if ( $(this).parent().next('.filter_links').is(':visible')) {
      $(this).css("background", "url('/img/site/delta-down.png') no-repeat right center" );
		}
		else {
      $(this).css("background", "url('/img/site/delta.png') no-repeat right 7px");
		}
		
		$(this).parent().next("ul").toggle(500);
	}).each(function(){
    if ( $(this).parent().next('.filter_links').is(':visible')) {
      $(this).css("background", "url('/img/site/delta-down.png') no-repeat right center" );
    }
    else {
      $(this).css("background", "url('/img/site/delta.png') no-repeat right 7px");
    }
  });
  if ($(".main-search input.search").val()) {
    $('.placeholder').hide();
  }
  $(".main-search input.search").click(function () {
		$(".smart-search").toggle(500);
	})
	$(".search").click(function () {
		$(".placeholder").hide();
	});
	$('.placeholder').click(function() {
		$(this).hide();
	    $(this).siblings('input.search').focus();
	});
	$('input.search').keyup(function() {
	    $('.placeholder').hide();
	});
	$('.search').blur(function() {
	    var $this = $(this);
	    if($this.val().length == 0)
	        $(this).siblings('.placeholder').show();
	});
	$('.placeholder').blur();
});