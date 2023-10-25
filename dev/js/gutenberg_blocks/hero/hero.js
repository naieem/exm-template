const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const { InspectorControls, InnerBlocks  } = wp.editor;
const { PanelBody,TextControl,CheckboxControl,Button } = wp.components;
const { ImageWP } = cdm_core.components;
const { MediaUploadCheck,MediaUpload } = wp.blockEditor;
const ALLOWED_MEDIA_TYPES = [ 'image' ];

const Hero = (props) => {
    return <section className={props.className + ' isEditor'} >
                {   props.attributes.urlbg !== '' ?
                    <figure class="wp-block-embed is-type-video is-provider-vimeo wp-block-embed-vimeo wp-embed-aspect-16-9 wp-has-aspect-ratio">
                        <div class="wp-block-embed__wrapper">
                            <iframe loading="lazy" title="EXM Manufacturing Ltd." src={props.attributes.urlbg} width="1200" height="675" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </figure>
                    :null}
                <div className={'content'}>
                    {props.isSave ? <InnerBlocks.Content /> : <InnerBlocks />}
                    
                    <div className={'bottom'}>
                        <div className="left test-naieem">
                            <div className="wp-block-buttons">
                                <div className="wp-block-button">
                                    <a  className="wp-block-button__link has-color-1-color has-color-5-background-color has-text-color has-background"
                                        style={{borderRadius:'5px'}}
                                        target={((props.attributes.btn_1.target)?'_blank':'_self')} 
                                        rel="noopener"
                                        href={props.attributes.btn_1.link }>{props.attributes.btn_1.text} <i className="fas fa-external-link"></i></a>
                                </div>
                                <div className="wp-block-button is-style-outline">
                                    <a  className="wp-block-button__link has-color-1-color has-color-2-background-color has-text-color has-background"
                                        style={{borderRadius:'5px'}}
                                        target={((props.attributes.btn_2.target)?'_blank':'_self')} 
                                        rel="noopener"
                                        href={props.attributes.btn_2.link }>{props.attributes.btn_2.text}</a>
                                </div>
                            </div>
                        </div>
                        <div className="right">  
                            <ImageWP src={props.attributes.imagebottomRight.url} /> 
                        </div>
                    </div>
                </div>
            </section>
}


registerBlockType(hero_data.suffix+'/hero',{
	title:hero_data.lang.title,
	icon: 'embed-photo',
    category: 'layout',
    className:'hero',
    supports: {
        align: [ 'full' ]
    },
    attributes:{
        urlbg:{
            type:'string',
            default: ''
        },
        imagebottomRight:{
            type:'object',
            default: {
                id:null,
                name:'',
                url:''
            }
        },
        title:{
            type:"string",
            default:"title"
        },
        tblue:{
            type:"string",
            default:""
        },
        align: {
            type: 'string',
            default: 'full'
        },
        btn_1:{
            type:'object',
            default:{
                link:'',
                text:'',
                target:''
            }
        },
        btn_2:{
            type:'object',
            default:{
                link:'',
                text:'',
                target:false
            }
        }
    },

	edit:(props)=>{
		return 	<Fragment>
                    <Hero isSave={false} { ...props} />
                    <InspectorControls>
                        <PanelBody>
                            <h3>Background video</h3>
                            <TextControl
                                label={'The background video'}
                                value={props.attributes.urlbg}
                                onChange={value => {
                                    props.setAttributes({
                                        urlbg:value
                                    })
                                }}
                            />
                            
                        </PanelBody>
                        <PanelBody>
                            <h3>Content</h3>
                            <TextControl
                                label={'Title'}
                                value={props.attributes.title}
                                onChange={value => {
                                    props.setAttributes({
                                        title:value
                                    })
                                }}
                            />
                            <TextControl
                                label={'Blue part of the title'}
                                value={props.attributes.tblue}
                                onChange={value => {
                                    props.setAttributes({
                                        tblue:value
                                    })
                                }}
                            />

                            <h3>Button 1</h3>
                            <TextControl
                                label="Text"
                                value={props.attributes.btn_1.text}
                                onChange={val=>props.setAttributes({btn_1:{...props.attributes.btn_1,text:val}})}
                            />
                            <TextControl
                                label="Link"
                                value={props.attributes.btn_1.link}
                                onChange={val=>props.setAttributes({btn_1:{...props.attributes.btn_1,link:val}})}
                            />
                            <CheckboxControl
                                label="Open in a new window"
                                checked={props.attributes.btn_1.target}
                                onChange={val=>props.setAttributes({btn_1:{...props.attributes.btn_1,target:val}})}
                            />
                            <h3>Button 2</h3>
                            <TextControl
                                label="Text"
                                value={props.attributes.btn_2.text}
                                onChange={val=>props.setAttributes({btn_2:{...props.attributes.btn_2,text:val}})}
                            />
                            <TextControl
                                label="Link"
                                value={props.attributes.btn_2.link}
                                onChange={val=>props.setAttributes({btn_2:{...props.attributes.btn_2,link:val}})}
                            />
                            <CheckboxControl
                                label="Open in a new window"
                                checked={props.attributes.btn_2.target}
                                onChange={val=>props.setAttributes({btn_2:{...props.attributes.btn_2,target:val}})}
                            />
                            <h3>Bottom right Image</h3>
                            <p>{props.attributes.imagebottomRight.name}</p>
                            <MediaUploadCheck>
                                <MediaUpload
                                    onSelect={ ( media ) => {
                                        props.setAttributes({
                                            imagebottomRight:{
                                                id:media.id,
                                                name:media.title,
                                                url:media.url
                                            }
                                        })
                                    }}
                                    allowedTypes={ ALLOWED_MEDIA_TYPES }
                                    value={ props.attributes.imagebottomRight.id }
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
    save:(props) => <Hero isSave={true} { ...props} />
});