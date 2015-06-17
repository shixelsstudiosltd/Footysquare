<?php

// Add groups after user registers
add_action('user_register','wps_add_default_groups');
function wps_add_default_groups($user_id){

	$values = get_option('wps_default_groups');
	if ($values):

		$new_user = get_user_by('id', $user_id);

		foreach($values as $value):

			// For each group...
	      	$group = get_post($value);

			$post = array(
			  'post_title'     	=> $new_user->user_login.' - '.$group->post_title,
			  'post_name'		=> sanitize_title_with_dashes($new_user->user_login.' '.$group->post_title),
			  'post_status'    	=> 'publish',
			  'post_type'      	=> 'wps_group_members',
			  'post_author'    	=> $user_id,
			  'ping_status'    	=> 'closed',
			  'comment_status' 	=> 'open',
			);  
			$new_id = wp_insert_post( $post );

			if ($new_id):
				update_post_meta( $new_id, 'wps_member', $user_id );
				update_post_meta( $new_id, 'wps_group', $value );
				update_post_meta( $new_id, 'wps_group_member_since', current_time('mysql') );						
			endif;		

		endforeach;

	endif;
}

?>