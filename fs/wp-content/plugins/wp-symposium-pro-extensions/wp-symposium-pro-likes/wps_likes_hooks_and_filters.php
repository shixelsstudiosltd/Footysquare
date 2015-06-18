<?php
// Add likes and dislikes (post)
add_filter('wps_activity_item_meta_filter', 'wps_add_likes_activity_items_filter', 10, 5);
function wps_add_likes_activity_items_filter ($item_html, $atts, $item_id, $user_id, $current_user_id) {

	// Shortcode parameters
	extract( shortcode_atts( array(
		'like_text' => __('Like', WPS2_TEXT_DOMAIN),
		'unlike_text' => __('Unlike', WPS2_TEXT_DOMAIN),
        'like_count_text_singular' => __('%d like', WPS2_TEXT_DOMAIN),
        'like_count_text_plural' => __('%d likes', WPS2_TEXT_DOMAIN),
		'dislike_text' => __('Dislike', WPS2_TEXT_DOMAIN),
		'undislike_text' => __('Undislike', WPS2_TEXT_DOMAIN),
        'dislike_count_text_singular' => __('%d dislike', WPS2_TEXT_DOMAIN),
        'dislike_count_text_plural' => __('%d dislikes', WPS2_TEXT_DOMAIN),
        'done_text' => __('Done!', WPS2_TEXT_DOMAIN), 
		'by_text' => __(' by ', WPS2_TEXT_DOMAIN),
        'more_text_singular' => __('and %d more...', WPS2_TEXT_DOMAIN),
        'more_text_plural' => __('and %d more...', WPS2_TEXT_DOMAIN),
		'or_text' => __(' or ', WPS2_TEXT_DOMAIN),
		'you_text' => __('you', WPS2_TEXT_DOMAIN),
        'max_names_to_show' => 3,
        'allow_unlikes' => true,
        'allow_undislikes' => true,
	), $atts, 'wps_likes_and_dislikes' ) );

    $allowed_likes_target = get_option('wps_allowed_likes_target');    
    $allowed_dislikes_target = get_option('wps_allowed_dislikes_target');
    $likes = get_post_meta( $item_id, 'wps_post_likes', true );
    $dislikes = get_post_meta( $item_id, 'wps_post_dislikes', true );

    $the_post = get_post($item_id);
    $show_likes_and_dislikes = false;
    if ( ($allowed_likes_target != 'nobody' || $allowed_dislikes_target != 'nobody') && $the_post->post_author != $current_user_id) $show_likes_and_dislikes = true;
    if ($likes || $dislikes) $show_likes_and_dislikes = true;
    
    if ($show_likes_and_dislikes):
    
        $item_html .= '<div class="wps_likes_and_dislikes">';

            $friend_status = wps_are_friends($user_id, $current_user_id);
            $is_friend = $friend_status['status'] == 'publish' ? true : false;
            $show_likes = true;
            if ( ($allowed_likes_target == 'nobody') || ($allowed_likes_target == 'friends' && !$is_friend) ) $show_likes = false; 
            $show_dislikes = true;
            if ( ($allowed_dislikes_target == 'nobody') || ($allowed_dislikes_target == 'friends' && !$is_friend) ) $show_dislikes = false; 

            // Like and Dislike (and Un...) links
            if ($the_post->post_author != $current_user_id):    

                $item_html .= '<div class="wps_likes_and_dislikes_actions">';

                    if ( (!$likes || !in_array($current_user_id, $likes)) && (!$dislikes || !in_array($current_user_id, $dislikes)) ):
                        if ($show_likes) $item_html .= '<a class="wps_like" data-done-text="'.$done_text.'" rel="'.$item_id.'" href="javascript:void(0);">'.$like_text.'</a>';
                        if ($show_likes && $show_dislikes) $item_html .= $or_text;
                        if ($show_dislikes) $item_html .= '<a class="wps_dislike" data-done-text="'.$done_text.'" rel="'.$item_id.'" href="javascript:void(0);">'.$dislike_text.'</a>';
                    endif;
                    if ($likes && in_array($current_user_id, $likes) && $show_likes && $allow_unlikes):
                        $item_html .= '<a class="wps_unlike" data-done-text="'.$done_text.'" rel="'.$item_id.'" href="javascript:void(0);">'.$unlike_text.'</a>';
                    endif;
                    if ($dislikes && in_array($current_user_id, $dislikes) && $show_dislikes && $allow_undislikes):
                        $item_html .= '<a class="wps_undislike" data-done-text="'.$done_text.'" rel="'.$item_id.'" href="javascript:void(0);">'.$undislike_text.'</a>';
                    endif;

                $item_html .= '</div>';
    
            endif;

            // Previous likes and dislikes
            if ($likes || $dislikes):

                $item_html .= '<div class="wps_likes_and_dislikes_counts">';

                    if ($likes && $show_likes):
                        $likes = array_reverse($likes);
                        $count = count($likes);
                        $label = $count > 1 ? $like_count_text_plural : $like_count_text_singular;
                        $item_html .= sprintf($label, $count).$by_text;
                        $shown = 0;
                        $tooltip = '';
                        $max_to_show = $count > $max_names_to_show ? $max_names_to_show : $count;
                        foreach ($likes as $user_id):
                            if ($shown < $max_to_show):
                                $shown++;
                                if ($shown > 1):
                                    if ($shown < $max_to_show):
                                        $item_html .= ', ';
                                    else:
                                        $item_html .= ' '.__('and', WPS2_TEXT_DOMAIN).' ';
                                    endif;
                                endif;
                                if ($user_id == $current_user_id):
                                    $item_html .= $you_text;
                                else:
                                    $item_html .= wps_display_name(array('user_id'=>$user_id, 'link'=>1));
                                endif;
                            endif;
                            $tooltip .= wps_display_name(array('user_id'=>$user_id, 'link'=>0)).'<br />';
                        endforeach;
                        $diff = $count-$shown;
                        if ($diff > 0):
                            $label = $diff > 1 ? $more_text_plural : $more_text_singular;
                            $item_html .= ' <span class="hastip wps_more_likes_dislikes" title="'.$tooltip.'">'.sprintf($label, $diff).'</span>';
                        endif;
                    endif;

                    if ($dislikes && $show_dislikes):
                        $dislikes = array_reverse($dislikes);
                        $count = count($dislikes);
                        $label = $count > 1 ? $dislike_count_text_plural : $dislike_count_text_singular;
                        if ($likes) $item_html .= ', ';
                        $item_html .= sprintf($label, $count).$by_text;
                        $shown = 0;
                        $tooltip = '';
                        $max_to_show = $count > $max_names_to_show ? $max_names_to_show : $count;
                        foreach ($dislikes as $user_id):
                            if ($shown < $max_to_show):
                                $shown++;
                                if ($shown > 1):
                                    if ($shown < $max_to_show):
                                        $item_html .= ', ';
                                    else:
                                        $item_html .= ' '.__('and', WPS2_TEXT_DOMAIN).' ';
                                    endif;
                                endif;
                                if ($user_id == $current_user_id):
                                    $item_html .= $you_text;
                                else:
                                    $item_html .= wps_display_name(array('user_id'=>$user_id, 'link'=>1));
                                endif;
                            endif;
                            $tooltip .= wps_display_name(array('user_id'=>$user_id, 'link'=>0)).'<br />';
                        endforeach;
                        $diff = $count-$shown;
                        if ($diff > 0):
                            $label = $diff > 1 ? $more_text_plural : $more_text_singular;
                            $item_html .= ' <span class="hastip wps_more_likes_dislikes" title="'.$tooltip.'">'.sprintf($label, $diff).'</span>';
                        endif;
                    endif;

                $item_html .= '</div>';

            endif;

        $item_html .= '</div>';
        $item_html .= '<div style="clear:both"></div>';
    
    endif;
    
	return $item_html;
}

