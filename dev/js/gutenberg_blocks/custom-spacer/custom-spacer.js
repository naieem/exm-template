const { registerBlockType } = wp.blocks;

const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;
const { PanelBody,SelectControl   } = wp.components;

registerBlockType(customspacer_data.suffix+'/custom-spacer',{
	title:customspacer_data.lang.title,
	icon: 'fullscreen-alt',
    category: 'layout',
    className:'custom-spacer',
 
	attributes:{
		height_desktop:{
			type: 'string',
			default: 'hd-50'
        },
        height_mobile:{
			type: 'string',
			default: 'hm-50'
        }
    },

    
	edit:(props)=>{
		return 	<Fragment>
             
                    <div style={{backgroundColor:'#eeeeee',opacity:((props.isSelected)?"1":'0.2')}} className={props.className +' '+ props.attributes.height_desktop +' '+ props.attributes.height_mobile + ' isEditor'} >
                        <span>Spacer <br/> desktop:{props.attributes.height_desktop.replace('hd-','')}px <br/> mobile:{props.attributes.height_mobile.replace('hm-','')}px </span>
                    </div>
                    <InspectorControls>
                        <PanelBody>
                            <h3>{customspacer_data.lang.title}</h3>
                            <SelectControl
                                label={customspacer_data.lang.height_desktop}
                                value={props.attributes.height_desktop}
                                onChange={val => {
                                    props.setAttributes({
                                        'height_desktop':val
                                    })
                                }}
                                options={[
                                    {value:'hd-5',label:'5px'},
                                    {value:'hd-10',label:'10px'},
                                    {value:'hd-15',label:'15px'},
                                    {value:'hd-20',label:'20px'},
                                    {value:'hd-30',label:'30px'},
                                    {value:'hd-40',label:'40px'},
                                    {value:'hd-50',label:'50px'},
                                    {value:'hd-60',label:'60px'},
                                    {value:'hd-70',label:'70px'},
                                    {value:'hd-80',label:'80px'},
                                    {value:'hd-90',label:'90px'},
                                    {value:'hd-100',label:'100px'},
                                    {value:'hd-150',label:'150px'},
                                    {value:'hd-200',label:'200px'},
                                ]}
                            />
                            <SelectControl
                                label={customspacer_data.lang.height_mobile}
                                value={props.attributes.height_mobile}
                                onChange={val => {
                                    props.setAttributes({
                                        'height_mobile':val
                                    })
                                }}
                                options={[
                                    {value:'hm-5',label:'5px'},
                                    {value:'hm-10',label:'10px'},
                                    {value:'hm-15',label:'15px'},
                                    {value:'hm-20',label:'20px'},
                                    {value:'hm-30',label:'30px'},
                                    {value:'hm-40',label:'40px'},
                                    {value:'hm-50',label:'50px'},
                                    {value:'hm-60',label:'60px'},
                                    {value:'hm-70',label:'70px'},
                                    {value:'hm-80',label:'80px'},
                                    {value:'hm-90',label:'90px'},
                                    {value:'hm-100',label:'100px'},
                                    {value:'hm-150',label:'150px'},
                                    {value:'hm-200',label:'200px'},
                                ]}
                            />
                        </PanelBody>
                    </InspectorControls>
				</Fragment>
	},
    save:(props) => <div className={props.className +' '+ props.attributes.height_desktop +' '+ props.attributes.height_mobile} ></div>, 
});