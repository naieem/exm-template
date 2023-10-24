const { registerBlockType } = wp.blocks;

const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;

registerBlockType(knowledgebase_data.suffix+'/knowledgebase',{
	title:knowledgebase_data.lang.title,
	icon: 'location-alt',
    category: 'widgets',
    className:'knowledgebase',
	edit:(props)	=> {
        //a chaque component.mount() 
        React.useEffect(() =>build_knowledgebase())
        return  <Fragment>
                    <div className={props.className} >
                    </div>
                    <script src={knowledgebase_data.url_render_script}></script>
                </Fragment>
    },
    save:(props) 	=> {
        return  <div className={props.className} >
 
                </div>
    }, 
});

