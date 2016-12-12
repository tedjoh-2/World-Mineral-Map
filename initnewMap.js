<script>
function initMap() {
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 3,
		center: {lat: 18.024, lng: 59.887}
	});

	//Create a simple menu for markers and circles that can be placed anywhere on the map.
	var drawingManager = new google.maps.drawing.DrawingManager({
		drawingMode: google.maps.drawing.OverlayType.MARKER,
		drawingControl: true,
		drawingControlOptions: {
			position: google.maps.ControlPosition.TOP_CENTER,
			drawingModes: ['marker','circle']
			},
			circleOptions: {
			fillColor: '#fff00',
			fillOpacity: 1,
			strokeweight: 5,
			clickable: false,
			editable: true,
			zIndex: 1
			}
		});

	drawingManager.setMap(map);


        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        var markers = locations.map(function(location, i) {
		return new google.maps.Marker({
		position: location,
		label: labels[i % labels.length]
		});
        });

	var markerCluster = new MarkerClusterer(map, markers,{imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
	}

	var locations = foo;

</script>
