<?php
// Custom Post Type
require_once('wps_custom_post_gallery.php');

// Admin Page
if (is_admin()) require_once('wps_gallery_admin.php');

// Shortcodes
require_once('wps_gallery_shortcodes.php');

// AJAX
require_once('ajax_gallery.php');

// Hooks and Filters
//require_once('wps_crowds_hooks_and_filters.php');

// Flush the re-write ruless, if WPS Pro rules are not yet included
function wps_gallery_flush_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_gallery/?'] ) ) $flush = true;		

	// If required, flush re-write rules
	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// Add WPS Pro re-write rules
function wps_gallery_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;

	$newrules = array();

	$newrules['wps_gallery/?'] = '/';

	return $newrules + $rules;
}


add_filter( 'rewrite_rules_array','wps_gallery_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_gallery_flush_rules' );


?>