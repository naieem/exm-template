<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package theme-client
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="footer-contact content">
			<div class="moitiee">
				<figure class="site-branding">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-title"><img src="<?= get_template_directory_uri() ?>/images/svg/exm-logo-reversed.svg" alt="<?php bloginfo( 'name' ); ?>" /></a>
				</figure>
				<div>
					<p class="label"></p>
					<ul>
						<?php //currency_selector() ?>
                        <?php // do_action('wcml_currency_switcher', array('format' => '%name% (%symbol%)')); ?>
						<?php language_selector() ?>
		
						<li><a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ?>"><?php echo _e('Account','theme-client')?></a></li>
					</ul>
				</div>
				<div>
					<p class="label"><?php _e('Shop','theme-client')?></p>
					<?php
						wp_nav_menu( array(
							'theme_location' => 'footer-1',
							'menu_id'        => 'footer-menu-1'
						) );
					?>
				</div>
				<div>
					<p class="label"><?php _e('Learn','theme-client')?></p>
					<?php
						wp_nav_menu( array(
							'theme_location' => 'footer-2',
							'menu_id'        => 'footer-menu-2'
						) );
					?>
				</div>
			</div>
			<div class="quart">
				<div>
					<p class="label"><?php _e('Contact us','theme-client')?></p>
					<ul>
						<li>
							<p><a href="mailto:<?php echo get_field('courriel','options') ?>"><?php echo get_field('courriel','options') ?></a></p>
							<p><a href="tel:<?php echo str_replace([',',' ','.','(',')','-'],'',get_field('telephone','options')) ?>"><?php echo get_field('telephone','options') ?></a></p>
							<p>
								<?php get_template_part('template-parts/inc-sociaux') ?>
							</p>
						</li>
					</ul>
				</div>
				<div class="align-bottom infolettre">
					<p class="label no-border"><?php _e('Stay up to date','theme-client')?></p>
					<ul>
						<li>
							<!--<form class="newsletter-form" action="">
								<input type="text" name="email" placeholder="<?php /* _e('Enter Email','theme-client')?>">
								<input type="submit" value="<?php _e('Subscribe','theme-client') */ ?>">
							</form>-->
							
							<!-- Begin Constant Contact Inline Form Code -->
							<?php
							if (ICL_LANGUAGE_CODE=='fr') { ?>
								<div class="ctct-inline-form" data-form-id="3b8937b7-a689-40e4-9477-e164715765bb"></div>	
							<?php } else { ?>
								<div class="ctct-inline-form" data-form-id="6580f545-a05b-4c76-9349-766a3c770206"></div>
							<?php } ?>
							<!-- End Constant Contact Inline Form Code --> 
							
							<div class="form-error-log"></div>
							<?php if(get_field('url_newsletter','options')): ?>
								<a target="_blank" href="<?php echo get_field('url_newsletter','options') ?>"><?php _e('See all newsletters','theme-client')?></a>
							<?php endif; ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="site-info content">
			<hr>
			<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-3',
					'menu_id'        => 'footer-menu-3'
				) );
			?>

			<span><?php _e('Copyright', 'theme-client') ?> &copy; EXM <?php echo date('Y'); ?>. <?php _e('All Rights Reserved','theme-client')?></span>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<script type="text/javascript">
jQuery(document).ready(function () 
{
    jQuery("ul.products li.serie-880-scq4").remove();
    jQuery("ul.products li.serie-880-scqcom").remove();
    jQuery("ul.products li.serie-889-scq4").remove();
    jQuery("ul.products li.locks-and-handles-3").remove();
    jQuery("ul.products li.products").remove();
    jQuery("ul.products li.lighting").remove();   
    jQuery("ul.products li.eclairage").remove();
    jQuery("ul.products li.produits").remove();
    jQuery("ul.products li.series-880-scq4").remove();
    jQuery("ul.products li.series-880-scqcom").remove();
    jQuery("ul.products li.series-889-scq4").remove();
    jQuery("ul.products li.serrures-et-poignees").remove();
    jQuery("ul.products li.distribution-accessories").remove();
    jQuery("ul.products li.distribution-accessoires").remove();
    jQuery("ul.products li.cooling-accessories").remove();
    jQuery("ul.products li.accessoires-polyester").remove();
    jQuery("ul.products li.din-rails-accessories").remove();
    jQuery("ul.products li.capteurs-denvironnement").remove();
    jQuery("ul.products li.doors-covers-accessories").remove();
    jQuery("ul.products li.chauffage").remove();
    jQuery("ul.products li.environmental-sensors-accessories").remove();
    jQuery("ul.products li.ensembles-de-fenetres").remove();
    jQuery("ul.products li.floor-stands-accessories").remove();
    jQuery("ul.products li.interverrouillages").remove();
    jQuery("ul.products li.gland-plates-accessories").remove();
    jQuery("ul.products li.mise-a-la-terre").remove();
    jQuery("ul.products li.ventilation").remove();
    jQuery("ul.products li.ventilation-fr").remove();
    jQuery("ul.products li").removeClass("last");
    jQuery("ul.products li").removeClass("first");
    jQuery( "li.product-category" ).hover
	(
		function() {
			var color=jQuery(this).attr( "data-color" );
			if(color && color.length)
			jQuery( this ).css( "background-color", color );
		}, 
		function() 
		{
			jQuery( this ).css( "background", 'none' );
		}
    );
});

