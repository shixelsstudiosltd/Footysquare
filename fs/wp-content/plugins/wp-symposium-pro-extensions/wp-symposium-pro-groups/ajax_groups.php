<?php
// AJAX functions for activity
add_action( 'wp_ajax_wps_ajax_get_groups', 'wps_ajax_get_groups' ); 
add_action( 'wp_ajax_wps_ajax_group_create', 'wps_ajax_group_create' ); 
add_action( 'wp_ajax_wps_ajax_group_delete', 'wps_ajax_group_delete' ); 
add_action( 'wp_ajax_wps_ajax_group_join', 'wps_ajax_group_join' ); 
add_action( 'wp_ajax_wps_ajax_group_leave', 'wps_ajax_group_leave' ); 
add_action( 'wp_ajax_wps_ajax_group_cancel', 'wps_ajax_group_cancel' ); 
add_action( 'wp_ajax_wps_ajax_group_kick', 'wps_ajax_group_kick' ); 
add_action( 'wp_ajax_wps_ajax_group_accept', 'wps_ajax_group_accept' ); 
add_action( 'wp_ajax_wps_ajax_group_reject', 'wps_ajax_group_reject' ); 

/* GET GROUPS */
function wps_ajax_get_groups() {

	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'post_title',
		'order'            => 'ASC',
		'post_type'        => 'wps_group',
		'post_status'      => 'publish',
	);
	$groups = get_posts($args);
	$return_arr = array();
	foreach ($groups as $group) {
	    $row_array['value'] = $group->post_title;
	    $row_array['label'] = $group->post_title;
	    array_push($return_arr,$row_array);
	}
	echo json_encode($return_arr);	
	exit;
}

/* CREATE GROUP */
function wps_ajax_group_create() {

	global $current_user;

	$post = array(
	  'post_title'     => $_POST['wps_group_create_title'],
	  'post_content'   => $_POST['wps_group_create_textarea'],
	  'post_status'    => 'publish',
	  'post_type'      => 'wps_group',
	  'post_author'    => $current_user->ID,
	  'ping_status'    => 'closed',
	  'comment_status' => 'closed',
	);  
	$new_id = wp_insert_post( $post );

	if ($new_id):

		// Add admin as a member
      	$member = get_user_by('id', $current_user->ID);

		$post = array(
		  'post_title'     	=> $member->user_login.' - '.$_POST['wps_group_create_title'],
		  'post_name'		=> sanitize_title_with_dashes($member->user_login.' '.$_POST['wps_group_create_title']),
		  'post_status'    	=> 'publish',
		  'post_type'      	=> 'wps_group_members',
		  'post_author'    	=> $current_user->ID,
		  'ping_status'    	=> 'closed',
		  'comment_status' 	=> 'open',
		);  
		$new_member_post_id = wp_insert_post( $post );

		if ($new_member_post_id):
			update_post_meta( $new_member_post_id, 'wps_member', $current_user->ID );
			update_post_meta( $new_member_post_id, 'wps_group', $new_id );
			update_post_meta( $new_member_post_id, 'wps_group_member_since', current_time('mysql', 1) );						
			echo $new_id;
		else:
			echo sprintf('failed to add author as member (%d, %d)', $current_user->ID, $new_id);
		endif;

		// Any further actions?
		do_action( 'wps_create_group_add_hook', $_POST, $new_id );

	else:

		echo sprintf('failed to add group (%d, %d)', $current_user->ID, $group_id);

	endif;
	exit;
}

