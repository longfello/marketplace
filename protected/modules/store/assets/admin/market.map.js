$(document).ready(function(){
  $("#marketUpdateForm").after("<div id='map' style='margin: 0 auto;'></div>");
  var count = 0, current;
  var menu = new Gmap3Menu($("#map"));

  menu.add("Поставить маркер", "itemAdd",
    function(){
      menu.close();

      count++;
      $("#myModal").data('current', current);
      $("#myModal").data('count', count);
      $('#myModal').modal({
        keyboard: false
      });
      $( "#city-ac" ).autocomplete( "option", "appendTo", "#marker_form .form-group-ac" );
    }
  );

  var lat = '55.7642131648377';
  var lng = '37.6171875';

  // INITIALIZE GOOGLE MAP
  $("#map").width("800px").height("600px").gmap3(
    {
      map:{
        options:{
          center:[lat, lng],
          zoom: 4
        },
        events:{
          rightclick:function(map, event){
            current = event;
            menu.open(current);
          },
          click: function(){
            menu.close();
          },
          dragstart: function(){
            menu.close();
          },
          zoom_changed: function(){
            menu.close();
          }
        }
      }
    }
  );

  $("#store_markers .one_marker").each(function(i, el){
    count++;
    var lat = $(el).data('lat');
    var lng = $(el).data('lng');
    var name = $(el).data('name');
    var address = $(el).data('address');
    var phone = $(el).data('phone');
    var city_id = $(el).data('city');
    var cityname = $(el).data('cityname');

    var id = 'marker_'+count;
    $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][lat]' id='input_"+id+"' class='direction' value='"+lat+"'>");
    $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][lng]' id='input_"+id+"' class='direction' value='"+lng+"'>");
    $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][name]' id='input_"+id+"' value='"+name+"'>");
    $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][address]' id='input_"+id+"' value='"+address+"'>");
    $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][phone]' id='input_"+id+"' value='"+phone+"'>");
    $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][city_id]' id='input_"+id+"' value='"+city_id+"'>");

    var text = name+"</br>"+address+"</br>"+phone+"</br>"+cityname;

    // add marker and store it
    $("#map").gmap3({
      marker:{
        latLng: [lat,lng],
        options:{
          draggable:true,
          label: name
        },
        events: {
          rightclick:function(marker, event, context){
            $("#map").gmap3({clear: context.id});
            $("#input_"+context.id).remove();
          },
          mouseover: function(marker, event, context){
            var map = $(this).gmap3("get"),
              infowindow = $(this).gmap3({get:{name:"infowindow"}});

            if (marker.label.length>0) {
              if (infowindow){
                infowindow.open(map, marker);
                infowindow.setContent(text);
              } else {
                $(this).gmap3({
                  infowindow:{
                    anchor:marker,
                    options:{content: text}
                  }
                });
              }
            }
          },
          mouseout: function(){
            var infowindow = $(this).gmap3({get:{name:"infowindow"}});
            if (infowindow){
              infowindow.close();
            }
          },
          dragend: function(marker, event, context){
            $("#input_"+context.id+".direction").remove();
            $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][lat]' id='input_"+id+"' class='direction' value='"+marker.getPosition().k+"'>");
            $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][lng]' id='input_"+id+"' class='direction' value='"+marker.getPosition().A+"'>");
          }
        },
        id: id
      }
    });
  })
});

function addMarker() {
  var m_current = $("#myModal").data('current');
  var m_count = $("#myModal").data('count');

  var data = $("#marker_form").blur().serializeArray();
  var city_id = $("#UserProfile_city_id").val();
  var city_name = $("#city-ac").val();
  var id = 'marker_'+m_count;

  $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][lat]' id='input_"+id+"' class='direction' value='"+m_current.latLng.k+"'>");
  $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][lng]' id='input_"+id+"' class='direction' value='"+m_current.latLng.A+"'>");
  $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][city_id]' id='input_"+id+"' value='"+city_id+"'>");
  var text = '';
  console.log(data);
  for (var obj in data) {
    if (data[obj].name != "UserProfile[city_id]") {
      $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"]["+data[obj].name+"]' id='input_"+id+"' value='"+data[obj].value+"'>");
      text += data[obj].value+"</br>";
    }
  }
  text += city_name;

  // add marker and store it
  $("#map").gmap3({
    marker:{
      latLng: m_current.latLng,
      options:{
        draggable:true,
        label: data[0].value
      },
      events: {
        rightclick:function(marker, event, context){
          $("#map").gmap3({clear: context.id});
          $("#input_"+context.id).remove();
        },
        mouseover: function(marker, event, context){
          var map = $(this).gmap3("get"),
            infowindow = $(this).gmap3({get:{name:"infowindow"}});

          if (marker.label.length>0) {
            if (infowindow){
              infowindow.open(map, marker);
              infowindow.setContent(text);
            } else {
              $(this).gmap3({
                infowindow:{
                  anchor:marker,
                  options:{content: text}
                }
              });
            }
          }
        },
        mouseout: function(){
          var infowindow = $(this).gmap3({get:{name:"infowindow"}});
          if (infowindow){
            infowindow.close();
          }
        },
        dragend: function(marker, event, context){
          $("#input_"+context.id+".direction").remove();
          $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][lat]' id='input_"+id+"' class='direction' value='"+marker.getPosition().k+"'>");
          $("#marketUpdateForm").append("<input type='hidden' name='marker["+id+"][lng]' id='input_"+id+"' class='direction' value='"+marker.getPosition().A+"'>");
        }
      },
      id: id
    }
  });


  $("#marker_form input").val('');
  $('#myModal').modal('hide');
}

