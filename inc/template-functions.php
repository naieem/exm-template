<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package theme-client
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @hooked body_class
 * @return array
 */
function theme_client_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}
    
    if ( apply_filters('wpml_current_language',false) ){
        $classes[] = 'lang_'.apply_filters('wpml_current_language',false);
    }

	return $classes;
}
add_filter( 'body_class', 'theme_client_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 * 
 * @hooked wp_head
 */
function theme_client_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'theme_client_pingback_header' );


include_once( 'wishlist.php' );


/**
 * Disable the emoji's
 * This aint a kid’s website
 * 
 * @hooked init
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	
	// Remove from TinyMCE
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter out the tinymce emoji plugin.
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}


/**
 * Ajoute des options supplémentaires au menu principal,  controlées par le champs add icon button dans les options
 */
function exm_add_icon_btn(string $items, object $args ){
		global $woocommerce;

		if('menu-principal' == $args->menu->slug ||'menu-principal-fr' == $args->menu->slug ){
			//$items.= '<li><a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'"><img src="'.get_template_directory_uri().'/images/svg/account.svg" /><span>'.__('Account','theme-client').'</span></a></li>';
			//$items.= '<li><a href="#" class="search-trigger" ><img src="'.get_template_directory_uri().'/images/svg/search.svg" /><span>'.__('Enclosure finder','theme-client').'</span></a></li>';
			ob_start();
			theme_client_woocommerce_header_cart(['class'=>'hide-mobile']);
			$items.= '<li class="exm-menu-cart">'.ob_get_clean().'</li>';
		}

    return $items;
}add_filter( 'wp_nav_menu_items', 'exm_add_icon_btn', 10, 2 );



/**
 * Ajoute un fichier pour modifier gutenberg
 */
function gutenberg_theme_client_enqueue_block_editor_assets() {
    // Enqueue our script
    wp_enqueue_script('gutenberg_addons',esc_url( get_template_directory_uri().'/js/gutenberg_modifications.min.js' ),array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),'1.0.0',false);
	wp_enqueue_script('wp_rellax',esc_url( get_template_directory_uri().'/js/wp_rellax.min.js' ),array( 'wp-blocks', 'wp-element', 'wp-editor' ),'1.0.0',false);
	wp_enqueue_script('wp_reverse_columns',esc_url( get_template_directory_uri().'/js/wp_reverse_columns.min.js' ),array( 'wp-blocks', 'wp-element', 'wp-editor' ),'1.0.0',false);
	wp_enqueue_script('wp_animate_css',esc_url( get_template_directory_uri().'/js/wp_animate_css.min.js' ),array( 'wp-blocks', 'wp-element', 'wp-editor' ),'1.0.0',false);
	wp_enqueue_style(
        'gutenberg_addons-style',
        esc_url( get_template_directory_uri().'/css/editor.min.css' )
	);
	wp_enqueue_script(
        'font_awesome',
        'https://kit.fontawesome.com/70ba5bc987.js'
	);

	


	// wp_enqueue_style('theme-client-Open-sans', "https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap", array(), wp_get_theme()->get('Version'));
	
}add_action( 'enqueue_block_editor_assets', 'gutenberg_theme_client_enqueue_block_editor_assets',10 );



if(!function_exists('language_selector')){
	/**
	 * Affiche un sélecteur de langue utilisant WPML.
	 *
	 * @var String $display Type d'affichage des langues. (ex: full name, code, flag, etc.)
	 */
	function language_selector($args = []){
		if(function_exists('icl_get_languages')){
			$languages = icl_get_languages('skip_missing=0&orderby=code');
			$active= null;
			$inactive = [];
			if(!empty($languages)){
				if(count($languages) > 2){
					foreach($languages as $l){
						if($l['active']) {
							$active = $l;
							break;
						}
					}
					echo '<li class="menu-item-has-children"><a href="#">'.$active['native_name'].'</a>';
					echo '<div class="sub-menu-wrap">';
					echo '<ul class="sub-menu">';
						foreach($languages as $l){
							echo '<li><a href="'.$l['url'].'">'.$l['native_name'].'</a></li>';
						}
					echo '</ul>';
					echo '</div> ';
					echo '</li>';
				}else{
					foreach($languages as $l){
						if(!$l['active']) {
							$active = $l;
							break;
						}
					}
					echo '<li class="menu-item"><a href="'.$active['url'].'">'.$active['native_name'].'</a></li>';

				}
			}
		}
	}
}

/*
if(!function_exists('currency_selector')){
	/**
	 * Affiche un sélecteur de langue utilisant WPML.
	 *
	 * @var String $display Type d'affichage des langues. (ex: full name, code, flag, etc.)
	 */
