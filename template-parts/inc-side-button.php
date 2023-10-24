
<?php if(get_field('side_btn_active') || is_woocommerce() || is_cart() ) : ?>
    <?php if(get_field('side_btn_type') == 'finder' || is_woocommerce() || is_cart() ) : ?>
        <button class="side-button finder search-trigger <?php echo ICL_LANGUAGE_CODE ?> " ><?php _e('Enclosure finder','theme-client')?> <i class="fal fa-search"></i></button>
    <?php elseif(get_field('side_btn_type') == 'boxcad') : ?>
        <a rel="noopener" target="_blank" href="https://www.boxcadexm.com/" class="side-button boxcad" ><?php _e('Configure on BOXCAD','theme-client')?> <i class="far fa-external-link-alt"></i></a>
    <?php endif; ?>
<?php endif; ?>