/* DELETE GROUP */
function wps_ajax_group_delete() {

	global $wpdb;
	$group_id = $_POST['group_id'];

	// Delete all memberships
	$sql = "DELETE FROM ".$wpdb->prefix."postmeta WHERE post_id = %d";
	$wpdb->query($wpdb->prepare($sql, $group_id));

	if (wp_delete_post( $group_id, true )):
		echo 'ok';
	else:
		echo sprintf('failed to delete group (%d)', $group_id);
	endif;
    
    // Delete members
    $sql = "SELECT p.ID, p.post_status, m1.meta_value as wps_member, m1.meta_value as wps_member FROM ".$wpdb->prefix."posts p 
    LEFT JOIN ".$wpdb->prefix."postmeta m1 ON m1.post_id = p.ID
    LEFT JOIN ".$wpdb->prefix."postmeta m2 ON m2.post_id = p.ID
    WHERE p.post_type = 'wps_group_members'
      AND (m1.meta_key = 'wps_member' AND m2.meta_key = 'wps_group')
      AND (m2.meta_value = %d)";

    $members = $wpdb->get_results($wpdb->prepare($sql, $group_id));
	if ($members):
		foreach ($members as $member):
			wp_delete_post( $member->ID, true );
		endforeach;
	endif;
    
	// Any further actions?
	do_action( 'wps_group_delete_hook', $group_id );

}

/* JOIN GROUP */
function wps_ajax_group_join() {

	global $wpdb,$current_user;
	$group_id = $_POST['group_id'];
	if ($group_id):
		$sql = "SELECT p.ID FROM ".$wpdb->prefix."posts p
				LEFT JOIN ".$wpdb->prefix."postmeta m1 ON p.ID = m1.post_id 
				LEFT JOIN ".$wpdb->prefix."postmeta m2 ON p.ID = m2.post_id
				WHERE post_type = 'wps_group_members' AND post_status = 'publish'
				  AND m1.meta_key = 'wps_member' AND m1.meta_value = %d
				  AND m2.meta_key = 'wps_group' AND m2.meta_value = %d";
		$membership = $wpdb->get_row($wpdb->prepare($sql, $current_user->ID, $group_id));
		if (!$membership):

	      	$member = get_user_by('id', $current_user->ID);
	      	$group = get_post($group_id);

	      	$post_status = get_post_meta($group_id, 'wps_group_privacy', true) ? 'pending' : 'publish';

			$post = array(
			  'post_title'     	=> $member->user_login.' - '.$group->post_title,
			  'post_name'		=> sanitize_title_with_dashes($member->user_login.' '.$group->post_title),
			  'post_status'    	=> $post_status,
			  'post_type'      	=> 'wps_group_members',
			  'post_author'    	=> $current_user->ID,
			  'ping_status'    	=> 'closed',
			  'comment_status' 	=> 'open',
			);  
			$new_id = wp_insert_post( $post );

			if ($new_id):
				update_post_meta( $new_id, 'wps_member', $current_user->ID );
				update_post_meta( $new_id, 'wps_group', $group_id );
				update_post_meta( $new_id, 'wps_group_member_since', current_time('mysql', 1) );						

				// If private group, send email to group administrator
				$group_admin = wps_group_administrator($group_id);
				$recipient_id = $group_admin->ID;
				$subject = __('Group join request', WPS2_TEXT_DOMAIN);
				$content = __('I would like to join your group.', WPS2_TEXT_DOMAIN);
				$content .= '<p>'.__('My profile:', WPS2_TEXT_DOMAIN).' '.wps_display_name(array('user_id'=>$current_user->ID, 'link'=>true)).'</p>';
				$content .= '<p>'.wps_get_group_name($group_id, $link=true).'</p>';
				$excerpt = __('I would like to join your group.', WPS2_TEXT_DOMAIN);
				$author_id = $current_user->ID;
				$url = '';
				wps_pro_insert_alert('group_join_request', $subject, $content, $author_id, $recipient_id, $url, $url, $excerpt, 'pending');

				// Done
				echo 'ok';
			else:
				echo sprintf('failed to add (%d, %d)', $current_user->ID, $group_id);
			endif;
		else:
			echo sprintf('failed to check (%d, %d)', $current_user->ID, $group_id);
		endif;
	else:
		echo 'No group id passed';
	endif;

}

