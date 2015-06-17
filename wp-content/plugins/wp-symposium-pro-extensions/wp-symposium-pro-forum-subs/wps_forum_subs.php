<?php
// Custom Post Types
require_once('wps_custom_post_subs.php');
require_once('wps_custom_post_forum_subs.php');

// Re-write rules
add_filter( 'rewrite_rules_array','wps_forum_subs_extension_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_forum_subs_extension_flush_rewrite_rules' );

function wps_forum_subs_extension_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;
	$newrules = array();
	
	$newrules['wps_subs/?'] = '/';
	$newrules['wps_forum_subs/?'] = '/';

	return $newrules + $rules;
}
// Flush re-write rules if need be
function wps_forum_subs_extension_flush_rewrite_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_subs/?'] ) ) $flush = true;		
	if ( ! isset( $rules['wps_forum_subs/?'] ) ) $flush = true;		

	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// AJAX functions
require_once('ajax_subs.php');	

// Hooks and Filters
require_once('wps_subs_hooks_and_filters.php');

// Shortcodes
require_once('wps_subs_shortcodes.php');


?>