// Add likes and dislikes (comment)
add_filter('wps_activity_comment_meta_filter', 'wps_add_likes_activity_item_comments_filter', 10, 6);
function wps_add_likes_activity_item_comments_filter ($item_html, $atts, $item_id, $comment_id, $user_id, $current_user_id) {

	// Shortcode parameters
	extract( shortcode_atts( array(
		'like_text' => __('Like', WPS2_TEXT_DOMAIN),
		'unlike_text' => __('Unlike', WPS2_TEXT_DOMAIN),
        'like_count_text_singular' => __('%d like', WPS2_TEXT_DOMAIN),
        'like_count_text_plural' => __('%d likes', WPS2_TEXT_DOMAIN),
		'dislike_text' => __('Dislike', WPS2_TEXT_DOMAIN),
		'undislike_text' => __('Undislike', WPS2_TEXT_DOMAIN),
        'dislike_count_text_singular' => __('%d dislike', WPS2_TEXT_DOMAIN),
        'dislike_count_text_plural' => __('%d dislikes', WPS2_TEXT_DOMAIN),
        'done_text' => __('Done!', WPS2_TEXT_DOMAIN), 
		'by_text' => __(' by ', WPS2_TEXT_DOMAIN),
        'more_text_singular' => __('and %d more...', WPS2_TEXT_DOMAIN),
        'more_text_plural' => __('and %d more...', WPS2_TEXT_DOMAIN),
		'or_text' => __(' or ', WPS2_TEXT_DOMAIN),
		'you_text' => __('you', WPS2_TEXT_DOMAIN),
        'max_names_to_show' => 3,
        'allow_unlikes' => true,
        'allow_undislikes' => true,
	), $atts, 'wps_likes_and_dislikes' ) );

    $allowed_likes_target = get_option('wps_allowed_likes_target');    
    $allowed_dislikes_target = get_option('wps_allowed_dislikes_target');
    $likes = get_comment_meta( $comment_id, 'wps_post_likes', true );
    $dislikes = get_comment_meta( $comment_id, 'wps_post_dislikes', true );

    $the_comment = get_comment($comment_id);
    $show_likes_and_dislikes = false;
    if ( ($allowed_likes_target != 'nobody' || $allowed_dislikes_target != 'nobody') && $the_comment->user_id != $current_user_id) $show_likes_and_dislikes = true;
    if ($likes || $dislikes) $show_likes_and_dislikes = true;
    
    if ($show_likes_and_dislikes):
    
        $item_html .= '<div class="wps_likes_and_dislikes">';

            $friend_status = wps_are_friends($user_id, $current_user_id);
            $is_friend = $friend_status['status'] == 'publish' ? true : false;
            $show_likes = true;
            if ( ($allowed_likes_target == 'nobody') || ($allowed_likes_target == 'friends' && !$is_friend) ) $show_likes = false; 
            $show_dislikes = true;
            if ( ($allowed_dislikes_target == 'nobody') || ($allowed_dislikes_target == 'friends' && !$is_friend) ) $show_dislikes = false; 

            // Like and Dislike (and Un...) links
            if ($the_comment->user_id != $current_user_id):    

                $item_html .= '<div class="wps_likes_and_dislikes_actions">';

                    if ( (!$likes || !in_array($current_user_id, $likes)) && (!$dislikes || !in_array($current_user_id, $dislikes)) ):
                        if ($show_likes) $item_html .= '<a class="wps_like_comment" data-done-text="'.$done_text.'" rel="'.$comment_id.'" href="javascript:void(0);">'.$like_text.'</a>';
                        if ($show_likes && $show_dislikes) $item_html .= $or_text;
                        if ($show_dislikes) $item_html .= '<a class="wps_dislike_comment" data-done-text="'.$done_text.'" rel="'.$comment_id.'" href="javascript:void(0);">'.$dislike_text.'</a>';
                    endif;
                    if ($likes && in_array($current_user_id, $likes) && $show_likes && $allow_unlikes):
                        $item_html .= '<a class="wps_unlike_comment" data-done-text="'.$done_text.'" rel="'.$comment_id.'" href="javascript:void(0);">'.$unlike_text.'</a>';
                    endif;
                    if ($dislikes && in_array($current_user_id, $dislikes) && $show_dislikes && $allow_undislikes):
                        $item_html .= '<a class="wps_undislike_comment" data-done-text="'.$done_text.'" rel="'.$comment_id.'" href="javascript:void(0);">'.$undislike_text.'</a>';
                    endif;

                $item_html .= '</div>';
    
            endif;

            // Previous likes and dislikes
            if ($likes || $dislikes):

                $item_html .= '<div class="wps_likes_and_dislikes_counts">';

                    if ($likes && $show_likes):
                        $likes = array_reverse($likes);
                        $count = count($likes);
                        $label = $count > 1 ? $like_count_text_plural : $like_count_text_singular;
                        $item_html .= sprintf($label, $count).$by_text;
                        $shown = 0;
                        $tooltip = '';
                        $max_to_show = $count > $max_names_to_show ? $max_names_to_show : $count;
                        foreach ($likes as $user_id):
                            if ($shown < $max_to_show):
                                $shown++;
                                if ($shown > 1):
                                    if ($shown < $max_to_show):
                                        $item_html .= ', ';
                                    else:
                                        $item_html .= ' '.__('and', WPS2_TEXT_DOMAIN).' ';
                                    endif;
                                endif;
                                if ($user_id == $current_user_id):
                                    $item_html .= $you_text;
                                else:
                                    $item_html .= wps_display_name(array('user_id'=>$user_id, 'link'=>1));
                                endif;
                            endif;
                            $tooltip .= wps_display_name(array('user_id'=>$user_id, 'link'=>0)).'<br />';
                        endforeach;
                        $diff = $count-$shown;
                        if ($diff > 0):
                            $label = $diff > 1 ? $more_text_plural : $more_text_singular;
                            $item_html .= ' <span class="hastip wps_more_likes_dislikes" title="'.$tooltip.'">'.sprintf($label, $diff).'</span>';
                        endif;
                    endif;

                    if ($dislikes && $show_dislikes):
                        $dislikes = array_reverse($dislikes);
                        $count = count($dislikes);
                        $label = $count > 1 ? $dislike_count_text_plural : $dislike_count_text_singular;
                        if ($likes) $item_html .= ', ';
                        $item_html .= sprintf($label, $count).$by_text;
                        $shown = 0;
                        $tooltip = '';
                        $max_to_show = $count > $max_names_to_show ? $max_names_to_show : $count;
                        foreach ($dislikes as $user_id):
                            if ($shown < $max_to_show):
                                $shown++;
                                if ($shown > 1):
                                    if ($shown < $max_to_show):
                                        $item_html .= ', ';
                                    else:
                                        $item_html .= ' '.__('and', WPS2_TEXT_DOMAIN).' ';
                                    endif;
                                endif;
                                if ($user_id == $current_user_id):
                                    $item_html .= $you_text;
                                else:
                                    $item_html .= wps_display_name(array('user_id'=>$user_id, 'link'=>1));
                                endif;
                            endif;
                            $tooltip .= wps_display_name(array('user_id'=>$user_id, 'link'=>0)).'<br />';
                        endforeach;
                        $diff = $count-$shown;
                        if ($diff > 0):
                            $label = $diff > 1 ? $more_text_plural : $more_text_singular;
                            $item_html .= ' <span class="hastip wps_more_likes_dislikes" title="'.$tooltip.'">'.sprintf($label, $diff).'</span>';
                        endif;
                    endif;

                $item_html .= '</div>';

            endif;

        $item_html .= '</div>';
        $item_html .= '<div style="clear:both"></div>';
    
    endif;
    
	return $item_html;
}

