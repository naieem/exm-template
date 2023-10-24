const { registerBlockType } = wp.blocks;
const { ImageWP } = cdm_core.components;
const { Fragment } = wp.element;
const { InspectorControls,InnerBlocks } = wp.editor;
const { PanelBody , TextControl  } = wp.components;


const Step = props => {
    return  <div className={props.className} >
                <figure>
                    <ImageWP src={props.attributes.image.url} />
                </figure>
                <h4>{props.attributes.title}</h4>
                <p className="action">{props.attributes.action}</p>

                {props.edited ? <InnerBlocks /> : <InnerBlocks.Content  />}
            </div>
}

registerBlockType(step_data.suffix+'/step',{
	title:step_data.lang.title,
	icon: 'table-col-before',
    category: 'layout',
    className:'step',
	attributes:{
        image:{
            type:'object',
            default: {
                id:null,
                name:'',
                url:''
            }
        },
        title : {
            type:'string',
            default:'Title'
        },
        action : {
            type:'string',
            default:'Action'
        },
        text : {
            type:'string',
            default:'Text'
        }
    },

    
	edit:(props)=>{
		return 	<Fragment>
                    <Step {...props} edited={true} />
                    <InspectorControls>
                        <PanelBody>
                            <h3>Step</h3>
                            <TextControl
                                label="title"
                                value={props.attributes.title}
                                onChange={(value)=>props.setAttributes({title:value})}
                            />
                            <TextControl
                                label="Action"
                                value={props.attributes.action}
                                onChange={(value)=>props.setAttributes({action:value})}
                            />
                            {/* <TextControl
                                label="Text"
                                value={props.attributes.text}
                                onChange={(value)=>props.setAttributes({text:value})}
                            /> */}
                            <h3>Top image</h3>
                            <p>{props.attributes.image.name}</p>
                            <MediaUploadCheck>
                                <MediaUpload
                                    onSelect={ ( media ) => {
                                        props.setAttributes({
                                            image:{
                                                id:media.id,
                                                name:media.title,
                                                url:media.url
                                            }
                                        })
                                    }}
                                    allowedTypes={ ALLOWED_MEDIA_TYPES }
                                    value={ props.attributes.image.id }
                                    render={ ( { open } ) => (
                                        <Button isPrimary onClick={ open }>
                                            Open Media Library
                                        </Button>
                                    ) }
                                />
                            </MediaUploadCheck>
                        </PanelBody>
                    </InspectorControls>
				</Fragment>
	},
    save:(props) =>  <Step {...props} />
});