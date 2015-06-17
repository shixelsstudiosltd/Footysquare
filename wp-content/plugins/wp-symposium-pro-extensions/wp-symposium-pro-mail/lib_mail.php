<?php
while(!is_file('wp-config.php')){
	if(is_dir('../')) chdir('../');
	else die('Could not find WordPress config file.');
}
include_once( 'wp-config.php' );

$action = isset($_POST['action']) ? $_POST['action'] : false;

if ($action) {

	global $current_user;
	get_currentuserinfo();

	if ( is_user_logged_in() ) {

		/* ADD MESSAGE */
		if ($action == 'wps_mail_post_add') {

			$the_post = $_POST;

			$post = array(
			  'post_title'     => $the_post['wps_mail_title'],
			  'post_content'   => $the_post['wps_mail_textarea'],
			  'post_status'    => 'publish',
			  'author'		   => $current_user->ID,
			  'post_type'      => 'wps_mail',
			  'post_author'    => $current_user->ID,
			  'ping_status'    => 'closed',
			  'comment_status' => 'open',
			);  
			$new_id = wp_insert_post( $post );

			if ($new_id):

				$recipients_list = array();

				if (isset($the_post['wps_default_mail_recipient_user']) && $the_post['wps_default_mail_recipient_user'] != ''):
					// default recipient
					array_push($recipients_list, $the_post['wps_default_mail_recipient_user']);
				else:
					// chosen recipient
					foreach ($the_post['wps_mail_recipients'] as $selectedOption):
						if ($selectedOption) array_push($recipients_list, $selectedOption);
					endforeach;
				endif;

				// Add unread recipients (excluding current user)
				update_post_meta( $new_id, 'wps_mail_unread', $recipients_list );
				// Add current user and set recipients
				array_push($recipients_list, $current_user->user_login);
				update_post_meta( $new_id, 'wps_mail_recipients', $recipients_list );

				// Any further actions?
				do_action( 'wps_mail_post_add_hook', $the_post, $_FILES, $new_id );

			endif;

		}

		/* ADD COMMENT */
		if ($action == 'wps_mail_comment_add') {

			$the_comment = $_POST;

			$data = array(
			    'comment_post_ID' => $the_comment['post_id'],
			    'comment_content' => $the_comment['wps_mail_comment'],
			    'comment_type' => '',
			    'comment_parent' => 0,
			    'comment_author' => $current_user->user_login,
			    'comment_author_email' => $current_user->user_email,
			    'user_id' => $current_user->ID,
			    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
			    'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
			    'comment_approved' => $status,
			);

			$new_id = wp_insert_comment($data);

			if ($new_id):

				// Set as unread for all recipients (apart from current user)
				$recipients_list = get_post_meta( $the_comment['post_id'], 'wps_mail_recipients', true );
				$unread_list = array();
				if ($recipients_list):
					foreach ($recipients_list as $user_login):
						if ($current_user->user_login != $user_login)
							array_push($unread_list, $user_login);
					endforeach;
				else:
					array_push($unread_list, $current_user->user_login);
				endif;
				
                // Set as unread
                update_post_meta( $the_comment['post_id'], 'wps_mail_unread', $unread_list );
            
                // Remove hidden flags
                delete_post_meta( $the_comment['post_id'], 'wps_mail_hidden_list' );

				// Any further actions?
				do_action( 'wps_mail_comment_add_hook', $the_comment, $_FILES, $the_comment['post_id'], $new_id );

			endif;

		}

	}


}

?>
