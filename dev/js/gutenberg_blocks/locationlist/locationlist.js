const { registerBlockType } = wp.blocks;

const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;

registerBlockType(locationlist_data.suffix+'/locationlist',{
	title:locationlist_data.lang.title,
	icon: 'location-alt',
    category: 'widgets',
    className:'locationlist',
	edit:(props)	=> {
        //a chaque component.mount()
        React.useEffect(() =>build_locationlist())
        return  <Fragment>
                    <div className={props.className} >
                        <div className="locations"></div>
                        <div id="contactmap" ></div>
                        
                    </div>
                    <script src={locationlist_data.url_render_script}></script>
                </Fragment>
    },//<div style={{backgroundColor:'#eeeeee'}} className={props.className + ' isEditor'} ><p>Location Map</p></div>, 
    save:(props) 	=> {
        return  <div className={props.className} >
                    <div className="locations"></div>
                    <div id="contactmap" ></div>
                </div>
    }, 
});