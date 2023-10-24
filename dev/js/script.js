// validation
function validateEmail(email) { 
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; // TODO: ne pas accepter un IP 
	return re.test(email);
}

(function($){

	$(document).ready(init_script)

	$.fn.select2.amd.define('select2/data/customAdapter',
		['select2/data/array', 'select2/utils'],
		function (ArrayAdapter, Utils) {
			function CustomDataAdapter ($element, options) {
				CustomDataAdapter.__super__.constructor.call(this, $element, options);
			}
			Utils.Extend(CustomDataAdapter, ArrayAdapter);
			CustomDataAdapter.prototype.updateOptions = function (data) {
				this.$element.find('option').remove(); // remove all options
				this.addOptions(this.convertToOptions(data));
			}        
			return CustomDataAdapter;
		}
	);

	function init_script(){
		var rellax = new Rellax('.rellax');
		$('a[href="#"]').on('click',e=>e.preventDefault()); // General setup
		init_selectwoo_custom();
		// init_custom_addon_woocommerce();
		init_sidebar_filters_woocommerce();
		watch_and_mimic_product_stock_variation();
		init_save_for_later();
		init_subscribe_to_newsletter();
		single_product_js();
		init_currencySelector();
		init_vimeo_playvideo()
		
	}

	function init_vimeo_playvideo(){
		$('iframe').each(function(){
			var iframe = $(this)[0];
			var player = new Vimeo.Player(iframe);
			$(document).on('scroll',function(){
				if(isInViewport(iframe)){
					playvideo(iframe)
				}
			})
		})
	}
	function playvideo(iframe){
		if(!$(iframe).hasClass('hasPlayed')){
			var player = new Vimeo.Player(iframe);
			player.setMuted(true)
			player.play();
			$(iframe).addClass('hasPlayed')
		}
		
	}


	function isInViewport(el) {
		const rect = el.getBoundingClientRect();
		return (
			rect.top >= 0 &&
			rect.left >= 0 &&
			rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
			rect.right <= (window.innerWidth || document.documentElement.clientWidth)
	
		);
	}
	/**
	 * All events and actions concerning the filter sidebar on product list page
	 * AB
	 */
	function init_sidebar_filters_woocommerce(){
		//gère les section developables
		$('.woocommerce-widget-layered-nav h4,#secondary .widget ul li.is_parent_term > a, .woocommerce aside h3').on('click',function(e) {
			e.preventDefault();
			$(this).next().toggleClass('open');
			$(this).toggleClass('open');
		});
		//click on child parent
		$('.woocommerce-widget-layered-nav-list__item a').on('click',function(){
			$(this).parent().parent().addClass('open');
			$(this).parent().parent().parent().find('.widget-title,a').addClass('open');
		});
		// Permet de cliquer sur les liens de doc supplémentaire dans les titre de sections
		$('.woocommerce-widget-layered-nav h4 a').on('click',function(e){
			e.stopPropagation();
		});

		

		$('#search + button').on('click',function(e){
			ajax_filter_products();
		});
		
		$('#search').on('keyup',function(e){
			if(e.keyCode == '13') {
				ajax_filter_products();
			}
		});

		if($('#secondary').length){
			$('#secondary .widget ul li:not(.is_parent_term) > a').click(function(e){
				e.preventDefault();
	
				var parent = $(this).parent();
	
				if(parent.hasClass('chosen')){
					parent.removeClass('chosen');
	
				}else{
					parent.addClass('chosen');
				}
	
				ajax_filter_products();
			});
	
			$('#secondary .reset-filters').click(function(e){
				e.preventDefault();
				$('#search').val('')
				$(this).parent().parent().find('.chosen').removeClass('chosen');
				ajax_filter_products();
			});
		}

		if($('.loadmore').length){
			$('.loadmore').click(function(e){
				e.preventDefault();
				ajax_filter_products(true);
			});
		}

		$('body').on('click','span [data-close]',removeTag);


		/**
		 * auto click from gat param sanitized in php
		 */
		$('.woocommerce-widget-layered-nav-list__item a').each(function(){
			Object.keys(theme_client_data.search_post).forEach(str => {
				if($(this).attr('href').includes('='+str)){
					$(this).trigger('click');
				}
			})
		})

		if(theme_client_data.search_post.name){
			addTags(theme_client_data.search_post.name,'name')
		}

		refillSelectFromDimensions();
		$('#dimensionfilter').select2()
		$('#dimensionfilter').on("change",function(){
			$('#dimensionfilter').parent().parent().find('ul li.wc-layered-nav-term a[href="'+$('#dimensionfilter').val()+'"]').click();
		})
		$('.dimension-filter-select [data-size]').on('click',function(){
			$('.dimension-filter-select [data-size]').removeClass('selected')
			$(this).addClass('selected');
			var size = $(this).data('size');

			switch_size_dimension_select(size);
		});

	}//init_sidebar_filters_woocommerce

	var dimensionfilterSize = 'in';
	function switch_size_dimension_select(size){
		var conversions= {
			in:{
				mm:{ rate:25.4, modifier:(x)=> x.toFixed(0)}
			},
			mm:{
				in:{ rate:0.0393701, modifier:(x)=> x.toFixed(0)}
			}
		};
		if(dimensionfilterSize == size){ return; }
		if(!in_array(size,Object.keys(conversions))){ return; }
		
		$('#dimensionfilter option').each(function(i,el){
			if($(el).val() != ''){
				let size_string = el.innerHTML;
				let size_array = size_string.split('x');
				for(let i = 0 ; i < size_array.length ; i++){
					size_array[i] = parseFloat(size_array[i]) * conversions[dimensionfilterSize][size].rate;
					if(conversions[dimensionfilterSize][size].modifier){
						size_array[i] = conversions[dimensionfilterSize][size].modifier(size_array[i]);
					}
					size_array[i] = isNaN(size_array[i])?'':size_array[i];
				}
				size_string = size_array[0]+' x '+size_array[1]+' x '+size_array[2];
				el.innerHTML = size_string;
			}
		});      
		dimensionfilterSize = size;
		$('#dimensionfilter').select2()
	}
 
	function refillSelectFromDimensions(){
		$datalist = [{value:"",label:theme_client_data.traductions.select_size}];
		$('#dimensionfilter').parent().parent().find('ul.woocommerce-widget-layered-nav-list').addClass('hide')
		$('#dimensionfilter').parent().parent().find('ul li.wc-layered-nav-term').each(function(id,el){
			var link = $(el).find('a');
			$datalist.push({
				value:link.attr('href'),
				label:link.html()
			})
		})
		$('#dimensionfilter').find('option').each(function(i,el){
			$(el).remove();
		});
		$datalist.forEach(function(x){
			$('#dimensionfilter').append($(`<option value="${x.value}" >${x.label}</option>`))
		})
	}

	function removeTag(){
		var type = $(this).parent().data('type');
		if(type === 'name'){
			$('#search').val('')
		}else{
			var slug = $(this).attr('data-close');
			$('li.chosen > a[data-slug="'+slug+'"]').parent().removeClass('chosen');
		}
		ajax_filter_products();
		
		
	}
	
	function addTags(string,type = 'attribute'){
		$('.filters-Tag').append($(`<span data-type="${type}" > ${string.replace('-fr','').replace('-2','')} <i  data-close="${string}" class="fal fa-times"></i> </span>`));
	}

	// /**
	//  * Gère les event pour le changement de prix des produits variables
	//  * AB
	//  */
	// function init_custom_addon_woocommerce(){
	// 	/**
	// 	 * Quand le form vérifie sa variation, on injecte les addon dans un data-attr, ils seront envoyés dans la requête Ajax
	// 	 * AB
	// 	 */
	// 	$('.variations_form').on('check_variations',function(){
	// 		let custom_data={};
	// 		$('[data-addon_name]:checked').each(function(){
	// 			custom_data[$(this).data('addon_name')] = $(this).val();
	// 		})
	// 		$(this).data('custom_data',custom_data)
	// 	})
	// 	/**
	// 	 * Si un addon est toggle, force le form a vérifier son prix
	// 	 * AB
	// 	 */
	// 	$('[data-addon_name]').on('change',function(){
	// 		$('.variations_form').trigger('check_variations');
	// 	})

	// 	$('[data-addon-name] input').on('change',function(){
	// 		let apply = $(this).parent().find('input:checked').val();
	// 		let addon_price = $(this).parent().data('addon-price');
	// 		let currency_selector = $('form.cart .woocommerce-Price-amount bdi span').remove()
	// 		let price = +($('form.cart .woocommerce-Price-amount bdi').text())
	// 		let el = $('<bdi></bdi>');
	// 		if(apply == 'true'){
	// 			el.text((price + addon_price).toFixed(2));
	// 		}else{
	// 			el.text((price - addon_price).toFixed(2));
	// 		}
			
	// 		el.prepend(currency_selector)
	// 		$('form.cart .woocommerce-Price-amount bdi').replaceWith(el)
	// 	})
	// }//init_custom_addon_woocommerce

	/**
	 * Watch the product stock on a variable product to show it elsewhere
	 * was neccessary because the price change via ajax in a script that can't be overriden (woocommerce)
	 * AB
	 */
	function watch_and_mimic_product_stock_variation(){
		if($('body.single-product').length == 0){
			if($('.variations_form').length > 0){
				$('.variations_form').on('change','input, select',function(){
					if($(this).val() === ''){
						$('.flex-wrap-gallery div.stock').hide()
					}
				})
			}
			if($('.woocommerce-variation.single_variation').find('div.stock').length >0){
				if($('.woocommerce-variation.single_variation').find('div.stock').css('display') == 'none'){
					$('.flex-wrap-gallery div.stock').hide()
				}
			}else{
				$('.flex-wrap-gallery div.stock').hide()
			}
		}
		$('.woocommerce-variation.single_variation div.stock').on('DOMSubtreeModified',function(){
			let stock_el = $(this);
			if(stock_el.length > 0){
				$('.flex-wrap-gallery div.stock').replaceWith(stock_el.clone())
			}
		
		})

		$('.woocommerce-variation.single_variation').on('DOMSubtreeModified',function(){

			let stock_el = $(this).find('div.stock');
			if(stock_el.length > 0){
				$('.flex-wrap-gallery div.stock').replaceWith(stock_el.clone())
			}
			
			if(!$(this).css('display') == 'none'  ){
				$('.flex-wrap-gallery div.stock').hide()
			}
		})
	}//watch_and_mimic_product_stock_variation

	function init_save_for_later(){
		/**
		 * Ajax pour ajouter des produits dans les favoris
		 */
		$(document).on('click', '.add_to_fav', function(e){
			e.preventDefault();
			
			if($('body.logged-in').length > 0){
				var $bouton = $(this);
				var id = $(this).attr('href');
				var post_id = id.replace(/\D/g, '');

				var data = {
					'action': 'add_fav',
					'ID': post_id,
					'valid_fav': 'ajax-fav', // ajoute une validation
				};
				
				$.post(theme_client_data.ajax_url, data, function(res){
					if(res){
						$bouton.toggleClass('added');
					}
				});	
			}else{
				displayQuickMessage( phpData.translate.need_connect );
			}
		});
	}//init_save_for_later

	function init_subscribe_to_newsletter(){
		$('.infolettre, .newsletter-form').submit(function(e){
			e.preventDefault();
			var messageLog = $('.form-error-log');
	
			if(!validateEmail($(this).find('input[name="email"]').val())){
				messageLog.text(theme_client_data.traductions.email_invalid).removeClass('success processing').addClass('error');
				
			}else{
				// update user interface
				messageLog.html(theme_client_data.traductions.email_processing).removeClass('success error').addClass('processing');
				
				var data = {
					ajax: true,
					email: $(this).find('input[name="email"]').val(),
					action: 'newsletter_subscription'
				}
				
				// Prepare query string and send AJAX request
				$.post( theme_client_data.ajax_url, data, function(res){
					JSON.stringify(res);
	
					if(res.success){				
						messageLog.removeClass('error processing').addClass('success').html(res.message);
						
					}else{
						messageLog.removeClass('success processing').addClass('error').html(res.message);
					}
				}, 'json');
				
				return false;
			}
		});
	}//init_subscribe_to_newsletter

	function init_selectwoo_custom(){
		$('[data-s2]').each(function(){ $(this).selectWoo()});

		// selectwoo custom template adapter https://github.com/woocommerce/selectWoo/issues/39
		// corrige un bug pour l'attribut que est selected
		$.fn.selectWoo.amd.define('customSingleSelectionAdapter', [
			'select2/utils',
			'select2/selection/single',
		], function (Utils, SingleSelection) {
			const adapter = SingleSelection;
			adapter.prototype.update = function (data) {
			if (data.length === 0) {
				this.clear();
				return;
			}
			var selection = data[0];
			var $rendered = this.$selection.find('.select2-selection__rendered');
			var formatted = this.display(selection, $rendered);
			$rendered.empty().append(formatted);
			$rendered.prop('title', selection.title || selection.text);
			};
			return adapter;
		});

		// Tout les select2 avec la class en question vont render la couleur dans data-color
		$('.with-color').selectWoo({
			width: "100%",
			minimumResultsForSearch: -1,
			templateSelection: formatColor,
			templateResult: formatColor,
			selectionAdapter: $.fn.selectWoo.amd.require('customSingleSelectionAdapter'),
			allowHtml: true
		})
		function formatColor(state) {
			const color = $(state.element).attr('data-color');
			if(color){
				return $('<span><i style="font-size:1.5em;color:'+color+'" class="fa fa-square"></i> '+state.text+'</span>');
			}else{
				return state.text;
			}
		}
	}//init_selectwoo_custom

	function single_product_js(){
		$('body').on('click','.technical-spec h3',function(){
			$('.technical-spec h3,.technical-spec ul').toggleClass('open');
		})
		$('body').on('click','.toggle_address',function(){
			$(this).parent().toggleClass('open');
		});
		$('body').on('click','.close-stock',function(){
			$('.pickup_stock ul').hide();
		})
		$('body').on('click','.check_availability',function(){
			$('.pickup_stock ul').toggle();
		})
		
	}//single_product_js

	function init_currencySelector(){
		$('.currecy-selector').on('click',function(e){
			e.preventDefault();
			var country = $(this).data('currency');
			$('#wcpbc-widget-country-switcher-form input[name="wcpbc-manual-country"]').val(country);
			$('#wcpbc-widget-country-switcher-form').submit();
		});
	}//init_currencySelector()

	var latestRequest = null;
	function ajax_filter_products(append){
		append = append == undefined ? false : append;
		
		if($('.chosen').length > 0){
			$('.reset-filters').show();
		}else{
			$('.reset-filters').hide();
		}

		var data = {
			ajax: true,
			action: 'theme_client_ajax_woo_cat_filter'
		}

		if(append){
			data['currentCount'] = $('.shop-products-wrapper .products > .product').length;
			// $('.show-more-products').addClass('loading-more');
			$('.shop-products-wrapper').addClass('loading');
		}else{
			$('.shop-products-wrapper').addClass('loading');
		}

		if($('.widget_product_categories').length && $('.widget_product_categories').find('.chosen').length){
			var catIDs = '';

			$('.widget_product_categories').find('.chosen').each(function(elem){
				this.classList.forEach(function(className){
					if(className.indexOf('cat-item-') !== -1){
						catIDs += className.replace('cat-item-', '')+',';

						return true;
					}
				});
			});

			data.catIDs = catIDs;
		}

		if($('.woocommerce-widget-layered-nav').length){
			var attributes = [];
			var url_filters= {};
			var url_name="";
			var name = '';
			$('.filters-Tag').empty()

			jQuery('.woocommerce-widget-layered-nav ul li').each(function() {
				var text = jQuery(this).find('a').attr('data-slug');
				if(text){
					jQuery(this).addClass('chosen');
				}
			});

			$('.woocommerce-widget-layered-nav').each(function(){
				if($(this).find('.chosen').length){
					var generalVars = new URL($(this).find('.chosen > a').attr('href'));
					
					var attributeName = '';

					for(var key of generalVars.searchParams.keys()) { 
						if(key.indexOf('filter_') !== -1){
							attributeName = key.replace('filter_', '');
							break;
						}
					}

					var termSlugs = '';
					var queryType = generalVars.searchParams.get('query_type_'+attributeName);

					
					
					$(this).find('.chosen > a').each(function(elem){
						var termURL = new URL($(this).attr('href'));
						var slug =  termURL.searchParams.get('filter_'+attributeName);
						termSlugs += slug+',';
						$(this).attr('data-slug',slug);
						addTags(slug);
						url_filters["filtre_"+slug] = 'on';
					});

					var parts = window.location.pathname.split("/");
					var pagenumber = parts[parts.length-2];
					if(jQuery.isNumeric(pagenumber)) { 
						pagenumber = pagenumber;
					} else { 
						pagenumber = 1;
					}
		

					attributes.push({
						name: attributeName,
						queryType: queryType,
						termSlugs: termSlugs,
						pagenumber:pagenumber
					});
					
				}
			});
			name = $('#search').val();
			if(name !== ''){
				addTags(name,'name')
				url_filters["filtre_name"] = name;

			}
			if($('input[name="limit_product_cat"]').length > 0 ){
				let found = false;
				attributes.forEach(x=>{
					
					if(x.name == 'product_cat'){
						x.termSlugs += $('input[name="limit_product_cat"]').val()+',';
						found=true;
					}
				});
			
				if(!found){
					attributes.push({
						name:'product_cat',
						queryType:undefined,
						termSlugs:$('input[name="limit_product_cat"]').val()
					})
				}
				
			}


			window.history.replaceState(null,null,'?'+Object.keys(url_filters).map(param => encodeURIComponent(param) + '=' + encodeURIComponent(url_filters[param])).join('&'));
			
			data.name = name;
			data.attributes = attributes;
		}

		latestRequest = $.post( theme_client_data.ajax_url, data, function(res, status, jqXHR){
			console.log("filterdata = ",res);
			console.log("filterSlug = ",res.post.attributes[0].termSlugs);

			JSON.stringify(res);
			if(latestRequest === jqXHR){
				
				if(!append){
					
					$('.shop-products-wrapper .products').empty();
				}

				if((res.count + $('.shop-products-wrapper .products > .product').length) >= res.total){
					$('..woocommerce-pagination').hide();

				}else{
					$('..woocommerce-pagination').show();
				}
				
				$('.shop-products-wrapper .products').append(res.html).removeClass('loading');
				$('.shop-products-wrapper').removeClass('loading');
				$('.show-more-products').removeClass('loading-more');
			}
		}, 'json');
	} // ajax_filter_products

})(jQuery);
jQuery( document ).ajaxStop(function() {
	let loc = window.location;
    
    let pages = jQuery('.products .page-numbers li').length;
    jQuery('.products .page-numbers li').each(function(){
        let page =  jQuery(this).index() + 1;
        if (window.location.href.indexOf("page/") > -1) {
            pathname = loc.pathname.substring(0, loc.pathname.indexOf('page'));
        }else{
            pathname = loc.pathname;
        }
        // console.log(pathname);
        if(pages == page){
            page = page-1;
            if (window.location.href.indexOf("page/"+page) > -1) {
				jQuery(this).find('span').remove();
                jQuery(this).find('a').remove();
                jQuery(this).append('<span aria-current="page" class="page-numbers current">→</span>');
            }else{
                jQuery(this).find('a').remove();
				jQuery(this).find('span').remove();
                jQuery(this).append('<span aria-current="page" class="page-numbers current">→</span>');
            }
        }else{
            if(window.location.href.indexOf("page/"+page) > -1) {
				jQuery(this).find('span').remove();
                jQuery(this).find('a').remove();
                jQuery(this).append('<span aria-current="page" class="page-numbers current">'+page+'</span>');
            }else{
                jQuery(this).find('span').remove();
                jQuery(this).find('a').remove();
                jQuery(this).append('<a class="page-numbers" href="'+loc.origin+''+pathname+'page/'+page+'/'+loc.search+'&paged='+page+'">'+page+'</a>');
            }
        }
    })
  });

jQuery('.woocommerce-widget-layered-nav').each(function(){
	if(jQuery(this).find('a').length){
		console.log(jQuery(this).attr('data-slug'));
	}
});
