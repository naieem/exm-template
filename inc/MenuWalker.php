<?php 
/**
 * Class MenuWalker
 * Description: Le menu Walker principale, il permet d'avoir une hiérachie entre les pages et les CPT.
 * Version: 1.2
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.8.3
 *
 *
 * @category Core
 * @author Codems
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'MenuWalker' ) ) :
	
/**
 * Le menu Walker principale, il permet d'avoir une hiérachie entre les pages et les CPT.
 *
 * @category Utilities
 * @author Codems
 * @version 1.0.0
 */	

class MenuWalker extends Walker_Nav_Menu {
	
	/**
	 * Indique au Walker les paramètres à hériter de son parent.
	 * @var Db_fields
	 */
	var $db_fields = array(
		'parent' => 'menu_item_parent', 
		'id'     => 'db_id' 
	);


	/**
	 * La profondeur actuel du menu.
	 */
	public $current_depth = 0;
	


	/**
	 * Permet de modifier se que chaque élément affiche à son début.
	 * 
	 * @param String $output HTML qui va être affiché.
	 * @param Object $item Object de l'élément actuelle.
	 * @param Int $depth
	 * @param Array $args
	 * @param Int $id ID de l'élément actuel.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$post_id = $item->object_id; // current menu item id



		

		$classes = (!empty($item->classes)? ' class="'.implode(' ', $item->classes).'"':'');
		
		$output .= '<li'.$classes.'><a href="'.$item->url.'" target="'.$item->target.'">'.$item->title.'</a>';
    }
	
	
	/**
	 * Permet de modifier se que chaque élément affiche à sa fin.
	 * 
	 * @param String $output HTML qui va être affiché.
	 * @param Object $item Object de l'élément actuelle.
	 * @param Int $depth
	 * @param Array $args
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {


		$output .= "</li>\n";
	}
	

	function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class='sub-menu-wrap'><ul class='sub-menu'>\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul></div>\n";
    }
	
}// MenuWalker Class

endif;

?>