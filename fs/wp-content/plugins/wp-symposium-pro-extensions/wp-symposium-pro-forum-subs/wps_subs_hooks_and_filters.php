<?php
// Show subscriptions at forum level [wps-forums]
add_filter('wps_forum_name_filter', 'wps_forum_name_show_subs', 10, 3);
function wps_forum_name_show_subs($suffix, $forum_name, $term_id) {

    global $current_user;

    $args = array (
        'post_type'              => 'wps_forum_subs',
        'posts_per_page'         => -1,
        'author'			 	 => $current_user->ID,
    );

    $subs = get_posts($args);
    if ($subs):

        $has_subs = true;

        foreach ($subs as $sub):

            if ($term_id == get_post_meta($sub->ID, 'wps_forum_id', true)):
                $suffix .= ' <img src="'.plugins_url('images/email.png', __FILE__).'" class="wps_forum_subscribed_icon" title="'.__('Subscribed', WPS2_TEXT_DOMAIN).'" />';
                break;
            endif;

        endforeach;

    endif;  

    return $suffix;
    
}

// Show subscriptions at forum posts level [wps-forum]
add_filter('wps_forum_post_name_filter', 'wps_forum_post_name_show_subs', 10, 2);
function wps_forum_post_name_show_subs($forum_name, $post_id) {

    global $current_user;

    $args = array (
        'post_type'              => 'wps_subs',
        'posts_per_page'         => -1,
        'author'			 	 => $current_user->ID,
    );

    $subs = get_posts($args);
    if ($subs):

        $has_subs = true;

        foreach ($subs as $sub):

            if ($post_id == get_post_meta($sub->ID, 'wps_post_id', true)):
                $forum_name .= ' <img src="'.plugins_url('images/email.png', __FILE__).'" rel="'.$sub->ID.'" class="wps_forum_subscribed_icon wps_forum_unsubscribe_icon" title="'.__('Subscribed', WPS2_TEXT_DOMAIN).'" />';
                break;
            endif;

        endforeach;

    endif;  

    return $forum_name;
    
}

// Add unsubscribe to all in wps_usermeta_change
add_filter('wps_usermeta_change_filter', 'wps_subs_usermeta_extend', 10, 3);
function wps_subs_usermeta_extend($form_html, $atts, $user_id) {

	// Shortcode parameters
	extract( shortcode_atts( array(
		'subs_unsubscribe' => __('Tick to cancel all forum subscriptions', WPS2_TEXT_DOMAIN),
		'meta_class' => 'wps_usermeta_change_label',
	), $atts, 'wps_usermeta_change' ) );

	global $wpdb, $current_user;
	$sql = "SELECT ID FROM ".$wpdb->prefix."posts WHERE post_type = %s AND post_author = %d";
	$subs = $wpdb->query($wpdb->prepare($sql, 'wps_subs', $current_user->ID));
	$forum_subs = $wpdb->query($wpdb->prepare($sql, 'wps_forum_subs', $current_user->ID));

	if ($subs || $forum_subs):

		$form_html .= '<div class="wps_usermeta_change_item">';
		$form_html .= '<div class="'.$meta_class.'"><input type="checkbox" name="wpspro_subs_unsubscribe_all" /> '.$subs_unsubscribe.'</div>';
		$form_html .= '</div>';

	endif;

	return $form_html;

}

// Extend wps_usermeta_change save
add_action( 'wps_usermeta_change_hook', 'wps_subs_usermeta_extend_save', 10, 4 );
function wps_subs_usermeta_extend_save($user_id, $atts, $the_form, $the_files) {

	if (isset($the_form['wpspro_subs_unsubscribe_all'])):

		global $wpdb, $current_user;

		// Double check logged in
		if (is_user_logged_in()):

			// Delete all subscriptions
			$sql = "DELETE FROM ".$wpdb->prefix."posts WHERE post_type = %s AND post_author = %d";
			$wpdb->query($wpdb->prepare($sql, 'wps_subs', $current_user->ID));
			$wpdb->query($wpdb->prepare($sql, 'wps_forum_subs', $current_user->ID));

			// Shortcode parameters
			extract( shortcode_atts( array(
				'subs_unsubscribe_msg' => __('You will no longer receive any forum email alerts.', WPS2_TEXT_DOMAIN),
			), $atts, 'wps_usermeta' ) );

			echo '<div class="wps_success">'.$subs_unsubscribe_msg.'</div>';

		endif;

	endif;

}

