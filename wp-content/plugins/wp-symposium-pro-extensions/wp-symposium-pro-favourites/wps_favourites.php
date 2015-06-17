<?php
if (!is_admin()) {

	add_action('wp_footer', 'wps_favourites_init');
    function wps_favourites_init() {

        wp_enqueue_script('wps-favourites-js', plugins_url('wps_favourites.js', __FILE__), array('jquery'));    
        wp_enqueue_style('wps-favourites-css', plugins_url('wps_favourites.css', __FILE__), 'css');
        wp_localize_script( 'wps-favourites-js', 'wps_favourites_ajax', array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'fav_on' => plugins_url('images/star.png', __FILE__),
            'fav_off' => plugins_url('images/star_empty.png', __FILE__),
        ));        

        // Anything else?
        do_action('wps_favourites_init_hook');
    }

    // Shortcodes
    require_once('wps_favourites_shortcodes.php');

    // Hooks and Filters
    require_once('wps_favourites_hooks_and_filters.php');

} else {

    // Getting Started/Help
	require_once('wps_favourites_admin.php');
    
}

// AJAX
require_once('ajax_favourites.php');


?>