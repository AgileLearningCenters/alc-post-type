<?php 

$geocodeAPIKey = 'AIzaSyDUkTyad56hKDcQaCOJOWDsundLnWFI3Fc';
$staticMapAPIKey = 'AIzaSyCXre9Yr0X1YQpFZJpXWIWN8ZOVTHZUjvU';

function alc_post_type_admin_script() {
    global $post_type, $geocodeAPIKey;
    if( 'alc' == $post_type )
    wp_enqueue_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . $geocodeAPIKey, array(), null, true );
    wp_enqueue_script( 'alc-post-type-js', plugins_url( 'scripts/alc-post-type.js' , __FILE__ ), array('google-maps-api'), '1.0.1', true );
}

add_action( 'admin_print_scripts-post-new.php', 'alc_post_type_admin_script', 11 );
add_action( 'admin_print_scripts-post.php', 'alc_post_type_admin_script', 11 );

// Map creation metabox

function alc_member_map_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'target' => false,
        'legend' => true,
        'class' => 'map',
        'zoom' => 3,
        'mapWidth' => '100%',
        'mapHeight' => '400px',
        'centerLat' => '30.136093332022153',
        'centerLng' => '-100.47842740624996',
    ), $atts );

    // global $geocodeAPIKey;
    // wp_enqueue_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . $geocodeAPIKey, array(), null, false );

    // Query ALCs

    $args = array(
        'post_type' => 'alc',
        'post_status' => 'publish'
    );

    $string = '';
    $alcs = get_posts( $args );
    $data = array();
    
    if($alcs){
      foreach ( $alcs as $alc ) {
          $map_meta = array();

          $map_meta['alcType'] = (array) array_shift( wp_get_post_terms($alc->ID, 'alc-type') );
          $map_icon_id = get_term_meta( $map_meta['alcType']['term_id'], 'type-icon-id', true );
          $map_meta['alcType']['mapIcon'] = array(
            'id' => $map_icon_id,
            'url' => wp_get_attachment_url( $map_icon_id )
          );

          // update_meta_cache()???
          foreach (get_post_meta($alc->ID) as $key => $value) {
            $match = 'alc_map_info';
              $map_meta[$key] = (is_array($value)) ? implode(' ', $value) : $value ;
            if (substr($key, 0, strlen($match)) === $match) {
            }
          }

          $infoWindow = '<h1>' . $map_meta['alc_map_info_name'] . '</h1>';
          $infoWindow .= '<p>' . $map_meta['alc_map_info_description'] . '</p>';
          if (isset($map_meta['alc_map_info_cta'])) {
            $infoWindow .= '<p><a class="button" href="' . $map_meta['alc_map_info_cta'] . '">' . $map_meta['alc_map_info_cta_label'] . '</a></p>';
          }

          $map_meta['infoWindowContent'] = $infoWindow;
          // Merge meta data and post data
          $data[$alc->post_name] = array_merge(get_object_vars($alc),$map_meta);
      }
    }

    // Create Script

    $id = ( $a['target'] ) ? $a['target'] : 'alc-map-' . substr( sha1( "Pickle Pie" . time() ), rand( 2, 10 ), rand( 5, 8 ) );
    $legend = $id . '-legend';
    $out = ( $a['target'] ) ? '' : '<div class="' . $a['class'] . '" id="' . $id . '"></div>';

    ob_start();
      ?>

      <script type='text/javascript'>
      function initialize() {
        var targetDiv = '<?php echo $id ?>',
            legendTarget = '<?php echo $legend ?>',
            settings = <?php echo json_encode($a) ?>,
            alcData = <?php echo json_encode($data) ?>,
            bounds = new google.maps.LatLngBounds(),
            mapStyle = [
              {
                "stylers": [
                  { "weight": 0.2 },
                  { "saturation": -73 }
                ]
              },{
                "featureType": "poi",
                "stylers": [
                  { "visibility": "off" }
                ]
              },{
                "featureType": "water",
                "stylers": [
                  { "visibility": "on" },
                  { "lightness": -63 },
                  { "hue": "#00aaff" }
                ]
              }
            ];

          google.maps.visualRefresh = true;
          var isMobile = (navigator.userAgent.toLowerCase().indexOf('android') > -1) ||
            (navigator.userAgent.match(/(iPod|iPhone|iPad|BlackBerry|Windows Phone|iemobile)/));
          if (isMobile) {
            var viewport = document.querySelector("meta[name=viewport]");
            viewport.setAttribute('content', 'initial-scale=1.0, user-scalable=no');
          }

          // construction
          
          var mapDiv = document.getElementById(targetDiv);
          mapDiv.style.width = isMobile ? '100%' : settings.mapWidth;
          mapDiv.style.height = isMobile ? '100%' : settings.mapHeight;
          
          var map = new google.maps.Map(mapDiv, {
            center: new google.maps.LatLng(settings.centerLat,settings.centerLng),
            zoom: settings.zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            streetViewControl: false,
            mapTypeControl: false,
            zoomControlOptions: {
              style: google.maps.ZoomControlStyle.SMALL,
              position: google.maps.ControlPosition.LEFT_TOP
            },
            scrollwheel: false,
            styles: mapStyle
          });
          
          // Markers

          // Display multiple markers on a map
          var infoWindow = new google.maps.InfoWindow(), 
              icons = {},
              alcMarker;

          // Loop through our array of markers & place each one on the map  
          for (var i in alcData) {
              // skip if on map is not set or no geocode is present
              if (alcData[i].alc_map_info_on_map != 1 || typeof alcData[i].alc_map_info_geocode == 'undefined') { continue; }
              
              var image = {
                url: alcData[i].alcType.mapIcon.url,
                scaledSize: new google.maps.Size(35,55),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 55)
              }

              // build icons object
              if ( !(alcData[i].alcType.name in icons) ){
                icons[alcData[i].alcType.slug] = alcData[i].alcType;
              }

              var geolocation = alcData[i].alc_map_info_geocode.split(',');
              var position = new google.maps.LatLng(geolocation[0], geolocation[1]);
              bounds.extend(position);
              alcMarker = new google.maps.Marker({
                  position: position,
                  map: map,
                  title: alcData[i].alc_map_info_name,
                  // icon: image,
                  icon: alcData[i].alcType.mapIcon.url
              });
              
              // Allow each marker to have an info window    
              google.maps.event.addListener(alcMarker, 'click', (function(alcMarker, i) {
                  return function() {
                      infoWindow.setContent(alcData[i]['infoWindowContent']);
                      infoWindow.open(map, alcMarker);
                  }
              })(alcMarker, i));

              // Automatically center the map fitting all markers on the screen
              map.fitBounds(bounds);
              // map.setZoom(map.getZoom() + 1);
          }

          // draw map legend
          var legend = document.createElement('div');
          legend.setAttribute('id',legendTarget);
          legend.setAttribute('class','alc-map-legend');
          legend.style.padding = '10px';
          legend.style.backgroundColor = '#FFF';
          legend.style.boxShadow = '-2px 2px 4px rgba(0,0,0,.25)';

          for (var key in icons) {
            var type = icons[key],
                name = type.name,
                url = type.mapIcon.url,
                div = document.createElement('div');
            div.innerHTML = '<img src="' + url + '" style="max-height:40px"> ' + name;
            legend.appendChild(div);
          }

          // set legend position
          map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(legend);

          // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
          // var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
          //     this.setZoom(14);
          //     google.maps.event.removeListener(boundsListener);
          // });

          // mobile

          if (isMobile) {
            var legend = document.getElementById('googft-legend');
            var legendOpenButton = document.getElementById('googft-legend-open');
            var legendCloseButton = document.getElementById('googft-legend-close');
            legend.style.display = 'none';
            legendOpenButton.style.display = 'block';
            legendCloseButton.style.display = 'block';
            legendOpenButton.onclick = function() {
              legend.style.display = 'block';
              legendOpenButton.style.display = 'none';
            }
            legendCloseButton.onclick = function() {
              legend.style.display = 'none';
              legendOpenButton.style.display = 'block';
            }
          }
        }
        google.maps.event.addDomListener(window, 'load', initialize);
      </script>

      <?php
      $out .= ob_get_clean();
    
    return '<script src="https://maps.google.com/maps/api/js?sensor=false&amp;v=3" type="text/javascript"></script>' . $out;
}

add_shortcode( 'alc_member_map', 'alc_member_map_shortcode' );