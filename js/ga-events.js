/*****************************************************************************************************
 * 
 * Script Name: GA-Events
 * Descrition: This script is used to track multiple events on the website https://exmweb.com
 * Author: David Berard (david.berard@exmweb.com)
 * Date of creation: Feb. 2nd, 2023
 * 
*****************************************************************************************************/

jQuery(function() {

    console.log('GA EVENTS LOADED');

    /* Clicks on Mainmenu links */
    
    /* ENGLISH */
    
    /* Products */
    jQuery('#primary-menu .mainmenu-products-en a').on('click', function(){
        ga('send', 'event', 'Primary Menu EN', 'Click', 'Products');
    });

    /* Custom Enclosure */
    jQuery('#primary-menu .mainmenu-customenclosures-en a').on('click', function(){
        ga('send', 'event', 'Primary Menu EN', 'Click', 'Custom Enclosure');
    });

    /* Resources */
    jQuery('#primary-menu .mainmenu-resources-en a').on('click', function(){
        ga('send', 'event', 'Primary Menu EN', 'Click', 'Resources');
    });

    /* Catalog */
    jQuery('#primary-menu .mainmenu-catalog-en a').on('click', function(){
        ga('send', 'event', 'Primary Menu EN', 'Click', 'Catalog');
    });

    /* About */
    jQuery('#primary-menu .mainmenu-about-en a').on('click', function(){
        ga('send', 'event', 'Primary Menu EN', 'Click', 'About');
    });

    /* Contact */
    jQuery('#primary-menu .mainmenu-contact-en a').on('click', function(){
        ga('send', 'event', 'Primary Menu EN', 'Click', 'Contact');
    });



    /* FRENCH */

    /* Products */
    jQuery('#primary-menu .mainmenu-products-fr a').on('click', function(){
        ga('send', 'event', 'Primary Menu FR', 'Click', 'Produits');
    });

    /* Custom Enclosure */
    jQuery('#primary-menu .mainmenu-customenclosures-fr a').on('click', function(){
        ga('send', 'event', 'Primary Menu FR', 'Click', 'Sur Mesure');
    });

    /* Resources */
    jQuery('#primary-menu .mainmenu-resources-fr a').on('click', function(){
        ga('send', 'event', 'Primary Menu FR', 'Click', 'Ressources');
    });

    /* Catalog */
    jQuery('#primary-menu .mainmenu-catalog-fr a').on('click', function(){
        ga('send', 'event', 'Primary Menu FR', 'Click', 'Catalogue');
    });

    /* About */
    jQuery('#primary-menu .mainmenu-about-fr a').on('click', function(){
        ga('send', 'event', 'Primary Menu FR', 'Click', 'À Propos');
    });

    /* Contact */
    jQuery('#primary-menu .mainmenu-contact-fr a').on('click', function(){
        ga('send', 'event', 'Primary Menu FR', 'Click', 'Contact');
    });

});