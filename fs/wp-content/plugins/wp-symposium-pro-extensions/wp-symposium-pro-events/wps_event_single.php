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
	$header_html = '<h2 id="wps_event_post_title">'.$event->post_title.'</h2>';
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
		if ($wps_event_end) $date_time_html .= ' &rarr; '.date('F j, Y', strtotime($wps_event_end));
		if ($wps_event_end_time) $date_time_html .= ', '.$wps_event_end_time;
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

			$initial_html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop($event->post_content))));

			// Filter for handling anything else
			// Passes $initial_html, shortcodes options ($atts), event post ($event), message ($event->post_content))
			$initial_html = apply_filters( 'wps_event_item_filter', $initial_html, $atts, $event, $event->post_content );

		$initial_html .= '</div>';

	$initial_html .= '</div>';

	$post_html .= $initial_html;

	// Published comments
	$sql = "SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date ASC";
	$comments = $wpdb->get_results($wpdb->prepare($sql, $post_id));

	if ($comments):

		$post_html .= '<div id="wps_event_post_comments">';

			foreach($comments as $comment) :

				$comment_html = '';

				$comment_html .= '<div class="wps_event_message_comment" style="padding-left: '.($comment_avatar_size+10).'px;">';

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