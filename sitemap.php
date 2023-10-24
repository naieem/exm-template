<?php
/**
 * The template for displaying the sitemap.
 *
 * @package theme-client
 */

get_header();
?>
    <section class="banner-top">
		<div class="content-banner content">
			<div class="left">
				<h1><?php _e("Website map",'theme-client') ?></h1>	
			</div>
			<div class="right">
			</div>
		</div>
	</section>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
            <article id="custom-sitemap">
                <div class="entry-content">
                    <div class=" sitemap-wrapper">
                        <div class="wp-block-group">
                            <div class="wp-block-group__inner-container">
                                <?php print_site_map(); ?>
                            </div>
                        </div>
                    </div>
                </div><!-- .entry-content -->
            </article><!-- #post-<?php the_ID(); ?> -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php

get_footer();
