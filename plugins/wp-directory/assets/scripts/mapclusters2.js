
function cs_google_mapclusters(Latitude, Longitude, dataobj){
 	jQuery(document).ready(function($) {
		jQuery(".fullscreen") .click(function() {
		  jQuery("body").toggleClass("body-fullscreen");
		  //jQuery("#map-container").height(jQuery(window).height)
		 google.maps.event.trigger(map, "resize");
		});
	});
	var markerClusterer = null;
	  var map = null;
	  var imageUrl;
	jQuery(window).load(function() {
		initialize(Latitude, Longitude, dataobj)
		jQuery(".loader").remove();
		jQuery(".map").css({
		  "opacity" :"1"
		})
  	});
}

function refreshMap(Latitude, Longitude, dataobj) {
	var map = null;
	  var imageUrl;
	var markers = [];
	var mc;
	var data = {}
	var markerImage = new google.maps.MarkerImage(imageUrl,
	new google.maps.Size(24, 32));
	var infowindow = new google.maps.InfoWindow();
	dataobj = dataobj;
	
	 jQuery.each(dataobj.posts, function(index, element) {
				var i = element.post_id;
				var latLng = new google.maps.LatLng(element.latitude, element.longitude);
				  var marker = new google.maps.Marker({
				   position: latLng,
				   draggable: true,
				   content: element.post_title,
				   picture: element.image_url,
				   icon: element.mapamrker
				  });
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				  return function() {
				var html = "<h3><a href="+element.permalink+">"+marker.content+"</a></h3><img src="+marker.picture+" width='200'>";
                                //marker
					  infowindow.setContent(html);
					  infowindow.open(map, marker, html);
				  }
				})(marker, i));
				  markers.push(marker);
		});
	var mcOptions;
	var clusterStyles = [
		{
		textColor: '#ffffff',    
		opt_textColor: '#ffffff',
		url: "images/img-txtfld.png",
		height: 37,
		width: 32,
		textSize:11
		}
	];
	 var mc = new MarkerClusterer(map, markers, mcOptions);
	if(  document.getElementById('gmapzoomplus') ){
		 google.maps.event.addDomListener(document.getElementById('gmapzoomplus'), 'click', function () {      
		  
		   var current= parseInt( map.getZoom(),11);
					 current++;
					 if(current>20){
						 current=20;
					 }
		  // var current=9;
		   map.setZoom(current);
		});  
	}
	if(  document.getElementById('gmapzoomminus') ){
		 google.maps.event.addDomListener(document.getElementById('gmapzoomminus'), 'click', function () {      
		   
		   var current= parseInt( map.getZoom(),11);
					  current--;
					  if(current<0){
						  current=0;
					  }
		   // var current=5;
		   map.setZoom(current);
		});  
	}
}
function initialize(Latitude, Longitude, dataobj) {
	map = new google.maps.Map(document.getElementById('map'), {
	  zoom: 3,
	  disableDefaultUI: true,
	  center: new google.maps.LatLng(Latitude, Longitude),
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	refreshMap(Latitude, Longitude, dataobj);
}