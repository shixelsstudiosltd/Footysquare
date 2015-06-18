<?php

if (AUTH_KEY):

	// Start Post
	$post_html = '';

	$event = get_post($post_id);

	$post_comments = wp_count_comments($post_id);
	$num_comments = $post_comments->total_comments;
	if ( $num_comments == 0 ) {
		$comments_count = __('No replies');
	} elseif ( $num_comments > 1 ) {
		$comments_count = sprintf(__('%d replies', WPS2_TEXT_DOMAIN), $num_comments);
	} else {
		$comments_count = __('1 reply', WPS2_TEXT_DOMAIN);
	}

	$post_html = apply_filters( 'wps_event_pre_filter', $post_html, $atts, $current_user->ID );

	// Title
	$header_html = '<h2 id="wps_event_post_title">'.$event->post_title;
	$url = wps_curPageURL();
	$url .= wps_query_mark($url).'event_action=edit';
	if ($current_user->ID == $event->post_author || current_user_can('manage_options'))
		$header_html .= ' [<a href="'.$url.'">'.__('Edit').'</a>]';
	$header_html .= '</h2>';
	$header_html = apply_filters( 'wps_event_post_title_filter', $header_html, $event, $atts, $current_user->ID );
	$post_html .= $header_html;

	// Date/Time
	$date_time_html = '<div id="wps_event_datetime">';
		$wps_event_start = get_post_meta( $post_id, 'wps_event_start', true );
		$wps_event_start_time = get_post_meta( $post_id, 'wps_event_start_time', true );
		if ($wps_event_start) $date_time_html .= date('F j, Y', strtotime($wps_event_start));
		if ($wps_event_start_time) $date_time_html .= ', '.$wps_event_start_time;
		$wps_event_end = get_post_meta( $post_id, 'wps_event_end', true );
		$wps_event_end_time = get_post_meta( $post_id, 'wps_event_end_time', true );
		if ($wps_event_end && $wps_event_end != $wps_event_start) {
			$date_time_html .= ' &rarr; '.date('F j, Y', strtotime($wps_event_end));
			if ($wps_event_end_time) $date_time_html .= ', '.$wps_event_end_time;
		} else {
			if ($wps_event_end_time) $date_time_html .= ' &rarr; '.$wps_event_end_time;
		}
	$date_time_html .= '</div>';
	$post_html .= $date_time_html;
	
	// Featured Image
	if (has_post_thumbnail($post_id)):
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_id) );
		$post_html .= '<div id="wps_event_featured_image"><img class="full-width" src="'.$feat_image.'" alt="'.esc_html($event->post_title).'" title="'.esc_html($event->post_title).'" /></div>';
	endif;

	// Initial post
	$initial_html = '<div class="wps_event_message_comment" style="padding-left: '.($avatar_size+10).'px;">';

		$initial_html .= '<div class="wps_event_message_comment_author" style="max-width: '.($avatar_size).'px; margin-left: -'.($avatar_size+10).'px;">';
			$initial_html .= '<div class="wps_event_message_comment_author_avatar">';
				$initial_html .= user_avatar_get_avatar( $event->post_author, $avatar_size );
			$initial_html .= '</div>';
			$initial_html .= '<div class="wps_event_message_comment_author_display_name">';
				$initial_html .= wps_display_name(array('user_id'=>$event->post_author, 'link'=>1));
			$initial_html .= '</div>';
			$initial_html .= '<div class="wps_event_message_comment_author_freshness">';
				$initial_html .= sprintf($date_format, human_time_diff(strtotime($event->post_date_gmt), current_time('timestamp', 1)));
			$initial_html .= '</div>';
		$initial_html .= '</div>';

		$initial_html .= '<div class="wps_event_post_content">';

			$initial_html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($event->post_content)))));

			// Filter for handling anything else
			// Passes $initial_html, shortcodes options ($atts), event post ($event), message ($event->post_content))
			$initial_html = apply_filters( 'wps_event_item_filter', $initial_html, $atts, $event, $event->post_content );

		$initial_html .= '</div>';

	$initial_html .= '</div>';

	$post_html .= $initial_html;

	// Add a comment?
	if ($event->comment_status == 'open'):

		$post_html .= '<form enctype="multipart/form-data" id="wps_event_theuploadform">';
			$post_html .= '<input type="hidden" name="post_id" value="'.$event->ID.'" />';
			$post_html .= '<input type="hidden" name="action" value="wps_calendar_comment_add" />';
			$post_html .= '<input type="hidden" id="wps_calendar_plugins_url" value="'.plugins_url( '', __FILE__ ).'" />';
			$post_html .= '<input title="'.$attachment_label.'" id="wps_event_comment_image_upload" name="wps_event_comment_image_upload[]" multiple size="50" type="file" />';
			$post_html .= '<textarea id="wps_event_comment" name="wps_event_comment" autocomplete="off"></textarea>';
			$post_html .= '<input type="submit" rel="'.$event->ID.'" id="wps_event_comment_button" class="wps_submit" value="'.$comment_label.'" />';
		$post_html .= '</form>';

	endif;

	// Published comments
	$sql = "SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d and comment_approved='1' ORDER BY comment_date ASC";
	$comments = $wpdb->get_results($wpdb->prepare($sql, $post_id));

	if ($comments):

		$post_html .= '<div id="wps_event_post_comments">';

			foreach($comments as $comment) :

				$comment_html = '';

				$comment_html .= '<div class="wps_event_message_comment" id="comment_'.$comment->comment_ID.'" style="padding-left: '.($comment_avatar_size+10).'px;">';

					// Settings
					if ($event->post_author == $current_user->ID || current_user_can('manage_options')):
						$comment_html .= '<div class="wps_calendar_settings">';
							$comment_html .= '<img style="height:15px;width:15px;" src="'.plugins_url('images/wrench'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" />';
						$comment_html .= '</div>';
						$comment_html .= '<div class="wps_calendar_settings_options" style="display:none">';
							if ($delete_label) $comment_html .= '<a class="wps_calendar_settings_delete" rel="'.$comment->comment_ID.'" href="javascript:void(0);">'.$delete_label.'</a>';
						$comment_html .= '</div>';
					endif;

					// Avatar
					$comment_html .= '<div class="wps_event_message_comment_author" style="max-width: '.($comment_avatar_size).'px; margin-left: -'.($comment_avatar_size+10).'px;">';
						$comment_html .= '<div class="wps_event_message_comment_author_avatar">';
							$comment_html .= user_avatar_get_avatar( $comment->user_id, $comment_avatar_size );
						$comment_html .= '</div>';
						$comment_html .= '<div class="wps_event_message_comment_author_display_name">';
							$comment_html .= wps_display_name(array('user_id'=>$comment->user_id, 'link'=>1));
						$comment_html .= '</div>';
						$comment_html .= '<div class="wps_event_message_comment_author_freshness">';
							$comment_html .= sprintf($date_format, human_time_diff(strtotime($comment->comment_date_gmt), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
						$comment_html .= '</div>';
					$comment_html .= '</div>';

					$comment_html .= '<div class="wps_event_post_comment_content">';

						$comment_html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($comment->comment_content)))));

						// Any attachments?
					    if (get_comment_meta($comment->comment_ID, 'wps_attachment_id', true)):

							$comment_html .= '<div id="wps_calendar_attachment_dialog"></div>';

					        $attachments = get_posts( array(
					            'post_type' => 'attachment',
					            'include' => get_comment_meta($comment->comment_ID, 'wps_attachment_id', true),
					            'post_id' => $comment->comment_ID,
					        ) );

					        $atts_to_pass = $atts ? $atts : '';

					        if ( $attachments ) {
					            foreach ( $attachments as $attachment ) {

					                // Get extensions and file type
					                $file_ext = strtolower(substr(strrchr(get_attached_file($attachment->ID),'.'),1));
					                
					                // Images, setting defaults (use filters to change)
					                $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
					                $valid_image_exts = apply_filters( 'wps_calendar_attachments_valid_image_extensions_filter', $valid_image_exts, $atts_to_pass );

					                if (in_array($file_ext, $valid_image_exts)):
					                    $comment_html .= '<div class="wps_calendar_item_attachment">'.wp_get_attachment_image($attachment->ID, 'thumbnail');            
					                        $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
					                        $thumbimg = wp_get_attachment_image_src( $attachment->ID, 'full' );
					                        $comment_html .= '<div data-width="'.$thumbimg[1].'" data-height="'.$thumbimg[2].'" class="wps_calendar_item_attachment_full">' . $thumbimg[0] . '</div>';
					                    $comment_html .= '</div>';
					                endif;

					                // Other (documents), setting defaults (use filters to change)
					                $valid_document_exts = array('txt', 'rtf', 'pdf', 'zip');
					                $valid_document_exts = apply_filters( 'wps_calendar_attachments_valid_document_extensions_filter', $valid_document_exts, $atts_to_pass );
					                if (in_array($file_ext, $valid_document_exts)):
					                    $comment_html .= '<a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">'.basename(get_attached_file($attachment->ID)).'</a>';
					                endif;

					            }
					        };

					    endif; // No attachments for this comment

						// Filter for handling anything else
						// Passes $comment_html, shortcodes options ($atts), event comment ($comment), message ($comment->comment_content))
						$comment_html = apply_filters( 'wps_event_item_comment_filter', $comment_html, $atts, $comment, $comment->comment_content );

					$comment_html .= '</div>';

				$comment_html .= '</div>';

				$comment_html = apply_filters( 'wps_event_post_comment_filter', $comment_html, $atts, $comment, $comment->comment_content );

				$post_html .= $comment_html;

			endforeach;

		$post_html .= '</div>';

	endif;

	$post_html = apply_filters( 'wps_event_message_post_filter', $post_html, $atts, $current_user->ID );

	$html .= $post_html;

endif;

?>