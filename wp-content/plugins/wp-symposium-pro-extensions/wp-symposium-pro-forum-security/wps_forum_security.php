<?php
// Hooks and Filters
require_once('wps_security_hooks_and_filters.php');

// Init
add_action('admin_footer', 'wps_forum_security_init');

function wps_forum_security_init() {
	// JS
	wp_enqueue_script('wps-forum-security-js', plugins_url('wps_forum_security.js', __FILE__), array('jquery'));	
	// Anything else?
	do_action('wps_forum_security_init');
}


?>