// Home Product-Slider on Hover Change START ********************************

jQuery(document).ready(function() 
{   
	jQuery('a.type-wrapper').hover(function(a) 
	{
		var color=jQuery(this).attr( "data-color" );
        if(color && color.length)
        jQuery(this).css("background-color", color );
    }, 
	function() 
	{
		jQuery(this).css( "background", 'none' );
	}
	);	
});    

// Home Product-Slider on Hover Change END ***********************

</script>

<script>
jQuery(document).ready(function() {
    if ( jQuery(window).width() > 880){
        var iconAppended = false;

        // Click event for .header_top_search
        jQuery(".menu-menu-principal-container .header_top_search, .menu-menu-principal-fr-container .header_top_search").click(function() {
            jQuery('.menu-menu-principal-container, .menu-menu-principal-fr-container').slideToggle(400);
            jQuery('.exm-global-product-search').slideToggle(400);
            
            if (!iconAppended) {
            jQuery('.exm-global-product-search div').append('<i class="fal fa-search fa-solid fa-xmark"></i>');
            iconAppended = true;
            }
            
            jQuery('.menu-menu-principal-container .header_top_search, .menu-menu-principal-fr-container .header_top_search').toggleClass('active');
        });

        // Click event for .exm-global-product-search div i
        jQuery(document).on('click', '.exm-global-product-search div i', function() {
            jQuery('.menu-menu-principal-container, .menu-menu-principal-fr-container').slideToggle(400);
            jQuery('.exm-global-product-search').slideToggle(400);
            
            if (iconAppended) {
            jQuery('.exm-global-product-search div i').remove();
            iconAppended = false;
            }
            
            jQuery('.menu-menu-principal-container .header_top_search, .menu-menu-principal-fr-container .header_top_search').removeClass('active');
        });
    }

    //   mobile View
    if ( jQuery(window).width() < 880 ){
        
        var mobileiconAppended = false;

        // Click event for .header_top_search
        jQuery(".mobile_header_search_wrapper i").click(function() {
            jQuery('.exm-global-product-search').show(400);
            if (!mobileiconAppended) {
                jQuery('.exm-global-product-search div').append('<i class="fal fa-search fa-solid fa-xmark"></i>');
                mobileiconAppended = true;
            }

            // jQuery('.mobile_header_search_wrapper i').toggleClass('active');
        });
        jQuery(document).on('click', '.exm-global-product-search div i', function() {
            jQuery('.exm-global-product-search').hide(400);
        });
    }
});
</script>   
<script>
jQuery(document).ready(function() {   
    var iframe = document.querySelector('#vimeo-player');
    var player = new Vimeo.Player(iframe);

    // Pause the video initially
    player.pause();

    // Add an event listener to play the video on user interaction
    iframe.addEventListener('click', function () {
        player.play();
    });

    // Add the "hasPlayed" class to the iframe initially
    iframe.classList.add('hasPlayed');

    // Add an event listener to detect page scroll and remove the "hasPlayed" class
    var hasPlayedRemoved = false; // To track whether the class has been removed
    window.addEventListener('scroll', function() {
        if (!hasPlayedRemoved) {
            var iframeRect = iframe.getBoundingClientRect();
            if (iframeRect.bottom < 0 || iframeRect.top > window.innerHeight) {
                iframe.classList.remove('hasPlayed');
                hasPlayedRemoved = true;
            }
        }
    });
});
</script>     
<?php wp_footer(); ?>
<?php // get_template_part('template-parts/inc-enclosure-finder') ?>
<?php // get_template_part('template-parts/inc-side-button') ?>
</body>
</html>