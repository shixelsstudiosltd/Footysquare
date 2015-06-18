<?php
// Custom Post Type
require_once('wps_custom_post_events.php');

// Shortcodes
require_once('wps_events_shortcodes.php');

// Getting Started/Help
if (is_admin())
	require_once('wps_events_help.php');

// Admin
add_action('init', 'wps_events_admin_init');
function wps_events_admin_init() {
	wp_enqueue_script('wps-events-js', plugins_url('wps_events.js', __FILE__), array('jquery'));		
	// Date/time picker
	wp_enqueue_script('wps-events-datepicker-js', plugins_url('jquery.datetimepicker.js', __FILE__), array('jquery'));		
	wp_enqueue_style('wps-events-datepicker-css', plugins_url('jquery.datetimepicker.css', __FILE__), 'css');
}

// Flush the re-write ruless, if WPS Pro rules are not yet included
function wps_events_flush_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_event/?'] ) ) $flush = true;		

	// If required, flush re-write rules
	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// Add WPS Pro re-write rules
function wps_events_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;

	$newrules = array();

	$newrules['wps_event/?'] = '/';

	return $newrules + $rules;
}


add_filter( 'rewrite_rules_array','wps_events_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_events_flush_rules' );

?>