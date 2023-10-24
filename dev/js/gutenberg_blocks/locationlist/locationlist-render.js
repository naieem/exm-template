
cdm_core.fn.docReady(()=>build_locationlist())

const build_locationlist = () => {
    if(document.querySelector('.wp-block-exm-locationlist .locations')){
        const {Collapsible} = cdm_core.components;
        let sorted_by_state = {};
        locationlist_data.locations.forEach(loc => {
            if(!sorted_by_state[loc.address.country]){
                sorted_by_state[loc.address.country]={};
            }
            if(!sorted_by_state[loc.address.country][loc.address.state]){
                sorted_by_state[loc.address.country][loc.address.state] = [loc]
            }else{
                sorted_by_state[loc.address.country][loc.address.state] = [...sorted_by_state[loc.address.country][loc.address.state],loc]
            }
        });

        const items = [];
        ['Canada','United States','China'].forEach(country_key => {
            var arr = country_key != 'United States'?Object.keys(sorted_by_state[country_key]).sort().reverse():Object.keys(sorted_by_state[country_key]);
            arr.map( state_key => {
                let locations = sorted_by_state[country_key][state_key].map(loc => <Loc address={loc.address.address} phone={loc.phone} email={loc.email} />);
                items.push(<Collapsible title={country_key == 'China'?country_key:state_key+', '+country_key } >{locations}</Collapsible>)
            })
        })
        delete sorted_by_state['Canada'];
        delete sorted_by_state['United States'];
        delete sorted_by_state['China'];

        Object.keys(sorted_by_state).forEach(country_key => {
            Object.keys(sorted_by_state[country_key]).map( state_key => {
                let locations = sorted_by_state[country_key][state_key].map(loc => <Loc address={loc.address.address} phone={loc.phone} email={loc.email} />);
                items.push(<Collapsible title={state_key+', '+country_key } >{locations}</Collapsible>)
            })
        })

        cdm_core.react.render(<List items={items} />,document.querySelector('.wp-block-exm-locationlist .locations'));

        if (typeof google === 'object' && typeof google.maps === 'object') {
            initMap()
        }

    }
}


const initMap = ()=>{
    map = new google.maps.Map(document.getElementById("contactmap"), {
        center: { lat: 28.564421989707274, lng: 0.8286596820654424 },
        zoom: 2,
        mapTypeId: 'custom_style',
        disableDefaultUI: true,
        mapTypeControlOptions: { mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'custom_style'] },
    });      
    
    map.mapTypes.set(
        'custom_style', 
        new google.maps.StyledMapType(GOOGLEMAP_CONFIG.style, {name: 'Custom Style'})
    );

    const bounds = new google.maps.LatLngBounds();
    map.markers = locationlist_data.locations.map(loc => {
        if(loc.address !== false && loc.address !== null){
            bounds.extend(new google.maps.LatLng(loc.address.lat, loc.address.lng));

            return new google.maps.Marker({
                position: new google.maps.LatLng(loc.address.lat, loc.address.lng),
                map,
                loc:loc,
                infowindow: new google.maps.InfoWindow({content:document.createElement('div')}),
                title: loc.address.address
            }); 
        }
    });// A marker
    // map.fitBounds(bounds,50); 

    map.markers.forEach(marker => {
        marker.addListener('click',()=>{
            //map.setZoom(12)
            map.panTo(marker.getPosition())
            map.setZoom(12)
            map.markers.forEach(marker => {marker.infowindow.close()})
            marker.infowindow.open(map, marker);
            cdm_core.react.render(<Loc address={marker.loc.address.address} phone={marker.loc.phone}   email={marker.loc.email} />,marker.infowindow.getContent())

        })
    })

    

} 






const List = (props) => {
    const Fragment = cdm_core.react.fragment
    return  <Fragment>
                {props.items}
            </Fragment>;
};




const Loc = (props) => {
    const {Conditional} = cdm_core.components
    return  <Conditional condition={(props.address !== null && props.address !== false)} >
                <div>
                    <p className="address-link">

                        <span>{props.address}</span>
                        <a target="_blank" href={'https://www.google.com/maps/search/?api=1&query=' + encodeURI(props.address)}>
                            {locationlist_data.lang.open}
                            <i className="far fa-external-link"></i>
                        </a>
                    </p>
                    <p className="contacts">
                        <Conditional condition={(props.phone !== '')} >
                            <a href={'tel:'+props.phone.split('').filter(x=>!['-',' ','(',')'].includes(x)).join('')}>{props.phone}</a>
                        </Conditional>
                        
                        <br/>
                        <Conditional condition={(props.email !== '')} >
                            <a href={'mailto:'+props.email} >{props.email}</a>
                        </Conditional>
                        
                    </p>
                </div>
            </Conditional>
}