/* LEAVE GROUP */
function wps_ajax_group_leave() {

	$group_id = $_POST['group_id'];
	global $wpdb, $current_user;
	if ($group_id):
		$sql = "SELECT p.ID FROM ".$wpdb->prefix."posts p
				LEFT JOIN ".$wpdb->prefix."postmeta m1 ON p.ID = m1.post_id 
				LEFT JOIN ".$wpdb->prefix."postmeta m2 ON p.ID = m2.post_id
				WHERE post_type = 'wps_group_members' AND post_status = 'publish'
				  AND m1.meta_key = 'wps_member' AND m1.meta_value = %d
				  AND m2.meta_key = 'wps_group' AND m2.meta_value = %d";
		$membership = $wpdb->get_row($wpdb->prepare($sql, $current_user->ID, $group_id));
		if ($membership):
			if (wp_delete_post( $membership->ID, true )):
				echo 'ok';
			else:
				echo sprintf('failed to delete (%d, %d)', $current_user->ID, $group_id);
			endif;
		else:
			echo sprintf('failed to find (%d, %d)', $current_user->ID, $group_id);
		endif;
	endif;

}

/* CANCEL JOIN REQUEST */
function wps_ajax_group_cancel() {

	$group_id = $_POST['group_id'];
	global $wpdb, $current_user;
	if ($group_id):
		$sql = "SELECT p.ID FROM ".$wpdb->prefix."posts p
				LEFT JOIN ".$wpdb->prefix."postmeta m1 ON p.ID = m1.post_id 
				LEFT JOIN ".$wpdb->prefix."postmeta m2 ON p.ID = m2.post_id
				WHERE post_type = 'wps_group_members' AND (post_status = 'publish' OR post_status = 'pending')
				  AND m1.meta_key = 'wps_member' AND m1.meta_value = %d
				  AND m2.meta_key = 'wps_group' AND m2.meta_value = %d";
		$membership = $wpdb->get_row($wpdb->prepare($sql, $current_user->ID, $group_id));
		if ($membership):
			if (wp_delete_post( $membership->ID, true )):
				echo 'ok';
			else:
				echo sprintf('failed to delete (%d, %d)', $current_user->ID, $group_id);
			endif;
		else:
			echo sprintf('failed to find (%d, %d)', $current_user->ID, $group_id);
		endif;
	endif;

}

/* KICK FROM GROUP */
function wps_ajax_group_kick() {

	$group_id = $_POST['group_id'];
	$member_id = $_POST['member_id'];
	global $wpdb, $current_user;
	if ($group_id && $member_id):
		$sql = "SELECT p.ID FROM ".$wpdb->prefix."posts p
				LEFT JOIN ".$wpdb->prefix."postmeta m1 ON p.ID = m1.post_id 
				LEFT JOIN ".$wpdb->prefix."postmeta m2 ON p.ID = m2.post_id
				WHERE post_type = 'wps_group_members' AND (post_status = 'publish' OR post_status = 'pending')
				  AND m1.meta_key = 'wps_member' AND m1.meta_value = %d
				  AND m2.meta_key = 'wps_group' AND m2.meta_value = %d";
		$membership = $wpdb->get_row($wpdb->prepare($sql, $member_id, $group_id));
		if ($membership):
			if (wp_delete_post( $membership->ID, true )):
				echo 'ok';
			else:
				echo sprintf('failed to delete (%d, %d)', $member_id, $group_id);
			endif;
		else:
			echo sprintf('failed to find (%d, %d)', $member_id, $group_id);
		endif;
	endif;

}

