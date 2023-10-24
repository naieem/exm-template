<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package theme-client
 */

/**
 * WooCommerce setup function.
 *
 * @hooked after_setup_theme
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function theme_client_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'theme_client_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @hooked wp_enqueue_scripts
 * @return void
 */
function theme_client_woocommerce_scripts() {
	if (SCRIPT_DEBUG) {
		$t = time();
		wp_enqueue_style( 'theme-client-woocommerce-style', get_template_directory_uri() . '/dev/css/woocommerce.css', array(), $t );

	} else {
		wp_enqueue_style( 'theme-client-woocommerce-style', get_template_directory_uri() . '/css/woocommerce.min.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_client_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @hooked woocommerce_enqueue_styles
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @hooked body_class
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function theme_client_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'theme_client_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @hooked loop_shop_per_page
 * @return integer number of products.
 */
function theme_client_woocommerce_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'theme_client_woocommerce_products_per_page' );

/**
 * Product gallery thumnbail columns.
 *
 * @hooked woocommerce_product_thumbnails_columns
 * @return integer number of columns.
 */
function theme_client_woocommerce_thumbnail_columns() {
	return 3;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'theme_client_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @hooked loop_shop_columns
 * @return integer products per row.
 */
function theme_client_woocommerce_loop_columns() {
	return 3;
}
add_filter( 'loop_shop_columns', 'theme_client_woocommerce_loop_columns' );

add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
	$defaults = array(
		'posts_per_page' => 12,
		'columns'        => 1,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
} );
add_filter( 'woocommerce_product_related_posts_shuffle', function($val){return false;},1,10 );

add_filter('woocommerce_related_products',function($related_posts,$product_id,$args){
	$product = wc_get_product( $product_id );
	return array_merge($product->get_upsell_ids(),$related_posts);
},3,20);



if ( function_exists( 'theme_client_woocommerce_header_cart' ) ) {
	theme_client_woocommerce_header_cart();
}

if ( ! function_exists( 'theme_client_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function theme_client_woocommerce_header_cart($options = []) {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		
		<ul id="site-header-cart" class="site-header-cart <?php echo ((isset($options['class']))?' '.$options['class']:'') ?>">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php theme_client_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
                    'sku' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}

if ( ! function_exists( 'theme_client_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @hooked woocommerce_add_to_cart_fragments
	 * @return array Fragments to refresh via AJAX.
	 */
	function theme_client_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		theme_client_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();
 
		return $fragments;
	}
}

add_filter( 'woocommerce_add_to_cart_fragments', 'theme_client_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'theme_client_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function theme_client_woocommerce_cart_link() {
		global $woocommerce;
		?>
		<a class="cart-contents <?php echo (($woocommerce->cart->cart_contents_count > 0)?'filled':'') ?>" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'theme-client' ); ?>">
			<img  src="<?php echo get_template_directory_uri() ?>/images/svg/cart.svg" alt="">
		</a>
		<?php
	}
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );


if ( ! function_exists( 'theme_client_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @hooked woocommerce_before_shop_loop
	 * @return  void
	 */
	function theme_client_woocommerce_product_columns_wrapper() {
		$columns = theme_client_woocommerce_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . ' shop-products-wrapper x">';
	}
}

add_action( 'woocommerce_before_shop_loop', 'theme_client_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'theme_client_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @hooked woocommerce_after_shop_loop
	 * @return  void
	 */
	function theme_client_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}

add_action( 'woocommerce_after_shop_loop', 'theme_client_woocommerce_product_columns_wrapper_close', 40 );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Remove product count
 */
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_after_shop_loop' , 'woocommerce_result_count', 20 );

/**
 * Remove woocommerce title
 */

remove_action( 'woocommerce_template_loop_product_title', 'woocommerce_shop_loop_item_title', 10 );
add_filter('woocommerce_show_page_title', '__return_false');

/**
 * Remove sorting from top
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
//add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );


add_action( 'wp', 'exm_remove_sidebar_product_pages' );
 
// remove sidebar from single product
function exm_remove_sidebar_product_pages() {
	if ( is_product() ) {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	}
}

if ( ! function_exists( 'theme_client_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @hooked woocommerce_before_main_content
	 * @return void
	 */
	function theme_client_woocommerce_wrapper_before() {
		?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
			<?php
	}
}

add_action( 'woocommerce_before_main_content', 'theme_client_woocommerce_wrapper_before' );

if ( ! function_exists( 'theme_client_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @hooked woocommerce_after_main_content
	 * @return void
	 */
	function theme_client_woocommerce_wrapper_after() {
			?>
			</main><!-- #main -->
		</div><!-- #primary -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'theme_client_woocommerce_wrapper_after' );


/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 */

//  if ( function_exists( 'theme_client_woocommerce_header_cart' ) ) {
// 	 theme_client_woocommerce_header_cart();
//  }

if ( ! function_exists( 'theme_client_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @hooked woocommerce_add_to_cart_fragments
	 * @return array Fragments to refresh via AJAX.
	 */
	function theme_client_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		theme_client_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();
 
		return $fragments;
	}
}


add_filter( 'woocommerce_add_to_cart_fragments', 'theme_client_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'theme_client_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function theme_client_woocommerce_cart_link() {
		global $woocommerce;
		?>
		<a class="cart-contents <?php echo (($woocommerce->cart->cart_contents_count > 0)?'filled':'') ?>" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'theme-client' ); ?>">
			<img  src="<?php echo get_template_directory_uri() ?>/images/svg/cart.svg" alt="">
		</a>
		<?php
	}
}



if ( ! function_exists( 'theme_client_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function theme_client_woocommerce_header_cart($options = []) {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		
		<ul id="site-header-cart" class="site-header-cart <?php echo ((isset($options['class']))?' '.$options['class']:'') ?>">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php theme_client_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
                    'sku' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}


/**
 * remove wordpress breadcrumb
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

/**
 * Add Banner to pages
 */
function exm_add_wc_banner(){
	get_template_part( 'template-parts/inc', 'banner' );
}
add_action('woocommerce_before_main_content','exm_add_wc_banner', 8);


/**
 * Change le type des titres des widget pour des h3.
 * 
 * @param array $args
 * @hooked dynamic_sidebar_params
 * @return array
 */
function theme_client_change_widget_title_type($args){
	$args[0]['before_title'] = '<h4 class="widget-title">';
	$args[0]['after_title'] = '</h4>';
	
	return $args;
}
add_filter('dynamic_sidebar_params', 'theme_client_change_widget_title_type');



/**
 * Ajoute un .content pour le sidebar et le contenu principal.
 */
add_action('woocommerce_before_main_content', function(){
	echo '<div id="content-wrapper" class="content">';
}, 9);

add_action('woocommerce_sidebar', function(){
	echo '</div>';
}, 11);

/**
 * Retire le nombre d'item à côté des options de grandeur.
 * 
 * @param string $html Le HTML pour afficher le nombre.
 * @hooked woocommerce_layered_nav_count
 * @return string
 */
function theme_client_remove_layered_nav_count($html){
	return '';
}
add_filter('woocommerce_layered_nav_count', 'theme_client_remove_layered_nav_count');

/**
 * 
 */
function theme_client_ajax_woo_cat_filter(){
	$paged               = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
	$ordering            = WC()->query->get_catalog_ordering_args();
	$products_per_page   = apply_filters('loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page());
	$paged = $_POST['attributes'][0]['pagenumber'];

	$args = [
		'meta_key' 	=> $ordering['meta_key'],
		'status'   	=> 'publish',
		'limit'    	=> $products_per_page,
		'page'     	=> $paged,
		'paginate' 	=> true,
		'return'   	=> 'ids',
		'orderby'  	=> $ordering['orderby'],
		'order'    	=> $ordering['order']
	];

	// echo "<pre>";
	// print_r($args);

	if(isset($_POST['catIDs']) && $_POST['catIDs'] != ''){
		$args['tax_query']['relation'] = 'AND';

		$args['tax_query'][] = array(
			'taxonomy'  => 'product_cat',
			'field' 	=> 'term_id',
			'terms'     => explode(',', trim($_POST['catIDs'], ',')),
		);
	}

	if(isset($_POST['name']) && !empty($_POST['name'])){
		$args['s'] = $_POST['name'];

	}

	if(isset($_POST['attributes']) && !empty($_POST['attributes'])){

		$args['tax_query']['relation'] = 'AND';

		foreach($_POST['attributes'] as $attribute){
			$filters = array();

			foreach(explode(',', trim($attribute['termSlugs'], ',')) as $termSlug){
				$filters[] = array(
					'taxonomy'  => (($attribute['name'] == 'product_cat')?'':'pa_').$attribute['name'],
					'field' 	=> 'slug',
					'terms'     => $termSlug,
				);
			}

			
			$args['tax_query'][] = array_merge(array(
				'relation' => $attribute['queryType'],
			), $filters);
		}
	}
	
	$products = wc_get_products($args);
	
	// echo "<pre>";
	// print_r($products);

	wc_set_loop_prop('current_page', $paged);
	wc_set_loop_prop('is_paginated', wc_string_to_bool(true));
	wc_set_loop_prop('page_template', get_page_template_slug());
	wc_set_loop_prop('per_page', $products_per_page);
	wc_set_loop_prop('total', $products->total);
	wc_set_loop_prop('total_pages', $products->max_num_pages);
	
	$data = [
		'count' => count($products->products),
		'total'	=> intval($products->total),
		'html'	=> '',
		'paged'	=> $paged,
		'post'	=> $_POST
	];
	
	if (  count($products->products) > 0  ) {
		ob_start();

		foreach($products->products as $product) {
			$post_object = get_post($product);
       		setup_postdata($GLOBALS['post'] =& $post_object);

			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}

		wp_reset_postdata();

	}else{
		do_action( 'woocommerce_no_products_found' );
	}
	
	$args = array(
		'total'   => $products->max_num_pages,
		'current' => $paged,
		'base'    => esc_url_raw( add_query_arg( 'product-page', '%#%', false ) ),
		'format'  => '?product-page=%#%',
	);
 
	if ( ! wc_get_loop_prop( 'is_shortcode' ) ) {
		$args['format'] = '';
		$args['base']   = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
	}
 
	wc_get_template( 'loop/pagination-filter.php', $args );

	$data['html'] = ob_get_clean();

	echo json_encode($data);
	exit;
}
add_action( 'wp_ajax_theme_client_ajax_woo_cat_filter', 'theme_client_ajax_woo_cat_filter' );
add_action( 'wp_ajax_nopriv_theme_client_ajax_woo_cat_filter', 'theme_client_ajax_woo_cat_filter' );

/**
 * Change la pagination de woocommerce pour y mettre un bouton load more à la place.
 */
function woocommerce_pagination() {

	if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
        return;
    }
 
    $args = array(
        'total'   => wc_get_loop_prop( 'total_pages' ),
        'current' => wc_get_loop_prop( 'current_page' ),
        'base'    => esc_url_raw( add_query_arg( 'product-page', '%#%', false ) ),
        'format'  => '?product-page=%#%',
    );
 
    if ( ! wc_get_loop_prop( 'is_shortcode' ) ) {
        $args['format'] = '';
        $args['base']   = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
    }
 
    wc_get_template( 'loop/pagination.php', $args );

	// if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
	// 	return;
	// }
	// if( wc_get_loop_prop( 'total_pages' ) > 1){
	// 	echo '<div class="show-more-products"><button class="loadmore">'.__('Load more', 'theme-client').'</button><div class="loading-anim"><div></div><div></div><div></div><div></div></div></div>';
	// }
}



/**
 * Remove add to cart button
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );


/**
 * Ajoute un figure autour des images de WooCommerce.
 * 
 * @param string $image Le HTML de l'image.
 * @hooked woocommerce_product_get_image
 * @return string
 */
function theme_client_add_img_figure($image){
	return '<figure>'.$image.'</figure>';
}
add_filter('woocommerce_product_get_image', 'theme_client_add_img_figure');


/**
 * Modify shop item grid title
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title',function(){
	global $product;
	$price="";
	$stock="";
	if($product->is_type( 'variable' )){
	    
		$price = __('Starting at ','theme-client').$product->get_variation_price('min').'$';
	}else{
		$price = __('Starting at ','theme-client').$product->get_price().'$';
	}
	if($product->get_stock_quantity() == '0'){
		$stock = "<span class='stock'>".__('out of stock','theme-client')."</span>";
	} else {
	    $stock = "<span class='stock in-stock' style='color:green'>".__('in stock','theme-client')."</span>";
	}
	//echo '<span class="price-stock"><span>'.$price.'</span>'.$stock.'</span>';
    
	if(get_field('is_new_product')) : ?>
		<span class="label-new"><?php _e('New','theme-client') ?></span>
	<?php endif; 

} ,10 );

add_action( 'woocommerce_before_shop_loop_item_title', function(){
	global $product;
	$order = [];
	foreach(wp_get_post_terms( $product->get_id(), 'product_cat' ) as $cat){
		$order[$cat->parent] = $cat;
	}
	ksort($order);
	
	$categories = array_reverse($order);
	$cat_arr = array_map(function($item){return $item->slug;},$categories);
	if(end($categories)->slug == 'products' || end($categories)->slug == 'produits'){
		if(count($categories) > 0){
			$attr =  $categories[0]->name;
			$series = get_field('series',$product->get_id())? get_field('series',$product->get_id()):'';
			//echo "<span title='".$attr.(($attr != '' && $series !='')?' | ':'').$series."' class='enclosure-top-title' >".$attr.(($attr != '' && $series !='')?' | ':'').$series."</span>";
            echo "<span title='".$attr.(($attr != '' && $series !='')?' | ':'').$series."' class='enclosure-top-title' >".$series."</span>";
		}
	};
	if(in_array('accessories',$cat_arr)  || in_array('accessoires',$cat_arr) ){
		if(count($categories) > 0){
			$attr =  $categories[0]->name;
			$series = get_field('series',$product->get_id())? get_field('series',$product->get_id()):'';
			echo "<span title='".$attr."' class='enclosure-top-title' >".$attr."</span>";
		}
	};

}, 20 );


function remove_image_zoom_support() {
    remove_theme_support( 'wc-product-gallery-zoom' );
}
add_action( 'wp', 'remove_image_zoom_support', 100 );

/**
 * custom_shop_page_redirect
 *	page shop devient inaccessible
 * @return void
 */
function custom_shop_page_redirect() {
    if( is_shop() ){

        wp_redirect( home_url( __('/product-category/products/','theme-client') ) );
        exit();
    }
}
add_action( 'template_redirect', 'custom_shop_page_redirect' );


//Wrap account with flex
add_action('woocommerce_before_account_navigation', function(){
	echo '<section class="flex-account " ><div class="content">';
});
add_action('woocommerce_after_my_account' , function(){
	echo '</div></section>';
});

//wrap cart with flex + content
add_action('woocommerce_before_cart', function(){
	echo '<div class="content cart-flex">';
});
add_action('woocommerce_after_cart' , function(){
	echo '</div>';
});

//wrap checkout with flex + content
add_action('woocommerce_before_checkout_form', function(){
	echo '<div class="content ">';
},0);
add_action('woocommerce_after_checkout_form' , function(){
	echo '</div>';
});

//wrap thankyou with flex + content
add_action('woocommerce_before_thankyou', function(){
	echo '<div class="content ">';
},0);
add_action('woocommerce_afterthankyou' , function(){
	echo '</div>';
});

//wrap thankyou with flex + content
add_action('woocommerce_before_customer_login_form', function(){
	echo '<div class="content ">';
},0);
add_action('woocommerce_after_customer_login_form' , function(){
	echo '</div>';
});



// SINGLE PRODUCT MODIFICATION

// setup ptoduct summaray as reverse row
add_action('woocommerce_before_single_product_summary', function(){
	echo '<div class="col-2 row-reverse">';
},0); 
add_action('woocommerce_after_single_product_summary', function(){
	echo '</div>';
},0);

add_action('woocommerce_after_single_product', function(){
	echo '<p class="note-change">'.__('Please note that data is subject to change without notice.','theme-client').'</p>';
},0);



//Add the description on top of the page
add_action('woocommerce_single_product_summary', function(){
	global $product;
	echo '<p class="description_product" >'.get_the_excerpt().'</p>';
},0);

//remove title from summary
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

//relocate price 
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_before_add_to_cart_quantity', 'woocommerce_template_single_price', 0 );

//wrap qty and add to cart
add_action( 'woocommerce_before_add_to_cart_quantity', function(){
	echo '<div class="add-to-cart-flex">';
}, 5 );
add_action( 'woocommerce_after_add_to_cart_quantity', function(){
	echo '<span>';
}, 999);
add_action( 'woocommerce_after_add_to_cart_button', function(){
	global $product;
	if(is_user_logged_in()){
		echo '<a rel="noopener" class="add_to_fav '.((in_array($product->get_id(), Wishlist()->get_user_favorites()) !== false) ? 'added' : '').'" href="#'.$product->get_id().'" >'.__('Save for later','theme-client').'</a></span></div>';
	}else{
		echo '<a rel="noopener"  href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" >'.__('Save for later','theme-client').'</a></span></div>';
	}
}, 50 );

//Add label to qty button woocommerce_before_add_to_cart_quantity
add_action( 'woocommerce_before_quantity_input_field', function(){
	echo '<span class="label" >Quantity</span>';
}, 50 );
//remove the post meta
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta',  40 );
//remove the data tabs
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );


//Add Product variation sheet
add_action( 'woocommerce_after_single_product_summary', function(){
	global $product;
	
	if($product->is_type('variable')){ 
		$show = 0; 
		ob_start(); ?>
		<div class="dimensions" >
			<h3><?php _e('Available dimensions','theme-client') ?></h3>
			<table>
				<thead>
					<tr>
						<th rowspan="2" class="r-sku" ><?php _e('Catalog number','theme-client') ?></th>
						<th colspan="5" ><?php _e('Dimensions','theme-client')?></th>
						<th><?php _e('Ship weight','theme-client')?></th>
						<th rowspan="2" ><?php _e('Downloads','theme-client') ?></th>
					<tr>
						<th></th>
						<th>A</th>
						<th>B</th>
						<th>C</th>
						<th></th>
						<!-- <th>D</th>
						<th>E</th> -->
						<th><?php _e('(lbs.)','theme-client') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($product->get_children() as $product_id){
					$variation = wc_get_product($product_id);

					$attr_size = $variation->get_attribute('dimension');
					if(!empty($attr_size)){
						$show++;
						$size_split = explode('x',$attr_size);
						if(count($size_split) > 2){
						?>
						<tr>
							
							<td class="r-sku" ><?php echo $variation->get_sku() ?></td>
							<td></td>
							<td class="r-dimension" ><?php echo $size_split[0] ?></td>
							<td class="r-dimension" ><?php echo $size_split[1] ?></td>
							<td class="r-dimension" ><?php echo $size_split[2] ?></td>
							<td></td>
							<!-- <td class="r-dimension" ><?php echo $size_split[2] ?></td>
							<td class="r-dimension" ><?php echo $size_split[2] ?></td> -->
							<td class="r-weight" ><?php echo $variation->get_weight() ?></td>
							<td class="r-download" >
		
							<div>
									<a target="_blank" href="https://boxcadexm.com/Output/WEB/<?php echo str_replace(' ','',$variation->get_sku()) ?>.pdf">2D Pdf</a>
									<a target="_blank" href="https://boxcadexm.com/Output/WEB/<?php echo str_replace(' ','',$variation->get_sku()) ?>.zip">Zip archive</a>
								</div>
							</td>
						</tr>
						<?php
						}
					}
				}
				?>
				</tbody>
			</table>
		</div>
		<?php
		$table_html = ob_get_clean();
		if($show>0){
			echo $table_html;
		}
	}
}, 10 ); 


//wrap Wocommerce single product gallery
add_action( 'woocommerce_before_single_product_summary', function(){
	global $product;
	echo '<div class="flex-wrap-gallery">';
	echo '<div class="sticky-top">';
	// echo '<p class="description_product" >'.get_the_excerpt().'</p>';
}, 19);
add_action( 'woocommerce_before_single_product_summary', function(){
	global $product;

	echo wc_get_stock_html( $product );
	echo '</div>';
	echo '</div>';
}, 21 );


/* add_filter( 'woocommerce_product_is_in_stock', function($is_in_stock, $product){
    
	if(function_exists('esi_get_warehouses')){
		if($product->is_type('variation')){
		    //die();
			$prod = wc_get_product(get_source_product($product->get_id()));
			$total = 0;
			foreach(esi_get_warehouses() as $key=>$warehouse){
				$val = round(intval($prod->get_meta('_stock_'.$key)),0);
				$total += $val;
				//echo $val;
				//die();
			}
			//echo $total;
			//die();
			return $total > 0;
		}elseif($product->is_type('variable')){
			$prod = wc_get_product(get_source_product($product->get_id()));
			$total = 0;
			foreach($prod->get_available_variations('objects') as $child){
				foreach(esi_get_warehouses() as $key=>$warehouse){
					$total += $child->is_in_stock?1:0;
				}
				
			}
			return $total > 0;
		}
	}
	return $is_in_stock;
},10,2); */

 


add_filter('woocommerce_get_availability', 'custom_get_availability', 1, 2);
function custom_get_availability($availability, $product) {
  if ($availability['availability'] == '') {
    $availability['availability'] = __('In Stock', 'woocommerce');
  }
  return $availability;
}

add_filter( 'woocommerce_single_product_carousel_options', 'sf_update_woo_flexslider_options' );
/** 
 * Filter WooCommerce Flexslider options - Add Navigation Arrows
 */
function sf_update_woo_flexslider_options( $options ) {

	$options['directionNav'] = true;
	$options['controlNav'] = true;
    return $options;
}


//Add 360 image
add_action('woocommerce_product_thumbnails',function(){
	if(get_field('technical_drawing',get_the_ID())): ?>
		<div class="woocommerce-product-gallery__image">
			<a href="<?php echo get_field('technical_drawing',get_the_ID()); ?>)" data-o_href="<?php echo get_field('technical_drawing',get_the_ID()); ?>)">
				<img src="<?php echo get_field('technical_drawing',get_the_ID()); ?>" class="wp-post-image" alt="" loading="lazy" title="1500-BASS-1" data-caption="" data-src="<?php echo get_field('technical_drawing',get_the_ID()); ?>)" data-large_image="<?php echo get_field('technical_drawing',get_the_ID()); ?>)" data-large_image_width="1500" data-large_image_height="1500" srcset="<?php echo get_field('technical_drawing',get_the_ID()); ?>) 1500w" data-o_sizes="(max-width: 600px) 100vw, 600px" data-o_title="1500-BASS-1" data-o_data-caption="" data-o_alt="" data-o_data-src="<?php echo get_field('technical_drawing',get_the_ID()); ?>)" data-o_data-large_image="<?php echo get_field('technical_drawing',get_the_ID()); ?>)" data-o_data-large_image_width="1500" data-o_data-large_image_height="1500" width="600" height="600">
			</a>
		</div>
	<?php endif;
	if ( woo3dv_is_woo3dv( get_the_ID() ) ) {
		//<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_src[0] ) . '">' . $image . '</a></div>
		 ?>
			<div class="woocommerce-product-gallery__image">
				<?php echo woo3dv_woocommerce_single_product_image_html (0, get_the_ID())  ?>
			</div>
		<?php
	}
},999);





// add_action('wp_enqueue_scripts', function(){
// 	wp_add_inline_script( 'woo3dv-frontend.js', 'woo3dv.default_scale = 1;', 'before');
// }, 11);


add_filter( 'woocommerce_variation_option_name', function($termname, $term){
	if($term->taxonomy == 'pa_dimension'){
		return str_replace('x',' x ',$termname);
	}
	return $termname;
},2,10);


/* 
 *Personaliser les champ attributs
 * change les argument a l'entrée des atteributs
 */
add_filter( 'woocommerce_dropdown_variation_attribute_options_args', function ( $args ) {
	// if('pa_dimension' === $args['attribute']){
	// 	$args['show_option_none'] = '0000 x 0000 x 0000 in';
	// } else 
	if('pa_material' === $args['attribute']){
		$attributes = $args['product']->get_variation_attributes();
		$options    = $attributes[ $args['attribute'] ];
		$args['colors'] = [];
		foreach($options as $option){
			$term = get_term_by('slug',$option,'pa_material');
			if(get_field('label_color',$term)){
				$args['colors'][$term->name] = get_field('label_color',$term);
			}
		}
		$args['class'] = 'full-width with-color';
	}
    return $args;
}, 10 );
/*
 * Personalise le label des attributs
 */
add_filter( 'woocommerce_attribute_label', function($label, $name, $product){
	if(is_single()){
		if('pa_dimension' === $name){
			$label.=" <span>".__('(height x width x depth)','theme-client')."</span>";
		}
	} 
	return $label; 
},10 ,3);
/**
 * Personalise le html des attributs
 */
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', function($html, $args){
	if('pa_material' === $args['attribute']){
		foreach($args['colors'] as $attr=>$color){
			$pos_attr = strpos($html,$attr);
			if($pos_attr){
				$html = substr_replace($html, ' data-color="'.$color.' "', $pos_attr-1, 0);
			}
		}
	}
	return $html;
},10,2 );



/**
 * Ajoute un petit lien sur cetaines ioptions de woocommerce filter 
 */
add_filter( 'widget_title', function($title, $instance, $id_base){
	if( "woocommerce_layered_nav" === $id_base){
		if('NEMA' == $title){
			if(get_field('nema_doc','options')){
				$title.= '<a href="'.get_field('nema_doc','options').'" target="_blank">'.__('Which NEMA is right for you?','theme-client').'</a>';
			}
		}else if('IP' == $title){
			if(get_field('ip_doc','options')){
				$title.= '<a href="'.get_field('ip_doc','options').'" target="_blank">'.__('Which IP is right for you?','theme-client').'</a>';
			}
		}
	}
	return $title;
},10,3 );

add_filter( 'woocommerce_layered_nav_term_html', function($term_html, $term, $link){
	if($term->taxonomy == 'pa_dimension'){
		return '<a rel="nofollow" href="'.$link.'" class="open">'.str_replace('x',' x ',$term->name).'</a>';
	}
	return $term_html;
},2,10 );




add_filter('woocommerce_cart_item_price',function($content){
	return '<span class="mobile-label">'.__('Price','woocommerce').':</span>'.$content;
},10,1);
add_filter('woocommerce_cart_item_subtotal',function($content){
	return '<span class="mobile-label">'.__('Subtotal','woocommerce').':</span>'.$content;
},10,1);

add_action('woocommerce_after_add_to_cart_form',function(){
	global $product;
	if(get_field('technical_specifications_sheet')){
		echo "<a target='_blank' class='button tech_sheet' href='".get_field('technical_specifications_sheet')."' ><i class='far fa-eye'></i> ".__('View the full technical sheet','theme-client')."</a>";
	}
	
});

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

function woocommerce_template_single_excerpt() {
    return;
}


remove_filter( 'wc_get_template', 'woo3dv_get_template', 10 );
remove_filter( 'woocommerce_locate_template', 'woo3dv_woocommerce_locate_template', 10 );


add_filter('woocommerce_available_variation',function($variation){
    if($variation['variation_description'] == NULL) {    
        //$product_full_description = $product_instance->get_description();
        //$product_instance = WC_Product::get_parent_id( $variation[ 'variation_id' ] );
        //$product_instance = wc_get_product($variation[ 'variation_id' ]);
        $product_instance = wc_get_product($variation[ 'variation_id' ]);
        $parent_id = $product_instance->get_parent_id();
        $parent_instance = wc_get_product($parent_id);
        $variation['variation_description'] = $parent_instance->get_description();
    }
	if(strpos($variation['variation_description'],';')){
		$variation['variation_description'] = str_replace('</p>','',str_replace('<p>','',$variation['variation_description'] ));
		$variation['variation_description'] = '<div class="technical-spec"><h3 class="">'.__('Technical specifications','theme-client').'</h3><ul class="">'.implode('',array_map(function($val) { return '<li>'.$val.'</li>';} ,explode(';',$variation['variation_description']))).'</ul></div>';
	}
	$key_included = get_post_meta( $variation[ 'variation_id' ], 'key_included', true );
	$key_number = get_post_meta( $variation[ 'variation_id' ], 'key_number', true );
	
	if($key_included && $key_number){
		$html_add = '<div class="product_info"><h3 class="">'.__('Key included','theme-client').': '.$key_included.'</h3></div>';
		$html_add .= '<div class="product_info"><h3 class="">'.__('Key number','theme-client').': '.$key_number.'</h3></div>';
		$variation['variation_description'] = $html_add.$variation['variation_description'];
	} 

	return $variation;
});



add_filter('woocommerce_dropdown_variation_attribute_options_args',function($option){
	
	// if($option['attribute'] == 'pa_material'){
		$option['selected'] = $option['options'][0];
	// }
	
	return $option;
});


add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );
add_filter( 'woocommerce_available_variation', 'load_variation_settings_fields' );

function variation_settings_fields( $loop, $variation_data, $variation ) {
    woocommerce_wp_text_input(
        array(
            'id'            => "key_included{$loop}",
            'name'          => "key_included[{$loop}]",
            'value'         => get_post_meta( $variation->ID, 'key_included', true ),
            'label'         => __( 'Key included', 'theme-client' ),
            'desc_tip'      => true,
            'description'   => __( '', 'theme-client' ),
            'wrapper_class' => 'form-row form-row-full',
        )
    );
	woocommerce_wp_text_input(
        array(
            'id'            => "key_number{$loop}",
            'name'          => "key_number[{$loop}]",
            'value'         => get_post_meta( $variation->ID, 'key_number', true ),
            'label'         => __( 'Key number', 'theme-client' ),
            'desc_tip'      => true,
            'description'   => __( '', 'theme-client' ),
            'wrapper_class' => 'form-row form-row-full',
        )
    );

}

function save_variation_settings_fields( $variation_id, $loop ) {
    $text_field = $_POST['key_included'][ $loop ];
    if ( ! empty( $text_field ) ) {
        update_post_meta( $variation_id, 'key_included', esc_attr( $text_field ));
    }
	$text_field = $_POST['key_number'][ $loop ];
    if ( ! empty( $text_field ) ) {
        update_post_meta( $variation_id, 'key_number', esc_attr( $text_field ));
    }
}

function load_variation_settings_fields( $variation ) {     
    $variation['key_included'] = get_post_meta( $variation[ 'variation_id' ], 'key_included', true );
    $variation['key_included'] = get_post_meta( $variation[ 'variation_id' ], 'key_number', true );

    return $variation;
}


add_filter('woocommerce_get_product_terms',function($terms,$product_id, $taxonomy, $args ){
	if($taxonomy == 'pa_dimension'){
		usort($terms,'pa_dimension_sorter');
		return $terms;
	}
	return $terms;
},10,4 );



/**
 * pa_dimension_sorter
 * Effectue une comparaison sur la valeur numérique des dimensions de deux term. Récusive si deux valeur sont égale
 *
 * @param  mixed $a objet term
 * @param  mixed $b objet term
 * @return void
 */
function pa_dimension_sorter($a,$b){
	$max_depth = 2;
	$reorder = 0;
	for($i=0;$i<=$max_depth;$i++){
		$reorder = order_by_pa_attribute_exploded_value($a,$b,$i);
		if($reorder != 0){
			break;
		}
	}
	return $reorder; 
}

/**
 * order_by_pa_attribute_exploded_value
 * Compare deux term "pa_dimension" selont sa valeur numérique
 *
 * @param  mixed $a objet term
 * @param  mixed $b objet term
 * @param  mixed $depth index qui sera testé
 * @return void
 */
function order_by_pa_attribute_exploded_value($a,$b,$depth=0){
	$nbra = floatval(explode('x',$a->name)[$depth]) ?? 0;
	$nbrb = floatval(explode('x',$b->name)[$depth]) ?? 0;
	return $nbra > $nbrb ? 1:( $nbra == $nbrb ? 0 :-1 );
}

/**
 * Force l'odre des terms
 */
add_filter( 'get_terms', function($terms, $taxonomy){
	if($taxonomy[0] == 'pa_dimension'){
		usort($terms,'pa_dimension_sorter');
	}
	return $terms;
},10,2);


/**
 * Ajoute un selectplus dans le WC_Widget_Layered_Nav de woocommerce
 *
 * @return void
 */
add_filter( 'dynamic_sidebar_params',function($params){

	global $wp_registered_widgets; // Va chercher les widgets enregistrés
	$widget_id = $params[0]['widget_id'];// le ID du widget
	$numero = $params[1]['number']; // le numero du widget // n'est pas le ID 

	if(isset($wp_registered_widgets[$widget_id])){ // Si le widgetID est présent dans les widget qui ont été registered

		$widget = $wp_registered_widgets[$widget_id];// Le widget enrigstré 
		$wc_widget_object = $widget['callback'][0]; // L'instance du widget (si widget WC)

		if(get_class($wc_widget_object) == "WC_Widget_Layered_Nav" ){ // on exécuste ces fonctions seulement si l'ont est une instance WC_Widget_Layered_Nav
			$settings = $wc_widget_object->get_settings(); // Va chercher les settings de l'instance WP_WIDGET
			if(isset($settings[$numero])){ // si des paramètres sont présent pour le widget actuel
				if(isset($settings[$numero]['attribute'])){ // si le param attibute existe
					if($settings[$numero]['attribute'] == 'dimension'){
						ob_start(); ?>
						<div class="dimension-filter-select">
							<select  class="size-select"  name="dimensions_filtre[]" id="dimensionfilter">
								<!-- <option value="" ><?php _e('Choose','theme-client') ?></option> -->
								
								<?php foreach(get_terms('pa_dimension') as $dimension) : ?>
									<option value="<?php echo $dimension->slug ?>" ><?php echo str_replace('x',' x ',$dimension->name) ?> </option>
								<?php endforeach; ?>
							</select>
							<span class="size-switch">
								<span data-size="in" class="selected">in</span>
								<span data-size="mm">mm</span>
							</span>
						</div>
						<?php $params[0]['after_title'] .= ob_get_clean();
						$params[0]['before_title'] = '<h4 class="widget-title noToggle">';
					}
				}	
			}
		}
	}
	return $params;
},10,1 );


add_action( 'woocommerce_before_shop_loop',function(){
	echo '<div class="prod-loader" ><div id="cdm-loader-prod"><div></div><div></div><div></div><div></div></div></div>';
},40 );

// // Check if woocommerce stock is out of sync, if out of sync (0 or higher than real stock), update stock
function check_entrepot_stock($product) {
	if(function_exists('esi_get_warehouses')){
	if($product->is_type('variation')){
		$prod = wc_get_product(get_source_product($product->get_id()));
		$total = 0;
		foreach(esi_get_warehouses() as $key=>$warehouse){
			$val = round(intval($prod->get_meta('_stock_'.$key)),0);
			$total += $val;
		}
		if ($product->get_stock_quantity() == 0 || $product->get_stock_quantity() > $total) {
			wc_update_product_stock( $product, $total);
		}
	}
	}
}
   
add_filter( 'woocommerce_get_availability_text', function($availability, $product){

	if(function_exists('esi_get_warehouses')){
		if($product->is_type('variation')){
			$prod = wc_get_product(get_source_product($product->get_id()));
			$total = 0;
			foreach(esi_get_warehouses() as $key=>$warehouse){
				$val = round(intval($prod->get_meta('_stock_'.$key)),0);
				$total += $val;
			}
			$availability = $total.' '.__('in stock','theme-client');
		}
	}

	return $availability;
},10,2);


add_filter( 'woocommerce_get_stock_html', function($html, $product)
{ 
	$pos_end_tag = strpos($html,'</div>');
    
	ob_start();
    
	?><div class="pickup_stock"><?php
    echo '<span class="check_availability">'.__('Check availability for pick up','theme-client').'</span>';
    ?><ul><li class="close-stock"><i class=" fal fa-times"></i></li><?php
    
    
    
    
    if(function_exists('esi_get_warehouses')){
        
        
        foreach(esi_get_warehouses() as $key=>$warehouse){
            $address = "";
            $phone = "";
            switch($key){
				    case 'bl': 
					   $address = '18 Rue Lapointe Suite 100, Mirabel, Québec J7J 0G2';
					   $phone = '450-979-4373';
					   break;
                    case 'ed': 
						$address = '6010 Edwards Blvd, Mississauga, Ontario L5T 2W3';
						$phone = '905-812-8065';
						break;
				    case 'cal': 
						$address = '18 Highland Park Way Northeast, Airdrie, Alberta T4A 2L5';
						$phone = '905-812-8065';
						break;
                    /*case 'mir': 
						$address = '18 005 Lapointe Street, Suite 100 Mirabel (Quebec) J7J 0G2';
						$phone = '450-979-4373';
						break;*/
					}
							
					$prod = wc_get_product(get_source_product($product->get_id()));
					$stock = $prod->get_meta('_stock_'.$key);
					?>
					<li>
						<i class="fal toggle_address"></i>
						<p class="warehouse-name"><?php echo $warehouse ?></p>
						<p class="stock-qty <?php echo ((round(intval($stock),0) > 0)?'':'out') ?> "><?php echo round(intval($stock),0) ?> <?php echo __('in stock','theme-client') ?></p>
						<div class="address">
							<a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($address) ?>"><?php echo $address ?></a>
							<a href="tel:<?php echo $phone ?>"><?php echo $phone ?></a>
						</div>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</div>
	<?php

    $boxcadLanguageCode = apply_filters( 'wpml_current_language', null );
    if($boxcadLanguageCode == 'en'){$boxcadLanguage='English';}
    if($boxcadLanguageCode == 'fr'){$boxcadLanguage='French';}
    if($boxcadLanguageCode == 'zh-hans'){$boxcadLanguage='Chinese';}
    
    global $current_user;
    get_currentuserinfo();
    $boxcadEmail = $current_user->user_email;
    
	//global $product;

	$boxcadSKU 	  = $product->get_sku();
			
	if ( $product->get_type() == 'variation') 
	{
		$parentPro = $product->get_parent_data();
		$boxcadSKU = $parentPro['sku'];
	}
    
    $boxcadRating = str_replace(' ', '', $boxcadSKU);

	$default_attributes =  $product->default_attributes;

	if(empty($default_attributes))
	{	
		if ( $product->get_type() == 'variable') 
		{	
			$productId 			= $product->get_id();
			$handle 			= new WC_Product_Variable($productId);
			$variationData      = $handle->get_children();
			$provar             = reset($variationData);
			$single_variation   = new WC_Product_Variation($provar);
			$firstVariation     =  $single_variation->get_variation_attributes()['attribute_pa_dimension'];
			$fvariation 	  	=  explode("x",$firstVariation);
			$boxcadHeight 		=  $fvariation[0];
			$boxcadWidth  		=  $fvariation[1];
			$boxcadDepth  		=  $fvariation[2];
		}
	}
	else
	{
		$variation 	  		= explode("x",$default_attributes['pa_dimension']);
		$boxcadHeight 		=  $variation[0];
		$boxcadWidth  		=  $variation[1];
		$boxcadDepth  		=  $variation[2]; 
	}
	?>
	<script>  
		
		jQuery(document).ready (function ()	 
		{
			// On page load START ************
				var pageLoadDim        = jQuery('#pa_dimension').val().split('x');
				var boxcadHeight       = pageLoadDim[0];
				var boxcadWidth        = pageLoadDim[1];
				var boxcadDepth        = pageLoadDim[2];  
				var boxcadEmail        = '<?php echo $current_user->user_email; ?>';
				var boxcadRating 	   = '<?php echo $boxcadRating; ?>';
				var boxcadLanguageCode = '<?php echo apply_filters( 'wpml_current_language', null ); ?>';
						
				if( boxcadLanguageCode == 'en') 
				{  
					var boxcadLanguage = 'English';
				}
				if( boxcadLanguageCode == 'fr') {  
					var boxcadLanguage = 'French';
				}
				if( boxcadLanguageCode == 'zh-hans') {  
					var boxcadLanguage = 'Chinese';
				}

				var boxcadUrl = 'https://www.boxcadexm.com/?ProcessName=BoxCadNew.rdx&Language='+boxcadLanguage+'&Email='+boxcadEmail+'&Rating='+boxcadRating+'&Height='+boxcadHeight+'&Width='+boxcadWidth+'&Depth='+boxcadDepth;
				
				jQuery("a.boxcadUrl").attr("href",boxcadUrl);
			//  On page load END **********

			// On Dimension (height x width x depth) Change START ****************
				jQuery("body").on('change', 'select#pa_dimension', function (e) 
				{  
					e.preventDefault();
					var selectedDinemsion = jQuery("#pa_dimension").val();
					var result = selectedDinemsion.split('x');
					var Height = result[0];
					var Width  = result[1];
					var depth  = result[2];  
					var boxcadEmail = '<?php echo $current_user->user_email; ?>';
					var boxcadRating = '<?php echo str_replace(' ', '', $boxcadSKU); ?>';
					
						var boxcadLanguageCode = '<?php echo apply_filters( 'wpml_current_language', null ); ?>';
						
						if( boxcadLanguageCode == 'en') 
						{  
							var boxcadLanguage = 'English';
						}
						
						if( boxcadLanguageCode == 'fr') {  
							var boxcadLanguage = 'French';
						}
						
						if( boxcadLanguageCode == 'zh-hans') {  
							var boxcadLanguage = 'Chinese';
						}

					var boxcadUrl = 'https://www.boxcadexm.com/?ProcessName=BoxCadNew.rdx&Language='+boxcadLanguage+'&Email='+boxcadEmail+'&Rating='+boxcadRating+'&Height='+Height+'&Width='+Width+'&Depth='+depth;
					jQuery("a.boxcadUrl").attr("href",boxcadUrl);
						
				});  
			// On Dimension (height x width x depth) Change END	*********************
		}); 
	</script>  
	<?php

	// $boxcadUrl = 'https://www.boxcadexm.com/?ProcessName=BoxCadNew.rdx&Language=' . $boxcadLanguage . '&Email=' . $boxcadEmail . '&Rating=' . $boxcadSKU . '&Height=' . $boxcadHeight . '&Width=' . $boxcadWidth . '&Depth=' . $boxcadDepth;

	// $product_cats = wp_get_post_terms( get_the_ID(), 'product_cat' );

	// $args = array(
	// 	'separator' => $separator,
	// 	'link'      => $link,
	// 	'format'    => $format,
	// );
	// echo '<p style="display:none;">'.get_term_parents_list( $product_cats[0]->term_id, 'product_cat', $args ).'</p>';

	// if($product_cats[0]->slug !== 'accessories'){
	// 	if(!empty($boxcadUrl))
	// 	{ ?>
	 		<!-- <a rel="noopener" target="_blank" id="boxcadUrl" class="button customize boxcadUrl" href="<?php echo $boxcadUrl;?>"><?php echo __('Customize on BOXCAD','theme-client')?></a>
	// 		<br> -->
	 		<?php 
	// 	}
	// }

	$popup = ob_get_clean();
	$insert = substr_replace($html, $popup, $pos_end_tag, 0);
	return $insert;
},10 ,2);


function get_source_product($product_id){
	$return = apply_filters( 'wpml_object_id', $product_id, 'product_variation', TRUE, 'en' );
	return 	$return;
}

