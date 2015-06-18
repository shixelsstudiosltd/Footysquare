<?php
																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_favourites($atts) {

    
    $html = '';
    
    if ( is_user_logged_in() ):
    
        global $current_user;
    
        $user_id = $current_user->ID;
        $this_user = $current_user->ID;

        // Shortcode parameters
        extract( shortcode_atts( array(
            'go_to' => __('View...', WPS2_TEXT_DOMAIN),
            'no_favorites_text' => __('You have no favorites, click the star beside activity posts to add them.', WPS2_TEXT_DOMAIN),
            'link' => true,
            'avatar_size' => 0,
            'date_format' => __('%s ago', WPS2_TEXT_DOMAIN),            
            'before' => '',
            'after' => '',
        ), $atts, 'wps_lounge' ) );

        $favs = get_user_meta($current_user->ID, 'wps_favourites', true);
        if ($favs):
    
            $html .= '<div class="wps_favourites">';
    
            arsort($favs);
            $shown_count = 0;

            foreach($favs as $fav):
    
                $item = get_post($fav);
    
                $item_html = '<div class="wps_favourite_item" id="wps_favourite_'.$fav.'" style="position:relative;padding-left: '.($avatar_size+10).'px">';

                    $item_html .= '<div id="wps_favourite_'.$fav.'_content" class="wps_favourite_content">';

                        // Link to post
                        if ( wps_using_permalinks() ):	
                            $u = get_user_by('id', $item->post_author);
                            $parameters = sprintf('%s?view=%d', $u->user_login, $item->ID);
                            $permalink = get_permalink(get_option('wpspro_profile_page'));
                            $permalink = $permalink.$parameters;
                        else:
                            $parameters = sprintf('user_id=%d&view=%d', $item->post_author, $item->ID);
                            $permalink = get_permalink(get_option('wpspro_profile_page'));
                            $permalink = $permalink.'&'.$parameters;
                        endif;
                        $item_html .= '<div class="wps_favourite_link"><a href="'.$permalink.'">'.$go_to.'</a></div>';    
    
                        // Settings
                        $settings = '<div class="wps_activity_favourite_icon" style="display:none">';
                            $settings .= '<img style="height:15px;width:15px;" class="wps_favourite" data-state="fav" rel="'.$item->ID.'" src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                        $settings .= '</div>';
                        $item_html .= $settings;
                        
                        // Avatar
                        $item_html .= '<div class="wps_favourite_item_avatar" style="float: left; margin-left: -'.($avatar_size+10).'px">';
                            $item_html .= user_avatar_get_avatar($item->post_author, $avatar_size);
                        $item_html .= '</div>';

                        // Meta
                        if ($item->post_type == 'wps_activity'):
                
                            $recipients = '';
                            $item_html .= '<div class="wps_favourite_item_meta">';
                                $item_html .= wps_display_name(array('user_id'=>$item->post_author, 'link'=>$link));
                                $target_ids = get_post_meta( $item->ID, 'wps_target', true );
                                if (is_array($target_ids)):
                                    $c=0;
                                    $recipients = ' &rarr; ';
                                    foreach ($target_ids as $target_id):
                                        if ( $target_id != $item->post_author):
                                            if ($c) $recipients .= ', ';
                                            $recipients .= wps_display_name(array('user_id'=>$target_id, 'link'=>$link));
                                            $c++;
                                        endif;
                                    endforeach;	
                                else:
                                    if ( $target_ids != $item->post_author):
                                        $recipient_display_name = wps_display_name(array('user_id'=>$target_ids, 'link'=>$link));
                                        if ($recipient_display_name):
                                            $recipients = ' &rarr; '.$recipient_display_name;
                                        endif;
                                    endif;
                                endif;

                                // In case of changes
                                $recipients = apply_filters( 'wps_activity_item_recipients_filter', $recipients, $atts, $target_ids, $item->ID, $user_id, $this_user );
                                $item_html .= $recipients;

                                // Date
                                $item_html .= '<br />';
                                $item_html .= '<div class="wps_ago">'.sprintf($date_format, human_time_diff(strtotime($item->post_date), current_time('timestamp', 0)), WPS2_TEXT_DOMAIN).'</div>';

                                // Any more meta?
                                // Passes $item_html, shortcodes options ($atts), current post ID ($item->ID), user page ($user_id), current users ID ($this_user)
                                $item_html = apply_filters( 'wps_activity_item_meta_filter', $item_html, $atts, $item->ID, $user_id, $this_user );

                                /* The activity pst */

                                // Shortern if necessary and applicable
                                $post_words = wps_bbcode_replace(convert_smilies(wps_make_clickable(wpautop(esc_html($item->post_title)))));
                                $post_words = str_replace('[a]', '<a', $post_words);
                                $post_words = str_replace('[a2]', '>', $post_words);
                                $post_words = str_replace('[/a]', '</a>', $post_words);

                                if (strpos($post_words, '[q]') !== false && strpos($post_words, '[/q]') === false) $post_words .= '[/q]';
                                $p = str_replace(': ', '<br />', $post_words);
                                $p = str_replace('[q]', '<div class="wps_quoted_content">', $p);
                                $p = str_replace('[/q]', '</div>', $p);
                                $p = str_replace('[p]', '<div class="wps_p_content">', $p);
                                $p = str_replace('[/p]', '</div>', $p);

                                $p = str_replace('<p>', '', $p);
                                $p = str_replace('</p>', '', $p);
                                $p = '<div id="activity_item_'.$item->ID.'">'.$p.'</div>';

                                // Check for any items (attachments)
                                if ($i=strpos($p, '[items]')):
                                    $attachments_list = substr($p, $i+7, strlen($p)-($i+7));

                                    $attachments = explode(',', $attachments_list);
                                    $attachment_html = '';
                                    foreach ($attachments as $attachment):
                                        $attachment_html .= '<div class="wps_activity_item_attachment wps_activity_item_attachment_item">'.wp_get_attachment_image($attachment, 'thumbnail');            
                                            $image_src = wp_get_attachment_image_src( $attachment, 'full' );
                                            $attachment_html .= '<div data-width="'.$image_src[1].'" data-height="'.$image_src[2].'" class="wps_activity_item_attachment_full">'.$image_src[0].'</div>';
                                        $attachment_html .= '</div>'; 
                                    endforeach;
                                    $attachment_html .= '<div style="clear:both"></div>';
                                    $p = str_replace('[items]', '', $p);
                                    $p = str_replace($attachments_list, '', $p);
                                    $p .= '</div>'.$attachment_html;
                                endif;

                                $item_html .= '<div class="wps_activity_item_post" id="activity_item_'.$item->ID.'">'.$p.'</div>';

                                // Filter for handling anything else
                                // Passes $item_html, shortcodes options ($atts), current post ID ($item->ID), post title ($item->post_stitle), user page ($user_id), current users ID ($this_user)
                                $item_html = apply_filters( 'wps_activity_item_filter', $item_html, $atts, $item->ID, $item->post_title, $user_id, $this_user, $shown_count );

                            $item_html .= '</div>';

                        endif; // End of activity type

                    $item_html .= '</div>';

                $item_html .= '</div>'; // end of favourite item
    
                $html .= $item_html; // add to output
    
                $shown_count++;

            endforeach;

            $html .= '</div>'; 
    
        else:
    
            $html .= $no_favorites_text;

        endif;
    
    endif;
    
    if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

    return $html;    
        
}


if (!is_admin()) {
	add_shortcode(WPS_PREFIX.'-favorites', 'wps_favourites');
}



?>
