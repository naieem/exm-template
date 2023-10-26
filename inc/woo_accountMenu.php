<?php 
/**
 * Class WOO_AccountMenu
 * Description: Add item in the my account menu
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 *
 * @category Core
 * @author Codems
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WOO_AccountMenu' ) ) :
	
final class WOO_AccountMenu {
	
	// Static instance of the class
	protected static $_instance = null;
	
	// Contain slug and name for all menu item
	public $endpoints = array();
	
	
	/**
	 * Main WOO_AccountMenu Instance
	 *
	 * Ensures only one instance of WOO_AccountMenu is loaded or can be loaded.
	 *
	 * @static
	 * @return WOO_AccountMenu - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * WOO_AccountMenu Constructor (called on wp init).
	 */
	private function __construct() {
		// $this->endpoints = array(
		// 	'mes-favoris' => __('My favorites', 'theme-client')
		// );
		
		$this->init_hooks();
	}
	
	
	/**
	 * Register necesary hooks
	 */
	private function init_hooks() {
		add_action( 'init', array($this, 'my_custom_endpoints') );
		add_filter( 'query_vars', array($this, 'my_custom_query_vars'), 0 );
		add_filter( 'woocommerce_account_menu_items', array($this, 'add_menu_items') );
		
		add_filter( 'the_title', array($this, 'endpoints_title') );
		
		// Childs emails
		if ( !wp_next_scheduled( 'send_child_birthday_email' ) ) {
			wp_schedule_event( time(), 'daily', 'send_child_birthday_email' );
		}
		add_action( 'send_child_birthday_email', array( $this, 'send_emails_childs' ) );
		
		
		foreach ($this->endpoints as $slug => $endpoint) {
			add_action( 'woocommerce_account_'.$slug.'_endpoint', array($this, 'endpoint_content') );
		}
	}
	

	/**
	 * Create the endpoints
	 */
	public function my_custom_endpoints() {
		foreach ($this->endpoints as $slug => $endpoint) {
			add_rewrite_endpoint( $slug, EP_ROOT | EP_PAGES );
		}
	}
	
	
	/**
	 * Add the endpoints to the query_vars
	 */
	public function my_custom_query_vars($vars) {
		foreach ($this->endpoints as $slug => $endpoint) {
			$vars[] = $slug;
		}
		return $vars;
	}
	
	
	/**
	 * Add menu items in my account
	 */
	public function add_menu_items($items){
		// Search for the item position and +1 since is after the selected item key.
	    $position = array_search( 'dashboard', array_keys( $items ) ) + 1;

	    // Insert the new item.
	    $array = array_slice( $items, 0, $position, true );
	    $array += $this->endpoints;
	    $array += array_slice( $items, $position, count( $items ) - $position, true );
		
		return $array;
	}
	
	
	/**
	 * Change the page title for the endpoints
	 */
	public function endpoints_title( $title ) {
	    global $wp_query;

	    foreach ($this->endpoints as $slug => $endpoint) {
	    	
		    $is_endpoint = isset( $wp_query->query_vars[$slug] );

		    if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
		        // New page title.
		        $title = $endpoint;
		        
		        remove_filter( 'the_title', array($this, 'endpoints_title') );
		    }
		}

	    return $title;
	}
	
	
	/**
	 * Custom template for the new endpoints
	 */
	public function endpoint_content(){
		global $wp;

		foreach ( $wp->query_vars as $key => $value ) {
			// Ignore pagename param.
			if ( 'pagename' === $key ) {
				continue;
			}

			wc_get_template('myaccount/'.$key.'.php');
		}
	}
	
	
	
	/**
	 * Scheduled daily cron to send birthday emails to childrens
	 */
	public function send_emails_childs(){
		$args = array(
			'orderby'      => 'id',
		);
		$wc_users = get_users( $args );
		
		foreach ($wc_users as $user) {
			if( have_rows('enfants', 'user_'.$user->ID ) ){
			    while ( have_rows('enfants', 'user_'.$user->ID ) ){ the_row();
			        
			        $date = get_sub_field('date_danniversaire');
					$nom = get_sub_field('prenom');
					
					$birthday = date( "-m-d", strtotime( $date ));
					$today = date("-m-d");
					
					// Check si cest today
					if( $birthday == $today ){
						if( ICL_LANGUAGE_CODE == 'fr' ){
							$subject = 'Joyeuse anniversaire '.$nom.'!';
							$message = 'Joyeuse anniversaire à '.$nom.'! Que cette journée soit remplie de bonheur pour toute la famille!';
						}else{
							$subject = 'Happy Birthday '.$nom.'!';
							$message = 'Happy Birthday '.$nom.'! That this day be filled with happiness for the whole family!';

						}
						wc_mail( $user->user_email, $subject, $message );
					}
					
				}
			}
		}
		
	}//
	
	
	
	
}// WOO_AccountMenu Class
	
function WOO_AccountMenu() {
	return WOO_AccountMenu::instance();
}


endif;
?>

