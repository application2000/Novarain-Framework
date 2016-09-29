jQuery(function($) {

	$('.nr_gmap').each(function(index, el) {
		gmapInput = $(el);
		(function(gmapInput) {
			var id = gmapInput.attr('id');
			var mapID = id + '_map';
			var coordinates = gmapInput.val();
			var zoom = gmapInput.data('zoom');
			if (coordinates.length) {
				coordinates = coordinates.split(',');
			} else {
				coordinates = gmapInput.data('coordinates').split(',');
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
					zoom: zoom
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
				$(document).on('blur', '#' + id, function(event) {
					if (checkCoordinates(gmapInput.val())) {
						newCoordinates = gmapInput.val().split(',');
						newCoordinates = new google.maps.LatLng({
							lat: parseFloat(newCoordinates[0]),
							lng: parseFloat(newCoordinates[1])
						});
						placeMarkerAndPanTo(newCoordinates, gmap);
						gmap.panTo(newCoordinates);
					} else {
						alert(Joomla.JText.strings.NR_WRONG_COORDINATES);
						gmapInput.val(coordinates.join(','));
					}
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
				updateInput(latLng);
			}

			function updateInput(latLng) {
				$("#" + id).val(latLng.lat() + "," + latLng.lng());
			}

			function checkCoordinates(latlng) {
				var pattern = new RegExp(/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/);
				return pattern.test(latlng);
			}
		})(gmapInput);
	});
});