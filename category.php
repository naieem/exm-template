<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package theme-client
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
                <div class="container">
				    <h1 class="page-title"><?php _e('News', 'theme-client'); ?></h1>
                    <div class="archive-description"><?php _e('Stay informed about our products and service.', 'theme-client'); ?></div>
                </div>
			</header><!-- .page-header -->
            
            <div class="container">
                <nav class="news-category-nav">
                    <?php
                    $categories = get_categories(array(
                        'hide_empty' => 1,
                        'taxonomy'   => 'category',
                    ));

                    $special_categories = array();
                    $other_categories = array();

                    if (!empty($categories)) {
                        foreach ($categories as $category) {
                            if ($category->term_id == 2924 || $category->term_id == 2927) {
                                $special_categories[] = $category;
                            } else {
                                $other_categories[] = $category;
                            }
                    }
    
                    // Merge the special and other categories arrays
                    $merged_categories = array_merge($special_categories, $other_categories);
    
                    echo '<ul>';
    
                        foreach ($merged_categories as $category) {
                            $category_link = get_category_link($category->term_id);

                            // Check if the current category is the selected category
                            $active_class = (is_category($category->term_id)) ? 'active' : '';

                            echo '<li><a href="' . $category_link . '" class="' . $active_class . '">' . $category->name . '</a></li>';
                        }

                    echo '</ul>';
                    }
                    ?>
                </nav>
            
                <div class="news-container">
			    <?php
                /* Start the Loop */
			
                while ( have_posts() ) :
				    the_post();

				    /*
                    * Include the Post-Type-specific template for the content.
                    * If you want to override this in a child theme, then include a file
                    * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                    */
				    ?>
            
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    	                <header class="entry-header">
                            <div class="entry-meta">
                                <?php
                                theme_client_post_thumbnail();
                                theme_client_posted_on();
                                echo '<br>';
                                the_category();
                                the_title( '<h1 class="entry-title">', '</h1>' );
                                the_excerpt();
                                ?>
                            
                                <p class="more-link-container"><a class="more-link" href="<?php the_permalink(); ?>"><?php _e( 'Read More', 'exm' ); ?></a></p>
                            
			                 </div><!-- .entry-meta -->
	                    </header><!-- .entry-header -->
                    </article>
            
                <?php
                endwhile;
                ?>
                </div>
            
                <div class="blog-pagination">
                    <?php
                    echo paginate_links();
                    ?>
                </div>
            </div>
                
            <?php
            endif;
            ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

get_footer();
