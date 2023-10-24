
/**
 * Init tiny slider pour les related products
 */
(function($){
    $(document).ready(function(){
        const slider = tns({
            container:".related.products .products.columns-1",
            items:1,
            nav:false,
            touch:true,
            controlsText:['<i class="fal fa-chevron-left"></i>','<i class="fal fa-chevron-right"></i>'],
            responsive:{
                680:{
                    items:2
                },
                768:{
                    items:3
                },
                1024:{
                    items:4
                },
            }
        })
    })
})(jQuery)