<?php
// Add crowd activity
add_filter('wps_activity_items_filter', 'wps_add_crowds_activity_items_filter', 10, 4);
function wps_add_crowds_activity_items_filter ($activity, $atts, $user_id, $current_user_id) {

    if (!isset($_GET['group_id'])): // don't show on group page

        global $wpdb, $current_user;

        $count = (isset($atts['count'])) ? $atts['count'] : 100;

        // Get all posts to crowds, including from others
        $sql = "SELECT p.ID, p.post_title, p.post_author, p.post_date, c.comment_date FROM ".$wpdb->prefix."posts p 
            LEFT JOIN ".$wpdb->prefix."comments c ON p.ID = c.comment_post_ID
            LEFT JOIN ".$wpdb->prefix."postmeta m2 ON p.ID = m2.post_id
            WHERE p.post_type = %s
            AND m2.meta_key = 'wps_target_type'
            AND m2.meta_value = 'crowd'
            AND p.post_status = 'publish'
            ORDER BY p.ID DESC
            LIMIT 0, %d";

        $results = $wpdb->get_results($wpdb->prepare($sql, 'wps_activity', $count));

        if ($results):

            // Get this user's details
            $the_user = get_user_by ('id', $user_id);

            foreach ($results as $r):

                $target_crowd_id = get_post_meta( $r->ID, 'wps_target', true );
                $crowd = get_post($target_crowd_id);
                if ($crowd):
                    $crowd_targets = get_post_meta( $crowd->ID, 'wps_crowd_recipients', true );
                    if ($crowd_targets):
                        $the_post = get_post($r->ID);
                        if ($the_post && $the_user && in_array($the_user->user_login, $crowd_targets) && is_array($activity)):

                            if ($r->post_date > $r->comment_date):
                                array_push($activity, array('ID' => $r->ID, 'date' => $r->post_date, 'is_sticky' => 1));
                            else:
                                array_push($activity, array('ID' => $r->ID, 'date' => $r->comment_date, 'is_sticky' => 1));
                            endif;

                        endif;
                    endif;
                endif;

            endforeach;
        endif;
    
    endif;

	return $activity;
}

// Add crowd activity single post view
add_filter('wps_activity_single_item_filter', 'wps_add_crowds_activity_item_filter', 10, 4);
function wps_add_crowds_activity_item_filter ($activity, $atts, $user_id, $current_user_id) {

    // Get this user's details
    $the_user = get_user_by ('id', $user_id);

	if (isset($_GET['view'])):
		$target_crowd_id = get_post_meta( $_GET['view'], 'wps_target', true );
		$crowd = get_post($target_crowd_id);
		if ($crowd):
			$crowd_targets = get_post_meta( $crowd->ID, 'wps_crowd_recipients', true );
			if ($crowd_targets):
				$the_post = get_post($_GET['view']);
				if (in_array($the_user->user_login, $crowd_targets) && is_array($activity)):
					array_push($activity, array('ID' => $_GET['view'], 'date' => $the_post->post_date, 'is_sticky' => 1));
				endif;
			endif;
		endif;
	endif;

	return $activity;
}


// Add crowds to Who to? drop down list
add_filter('wps_activity_post_form_whoto_options_filter', 'wps_addto_activity_post_form_whoto_options_crowds', 10, 1);
function wps_addto_activity_post_form_whoto_options_crowds ($options) {

	global $current_user;
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'post_title',
		'order'            => 'ASC',
		'post_type'        => 'wps_crowd',
		'post_status'      => 'publish',
		'author'		 	=> $current_user->ID,
	);
	$crowds = get_posts($args);

	if ($crowds):
		foreach ($crowds as $crowd):
		    $options .= '<option value="crowd-'.$crowd->ID.'">'.$crowd->post_title.'</option>';
		endforeach;
	endif;
	$options .= '<option value="manage-crowds">'.__('Manage lists...', WPS2_TEXT_DOMAIN).'</option>';

	return $options;

}

