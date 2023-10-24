const { registerBlockType } = wp.blocks;

const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;

registerBlockType(shopbyenclosure_data.suffix+'/shop-by-enclosure',{
	title:shopbyenclosure_data.lang.title,
	icon: 'location-alt',
    category: 'widgets',
    className:'shop-by-enclosure',
	edit:(props)	=> {
        //a chaque component.mount() 
        React.useEffect(() =>build_shop_by_enclosure(),[])
        return  <Fragment>
                    <div className={props.className} >
                    </div>
                    <script src={shopbyenclosure_data.url_render_script}></script>
                </Fragment>
    },
    save:(props) 	=> {
        return  <div className={props.className} >
 
                </div>
    }, 
});