/*
    function currency_selector(){
		if(function_exists('icl_get_languages')){
			echo '<form method="post" id="wcpbc-widget-country-switcher-form" class="wcpbc-widget-country-switcher" style="display: none;">';
				echo '<input type="hidden" name="wcpbc-manual-country" value="" />';
			echo '</form>';
			echo '<li class="menu-item-has-children"><a href="#">'.wcpbc_get_zone_by_country(wcpbc_get_woocommerce_country())->get_currency().'</a>';
				echo '<div class="sub-menu-wrap">' ;
					echo '<ul class="sub-menu ">';
						foreach(WCPBC_Pricing_Zones::get_zones() as $pricing_zone) :
						echo '<li><a class="currecy-selector" data-currency="'.$pricing_zone->get_countries()[0].'" href="'.$pricing_zone->get_countries()[0].'">'.$pricing_zone->get_currency().'</a></li>';
						endforeach;
					echo '</ul>';
				echo '</div> ';
			echo '</li>';
		
		}
	}
}
*/

/**
 * Term list helper for pages 
 * 
 */

 function exm_get_product_cat_terms_list(){
	$terms_list = [];
	$terms_list[] = get_queried_object();
	
	$get_parent = function($termID){
		return get_term_by( 'id', $termID, 'product_cat' );
	};

	while($terms_list[0]->parent != null){
		array_unshift($terms_list,$get_parent($terms_list[0]->parent));
	}
	return $terms_list;
 }


/**
 * Affiche un fil d'ariane.
 * 
 * @param String $separator
 * 
 */
function displayBreadcrumb($separator = '/'){
	global $post; 
	
	if (!is_front_page()) {
		echo '<div class="breadcrumb">';
		//echo '<a title="'.__('Home', 'theme-client').'" rel="nofollow" href="'.esc_url( home_url( '/' ) ).'">'.__('Home', 'theme-client').'</a> '.$separator.' ';

		

		if (is_page()) {
			$ancestors = get_post_ancestors($post);
		
			if ($ancestors) {
				$ancestors = array_reverse($ancestors);
				
				foreach ($ancestors as $crumb) {
					echo '<a href="'.get_permalink($crumb).'">'.get_the_title($crumb).'</a> '.$separator.' ';
				}// foreach
			}//if
		}//if

		do_action('cdm_breadcrumb_add_options', $separator); // add custom options

		if(is_single()){
			$parent_page_id = wp_get_post_parent_id($post);

			if('post' == get_post_type()){
				$parent_page_id = get_option('page_for_posts ');
			}

			if('product' == get_post_type()){
				$tax = null;
				foreach(get_the_terms(get_the_ID(),'product_cat') as $taxonomie){
					if($taxonomie->parent == 0){
						$tax = $taxonomie;
					}
				}
				if($tax){
					echo '<a href="'.get_term_link( $tax->term_id, 'product_cat' ).'">'.$tax->name.'</a> '.$separator.' ';
				}
			}

			if($parent_page_id){
				echo '<a href="'.get_permalink($parent_page_id).'">'.get_the_title($parent_page_id).'</a> '.$separator.' ';

			}

			do_action('breadcrumb_single_parents', $separator); // hook in child to add parent to single post

			echo '<span>'.get_the_title().'</span>';

		}else if(is_category()){
			$category = get_queried_object();
			
			$parent_page_id = wp_get_post_parent_id('post');

			if($parent_page_id){
				echo '<a href="'.get_permalink($parent_page_id).'">'.get_the_title($parent_page_id).'</a> '.$separator.' ';
			}

			$ancestors = get_ancestors($category->term_id, $category->taxonomy, 'taxonomy');
		
			if ($ancestors) {
				$ancestors = array_reverse($ancestors);
				
				foreach ($ancestors as $ancestor) {
					$ancestor_obj = get_category($ancestor);
					
					echo '<a href="'.get_category_link($ancestor_obj->term_id).'">'.$ancestor_obj->name.'</a> '.$separator.' ';
				}
			}

			echo '<span>'.$category->cat_name.'</span>';

		}else if(is_tax()){
			$terms_list = exm_get_product_cat_terms_list();

			foreach($terms_list as $index=>$term){
				if($index < count($terms_list)-1 ){
					echo '<a title="'.$term->name.'" href="'. get_category_link($term->term_id).'">'.$term->name.'</a> '.$separator;
				}else{
					echo '<span>'.$term->name.'</span>';
				}
			}
			

		}else if(is_archive() && get_post_type() == 'product'){		
			echo '<span>'.get_the_title(get_option('woocommerce_shop_page_id')).'</span>';

		}else if(!is_archive()){
			if ( get_query_var( 'issitemap' ) == true && get_query_var( 'issitemap' ) == '1' ) {
				echo '<span>'._e('Site map','theme-client').'</span>';

			}else if('post' === get_post_type()){
				echo '<span>'._e('News','theme-client').'</span>';

			}else{
				//echo '<span>'.get_the_title().'</span>';

			}
		}

		echo '</div>';
	} // is front page
}


