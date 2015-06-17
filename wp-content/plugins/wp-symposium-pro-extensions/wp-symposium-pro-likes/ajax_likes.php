<?php
// AJAX functions for likes and dislikes (posts)
add_action( 'wp_ajax_wps_like', 'wps_like' ); 
add_action( 'wp_ajax_wps_unlike', 'wps_unlike' ); 
add_action( 'wp_ajax_wps_dislike', 'wps_dislike' ); 
add_action( 'wp_ajax_wps_undislike', 'wps_undislike' ); 
// AJAX functions for likes and dislikes (comments)
add_action( 'wp_ajax_wps_like_comment', 'wps_like_comment' ); 
add_action( 'wp_ajax_wps_unlike_comment', 'wps_unlike_comment' ); 
add_action( 'wp_ajax_wps_dislike_comment', 'wps_dislike_comment' ); 
add_action( 'wp_ajax_wps_undislike_comment', 'wps_undislike_comment' ); 
// AJAX functions for admin
add_action( 'wp_ajax_wps_likes_admin_remove', 'wps_likes_admin_remove' ); 

/* ADMIN */
function wps_likes_admin_remove() {

    if ($_POST['type'] == 'like') :

        $likes = get_post_meta( $_POST['post_id'], 'wps_post_likes', true );

        if(($key = array_search($_POST['user_id'], $likes)) !== false) {
            unset($likes[$key]);
        }

        update_post_meta( $_POST['post_id'], 'wps_post_likes', $likes );    
    
    else:
    
        $dislikes = get_post_meta( $_POST['post_id'], 'wps_post_dislikes', true );

        if(($key = array_search($_POST['user_id'], $dislikes)) !== false) {
            unset($dislikes[$key]);
        }

        update_post_meta( $_POST['post_id'], 'wps_post_dislikes', $dislikes );    
    
    endif;
    
    echo 'ok';
    exit();
}

/* -------------------- POSTS -------------------- */

/* LIKE */
function wps_like() {

    global $wpdb, $current_user;

	$the_post = $_POST;
	if (isset($the_post['post_id']) && $the_post['post_id'] != ''):
    
        $likes = get_post_meta( $the_post['post_id'], 'wps_post_likes', true );
        if (!$likes) $likes = array();
        
        if (!in_array($current_user->ID, $likes)):
            array_push($likes, $current_user->ID); 
            update_post_meta( $the_post['post_id'], 'wps_post_likes', $likes );
        endif;
    
        // Send alert
        $subject = __('New like on activity post', WPS2_TEXT_DOMAIN);
        $msg = __('A new like on activity post', WPS2_TEXT_DOMAIN);
        $author_id = $current_user->ID;
        $the_activity_post = get_post($the_post['post_id']);
        $recipient_id = $the_activity_post->post_author;
        $type = 'like';
        $status = 'pending';

        $u = get_user_by('id', $recipient_id);
        if ( wps_using_permalinks() ):	
            $parameters = sprintf('%s?view=%d', urlencode($u->user_login), $the_post['post_id']);
            $permalink = get_permalink(get_option('wpspro_profile_page'));
            $url = $permalink.$parameters;
        else:
            $parameters = sprintf('user_id=%d&view=%d', urlencode($recipient_id), $the_post['post_id']);
            $permalink = get_permalink(get_option('wpspro_profile_page'));
            $url = $permalink.'&'.$parameters;
        endif;
    
        $author = get_user_by('id', $author_id);
        $content = '<h2>'.$author->display_name.' '.__('likes your post', WPS2_TEXT_DOMAIN).'</h2>';
        $content .= '<p>'.$the_activity_post->post_title.'</p>';
        $content .= '<p><a href="'.$url.'">'.$url.'</a></p>';
        $content = apply_filters( 'wps_like_alert_after', $content );

        wps_pro_insert_alert($type, $subject, $content, $author_id, $recipient_id, $parameters, $url, $msg, $status);

        // Any further actions?
        do_action( 'wps_like_hook', $the_post );

        // Done
        echo 'ok';
    
    else:
        echo 'Like: No post ID passed';
    endif;
    
	exit();
}

/* UNLIKE */
function wps_unlike() {

    global $wpdb, $current_user;

	$the_post = $_POST;
	if (isset($the_post['post_id']) && $the_post['post_id'] != ''):
    
        $likes = get_post_meta( $the_post['post_id'], 'wps_post_likes', true );

        if(($key = array_search($current_user->ID, $likes)) !== false) {
            unset($likes[$key]);
        }
    
        update_post_meta( $the_post['post_id'], 'wps_post_likes', $likes );
    
        // Any further actions?
        do_action( 'wps_unlike_hook', $the_post );

        // Done
        echo 'ok';
    
    else:
        echo 'UnLike: No post ID passed';
    endif;
    
	exit();
}

