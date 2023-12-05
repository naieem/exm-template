

jQuery(document).ready(function(){
    build_shop_by_enclosure()
});
function build_shop_by_enclosure() {
    const $ = jQuery;
    const container = $('.wp-block-exm-shop-by-enclosure');
        shopbyenclosure_data.enclosure_types.forEach(function(type){
            // container.append($(`
            //     <div>
            //         <a  href="${type.slug}" class="type-wrapper">
			// 			<div class="contain">
            //             <figure>
            //                 ${type.image}
            //             </figure>
            //             <span>${type.name}</span>
			// 			</div>
            //         </a>
            //     </div>
            // `))
        })

    $('body').on('click','.type-wrapper',function(e){
        e.preventDefault();
        const form = $(`<form action="${shopbyenclosure_data.link}" method="GET" ><input type="hidden" value="on" name="filtre_${$(this).attr('href')}" /></form`);
        $('body').append(form);
        form.submit();
    });

    const slider = tns({
        container:".wp-block-exm-shop-by-enclosure",
        items:1,
        gutter:30,
        edgePadding:0,
        mouseDrag: true,
        nav:false,
        autoplay:true,
        controls:true,
        autoplayButton:false,
        autoplayHoverPause:true,
        autoplayButtonOutput:false,
        touch:true,
        controlsText:['<i class="fal fa-chevron-left"></i>','<i class="fal fa-chevron-right"></i>'],
        responsive:{
			480:{
                items:2
            },
            680:{
                items:3
            },
            768:{
                items:3
            },
            1024:{
                items:4
            },
            1280:{
                items:4
            },
            1440:{
                items:5
            },
            1660:{
                items:5
            },
            1920:{
                items:5
            },
        }
    }) 

	if(slider){
		$(slider.getInfo().controlsContainer).addClass('shop-by-enclosure');
		$(slider.getInfo().controlsContainer).parent().addClass('shop-by-enclosure');
		$(slider.getInfo().controlsContainer).parent().addClass('content');
	}


    
}