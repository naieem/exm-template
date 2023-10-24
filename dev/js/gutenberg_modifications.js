

//importe le hook addfilter et la localisation
const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;


/**
 * Ajoute un props au block
 */
const addBorderOption = ( props, name ) => {
	if(name == 'core/image'){
		// on ajoute l'attribut et on ajoute une valeur par défaut
		props.attributes = Object.assign( props.attributes, {
			imgClass: {
				type: 'string',
				default: '',
			},
		} );
	}
	return props;
};addFilter( 'blocks.registerBlockType', 'imgClass/attribute/imgClass', addBorderOption );
/**
 * Ajoute un controle dans la sidebar pour le plugin 
 *
 */
const withimgClassControl = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		if ( props.name != 'core/image' ) { return  ( <BlockEdit { ...props } /> ) ; }
		return (
			<Fragment>
				<BlockEdit { ...props } className={ props.attributes.imgClass }  />
				<InspectorControls>
						<PanelBody>
                        <h3>Overflow image</h3>
						<SelectControl
							label={ __( 'direction','theme-client' ) }
							value={ props.attributes.imgClass }
							options={ [
								{label:'No overflow',value:''},
								{label:'top',value:' img-overflow-top'},
								{label:'bottom',value:' img-overflow-bottom'},
							] }
							onChange={ ( newVal) => {
								props.setAttributes( {
									imgClass: newVal
								} );
							} }
						/>
						</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withimgClassControl' );
addFilter( 'editor.BlockEdit', 'imgClass/with-imgClass-control', withimgClassControl );

const imgClassExtraProps = ( saveElementProps, blockType, attributes ) => {
    if ( blockType.name == 'core/image' ) { 
        Object.assign( saveElementProps, { className:  saveElementProps.className + attributes.imgClass } );
    }
	return saveElementProps;
};addFilter( 'blocks.getSaveContent.extraProps', 'imgClass/get-save-content/extra-props', imgClassExtraProps );



