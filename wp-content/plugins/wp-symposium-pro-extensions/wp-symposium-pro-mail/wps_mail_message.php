<?php

if (AUTH_KEY):

	// Start Post
	$post_html = '';

	$mail = get_post($post_id);

	$post_comments = wp_count_comments($post_id);
	$num_comments = $post_comments->total_comments;
	if ( $num_comments == 0 ) {
		$comments_count = __('No replies');
	} elseif ( $num_comments > 1 ) {
		$comments_count = sprintf(__('%d replies', WPS2_TEXT_DOMAIN), $num_comments);
	} else {
		$comments_count = __('1 reply', WPS2_TEXT_DOMAIN);
	}

	// Take off unread flag for this user
	$unread = get_post_meta( $post_id, 'wps_mail_unread', true );
	if ($unread):
		$new_list = array();
		foreach ($unread as $user_login):
			if ($current_user->user_login != $user_login) $new_list[] = $user_login;
		endforeach;
		update_post_meta ( $post_id, 'wps_mail_unread', $new_list);
	endif;

	$recipients = get_post_meta( $mail->ID, 'wps_mail_recipients', true );
	if (!empty($recipients)):

        if (in_array($current_user->user_login, $recipients) || current_user_can('manage_options')):

            $post_html = apply_filters( 'wps_mail_message_pre_filter', $post_html, $atts, $mail, $recipients );

            // Title
            $header_html = '<h2 class="wps_mail_message_post_title">';
            $header_html .= $mail->post_title.' ('.$comments_count.')</h2>';
            $header_html = apply_filters( 'wps_mail_message_post_title_filter', $header_html, $atts, $mail );
            $post_html .= $header_html;

            // Initial post
            $initial_html = '<div class="wps_mail_message_comment" style="padding-left: '.($comment_avatar_size+10).'px;">';

                $initial_html .= '<div class="wps_mail_message_comment_author" style="max-width: '.($comment_avatar_size).'px; margin-left: -'.($comment_avatar_size+10).'px;">';
                    $initial_html .= '<div class="wps_mail_message_comment_author_avatar">';
                        $initial_html .= user_avatar_get_avatar( $mail->post_author, $comment_avatar_size );
                    $initial_html .= '</div>';
                    $initial_html .= '<div class="wps_mail_message_comment_author_display_name">';
                        $initial_html .= wps_display_name(array('user_id'=>$mail->post_author, 'link'=>1));
                    $initial_html .= '</div>';
                    $initial_html .= '<div class="wps_mail_message_comment_author_freshness">';
                        $initial_html .= sprintf($date_format, human_time_diff(strtotime($mail->post_date_gmt), current_time('timestamp', 1)));
                    $initial_html .= '</div>';
                $initial_html .= '</div>';

                $initial_html .= '<div class="wps_forum_post_comment_content">';

                    $initial_html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($mail->post_content)))));

                    // Filter for handling anything else
                    // Passes $initial_html, shortcodes options ($atts), mail post ($mail), message ($mail->post_content))
                    $initial_html = apply_filters( 'wps_mail_item_filter', $initial_html, $atts, $mail, $mail->post_content );

                $initial_html .= '</div>';

            $initial_html .= '</div>';

            $post_html .= $initial_html;

            // Published comments
            $sql = "SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date ASC";
            $comments = $wpdb->get_results($wpdb->prepare($sql, $post_id));

            if ($comments):

                $post_html .= '<div id="wps_forum_post_comments">';

                    foreach($comments as $comment) :

                        $comment_html = '';

                        $comment_html .= '<div class="wps_mail_message_comment" style="padding-left: '.($comment_avatar_size+10).'px;">';

                            $comment_html .= '<div class="wps_mail_message_comment_author" style="max-width: '.($comment_avatar_size).'px; margin-left: -'.($comment_avatar_size+10).'px;">';
                                $comment_html .= '<div class="wps_mail_message_comment_author_avatar">';
                                    $comment_html .= user_avatar_get_avatar( $comment->user_id, $comment_avatar_size );
                                $comment_html .= '</div>';
                                $comment_html .= '<div class="wps_mail_message_comment_author_display_name">';
                                    $comment_html .= wps_display_name(array('user_id'=>$comment->user_id, 'link'=>1));
                                $comment_html .= '</div>';
                                $comment_html .= '<div class="wps_mail_message_comment_author_freshness">';
                                    $comment_html .= sprintf($date_format, human_time_diff(strtotime($comment->comment_date_gmt), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
                                $comment_html .= '</div>';
                            $comment_html .= '</div>';

                            $comment_html .= '<div class="wps_forum_post_comment_content">';

                                $comment_html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($comment->comment_content)))));

                                // Filter for handling anything else
                                // Passes $comment_html, shortcodes options ($atts), mail comment ($comment), message ($comment->comment_content))
                                $comment_html = apply_filters( 'wps_mail_item_comment_filter', $comment_html, $atts, $comment, $comment->comment_content );

                            $comment_html .= '</div>';

                        $comment_html .= '</div>';

                        $comment_html = apply_filters( 'wps_mail_post_comment_filter', $comment_html, $atts, $comment, $comment->comment_content );

                        $post_html .= $comment_html;

                    endforeach;

                $post_html .= '</div>';

            endif;

            $post_html = apply_filters( 'wps_mail_message_post_filter', $post_html, $atts, $recipients );

        endif;

	endif;

	$html .= $post_html;

endif;

?>