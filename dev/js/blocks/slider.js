(function($){

    /**
     * initializeBlock
     *
     * Adds custom JavaScript to the block HTML.
     *
     * @date    15/4/19
     * @since   1.0.0
     *
     * @param   object $block The block jQuery element.
     * @param   object attributes The block attributes (only available when editing).
     * @return  void
     */
    var initializeBlock = function( $block ) {
        tns({
            container: $block.find('.slider')[0],
            items: 1,
            autoplay: true,
            autoplayButtonOutput: false,
            nav: false,
            controlsText: ['<i class="far fa-chevron-left"></i>', '<i class="far fa-chevron-right"></i>']
        });
    }

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        $('.image-slider').each(function(){
            initializeBlock( $(this) );
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=slider', initializeBlock );
    }

})(jQuery);