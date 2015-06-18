<?php
/**
 * Filter to add who-to select list
 **/
add_filter('wps_activity_post_pre_form_filter', 'wps_activity_post_form_whoto',10,4);
function wps_activity_post_form_whoto($html, $atts, $user_id, $current_user_id) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'share_with' => __('Share with:', WPS2_TEXT_DOMAIN),
        'friends_text' => __('All friends', WPS2_TEXT_DOMAIN),
        'select_text' => __('Select friends...', WPS2_TEXT_DOMAIN),
    ), $atts, 'wps_activity_whoto' ) );

    $html .= '<div id="activity_whoto">';

        $html .= $share_with.' ';
        $html .= '<select name="activity_whoto_select" id="activity_whoto_select">';

        $options = '<option value="friends">'.$friends_text.'</option>';
        $friends = wps_get_friends($current_user_id);
        if ($friends):
            $selected_as_default = ($user_id != $current_user_id) ? ' SELECTED' : '';
            $options .= '<option value="select"'.$selected_as_default.'>'.$select_text.'</option>';
        endif;
        $options = apply_filters( 'wps_activity_post_form_whoto_options_filter', $options );
        $html .= $options;
        $html .= '</select>';

    $html .= '</div>';

    $html .= '<div id="activity_whoto_select_list" style="display:none">';
        $html .= '<select multiple="multiple" id="wps_activity_recipients" name="wps_activity_recipients[]" style="width:100%">';
        if ($friends):
            foreach ($friends as $friend):
                $user = get_user_by('id', $friend['ID']);
                if ($user):
                    $selected = ($user_id == $friend['ID']) ? ' selected' : '';
                    $html .= sprintf('<option value="%s"%s>%s</option>', $user->ID, $selected, $user->display_name);
                endif;
            endforeach;
        endif;
        $html .= '</select>';
    $html .= '</div>';


    return $html;

}

/**
 * Hook to change target to array of recipients
 **/
add_action("wps_activity_post_add_hook", 'wps_whoto_update_target', 10, 3);
function wps_whoto_update_target( $post_vars, $files_var, $new_id ) {

    if (isset($post_vars['wps_activity_recipients']) && $post_vars['wps_activity_recipients']):

        $recipients = array();
        foreach ($post_vars['wps_activity_recipients'] as $recipient) {
            array_push($recipients, $recipient);            
        }
        update_post_meta( $new_id, 'wps_target', $recipients );

    endif;

    // Any further actions?
    do_action( 'wps_whoto_update_target_hook', $post_vars, $files_var, $new_id );

}

/**
 * Change recipients to multiple for alerts
 **/

// Filter recipients in wps_activity_post_add_alerts
add_filter( 'wps_activity_post_add_alerts_recipients_filter', 'wps_whoto_set_recipients_to_selected_friends', 10, 4 );
function wps_whoto_set_recipients_to_selected_friends($recipients, $the_post, $the_files, $new_id) {

    if ($the_post['wps_activity_recipients']):
        $new_recipients = array();
        foreach ($the_post['wps_activity_recipients'] as $recipient) {
            array_push($new_recipients, $recipient);            
        }
        return $new_recipients;
    else:
        return $recipients;
    endif;


}

// Filter recipients in wps_activity_comment_add_alerts
add_filter( 'wps_activity_comment_add_alerts_recipients_filter', 'wps_whoto_set_comment_recipients_to_selected_friends', 10, 4 );
function wps_whoto_set_comment_recipients_to_selected_friends($recipients, $the_post, $post_id, $new_id) {

    // Handle target recipient(s) of original post
    $target_ids = get_post_meta( $post_id, 'wps_target', true );
    $recipients = array();
    if (is_array($target_ids)):
        // Add all target user IDs
        foreach ($target_ids as $target_id):
            $recipients['target '.$target_id] = $target_id;
        endforeach;
    else:
        // Add single target user ID
        $target_ids = get_post_meta( $post_id, 'wps_target', true );
        $recipients['target '.$target_ids] = $target_ids;
    endif;
    return $recipients;

}

?>