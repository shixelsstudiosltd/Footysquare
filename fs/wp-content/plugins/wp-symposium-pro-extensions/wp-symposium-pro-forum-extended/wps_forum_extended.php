<?php
// Custom Post Type
require_once('wps_custom_post_forum_extended.php');

// Re-write rules
add_filter( 'rewrite_rules_array','wps_forum_extension_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_forum_extension_flush_rewrite_rules' );

function wps_forum_extension_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;
	$newrules = array();
	
	$newrules['wps_forum_extension/?'] = '/';

	return $newrules + $rules;
}
// Flush re-write rules if need be
function wps_forum_extension_flush_rewrite_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_forum_extension/?'] ) ) $flush = true;		

	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// Hooks and Filters
require_once('wps_forum_extended_hooks_and_filters.php');

// AJAX
require_once('ajax_forum_extended.php');

if (is_admin()) add_action('init', '__wps__wpspro_forum_extended_au');
function __wps__wpspro_forum_extended_au()
{
    wp_enqueue_script('wps-forum-extended-js', plugins_url('wps_forum_extended.js', __FILE__), array('jquery'));   
	wp_localize_script('wps-forum-extended-js', 'wps_forum_extended', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));    	
}

?>