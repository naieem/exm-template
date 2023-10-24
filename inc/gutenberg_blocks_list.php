<?php 


function theme_client_register_gutenberg_blocks() {

	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'gutenberg-core',
		'editor_style'		=>false,
		'dependencies_js'	=>array(),
		'dependencies_css'	=>array(),
		'localize'			=>[
			'lang'=>[
				'title'=> __('Home Banner','theme-client'),
				'default_title'=> __('Title','theme-client'),
				'default_sub_title'=> __('Subtitle','theme-client'),
			]
		]
	]);

	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'hero',
		'editor_style'		=>false,
		'dependencies_js'	=>array('wp-blocks','wp-compose', 'wp-element', 'wp-editor','wp-media-utils'),
		'localize'			=>[
			'lang'=>[
				'title'=> __('Hero','theme-client')
			]
		]
	]);

	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'step',
		'editor_style'		=>false,
		'dependencies_js'	=>array('wp-blocks','wp-compose', 'wp-element', 'wp-editor','wp-media-utils'),
		'localize'			=>[
			'lang'=>[
				'title'=> __('Step','theme-client')
			]
		]
	]);
	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'custom-spacer',
		'editor_style'		=>false,
		'dependencies_js'	=>array('wp-blocks','wp-compose', 'wp-element', 'wp-editor'),
		'localize'			=>[
			'lang'=>[
				'title'=> __('Custom spacer','theme-client'),
				'height_desktop'=> __('Height Desktop','theme-client'),
				'height_mobile'=> __('Height Mobile','theme-client'),
			]
		]
	]);

	
	wp_register_script( 'locationlist-config', get_template_directory_uri() . "/js/gutenberg_blocks/locationlist/locationlist.config.min.js");
	wp_register_script( 'googlemap', 'https://maps.googleapis.com/maps/api/js?key='.GOOGLE_MAP_API);
	$get_locations_assoc = function($locations){
		$result = [];
		foreach($locations as $loc){
			$result[] = [
				'address'=>get_field('address',$loc->ID),
				'phone'=>get_field('contact_phone',$loc->ID),
				'email'=>get_field('contact_email',$loc->ID),

			];
		}
		return $result;
	};
	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'locationlist',
		'editor_style'		=>false,
		'dependencies_js'	=>array('wp-blocks','wp-compose', 'wp-element', 'wp-editor','googlemap','locationlist-config'),
		'render_script'		=>true,
		'localize'			=>[
			'lang'=>[
				'title'=> __('Locations list','theme-client'),
				'open'=>__('Open maps','theme-client'),
			],
			'locations'=> $get_locations_assoc(get_posts(['post_type'=>'locations','posts_per_page'   => -1,'suppress_filters' => false])),
			'url_render_script'=> get_template_directory_uri() . "/js/gutenberg_blocks/locationlist/locationlist-render.min.js"

		]
	]);

	wp_register_script( 'sales-agent-finder-config', get_template_directory_uri() . "/js/gutenberg_blocks/sales-agent-finder/sales-agent-finder.config.min.js");
	$get_agents_assoc = function($agents){
		$result = [];
		foreach($agents as $agent){
			$result[] = [
				'id'=> $agent->ID,
				'title'=> get_the_title($agent->ID),
				'name'=>get_field('name',$agent->ID),
				'cell_phone'=>get_field('cell_phone',$agent->ID),
				'phone'=>get_field('phone',$agent->ID),
				'fax'=>get_field('fax',$agent->ID),
				'email'=>get_field('email',$agent->ID),
				'website'=>get_field('website',$agent->ID),
				'address'=>get_field('address',$agent->ID),
			];
		}
		return $result;
	};
	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'sales-agent-finder',
		'editor_style'		=>false,
		'dependencies_js'	=>array('wp-blocks','wp-compose', 'wp-element', 'wp-editor','googlemap','sales-agent-finder-config'),
		'render_script'		=>true,
		'localize'			=>[
			'lang'=>[
				'title'=> __('Sales agent finder','theme-client'),
				'open'=>__('Open maps','theme-client'),
				'find'=>__('Find agent','theme-client'),
				'Contact'=>__('Contact','theme-client'),
				'Telephone'=>__('Telephone','theme-client'),
				'Cell'=>__('Cell','theme-client'),
				'Fax'=>__('Fax','theme-client'),
				'Email'=>__('Email','theme-client'),
				'Website'=>__('Website','theme-client'),
				'Address'=>__('Address','theme-client'),
				'postal'=>__('Search by postal code','theme-client'),
				'noresult'=>__('There are no sale agent in the searched zone.','theme-client'),
				'reset'=>__('Reset','theme-client')
			],
			'agents'=> $get_agents_assoc(get_posts(['post_type'=>'sales-agents','posts_per_page'   => -1,'suppress_filters' => false])),
			'url_render_script'=> get_template_directory_uri() . "/js/gutenberg_blocks/sales-agent-finder/sales-agent-finder-render.min.js"

		]
	]);

	$get_faqs_assoc = function($faqs){
		$result = [];
		foreach($faqs as $faq){
			$result[] = [
				'question'=>get_the_title($faq->ID),
				'reponse'=>get_field('answer',$faq->ID),
			];
		}
		return $result;
	};
	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'faqlist',
		'dependencies_js'	=>array('wp-blocks','wp-compose', 'wp-element', 'wp-editor'),
		'render_script'		=>true,
		'localize'			=>[
			'lang'=>[
				'title'=> __('Faqs list','theme-client'),
				'more'=> __('Show more faqs','theme-client'),
			],
			'faqs'=> $get_faqs_assoc(get_posts(['post_type'=>'faqs','posts_per_page'   => -1,'suppress_filters' => false])),
			'url_render_script'=> get_template_directory_uri() . "/js/gutenberg_blocks/faqlist/faqlist-render.min.js"

		]
	]);

	$get_enclosure_types = function(){
		
		if(ICL_LANGUAGE_CODE == 'fr'):
			
			$data   = [];
			$data[] = get_term_by('slug','products','product_cat',
						array( 
							'orderby' => 'name',
							'hide_empty' => 0
							)
						)->term_id;
			$data[] = 	get_term_by('slug','accessories','product_cat',
						array( 
							'orderby' => 'name',
							'hide_empty' => 0
							)
						);
			$data[] = 	get_term_by('slug','clearance','product_cat',
					  	array( 
							'orderby' => 'name',
							'hide_empty' => 0
						)
					 	); 

		elseif(ICL_LANGUAGE_CODE == 'en'):
			
			$data   = 	[];
			$data[] = 	get_term_by('slug','products','product_cat',
						array( 
							'orderby' => 'name',
							'hide_empty' => 0
							)
						)->term_id;
			$data[] = 	get_term_by('slug','accessories','product_cat',
						array( 
							'orderby' => 'name',
							'hide_empty' => 0
							)
						);
			$data[] = 	get_term_by('slug','clearance','product_cat',
					  	array( 
							'orderby' => 'name',
							'hide_empty' => 0
						)
					 	); 
		endif;

		$terms_array= [];

		foreach($data as $termId)
		{
			$et = get_term($termId,'product_cat');
			$CategoryName = $et->name;
			$ProdCount = $et->count;

			if($CategoryName  != 'Products' && $CategoryName  != 'Produits' && $ProdCount > 0):
				$et->color 	   = get_field('category_color',$termId);
				$thumbnail_id  = get_term_meta( $et->term_id, 'thumbnail_id', true );
				$et->image 	   = wp_get_attachment_image($thumbnail_id);
				$terms_array[] = $et;
			endif;

			$terms = get_terms( 
					array(
						'taxonomy'   => 'product_cat',
						'hide_empty' => false,
						'parent'     => $termId
						) 
					);

				foreach($terms as $et_ID) :
				$et = get_term($et_ID,'product_cat');
				
				$ProductCount = $et->count;
				if($ProductCount > 0): 
					if($et)
					{
						$et->color 	   = get_field('category_color',$et_ID);
						$thumbnail_id  = get_term_meta( $et->term_id, 'thumbnail_id', true );
						$et->image 	   = wp_get_attachment_image($thumbnail_id);
						$terms_array[] = $et;
					}
				endif; 
			endforeach;
		}
		return $terms_array;
	};

	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'shop-by-enclosure',
		'dependencies_js'	=>array('wp-blocks','wp-compose', 'wp-element', 'wp-editor', 'jquery', 'tns2'),
		'render_script'		=>true,
		'localize'			=>[
			'lang'=>[
				'title'=> __('Shop by enclosure','theme-client')
			],
			'link'=>get_term_link( get_field('finder_target','options'), 'product_cat' ),
			'enclosure_types'=>$get_enclosure_types(),
			'url_render_script'=> get_template_directory_uri() . "/js/gutenberg_blocks/shop-by-enclosure/shop-by-enclosure-render.min.js"

		]
	]);


	$get_docs_assoc = function($cats)  {
		//get first level 
		$unsorted = [];
		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;
		$forbiddens = [];
		//get_forbidden
		function recursive_child($id){
			$array = [$id];
			foreach(get_posts(['post_parent'=>$id,'post_type'=>"documentations",'suppress_filters' => false]) as $child){
				$array = array_merge($array,recursive_child( $child->ID));
			}
			return $array;
		};
		foreach($cats as $doc){
			if(get_field('limited',$doc->ID) ){
				$forbiddens = array_merge($forbiddens,recursive_child( $doc->ID));
			}
		}
		foreach($cats as $cat){
			if((!in_array($cat->ID,array_values($forbiddens))) || (in_array('reseller',$roles) && in_array($cat->ID,array_values($forbiddens)) )) {
				$unsorted[]= [
					'ID'=>$cat->ID,
					'name'=>get_the_title($cat->ID),
					'is_category'=>true,
					'parent_category'=>$cat->post_parent,
					'image'=>get_field('image',$cat->ID),
					'file'=> get_field('file',$cat->ID),
				];
				if(get_field('files',$cat->ID)){
					$index = 0;
					foreach(get_field('files',$cat->ID) as $doc){
						if(!$doc['limited'] || in_array('reseller',$roles)){
							$unsorted[]= [
								'ID'=>$cat->ID."-".$index,
								'name'=>$doc['name'],
								'is_category'=>false,
								'parent_category'=>$cat->ID,
								'image'=>$doc['image'],
								'file'=>$doc['file'],
								'external_link2'=>$doc['external_link2']
							];
						}
					}
				}
			}
		}
		return   $unsorted  ;
	};

	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'knowledgebase',
		'dependencies_js'	=>array('wp-blocks','wp-compose', 'wp-element', 'wp-editor'),
		'render_script'		=>true,
		'localize'			=>[
			'lang'=>[
				'title'=> __('Resources','theme-client'),
				'back'=> __('Back','theme-client'),
				'empty'=> __('This folder is empty.','theme-client'),
				'howhelp'=> __('How can we help you?','theme-client'),
				'ariane_base'=>__('Resources','theme-client'),
				'search'=> __('Search all documentation by keyword','theme-client'),
				'search_empty'=> __('No match found.','theme-client'),
			],
			'docs'=> $get_docs_assoc(get_posts(['post_type'=>'documentations','posts_per_page'=> -1,'suppress_filters' => false])),
			'url_render_script'=> get_template_directory_uri() . "/js/gutenberg_blocks/knowledgebase/knowledgebase-render.min.js"

		]
	]);

	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'compare-list',
		'editor_style'		=>false,
		'dependencies_js'	=>array('wp-blocks','wp-compose', 'wp-element', 'wp-editor'),
		'localize'			=>[
			'lang'=>[
				'title'=> __('List Compare','theme-client')
			]
		]
	]);

	cdm_register_gutenberg_block([
		'suffix'			=>'exm',
		'name'  			=>'custom-text',
		'dependencies_js'	=>array('wp-blocks','wp-compose', 'wp-editor','wp-element'),
		'localize'			=>[
			'lang'=>[
				'title'=> __('Custom text','theme-client'),
				'select_label'=> __('Font-size','theme-client'),
				'select_label_fw'=> __('Font weight','theme-client'),
				'w_lighter'=>__('Light','theme-client'),
				'w_normal'=>__('Regular','theme-client'),
				'w_bold'=>__('Bold','theme-client'),
				'w_bolder'=>__('Bolder','theme-client'),
				'select_label_tag'=> __('Text Hierarchy','theme-client'),
				'p_tag'	=>__('Paragraph','theme-client'),
				'h1_tag'=>__('Heading 1','theme-client'),
				'h2_tag'=>__('Heading 2','theme-client'),
				'h3_tag'=>__('Heading 3','theme-client'),
				'h4_tag'=>__('Heading 4','theme-client'),
				'h5_tag'=>__('Heading 5','theme-client'),
				'h6_tag'=>__('Heading 6','theme-client'),
				'select_label_spacing_top'=> __('Top spacing','theme-client'),
				'select_label_spacing_bottom'=> __('Bottom spacing','theme-client'),
				'select_label_align'=> __('Text alignment','theme-client'),
				'ta_left'=> __('Left','theme-client'),
				'ta_center'=> __('Center','theme-client'),
				'ta_right'=> __('Right','theme-client'),
				'responsive_title' => __('Responsive','theme-client'),
				'responsive_help' => __('If activated, the font size will reduce at phone size (768px).','theme-client'),
				'responsiveCenter_title' => __('Center on mobile','theme-client'),
				'responsiveCenter_help' => __('If activated, the text will center itself on mobile (768px).','theme-client'),
				'select_label_ln'=> __('Line Height','theme-client'),
				'text_area_link'=> __('Link\'s destination','theme-client'),

			]
		]
	]);

}
if(is_admin()){
	add_action( 'enqueue_block_editor_assets', 'theme_client_register_gutenberg_blocks',20 );
}else{
	add_action( 'init', 'theme_client_register_gutenberg_blocks' );
}