/* Rewards */

// Add rewards
add_action( 'rewards_info_box_content_options_filter', 'wps_like_add_rewards' , 10, 1);
function wps_like_add_rewards($the_post_id) {
    echo '<option value="like"';
        if (get_post_meta($the_post_id, 'wps_rewards_type', true) == 'like') echo ' SELECTED';
        echo '>'.__('Like an activity post/comment', WPS2_TEXT_DOMAIN).'</option>';
    echo '<option value="dislike"';
        if (get_post_meta($the_post_id, 'wps_rewards_type', true) == 'dislike') echo ' SELECTED';
        echo '>'.__('Dislike an activity post/comment', WPS2_TEXT_DOMAIN).'</option>';
}
// Add rewards (count)
add_action( 'rewards_info_box_content_options_count_filter', 'wps_like_add_rewards_count_type' , 10, 1);
function wps_like_add_rewards_count_type($the_post_id) {
    echo '<option value="like"';
        if (get_post_meta($the_post_id, 'wps_rewards_count_type', true) == 'like') echo ' SELECTED';
        echo '>'.__('Like an activity post/comment', WPS2_TEXT_DOMAIN).'</option>';
    echo '<option value="dislike"';
        if (get_post_meta($the_post_id, 'wps_rewards_count_type', true) == 'dislike') echo ' SELECTED';
        echo '>'.__('Dislike an activity post/comment', WPS2_TEXT_DOMAIN).'</option>';
}
// Add rewards (when editing individual user rewards)
add_action( 'reward_info_box_content_options_filter', 'wps_like_add_reward' , 10, 1);
function wps_like_add_reward($the_post_id) {
    echo '<option value="like"';
        if (get_post_meta($the_post_id, 'wps_reward_type', true) == 'like') echo ' SELECTED';
        echo '>'.__('Like an activity post/comment', WPS2_TEXT_DOMAIN).'</option>';
    echo '<option value="dislike"';
        if (get_post_meta($the_post_id, 'wps_reward_type', true) == 'dislike') echo ' SELECTED';
        echo '>'.__('Dislike an activity post/comment', WPS2_TEXT_DOMAIN).'</option>';
}