// Add checkbox to new forum post
add_filter('wps_forum_post_post_form_filter', 'wps_subs_add_post_checkbox', 10, 3);
function wps_subs_add_post_checkbox($form_html, $atts, $user_id) {

	// Shortcode parameters
	extract( shortcode_atts( array(
		'comment_subscribe_msg' => __('Receive email when new comments are added', WPS2_TEXT_DOMAIN),
	), $atts, 'wps_subscribe_post' ) );

	$form_html .= '<div class="wps_subs_add_post_checkbox">';
	$form_html .= '<input type="checkbox" CHECKED name="wps_subs_add_post_checkbox" style="float: left" id="wps_subs_add_post_checkbox"> <label for="wps_subs_add_post_checkbox">'.$comment_subscribe_msg.'</a></div>';

	return $form_html;

}

// If checked, add subscription
add_action( 'wps_forum_post_add_hook', 'wps_subs_post_add_sub', 10, 3 );
function wps_subs_post_add_sub($the_post, $the_files, $new_id) {

	if (isset($the_post['wps_subs_add_post_checkbox']) && $the_post['wps_subs_add_post_checkbox']):

		global $current_user;
		get_currentuserinfo();

		$post = array(
			'post_title'		=> $current_user->user_login,
		  	'post_status'   	=> 'publish',
		  	'post_type'     	=> 'wps_subs',
		  	'post_author'   	=> $current_user->ID,
		  	'ping_status'   	=> 'closed',
		  	'comment_status'	=> 'closed',
		);  
		$new_sub_id = wp_insert_post( $post );

		update_post_meta( $new_sub_id, 'wps_post_id', $new_id );	

	endif;

}


// Hook into wps_forum_comment_add_hook to send email for new comments (unmoderated)
add_action( 'wps_forum_comment_add_hook', 'wps_subs_comment_add_alerts', 10, 4 );
function wps_subs_comment_add_alerts($the_comment, $the_files, $post_id, $new_id) {


	if ($the_comment['wps_forum_moderate'] != '1')
		wps_send_new_comment_alert($new_id);

}

// Send alert for new 'approved' comments, after moderation from unapproved
add_action('wp_set_comment_status', 'wps_comment_approved');
add_action('edit_comment', 'wps_comment_approved');
function wps_comment_approved ($comment_id) {

	wps_send_new_comment_alert($comment_id);

}

// Hook into wps_forum_post_add_hook to send email for new posts (unmoderated)
add_action( 'wps_forum_post_add_hook', 'wps_subs_post_add_alerts', 10, 3 );
function wps_subs_post_add_alerts($the_post, $the_files, $new_id) {

	if (post_type_exists('wps_alerts')):

		if ($the_post['wps_forum_moderate'] != '1')
			wps_send_new_post_alert($new_id);

	endif;

}

// Send alert for new 'publish'ed post, after moderation from pending
add_action( 'pending_to_publish', 'wps_post_published' );
function wps_post_published( $post ) {

    if ( $post->post_type == 'wps_forum_post')
		wps_send_new_post_alert($post->ID);

}


