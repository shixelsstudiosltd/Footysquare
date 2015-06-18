<?php
$count_unread_msg=0;
if (AUTH_KEY):

	$mail_items = array();

	// If friends with this user, or the user, get activity
	if ($current_user->ID == $user_id):

        $term = isset($_GET['term']) ? $_GET['term'] : false;

        if (!$term):

            $sql = "SELECT p.* FROM ".$wpdb->prefix."posts p
                LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_ID
                WHERE p.post_type = 'wps_mail'
                  AND m.meta_key = 'wps_mail_recipients' AND m.meta_value LIKE '%%%s%%'
                ORDER BY ID DESC
                LIMIT 0,1000";
            $loop = $wpdb->get_results($wpdb->prepare($sql, $current_user->user_login));

        else:

            $sql = "SELECT p.* FROM ".$wpdb->prefix."posts p
                LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_ID
                LEFT JOIN ".$wpdb->prefix."comments c ON p.ID = c.comment_post_ID
                WHERE p.post_type = 'wps_mail'
                  AND m.meta_key = 'wps_mail_recipients' AND m.meta_value LIKE '%%%s%%'
                  AND (p.post_title LIKE '%%%s%%' OR p.post_content LIKE '%%%s%%' OR c.comment_content LIKE '%%%s%%')
                ORDER BY ID DESC
                LIMIT 0,1000";
            $loop = $wpdb->get_results($wpdb->prepare($sql, $current_user->user_login, $term, $term, $term));

        endif;


        $hidden_count = 0; // Keep track of hidden messages
        $unread_messages = false;

        foreach ($loop as $the_post):

			$mail_item = array();
			$mail_item['ID'] = $the_post->ID;
			$mail_item['post_author'] = $the_post->post_author;
			$mail_item['post_name'] = $the_post->post_name;
			$mail_item['post_title'] = $the_post->post_title;
			$mail_item['post_title_lower'] = strtolower($the_post->post_title);
			$mail_item['post_date'] = $the_post->post_date;
			$mail_item['post_date_gmt'] = $the_post->post_date_gmt;
			$mail_item['post_content'] = $the_post->post_content;

			$recipients = get_post_meta( $the_post->ID, 'wps_mail_recipients', true );

			if (!empty($recipients)):
				$mail_item['recipients'] = array_unique($recipients);
			else:
				$mail_item['recipients'] = '';
			endif;

			$unread = get_post_meta( $the_post->ID, 'wps_mail_unread', true );
			if ($unread && in_array($current_user->user_login, $unread)):
				$mail_item['unread'] = true;
                $unread_messages = true;
			else:
				$mail_item['unread'] = false;
			endif;

            // Check if removed (hidden)
            $hidden_list = get_post_meta($the_post->ID, 'wps_mail_hidden_list', true);
            if (!$hidden_list) $hidden_list = array();
            if (in_array($current_user->ID, $hidden_list)):
                $mail_item['hidden'] = true;
                $hidden_count++;
            else:
                $mail_item['hidden'] = false;
            endif;

            // Get latest comment date
            $sql = "SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC LIMIT 0,1";
            $comment = $wpdb->get_row($wpdb->prepare($sql, $mail_item['ID']));
            if ($comment):
                $mail_item['post_date'] = $comment->comment_date;
                $mail_item['post_date_gmt'] = $comment->comment_date_gmt;
            endif;

            // Add mail item
            $mail_items[$the_post->ID] = $mail_item;

        endforeach;

		if ( !empty( $mail_items ) ):

			// Sort mail items unread, read, age
			$sort = array();
			foreach($mail_items as $k=>$v) {
			    $sort['unread'][$k] = $v['unread'];
			    $sort['post_date_gmt'][$k] = $v['post_date_gmt'];
			    $sort['ID'][$k] = $v['ID'];
			}
			array_multisort($sort['unread'], SORT_DESC, $sort['post_date_gmt'], SORT_DESC, $sort['ID'], SORT_DESC, $mail_items);

            $html .= '<div id="wps_actions_div">';

                $hidden_status = get_user_meta($current_user->ID, 'wps_mail_show_hidden', true);
                if ($hidden_count):
                    if ($hidden_status):
                        $html .= '<a href="javascript:void(0)" id="wps_mail_show_hidden" class="wps_mail_hidden" style="display:none">'.$show_hidden_text.'</a>';
                        $html .= '<a href="javascript:void(0)" id="wps_mail_hide_hidden" class="wps_mail_hidden">'.$hide_hidden_text.'</a>';
                    else:
                        $html .= '<a href="javascript:void(0)" id="wps_mail_show_hidden" class="wps_mail_hidden">'.$show_hidden_text.'</a>';
                        $html .= '<a href="javascript:void(0)" id="wps_mail_hide_hidden" class="wps_mail_hidden" style="display:none">'.$hide_hidden_text.'</a>';
                    endif;
                endif;

                if ($unread_messages && $mark_all_read_text):
                        $html .= '<a href="javascript:void(0)" id="wps_mail_mark_all_read">'.$mark_all_read_text.'</a>';            
                endif;

            $html .= '</div>';

			$html .= '<div class="wps_mail_posts">';

		    foreach ($mail_items as $mail_item):
				
				$sql = "SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date ASC";
				$comments = $wpdb->get_results($wpdb->prepare($sql, $mail_item['ID']));
				
				if ($comments):
					$comment_count = count($comments);
				else:
					$comment_count = 0;
				endif;
				$created = sprintf($date_format, human_time_diff(strtotime($mail_item['post_date_gmt']), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);


				$mail_html = '';

				$mail_html .= '<div id="wps_mail_'.$mail_item['ID'].'" class="wps_mail_post';
					if ($mail_item['unread']) { $count_unread_msg++;}
					if ($mail_item['unread']) $mail_html .= ' wps_mail_post_unread';
					if ($mail_item['hidden']) $mail_html .= ' wps_mail_post_hidden';
					$mail_html .= '"';
                    if ($mail_item['hidden'] && !$hidden_status) $mail_html .= ' style="display:none;"';
                    $mail_html .= '>';
                    // Hide/restore
                    if ($mail_item['hidden']):
                        $mail_html .= '<div class="wps_mail_restore">';
                            $mail_html .= '<img class="wps_mail_restore_icon" title="'.__('Restore message', WPS2_TEXT_DOMAIN).'" rel="'.$mail_item['ID'].'" style="cursor:pointer;height:12 px;width:12px;" src="'.plugins_url('../../wp-symposium-pro/forums/images/reply.png', __FILE__).'" />';
                    else:
                        $mail_html .= '<div class="wps_mail_remove">';
                            $mail_html .= '<img class="wps_mail_remove_icon" title="'.__('Hide message', WPS2_TEXT_DOMAIN).'" rel="'.$mail_item['ID'].'" style="cursor:pointer;height:12 px;width:12px;" src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                    endif;

                    $mail_html .= '</div>';
					$mail_html .= '<div class="wps_mail_recipients">';
						if ($mail_item['recipients']):
							$count=0;
							foreach ($mail_item['recipients'] as $mail_item_recipient):
								if ($mail_item_recipient != $current_user->user_login):
									$count++;
									$recipient = get_user_by('login', $mail_item_recipient);
									$mail_html .= '<div class="wps_mail_recipient">';
									if ($recipient):
										$mail_html .= wps_display_name(array('user_id'=>$recipient->ID, 'link'=>1));
									endif;
									$mail_html .= '</div>';
								endif;
							endforeach;
							if (!$count) $mail_html .= '<div class="wps_mail_recipient">-</div>';
						endif;
					$mail_html .= '</div>';
					$mail_html .= '<div class="wps_mail_title">';
						$url = wps_curPageURL();
						$mail_html .= '<a href="'.$url.wps_query_mark($url).'mail='.$mail_item['ID'].'">'.esc_attr($mail_item['post_title']).'</a>';
					$mail_html .= '</div>';
					$mail_html .= '<div class="wps_mail_count">'.$comment_count.'</div>';
					$mail_html .= '<div class="wps_mail_freshness">'.$created.'</div>';
				$mail_html .= '</div>';

				$mail_html = apply_filters( 'wps_mail_post_item', $mail_html, $atts, $mail_item, $comment_count, $created, $hidden_status );
				$html .= $mail_html;

			endforeach;

			$html .= '</div>';

		else:

			$html .= '<p>'.$label_nomail.'</p>';

		endif;

	endif;

endif;

?>