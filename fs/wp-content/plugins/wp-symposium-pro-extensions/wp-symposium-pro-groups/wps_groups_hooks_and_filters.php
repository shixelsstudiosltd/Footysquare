<?php

// Add Group ID to post form
add_filter( 'wps_activity_post_pre_form_filter', 'add_group_to_wps_activity_post_pre_form_filter', 10, 4 );
function add_group_to_wps_activity_post_pre_form_filter ($form_html, $atts, $user_id, $current_user_id ) {
	if (isset($_GET['group_id'])):
		$group = get_post($_GET['group_id']);
		$form_html .= '<input type="hidden" name="wps_activity_group_id" value="'.$group->ID.'" />';
	endif;
	return $form_html;
}

// Update new activity posts to group
add_action( 'wps_activity_post_add_hook', 'wps_change_to_group_activity_post_add_hook', 5, 3 ); // Priority over other post_add_hook actions
function wps_change_to_group_activity_post_add_hook($the_post, $the_files, $new_id) {
	if (isset($the_post['wps_activity_group_id']) && !isset($the_post['activity_whoto_select'])):
		update_post_meta( $new_id, 'wps_target_type', 'group' ); // Change activity to type group
		update_post_meta( $new_id, 'wps_target', $the_post['wps_activity_group_id'] );
	endif;
}

// Add groups to Who to? drop down list
add_filter('wps_activity_post_form_whoto_options_filter', 'wps_addto_activity_post_form_whoto_options', 10, 1);
function wps_addto_activity_post_form_whoto_options ($options) {

	$groups = wps_get_groups();
	global $current_user;
	foreach ($groups as $group):
		$is_member = wps_is_group_member($current_user->ID, $group->ID);
		if ($is_member['status']):
			$selected = (isset($_GET['group_id']) && $_GET['group_id'] == $group->ID) ? ' SELECTED' : '';
		    $options .= '<option value="group-'.$group->ID.'"'.$selected.'>'.$group->post_title.'</option>';
		endif;
	endforeach;

	return $options;

}

// Because Who to? plugin will change wps_change_to_group_activity_post_add_hook, we need to over-ride Who to? with recipient
add_action( 'wps_whoto_update_target_hook', 'wps_override_whoto_update_target_hook', 10, 3 );
function wps_override_whoto_update_target_hook($the_post, $the_files, $new_id) {
    $who_to_selected_value = $the_post['activity_whoto_select'];
	if (strpos($who_to_selected_value, 'group-') !== false):
	    $group_id = explode('-', $who_to_selected_value);
	    update_post_meta( $new_id, 'wps_target_type', 'group' ); // Change activity to type group
	    update_post_meta( $new_id, 'wps_target', $group_id[1] ); // Set target to group ID
	endif;
}

add_filter('wps_activity_item_recipients_filter', 'wps_change_to_group_activity_item_recipients_filter', 10, 6);
function wps_change_to_group_activity_item_recipients_filter ($recipients, $atts, $target_ids, $item_id, $user_id, $current_user_id) {
	if ( get_post_meta($item_id, 'wps_target_type', true) == 'group'):
		if (!isset($_GET['group_id'])):
			$recipients = ' &rarr; '.wps_get_group_name($target_ids);
		else:
			$recipients = ' &rarr; '.wps_get_group_name($target_ids, false);
		endif;
	endif;
	return $recipients;
}

// Insert alerts for all group members for new post
add_action("wps_activity_post_add_hook", 'wps_alert_to_group_members', 10, 3);
function wps_alert_to_group_members( $post_vars, $files_var, $new_id ) {

	$recipients = array();

	if (isset($post_vars['wps_activity_group_id'])):

		$members = wps_get_group_members($post_vars['wps_activity_group_id'], 'publish');
		foreach ($members as $member):
			if ($member != $post_vars['wps_activity_post_author']) {
				array_push($recipients, $member);
			}
		endforeach;

		if (post_type_exists('wps_alerts') && count($recipients) > 0):

			$sent = array();
			global $current_user;
			foreach ($recipients as $target_id):

				if ( (int)$target_id != (int)$current_user->ID && !in_array($target_id, $sent) ):

					array_push($sent, $target_id);

					$title = get_bloginfo('name').': '.__('New group activity post', WPS2_TEXT_DOMAIN);
					$content = '';

					$content = apply_filters( 'wps_alert_before', $content );

					$recipient = get_user_by ('id', $target_id); // Get user by ID of post recipient
					$content .= '<h1>'.$recipient->display_name.'</h1>';

					$author = get_user_by('id', $post_vars['wps_activity_post_author']);
					$msg = sprintf(__('You have a new post in the group from %s.', WPS2_TEXT_DOMAIN), $author->display_name);
					$content .= '<p>'.$msg.'</p>';
					$content .= '<p><em>'.$post_vars['wps_activity_post'].'</em></p>';
					
					$parameters = sprintf('group_id=%d&view=%d', $post_vars['wps_activity_group_id'], $new_id);
					$permalink = get_permalink(get_option('wpspro_group_page'));
					$url = $permalink.wps_query_mark($permalink).$parameters;
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
					update_post_meta( $new_alert_id, 'wps_alert_target', 'group' );
					update_post_meta( $new_alert_id, 'wps_alert_parameters', $parameters );	

					do_action( 'wps_alert_add_hook', $recipient->ID, $new_alert_id, $url, $msg );

				endif;

			endforeach;

		endif;

	endif;

}