// Reward like
add_action( 'wps_like_hook', 'wps_like_reward', 10, 1 );
add_action( 'wps_like_comment_hook', 'wps_like_reward', 10, 1 );
function wps_like_reward($the_post) {

    global $wpdb;
    $sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_rewards' AND post_status='publish'";
    $rewards = $wpdb->get_results($sql);

    if ($rewards):

        global $current_user;

        foreach ($rewards as $reward):

            $wps_reward_type = get_post_meta($reward->ID, 'wps_rewards_type', true);
            $wps_reward_value = get_post_meta($reward->ID, 'wps_rewards_value', true);

            if ($wps_reward_type == 'like'):

                $post = array(
                  'post_title'      => $current_user->display_name,
                  'post_status'     => 'publish',
                  'post_content'    => $current_user->display_name.'<br />'.serialize($the_post),
                  'post_type'       => 'wps_reward',
                  'post_author'     => $current_user->ID,
                  'ping_status'     => 'closed',
                  'comment_status'  => 'closed',
                );  
                $reward_id = wp_insert_post( $post );

                update_post_meta( $reward_id, 'wps_reward_type', $reward->post_name );
                update_post_meta( $reward_id, 'wps_reward_value', $wps_reward_value );

            endif;      

        endforeach;

    endif;

}

