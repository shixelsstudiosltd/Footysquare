<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Meta Box Class
 *
 * Handles admin side plugin functionality.
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */

//include the main class file
require_once ( WPW_FP_META_DIR . '/meta-box-class.php' );

class Wpw_Fp_Custom_Meta_Box extends Wpw_Fp_Meta_Box {
	
	public function __construct( $config ) {
		
		parent::__construct( $config );
		
	}
	
	/**
	 * Add Label to meta box
	 * 
	 * @author Ohad Raz
	 * 
	 */
	public function addFollowersCounter($id, $args, $repeater=false){
	
		$new_field = array( 'type' => 'followerscounter','id'=> $id,'default' => '0','std' => '','desc' => '','style' =>'','name' => 'Label' );
		$new_field = array_merge( $new_field, $args );
    	
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
	
	/**
	 * Show Field Followers Counter Label.
	 *
	 * @param string $field 
	 * @param string $meta 
	 * @since 1.1.0
	 * @access public
	 */
	public function show_field_followerscounter( $field, $meta) {  
		
		global $post;
		
		$this->show_field_begin( $field, $meta );
		
		// get user counts
		$numn = wpw_fp_get_post_followers_count( $post->ID );
		
		echo "<label for='{$field['id']}' id='{$field['id']}'><strong>{$numn}</strong></label>";
		$this->show_field_end( $field, $meta );
	} 
	
}
?>