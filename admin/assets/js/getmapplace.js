
function initMap() {
    var map = new google.maps.Map(document.getElementById('locationmap'), {
      center: {lat: 21.1702, lng: 72.8311},
      zoom: 13
    });
    var input = document.getElementById('pd_location');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.setComponentRestrictions({
	    country: ["in"],
	 });
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    
    
    
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });
		
    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
       
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
  
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        marker.setIcon(({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
        
        // var test_array=JSON.stringify(place);
		// var storedNames = JSON.parse(test_array);
		// console.dir(storedNames);
    
        var address = '';
        if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
    
        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);
			
        //Location details
        //alert(place.geometry.location.lat()+' == '+place.geometry.location.lng());
        
        //alert(JSON.stringify(place));
        
        $('#pickupdroplocModal #pd_latitude').val(place.geometry.location.lat());
	    $('#pickupdroplocModal #pd_longitude').val(place.geometry.location.lng());
	     
	    $('#custaddressForm #cm_latitude').val(place.geometry.location.lat());
	    $('#custaddressForm #cm_longitude').val(place.geometry.location.lng());
	    
	    $('#pickupdroplocModal #cust_addressid').val('');
		$('#pickupdroplocModal #cust_addressid').selectpicker('refresh');
        
    });
}