// Sends new post alert
function wps_send_new_post_alert($new_id) {

	if (post_type_exists('wps_alerts')):

		global $wpdb;
		$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE ID = %d";
		$new_post = $wpdb->get_row($wpdb->prepare($sql, $new_id));

		$recipients = array();
		$post_terms = wp_get_object_terms( $new_id, 'wps_forum' );
		$term = $post_terms[0];

        // Check for email to all (no opt-out), activated via WPS Pro->Forum Setup->Edit
        $wps_forum_email_all = wps_get_term_meta($term->term_id, 'wps_forum_email_all', true);
        if (!$wps_forum_email_all):
    
            // Send to subscribers
    
            $args = array (
                'post_type'              => 'wps_forum_subs',
                'posts_per_page'         => -1,
                'meta_query'             => array(
                    array(
                        'key'       => 'wps_forum_id',
                        'value'     => $term->term_id,
                        'type'		=> 'NUMERIC'
                    ),
                ),
            );

            $subs = get_posts( $args );
            if ($subs):
                foreach ($subs as $sub) {
                    $recipients['sub '.$sub->ID] = (int)$sub->post_author;
                }
            endif;
    
        else:
    
            // Send to all site members
    
            $sql = "SELECT ID FROM ".$wpdb->prefix."users";
            $subs = $wpdb->get_results($sql);
            foreach ($subs as $sub) {
                $recipients['sub '.$sub->ID] = (int)$sub->ID;
            }    
    
        endif;

		$sent = array();
		global $current_user;
		get_currentuserinfo();

		if ($recipients):

			foreach ($recipients as $key=>$value):

				if ( $value != $new_post->post_author && !in_array($value, $sent) ):

					array_push($sent, $value);
					$user = get_user_by('id', $value);

					$subject = __('New forum post', WPS2_TEXT_DOMAIN);
					$subject = get_bloginfo('name').': '.$subject;

					$content = '';

					$content = apply_filters( 'wps_alert_before', $content );
			
					$content .= '<h1>'.$term->name.'</h1>';

					$author = get_user_by('id', $new_post->post_author);
					$msg = sprintf(__('A new post from %s.', WPS2_TEXT_DOMAIN), $author->display_name);
					$content .= '<p>'.$msg.'</p>';

					$content .= '<h2>'.$new_post->post_title.'</h2>';
					$content .= '<p>'.$new_post->post_content.'</p>';

					$url = get_bloginfo('url').'/'.$term->slug.'/'.$new_post->post_name;
					$content .= '<p><a href="'.$url.'">'.$url.'</a></p>';

					$content = apply_filters( 'wps_alert_after', $content );

					wps_pro_insert_alert('forum', $subject, $content, $new_post->post_author, $value, '', $url, $msg, 'pending');

				endif;
				
			endforeach;
		endif;

	endif;

}

// Sends new comment alert
function wps_send_new_comment_alert($new_id) {

    global $current_user;
    
	if (post_type_exists('wps_alerts')):

		$the_comment = get_comment($new_id);
		if ($the_comment):

			$post_id = $the_comment->comment_post_ID;
            $private = get_comment_meta( $new_id, 'wps_private_post', true );
            $original_post = get_post($post_id);

			$post_terms = wp_get_object_terms( $post_id, 'wps_forum' );		
			$term = $post_terms[0];

			$recipients = array();

			$args = array (
				'post_type'              => 'wps_subs',
				'posts_per_page'         => -1,
				'meta_query'             => array(
					array(
						'key'       => 'wps_post_id',
						'value'     => $post_id,
						'type'		=> 'NUMERIC'
					),
				),
			);

			$subs = get_posts( $args );
			if ($subs):
				foreach ($subs as $sub) {
                    if (!$private || (int)$sub->post_author == $original_post->post_author) {
                        $recipients['sub '.$sub->ID] = (int)$sub->post_author;
                    }
				}
			endif;

			$sent = array();
			global $current_user;
			get_currentuserinfo();

			if ($recipients):

				foreach ($recipients as $key=>$value):

					if ( (int)$value != (int)$current_user->ID && !in_array($value, $sent) ):

						array_push($sent, $value);
						$user = get_user_by('id', $value);

						$subject = __('New forum comment', WPS2_TEXT_DOMAIN);
						$subject = get_bloginfo('name').': '.$subject;

						$content = '';

						$content = apply_filters( 'wps_alert_before', $content );

						$author = get_user_by('login', $the_comment->comment_author);
						$msg = sprintf(__('A new reply from %s.', WPS2_TEXT_DOMAIN), $author->display_name);
						$content .= '<p>'.$msg.'</p>';
						$content .= '<p><em>'.$the_comment->comment_content.'</em></p>';

						$url = get_bloginfo('url').'/'.$term->slug.'/'.$original_post->post_name;
						$content .= '<p><a href="'.$url.'">'.$url.'</a></p>';

						$post_author = get_user_by('id', $original_post->post_author);
						$content .= '<p><strong>'.sprintf(__('Original Post by %s', WPS2_TEXT_DOMAIN), $post_author->display_name).'</strong></p>';
						$content .= '<h2>'.$original_post->post_title.'</h2>';
						$content .= '<p>'.$original_post->post_content.'</p>';

						$content = apply_filters( 'wps_alert_after', $content );

						wps_pro_insert_alert('reply', $subject, $content, $the_comment->comment_author, $value, '', $url, $msg, 'pending');

					endif;
				endforeach;
			endif;

		endif;

	endif;

}


?>