<?php
/**
 * Exécute les réglages de bases pour ACF (Google map api, Acf blocks).
 * 
 * @hooked acf/init
 */
function theme_client_acf_init() {
    if(defined('GOOGLE_MAP_API') && GOOGLE_MAP_API != ''){
        acf_update_setting('google_api_key', GOOGLE_MAP_API);
    }

    theme_client_acf_register_blocks();
}
add_action('acf/init', 'theme_client_acf_init');

/**
 * Ajoute des blocks Gutenberg avec ACF.
 */
function theme_client_acf_register_blocks(){
    if( function_exists('acf_register_block_type') ) {

		acf_register_block_type(array(
            'name'              => 'header-spacer',
            'title'             => __('Header spacer', 'theme_client'),
            'description'       => __('A spacer that follows the header\'s height', 'theme_client'),
            'render_template'   => get_template_directory().'/template-parts/blocks/header-spacer.php',
            'category'          => 'layout',
            'icon'              => 'image-flip-vertical',
            'keywords'          => array( 'spacer', 'header' ),
            'mode'              => 'edit',
        ));

        acf_register_block_type(array(
            'name'              => 'slider',
            'title'             => __('Slider', 'theme_client'),
            'description'       => __('A images slider with a optional text overlay.', 'theme_client'),
            'render_template'   => get_template_directory().'/template-parts/blocks/slider.php',
            'enqueue_style'     => get_template_directory_uri().'/css/blocks/slider.min.css',
            'enqueue_assets'    => function(){
                wp_enqueue_style( 'tiny-slider-css', 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css', array(), '2.9.3' );
                wp_enqueue_script( 'tiny-slider-js', 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js', array(), '2.9.2', true );
                wp_enqueue_script( 'slider-js', get_template_directory_uri().'/js/blocks/slider.min.js', array('tiny-slider-js', 'jquery'), wp_get_theme()->get('Version'), true );
            },
            'category'          => 'media',
            'icon'              => 'images-alt2',
            'keywords'          => array( 'slider', 'image', 'banner' ),
            'mode'              => 'edit',
        ));

        if(defined('GOOGLE_MAP_API') && GOOGLE_MAP_API != ''){
            acf_register_block_type(array(
                'name'              => 'googlemap',
                'title'             => __('Google Map', 'theme_client'),
                'description'       => __('A interactive Google Map.', 'theme_client'),
                'render_template'   => get_template_directory().'/template-parts/blocks/googlemap.php',
                'enqueue_style'     => get_template_directory_uri().'/css/blocks/googlemap.min.css',
                'enqueue_assets'    => function(){
                    wp_register_script("google-map-api", "//maps.googleapis.com/maps/api/js?key=". GOOGLE_MAP_API ."&v=3.exp&libraries=places", array(), '3.0.0', true);
                    wp_enqueue_script( 'googlemap-js', get_template_directory_uri().'/js/blocks/googlemap.min.js', array('google-map-api', 'jquery'), wp_get_theme()->get('Version'), true );

                    wp_localize_script('googlemap-js', 'mapData', [
                        'siteName' => esc_attr( get_bloginfo( 'name', 'display' ) ),
                        'itineraire' => __('Directions to this place', 'theme_client'),
                        'themeColors' => get_theme_support('editor-color-palette')[0]
                    ]);
                },
                'category'          => 'media',
                'icon'              => 'location-alt',
                'keywords'          => array( 'map', 'google', 'interactive', 'location', 'address' ),
                'mode'              => 'edit',
                'example'  => array(
                    'attributes' => array(
                        'mode' => 'preview',
                        'data' => array(
                            'map' => get_field('adresse', 'option')
                        )
                    )
                )
            ));
        }

        if(class_exists('WPCF7')){
            add_filter( 'wpcf7_load_js', '__return_false' );
            add_filter( 'wpcf7_load_css', '__return_false' );

            acf_register_block_type(array(
                'name'              => 'contactform',
                'title'             => __('Contact form', 'theme_client'),
                'description'       => __('Basic contact form for your customer to get in touch with you.', 'theme_client'),
                'render_template'   => get_template_directory().'/template-parts/blocks/contactform.php',
                'enqueue_style'     => get_template_directory_uri().'/css/blocks/contactform.min.css',
                'enqueue_assets'    => function(){
                    if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
                        wpcf7_enqueue_scripts();
                    }
                    
                    if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
                        wpcf7_enqueue_styles();
                    }
                },
                'category'          => 'widgets',
                'icon'              => 'email-alt2',
                'keywords'          => array( 'contact', 'form', 'formulaire', 'email' ),
                'mode'              => 'preview',
                'supports'          => [
                    'mode' => false
                ],
                'example'  => array(
                    'attributes' => array(
                        'mode' => 'preview',
                        'data' => array()
                    )
                )
            ));
        }
    }
}

/**
 * Function ajax pour récupérer l'adresse de la page option.
 */
function theme_client_get_option_address(){
    echo json_encode(get_field('adresse', 'option'));
    die();
}
add_action( 'wp_ajax_theme_client_get_option_address', 'theme_client_get_option_address');