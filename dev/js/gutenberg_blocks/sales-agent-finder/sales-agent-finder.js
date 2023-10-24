const { registerBlockType } = wp.blocks;

const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;

registerBlockType(salesagentfinder_data.suffix+'/sales-agent-finder',{
	title:salesagentfinder_data.lang.title,
	icon: 'location-alt',
    category: 'widgets',
    className:'salesagentfinder',
	edit:(props)	=> {
        //a chaque component.mount()
        React.useEffect(() =>build_salesagentfinder())
        return  <Fragment>
                    <div className={props.className} >
                        <div className="filters"></div>
                        <div id="contactmap" ></div>
                        <div className="agents" ></div>
                        
                    </div>
                    <script src={salesagentfinder_data.url_render_script}></script>
                </Fragment>
    },//<div style={{backgroundColor:'#eeeeee'}} className={props.className + ' isEditor'} ><p>Location Map</p></div>, 
    save:(props) 	=> {
        return  <div className={props.className} >
                    <div className="filters"></div>
                    <div id="contactmap" ></div>
                    <div className="agents" ></div>
                </div>
    }, 
});