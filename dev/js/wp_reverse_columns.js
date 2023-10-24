//importe le hook addfilter et la localisation
const { addFilter } = wp.hooks;

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, CheckboxControl } = wp.components;

let activated_reverse = function(name){
    return [
		'core/columns'
    ].includes(name);
}
/**
 * Ajoute un props au block
 */
const addRellaxOption = ( props, name ) => {
	if(activated_reverse(name)){
		// on ajoute l'attribut et on ajoute une valeur par défaut
		props.attributes = Object.assign( props.attributes, {
			reverse_cols: {
				type: 'boolean',
				default: false,
			},
		} );
	}
	return props;
};addFilter( 'blocks.registerBlockType', 'rellax/attribute/rellax', addRellaxOption );
/**
 * Ajoute un controle dans la sidebar pour le plugin 
 *
 */
const withReverseControl = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		if (!activated_reverse(props.name)) { return  ( <BlockEdit { ...props } /> ) ; }

		return (
			<Fragment>
				<BlockEdit { ...props } />
				<InspectorControls>
						<PanelBody>
                        <h3>Reverse Columns on mobile</h3>
                        <CheckboxControl
							label="Reverse"
							checked={props.attributes.reverse_cols}
							onChange={  (val)=>{
                                            props.setAttributes({
                                                reverse_cols:val
                                            }) 
                                        }
                            }
                        />
						</PanelBody>
						
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withReverseControl' );
addFilter( 'editor.BlockEdit', 'Rellax/with-Reverse-control', withReverseControl );

const ReverseExtraProps = ( saveElementProps, blockType, attributes ) => {
    if ( activated_reverse(blockType.name) ) { 
		if(attributes.reverse_cols){
			let reverse = attributes.reverse_cols;
			if(reverse){
				Object.assign( saveElementProps, { 
					className:  saveElementProps.className + ((reverse)?' reverse-mobile':''),
				});

			}
		}
    }
	return saveElementProps;
};addFilter( 'blocks.getSaveContent.extraProps', 'Reverse/get-save-content/extra-props', ReverseExtraProps );



