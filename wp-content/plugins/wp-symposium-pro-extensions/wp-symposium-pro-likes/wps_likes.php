<?php

// AJAX
require_once('ajax_likes.php');

// Hooks and Filters
require_once('wps_likes_hooks_and_filters.php');

// Init
add_action('wp_footer', 'wps_likes_init');
function wps_likes_init() {
        
    global $current_user;

    wp_enqueue_script('wps-likes-js', plugins_url('wps_likes.js', __FILE__), array('jquery'));    
	wp_localize_script( 'wps-likes-js', 'wps_likes_ajax', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'user_id' => $current_user->ID,
	));		
    wp_enqueue_style('wps-likes-css', plugins_url('wps_likes.css', __FILE__), 'css');

    // Tooltip
	wp_enqueue_script('wps-tooltip-js', plugins_url('../../wp-symposium-pro/js/tooltipsy.min.js', __FILE__), array('jquery'));	

}

// Admin
if (is_admin()):
	require_once('wps_likes_admin.php');
    add_action('init', 'wps_likes_admin');
endif;
function wps_likes_admin() {
    wp_enqueue_script('wps-likes-js', plugins_url('wps_likes.js', __FILE__), array('jquery'));    
	wp_localize_script( 'wps-likes-js', 'wps_likes_ajax', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	));		
}

?>