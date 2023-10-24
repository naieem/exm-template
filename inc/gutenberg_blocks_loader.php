<?php


/**
 * Permet d'enregistrer un bloc gutenberg sans répéter trop de code
 * auteur: Antoine Bernier
 *
 * @param string $suffix // suffixe du block (namespace)
 * @param string $name // nom du block //case sensitive pour retrouver les fichiers
 * @param boolean $editor_style // true si un style d'éditeur a été fourni
 * @param array $dependencies_js // dépendance JS	
 * @param array $dependencies_css // dépendance CSS
 * @return void
 */
function cdm_register_gutenberg_block( array $params){

	//VALIDATION

	//required check
	if(!isset($params['suffix'],$params['name']) ) {
		return false;
	}else{
		if($params['suffix'] === '' || $params['name'] === '') {
			return false;
		}
	}
	//set dafault or fail on wrong type
	if(isset($params['editor_style'])){
		if(!is_bool($params['editor_style'])){
			return false;
		}
	}else{  $params['editor_style'] = false;  }

	if(isset($params['dependencies_js'])){
		if(!is_array($params['dependencies_js'])){
			return false;
		}
	}else{  $params['dependencies_js'] = [];  }

	if(isset($params['dependencies_css'])){
		if(!is_array($params['dependencies_css'])){
			return false;
		}
	}else{  $params['dependencies_css'] = [];  }

	if(isset($params['localize'])){
		if(!is_array($params['localize'])){
			return false;
		}
	}else{  $params['localize'] = [];  }

	if(isset($params['render_script'])){
		if(!is_bool($params['render_script'])){
			return false;
		}
	}else{  $params['render_script'] = false;  }

	// VALIDATION END


	wp_register_script( 
		$params['name'].'-js', 	
		get_template_directory_uri() . "/js/gutenberg_blocks/".$params['name']."/".$params['name'].".min.js",
		($params['render_script'])?array_merge($params['dependencies_js'],[$params['name'].'-render-js']):$params['dependencies_js']
	);
	wp_add_inline_script( $params['name'].'-js' , str_replace('-','', $params['name']).'_data'.'='.json_encode(array_merge(['suffix'=>$params['suffix']],$params['localize'])),'before' );

	wp_register_style( 
		$params['name'].'-css',	
		get_template_directory_uri() . "/js/gutenberg_blocks/".$params['name']."/".$params['name'].".min.css",
		($params['name']!= 'gutenberg-core')?array_merge($params['dependencies_css'],['gutenberg-core-css']):$params['dependencies_css']
	);
	if($params['editor_style']){
		wp_register_style( 
			$params['name'].'-editor-css',	
			get_template_directory_uri() . "/js/gutenberg_blocks/".$params['name']."/".$params['name']."-editor.min.css",
			$params['dependencies_css']
		);
	}
	//only if the block is on the current page
	add_action( 'enqueue_block_assets', function() use($params){
		if ( has_block( $params['suffix']."/".$params['name'] ) || is_admin() ) {
			if($params['render_script']){
				wp_register_script( 
					$params['name'].'-render-js', 	
					get_template_directory_uri() . "/js/gutenberg_blocks/".$params['name']."/".$params['name']."-render.min.js",
					array_merge($params['dependencies_js'],['gutenberg-core-js'])
				);
				wp_add_inline_script( $params['name'].'-render-js' , str_replace('-','', $params['name']).'_data'.'='.json_encode(array_merge(['suffix'=>$params['suffix']],$params['localize'])) , 'before' );
			}
		}
	});
	add_action( 'enqueue_block_editor_assets', function() use ($params){
		if($params['render_script']){
			wp_register_script( 
				$params['name'].'-render-js', 	
				get_template_directory_uri() . "/js/gutenberg_blocks/".$params['name']."/".$params['name']."-render.min.js",
				array_merge($params['dependencies_js'],['gutenberg-core-js'])
			);
			wp_add_inline_script( $params['name'].'-render-js' , str_replace('-','', $params['name']).'_data'.'='.json_encode(array_merge(['suffix'=>$params['suffix']],$params['localize'])) , 'before' );
		}
	} );
	
	
	wp_add_inline_script( $params['name'].'-js' ,str_replace('-','', $params['name']).'_data'.'='.json_encode( array_merge(['suffix'=>$params['suffix']],$params['localize'])),'before' );
	
    return register_block_type( $params['suffix']."/".$params['name'], array(
		'editor_script' => 	$params['name']."-js",
		'style' => 			$params['name']."-css",
		'script'=>			($params['render_script'])?$params['name'].'-render-js':null,
		'editor_style' => 	($params['editor_style'])?$params['name']."-editor-css":$params['name']."-css"
		
    ) );
}

include(__DIR__.'/gutenberg_blocks_list.php' );