if(!function_exists('syncMailchimp')){
	/**
	 * Permet l'inscription de membre à une liste de Mailchimp
	 * en utilisant l'API de Mailchimp.
	 * 
	 * @return Array
	 */
	function syncMailchimp($email, $lang = 'fr'){
		// Validation
		if(!$email){ 
			return array(
				'success' => false,
				'message' => __('No email address provided.', 'theme-client')
			);
		}

		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $email)) {
			return array(
				'success' => false,
				'message' => __('Email address is not valid.', 'theme-client')
			);
		}
		
		if(!defined('MAILCHIMP_API_KEY') || !defined('MAILCHIMP_LIST_ID_EN') || !defined('MAILCHIMP_LIST_ID_FR') ){
			return array(
				'success' => true,
				'message' => __('In test mode, no email address is added.', 'theme-client')
			);
		}

		$listID = MAILCHIMP_LIST_ID_EN;
		if( defined('ICL_LANGUAGE_CODE') ){ // Si WPML est installé
			if(ICL_LANGUAGE_CODE == 'fr'){ // WPML n'a pas de français canadien, donc si fr on laisse fr_CA.
				$listID = MAILCHIMP_LIST_ID_FR;
			}
		}

		
		
		$dataCenter = substr(MAILCHIMP_API_KEY, strpos(MAILCHIMP_API_KEY,'-')+1);
		$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members';

		$lang = 'fr_CA'; // Par défaut
		if( defined('ICL_LANGUAGE_CODE') ){ // Si WPML est installé
			if(ICL_LANGUAGE_CODE != 'fr'){ // WPML n'a pas de français canadien, donc si fr on laisse fr_CA.
				$lang = ICL_LANGUAGE_CODE;
			}
		}

		$json = json_encode([
			'email_address' => $email,
			'status'        => 'subscribed', // "subscribed","unsubscribed","cleaned","pending"
			'language'		=> $lang
		]);

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_USERPWD, 'user:' . MAILCHIMP_API_KEY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 

		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$res = json_decode($result);
	
		
		if(isset($res->title)){
			if(strpos($res->title, 'Exists') !== 0){
				return array(
					'success' => true,
					'message' => __('You are already registered, thank you.', 'theme-client')
				);

			}else{
				return array(
					'success' => false,
					'message' => __('An error has occurred. Please try again.', 'theme-client')
				);
			}
		}else{
			return array(
				'success' => true,
				'message' => __('Your email address has been added. Thank you!', 'theme-client')
			);
		}
		
	}
}
/**
 * Gère l'inscription à Mailchimp par Ajax.
 * 
 */
function newsletter_subscription(){
	if(isset($_POST['ajax']) && isset($_POST['email'])){
		$lang = 'fr';

		if(isset($_POST['lang'])){
			$lang = $_POST['lang'];

		}else if(defined('ICL_LANGUAGE_CODE')){
			$lang = ICL_LANGUAGE_CODE;
		}
		
		echo json_encode(syncMailchimp($_POST['email'], $lang));
	}

	wp_die();
}
add_action( 'wp_ajax_newsletter_subscription', 'newsletter_subscription' );
add_action( 'wp_ajax_nopriv_newsletter_subscription', 'newsletter_subscription' );


/**
 * Affiche un sitemap du site (seulement les pages et catégorie Woocommerce) de façon récursive.
 * 
 * @param array $args Les arguments pour la WP_Query.
 */
function print_site_map($args = []){
	// The Query
	$default_args = [
		'post_type' 		=> 'page',
		'posts_per_page' 	=> -1,
		'post_parent' 		=> 0,
		'order'				=> 'ASC',
		'orderby'			=> 'menu_order'
	];

	$args = wp_parse_args($args, $default_args);

	$the_query = new WP_Query( $args );
	
	// The Loop
	if($the_query->have_posts()){
		echo '<ul class="sitemap">';
		while($the_query->have_posts()){
			$the_query->the_post();
			echo '<li><a href="'. get_permalink() .'">'. get_the_title() .'</a></li>';

			print_site_map(wp_parse_args(['post_parent' => get_the_ID()], $args));

			if(function_exists('wc_get_page_id') && wc_get_page_id('shop') == get_the_ID()){
				print_site_map_tax([
					'taxonomy' => 'product_cat'
				]);
			}
		}
		echo '</ul>';

		/* Restore original Post Data */
		wp_reset_postdata();
	}
}

/**
 * Parcours les terms d'une taxonomy de façon récursive pour les afficher de façon structuré pour le sitemap.
 * 
 * @param array $args Les arguments pour la WP_Term_Query.
 */
function print_site_map_tax($args = []){
	// The Query
	$default_args = [
		'taxonomy' 			=> 'category',
		'number' 			=> 0,
		'parent' 			=> 0,
		'order'				=> 'ASC',
		'orderby'			=> 'name'
	];

	$args = wp_parse_args($args, $default_args);
	
	$the_query = new WP_Term_Query( $args );
	$the_terms = $the_query->get_terms();

	// The Loop
	if(count($the_terms)){
		echo '<ul class="sitemap">';

		foreach($the_terms as $term){
			echo '<li><a href="'. get_term_link($term->term_id) .'">'. $term->name .'</a></li>';

			print_site_map_tax(wp_parse_args(['parent' => $term->term_id], $args));
		}

		echo '</ul>';
	}
}