
cdm_core.fn.docReady(()=>build_salesagentfinder())

const build_salesagentfinder = () => {
    
    if(document.querySelector('.wp-block-exm-sales-agent-finder .agents')){
        if (typeof google === 'object' && typeof google.maps === 'object') {
            let GEOCODER = new google.maps.Geocoder();
            let REQUEST = null;
            let CODE_POSTAL = "";
            let DISTANCE = 0;
            let CIRCLE = null;
            let LAST_QUERY = "";
            let MAP = initMap();

            const rad = (x) =>  x * Math.PI / 180;

            const getDistance = function(p1, p2) {
        
                var R = 6378137; // Earth’s mean radius in meter
                var dLat = rad(p2.lat() - p1.lat());
                var dLong = rad(p2.lng() - p1.lng());
                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat())) *
                    Math.sin(dLong / 2) * Math.sin(dLong / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                var d = R * c;
                return d; // returns the distance in meter
            };

            const TRIER = ()=>{
                let bounds = new google.maps.LatLngBounds();
                let rebound = false;
                let pointsCount = 0;
                
                MAP.markers.forEach(marker => {
                    if(CODE_POSTAL !== '' && marker){
                        if( getDistance(marker.position,CIRCLE.center) <= DISTANCE*1000 ){
                            marker.setVisible(true);
                            rebound = true;
                            bounds.extend(marker.getPosition());
                            pointsCount++
                            
                        }else{
                            marker.setVisible(false);
                        }
                    }else{
                        marker.setVisible(true);
                        rebound = true;
                        bounds.extend(marker.getPosition());
                        pointsCount++
                    }
                })
                // markerClusterer.repaint()
                if(rebound){
                    if(CIRCLE !== null){
                        var radius = CIRCLE.getRadius()/200;
                        map.setZoom(Math.ceil(16 - Math.log(radius) / Math.log(2)));
                    }
                }
                cdm_core.react.render(<List markers={MAP.markers} find={findmarkerbyID} />,document.querySelector('.wp-block-exm-sales-agent-finder .agents'));
            };

            const FILTER_ALL = (val)=>{
                console.log('filter is on');
                CODE_POSTAL = val.postal
                DISTANCE = val.range
               
                if(LAST_QUERY == CODE_POSTAL){
                    
                    if(CIRCLE !== null){
                        CIRCLE.setRadius(DISTANCE * 1000);
                    }
                    TRIER()
                    
                }else if(!CODE_POSTAL){
                    CIRCLE.setMap(null)
                    TRIER()
                }else{
                    if(!REQUEST){
                        
                        REQUEST == true;
                        GEOCODER.geocode( { 'address': CODE_POSTAL}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                            //Got result, center the map and put it out there
                            MAP.setCenter(results[0].geometry.location);
                            if(CIRCLE !== null){ CIRCLE.setMap(null) }
                            CIRCLE = new google.maps.Circle({
                                
                                    center: results[0].geometry.location,
                                    map: MAP,
                                    strokeColor: '#c79153',
                                    strokeWeight: 1,
                                    strokeOpacity: 0.5,
                                    fillColor: '#c79153',
                                    fillOpacity: 0.4,
                                    radius: DISTANCE * 1000,
                                    is_circle : true
                            });
                            TRIER();
                            LAST_QUERY = CODE_POSTAL;
                            REQUEST = false;
                            } else {
                            // console.log("Geocode failed " + status);
                            LAST_QUERY = CODE_POSTAL;
                                REQUEST = false;
                            }
                        });
                    }
                }
                   
            }
            cdm_core.react.render(<Filters onfilter={FILTER_ALL} />,document.querySelector('.wp-block-exm-sales-agent-finder .filters'));
            TRIER()
            function findmarkerbyID(id){
                MAP.markers.forEach(marker => {
                    if(marker.agent.id == id){
                        MAP.togglemarker(marker)
                    }
                })
            }

           
           
            
            
        }//initmap



    }
}



const List = (props) => {
    const firstUpdate = React.useRef(true);
    React.useLayoutEffect(() => {
        if (firstUpdate.current) {
          firstUpdate.current = false;
          return ;
        }
    });
    const {Conditional} = cdm_core.components;
    const {Collapsible} = cdm_core.components;
    const Fragment = cdm_core.react.fragment
    let added = 0;

    return  <Fragment>
                {props.markers.map(x=>{
                    if(x.visible  ){
                        added++;
                        return  <Collapsible title={cdm_core.fn.HTMLEntities(x.agent.title)} >
                                    <Agent find={props.find} agent={x.agent} />
                                </Collapsible>
                    }
                })}
                <Conditional condition={added == 0} >
                    <p class="noresult">{salesagentfinder_data.lang.noresult}</p>
                </Conditional>
            </Fragment>;
};