// Insert alerts for all group members for new comment
add_action("wps_activity_comment_add_hook", 'wps_alert_to_group_members_comment', 10, 2);
function wps_alert_to_group_members_comment( $the_post, $new_id ) {

	if (post_type_exists('wps_alerts')):

		// Get original post author
		$the_comment = get_comment($new_id);
		$post_id = $the_comment->comment_post_ID;
		$the_post = get_post($post_id);

		// only process for comments on group posts
		if ($the_post->wps_target_type == 'group'):

			$recipients = array();

			// Add original post author and target
			$recipients['author'] = (int)$the_post->post_author;

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

							array_push($sent, $value);

							if ($key == 'author'):
								$subject = __('New comment on your group post', WPS2_TEXT_DOMAIN);
							else:
								$subject = __('New comment on group post', WPS2_TEXT_DOMAIN);
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

							$parameters = sprintf('group_id=%d&view=%d', $the_post->wps_target, $post_id);
							$permalink = get_permalink(get_option('wpspro_group_page'));
							$url = $permalink.wps_query_mark($permalink).$parameters;
							$content .= '<p><a href="'.$url.'">'.$url.'</a></p>';

							$content .= '<p><strong>'.__('Original Post', WPS2_TEXT_DOMAIN).'</strong></p>';
							$content .= '<p>'.$the_post->post_title.'</p>';

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
							update_post_meta( $new_alert_id, 'wps_alert_target', 'group' );
							update_post_meta( $new_alert_id, 'wps_alert_parameters', $parameters );	

							do_action( 'wps_alert_add_hook', $target->ID, $new_alert_id, $url, $msg );

						endif;

					endif;

				endforeach;
				
			endif;

		endif;

	endif;

}


// Add group activity
add_filter('wps_activity_items_filter', 'wps_add_groups_activity_items_filter', 10, 6);
function wps_add_groups_activity_items_filter ($activity, $atts, $user_id, $current_user_id) {

	global $wpdb;

    // NB. include all posts to this group are included, from self and others
    
	$count = (isset($atts['count'])) ? $atts['count'] : 100;

	if (isset($_GET['group_id'])):
		$target = "AND m.meta_value = ".$_GET['group_id'];
	else:
		$target = '';
	endif;


    // Get all posts to this group, including from others
    $sql = "SELECT p.ID, p.post_date, p.post_author, c.comment_date, m.meta_value AS target_ids FROM ".$wpdb->prefix."posts p 
        LEFT JOIN ".$wpdb->prefix."comments c ON p.ID = c.comment_post_ID
        LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_id
        LEFT JOIN ".$wpdb->prefix."postmeta m2 ON p.ID = m2.post_id
        WHERE p.post_type = %s
        AND m.meta_key = 'wps_target'
        ".$target."		
        AND m2.meta_key = 'wps_target_type'
        AND m2.meta_value = 'group'
        AND p.post_status = 'publish'
        ORDER BY p.ID DESC
        LIMIT 0, %d";

    $results = $wpdb->get_results($wpdb->prepare($sql, 'wps_activity', $count));

    if ($results):

        foreach ($results as $r):

            $group_member = wps_is_group_member($current_user_id, $r->target_ids);
            if ($group_member['status']):
                if ($r->post_date > $r->comment_date):
                    array_push($activity, array('ID' => $r->ID, 'date' => $r->post_date, 'is_sticky' => 1));
                else:
                    array_push($activity, array('ID' => $r->ID, 'date' => $r->comment_date, 'is_sticky' => 1));
                endif;
            endif;

        endforeach;
    endif;

	return $activity;
}

// Add group activity single post view
add_filter('wps_activity_single_item_filter', 'wps_add_groups_activity_single_item_filter', 10, 6);
function wps_add_groups_activity_single_item_filter ($activity, $atts, $user_id, $this_user) {

	global $wpdb;

	if (isset($_GET['group_id']) && isset($_GET['view'])):

		$group_id = $_GET['group_id'];
		$post_id = $_GET['view'];
		$the_post = get_post($post_id);

		$group_member = wps_is_group_member($user_id, $group_id);
		if ($group_member['status']):
			@array_push($activity, array('ID' => $post_id, 'date' => $the_post->post_date, 'is_sticky' => 1));
		endif;

	endif;

	return $activity;
}


// Add link back to group, when on single post view
add_filter('wps_activity_pre_filter', 'wps_groups_activity_single_pre_filter', 10, 6);
function wps_groups_activity_single_pre_filter($items, $atts, $user_id, $this_user) {

    if (isset($_GET['group_id']) && isset($_GET['view'])):

        extract( shortcode_atts( array(
            'back_to' => false,
        ), $atts, 'wps_groups_activity_single_pre_filter' ) );

        if (!$back_to) $back_to = __('Back to %s...', WPS2_TEXT_DOMAIN);
    
        $items .= '<p>'.sprintf($back_to, wps_get_group_name($_GET['group_id'])).'</p>'.$items;

	endif;
    
    return $items;
}

?>