/* ACCEPT REQUEST TO JOIN GROUP */
function wps_ajax_group_accept() {

	global $wpdb,$current_user;
	$group_id = $_POST['group_id'];
	$member_id = $_POST['member_id'];

	if ($group_id && $member_id):
		$sql = "SELECT p.ID FROM ".$wpdb->prefix."posts p
				LEFT JOIN ".$wpdb->prefix."postmeta m1 ON p.ID = m1.post_id 
				LEFT JOIN ".$wpdb->prefix."postmeta m2 ON p.ID = m2.post_id
				WHERE post_type = 'wps_group_members' AND post_status = 'pending'
				  AND m1.meta_key = 'wps_member' AND m1.meta_value = %d
				  AND m2.meta_key = 'wps_group' AND m2.meta_value = %d";
		$membership = $wpdb->get_var($wpdb->prepare($sql, $member_id, $group_id));
		if ($membership):

	      	$group = get_post($group_id);

			// Update membership
			$post = array(
			  'ID'			   => (int)$membership,
			  'post_status'    => 'publish',
			);  
			if (wp_update_post( $post )):

				// Tell the member
				$group_admin = wps_group_administrator($group_id);
				$recipient_id = $member_id;
				$subject = __('Group join request accepted', WPS2_TEXT_DOMAIN);
				$content = '<p>'.sprintf(__('You request to join %s has been accepted.', WPS2_TEXT_DOMAIN), wps_get_group_name($group_id, $link=true)).'</p>';
				$excerpt = __('Join request accepted.', WPS2_TEXT_DOMAIN);
				$author_id = $current_user->ID;
				$url = '';
				wps_pro_insert_alert('group_join_request_accept', $subject, $content, $author_id, $recipient_id, $url, $url, $excerpt, 'pending');

				// Done
				echo 'ok';
			else:
				echo sprintf('failed to update (%d, %d)', (int)$membership, $group_id);
			endif;
		else:
			echo $wpdb->last_query;
			echo sprintf('failed to check (%d, %d)', (int)$membership, $group_id);
		endif;

	else:
		echo 'No group id passed';
	endif;

}

/* REJECT REQUEST TO JOIN GROUP */
function wps_ajax_group_reject() {

	global $wpdb,$current_user;
	$group_id = $_POST['group_id'];
	$member_id = $_POST['member_id'];

	if ($group_id && $member_id):
		$sql = "SELECT p.ID FROM ".$wpdb->prefix."posts p
				LEFT JOIN ".$wpdb->prefix."postmeta m1 ON p.ID = m1.post_id 
				LEFT JOIN ".$wpdb->prefix."postmeta m2 ON p.ID = m2.post_id
				WHERE post_type = 'wps_group_members' AND post_status = 'pending'
				  AND m1.meta_key = 'wps_member' AND m1.meta_value = %d
				  AND m2.meta_key = 'wps_group' AND m2.meta_value = %d";
		$membership = $wpdb->get_var($wpdb->prepare($sql, $member_id, $group_id));
		if ($membership):

	      	$group = get_post($group_id);

			// Delete request membership
			if (wp_delete_post( $membership, true )):

				// Tell the member
				$group_admin = wps_group_administrator($group_id);
				$recipient_id = $member_id;
				$subject = __('Group join request declined', WPS2_TEXT_DOMAIN);
				$content = '<p>'.sprintf(__('You request to join %s has been declined.', WPS2_TEXT_DOMAIN), wps_get_group_name($group_id, $link=true)).'</p>';
				$excerpt = __('Join request declined.', WPS2_TEXT_DOMAIN);
				$author_id = $current_user->ID;
				$url = '';
				wps_pro_insert_alert('group_join_request_reject', $subject, $content, $author_id, $recipient_id, $url, $url, $excerpt, 'pending');

				// Done
				echo 'ok';
			else:
				echo sprintf('failed to update (%d, %d)', (int)$membership, $group_id);
			endif;
		else:
			echo $wpdb->last_query;
			echo sprintf('failed to check (%d, %d)', (int)$membership, $group_id);
		endif;

	else:
		echo 'No group id passed';
	endif;

}


?>
