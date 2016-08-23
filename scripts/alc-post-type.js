( function( $ ) {
  jQuery(document).ready(function($) {

    function alcGeocodeAddress(addressId,geocodeId){
      var addressId = (typeof addressId == 'undefined') ? '#address' : addressId,
          geocodeId = (typeof geocodeId == 'undefined') ? '#geocode' : geocodeId,
          geocoder = new google.maps.Geocoder(),
          address = $(addressId).val().split('|');

      // Remove Address line 2 from array
      address.splice(1,1);

      geocoder.geocode({ 'address': address.join('+') }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          var geolocation = results[0].geometry.location,
              geocodeLatLng = geolocation.lat() + ',' + geolocation.lng();
          
          $(geocodeId).val(geocodeLatLng);

          $('#geocodeButton').after('<p><img src="' + alcStaticMapURL(geocodeLatLng) + '"></p>');
        }
        else {
          console.log("Geocoding failed: " + status + ' | address:' + address.join('+'));
        }
      });

    }

    function alcStaticMapURL(geocode){
      var baseurl = 'https://maps.googleapis.com/maps/api/staticmap',
          apikey = 'AIzaSyCXre9Yr0X1YQpFZJpXWIWN8ZOVTHZUjvU';
      var mapObject = {
        'center':geocode,
        'size':'600x200',
        'zoom':'8',
        'maptype':'roadmap',
        'markers':'color:blue|label:A|' + geocode,
        'key':apikey
      };

      return baseurl + '?' + $.param( mapObject );
    }

    $('#geocodeButton').click(function(){
      alcGeocodeAddress();
    });

  });
} )( jQuery );