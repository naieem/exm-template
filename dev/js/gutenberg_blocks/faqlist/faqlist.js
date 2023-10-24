const { registerBlockType } = wp.blocks;

const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;

registerBlockType(faqlist_data.suffix+'/faqlist',{
	title:faqlist_data.lang.title,
	icon: 'location-alt',
    category: 'widgets',
    className:'faqlist',
	edit:(props)	=> {
        //a chaque component.mount() 
        React.useEffect(() =>build_faqlist())
        return  <Fragment>
                    <div className={props.className} >
                    </div>
                    <script src={faqlist_data.url_render_script}></script>
                </Fragment>
    },
    save:(props) 	=> {
        return  <div className={props.className} >
 
                </div>
    }, 
});