// We need to over-ride Who to? with recipient (ie. crowd)
add_action( 'wps_whoto_update_target_hook', 'wps_override_whoto_update_target_hook_crowd', 10, 3 );
function wps_override_whoto_update_target_hook_crowd($the_post, $the_files, $new_id) {
    $who_to_selected_value = $the_post['activity_whoto_select'];
	if (strpos($who_to_selected_value, 'crowd-') !== false):
	    $crowd_id = explode('-', $who_to_selected_value);
	    update_post_meta( $new_id, 'wps_target_type', 'crowd' ); // Change activity to type crowd
	    update_post_meta( $new_id, 'wps_target', $crowd_id[1] ); // Set target to crowd ID
	endif;
}

// Change target on profile activity to crowd
add_filter('wps_activity_item_recipients_filter', 'wps_change_to_crowd_activity_item_recipients_filter', 10, 6);
function wps_change_to_crowd_activity_item_recipients_filter ($recipients, $atts, $target_ids, $item_id, $user_id, $current_user_id) {
	if ( get_post_meta($item_id, 'wps_target_type', true) == 'crowd'):
		$crowd = get_post($target_ids);
		$recipients = get_post_meta($crowd->ID, 'wps_crowd_recipients', true);
		if ($recipients):
			$recipients = ' &rarr; '.sprintf('<a class="hastip" href="javascript:void(0)" title="%s" style="cursor:default">'.$crowd->post_title.'</a>', implode(",", $recipients));
		else:
			$recipients = '';
		endif;
	endif;
	return $recipients;
}

// Insert alerts for all crowd members for new post
add_action("wps_activity_post_add_hook", 'wps_alert_to_crowd_members', 10, 3);
function wps_alert_to_crowd_members( $post_vars, $files_var, $new_id ) {

	if (isset($post_vars['activity_whoto_select']) && substr($post_vars['activity_whoto_select'], 0, 6) == 'crowd-'):

		$crowd_in = explode('-', $post_vars['activity_whoto_select']);
		$crowd = get_post($crowd_in[1]);

		$recipients = array();
		$members = get_post_meta( $crowd_in[1], 'wps_crowd_recipients', true );

		// Get all targets from crowd
		foreach ($members as $member):
			if ($member != $post_vars['wps_activity_post_author']) {
				array_push($recipients, $member);
			}
		endforeach;

		if (post_type_exists('wps_alerts') && count($recipients) > 0):

			$sent = array();
			foreach ($recipients as $target_id):

				echo $target_id.'<br>';

				global $current_user;

				if ( (int)$target_id != (int)$current_user->ID && !in_array($target_id, $sent) ):

					array_push($sent, $target_id);

					$title = get_bloginfo('name').': '.__('New activity post', WPS2_TEXT_DOMAIN);
					$content = '';

					$content = apply_filters( 'wps_alert_before', $content );

					$recipient = get_user_by ('login', $target_id); // Get user by ID of post recipient
					$content .= '<h1>'.$recipient->display_name.'</h1>';

					$author = get_user_by('id', $post_vars['wps_activity_post_author']);
					$msg = sprintf(__('You have a new post from %s.', WPS2_TEXT_DOMAIN), $author->display_name);
					$content .= '<p>'.$msg.'</p>';
					$content .= '<p><em>'.$post_vars['wps_activity_post'].'</em></p>';
					
					if ( wps_using_permalinks() ):	
						$u = get_user_by('id', $post_vars['wps_activity_post_author']);
						$parameters = sprintf('%s?view=%d', urlencode($u->user_login), $new_id);
						$permalink = get_permalink(get_option('wpspro_profile_page'));
						$url = $permalink.$parameters;
					else:
						$parameters = sprintf('user_id=%d&view=%d', urlencode($post_vars['wps_activity_post_author']), $new_id);
						$permalink = get_permalink(get_option('wpspro_profile_page'));
						$url = $permalink.'&'.$parameters;
					endif;
					$content .= '<p><a href="'.$url.'">'.$url.'</a></p>';

					$content = apply_filters( 'wps_alert_after', $content );

					$post = array(
						'post_title'		=> $title,
					  	'post_excerpt'		=> $msg,
					  	'post_content'		=> $content,
					  	'post_status'   	=> 'pending',
					  	'post_type'     	=> 'wps_alerts',
					  	'post_author'   	=> $post_vars['wps_activity_post_author'],
					  	'ping_status'   	=> 'closed',
					  	'comment_status'	=> 'closed',
					);  
					$new_alert_id = wp_insert_post( $post );

					update_post_meta( $new_alert_id, 'wps_alert_recipient', $recipient->user_login );	
					update_post_meta( $new_alert_id, 'wps_alert_target', 'crowd' );
					update_post_meta( $new_alert_id, 'wps_alert_parameters', $parameters );	

					do_action( 'wps_alert_add_hook', $recipient->ID, $new_alert_id, $url, $msg );

				endif;

			endforeach;

		endif;

	endif;

}