// Reward dislike
add_action( 'wps_dislike_hook', 'wps_dislike_reward', 10, 1 );
add_action( 'wps_dislike_comment_hook', 'wps_dislike_reward', 10, 1 );
function wps_dislike_reward($the_post) {

    global $wpdb;
    $sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_rewards' AND post_status='publish'";
    $rewards = $wpdb->get_results($sql);

    if ($rewards):

        global $current_user;

        foreach ($rewards as $reward):

            $wps_reward_type = get_post_meta($reward->ID, 'wps_rewards_type', true);
            $wps_reward_value = get_post_meta($reward->ID, 'wps_rewards_value', true);

            if ($wps_reward_type == 'dislike'):

                $post = array(
                  'post_title'      => $current_user->display_name,
                  'post_status'     => 'publish',
                  'post_content'    => $current_user->display_name.'<br />'.serialize($the_post),
                  'post_type'       => 'wps_reward',
                  'post_author'     => $current_user->ID,
                  'ping_status'     => 'closed',
                  'comment_status'  => 'closed',
                );  
                $reward_id = wp_insert_post( $post );

                update_post_meta( $reward_id, 'wps_reward_type', $reward->post_name );
                update_post_meta( $reward_id, 'wps_reward_value', $wps_reward_value );

            endif;      

        endforeach;

    endif;

}



?>