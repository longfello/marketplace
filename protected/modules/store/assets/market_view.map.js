$(document).ready(function(){

  var count = 0;
  var lat = '55.7642131648377';
  var lng = '37.6171875';

  // INITIALIZE GOOGLE MAP
  $("#map").width("640px").height("480px").gmap3(
    {
      map:{
        options:{
          center:[lat, lng],
          zoom: 4
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
    var cityname = $(el).data('cityname');

    var id = 'marker_'+count;

    var text = name+"</br>"+address+"</br>"+phone+"</br>"+cityname;

    // add marker and store it
    $("#map").gmap3({
      marker:{
        latLng: [lat,lng],
        options:{
          draggable:false,
          label: name
        },
        events: {
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
          }
        },
        id: id
      }
    });
  })

});

