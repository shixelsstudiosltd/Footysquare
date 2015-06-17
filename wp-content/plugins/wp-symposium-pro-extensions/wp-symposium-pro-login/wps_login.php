<?php
// Admin
require_once('wps_login_admin.php');

// Shortcodes
require_once('wps_login_shortcodes.php');

// Hooks and Filters
require_once('wps_login_hooks_and_filters.php');

// AJAX
require_once('ajax_login.php');

// CSS etc
add_action('wp_footer', 'wps_login_init');
function wps_login_init() {
    
	wp_enqueue_script('wps-login-js', plugins_url('wps_login.js', __FILE__), array('jquery'));	
	wp_localize_script( 'wps-login-js', 'wps_login_ajax', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	));		
	wp_enqueue_style('wps-login-css', plugins_url('wps_login.css', __FILE__), 'css');	
	// Select2 replacement drop-down list from core
	wp_enqueue_script('wps-select2-js', plugins_url('../../wp-symposium-pro/js/select2.min.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-select2-css', plugins_url('../../wp-symposium-pro/js/select2.css', __FILE__), 'css');
	// Anything else?
	do_action('wps_login_init_hook');
}


?>