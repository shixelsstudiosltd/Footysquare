<?php
// Shortcodes
require_once('wps_extended_shortcodes.php');

// Custom Post Type
require_once('wps_custom_post_extended.php');

// Re-write rules
add_filter( 'rewrite_rules_array','wps_extension_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_extension_flush_rewrite_rules' );

function wps_extension_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;
	$newrules = array();
	
	$newrules['wps_extension/?'] = '/';

	return $newrules + $rules;
}
// Flush re-write rules if need be
function wps_extension_flush_rewrite_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_extension/?'] ) ) $flush = true;		

	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// Hooks and Filters
require_once('wps_extended_hooks_and_filters.php');

// AJAX
require_once('ajax_extended.php');

// Supporting functions
function wps_can_see_extension($slug) {
    
    $saved_roles = get_option( 'wps_extension_roles_'.$slug);
    $can_see = false;
    global $current_user;

    if ($saved_roles):
        foreach ( $saved_roles as $role => $name ) :
            if ($name == 'everyone' || current_user_can($name)) $can_see = true;
        endforeach;
    else:
        $can_see = true; // no setting, so defaults to everyone
    endif;

    return $can_see;
    
}


// Admin
if (is_admin()) add_action('init', 'wps_extended_admin');
function wps_extended_admin() {
	wp_enqueue_script('wps-extended-js', plugins_url('wps_extended.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-extended-css', plugins_url('wps_extended.css', __FILE__), 'css');
}

?>