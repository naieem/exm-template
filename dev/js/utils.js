/**
 * Polyfill CustomEvent pour IE.
 */
(function(){
    if ( typeof window.CustomEvent === "function" ) return false;
  
    function CustomEvent ( event, params ) {
      params = params || { bubbles: false, cancelable: false, detail: undefined };
      var evt = document.createEvent( 'CustomEvent' );
      evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
      return evt;
     }
  
    CustomEvent.prototype = window.Event.prototype;
  
    window.CustomEvent = CustomEvent;
})();

// validation
function validateEmail(email) { 
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; // TODO: ne pas accepter un IP 
	return re.test(email);
}

function isMobile(){
	if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4)) || window.innerWidth <= cdmConf.mobile_width){ return true;}else{return false;}
}



if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = function (callback, thisArg) {
        thisArg = thisArg || window;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    };
}
if (!String.prototype.includes) {
	String.prototype.includes = function(search, start) {
	  'use strict';
  
	  if (search instanceof RegExp) {
		throw TypeError('first argument must not be a RegExp');
	  }
	  if (start === undefined) { start = 0; }
	  return this.indexOf(search, start) !== -1;
	};
  }


(function($){
	/* function requise pour le parallax
	* détecte si un élément est afficher sur l'écran
	* Return bool
	*/
	$.fn.isOnScreen = function(){

		var win = $(window);

		var viewport = {
			top : win.scrollTop(),
			left : win.scrollLeft()
		};
		viewport.right = viewport.left + win.width();
		viewport.bottom = viewport.top + win.height();

		var bounds = this.offset();
		bounds.right = bounds.left + this.outerWidth();
		bounds.bottom = bounds.top + this.outerHeight();

		return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

	};

	/**
	 * Calcule les dimensions du viewBox d'un SVG avec les path visibles.
	 * @param {Node} svg 
	 */
	$.fn.calculateViewBox = function(svg){
		var viewboxWidth = 0;
		var viewboxHeight = 0;

		$(svg).find('path:visible').each(function(){
			var bbox = this.getBBox();
			var endX = bbox.x + bbox.width;
			var endY = bbox.y + bbox.height;

			if(endX > viewboxWidth){
				viewboxWidth = endX;
			}

			if(endY > viewboxHeight){
				viewboxHeight = endY;
			}
		});

		$(svg).attr('viewBox', '0 0 '+ viewboxWidth + ' ' + viewboxHeight);
	}

	/**
	 * Rajoute du parallax a un élément
	 * On doit déjà avoir un element dans le div qui est l'image
	 *
	 * @param section Le div qui va contenir le parallax
	 * @param {int} [vitesse=0] La vitesse de défilement. Plus le chiffre est grand, mois la vitesse est grande, 0 = automatique
	 * @param {int} [zIndex=-1] z-index de l'image
	 * @return void
	 */
	$.fn.parallax = function(section, vitesse, zIndex){
		image = this; // image to animation
		section = $(section); // section to animate the image

		if(section.length == 0){
			console.log("la section pour le parallax n'existe pas");
		}

		if(image.length == 0){
			return false;
		}
		
		vitesse = (typeof vitesse === 'undefined') ? 3 : vitesse; // réduction de la vitesse du parallax
		zIndex = (typeof zIndex === 'undefined') ? -1 : zIndex; // zIndex de -1 par default
		
		
		image.addClass('img-parallax');
		section.addClass('section-parallax');
		
		// ajoute une wrapper pour l'image a l'intérieur de la section pour la garder dans le flow
		$('<div class="parallax-img-wrapper"></div>').appendTo(section).css('z-index', zIndex).append(image);
		
		var parallaxObject = {
			vitesse: vitesse,
			image: image,
			section: section
		};
		
		parallaxAnimationFrame(parallaxObject);
	}

	function parallaxAnimationFrame(parallaxObject){
		win = $(window);

		parallaxObject.image.removeClass('inactif'); // active le parallax
		
		if(parallaxObject.section.isOnScreen() === true){
			var visiblePart = parseInt(parallaxObject.section.css('height')); // hauteur de la section = zone visible de l'image
			var maxScroll = parallaxObject.image.height() - visiblePart; // hauteur d'extra de l'image
			
			var sectionScroll = win.scrollTop() + win.height() - parallaxObject.section.offset().top; // sectionScroll est à 0 quand on commence a voir la section, est représente le nombre de pixels visibles
			
			centPourcent = parallaxObject.section.outerHeight() + win.height();
			//console.log(sectionScroll/centPourcent +"%");
			
			if(parallaxObject.vitesse == 0){
				// sectionScroll/centPourcent = mon pourcentage, entre 0 et 1
				parallaxObject.image.css('top', ((parallaxObject.section.outerHeight() - parallaxObject.image.height()) * (sectionScroll/centPourcent)) );
			}else{
				// si l'image est visible au top, on la part de 0
				if(parallaxObject.section.offset().top < win.height()){
					sectionScroll = win.scrollTop()/parallaxObject.vitesse;
				}else{
					sectionScroll = sectionScroll/parallaxObject.vitesse;
				}
				
				if(sectionScroll >= maxScroll){ // si on a atteint le scroll maximum on reste au max
					parallaxObject.image.css('top', maxScroll*-1);
				}else{
					parallaxObject.image.css('top', sectionScroll*-1);
				}
				
			}
			
		}

		window.requestAnimationFrame(function(){
			parallaxAnimationFrame(parallaxObject);
		});
	}

	/*************/
	/* ANIMATION */
	/*************/

	// add class .toAnimate to html element with a animate.css effect class
	// when on screen, the animation will execute
	const mutationConfig = {
		childList: true,
		subtree: true
	};
	var newAnimationObserver = new MutationObserver(domNewContent);
	newAnimationObserver.observe(document, mutationConfig);
	
	function domNewContent(mutations){
		mutations.forEach(function(mutation){
			if(mutation.type == 'childList'){
				let addedNodes = mutation.addedNodes;
				
				if(addedNodes.length){ // Nouveau contenu ajouté
					checkAnimations();
				}
			}
		}); 
	}

	$(window).on('scroll load', function(event) {
		checkAnimations();
	});

	function checkAnimations(){
		if(!$('html').hasClass('preload')){
			requestAnimationFrame(function() {
				$('.toAnimate').each(function(index, el) {
					if($(this).isOnScreen() === true){
						var elem_class = $(this).attr('class');
						var pos = elem_class.search('animate__');

						if(pos !== -1){
							var anim_name = elem_class.substring(pos, elem_class.indexOf(" ", pos) !== -1 ? elem_class.indexOf(" ", pos) : elem_class.length);
							
							$(this).removeClass(anim_name);

							setTimeout(function(){
								animateCSS(el, anim_name.replace('animate__', ''));
								$(el).removeClass('toAnimate');
							}, 100);
						}
						
						this.dispatchEvent(new CustomEvent('animated'));
					}
				});
			});
		}
	}


})(jQuery);

/**
 * Fonction pour déclencher une animation.
 * 
 * @param string|Element|HTMLDocument element Sélecteur sous forme de string ou un élément HTML.
 * @param string animation Le nom de l'animation.
 * @param string prefix Le prefix pour les animations (laissez valeur par défaut).
 */
const animateCSS = (element, animation, prefix = 'animate__') =>
	// We create a Promise and return it
	new Promise((resolve, reject) => {
		const animationName = `${prefix}${animation}`;

		if((element instanceof Element) === false && (element instanceof HTMLDocument) === false){
			element = document.querySelector(element);
		}

		element.classList.add(`${prefix}animated`, animationName);

		// When the animation ends, we clean the classes and resolve the Promise
		function handleAnimationEnd() {
			element.classList.remove(`${prefix}animated`, animationName);
			resolve('Animation ended');
		}

		element.addEventListener('animationend', handleAnimationEnd, {once: true});
	});