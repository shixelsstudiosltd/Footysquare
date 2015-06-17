<?php
// AJAX functions
add_action( 'wp_ajax_wps_gallery_item_delete', 'wps_gallery_item_delete' ); 
add_action( 'wp_ajax_wps_gallery_set_featured', 'wps_gallery_set_featured' ); 
add_action( 'wp_ajax_wps_gallery_delete', 'wps_gallery_delete' ); 
add_action( 'wp_ajax_wps_gallery_create', 'wps_gallery_create' ); 
add_action( 'wp_ajax_wps_gallery_activity_comment_add', 'wps_gallery_activity_comment_add' ); 
add_action( 'wp_ajax_wps_gallery_comment_settings_delete', 'wps_gallery_comment_settings_delete' ); 


/* ADD COMMENT */
function wps_gallery_activity_comment_add() {

    global $current_user;
	$data = array(
	    'comment_post_ID' => $_POST['post_id'],
	    'comment_content' => $_POST['comment_content'],
	    'comment_type' => '',
	    'comment_parent' => 0,
	    'comment_author' => $current_user->user_login,
	    'user_id' => $current_user->ID,
	    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
	    'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
	    'comment_approved' => 1,
	);

	$new_id = wp_insert_comment($data);

	if ($new_id):

        // Send alert (if in use, informing album owner and participants)
        if (post_type_exists('wps_alerts')):

            // Get original post author
            $the_comment = get_comment($new_id);
            $post_id = $_POST['post_id'];
            $original_post = get_post($post_id);

            $recipients = array();

            // Add original post author and target
            $recipients['author'] = (int)$original_post->post_author;

            // Add all comment authors
            $args = array(
                'post_id' => $post_id
            );
            $comments = get_comments($args);
            if ($comments):
                foreach($comments as $comment):
                    if ($comment->comment_author):
                        if (is_string($comment->comment_author)):
                            $u = get_user_by('login', $comment->comment_author);
                            $comment_author = $u->ID;
                        else:
                            $comment_author = $comment->comment_author;
                        endif;
                        $recipients['comment '.$comment->comment_ID] = $comment_author;
                    endif;
                endforeach;
            endif;

            $sent = array();
            global $current_user;
            get_currentuserinfo();

            if ($recipients):
                foreach ($recipients as $key=>$value):
    
                    if ($value):
    
                        if ( (int)$value != (int)$current_user->ID && !in_array($value, $sent) ):

                            $status = 'publish';
                            if (get_user_meta($value, 'wps_activity_subscribe', true) != 'off') $status = 'pending';

                            array_push($sent, $value);

                            $subject = sprintf(__('New comment on %s', WPS2_TEXT_DOMAIN), $original_post->post_title);
                            $subject = get_bloginfo('name').': '.$subject;

                            $content = '';

                            $content = apply_filters( 'wps_alert_before', $content );

                            $target = get_user_by('id', $value);
                            $content .= '<h1>'.$target->display_name.'</h1>';

                            $author = get_user_by('login', $the_comment->comment_author);
                            $msg = sprintf(__('A new comment from %s on %s.', WPS2_TEXT_DOMAIN), $author->display_name, $original_post->post_title);
                            $content .= '<p>'.$msg.'</p>';
                            $content .= '<p><em>'.$the_comment->comment_content.'</em></p>';

                            $parameters = sprintf('user_id=%d&gallery_id=%d', (int)$original_post->post_author, $post_id);
                            $permalink = get_permalink(get_option('wpspro_gallery_page'));
                            $url = $permalink.wps_query_mark($permalink).$parameters;
                            $content .= '<p><a href="'.$url.'">'.$url.'</a></p>';

                            $content = apply_filters( 'wps_alert_after', $content );

                            $post = array(
                                'post_title'		=> $subject,
                                'post_excerpt'		=> $msg,
                                'post_content'		=> $content,
                                'post_status'   	=> $status,
                                'post_type'     	=> 'wps_alerts',
                                'post_author'   	=> (int)$the_comment->comment_author,
                                'ping_status'   	=> 'closed',
                                'comment_status'	=> 'closed',
                            );  
                            $new_alert_id = wp_insert_post( $post );

                            $recipient_user = get_user_by ('id', $value); // Get user by ID of email recipient
                            update_post_meta( $new_alert_id, 'wps_alert_recipient', $recipient_user->user_login );	
                            update_post_meta( $new_alert_id, 'wps_alert_target', 'profile' );
                            update_post_meta( $new_alert_id, 'wps_alert_parameters', $parameters );	

                            if ($status == 'publish'):
                                update_post_meta( $new_alert_id, 'wps_alert_failed_datetime', current_time('mysql', 1) );
                                update_post_meta( $new_alert_id, 'wps_alert_note', __('Chosen not to receive', WPS2_TEXT_DOMAIN) );
                            endif;

                            do_action( 'wps_alert_add_hook', $target->ID, $new_alert_id, $url, $msg );

                        endif;

                    endif;

                endforeach;

            endif;

        endif;

        // Any further actions?
		do_action( 'wps_gallery_activity_comment_add_hook', $_POST, $new_id );
		echo $new_id;
		
	else:
		echo 0;
	endif;
    
}

