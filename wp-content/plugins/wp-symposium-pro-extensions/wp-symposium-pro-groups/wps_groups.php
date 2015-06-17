<?php
// Custom Post Type
require_once('wps_custom_post_groups.php');
require_once('wps_custom_post_group_members.php');

// Re-write rules
add_filter( 'rewrite_rules_array','wps_groups_subs_extension_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_groups_subs_extension_flush_rewrite_rules' );

function wps_groups_subs_extension_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;
	$newrules = array();
	
	$newrules['wps_group/?'] = '/';
	$newrules['wps_group_members/?'] = '/';

	return $newrules + $rules;
}
// Flush re-write rules if need be
function wps_groups_subs_extension_flush_rewrite_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_group/?'] ) ) $flush = true;		
	if ( ! isset( $rules['wps_group_members/?'] ) ) $flush = true;		

	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// Core
require_once('wps_groups_core.php');

// AJAX functions (needed for admin and frontend)
require_once('ajax_groups.php');

// Hooks and Filters
require_once('wps_groups_hooks_and_filters.php');

// Non-admin
if (!is_admin()) {
	require_once('wps_groups_shortcodes.php');
}

// Admin
if (is_admin()) {
	require_once('wps_groups_admin.php');
	add_action('init', 'wps_group_admin_init');
}	


function wps_group_admin_init() {
	if (function_exists('wps_display_name')):
		$permalink = get_permalink(get_option('wpspro_group_page'));
		$group_url = $permalink.wps_query_mark($permalink);

		if ($profile_page = get_option('wpspro_profile_page')):
			if (get_post($profile_page)):
				wp_enqueue_script('wps-groups-js', plugins_url('wps_groups.js', __FILE__), array('jquery'));	
				wp_localize_script('wps-groups-js', 'wpspro_groups', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ), 
					'plugins_url' => plugins_url( '', __FILE__ ), 
					'profile_page_url' => get_page_link($profile_page),
					'group_page_url' => $group_url,
				));	
			endif;
		endif;
	endif;
}



?>