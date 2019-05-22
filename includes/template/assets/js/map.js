jQuery(document).ready(function($) {

	// Init
	var is_xs, is_sm, is_md, is_lg, is_xl, is_landscape, is_hd,
        body = $( 'body' ),
        opt = $( '#options' ),
        offset = 0;

	// Map
	if( $( '#vdslMapCanvas' ).length > 0 ) {
        google.maps.event.addDomListener( window, 'load', vdslGoogleMap );
    }

    function vdslGoogleMap() {
	    
	    var clusterPinImg = vdslMapScript.clusterPinImg;
		if (clusterPinImg == '') {
			clusterPinImg = vdslMapScript.pluginsUrl + '/includes/template/assets/img/pin-cluster.png';
		}

	    var clusterStyles = [{
		    textColor: 'white',
		    url: clusterPinImg,
		    height: 53,
		    width: 53
		}];

        var map,
            sizeX = 29,
            halfSizeX = sizeX / 2,
            sizeY = 40,
            dragOption = true,
            marker,
            infoWindows = [],
            markers = [],
            markers_clusters = {},
            view_map = $( '#view-map' ),
            mapBounds = new google.maps.LatLngBounds();

        if( is_xs ) {
            dragOption = false;
            sizeX = 29;
            halfSizeX = sizeX / 2;
            sizeY = 40;
        }

        if( is_sm ) {
            sizeX = 29;
            halfSizeX = sizeX / 2;
            sizeY = 40;
        }

        var destination = new google.maps.LatLng( 45.474430, -73.699438 ),
            infowindow = new google.maps.InfoWindow(),
            maptypeId = 'custom',
            featureOpts = [{ stylers: [ { hue: "#bbbbbb" }, { saturation: -100 }, { lightness: 0 }, { gamma: 1 } ] }],
            mapOptions = {
                zoom: 5,
                center: destination,
                scrollwheel: false,
                panControl: false,
                panControlOptions: {
                    position: google.maps.ControlPosition.TOP_RIGHT
                },
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE,
                    position: google.maps.ControlPosition.TOP_LEFT
                },
                mapTypeControl: false,
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP]
                },
                draggable: dragOption,
                disableDefaultUI: true,
                mapTypeId: maptypeId
            };

        map = new google.maps.Map( document.getElementById( 'vdslMapCanvas' ), mapOptions );

		var mcOptions = {
		    styles: clusterStyles,
		};
        markers_clusters = new MarkerClusterer(map, [], mcOptions); // TODO: Fix cluster, add an option to turn on and off.
		
		// Get Pins from Admin. Else, use default pins.
		var defaultPin = vdslMapScript.defaultPinImg;
		if (defaultPin == '') {
			defaultPin = vdslMapScript.pluginsUrl + '/includes/template/assets/img/pin.png';
		}
		
		var selectPin = vdslMapScript.selectPinImg;
		if (selectPin == '') {
			selectPin = vdslMapScript.pluginsUrl + '/includes/template/assets/img/pin-active.png';
		}
		
        var image = {
	        	url: defaultPin,
                size: new google.maps.Size( sizeX, sizeY ),
                scaledSize: new google.maps.Size( sizeX, sizeY ),
                anchor: new google.maps.Point( halfSizeX, sizeY )
            },
            locations = [],
            styledMapOptions = {
                name: 'N&B' //TODOcustomize
            },
            curr_marker,
            customMapType = new google.maps.StyledMapType( featureOpts, styledMapOptions );

		var imageActive = {
				url: selectPin,
                size: new google.maps.Size( sizeX, sizeY ),
                scaledSize: new google.maps.Size( sizeX, sizeY ),
                anchor: new google.maps.Point( halfSizeX, sizeY )
        };

        map.mapTypes.set( maptypeId, customMapType );

        // Get the map points
        $.ajax({
            url: vdslMapScript.ajaxUrl,
            method: 'GET',
            dataType: 'json',
            data: {
                action: 'map_points',
            },
            success : function( data, textStatus, jqXHR ) {
                for (var i = 0; i < data.length; i++) {
                    locations[i] = {
                        '_latitude': data[i]._lat,
                        '_longitude': data[i]._lng,
/*
                        '_address': data[i]._address,
                        '_phone': data[i]._phone,
                        '_email': data[i]._email,
*/
                        '_title': data[i]._title,
                        '_slug': data[i]._slug
                    };
                }

                for (var i = 0; i < data.length; i++) {
                    vdslCreatePoints( locations[i] );
                }

                setTimeout( function() {
                    map.fitBounds( mapBounds );
                }, 0 );
            },
            error : function( jqXHR, textStatus, errorThrown ) {
                console.log( errorThrown );
            }
        });


		/**
		 * 	Detect Location
		 */
        $('#detect').on( 'click', function() {
            vdslGeolocation();

            return false;
        });

        function vdslGeolocation() {
            $( '.vdslLoading' ).fadeIn( 1000 );

            if ( navigator.geolocation ) {
                navigator.geolocation.getCurrentPosition( function( position ) {
                    mapBounds = new google.maps.LatLngBounds();
                    vdslClearMarker();

                    var pos = new google.maps.LatLng( position.coords.latitude, position.coords.longitude );

                    vdslResetPosition( pos );

                }, function() {
                    handleNoGeolocation( true );
                });

            } else {
                handleNoGeolocation( false );
            }
        }

        function handleNoGeolocation( errorFlag ) {
            if ( errorFlag ) {
                if( vdsl.lang_code == 'fr' ) { // TODO optimize: translate strings
                    alert( 'Erreur: Le service de géolocalisation a échoué.' );
                } else {
                    alert( 'Error: The location service failed.' );
                }

            } else {
                if( vdsl.lang_code == 'fr' ) { // TODO optimize: translate strings
                    alert( 'Erreur: Votre navigateur ne supporte pas la géolocalisation.' );
                } else {
                    alert( 'Error: Your browser does not support geolocation.' );
                }
            }
        }


		/**
		 * 	Search Field
		 */
        $( 'form.vdStores' ).on( 'submit', function() {

            var me = $( this ),
                address = me.parent().find( 'input' ).val();

            if( address !== '' ) {
                vdslGetPostal( address + ', Canada' ); // TODO: Add default country (add a learn more box). If Canada is chosed, Paris (Brantford, Ontario) will comes up before Paris (France)
            }

            return false;
        });

        function vdslGetPostal( address ) {
            $('.vdslLoading').fadeIn(1000);

            var geocoder = new google.maps.Geocoder(),
                position;

            geocoder.geocode( { 'address': address }, function( results, status ) {

                if ( status == google.maps.GeocoderStatus.OK ) {
                    mapBounds = new google.maps.LatLngBounds();
                    vdslClearMarker();

                    position = results[0].geometry.location;

                    vdslResetPosition( position );

                } else if ( status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT ) {

                } else if ( status == google.maps.GeocoderStatus.ZERO_RESULTS ) {

                    if( vdsl.lang_code == 'fr' ) { // TODO optimize: translate strings
                        alert( 'Une erreur est survenue lors de la recherche de l\'adresse' );
                    } else {
                        alert( 'An error occured when searching for the address' );
                    }

                    $('.vdslLoading').fadeOut(1000);
                }
            });
        }

        function vdslClearMarker() {

            if( typeof marker !== 'undefined' ) {
                marker.setMap(null);
            }

            $( '#vdslRetailersList' ).empty();

            for ( var i = 0; i < markers.length; i++ ) {
                markers[i].setMap(null);
            }
        }

        function vdslCloseAllInfoWindows() {
            for ( var i = 0; i < infoWindows.length; i++ ) {
                infoWindows[i].close();
            }

           for ( var i = 0; i < markers.length; i++ ) {
                markers[i].setIcon(image);
            }

        }


        function vdslResetPosition( position ) {
            var image = {
                    url: vdslMapScript.pluginsUrl + '/includes/template/assets/img/localisation.png',
                    size: new google.maps.Size( sizeX, sizeY ),
                    scaledSize: new google.maps.Size( sizeX, sizeY ),
                    anchor: new google.maps.Point( halfSizeX, sizeY )
                };

            marker = new google.maps.Marker({
                position: position,
                map: map,
                icon: image,
                zIndex: 1
            });

            markers.push( marker );

            var infowindow = new google.maps.InfoWindow({
                content: vdslMapScript.you
            });

            infoWindows.push( infowindow );

            marker.addListener( 'click', function() {
                vdslCloseAllInfoWindows();

                // Pan to current marker
                map.panTo( marker.position );

                infowindow.open( map, marker );
            });

            mapBounds.extend( marker.position );

            // Create elements
            var imax = locations.length;

            for ( var i = 0; i < imax; i++ ) {
                var latLng = new google.maps.LatLng( locations[i]._latitude, locations[i]._longitude ),
                    distance,
                    format_distance = '';

                distance = google.maps.geometry.spherical.computeDistanceBetween( position, latLng ) / 1000;
                format_distance = Math.round(distance);

                locations[i].distance = format_distance;
            }

            function compareDistance(oObjA, oObjB) {
                var iRes = 0;

                if (oObjA.distance < oObjB.distance) {
                    iRes = -1;
                } else if (oObjA.distance > oObjB.distance) {
                    iRes = 1;
                }

                return iRes;
            }

            locations.sort( compareDistance );
            var retailers_count = 0;

			// Search Radius for localisation (in Km)
            var searchradius = $('#vdslRadius option:selected').val(); // TODO customize: Localise in Km or in Miles

			$('#vdslRadius').change(function() {
				$('#vdslSearch').trigger('click');
			});


            for ( var i = 0; i < imax; i++ ) {
                if( locations[i].distance <= searchradius ) {
                    retailers_count++;
                    vdslCreatePoints( locations[i] );

                    $( '.loc_distance' ).toggle();

                    if( retailers_count % 2 == 0 ) {
                        $( '#vdslRetailersList' ).append( '<div class="u-clearfix is-visible-xs"></div>' );
                    }

                    if( retailers_count % 4 == 0 ) {
                        $( '#vdslRetailersList' ).append( '<div class="u-clearfix is-visible-sm"></div>' );
                    }

                    if( retailers_count % 5 == 0 ) {
                        $( '#vdslRetailersList' ).append( '<div class="u-clearfix is-hidden-xl is-hidden-sm is-hidden-xs"></div>' );
                    }

                    if( retailers_count % 6 == 0 ) {
                        $( '#vdslRetailersList' ).append( '<div class="u-clearfix is-visible-xl"></div>' );
                    }
                }
            }

            if( retailers_count == 0 ) {
                $('#vdslRetailersList').append('No Store found.'); // TODO: Localize string
                $('#vdslRetailersList').empty();
                $('.vdslLoading' ).hide();

            } else {
                $( '.vdslLoading' ).fadeOut( 1000 );
            }

            setTimeout( function() {
                map.fitBounds( mapBounds );
            }, 0 );

            var listener = google.maps.event.addListener( map, 'idle', function() {
                if ( map.getZoom() > 16 ) map.setZoom( 10 );
                google.maps.event.removeListener( listener );
            });
        }


        function vdslCreatePoints( element ) {
            var latLng = new google.maps.LatLng( element._latitude, element._longitude ),
                output_html;

            element.marker = new google.maps.Marker({
                position: latLng,
                map: map,
                animation: google.maps.Animation.DROP,
                title: element._title,
                icon: image,
                zIndex: 2
            });

            element.marker.set( 'marker_id', element._id );

			markers_clusters.addMarker(element.marker); // TODO: Fix cluster, add an option to turn on and off.

            markers.push( element.marker );

			element.infowindow = new google.maps.InfoWindow({
// 				pixelOffset: new google.maps.Size(210,130),
                content:    '<div class="infowindow__heading">' + element._title + '</div>' +
                            '<div class="infowindow__content">' + element._address +
                            '<a href="' + element._slug + '">' + vdslMapScript.moreinfo +'</a></div>'
            });

            infoWindows.push( element.infowindow );

            element.marker.addListener( 'click', function() {
                vdslCloseAllInfoWindows();

				element.marker.setIcon(imageActive);

                element.infowindow.open( map, element.marker );
            });

            google.maps.event.addListener( element.marker, 'click', function() {
                map.panTo( latLng );
            });

            output_html =   '<div class="retailer"><a href="' + element._slug + '">' + element._title + '</a></p></div>';

            $( '#vdslRetailersList' ).append( output_html );
            $('.vdslLoading').fadeOut('slow');

            mapBounds.extend( latLng );

            setTimeout( function() {
                map.fitBounds( mapBounds );
            }, 0 );

        }

    }

});