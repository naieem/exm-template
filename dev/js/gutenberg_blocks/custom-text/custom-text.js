const { registerBlockType } = wp.blocks;

const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;
const { PanelBody,TextareaControl,SelectControl, ToggleControl, TextControl } = wp.components;



registerBlockType(customtext_data.suffix+'/custom-text',{
	title:customtext_data.lang.title,
	icon: 'editor-paragraph',
    category: 'common',
    className:'custom-text',
    
	supports: {
		color:{
			background:true,
			gradient:false,
			text:true
        },
        align: true,
        alignWide: true,
	},
	attributes:{
		text:{
			type: 'string',
			default: customtext_data.lang.default_title
        },
        fontSize:{
			type: 'string',
			default: 'fs-16'
        },
        fontWeight:{
			type: 'string',
			default: 'fw-normal'
        },
        tag:{
			type: 'string',
			default: 'p'
        },
        paddingtop:{
            type: 'string',
            default: 's-t-10'
        },
        paddingbottom:{
            type: 'string',
            default: 's-b-10'
        },
        textAlign:{
            type: 'string',
            default: 'ta-left'
        },
        responsive:{
            type:'boolean',
            default:false,
        },
        responsiveCenter:{
            type:'boolean',
            default:false,
        },
        lineHeight:{
            type:'string',
            default:'ln-1-15'
		},
		link: {
			type:'string',
			default:''
		}
    },

	edit:(props)=>{
		return 	<Fragment>
             
                    <props.attributes.tag  className={
                        props.className 
                        + ' ' + 
                        props.attributes.fontSize 
                        + ' ' +
                        props.attributes.lineHeight 
                        + ' ' +
                        props.attributes.fontWeight
                        + ' ' +
                        props.attributes.paddingtop
                        + ' ' +
                        props.attributes.paddingbottom
                        + ' ' +
                        props.attributes.textAlign+
                        ((props.attributes.responsive)?' t-responsive':'')+
                        ((props.attributes.responsiveCenter)?' c-responsive':'')
                    } style={props.attributes.style}>
                        {(props.isSelected)?
                            <TextareaControl
                                value={ props.attributes.text }
                                onChange={ ( string ) => {  
                                    props.setAttributes({
                                        text:string
                                    })
                                }}
                            />
                            :
							((props.attributes.link !== '')?
							<a href={props.attributes.link} >{props.attributes.text}</a>
							:	
							props.attributes.text)
						
                        }
                
					</props.attributes.tag>
                    <InspectorControls>
                        <PanelBody>
                            <h3>{customtext_data.lang.title}</h3>
                            <SelectControl
                                label={customtext_data.lang.select_label}
                                value={props.attributes.fontSize}
                                onChange={ ( fs ) => {  
                                    props.setAttributes({
                                        fontSize:fs
                                    })
                                }}
                                options={[
                                    // {value:'fs-14',label:'14px'},
                                    // {value:'fs-16',label:'16px'},
                                    {value:'fs-18',label:'Small'},
                                    {value:'fs-20',label:'Medium'},
                                    {value:'fs-36',label:'Large'},
                                    // {value:'fs-40',label:'40px'},
                                    {value:'fs-70',label:'X-Large'},
                                ]}
                            />
                            <SelectControl
                                label={customtext_data.lang.select_label_fw}
                                value={props.attributes.fontWeight}
                                onChange={ ( fw ) => {  
                                    props.setAttributes({
                                        fontWeight:fw
                                    })
                                }}
                                options={[
                                    {value:'fw-lighter',label:customtext_data.lang.w_lighter},
                                    {value:'fw-normal',label:customtext_data.lang.w_normal},
                                    {value:'fw-bold',label:customtext_data.lang.w_bold},
                                    // {value:'fw-bolder',label:customtext_data.lang.w_bolder}
                                ]}
                            />
                            <SelectControl
                                label={customtext_data.lang.select_label_tag}
                                value={props.attributes.tag}
                                onChange={ ( tag ) => {  
                                    props.setAttributes({
                                        tag:tag
                                    })
                                }}
                                options={[
                                    {value:'p',label:customtext_data.lang.p_tag},
                                    {value:'h1',label:customtext_data.lang.h1_tag},
                                    {value:'h2',label:customtext_data.lang.h2_tag},
                                    {value:'h3',label:customtext_data.lang.h3_tag},
                                    {value:'h4',label:customtext_data.lang.h4_tag},
                                    {value:'h5',label:customtext_data.lang.h5_tag},
                                    {value:'h6',label:customtext_data.lang.h6_tag}
                                ]}
                            />
                            <SelectControl
                                label={customtext_data.lang.select_label_spacing_top}
                                value={props.attributes.paddingtop}
                                onChange={ ( space ) => {  
                                    props.setAttributes({
                                        paddingtop:space
                                    })
                                }}
                                options={[
                                    {value:'s-t-0', label:'None'},
                                    // {value:'s-t-5', label:'5px'},
                                    // {value:'s-t-10',label:'10px'},
                                    {value:'s-t-15',label:'Small'},
                                    {value:'s-t-20',label:'Medium'},
                                    // {value:'s-t-30',label:'30px'},
                                    {value:'s-t-40',label:'Large'}
                                ]}
                            />
                            <SelectControl
                                label={customtext_data.lang.select_label_spacing_bottom}
                                value={props.attributes.paddingbottom}
                                onChange={ ( space ) => {  
                                    props.setAttributes({
                                        paddingbottom:space
                                    })
                                }}
                                options={[
                                    {value:'s-b-0', label:'None'},
                                    // {value:'s-b-5', label:'5px'},
                                    // {value:'s-b-10',label:'10px'},
                                    {value:'s-b-15',label:'Small'},
                                    {value:'s-b-20',label:'Medium'},
                                    // {value:'s-b-30',label:'30px'},
                                    {value:'s-b-40',label:'Large'}
                                ]}
                            />
                            <SelectControl
                                label={customtext_data.lang.select_label_align}
                                value={props.attributes.textAlign}
                                onChange={ ( align ) => {  
                                    props.setAttributes({
                                        textAlign:align
                                    })
                                }}
                                options={[
                                    {value:'ta-left', label:customtext_data.lang.ta_left},
                                    {value:'ta-center', label:customtext_data.lang.ta_center},
                                    {value:'ta-right',label:customtext_data.lang.ta_right}
                                ]}
                            />
                            {/* <SelectControl
                                label={customtext_data.lang.select_label_ln}
                                value={props.attributes.lineHeight}
                                onChange={ ( val ) => {  
                                    props.setAttributes({
                                        lineHeight:val
                                    })
                                }}
                                options={[
                                    {value:'ln-1',label:'1'},
                                    {value:'ln-1-15',label:'1,15'},
                                    {value:'ln-1-25',label:'1,25'},
                                    {value:'ln-1-33',label:'1,33'},
                                    {value:'ln-1-5',label:'1,5'},
                                    {value:'ln-1-75',label:'1,75'},
                                    {value:'ln-1-90',label:'1,9'},
                                    {value:'ln-2',label:'2'}
                                ]}
                            /> */}  
                            <ToggleControl
                                label={customtext_data.lang.responsive_title}
                                help={customtext_data.lang.responsive_help}
                                checked={props.attributes.responsive}
                                onChange={check => {
                                    props.setAttributes({
                                        responsive:check
                                    })
                                }}
                            />
                            <ToggleControl
                                label={customtext_data.lang.responsiveCenter_title}
                                help={customtext_data.lang.responsiveCenter_help}
                                checked={props.attributes.responsiveCenter}
                                onChange={check => {
                                    props.setAttributes({
                                        responsiveCenter:check
                                    })
                                }}
                            />
							<TextControl
								label={customtext_data.lang.text_area_link}
								value={props.attributes.link}
								onChange={val => {
                                    props.setAttributes({
                                        link:val
                                    })
                                }}
							/>
                        </PanelBody>
                    </InspectorControls>
				</Fragment>
	},
    save:(props) => {

        return 	<props.attributes.tag  className={
                    props.className 
                    + ' ' + 
                    props.attributes.fontSize 
                    + ' ' +
                    props.attributes.lineHeight 
                    + ' ' +
                    props.attributes.fontWeight
                    + ' ' +
                    props.attributes.paddingtop
                    + ' ' +
                    props.attributes.paddingbottom
                    + ' ' +
                    props.attributes.textAlign+
                    ((props.attributes.responsive)?' t-responsive':'')+
                    ((props.attributes.responsiveCenter)?' c-responsive':'')
                } style={props.attributes.style}>
					{	(props.attributes.link !== '')?
						<a href={props.attributes.link} >{props.attributes.text}</a>
						:	
						props.attributes.text
					}	
				</props.attributes.tag>; 
	},
});