<?php 
/**
 * Class Wishlist
 * Description: Manage favorites/wishlist items for user
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 5.8
 *
 *
 * @category Plugin
 * @author Codems
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Wishlist' ) ) :
	
final class Wishlist {
	
	// Static instance of the class
	protected static $_instance = null;
	
	// List of allowed type separate by comma (post, page, custom_post_type, etc.)
	private $allowedPostType = 'product';
	public $metaName = '_wishlist';
	
	/**
	 * Main Wishlist Instance
	 *
	 * Ensures only one instance of Wishlist is loaded or can be loaded.
	 *
	 * @static
	 * @return Wishlist - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Wishlist Constructor (called on wp init).
	 */
	private function __construct() {
		$this->init_hooks();
	}
	
	
	/**
	 * Register necesary hooks
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'add_hooks' ) );
	}
	
	
	/**
	 * Register ajax hook
	 */
	public function add_hooks() {
	    add_action( 'wp_ajax_add_fav', array($this, 'ajax_request_convert') );
		add_action( 'wp_ajax_nopriv_add_fav', array($this, 'ajax_request_convert') );
	}
	
	
	/**
	 * Check if the ajax request has the key
	 *
	 * @return False if invalid
	 */
	public function ajax_request_convert(){
		if($_POST['valid_fav'] == 'ajax-fav'){ // validation d'origine
			return $this->add_post_to_fav($_POST['ID']);
		}else{
			return false;
		}
	}
	
	
	/**
	 * Display the query resultats count
	 *
	 * @param $post_id : ID to add to fav
	 *		  $user_id : User id to add the favorite
	 * @return false or array
	 */
	public function add_post_to_fav($post_id, $user_id = NULL){		
		// post type validation
		$postType = get_post_type($post_id);
		
		if (strpos($this->allowedPostType, $postType) === false) {
			return false; // if post type is not in $allowedPostType
		}
		
		if(is_user_logged_in() || $user_id){ // store in db
			if(!$user_id)
				$user_id = get_current_user_id();
			
			$currFav = get_user_meta($user_id, $this->metaName, true);
			
			$tFav = explode(",", $currFav);
		
			if(empty($tFav)){
				add_user_meta($user_id, $this->metaName, $post_id);
			}else{			
				$key = array_search($post_id, $tFav);
				
				if($key === false){
					$tFav[] = $post_id;
				}else{
					unset($tFav[$key]);
				}
				
				$currFav = implode(",", $tFav);
				
				update_user_meta($user_id, $this->metaName, $currFav);
			}
			
			return get_user_meta($user_id, $this->metaName, true);
		}else{
			// store in cookie
			$currFav = '';
			if(isset($_COOKIE['favoris'])){
				$currFav = $_COOKIE['favoris'];
			}
			
			$tFav = explode(",", $currFav);
			
			$key = array_search($post_id, $tFav);
			
			if($key === false){
				$tFav[] = $post_id;
			}else{
				unset($tFav[$key]);
			}

			$currFav = implode(",", $tFav);
			
			setcookie('favoris', $currFav, time() + (86400 * 365), "/");
			
			return $currFav;
		}
	}
	
	
	/**
	 * Return all the favorites for a user
	 *
	 * @param $user_id (optional) : Int with the user id to retrieve the favorites
	 * @return array
	 */
	public function get_user_favorites($user_id = NULL){
		if(!$user_id){
			if(is_user_logged_in()){
				$tFav = get_user_meta(get_current_user_id(), $this->metaName, true);
			}else{
				$tFav = $_COOKIE['favoris'];
			}
		}else{
			$tFav = get_user_meta($user_id, $this->metaName, true);
		}
		
		return explode(",", $tFav);
	}
	
	
	
}// Wishlist Class
	
function Wishlist() {
	return Wishlist::instance();
}
Wishlist(); 

endif;
?>