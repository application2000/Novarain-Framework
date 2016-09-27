jQuery(function($) {

	$('.nr_gmap').each(function(index, el) {
		gmapInput = $(el);
		(function(gmapInput) {
			var id = gmapInput.attr('id');
			var mapID = id + '_map';
			var coordinates = gmapInput.val();
			if (coordinates.length) {
				coordinates = coordinates.split(',');
			} else {
				// use the center of the earth for a broad view of the map
				coordinates = [19.189444 , -31.113281];
			}
			var gmap;
			var marker;

			initMap();

			function initMap() {
				gmap = new google.maps.Map(document.getElementById(mapID), {
					center: {
						lat: parseFloat(coordinates[0]),
						lng: parseFloat(coordinates[1])
					},
					zoom: 2
				});
				if (coordinates.length) {
					marker = new google.maps.Marker({
						position: {
							lat: parseFloat(coordinates[0]),
							lng: parseFloat(coordinates[1])
						},
						map: gmap
					});
				}

				gmap.addListener("click", function(e) {
					placeMarkerAndPanTo(e.latLng, gmap);
				});
				// set a click listener for the Settings tab in order for the google map to re-render correctly
				$(document).on('click', 'a[href="#attrib-settings"]', function(event) {
					var center = gmap.getCenter();
					google.maps.event.trigger(gmap, 'resize');
					gmap.setCenter(center);
				});
			}

			function placeMarkerAndPanTo(latLng, map) {
				if (typeof marker == "undefined") {
					marker = new google.maps.Marker({
						position: latLng,
						map: gmap
					});
				}
				marker.setPosition(latLng);
				gmap.panTo(latLng);
				updateInput(latLng);
			}

			function updateInput(latLng) {
				$("#" + id).val(latLng.lat() + "," + latLng.lng());
			}
		})(gmapInput);
	});
});