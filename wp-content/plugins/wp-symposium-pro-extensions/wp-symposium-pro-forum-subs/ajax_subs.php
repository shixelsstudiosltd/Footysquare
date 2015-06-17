<?php
// AJAX functions for forum subscriptions
add_action( 'wp_ajax_wps_ajax_subs_subscribe', 'wps_ajax_subs_subscribe' ); 
add_action( 'wp_ajax_wps_ajax_subs_unsubscribe', 'wps_ajax_subs_unsubscribe' ); 
add_action( 'wp_ajax_wps_ajax_subs_unsubscribe_all', 'wps_ajax_subs_unsubscribe_all' ); 
add_action( 'wp_ajax_wps_ajax_subs_forum_subscribe', 'wps_ajax_subs_forum_subscribe' ); 
add_action( 'wp_ajax_wps_ajax_subs_forum_unsubscribe', 'wps_ajax_subs_forum_unsubscribe' ); 

/* ADD SUB TO POST */
function wps_ajax_subs_subscribe() {
	global $current_user;
	$post = array(
		'post_title'		=> $current_user->user_login,
	  	'post_status'   	=> 'publish',
	  	'post_type'     	=> 'wps_subs',
	  	'post_author'   	=> $current_user->ID,
	  	'ping_status'   	=> 'closed',
	  	'comment_status'	=> 'closed',
	);  
	$new_sub_id = wp_insert_post( $post );
	update_post_meta( $new_sub_id, 'wps_post_id', $_POST['post_id'] );	
	echo $new_sub_id;
}

/* DELETE POST SUB */
function wps_ajax_subs_unsubscribe() {
	$id = $_POST['sub_id'];
	if ($id):
		global $current_user;
		$sub = get_post($id);
		if ($sub->post_author == $current_user->ID):
			if (wp_delete_post($id, true)):
				echo 'ok';
			else:
				echo 'delete failed: '.$id;
			endif;
		else:
			echo 'not owner, owner is '.$sub->post_author;
		endif;
	else:
		echo 'no id';
	endif;
}

/* DELETE POST SUB (ALL) */
function wps_ajax_subs_unsubscribe_all() {
	global $current_user,$wpdb;
	$sql = "DELETE FROM ".$wpdb->prefix."posts WHERE post_type='wps_subs' AND post_author = %d";
	$wpdb->query($wpdb->prepare($sql, $current_user->ID));
}


/* ADD SUB TO FORUM */
function wps_ajax_subs_forum_subscribe() {
	global $current_user;
	$post = array(
		'post_title'		=> $current_user->user_login,
	  	'post_status'   	=> 'publish',
	  	'post_type'     	=> 'wps_forum_subs',
	  	'post_author'   	=> $current_user->ID,
	  	'ping_status'   	=> 'closed',
	  	'comment_status'	=> 'closed',
	);  
	$new_sub_id = wp_insert_post( $post );
	update_post_meta( $new_sub_id, 'wps_forum_id', $_POST['forum_id'] );
	echo $new_sub_id;
}

/* DELETE FORUM SUB */
function wps_ajax_subs_forum_unsubscribe() {
	$id = $_POST['sub_id'];
	if ($id):
		global $current_user;
		$sub = get_post($id);
		if ($sub->post_author == $current_user->ID):
			if (wp_delete_post($id, true)):
				echo 'success';
			else:
				echo 'delete failed: '.$id;
			endif;
		else:
			echo 'not owner';
		endif;
	else:
		echo 'no id';
	endif;
}

?>