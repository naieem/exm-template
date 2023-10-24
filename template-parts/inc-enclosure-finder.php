<script> 
    jQuery(document).ready(function()
    {
        jQuery(".checkCat").click(function()            
        {
            if (jQuery(this).is(":checked", true))
            {
                jQuery('input.checkCat').not(this).prop('checked', false);  
                var checkCat = jQuery(this).attr("id"); 
                var siteURL = '<?= get_site_url(); ?>';
                
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
</script>

<?php 
    $link = get_term_link( get_field('finder_target','options'), 'product_cat' );
    if($link):
?>

<section id="EF" class="enclosure-finder" >
    <div class="popup">
        <span class="close-search"><?php _e("Close",'theme-client') ?> <i class="fal fa-times" ></i></span>
        <h3><?php _e('Enclosure finder','theme-client') ?></h3>
        <form class="popup-content" method="GET" id="formCat" action="<?php echo $link; ?>" >
            
            <div class="row">
                <div class="left">
                    <p class="finder-header">
                        <span class="title">
                        <?php _e('Dimensions','theme-client') ?> <span><?php _e('(height x width x depth)','theme-client') ?></span>
                        <span class="size-switch">
                            <span data-size="in" class="selected">in</span>
                            <span data-size="mm">mm</span>
                        </span>
                        </span>
                    </p>
                    
                    <div class="selections">
                        <label for="minSize">
                            <!-- <?php echo _e("min",'theme-client') ?> -->
                            <select multiple class="size-select" data-s2="true" name="dimensions_filtre[]" id="minSize">
                                <!-- <option value="" ><?php _e('Choose','theme-client') ?></option> -->
                                <?php foreach(get_terms('pa_dimension') as $dimension) : ?>
                                    <option value="<?php echo $dimension->slug ?>" ><?php echo str_replace('x',' x ',$dimension->name) ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>
                    
                </div>
                
                <!--<div class="boxcadUrl" <?php /* _e('boxcadUrl','theme-client') ?>>
                    <label for="minSize">Enter Boxcad Url</label>
                    <input type="text" class="boxUrl" <?php _e('boxUrl','theme-client') */ ?> style="Width:100%">
                </div>-->
    
                <div class="right">
                    <!--<p class="finder-header">
                        <span class="title"><?php /* _e('Select required degree of protection','theme-client') ?> 
                            <?php if(get_field('ratings_reference_sheet','options')) : ?>
                            <span>
                                <a rel="noopener" target="_blank" href="<?php echo get_field('ratings_reference_sheet','options') ?>"><?php _e('Open ratings reference sheet','theme-client') ?></a>
                            </span>
                            <?php endif; ?>
                        </span>
                    </p>
                    <ul class="inline_check">
                        
                        <?php foreach(get_terms('pa_nema') as $ip) : ?>
                            <li>
                                <input type="checkbox" name="filtre_<?php echo $ip->slug?>" id="<?php echo $ip->slug?>">
                                <label for="<?php echo $ip->slug?>"><span class="pretty_check"><i class="fal fa-times"></i></span> <?php echo $ip->name ?></label>
                            </li>
                        <?php endforeach; */ ?>
                        
                    </ul>-->
                </div>
            </div>
            <div class="row">
                <div class="left">
                    <p class="finder-header">
                        <span class="title"><?php _e('Select enclosure type','theme-client') ?></span>
                    </p>
                    <ul class="flex-items">
                        <?php 
                        $term = get_term_by('slug','products','product_cat'); 
                        foreach(get_term_children($term->term_id,'product_cat') as $et_ID) : 
                            $et = get_term($et_ID,'product_cat'); 
                            $ProductCount = $et->count;
                            if($ProductCount > 0): 
                                if($et): 
                                    ?>
                                    <li>
                                        <input type="checkbox" name="<?php echo $et->slug?>" id="<?php echo $et->slug?>" class="checkCat">
                                        <label for="<?php echo $et->slug?>"><span class="pretty_check">
                                            <i class="fal fa-times"></i></span> <?php echo $et->name ?>  
                                            <?php 
                                                $thumbnail_id = get_term_meta( $et->term_id, 'thumbnail_id', true );
                                                echo wp_get_attachment_image($thumbnail_id ); 
                                            ?>
                                        </label>
                                    </li>
                                    <?php 
                                endif; 
                            endif;     
                        endforeach; 
                        ?>
                        <?php 
                        $term = get_term_by('slug','accessories','product_cat'); 
                        $ProductCount = $term->count;
                        if($term && $ProductCount > 0):  
                            ?>
                            <li>
                                <input type="checkbox" name="<?php echo $term->slug?>" id="<?php echo $term->slug?>" class="checkCat">
                                <label for="<?php echo $term->slug?>"><span class="pretty_check">
                                    <i class="fal fa-times"></i></span> <?php echo $term->name ?>  
                                    <?php 
                                        $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                                        echo wp_get_attachment_image($thumbnail_id ); 
                                    ?>
                                </label>
                            </li>
                            <?php 
                        endif; 
                        ?>
                        <?php 
                        $term = get_term_by('slug','clearance','product_cat'); 
                        $ProductCount = $term->count;
                        if($term && $ProductCount > 0):    
                            ?>
                            <li>
                                <input type="checkbox" name="<?php echo $term->slug?>" id="<?php echo $term->slug?>" class="checkCat">
                                <label for="<?php echo $term->slug?>"><span class="pretty_check">
                                    <i class="fal fa-times"></i></span> <?php echo $term->name ?>  
                                    <?php 
                                        $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                                        echo wp_get_attachment_image($thumbnail_id ); 
                                    ?>
                                </label>
                            </li>
                            <?php 
                        endif; 
                        ?>
                    </ul>
                </div>
                <div class="right">
                    <p class="finder-header">
                        <span class="title"><?php _e('Select enclosure material','theme-client') ?></span>
                    </p>
                    <ul class="flex-items">
                        <?php foreach(get_terms('pa_material') as $material) : ?>
                            <?php if (get_field('show_in_finder',$material)): ?>
                                <li>
                                    <input type="checkbox" name="filtre_<?php echo $material->slug?>" id="mat_<?php echo $material->slug?>">
                                    <label for="mat_<?php echo $material->slug?>"><span class="pretty_check">
                                        <i class="fal fa-times"></i></span> <?php echo $material->name ?> 
                                        <?php if(get_field('label_color',$material)) : ?>
                                            <span style="background-color:<?php echo get_field('label_color',$material)?>;" class="color"></span>
                                        <?php endif; ?>
                                    </label>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </form>
        <div class="finder-footer">
            <button><?php _e('Find enclosure','theme-client') ?></button><br>
            <p class="reset-finder"><?php _e('Reset criterias','theme-client') ?></p>
        </div>
    </div>
</section>
<?php endif; ?>