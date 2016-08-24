( function( $ ) {
  jQuery(document).ready(function($) {

    var geocodeButtonId = '#geocodeButton';

    function formatAddress(addressStr,delineator){
      var delineator = (typeof delineator == 'undefined') ? '|' : delineator,
          addressArray = addressStr.split(delineator);

          addressArray.splice(1,1);

          return [ addressArray.join('+'), addressArray];
    }

    function geocodeAddress(addressId,geocodeId){
      var addressId = (typeof addressId == 'undefined') ? '#address' : addressId,
          geocodeId = (typeof geocodeId == 'undefined') ? '#geocode' : geocodeId,
          geocoder = new google.maps.Geocoder(),
          address = formatAddress($(addressId).val());

      geocoder.geocode({ 'address': address[0] }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          var geolocation = results[0].geometry.location,
              geocodeLatLng = ([geolocation.lat(),geolocation.lng()]).join(',');
          
          $(geocodeId).val(geocodeLatLng);

          $(geocodeButtonId).after('<p><img src="' + alcStaticMapURL(geocodeLatLng) + '"></p>');
        }
        else {
          console.log("Geocoding failed: " + status + ' | address:' + address[0]);
        }
      });

    }

    function alcStaticMapURL(geocode, mapObject){
      var baseurl = 'https://maps.googleapis.com/maps/api/staticmap',
          apikey = 'AIzaSyCXre9Yr0X1YQpFZJpXWIWN8ZOVTHZUjvU';
      var defaults = {
        'center':geocode,
        'size':'600x200',
        'zoom':'8',
        'maptype':'roadmap',
        'markers':'color:red|' + geocode,
        'key':apikey
      };
      var mapObject = $.extend({}, defaults, mapObject || {});

      return baseurl + '?' + $.param( mapObject );
    }

    $(geocodeButtonId).click(function(){
      geocodeAddress();
    });

  });
} )( jQuery );