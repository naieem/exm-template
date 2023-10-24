<?php 

/**
 * theme-client functions et definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @hooked after_setup_theme
 * @package theme-client
 */

require get_template_directory() . '/inc/helpers.php';

if (!function_exists('theme_client_setup')) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function theme_client_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on theme-client, use a find and replace
		 * to change 'theme-client' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('theme-client', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'menu-1' => esc_html__('Primary', 'theme-client'),
			'footer-1' => esc_html__('Footer first line', 'theme-client'),
			'footer-2' => esc_html__('Footer second line', 'theme-client'),
			'footer-3' => esc_html__('Footer copyright line', 'theme-client'),
		));


		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		));

		/**
		 * Ajoute l'option de mettre les blocs fullwidth.
		 */
		add_theme_support('align-wide');

		/**
		 * Ajoute des tailles d'images
		 */
		add_image_size('banner_top',1920,400,true );

		Helpers::allow_svg();
	}

endif;
add_action('after_setup_theme', 'theme_client_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 * @hooked after_setup_theme
 */
function theme_client_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters('theme_client_content_width', 1200);
}
add_action('after_setup_theme', 'theme_client_content_width', 0);

/**
 * Set les couleurs du thème dans gutenberg et désactive les options superflu.
 *
 */
function setup_theme_color() {
	add_theme_support( 'editor-gradient-presets', array() );
	add_theme_support( 'disable-custom-gradients' );
	add_theme_support( 'disable-custom-font-sizes' );
	add_theme_support( 'editor-font-sizes', array() );

	// Editor Color Palette
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'Color 1', 'theme-client' ),
			'slug'  => 'color-1',
			'color'	=> '#ffffff',
		),
		array(
			'name'  => __( 'Color 2', 'theme-client' ),
			'slug'  => 'color-2',
			'color'	=> '#000000',
		),
		array(
			'name'  => __( 'Color 3', 'theme-client' ),
			'slug'  => 'color-3',
			'color' => '#E30613',
		),
		array(
			'name'  => __( 'Color 4', 'theme-client' ),
			'slug'  => 'color-4',
			'color' => '#42A1FF',
		),
		array(
			'name'  => __( 'Color 5', 'theme-client' ),
			'slug'  => 'color-5',
			'color' => '#1739E6',
		),
		array(
			'name'	=> __( 'Color 6', 'theme-client' ),
			'slug'	=> 'color-6',
			'color'	=> '#F2F0F0',
		),
		array(
			'name'	=> __( 'Color 7', 'theme-client' ),
			'slug'	=> 'color-7',
			'color'	=> '#999694',
		),
		array(
			'name'	=> __( 'Color 8', 'theme-client' ),
			'slug'	=> 'color-8',
			'color'	=> '#00b377',
		)
	) );
}

