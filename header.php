<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package theme-client
 */
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js preload">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri().'/style.css'?>">
    <style><?= theme_client_inline_include('/css/init.min.css') ?></style>
    <script type="text/javascript"><?= theme_client_inline_include('/js/inline_init.min.js') ?></script>
    <script src="https://kit.fontawesome.com/70ba5bc987.js" crossorigin="anonymous" async="async"></script>
	<?php wp_head(); ?>
    
    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-W4XNHQX');
    </script>
    <!-- End Google Tag Manager -->
	
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-49572267-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-49572267-1');
	</script>
		
	<!-- Google tag GA4 (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-6CC0ZT2V75"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-6CC0ZT2V75');
	</script>

    <!--Start of Tawk.to Script-->
    <!--<script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/63599c6cb0d6371309cbbad2/1ggb0m1uv';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>-->
    <!--End of Tawk.to Script-->

</head>

<body <?php body_class(); ?>>
	<?php get_template_part('template-parts/inc', 'loader'); ?>
	<span id="header-intersection-observer"></span>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'theme-client' ); ?></a>

        <?php
            $tax = $wp_query->get_queried_object();
            $taxslug = $tax->slug;
        ?>

        <header id="masthead" class="site-header <?php echo ((get_field('white_header'))?'white':'') ?> <?php echo ((get_field('black_header'))?'black':'') ?> <?php echo 'tax-'.$taxslug; ?>">
            
            <div class="content nav-wrapper mobile_search_bar">
                  
				<!-- <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><i class="fal fa-bars"></i></button> -->
				<div class="site-branding">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-title"><img src="<?= get_template_directory_uri() ?>/images/svg/exm-logo-color.svg" alt="<?php bloginfo( 'name' ); ?>" /></a>

				</div><!-- .site-branding -->
                <div class="mobile_header_icon_wrapper">
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><i class="fal fa-bars"></i></button>
                    <div class="mobile_header_search_wrapper">
                        <i class="fal fa-search" aria-hidden="true"></i>
                    </div>
                
				<?php theme_client_woocommerce_header_cart(['class'=>'mobile-only']); ?>
                </div>
				<nav id="site-navigation" class="main-navigation">
					<!--<div id="exm-header-top-section">-->
                    
                        <div class="additional-options">
							<ul>
                                <li>
                                    <?php if ( is_user_logged_in() ) { ?>
                                        <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','woothemes'); ?>"><?php if( $current_user = wp_get_current_user() ) echo $current_user->first_name . " " . $current_user->last_name . " | " . __('My Account','theme-client'); ?></a>
                                    <?php } 
                                    else { ?>
 	                                    <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Login / Register','woothemes'); ?>"><?php _e('Login / Register','theme-client'); ?></a>
                                    <?php } ?>
                                </li>
								<?php language_selector() ?>
                                <?php do_action('wcml_currency_switcher', array('format' => '%code% (%symbol%)')); ?>
                            </ul>
					    </div>
                    <!--</div>-->
                    <?php 
                    $link = get_term_link( get_field('finder_target','options'), 'product_cat' );
                    if($link):
                    ?>
                    <div class="exm-global-product-search">
                        <form role="search" method="get" id="searchform" class="searchform acx_search_nf" action="<?php echo $link; ?>" >
                            <div style="display:flex; width:100%;">
                                <input type="text" value="<?php get_search_query() ?>" name="s" id="exm-gp-search" placeholder="<?php _e('Search products','theme-client') ?>" />
                            </div>
                        </form>
                    </div>
                    <div class="exm_top_header_wrapper">
                        <button><i class="fal fa-search"></i></button>
                    </div>
                    <?php endif; ?>                    
					<?php
					wp_nav_menu( array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'walker' => new MenuWalker()
					) );
					?>
				</nav><!-- #site-navigation -->
				
			</div>
		</header><!-- #masthead -->

		<?php 
			if( is_tax( 'product_cat' ) ) {
				$terms_list = exm_get_product_cat_terms_list();
				
				echo '<input type="hidden" name="limit_product_cat" value="'.end($terms_list)->slug.'" />';
				echo '<!--Shop search cat-->';
			}
		?>
		<div id="content" class="site-content">
