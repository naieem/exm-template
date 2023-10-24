<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package theme-client
 */

get_header();
?>
    <section class="banner-top">
		<div class="content-banner content">
			<div class="left">
				<h1><?php _e("404",'theme-client') ?></h1>	
			</div>
			<div class="right">
			</div>
		</div>
	</section>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<section class="error-404 not-found">
				<header class="page-header content">
				<div class="wp-block-group alignwide">
					<div class="wp-block-group__inner-container">
						<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'theme-client' ); ?></h1>
						<br>
						<div class="wp-block-button">
							<a class="wp-block-button__link has-color-1-color has-color-2-background-color has-text-color has-background" href="<?php echo esc_url( home_url('/') ) ?>" style="border-radius:5px"><?php _e('Return to homepage','theme-client')?></a>
						</div>
					</div>
				</div>
				</header><!-- .page-header -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();




	