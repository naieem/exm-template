//importe le hook addfilter et la localisation
const { addFilter } = wp.hooks;

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl, CheckboxControl, __experimentalNumberControl } = wp.components;

let activated = function(name){
    return [
        'core/image',
		'core/heading',
		'exm/custom-text',
		'core/buttons',
		//'core/embed', 
		'core/group',
		'core/column',
		'exm/step',
		'exm/compare-list'
		// 'core/columns'
    ].includes(name);
}
/**
 * Ajoute un props au block
 */
const addAnimateCSSOption = ( props, name ) => {
	if(activated(name)){
		// on ajoute l'attribut et on ajoute une valeur par défaut
		props.attributes = Object.assign( props.attributes, {
			AnimateCss: {
				type: 'object',
				default: {
					activated:false,
					name:'fadeIn',
					delay:0, //2,3,4,5 -- animate__delay-2s
					speed:'normal', // slow,slower,fast,faster -- animate__slow
					repeat:'no', // 1,2,3,infinite -- animate__repeat-2
					animate_on_sight:true // toAnimate
                },
			},
		} );
	}
	return props;
};addFilter( 'blocks.registerBlockType', 'AnimateCSS/attribute/AnimateCSS', addAnimateCSSOption );
/**
 * Ajoute un controle dans la sidebar pour le plugin 
 *
 */