const Filters = (props) => {
    const {Conditional} = cdm_core.components;
    const [range,setRange] = cdm_core.react.useState(100);
    const [postal,setPostal] = cdm_core.react.useState('');
    const max = 500;
    const min = 1;    
    const step = 5;    
    const modifyRange = (val) => {
        if((range + val) <= max && (range + val) >= min ){
            setRange(range + val);
            props.onfilter({postal:postal,range:range + val})
        }
    }

    const resetAll = (e)=>{
        setPostal('');
        setRange(100);
        props.onfilter({postal:'',range:100})
    };

    const onChangeHandler = (e)=>{
        setPostal(e.target.value)
        props.onfilter({postal:e.target.value,range:range})
    };
    const onRange = (e) => {
        setRange(+(e.target.value))
        props.onfilter({postal:postal,range:+(e.target.value)})

    }

    return <div>
                <span class="form-wrapper">
                    <input value={postal} onInput={onChangeHandler} type="text" placeholder={salesagentfinder_data.lang.postal} />
                    <button ><i class="fa fa-search"></i></button>
                </span>
                <span class="ranger-wrapper">
                    <span class="title" >Max distance: {range}Km <Conditional condition={(range !== 100 || postal !== '')} > <span onClick={resetAll} class="reset">{salesagentfinder_data.lang.reset}</span> </Conditional> </span>
                    <span class="wrap-add">
                        <span onClick={()=>modifyRange(-step)}  class="moins">-</span>
                            <input type="range" min={min} max={max} value={range} onChange={onRange} class="slider" id="myRange" />
                        <span onClick={()=>modifyRange(step)}  class="plus">+</span>
                    </span>
                </span>
            </div>
};




const Agent = (props) => {
    const {Conditional} = cdm_core.components;
    const addhttp = (url) => {
        if (!/^(?:f|ht)tps?\:\/\//.test(url)) {
            url = "https://" + url;
        }
        return url;
    }
    return  <Conditional condition={(props.agent.address.address !== null && props.agent.address.address !== false)} >
                <div>
                    <p className="address-link">
                        <span onClick={() => props.find(props.agent.id)}>{cdm_core.fn.HTMLEntities(props.agent.title)}</span>
                        <a target="_blank" href={'https://www.google.com/maps/search/?api=1&query=' + encodeURI(props.agent.address.address)}>
                            {salesagentfinder_data.lang.open}
                            <i className="far fa-external-link"></i>
                        </a>
                    </p>
                    <p className="contacts">
                        <Conditional condition={(props.agent.name !== '')} >
                            <span class="title">{salesagentfinder_data.lang.Contact}: </span><span>{cdm_core.fn.HTMLEntities(props.agent.name)}</span><br/>
                        </Conditional>
                        <Conditional condition={(props.agent.phone !== '')} >
                            <span class="title">{salesagentfinder_data.lang.Telephone}: </span><a href={'tel:'+props.agent.phone.split('').filter(x=>!['-',' ','(',')'].includes(x)).join('')}>{props.agent.phone}</a><br/>
                        </Conditional>
                        <Conditional condition={(props.agent.cell_phone !== '')} >
                            <span class="title">{salesagentfinder_data.lang.Cell} phone: </span><a href={'tel:'+props.agent.phone.split('').filter(x=>!['-',' ','(',')'].includes(x)).join('')}>{props.agent.cell_phone}</a><br/>
                        </Conditional>
                        <Conditional condition={(props.agent.fax !== '')} >
                            <span class="title">{salesagentfinder_data.lang.Fax}: </span><a href={'tel:'+props.agent.phone.split('').filter(x=>!['-',' ','(',')'].includes(x)).join('')}>{props.agent.fax}</a><br/>
                        </Conditional>
                        <Conditional condition={(props.agent.email !== '')} >
                            <span class="title">{salesagentfinder_data.lang.Email}: </span><a href={'mailto:'+props.agent.email} >{props.agent.email}</a><br/>
                        </Conditional>
                        <Conditional condition={(props.agent.website !== '')} >
                            <span class="title">{salesagentfinder_data.lang.Website}: </span><a  rel="noopener" target="_blank" href={addhttp(props.agent.website)} >{props.agent.website}</a><br></br>
                        </Conditional>
                        <Conditional condition={(props.agent.address.address !== '')} >
                            <span class="title">{salesagentfinder_data.lang.Address}: </span><a target="_blank" href={'https://www.google.com/maps/search/?api=1&query=' + encodeURI(props.agent.address.address)} >{props.agent.address.address}</a><br></br>
                        </Conditional> 

                        
                    </p>
                </div>
            </Conditional>
}


function initMap(){
    map = new google.maps.Map(document.getElementById("contactmap"), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 8,
        mapTypeId: 'custom_style',
        disableDefaultUI: true,
        mapTypeControlOptions: { mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'custom_style'] },
    });      
    
    map.mapTypes.set(
        'custom_style', 
        new google.maps.StyledMapType(GOOGLEMAP_CONFIG.style, {name: 'Custom Style'})
    );

    const bounds = new google.maps.LatLngBounds();
    map.markers = salesagentfinder_data.agents.map(agent => {
        if(agent.address !== false && agent.address !== null){
            bounds.extend(new google.maps.LatLng(agent.address.lat, agent.address.lng));

            return new google.maps.Marker({
                position: new google.maps.LatLng(agent.address.lat, agent.address.lng),
                map,
                agent:agent,
                infowindow: new google.maps.InfoWindow({content:document.createElement('div')}),
                title: agent.address.address
            });
        }
    });// A marker
    map.fitBounds(bounds,50); 


    map.togglemarker = (marker) => {
        map.panTo(marker.getPosition())
        map.setZoom(12)
        map.markers.forEach(marker => {marker.infowindow.close()})
        marker.infowindow.open(map, marker);
        cdm_core.react.render(<Agent agent={marker.agent} />,marker.infowindow.getContent())
    }

try {
    map.markers.forEach(marker => {
        marker.addListener('click',()=>{
            map.togglemarker(marker)
        })
    })
} catch(e) {
  //e; // => ReferenceError
  console.log('missingVar marker.addListener...');
}

    return map;
}




