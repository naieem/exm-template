<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package theme-client
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<h3><?php _e('Filters', 'theme-client'); ?><span class="reset-filters" style="display: none;" ><?php _e('Clear all', 'theme-client'); ?></span></h3>
	<div class="filters-Tag"></div>
	<hr>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
