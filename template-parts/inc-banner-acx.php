<!--<script> 
    jQuery(document).ready(function()
    {
        jQuery(".checkCat").click(function()            
        {
            if (jQuery(this).is(":checked", true))
            {
                jQuery('input.checkCat').not(this).prop('checked', false);  
                var checkCat = jQuery(this).attr("id"); 
                var siteURL = '<? //= get_site_url(); ?>';
                
                if (window.location.href.indexOf("fr") > -1) 
                {
                    jQuery('#formCat').attr('action', siteURL+"/fr/product-category/products/"+checkCat);        
                }
                else
                {
                    jQuery('#formCat').attr('action', siteURL+"/product-category/products/"+checkCat);        
                }
            }
        });  
    }); 
</script>-->

<?php
	$show_banner = true;
	$title = get_the_title();
	$imgID = get_field('banner_image','option');
	$cat_singular_name = '';
	if(get_field('show_banner') !== null ){
		$show_banner = get_field('show_banner');
	}
	if(get_field('title') !== null ){
		if(get_field('title') !== '' ){
			$title = get_field('title');
		}
	}else{
		if( is_tax( 'product_cat' ) ) {
			$terms_list = exm_get_product_cat_terms_list();
			$cat_singular_name = strtolower(get_field('singular_name',end($terms_list)));
			 $title = end($terms_list)->name;
		}
	}
	if(get_field('banner_background') !== null ){
		$imgID = get_field('banner_background');
	}
if($show_banner) : ?>
    <?php $taxcolor = get_term_meta( get_queried_object_id(), 'category_color', true); ?>
	<section style="background-color:<?php echo $taxcolor; ?>;" class="banner-top">
		<?php if ($imgID ) :?>
			<figure>
				<?php wp_get_attachment_image($imgID,'banner_top') ?>
			</figure>
		<?php endif; ?>
		<div class="content-banner content">
			<div class="left">
				<?php if(get_post_type() === 'product') : ?>
					<?php if(get_field('is_new_product') && is_product()) : ?>
						<span class="label-new"><?php _e('New','theme-client') ?></span>
					<?php endif; ?>	
				<?php endif; ?>	
				<h1><?php echo $title ?></h1>
				
			</div>
			<div class="right">
			</div>
		</div>
	</section>
	<section class=" wrap-extra">
		<div class="banner-extra content">
			<div class="left">
				<?php displayBreadcrumb($separator = '<i class="fal fa-angle-right"></i>') ?>		
			</div>
            
            <div class="right">
				<?php /*if( is_tax( 'product_cat' )) : ?>
					<div class="search_box">
						<h4><?php echo __('Find the right','theme-client').' '.$cat_singular_name  ?></h4>
						<?php if(end($terms_list)->slug == 'enclosures' || end($terms_list)->slug == 'boitiers-standards') : ?>
				            <div class="finder-tip">
								<span>
									<?php _e('You are not familiar with our products?','theme-client') ?> 
									<a class="search-trigger" href="" ><?php _e('Open our enclosure finder','theme-client') ?></a>
								</span>
							</div>
				        <?php endif; ?>
				        <div class="form-wrapper">
				            <input type="text" name="s" id="search" value="<?php echo $s ?>" placeholder="<?php _e('Search product name, dimension, catalog number etc.','theme-client') ?>" ><button><i class="fal fa-search"></i></button>
				        </div>
				    </div>
				<?php endif; */?>
                
                <?php
                    $link = get_term_link( get_field('finder_target','options'), 'product_cat' );
                    if($link):
                ?>
                <?php //if( is_page( 238775 ) || is_page( 238777 ) ) : ?>
            		<div class="search_box pc_custom">
						<div class="form-wrapper">
                            <form role="search" method="get" id="searchform" class="searchform acx_search_nf" action="<?php echo $link; ?>" >
                                <div style="display:flex; width:100%;">
                                    <input type="text" value="<?php get_search_query() ?>" name="s" id="search" placeholder="<?php _e('Search product name, dimension, catalog number etc.','theme-client') ?>" />
                                    <button><i class="fal fa-search"></i></button>
                                </div>
                            </form>
						</div>
                        <div class="finder-tip">
							<span>
							    <?php _e('Not familiar with our products?','theme-client') ?> 
							    <a class="search-trigger" href="" ><?php _e('Try the enclosure finder','theme-client') ?></a>
							</span>
			            </div>
                    </div>
				<?php endif; ?>
                <?php //endif; ?>
                
                <?php /* if( is_tax( 'product_cat' ) ) : ?>
            		<div class="search_box pc_custom">
						<div class="form-wrapper">
							<input type="text" name="s" id="search" value="<?php echo $s ?>" placeholder="<?php _e('Search product name, dimension, catalog number etc.','theme-client') ?>" ><button><i class="fal fa-search"></i></button>
						</div>
                        <div class="finder-tip">
							<span>
							    <?php _e('Not familiar with our products?','theme-client') ?> 
							    <a class="search-trigger" href="" ><?php _e('Try the enclosure finder','theme-client') ?></a>
							</span>
			            </div>
                    </div>
				<?php endif; */ ?>
                
				<?php if(get_field('add_contact_form_to_page','options')) : ?>
					<?php if(get_field('add_contact_form_to_page','options')[0] === get_the_ID()) : ?>
						<div class="search_box">
				            <?php if(ICL_LANGUAGE_CODE == 'fr'): ?>
								<?php echo do_shortcode('[contact-form-7 id="3550" title="Nous joindre"]') ?>
				            <?php elseif(ICL_LANGUAGE_CODE == 'en'): ?>
								<?php echo do_shortcode('[contact-form-7 id="346" title="contact-us"]') ?>
				            <?php endif; ?>
				        </div>
				    <?php endif; ?>
            	<?php endif; ?>
                
				<?php if(get_field('add_career_form_to_page','options')) : ?>
					<?php if(get_field('add_career_form_to_page','options')[0] === get_the_ID()) : ?>
						<div class="search_box">
				            <?php if(ICL_LANGUAGE_CODE == 'fr'): ?>
								<?php echo do_shortcode('[contact-form-7 id="3558" title="Carrière"]') ?>
							<?php elseif(ICL_LANGUAGE_CODE == 'en'): ?>
								<?php echo do_shortcode('[contact-form-7 id="905" title="Career"]') ?>
							<?php endif; ?>
				        </div>
				    <?php endif; ?>
				<?php endif; ?>
            </div>
		</div>
	</section>
	<?php if(is_single()): ?>
	<hr class="p-0 m-0 has-color-2-background-color has-background">
	<?php endif; ?>

<?php endif; ?>

