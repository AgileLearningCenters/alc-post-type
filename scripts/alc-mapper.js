membersMapData = {};

function initMembersMaps (){
  for (var i in membersMapData ){
    var map = membersMapData[i];
    map.mapObj = new membersMap(map.settings.target, map.alcs, map.settings);
  }
}

google.maps.event.addDomListener(window, 'load', initMembersMaps);

function membersMap ( target, data, options ) {
  this.target = target;
  this.data = data;
  this.bounds = new google.maps.LatLngBounds();
  // options
  this.settings = jQuery.extend({}, this.constructor.defaults);
  this.setting(options);

  // determine if agent is mobile
  this.isMobile = (navigator.userAgent.toLowerCase().indexOf('android') > -1) ||
                  (navigator.userAgent.match(/(iPod|iPhone|iPad|BlackBerry|Windows Phone|iemobile)/));

  //init
  this.build();
  if ( ! this.isNegative(this.settings.markers) ) {
    this.markers();
  }
  if ( ! this.isNegative(this.settings.legend) ) {
    this.legend();
  }

}

membersMap.defaults = {
  cssClass: 'map',
  zoom: 3,
  width: '400px',
  height: '100%',
  centerLat : 30.136093332022153,
  centerLng : -100.47842740624996,
  legend: true,
  mapTypeId: google.maps.MapTypeId.ROADMAP,
  style: [
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
  ],
};

membersMap.prototype = {
  constructor: membersMap,
  isNegative:function (value){
    var neg =  ["no","false","n",0,"0"];
    return neg.indexOf(value) > -1;
  },
  setting:function (options) {
    jQuery.extend(this.settings, options);
  },
  build:function () {
    
    if (this.isMobile) {
      this.viewport = document.querySelector("meta[name=viewport]");
      this.viewport.setAttribute('content', 'initial-scale=1.0, user-scalable=no');
    }

    var zoomPosition = ( this.isMobile ) ? google.maps.ControlPosition.LEFT_BOTTOM : google.maps.ControlPosition.LEFT_TOP ;

    // construction
    this.mapElement = document.getElementById(this.target);
    // exit the script if the HTML element isn't present
    if ( this.mapElement === null ) { console.log("map failed to find target: " + target); return false; }
    // prepare the element
    this.mapElement.style.width  = this.settings.mapWidth;
    this.mapElement.style.height = this.settings.mapHeight;
    
    this.map = new google.maps.Map( this.mapElement, {
      center: new google.maps.LatLng( this.settings.centerLat, this.settings.centerLng ),
      zoom: this.settings.zoom,
      mapTypeId: this.settings.mapTypeId,
      streetViewControl: false,
      mapTypeControl: false,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL,
        position: zoomPosition
      },
      scrollwheel: false,
      styles: this.settings.style
    });
  },
  markers:function () {
    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(),
        marker,
        icon;

    this.icons = {};

    // Loop through our array of markers & place each one on the map  
    for (var i in this.data) {
        // skip if on map is not set or no geocode is present
        // if (this.data[i].alc_map_info_on_map != 1 || typeof this.data[i].alc_map_info_geocode == 'undefined') { continue; }
        
        icon = ( typeof this.data[i].type.icon.url !== 'undefined' && this.data[i].type.icon.url != '') ? this.data[i].type.icon.url : this.settings.defaultIcon ;
        
        var image = {
          url: icon,
          scaledSize: new google.maps.Size(35,55),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(17, 55)
        };

        // build icons object
        if ( !(this.data[i].type.name in this.icons) ){
          this.icons[this.data[i].type.slug] = this.data[i].type;
        }

        var geolocation = this.data[i].alc.map.info_geocode.split(',');
        var position = new google.maps.LatLng(geolocation[0], geolocation[1]);
        this.bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: this.map,
            title: this.data[i].alc.map.info_name,
            // icon: image,
            icon: icon
        });
        
        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function(marker, content) {
            return function() {
                infoWindow.setContent(content);
                infoWindow.open(this.map, marker);
            }
        })(marker, this.data[i].infoWindow));

        // Automatically center the map fitting all markers on the screen
        this.map.fitBounds(this.bounds);
        // map.setZoom(map.getZoom() + 1);
    }
  },
  legend:function(){
    // draw map legend
    var legend = document.createElement('div');
    legend.setAttribute('id',this.settings.legend);
    legend.setAttribute('class','alc-map-legend');
    legend.style.padding = '10px';
    legend.style.backgroundColor = '#FFF';
    legend.style.boxShadow = '-2px 2px 4px rgba(0,0,0,.25)';

    for (var key in this.icons) {
      var type = this.icons[key],
          url  = ( typeof type.icon.url !== 'undefined' && type.icon.url != '') ? type.icon.url : this.settings.defaultIcon,
          hgt  = (this.isMobile) ? '10px' : '40px',
          div  = document.createElement('div');
          div.innerHTML = '<img src="' + url + '" style="max-height:' + hgt + '"> ' + type.name;
      
      legend.appendChild(div);
    }

    // set legend position
    if (this.isMobile) {
      this.map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
    } else {
      this.map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(legend);
    }
  }


};