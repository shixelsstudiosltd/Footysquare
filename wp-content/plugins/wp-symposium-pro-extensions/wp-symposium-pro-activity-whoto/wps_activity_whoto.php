<?php
// Hooks and filters
require_once('wps_whoto_hooks_and_filters.php');

// Init
add_action('wps_activity_init_hook', 'wps_whoto_init');
function wps_whoto_init() {
        
    wp_enqueue_script('wps-activity-whoto-js', plugins_url('wps_activity_whoto.js', __FILE__), array('jquery'));    
    wp_enqueue_style('wps-activity-whoto-css', plugins_url('wps_activity_whoto.css', __FILE__), 'css');

}



?>