(function($){

    /**
     * initializeBlock
     *
     * Adds custom JavaScript to the block HTML.
     *
     * @date    15/4/19
     * @since   1.0.0
     *
     * @param   object $block The block jQuery element.
     * @param   object attributes The block attributes (only available when editing).
     * @return  void
     */
    var initializeBlock = function( $block ) {
        var addressData = $block.find('.map').data('address');
		var map;
		var MY_MAPTYPE_ID = 'custom_style';
        var lat = addressData.lat;
        var lng = addressData.lng;
        
        var markerPos = new google.maps.LatLng(lat, lng);
                
        var featureOpts = [
                {
                "featureType": "administrative",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": mapData.themeColors[4].color
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [
                    {
                        "color": mapData.themeColors[2].color
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "all",
                "stylers": [
                    {
                        "saturation": -100
                    },
                    {
                        "lightness": 45
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [
                    {
                        "color": mapData.themeColors[1].color
                    },
                    {
                        "visibility": "on"
                    }
                ]
            }
        ];
                
        var mapOptions = {
            zoom: 13,
            scrollwheel : false,
            zoomControl: true,
            disableDefaultUI: true,
            center: markerPos,
            labelContent: mapData.siteName,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
            },
            mapTypeId: MY_MAPTYPE_ID
        };// mappOptions
                
        map = new google.maps.Map($block.find('.map')[0], mapOptions);
        
        var styledMapOptions = {
            name: 'Custom Style'
        };//StyledMapOptions
                
        var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
        
        map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
        
        var infowindow = new google.maps.InfoWindow({
            content: '<a href="https://www.google.ca/maps/dir//'+encodeURIComponent(addressData.address)+'" target="_blank" rel="nooponer">'+mapData.itineraire+'</a>'
        });
                
        var markerMap = new google.maps.Marker({
            position: markerPos,
            map: map,
            title: mapData.siteName
        });// A marker
                
        markerMap.addListener('click', function() {
            infowindow.open(map, markerMap);
        });
    }

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        $('.google-map').each(function(){
            initializeBlock( $(this) );
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=googlemap', initializeBlock );

        window.acf.addAction('google_map_init', function( map, marker, field ){ // Signale lorsque le champ google map est prêt à être modifié.
            field.mapReady = true;
            window.acf.doAction('field_map_ready', field, map);
        });

        window.acf.addAction('new_field/name=utiliser_ladresse_generale', function(field){
            field.on('change', 'input', function( e ){ // Quand on change le true/false.
                let ACFmap = window.acf.getField(window.acf.findFields({name: 'map', sibling: field.$el})); // Le champ map associé au true/false.
                
                if(ACFmap.mapReady){ // Si le champ de la map est prêt à être manipulé.
                    if(field.$input().prop('checked')){
                        ACFmap.$el.find('input.search').hide(); // on cache la barre de recherche.
                        ACFmap.searchPositionCopy = ACFmap.searchPosition; // Garde en copy la function pour positionner le marker au clic.
                        ACFmap.searchPosition = function(){return false;}; // On désactive la fonction de click pour positionner le marker.
                        ACFmap.map.marker.setDraggable(false); // désactive le drag and drop du marker.

                        // On va chercher l'adresse générale et on change la valeur de la carte.
                        jQuery.post(window.wp.ajax.settings.url, {action: 'theme_client_get_option_address'}, function(res){
                            JSON.stringify(res);
            
                            ACFmap.val(res);
                        }, 'json');

                    }else{ // On réactive les options de positionnement du marker.
                        ACFmap.$el.find('input.search').show();
                        ACFmap.searchPosition = ACFmap.searchPositionCopy;
                        ACFmap.map.marker.setDraggable(true);
                    }

                }else{ // Si la map est pas prête, on attent l'action.
                    window.acf.addAction('field_map_ready', function(mapField, map){
                        if(mapField.cid == ACFmap.cid){
                            field.$input().trigger('change');
                        }
                    });
                }
            });

            field.$input().trigger('change');
        });
    }

})(jQuery);