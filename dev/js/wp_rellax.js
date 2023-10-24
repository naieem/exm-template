//importe le hook addfilter et la localisation
const { addFilter } = wp.hooks;

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl, CheckboxControl, __experimentalNumberControl } = wp.components;

let activated_rellax = function(name){
	
    return [
        'core/image',
		'core/heading',
		'exm/custom-text',
		'core/buttons',
		//'core/embed', 
		'core/group',
		'core/column',
		'core/columns'
    ].includes(name);
}
/**
 * Ajoute un props au block
 */
const addRellaxOption = ( props, name ) => {
	if(activated_rellax(name)){
		// on ajoute l'attribut et on ajoute une valeur par défaut
		props.attributes = Object.assign( props.attributes, {
			Rellax: {
				type: 'object',
				default: {
                    activated:false,
                    speed: -2,
                    center: false,
                    wrapper: null,
                    round: true,
                    vertical: true,
                    horizontal: false
                },
			},
		} );
	}
	return props;
};addFilter( 'blocks.registerBlockType', 'rellax/attribute/rellax', addRellaxOption );
/**
 * Ajoute un controle dans la sidebar pour le plugin 
 *
 */
const withRellaxControl = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		if (!activated_rellax(props.name)) { return  ( <BlockEdit { ...props } /> ) ; }

		const setActivated = (val) =>{
			props.setAttributes({
				Rellax:{...props.attributes.Rellax,activated:val}
			})
		}
		const setSpeed = (val) =>{
			if(val >= -10 && val <=10) {
				props.setAttributes({
					Rellax:{...props.attributes.Rellax,speed:val}
				})
			}
		}

		return (
			<Fragment>
				<BlockEdit { ...props } />
				<InspectorControls>
						<PanelBody>
                        <h3>Parralax</h3>
                        <CheckboxControl
							label="Activated"
							checked={props.attributes.Rellax.activated}
							onChange={setActivated}
                        />
						{(props.attributes.Rellax.activated)?
								<__experimentalNumberControl
									label="Parralax speed"
									onChange={ setSpeed }
									value={ props.attributes.Rellax.speed }
								/>
						:null}
						</PanelBody>
						
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withRellaxControl' );
addFilter( 'editor.BlockEdit', 'Rellax/with-Rellax-control', withRellaxControl );

const RellaxExtraProps = ( saveElementProps, blockType, attributes ) => {
    if ( activated_rellax(blockType.name) ) { 
		if(attributes.Rellax){
			let rellax = attributes.Rellax;
			if(rellax.activated){
				Object.assign( saveElementProps, { 
					className:  saveElementProps.className + ' rellax',
					'data-rellax-speed':rellax.speed
				});

			}
		}
    }
	return saveElementProps;
};addFilter( 'blocks.getSaveContent.extraProps', 'Rellax/get-save-content/extra-props', RellaxExtraProps );