add_action( 'after_setup_theme', 'setup_theme_color' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function theme_client_widgets_init() {
	register_sidebar(array(
		'name' => esc_html__('Sidebar', 'theme-client'),
		'id' => 'sidebar-1',
		'description' => esc_html__('Add widgets here.', 'theme-client'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));

}
add_action( 'widgets_init', 'theme_client_widgets_init' );

/**
 * Enqueue les scripts et styles du thème.
 * 
 * @todo merge 'theme-client-navigation' & 'theme-client-skip-link-focus-fix'
 * @hooked wp_enqueue_scripts
 */
function theme_client_scripts() {
	global $version;
	if (SCRIPT_DEBUG) {
	
		$t = time();
        wp_enqueue_style('animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array(), '4.1.1');
		wp_register_script('theme-client-utils',          get_template_directory_uri() . '/js/utils.min.js',           array('jquery'),$t );
		wp_enqueue_style('theme-client-style', get_template_directory_uri() .'/dev/css/style.css', array('select2'), $t);
		// wp_enqueue_style('theme-client-Open-sans', "https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap", array(), $t);
		wp_enqueue_script('theme-client-navigation',          get_template_directory_uri() . '/dev/js/navigation.js',           array('jquery','intersection-observer'), $t, true);
		wp_enqueue_script('theme-client-skip-link-focus-fix', get_template_directory_uri() . '/dev/js/skip-link-focus-fix.js',  array('jquery'), $t, true);
		wp_enqueue_script('theme-client-scripts',          get_template_directory_uri() . '/js/script.min.js',           array('jquery','theme-client-utils','selectWoo','rellax'), $t, true);
		wp_enqueue_script('enclosure-finder',get_template_directory_uri() . '/js/enclosure-finder.min.js', array('jquery','theme-client-utils'), $t, true);
		wp_enqueue_script('rellax',get_template_directory_uri() . '/js/rellax.min.js', array(), $t, true);
		
		/* GA Events */
		wp_enqueue_script('ga-events', get_template_directory_uri().'/js/ga-events.js', array(), '', true);
		
		wp_localize_script( 'theme-client-scripts', 'theme_client_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),'search_post'=>get_filtres_enclosure(),'traductions'=>exm_JS_locales() ) );
		wp_enqueue_script('tns2','https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js',array('jquery'),true);
		wp_enqueue_style('tns2', 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css');
		wp_enqueue_script('intersection-observer', get_template_directory_uri() . '/js/intersection-observer.min.js',  array(), '20151215', true);
		if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
			wpcf7_enqueue_scripts();
		}
		if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
			wpcf7_enqueue_styles();
		}
		if ( is_single() ){
			wp_enqueue_script('cloudimage-360','https://cdn.scaleflex.it/plugins/js-cloudimage-360-view/2/js-cloudimage-360-view.min.js',array());
			wp_enqueue_script('cloudimage-360-init',get_template_directory_uri() . '/js/image360_init.min.js', array(),wp_get_theme()->get('Version') , true);
			wp_enqueue_script('tns2-init',get_template_directory_uri() . '/js/tns2_wc.min.js', array('tns2'),wp_get_theme()->get('Version') , true);
		}
	
	} else {
		
		wp_enqueue_style('animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array(), '4.1.1');
		wp_register_script('theme-client-utils',          get_template_directory_uri() . '/js/utils.min.js',           array('jquery'), wp_get_theme()->get('Version'));
		wp_enqueue_style('theme-client-style', get_template_directory_uri() .'/css/style.min.css', array('select2'), wp_get_theme()->get('Version'));
		// wp_enqueue_style('theme-client-Open-sans', "https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap", array(), wp_get_theme()->get('Version'));
		wp_enqueue_script('theme-client-navigation',          get_template_directory_uri() . '/js/navigation.min.js',           array('jquery','intersection-observer'), '20151215', true);
		wp_enqueue_script('theme-client-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.min.js',  array('jquery'), '20151215', true);
		wp_enqueue_script('theme-client-scripts',          get_template_directory_uri() . '/js/script.min.js',           array('jquery','theme-client-utils','selectWoo','rellax'), '20151215', true);
		wp_enqueue_script('enclosure-finder',get_template_directory_uri() . '/js/enclosure-finder.min.js', array('jquery','theme-client-utils'), '20151215', true);
		wp_enqueue_script('rellax',get_template_directory_uri() . '/js/rellax.min.js', array(), '20151215', true);
		
        /* GA Events */
		wp_enqueue_script('ga-events', get_template_directory_uri().'/js/ga-events.js', array(), '', true);
		
		wp_localize_script( 'theme-client-scripts', 'theme_client_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),'search_post'=>get_filtres_enclosure(),'traductions'=>exm_JS_locales() ) );
		wp_enqueue_script('tns2','https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js',array('jquery'),true);
		wp_enqueue_style('tns2', 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css');
		wp_enqueue_script('intersection-observer', get_template_directory_uri() . '/js/intersection-observer.min.js',  array(), '20151215', true);
		wp_enqueue_script('vimeo','https://player.vimeo.com/api/player.js',array(),true);


		if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
			wpcf7_enqueue_scripts();
		}
		if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
			wpcf7_enqueue_styles();
		}
		if ( is_single() ){
			// wp_enqueue_script('cloudimage-360','https://cdn.scaleflex.it/plugins/js-cloudimage-360-view/2/js-cloudimage-360-view.min.js',array());
			// wp_enqueue_script('cloudimage-360-init',get_template_directory_uri() . '/js/image360_init.min.js', array(),wp_get_theme()->get('Version') , true);
			wp_enqueue_script('tns2-init',get_template_directory_uri() . '/js/tns2_wc.min.js', array('tns2'),wp_get_theme()->get('Version') , true);
	
		}

		
	}
// 	wp_enqueue_script('acx-script-custom', get_stylesheet_directory_uri() . '/js/acx-custom.js', array('jquery'), $version, true);
}
add_action('wp_enqueue_scripts', 'theme_client_scripts', 11);


/**
 * Pour avoir le bloc gutenberg tinyslider aussi dans le page builder
 */
add_action( 'admin_enqueue_scripts', function(){
	wp_enqueue_script('tns2','https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js',array('jquery'),true);
	wp_enqueue_style('tns2', 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css');
    wp_enqueue_style('acx-custom-fa', '/wp-content/themes/exm-manufacturing/css/custom.css');
});

/**
 * Fonction utilisé pendant l'enque du script prinçipal, sert à injecter des traduction dans le front end
 */
function exm_JS_locales(){
	return [
		'email_invalid'=>__('This email address is invalid.','theme-client'),
		'email_processing'=>__('<i class="fas fa-spinner fa-pulse"></i>','theme-client'),
		'select_size'=>__('Filter by size','theme-client')
	];
}

/**
 * Va chercher les filtres dans la barre url (GET), et els injecte dans le JS qui select programatiquement les filter woocommerce au load de la page
 */
function get_filtres_enclosure(){
	$filters = [];
	if(isset($_GET['dimensions_filtre'])){
		foreach($_GET['dimensions_filtre'] as $dimension){
			$filters[$dimension] = '$val';
		}
	}
	foreach($_GET as $key=>$val){
		if(strpos($key,'filtre_') > -1){
			$filters[str_replace('filtre_','',$key)] = $val;
		}
	}
	return $filters;
}

/**
 * Créé les custom post type
 */
add_action('init', function() {
	include_once( 'inc/woo_accountMenu.php' );
	WOO_AccountMenu();
 
    register_post_type( 'locations',
        array(
            'labels' => array(
                'name' => __( 'Locations' ),
                'singular_name' => __( 'location' )
            ),
			'public' => true,
			'exclude_from_search' =>true,
            'has_archive' => false,
			'show_in_rest' => true,
			'menu_icon'=>'dashicons-location'
 
        )
	);
	remove_post_type_support( 'locations', 'editor' );
	remove_post_type_support( 'locations', 'excerpt' );
	remove_post_type_support( 'locations', 'thumbnail' );

	register_post_type( 'sales-agents',
        array(
            'labels' => array(
                'name' => __( 'Sales agents' ),
                'singular_name' => __( 'Sales agent' )
            ),
			'public' => true,
			'exclude_from_search' =>true,
            'has_archive' => false,
			'show_in_rest' => true,
			'menu_icon'=>'dashicons-admin-users'
 
        )
	);
	remove_post_type_support( 'sales-agents', 'editor' );
	remove_post_type_support( 'sales-agents', 'excerpt' );
	remove_post_type_support( 'sales-agents', 'thumbnail' );

	register_post_type( 'faqs',
        array(
            'labels' => array(
                'name' => __( 'Faqs' ),
                'singular_name' => __( 'faqs' )
            ),
			'public' => true,
			'exclude_from_search' =>true,
            'has_archive' => false,
			'show_in_rest' => true,
			'menu_icon'=>'dashicons-format-status'
 
        )
	);
	remove_post_type_support( 'faqs', 'editor' );
	remove_post_type_support( 'faqs', 'excerpt' );
	remove_post_type_support( 'faqs', 'thumbnail' );

	register_post_type( 'documentations',
        array(
            'labels' => array(
                'name' => __( 'Documentations' ),
                'singular_name' => __( 'Documentation' )
            ),
			'public' => true,
			'exclude_from_search' =>true,
            'has_archive' => false,
			'show_in_rest' => true,
			'hierarchical'=>true,
			'menu_icon'=>'dashicons-text-page',
			'supports'=>[
				'title',
				'page-attributes'
			]
 
        )
	);

});





/** 
 * Ajoute le rewrite rule pour la page plan du site.
 * 
 * @hooked init
 */
function theme_client_rewrite_rule(){
	add_rewrite_rule( 'plan-du-site?$', 'index.php?issitemap=1', 'top' );
	add_rewrite_rule( 'sitemap?$', 'index.php?issitemap=1', 'top' );
}
add_action( 'init', 'theme_client_rewrite_rule');

/**
 * Add role 
 */
function exm_add_role(){
	add_role( 'reseller', __('Reseller','theme-client'),[
		'read'=>true
	]);
}
add_action( 'init', 'exm_add_role');


/**
 * Ajoute le support pour le query_var issitemap.
 * 
 * @hooked query_vars
 * @param array $query_vars
 * @return array
 */
function theme_client_add_sitemap_query_var($query_vars){
	$query_vars[] = 'issitemap';

    return $query_vars;
}
add_filter( 'query_vars', 'theme_client_add_sitemap_query_var');

/**
 * Si le query_var issitemap est présent, on load le template sitemap.php.
 * 
 * @hooked template_include
 * @param string $template Le chemin vers le template.
 * @return string
 */
function theme_client_sitemap_template($template){
	if ( get_query_var( 'issitemap' ) == true && get_query_var( 'issitemap' ) == '1' ) {
		return get_template_directory() . '/sitemap.php';
	}
	
	return $template;
}
add_action( 'template_include', 'theme_client_sitemap_template');


/**
 * add_files_type
 * Permet d'allow des mime type au upload
 * @param  mixed $mime_types
 * @return void
 */
function add_files_type($mime_types){
	
	$mime_types['dxf'] = 'text/plain'; //Adding dxf extension

    return $mime_types;
}
add_filter('upload_mimes', 'add_files_type', 1, 1);

//
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
/**
 * Gutenberg blocks
*/

require get_template_directory() . '/inc/gutenberg_blocks_loader.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * ACF blocks.
 */
require get_template_directory() . '/inc/acf-blocks.php';


/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
	require get_template_directory() . '/inc/woocommerce.php';
	
}

/**
 * MenuWalker
 */
require get_template_directory() . '/inc/MenuWalker.php';


/* ACXCOM ADD CLASS TO PRODUCT ITEM */
function add_class_to_category_list_element( $classes, $class, $category ) {
    if( is_object( $category ) )
        $classes[] = $category->slug;

    return $classes;
}
add_filter( 'product_cat_class' , 'add_class_to_category_list_element', 10, 3 );


add_action( 'admin_menu', 'wpse_91693_register' );

function wpse_91693_register()
{
    add_menu_page(
        'Product Options',     // page title
        'Product Options',     // menu title
        'manage_options',   // capability
        'product-options',     // menu slug
        'wpse_91693_render' // callback function
    );
}
function wpse_91693_render()
{
    global $title;

    print '<div class="wrap">';
    print "<h1>$title</h1>";

    $file = get_template_directory() . "/productCustomOption.php";
 
    if ( file_exists( $file ) )
        require $file;

    print '</div>';
}



add_action("wp_ajax_productOptions_ajax_request", "productOptions_ajax_request");
add_action("wp_ajax_nopriv_productOptions_ajax_request", "productOptions_ajax_request");

function productOptions_ajax_request() 
{
	if (isset($_POST['productID'])) 
	{
		$productID     =  $_POST["productID"];
		$product       =  wc_get_product( $productID ); 

		// For English Product
		
		if ($product->is_type( 'variable' ))
    	{
			$variations  =  $product->get_available_variations();
			$prodList = '';	
			foreach($variations as $value)
			{	
				$variationID      		= $value['variation_id'];
				$variationKeyField      = $value['key_included'];
				$variationNumberField   = $value['key_number'];
				$variation_description  = $value['variation_description'];
				$variation        		= wc_get_product($variationID);
				$variationDetails 		= $variation->get_formatted_name(); 
				$prodList 				.= '<p><strong>#'.$variationID.'</strong> '.$variationDetails.'</p>';
			}
    	}
		// For French Product
		
		$frProductId = apply_filters( 'wpml_object_id', $productID, 'product', FALSE, 'fr' );
		$frproduct   =  wc_get_product( $frProductId ); 
		$frtitle 	 = $frproduct->name;
		
		if ($frproduct->is_type( 'variable' ))
    	{
			$frvariations  =  $frproduct->get_available_variations();
			
			$frList = '';	
			foreach($frvariations as $frvalue)
			{
				$frvariationID      	  = $frvalue['variation_id'];
				$frvariation_description  = $value['variation_description'];
				$frvariation        	  = wc_get_product($frvariationID);
				$frvariationDetails 	  = $frvariation->get_formatted_name(); 
				$frList .= '<p><strong>#'.$frvariationID.'</strong> '.$frvariationDetails.'</p>';
			}
    	}

		echo json_encode(
			array(
				'success'   			 => 'true',
				'data_en' 				 => $prodList, 
				'type' 					 => 'Variations', 
				'frtitle' 				 => 'Product Name : '.$frtitle, 
				'frProductID' 			 => $frProductId, 
				'data_fr' 	    		 => $frList,
				'variation_description'  => $variation_description,
				'frvariation_description' => $frvariation_description,
			)
		);
		die;
	}
}


/* ACXCOM ADD SKU ON PRODUCT SINGLE PAGE */
add_action( 'woocommerce_before_single_variation', 'acx_show_sku', 5 );
function acx_show_sku(){
    global $product;
    ?>
	<div class="product_meta custom">
		<?php // do_action('wcml_currency_switcher', array('format' => '%name% (%symbol%)')); ?>
		<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
			<div class="exm-sku"><?php esc_html_e( 'Catalog number:', 'woocommerce' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></div>
		<?php endif; ?>
	</div>
 <?php
}

/* ACXCOM ADD SKU ON CART, CHECKOUT, ORDER, EMAILS */
// First, let's write the function that returns a given product SKU
function acx_return_sku( $product ) {
    $sku = $product->get_sku();
    if ( ! empty( $sku ) ) {
            return '<br/>Catalog number: ' . $sku;
    } else {
        return '';
    }
}
 
// This adds the SKU under cart/checkout item name
add_filter( 'woocommerce_cart_item_name', 'acx_sku_cart_checkout_pages', 9999, 3 );
 
function acx_sku_cart_checkout_pages( $item_name, $cart_item, $cart_item_key  ) {
   $product = $cart_item['data'];
   $item_name .= acx_return_sku( $product );
   return $item_name;
}
 
// This adds SKU under order item table name
add_action( 'woocommerce_order_item_meta_start', 'acx_sku_thankyou_order_email_pages', 9999, 4 );
 
function acx_sku_thankyou_order_email_pages( $item_id, $item, $order, $plain_text ) {
   $product = $item->get_product();
   echo acx_return_sku( $product );
}


/* ACXCOM SIDEBAR FILTER */
add_filter( 'widget_text', 'do_shortcode' );
add_shortcode('acx_sidebar_filter', 'acx_new_sidebar_filter');
function acx_new_sidebar_filter() {
    if( is_tax( 'product_cat' ) ) :
        return
        '<h4 class="widget-title noToggle">' . __('SKU/Keyword', 'theme-client') . '</h4>        
        <div class="search_box pc_custom">
            <div class="form-wrapper">
                <input type="text" name="s" id="search" value="' . $s . '" placeholder="" >
                <button><i class="fal fa-filter"></i></button>
            </div>
        </div>';
    endif; 
}

/* ADD CURRENCY SYMBOL */
add_filter('woocommerce_currency_symbol', 'add_cw_currency_symbol', 10, 2);
function add_cw_currency_symbol( $custom_currency_symbol, $custom_currency ) {
     switch( $custom_currency ) {
         case 'USD': $custom_currency_symbol = 'USD $'; break;
     }
     return $custom_currency_symbol;
}


/* ACXCOM ADD SKU ON PRODUCT SINGLE PAGE */
add_action( 'woocommerce_before_add_to_cart_quantity', 'acx_show_cad', 5 );
function acx_show_cad(){

	global $wpdb;
	$table_name      = $wpdb->prefix . "exchange_rates";
	$exchangeData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name"));

	/* Get exchange rate from API */
    // $curl = curl_init();
    // curl_setopt_array($curl, array(
    //     CURLOPT_URL => 'https://openexchangerates.org/api/latest.json?app_id=15b5aae4cbee4d93942d907f1e6a24e0&base=USD&symbols=CAD',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'GET',
    // ));

    // $response = curl_exec($curl);
    // curl_close($curl);

    // $data = json_decode($response);
    // foreach ($data->rates as $value) {
    //    $CAD_rate = $value;
    // }

	$exchangeRate = $wpdb->get_results($wpdb->prepare("SELECT cad_rate FROM $table_name WHERE id = 1") , ARRAY_A);
    $CAD_rate = $exchangeRate[0]['cad_rate'];

    global $product;
    $usd_price = $product->get_price();
    $USDprice = number_format($usd_price, 2);
    $cad_price = round($usd_price * $CAD_rate , 2);
	?> <input type="hidden" id="productGetPrice" value="<?php echo $USDprice; ?>"></input> 
	<input type="hidden" id="cad_Rate" value="<?php echo $CAD_rate; ?>"></input> <?php
    if($cad_price > 0) { ?>
        <div class="exm-cad"><span style="font-weight:bold;"><?php _e( 'CAD estimate:', 'woocommerce' ); ?> <?php echo $cad_price; ?> $</span><br><?php _e('We only accept payments in US Dollars. The price charged to Canadian credit cards may differ.', 'theme-client'); ?></div>
	<?php }else{ ?>
		<div class="exm-cad"><span style="font-weight:bold;"></span><br><?php _e('We only accept payments in US Dollars. The price charged to Canadian credit cards may differ.', 'theme-client'); ?></div>
	<?php }
}
// Set price if variation is changed
add_action('wp_footer', 'display_canadian_price');
function display_canadian_price(){
	?>
	<script>
	jQuery( document ).ready(function() {
		jQuery('#exm-gp-search').attr('autocomplete', 'off');
		jQuery("#searchResult").remove();
		jQuery("#searchResultLoader").remove();

		setTimeout(() => { getCadPrice();}, 2000);
		setTimeout(() => { getCadPrice();}, 4000);
		jQuery( '.variations_form' ).each( function() {
			jQuery(this).on( 'change', '.variations select', function() {
				setTimeout(() => {
					getCadPrice();
				}, 1500);
			});
		});
		jQuery(this).on( 'click', '.reset_variations', function() {
			setTimeout(() => {
				jQuery('.exm-cad').hide();
				jQuery('.exm-cad').css("display: none;");
			}, 200);	
		});

		jQuery(this).on( 'click', '.exm-global-product-search button', function() {
			jQuery("#searchResult").remove();
			jQuery("#searchResultLoader").remove();	
		});

		jQuery(this).on( 'click', '.autosearch', function() {
			setTimeout(() => {
				jQuery("#searchResult").hide();
				jQuery("#searchResult").remove();
				jQuery('#exm-gp-search').val();
			}, 2000);
		});

		jQuery(this).on( 'click', '#content', function() {
			jQuery("#searchResult").remove();
			jQuery("#searchResultLoader").remove();
		});

	});
	function getCadPrice(){
		cadRate = jQuery('#cad_Rate').val();
		price  = jQuery('.woocommerce-variation-price .amount').text().replace ( /[^\d.]/g, '' ),
		priceInt = parseFloat(price);
		canadian_price = priceInt * cadRate, 2;
		totalcanadianamount = canadian_price.toFixed(2);
		console.log(priceInt);
		console.log(totalcanadianamount);
		var userLang = document.documentElement.lang.toLowerCase();
		var cadEstimate =  "";
		var cad_zero_text = "";
		if (userLang === "fr-ca") {
			cadEstimate =  "Estimé en CAD: ";
			cad_zero_text = "Ce produit n'est pas disponible pour un achat en ligne. Veuillez consulter notre réseau de vente pour trouver un représentant près de chez vous.";
		}
		if (userLang === "en-us") {
		    cadEstimate =  "CAD estimate: ";
			cad_zero_text = "This product is not available for online purchase. Please consult our sales network to find a representative near you.";	
		}
		if(totalcanadianamount != 'NaN'){
			jQuery('.exm-cad  span').replaceWith('<span class="test" style="font-weight:bold;">' + cadEstimate + totalcanadianamount + '</span>');
			jQuery('.exm-cad').show();
			jQuery('.exm-cad').css("display: block;");
			jQuery('.woocommerce-variation-add-to-cart').show();
			jQuery('.woocommerce-variation-add-to-cart').css("display: block;");	
		}else{
			let priceValid = jQuery('#productGetPrice').val();
			let exmHtmlchange = '<div class="price-zero-text" style="border: 1px solid #000; padding: 10px 10px;     line-height: 18px; color: red;"><span class="price"><span class="woocommerce-Price-replace" style="font-size: 18px; font-weight: 500;"><span class="woocommerce-Price-Text">'+cad_zero_text+'</span></span></span></div>'
			jQuery('.woocommerce-variation-price').html(exmHtmlchange);
			jQuery('.woocommerce-variation.single_variation').css("display: block !important;");
			jQuery('.woocommerce-variation.single_variation').show();
			jQuery('.woocommerce-variation-add-to-cart').hide();
			jQuery('.woocommerce-variation-add-to-cart').css("display: none;");
			jQuery('.flex-wrap-gallery .stock').hide();
			jQuery('.flex-wrap-gallery .stock').css("display: none;");
			jQuery('.exm-cad').hide();
			jQuery('.exm-cad').css("display: none;");	
		}
	}

	var timeout;
    var delay = 500;
	jQuery('#exm-gp-search').on('keyup keypress', function(e) {
		if(e.key == 'Enter'){
			clearTimeout(timeout);
			jQuery("#searchResultLoader").remove();
			jQuery("#searchResult").remove();
		}else{
			autoCompleteLoader();

			clearTimeout(timeout);
			timeout = setTimeout(function() {
				autoCompleteFunc();
			}, delay);
		}

	});

	function autoCompleteFunc(){
		let termname = jQuery('#exm-gp-search').val();

		if(termname.length > 2){
			autoCompleteLoader();
			jQuery.ajax({
				type: 'post',
				dataType: 'json',
				url: theme_client_data.ajax_url,
				data: {
					'term': termname,
					action: 'autocompleteSearch',
				},
				success: function(response) {
					console.log(response);
					if(response.message){
						jQuery("#searchResult").html('');
						jQuery("#searchResultLoader").html('');
						jQuery("#searchResultLoader").append('<div class="container"><section><div class="message">'+response.message+'</div></section></div>');
						jQuery("#searchResult").remove();
					}else{
						var len = response.length;
						jQuery("#searchResult").html('');
						jQuery("#searchResultLoader").remove();
						jQuery("#searchResult").remove();
						jQuery('<ul id="searchResult"></ul').insertAfter("#searchform");
						var browserLang = document.documentElement.lang.toLowerCase();
						var base_url = '<?php echo get_site_url(); ?>';
						let frUrl = ''
						if (browserLang === "fr-ca") {
							var url = base_url+'/fr/produit/';
						}else{
							var url = base_url+'/produit/';
						}
						for( var i = 0; i<len; i++){
							var name = response[i]['name'];
							var sku = response[i]['sku'];
							var slug = response[i]['slug'];
							jQuery("#searchResult").append("<li class='autosearch' data-value='"+name+"'><a href='"+url+""+slug+"'>"+sku+" "+name+"</a></li>");
						}

						jQuery(function(){
							function equalHeight() {
								var menuhight = jQuery("#searchResult").map( function(){
									return jQuery(this).height(); 
								}).get();
								if(menuhight > 500){
									jQuery("#searchResult").height('500px');
								}else{
									var maxHeight = Math.max.apply( Math, menuhight);
									jQuery( "#searchResult" ).css( "min-height", menuhight+"px" );
								}
							} 
							equalHeight();
						});
					}
				}
			});
		}else{
			jQuery("#searchResult").remove();
			jQuery("#searchResultLoader").remove();
		}
	}

	if(document.getElementById("searchResult") !== null)
	{   if(document.getElementById("searchResultLoader") !== null)
		{
			jQuery("#searchResultLoader").remove();
		}
	}
	function autoCompleteLoader(){
		if(document.getElementById("searchResult") == null)
		{   if(document.getElementById("searchResultLoader") == null)
			{
				jQuery("#searchResultLoader").remove();
				jQuery('<ul id="searchResultLoader"><div class="container"><section><div class="loader loader-1"><div class="loader-outter"></div><div class="loader-inner"></div></div></section></div></ul>').insertAfter("#searchform");
			}
		}else{
			jQuery("#searchResult").remove();
			if(document.getElementById("searchResultLoader") == null)
			{
				jQuery("#searchResultLoader").remove();
				jQuery('<ul id="searchResultLoader"><div class="container"><section><div class="loader loader-1"><div class="loader-outter"></div><div class="loader-inner"></div></div></section></div></ul>').insertAfter("#searchform");
			}
		}
	}
	</script>
<?php
}

add_action('wp_ajax_nopriv_autocompleteSearch', 'acx_autocomplete_search');
add_action('wp_ajax_autocompleteSearch', 'acx_autocomplete_search');
function acx_autocomplete_search() {

	$keyword = $_POST['term'];
	$query = new WC_Product_Query( array(
		'status'                                => 'publish',
		'limit'                                 => -1,
		's'                                     => $keyword,
	) );
	$products = $query->get_products();

	if(empty($products)){
		$query_vars = $keyword;
		if($query_vars->query['s'] && !empty($query_vars->query['s'])){
			$query1 = new WC_Product_Query( array(
				'posts_per_page'  => -1,
				'post_type'       => 'product',
				'meta_query' => array(
					array(
						'key' => '_sku',
						'value' => $query_vars->query['s'],
						'compare' => 'LIKE'
					)) 
			    ) 
		    );
			$products = $query1->get_products();
		}
	}

	if(empty($products)){
		$prod_id = wc_get_product_id_by_sku($keyword);
        $products = wc_get_product($prod_id);
        if($products){
			$parentProd_id = $products->parent_id;
			$parent_product = wc_get_product($parentProd_id);
            $return_arr[] = array("name"=> $products->get_title(), "sku" => $products->get_sku(), "slug" => $parent_product->slug);
            echo json_encode($return_arr);
            die();
        }
	}

	if(empty($products)){
		$newKeyword = explode (' ', $keyword, 3);
		$newKeyword = $newKeyword[2];
		if($newKeyword){
			$query = new WC_Product_Query( array(
				'status'                                => 'publish',
				'limit'                                 => -1,
				's'                                     => $newKeyword,
			) );
			$products = $query->get_products();
		}	
	}

	if($products){
		foreach($products as $product){ 
			$return_arr[] = array("name"=> $product->get_title(), "sku" => $product->get_sku(), "slug" => $product->slug);
		}
	}else{
		$return_arr = array("message"=> 'No product Found');
	}
	echo json_encode($return_arr);
	die();
}


/* ADD READ MORE LINK TO BLOG POSTS */
function excerpt_readmore($more) {
    return '... <a href="'. get_permalink($post->ID) . '" class="readmore">' . 'Read More' . '</a>';
}
add_filter('excerpt_more', 'excerpt_readmore');


/* FORCE CATEGORIES WITH FLIPBBOK REDIRECT TO OPEN IN NEW TAB */
function add_custom_script_to_header() {
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    jQuery(document).ready(function($) {
        var specificLinks = [
            'https://exmweb.com/product-category/products/modular/',
            'https://exmweb.com/fr/categorie-produit/produits/modulaire/',
            'https://exmweb.com/product-category/products/wire-way/',
            'https://exmweb.com/fr/categorie-produit/produits/caniveaux/'
        ];

        $.each(specificLinks, function(index, specificLink) {
            $('a[href="' + specificLink + '"]').attr('target', '_blank');
        });
    });
    </script>
    <?php
}
add_action('wp_head', 'add_custom_script_to_header');