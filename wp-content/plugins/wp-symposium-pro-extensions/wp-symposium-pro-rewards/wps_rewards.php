<?php
// Shortcodes
require_once('wps_rewards_shortcodes.php');

// Custom Post Type
require_once('wps_custom_post_reward.php');
require_once('wps_custom_post_rewards.php');
// Re-write rules
add_filter( 'rewrite_rules_array','wps_rewards_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_rewards_flush_rewrite_rules' );

function wps_rewards_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;
	$newrules = array();
	
	$newrules['wps_reward/?'] = '/';
	$newrules['wps_rewards/?'] = '/';

	return $newrules + $rules;
}
// Flush re-write rules if need be
function wps_rewards_flush_rewrite_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_reward/?'] ) ) $flush = true;		
	if ( ! isset( $rules['wps_rewards/?'] ) ) $flush = true;		

	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}


// Hooks and Filters
require_once('wps_rewards_hooks_and_filters.php');

// Admin
if (is_admin()) {
	add_action('init', 'wps_rewards_admin_init');
	require_once('wps_rewards_admin.php');
}

function wps_rewards_admin_init() {
	// JS
	wp_enqueue_script('wps-rewards-js', plugins_url('wps_rewards.js', __FILE__), array('jquery'));	
	do_action('wps_rewards_init');
}

// Check if a reward type exists
function wps_reward_type_exists($type) {

	global $wpdb;
	$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_rewards' AND post_status='publish'";
	$rewards = $wpdb->get_results($sql);
	$reward_type_found = false;
	if ($rewards):
		foreach ($rewards as $reward):
			if (get_post_meta($reward->ID, 'wps_rewards_type', true) == $type) $reward_type_found = true;
		endforeach;
	endif;
	return $reward_type_found;

}

// Global function to insert new reward
/*  How it's used (all are required):
	$reward = array(
		'type' => 'reward-slug', // the reward slug
		'title' => 'title', // show in reward title
		'content' => 'content', // shown in reward content
		'post_author' => ID // ID of the user to receive the reward
	);
	wps_insert_reward($reward);
*/
function wps_insert_reward($new_reward) {

	global $wpdb;
	$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_rewards' AND post_status='publish'";
	$rewards = $wpdb->get_results($sql);

	$reward_id = 0;

	if ($rewards):

		global $current_user;

		foreach ($rewards as $reward):

			$wps_reward_type = get_post_meta($reward->ID, 'wps_rewards_type', true);
			$wps_reward_value = get_post_meta($reward->ID, 'wps_rewards_value', true);

			if ($wps_reward_value):

				if ($wps_reward_type == $new_reward['type']):

					$post = array(
					  'post_title'     	=> $new_reward['title'],
					  'post_status'    	=> 'publish',
					  'post_content'	=> $new_reward['content'],
					  'post_type'      	=> 'wps_reward',
					  'post_author'    	=> $new_reward['post_author'],
					  'ping_status'    	=> 'closed',
					  'comment_status' 	=> 'closed',
					);  
					$reward_id = wp_insert_post( $post );

					update_post_meta( $reward_id, 'wps_reward_type', $reward->post_name );
					update_post_meta( $reward_id, 'wps_reward_value', $wps_reward_value );

				endif;		

			endif;

		endforeach;

	endif;

	return $reward_id;

}
?>