/* DELETE COMMENT */
function wps_gallery_comment_settings_delete() {

	$id = $_POST['id'];
	if ($id):
		global $current_user;
		$comment = get_comment($id);
		if ($comment->user_id == $current_user->ID || current_user_can('manage_options')):
			if (wp_delete_comment($id, true)):
				echo 'success';
			else:
				echo 'failed to delete comment '.$id;
			endif;
		else:
			echo 'not owner';
		endif;
	endif;

}

/* CREATE GALLERY */
function wps_gallery_create() {
    
    global $current_user;
    
    $title = $_POST['wps_create_album'];
    if ($title == '') $title == __('Edit album to change the name', WPS2_TEXT_DOMAIN);
    
    $post = array(
      'post_content'   => '',
      'post_name'      => sanitize_title_with_dashes($title),
      'post_title'     => $title,
      'post_status'    => 'publish',
      'post_type'      => 'wps_gallery',
      'ping_status'    => 'closed',
      'comment_status' => 'closed'
    );  
    $new_gallery_id = wp_insert_post( $post );

    // Create updated date
    update_post_meta( $new_gallery_id, 'wps_gallery_updated', current_time('Y-m-d H:i:s', 0) );
    
    echo 'user_id='.$current_user->ID.'&gallery_id='.$new_gallery_id;
    exit();
    
}


/* DELETE ITEM */
function wps_gallery_item_delete() {

	$the_post = $_POST;
	if (isset($the_post['id']) && $the_post['id'] != ''):

		wp_delete_attachment($the_post['id'], true);
        
	else:
		echo __('Error: no ID passed', WPS2_TEXT_DOMAIN);
	endif;
	exit();
}

/* FEATURED IMAGE */
function wps_gallery_set_featured() {

	$the_post = $_POST;
	if (isset($the_post['id']) && $the_post['id'] != ''):

		update_post_meta($the_post['post_id'], '_thumbnail_id', $the_post['id']);
        
	else:
		echo __('Error: no ID passed', WPS2_TEXT_DOMAIN);
	endif;
	exit();
}

/* DELETE ALBUM */
function wps_gallery_delete() {

	$the_post = $_POST;
	if (isset($the_post['id']) && $the_post['id'] != ''):

        $id = $the_post['id'];
		$gallery = get_post($id);
        global $current_user;
		if ($gallery->post_author == $current_user->ID || current_user_can('manage_options')):
			if (wp_delete_post($id, true)):
				echo 'ok';
			else:
				echo 'delete failed: '.$id;
			endif;
		else:
			echo 'not owner, owner is '.$sub->post_author;
		endif;
    
	else:
		echo __('Error: no ID passed', WPS2_TEXT_DOMAIN);
	endif;
	exit();
}



?>
