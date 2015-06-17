<?php
// AJAX functions for mail
add_action( 'wp_ajax_wps_mail_popup', 'wps_mail_popup' ); 
add_action( 'wp_ajax_wps_mail_remove', 'wps_mail_remove' ); 
add_action( 'wp_ajax_wps_mail_restore', 'wps_mail_restore' ); 
add_action( 'wp_ajax_wps_mail_show_hidden', 'wps_mail_show_hidden' ); 
add_action( 'wp_ajax_wps_mail_hide_hidden', 'wps_mail_hide_hidden' ); 
add_action( 'wp_ajax_wps_mail_new_recipients', 'wps_mail_new_recipients' ); 
add_action( 'wp_ajax_wps_mail_delete_recipient', 'wps_mail_delete_recipient' ); 
add_action( 'wp_ajax_wps_mail_mark_all_read', 'wps_mail_mark_all_read' ); 
add_action( 'wp_ajax_wps_alerts_mail_unread', 'wps_alerts_mail_unread' ); 

/* CHECK COUNT OF UNREAD (FOR FLAG) */
function wps_alerts_mail_unread() {

    global $current_user, $post;
    $unread_count=0;

	if ( is_user_logged_in() ):    
    
        $args = array(
            'post_type' => 'wps_mail',
            'orderby' => 'ID',
            'order' => 'DESC',
            'post_status' => 'publish',
            'posts_per_page' => 1000,
            'meta_query' => array(
                array( 
                    'key' => 'wps_mail_recipients',
                    'value' => $current_user->user_login,
                    'compare' => 'LIKE'
                ),
                array( 
                    'key' => 'wps_mail_unread',
                )
            ),
        );

        $posts = get_posts($args);
        
        $unread_count = 0; // unread count
        
        foreach ($posts as $mail):
    
            if ($mail->wps_mail_unread && in_array($current_user->user_login, $mail->wps_mail_unread)):
                $unread_count++;
            endif;

        endforeach;

        wp_reset_query();
    
    endif;
    
    echo $unread_count;
    exit;
    
}
/* MARK ALL AS READ */
function wps_mail_mark_all_read() {

	global $current_user, $post;

	if ( is_user_logged_in() ):
    
        // Get all mail to this user
		$args = array(
		   	'post_type' => 'wps_mail',
		   	'orderby' => 'ID',
		   	'order' => 'DESC',
		   	'posts_per_page' => -1,
			'meta_query' => array(
		        array( 
		            'key' => 'wps_mail_recipients',
		            'value' => $current_user->user_login,
		            'compare' => 'LIKE'
		        )
		    )				
		);
		$loop = new WP_Query($args); 
    
        while ( $loop->have_posts() ) : $loop->the_post();

			$unread = get_post_meta( $post->ID, 'wps_mail_unread', true );
			if ($unread && in_array($current_user->user_login, $unread)):

                if(($key = array_search($current_user->user_login, $unread)) !== false) {
                    unset($unread[$key]);
                    update_post_meta( $post->ID, 'wps_mail_unread', $unread );
                }

            endif;

		endwhile;
    
        wp_reset_query();
    
	endif;
    exit;

}

/* DELETE RECIPIENT */
function wps_mail_delete_recipient() {

	global $current_user;

	if ( is_user_logged_in() ):
    
        $post_id = $_POST['post_id'];
        $recipient_login = $_POST['recipient_login'];
        $recipients_list = get_post_meta( $post_id, 'wps_mail_recipients', true );

        if(($key = array_search($recipient_login, $recipients_list)) !== false) {
            unset($recipients_list[$key]);
            update_post_meta( $post_id, 'wps_mail_recipients', $recipients_list );
        }
    
	endif;
    exit;

}

/* ADD NEW RECIPIENT */
function wps_mail_new_recipients() {

	global $current_user;

	if ( is_user_logged_in() ):
    
        $post_id = $_POST['post_id'];
        $recipients = $_POST['new_recipients'];
        $recipients_list = get_post_meta( $post_id, 'wps_mail_recipients', true );
    
        foreach ($recipients as $user_login):
            array_push($recipients_list, $user_login);
            update_post_meta( $post_id, 'wps_mail_recipients', $recipients_list );
        endforeach;
    
	endif;
    exit;

}

/* SHOW HIDDEN */
function wps_mail_show_hidden() {

	global $current_user;

	if ( is_user_logged_in() ):

        update_user_meta($current_user->ID, 'wps_mail_show_hidden', true);
    
	endif;
    exit;

}

/* HIDE HIDDEN */
function wps_mail_hide_hidden() {

	global $current_user;

	if ( is_user_logged_in() ):

        delete_user_meta($current_user->ID, 'wps_mail_show_hidden');
    
	endif;
    exit;

}

/* RESTORE MESSAGE */
function wps_mail_restore() {

	global $current_user;

	if ( is_user_logged_in() ):

		$the_post = $_POST;

		$mail_id = $the_post['mail_id'];
    
        $hidden_list = get_post_meta($mail_id, 'wps_mail_hidden_list', true);
        if (!$hidden_list) $hidden_list = array();
        $hidden_list = array_diff($hidden_list, $current_user->ID);
        update_post_meta( $mail_id, 'wps_mail_hidden_list', $hidden_list );
        echo $mail_id;
    
	endif;
    exit;

}

/* REMOVE MESSAGE */
function wps_mail_remove() {

	global $current_user;

	if ( is_user_logged_in() ):

		$the_post = $_POST;

		$mail_id = $the_post['mail_id'];
    
        $hidden_list = get_post_meta($mail_id, 'wps_mail_hidden_list', true);
        if (!$hidden_list) $hidden_list = array();
        array_push($hidden_list, $current_user->ID);
        update_post_meta( $mail_id, 'wps_mail_hidden_list', $hidden_list );
        echo $mail_id;
    
	endif;
    exit;

}

/* SEND MAIL VIA POPUP */
function wps_mail_popup() {

	global $current_user;

	if ( is_user_logged_in() ):

		$the_post = $_POST;

		$recipient = $the_post['recipient'];
		$message = $the_post['message'];
		$title = (strlen($message) > 50) ? substr($message, 0, 25).'...' : $message;

		$post = array(
		  'post_title'     => $title,
		  'post_content'   => $message,
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

			$recipient_user = get_user_by('id', $recipient);

			array_push($recipients_list, $recipient_user->user_login);

			// Add unread recipients (excluding current user)
			update_post_meta( $new_id, 'wps_mail_unread', $recipients_list );
			// Add current user and set recipients
			array_push($recipients_list, $current_user->user_login);
			update_post_meta( $new_id, 'wps_mail_recipients', $recipients_list );

			// Any further actions?
			do_action( 'wps_mail_post_add_hook', $the_post, false, $new_id );

			echo $new_id;

		endif;

	endif;

}

?>