// Hook into wps_activity_comment_add_hook to send alerts for new comments
// excluding the current user

add_action( 'wps_activity_comment_add_hook', 'wps_crowd_comment_add_alerts', 10, 2 );
function wps_crowd_comment_add_alerts($the_post, $new_id) {

	if (post_type_exists('wps_alerts')):

		// Get original post author
		$the_comment = get_comment($new_id);
		$post_id = $the_comment->comment_post_ID;
		$original_post = get_post($post_id);

		// alerts only added it activity type is default (ie. not set)
		// other types must add alerts themselves
		if ($original_post->wps_target_type == 'crowd'):

			$recipients = array();

			// Add target of original post
			$target = get_post_meta($post_id, 'wps_target', true);
			$get_recipient = get_user_by('id', $original_post->wps_target);
			if ($get_recipient) {
				$recipients['target'] = $target;
			}

			// Any changes to recipients target list?
			$recipients = apply_filters('wps_activity_comment_add_alerts_recipients_filter', $recipients, $original_post, $post_id, $new_id);

			// Add original post author and target
			$recipients['author'] = (int)$original_post->post_author;

			// Add all comment authors
			$args = array(
				'post_id' => $post_id
			);
			$comments = get_comments($args);
			if ($comments):
				foreach($comments as $comment):
					if ($comment->comment_author)
						$recipients['comment '.$comment->comment_ID] = (int)$comment->comment_author;
				endforeach;
			endif;

			$sent = array();
			global $current_user;
			get_currentuserinfo();

			if ($recipients):
				foreach ($recipients as $key=>$value):

					if ($value):

						if ( (int)$value != (int)$current_user->ID && !in_array($value, $sent) ):

							if (get_user_meta($value, 'wps_activity_subscribe', true) != 'off'):

								array_push($sent, $value);

								if ($key == 'author'):
									$subject = __('New comment on your post', WPS2_TEXT_DOMAIN);
								else:
									$subject = __('New comment', WPS2_TEXT_DOMAIN);
								endif;
								$subject = get_bloginfo('name').': '.$subject;

								$content = '';

								$content = apply_filters( 'wps_alert_before', $content );

								$target = get_user_by('id', $value);
								$content .= '<h1>'.$target->display_name.'</h1>';

								$author = get_user_by('login', $the_comment->comment_author);
								$msg = sprintf(__('A new comment from %s.', WPS2_TEXT_DOMAIN), $author->display_name);
								$content .= '<p>'.$msg.'</p>';
								$content .= '<p><em>'.$the_comment->comment_content.'</em></p>';

								$parameters = sprintf('user_id=%d&view=%d', (int)$original_post->post_author, $post_id);
								$permalink = get_permalink(get_option('wpspro_profile_page'));
								$url = $permalink.wps_query_mark($permalink).$parameters;
								$content .= '<p><a href="'.$url.'">'.$url.'</a></p>';

								$content .= '<p><strong>'.__('Original Post', WPS2_TEXT_DOMAIN).'</strong></p>';
								$content .= '<p>'.$original_post->post_title.'</p>';

								$content = apply_filters( 'wps_alert_after', $content );

								$post = array(
									'post_title'		=> $subject,
								  	'post_excerpt'		=> $msg,
								  	'post_content'		=> $content,
								  	'post_status'   	=> 'pending',
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

								do_action( 'wps_alert_add_hook', $target->ID, $new_alert_id, $url, $msg );

							endif;

						endif;

					endif;

				endforeach;
				
			endif;

		endif;

	endif;

}

?>