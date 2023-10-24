<?php
/**
 * Slider Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'slider-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'slider';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
?>
<section class="image-slider">
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
        <?php while ( have_rows('slider') ) : the_row(); ?>
            <div class="slide">
                <figure>
                    <?php echo wp_get_attachment_image( get_sub_field( 'image' ), 'original'); ?>
                </figure>

                <?php if(get_sub_field('titre')): ?>
                    <div class="content">
                        <h1><?php the_sub_field('titre'); ?></h1>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</section>