/* DISLIKE */
function wps_dislike() {

    global $wpdb, $current_user;

	$the_post = $_POST;
	if (isset($the_post['post_id']) && $the_post['post_id'] != ''):
    
        $dislikes = get_post_meta( $the_post['post_id'], 'wps_post_dislikes', true );
        if (!$dislikes) $dislikes = array();
        
        if (!in_array($current_user->ID, $dislikes)):
            array_push($dislikes, $current_user->ID); 
            update_post_meta( $the_post['post_id'], 'wps_post_dislikes', $dislikes );
        endif;

        // Send alert
        $subject = __('New dislike on activity post', WPS2_TEXT_DOMAIN);
        $msg = __('A new dislike on activity post', WPS2_TEXT_DOMAIN);
        $author_id = $current_user->ID;
        $the_activity_post = get_post($the_post['post_id']);
        $recipient_id = $the_activity_post->post_author;
        $type = 'dislike';
        $status = 'pending';

        $u = get_user_by('id', $recipient_id);
        if ( wps_using_permalinks() ):	
            $parameters = sprintf('%s?view=%d', urlencode($u->user_login), $the_post['post_id']);
            $permalink = get_permalink(get_option('wpspro_profile_page'));
            $url = $permalink.$parameters;
        else:
            $parameters = sprintf('user_id=%d&view=%d', urlencode($recipient_id), $the_post['post_id']);
            $permalink = get_permalink(get_option('wpspro_profile_page'));
            $url = $permalink.'&'.$parameters;
        endif;
    
        $author = get_user_by('id', $recipient_id);
        $content = '<h2>'.$author->display_name.' '.__('dislikes your post', WPS2_TEXT_DOMAIN).'</h2>';
        $content .= '<p>'.$the_activity_post->post_title.'</p>';
        $content .= '<p><a href="'.$url.'">'.$url.'</a></p>';
        $content = apply_filters( 'wps_dislike_alert_after', $content );

        wps_pro_insert_alert($type, $subject, $content, $author_id, $recipient_id, $parameters, $url, $msg, $status);

        // Any further actions?
        do_action( 'wps_dislike_hook', $the_post );
        
        echo 'ok';
    
    else:
        echo 'DisLike: No post ID passed';
    endif;
    
	exit();
}

/* UNDISLIKE */
function wps_undislike() {

    global $wpdb, $current_user;

	$the_post = $_POST;
	if (isset($the_post['post_id']) && $the_post['post_id'] != ''):
    
        $dislikes = get_post_meta( $the_post['post_id'], 'wps_post_dislikes', true );

        if(($key = array_search($current_user->ID, $dislikes)) !== false) {
            unset($dislikes[$key]);
        }
    
        update_post_meta( $the_post['post_id'], 'wps_post_dislikes', $dislikes );

        // Any further actions?
        do_action( 'wps_undislike_hook', $the_post );

        // Done
        echo 'ok';
    
    else:
        echo 'UnDisLike: No post ID passed';
    endif;
    
	exit();
}

/* -------------------- COMMENTS -------------------- */

/* LIKE */
function wps_like_comment() {

    global $wpdb, $current_user;

	$the_post = $_POST;
	if (isset($the_post['comment_id']) && $the_post['comment_id'] != ''):
        
        $likes = get_comment_meta( $the_post['comment_id'], 'wps_post_likes', true );
        if (!$likes) $likes = array();
        
        if (!in_array($current_user->ID, $likes)):
            array_push($likes, $current_user->ID); 
            update_comment_meta( $the_post['comment_id'], 'wps_post_likes', $likes );
        endif;

        // Any further actions?
        do_action( 'wps_like_comment_hook', $the_post );

        // Done
        echo 'ok';
    
    else:
        echo 'Like: No post ID passed';
    endif;
    
	exit();
}

/* UNLIKE */
function wps_unlike_comment() {

    global $wpdb, $current_user;

	$the_post = $_POST;
	if (isset($the_post['comment_id']) && $the_post['comment_id'] != ''):
    
        $likes = get_comment_meta( $the_post['comment_id'], 'wps_post_likes', true );

        if(($key = array_search($current_user->ID, $likes)) !== false) {
            unset($likes[$key]);
        }
    
        update_comment_meta( $the_post['comment_id'], 'wps_post_likes', $likes );

        // Any further actions?
        do_action( 'wps_unlike_comment_hook', $the_post );
    
        // Done
        echo 'ok';
    
    else:
        echo 'UnLike: No post ID passed';
    endif;
    
	exit();
}

/* DISLIKE */
function wps_dislike_comment() {

    global $wpdb, $current_user;

	$the_post = $_POST;
	if (isset($the_post['comment_id']) && $the_post['comment_id'] != ''):
    
        $dislikes = get_comment_meta( $the_post['comment_id'], 'wps_post_dislikes', true );
        if (!$dislikes) $dislikes = array();
        
        if (!in_array($current_user->ID, $dislikes)):
            array_push($dislikes, $current_user->ID); 
            update_comment_meta( $the_post['comment_id'], 'wps_post_dislikes', $dislikes );
        endif;

        // Any further actions?
        do_action( 'wps_dislike_comment_hook', $the_post );
    
        echo 'ok';
    
    else:
        echo 'DisLike: No post ID passed';
    endif;
    
	exit();
}

/* UNDISLIKE */
function wps_undislike_comment() {

    global $wpdb, $current_user;

	$the_post = $_POST;
	if (isset($the_post['comment_id']) && $the_post['comment_id'] != ''):
    
        $dislikes = get_comment_meta( $the_post['comment_id'], 'wps_post_dislikes', true );

        if(($key = array_search($current_user->ID, $dislikes)) !== false) {
            unset($dislikes[$key]);
        }
    
        update_comment_meta( $the_post['comment_id'], 'wps_post_dislikes', $dislikes );

        // Any further actions?
        do_action( 'wps_undislike_comment_hook', $the_post );

        // Done
        echo 'ok';
    
    else:
        echo 'UnDisLike: No post ID passed';
    endif;
    
	exit();
}

?>
