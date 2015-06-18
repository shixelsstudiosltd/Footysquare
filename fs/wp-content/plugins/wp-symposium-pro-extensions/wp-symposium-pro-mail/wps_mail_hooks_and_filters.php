<?php
// Add rewards
add_action( 'rewards_info_box_content_options_filter', 'wps_mail_add_rewards' , 10, 1);
function wps_mail_add_rewards($the_post_id) {
	echo '<option value="mail_send"';
		if (get_post_meta($the_post_id, 'wps_rewards_type', true) == 'mail_send') echo ' SELECTED';
		echo '>'.__('Send private message', WPS2_TEXT_DOMAIN).'</option>';
	echo '<option value="mail_reply"';
		if (get_post_meta($the_post_id, 'wps_rewards_type', true) == 'mail_reply') echo ' SELECTED';
		echo '>'.__('Reply to private message', WPS2_TEXT_DOMAIN).'</option>';
}
// Add rewards (count)
add_action( 'rewards_info_box_content_options_count_filter', 'wps_mail_add_rewards_count_type' , 10, 1);
function wps_mail_add_rewards_count_type($the_post_id) {
	echo '<option value="mail_send"';
		if (get_post_meta($the_post_id, 'wps_rewards_count_type', true) == 'mail_send') echo ' SELECTED';
		echo '>'.__('Send private message', WPS2_TEXT_DOMAIN).'</option>';
	echo '<option value="mail_reply"';
		if (get_post_meta($the_post_id, 'wps_rewards_count_type', true) == 'mail_reply') echo ' SELECTED';
		echo '>'.__('Reply to private message', WPS2_TEXT_DOMAIN).'</option>';
}
// Add rewards (when editing individual user rewards)
add_action( 'reward_info_box_content_options_filter', 'wps_mail_add_reward' , 10, 1);
function wps_mail_add_reward($the_post_id) {
	echo '<option value="mail_send"';
		if (get_post_meta($the_post_id, 'wps_reward_type', true) == 'mail_send') echo ' SELECTED';
		echo '>'.__('Send private message', WPS2_TEXT_DOMAIN).'</option>';
	echo '<option value="mail_reply"';
		if (get_post_meta($the_post_id, 'wps_reward_type', true) == 'mail_reply') echo ' SELECTED';
		echo '>'.__('Reply to private message', WPS2_TEXT_DOMAIN).'</option>';
}

// Reward new mail
add_action( 'wps_mail_post_add_hook', 'wps_mail_send_reward', 10, 3 );
function wps_mail_send_reward($the_post, $the_files, $new_id) {

	global $wpdb;
	$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_rewards' AND post_status='publish'";
	$rewards = $wpdb->get_results($sql);

	if ($rewards):

		global $current_user;

		foreach ($rewards as $reward):

			$wps_reward_type = get_post_meta($reward->ID, 'wps_rewards_type', true);
			$wps_reward_value = get_post_meta($reward->ID, 'wps_rewards_value', true);

			if ($wps_reward_type == 'mail_send'):

				$post = array(
				  'post_title'     	=> $current_user->display_name,
				  'post_status'    	=> 'publish',
				  'post_content'	=> $current_user->display_name.'<br />'.serialize($the_post),
				  'post_type'      	=> 'wps_reward',
				  'post_author'    	=> $current_user->ID,
				  'ping_status'    	=> 'closed',
				  'comment_status' 	=> 'closed',
				);  
				$reward_id = wp_insert_post( $post );

				update_post_meta( $reward_id, 'wps_reward_type', $reward->post_name );
				update_post_meta( $reward_id, 'wps_reward_value', $wps_reward_value );

			endif;		

		endforeach;

	endif;

}

// Reward mail reply
add_action( 'wps_mail_comment_add_hook', 'wps_mail_reply_reward', 10, 3 );
function wps_mail_reply_reward($the_comment, $the_files, $post_id, $new_id) {

	global $wpdb;
	$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_rewards' AND post_status='publish'";
	$rewards = $wpdb->get_results($sql);

	if ($rewards):

		global $current_user;

		foreach ($rewards as $reward):

			$wps_reward_type = get_post_meta($reward->ID, 'wps_rewards_type', true);
			$wps_reward_value = get_post_meta($reward->ID, 'wps_rewards_value', true);

			if ($wps_reward_type == 'mail_reply'):

				$post = array(
				  'post_title'     	=> $current_user->display_name,
				  'post_status'    	=> 'publish',
				  'post_content'	=> $current_user->display_name.'<br />'.serialize($the_comment).'<br />'.$post_id,
				  'post_type'      	=> 'wps_reward',
				  'post_author'    	=> $current_user->ID,
				  'ping_status'    	=> 'closed',
				  'comment_status' 	=> 'closed',
				);  
				$reward_id = wp_insert_post( $post );

				update_post_meta( $reward_id, 'wps_reward_type', $reward->post_name );
				update_post_meta( $reward_id, 'wps_reward_value', $wps_reward_value );

			endif;		

		endforeach;

	endif;

}




?>