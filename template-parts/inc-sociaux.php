
    <?php if(get_field('facebook', 'option')): ?>
        <a href="<?= get_field('facebook', 'option'); ?>" target="_blank" rel="noopener" title="<?php _e('View our Facebook page', 'theme-client'); ?>"><i class="fab fa-fw fa-facebook-square" aria-hidden="true"></i></a>
    <?php endif; ?>

    <?php if(get_field('twitter', 'option')): ?>
        <a href="<?= get_field('twitter', 'option'); ?>" target="_blank" rel="noopener" title="<?php _e('View our Twitter page', 'theme-client'); ?>"><i class="fab fa-fw fa-twitter" aria-hidden="true"></i></a>
    <?php endif; ?>

    <?php if(get_field('linkedin', 'option')): ?>
        <a href="<?= get_field('linkedin', 'option'); ?>" target="_blank" rel="noopener" title="<?php _e('View our Linkedin page', 'theme-client'); ?>"><i class="fab fa-fw fa-linkedin" aria-hidden="true"></i></a>
    <?php endif; ?>

    <?php if(get_field('instagram', 'option')): ?>
        <a href="<?= get_field('instagram', 'option'); ?>" target="_blank" rel="noopener" title="<?php _e('View our Instagram page', 'theme-client'); ?>"><i class="fab fa-fw fa-instagram" aria-hidden="true"></i></a>
    <?php endif; ?>

    <?php if(get_field('youtube', 'option')): ?>
        <a href="<?= get_field('youtube', 'option'); ?>" target="_blank" rel="noopener" title="<?php _e('View our Youtube page', 'theme-client'); ?>"><i class="fab fa-fw fa-youtube" aria-hidden="true"></i></a>
    <?php endif; ?>

    <?php if(get_field('snapchat', 'option')): ?>
        <a href="<?= get_field('snapchat', 'option'); ?>" target="_blank" rel="noopener" title="<?php _e('View our Snapchat page', 'theme-client'); ?>"><i class="fab fa-fw fa-snapchat-ghost" aria-hidden="true"></i></a>
    <?php endif; ?>
