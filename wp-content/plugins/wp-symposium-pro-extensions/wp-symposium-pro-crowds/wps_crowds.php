<?php
// Custom Post Type
require_once('wps_custom_post_crowd.php');

// Re-write rules
add_filter( 'rewrite_rules_array','wps_crowd_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_crowd_flush_rewrite_rules' );

function wps_crowd_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;
	$newrules = array();
	
	$newrules['wps_crowd/?'] = '/';

	return $newrules + $rules;
}
// Flush re-write rules if need be
function wps_crowd_flush_rewrite_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_crowd/?'] ) ) $flush = true;		

	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// AJAX
require_once('ajax_crowds.php');

// Hooks and Filters
require_once('wps_crowds_hooks_and_filters.php');

// Init
add_action('wp_footer', 'wps_crowds_init');
function wps_crowds_init() {
        
    global $current_user;

    wp_enqueue_script('wps-crowds-js', plugins_url('wps_crowds.js', __FILE__), array('jquery'));    
	wp_localize_script( 'wps-crowds-js', 'wps_crowds_ajax', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'wait' => plugins_url('../../wp-symposium-pro/css/images/wait.gif', __FILE__),
		'user_id' => $current_user->ID,
	));		
    wp_enqueue_style('wps-crowds-css', plugins_url('wps_crowds.css', __FILE__), 'css');

	// Select2 replacement drop-down list from com_release(oid)
	wp_enqueue_script('wps-select2-js', plugins_url('../../wp-symposium-pro/js/select2.min.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-select2-css', plugins_url('../../wp-symposium-pro/js/select2.css', __FILE__), 'css');

	// Tooltip
	wp_enqueue_script('wps-tooltip-js', plugins_url('../../wp-symposium-pro/js/tooltipsy.min.js', __FILE__), array('jquery'));	

}


?>