const withAnimateCssControl = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		if (!activated(props.name)) { return  ( <BlockEdit { ...props } /> ) ; }

		return (
			<Fragment>
				<BlockEdit { ...props } />
				<InspectorControls>
						<PanelBody>
                        <h3>AnimateCSS</h3>
						<CheckboxControl
							label="Activated"
							checked={props.attributes.AnimateCss.activated}
							onChange={(val)=>{
								props.setAttributes({
									AnimateCss:{...props.attributes.AnimateCss,activated:val}
								})
							}}
                        />
						{(props.attributes.AnimateCss.activated)?
							<Fragment>
								<SelectControl
									label="Animation name"
									value={props.attributes.AnimateCss.name}
									options={animationNames}
									onChange={(val)=>{
										props.setAttributes({
											AnimateCss:{...props.attributes.AnimateCss,name:val}
										})
									}}
								/>
								<SelectControl
									label="Animation speed"
									value={props.attributes.AnimateCss.speed}
									options={[
										{value:'slow',label:'slow'},
										{value:'slower',label:'slower'},
										{value:'normal',label:'normal'},
										{value:'fast',label:'fast'},
										{value:'faster',label:'faster'}
									]}
									onChange={(val)=>{
										props.setAttributes({
											AnimateCss:{...props.attributes.AnimateCss,speed:val}
										})
									}}
								/>
								<SelectControl
									label="Animation delay"
									value={props.attributes.AnimateCss.delay}
									options={[
										{value:'0',label:'no delay'},
										{value:'2',label:'2s'},
										{value:'3',label:'3s'},
										{value:'4',label:'4s'},
										{value:'5',label:'5s'}
									]}
									onChange={(val)=>{
										props.setAttributes({
											AnimateCss:{...props.attributes.AnimateCss,delay:val}
										})
									}}
								/>
								<SelectControl
									label="Animation repeat"
									value={props.attributes.AnimateCss.repeat}
									options={[
										{value:'no',label:'no repeat'},
										{value:'1',label:'1x'},
										{value:'2',label:'2x'},
										{value:'3',label:'3x'},
										{value:'infinite',label:'infinite'}
									]}
									onChange={(val)=>{
										props.setAttributes({
											AnimateCss:{...props.attributes.AnimateCss,repeat:val}
										})
									}}
								/>
								<CheckboxControl
									label="Animate on viewport"
									checked={props.attributes.AnimateCss.animate_on_sight}
									onChange={(val)=>{
										props.setAttributes({
											AnimateCss:{...props.attributes.AnimateCss,animate_on_sight:val}
										})
									}}
								/>
							</Fragment>
						:null}
						
						</PanelBody>
						
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withAnimateCssControl' );
addFilter( 'editor.BlockEdit', 'AnimateCSS/with-AnimateCSS-control', withAnimateCssControl );

const AnitateCssExtraProps = ( saveElementProps, blockType, attributes ) => {
    if ( activated(blockType.name) ) { 
		if(attributes.AnimateCss.activated){
			Object.assign( saveElementProps, { className:  saveElementProps.className + ' animate__' + attributes.AnimateCss.name});
			if(attributes.AnimateCss.delay != 0){
				Object.assign( saveElementProps, { className:  saveElementProps.className + ' animate__delay-' + attributes.AnimateCss.delay+'s'});
			}
			if(attributes.AnimateCss.speed != 'normal'){
				Object.assign( saveElementProps, { className:  saveElementProps.className + ' animate__' + attributes.AnimateCss.speed});
			}
			if(attributes.AnimateCss.repeat != 'no'){
				Object.assign( saveElementProps, { className:  saveElementProps.className + ' animate__repeat-' + attributes.AnimateCss.repeat});
			}
			if(attributes.AnimateCss.animate_on_sight){
				Object.assign( saveElementProps, { className:  saveElementProps.className + ' toAnimate'});
			}
		}
	}
	return saveElementProps;
};addFilter( 'blocks.getSaveContent.extraProps', 'AnitateCss/get-save-content/extra-props', AnitateCssExtraProps );


const animationNames= [
	  //Attention seekers 
	{value:"bounce",label:"bounce"},
	{value:"flash",label:"flash"},
	{value:"pulse",label:"pulse"},
	{value:"rubberBand",label:"rubberBand"},
	{value:"shakeX",label:"shakeX"},
	{value:"shakeY",label:"shakeY"},
	{value:"headShake",label:"headShake"},
	{value:"swing",label:"swing"},
	{value:"tada",label:"tada"},
	{value:"wobble",label:"wobble"},
	{value:"jello",label:"jello"},
	{value:"heartBeat",label:"heartBeat"},
	//Back entrances
	{value:"backInDown",label:"backInDown"},
	{value:"backInLeft",label:"backInLeft"},
	{value:"backInRight",label:"backInRight"},
	{value:"backInUp",label:"backInUp"},
	//Back exits  
	{value:"backOutDown",label:"backOutDown"},
	{value:"backOutLeft",label:"backOutLeft"},
	{value:"backOutRight",label:"backOutRight"},
	{value:"backOutUp",label:"backOutUp"},
	//Bouncing entrances  
	{value:"bounceIn",label:"bounceIn"},
	{value:"bounceInDown",label:"bounceInDown"},
	{value:"bounceInLeft",label:"bounceInLeft"},
	{value:"bounceInRight",label:"bounceInRight"},
	{value:"bounceInUp",label:"bounceInUp"},
	//Bouncing exits
	{value:"bounceOut",label:"bounceOut"},
	{value:"bounceOutDown",label:"bounceOutDown"},
	{value:"bounceOutLeft",label:"bounceOutLeft"},
	{value:"bounceOutRight",label:"bounceOutRight"},
	{value:"bounceOutUp",label:"bounceOutUp"},
	//Fading entrances
	{value:"fadeIn",label:"fadeIn"},
	{value:"fadeInDown",label:"fadeInDown"},
	{value:"fadeInDownBig",label:"fadeInDownBig"},
	{value:"fadeInLeft",label:"fadeInLeft"},
	{value:"fadeInLeftBig",label:"fadeInLeftBig"},
	{value:"fadeInRight",label:"fadeInRight"},
	{value:"fadeInRightBig",label:"fadeInRightBig"},
	{value:"fadeInUp",label:"fadeInUp"},
	{value:"fadeInUpBig",label:"fadeInUpBig"},
	{value:"fadeInTopLeft",label:"fadeInTopLeft"},
	{value:"fadeInTopRight",label:"fadeInTopRight"},
	{value:"fadeInBottomLeft",label:"fadeInBottomLeft"},
	{value:"fadeInBottomRight",label:"fadeInBottomRight"},
	//Fading exits
	{value:"fadeOut",label:"fadeOut"},
	{value:"fadeOutDown",label:"fadeOutDown"},
	{value:"fadeOutDownBig",label:"fadeOutDownBig"},
	{value:"fadeOutLeft",label:"fadeOutLeft"},
	{value:"fadeOutLeftBig",label:"fadeOutLeftBig"},
	{value:"fadeOutRight",label:"fadeOutRight"},
	{value:"fadeOutRightBig",label:"fadeOutRightBig"},
	{value:"fadeOutUp",label:"fadeOutUp"},
	{value:"fadeOutUpBig",label:"fadeOutUpBig"},
	{value:"fadeOutTopLeft",label:"fadeOutTopLeft"},
	{value:"fadeOutTopRight",label:"fadeOutTopRight"},
	{value:"fadeOutBottomRight",label:"fadeOutBottomRight"},
	{value:"fadeOutBottomLeft",label:"fadeOutBottomLeft"},
	//Flippers
	{value:"flip",label:"flip"},
	{value:"flipInX",label:"flipInX"},
	{value:"flipInY",label:"flipInY"},
	{value:"flipOutX",label:"flipOutX"},
	{value:"flipOutY",label:"flipOutY"},
	//Lightspeed
	{value:"lightSpeedInRight",label:"lightSpeedInRight"},
	{value:"lightSpeedInLeft",label:"lightSpeedInLeft"},
	{value:"lightSpeedOutRight",label:"lightSpeedOutRight"},
	{value:"lightSpeedOutLeft",label:"lightSpeedOutLeft"},
	//Rotating entrances
	{value:"rotateIn",label:"rotateIn"},
	{value:"rotateInDownLeft",label:"rotateInDownLeft"},
	{value:"rotateInDownRight",label:"rotateInDownRight"},
	{value:"rotateInUpLeft",label:"rotateInUpLeft"},
	{value:"rotateInUpRight",label:"rotateInUpRight"},
	//Rotating exits
	{value:"rotateOut",label:"rotateOut"},
	{value:"rotateOutDownLeft",label:"rotateOutDownLeft"},
	{value:"rotateOutDownRight",label:"rotateOutDownRight"},
	{value:"rotateOutUpLeft",label:"rotateOutUpLeft"},
	{value:"rotateOutUpRight",label:"rotateOutUpRight"},
	//Specials    
	{value:"hinge",label:"hinge"},
	{value:"jackInTheBox",label:"jackInTheBox"},
	{value:"rollIn",label:"rollIn"},
	{value:"rollOut",label:"rollOut"},
	//Zooming entrances
	{value:"zoomIn",label:"zoomIn"},
	{value:"zoomInDown",label:"zoomInDown"},
	{value:"zoomInLeft",label:"zoomInLeft"},
	{value:"zoomInRight",label:"zoomInRight"},
	{value:"zoomInUp",label:"zoomInUp"},
	//Zooming exits
	{value:"zoomOut",label:"zoomOut"},
	{value:"zoomOutDown",label:"zoomOutDown"},
	{value:"zoomOutLeft",label:"zoomOutLeft"},
	{value:"zoomOutRight",label:"zoomOutRight"},
	{value:"zoomOutUp",label:"zoomOutUp"},
	//Sliding entrances  
	{value:"slideInDown",label:"slideInDown"},
	{value:"slideInLeft",label:"slideInLeft"},
	{value:"slideInRight",label:"slideInRight"},
	{value:"slideInUp",label:"slideInUp"},
	//Sliding exits  
	{value:"slideOutDown",label:"slideOutDown"},
	{value:"slideOutLeft",label:"slideOutLeft"},
	{value:"slideOutRight",label:"slideOutRight"},
	{value:"slideOutUp",label:"slideOutUp"},
];

