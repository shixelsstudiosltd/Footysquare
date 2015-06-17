<?php
// Custom Post Type
require_once('wps_custom_post_calendars.php');
require_once('wps_custom_post_events.php');

// Re-write rules
add_filter( 'rewrite_rules_array','wps_calendar_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_calendar_flush_rewrite_rules' );

function wps_calendar_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;
	$newrules = array();
	
	$newrules['wps_calendar/?'] = '/';
	$newrules['wps_event/?'] = '/';

	return $newrules + $rules;
}
// Flush re-write rules if need be
function wps_calendar_flush_rewrite_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_calendar/?'] ) ) $flush = true;		
	if ( ! isset( $rules['wps_event/?'] ) ) $flush = true;		

	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// Shortcodes
require_once('wps_calendar_shortcodes.php');

// AJAX
require_once('ajax_calendar.php');

// Getting Started/Help
if (is_admin())
	require_once('wps_calendar_help.php');

// Admin
add_action('init', 'wps_calendar_admin_init');
function wps_calendar_admin_init() {
	wp_enqueue_script('wps-calendar-js', plugins_url('wps_calendar.js', __FILE__), array('jquery'));		
	wp_localize_script( 'wps-calendar-js', 'wps_calendar_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );		
	// Date/time picker
	wp_enqueue_script('wps-calendar-datepicker-js', plugins_url('jquery.datetimepicker.js', __FILE__), array('jquery'));		
	wp_enqueue_style('wps-calendar-datepicker-css', plugins_url('jquery.datetimepicker.css', __FILE__), 'css');
	// Select2 replacement drop-down list from core
	wp_enqueue_script('wps-select2-js', plugins_url('../../wp-symposium-pro/js/select2.min.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-select2-css', plugins_url('../../wp-symposium-pro/js/select2.css', __FILE__), 'css');
}

?>