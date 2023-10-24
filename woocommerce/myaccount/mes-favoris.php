<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<ul class="products">
<?php
$favorites = Wishlist()->get_user_favorites();
if(!empty($favorites)){
	$args = array (
		'post_type' => 'product',
		'post__in'	=> $favorites,
	);
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			wc_get_template_part( 'content', 'product' );
		}
		wp_reset_postdata();
	}else{
		wc_print_notice( sprintf( __('You have no favorites at the moment,  <a class="button" href="%s">check out shop now</a>','theme-client'), get_permalink( wc_get_page_id( 'shop' ) ) ), 'notice' );
	}
}
?>
</ul>