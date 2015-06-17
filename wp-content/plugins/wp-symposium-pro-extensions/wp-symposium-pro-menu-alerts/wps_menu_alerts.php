<?php
add_filter( 'wp_nav_menu_items', 'wps_unread_mail' );
function wps_unread_mail($items){ 

    global $wpdb, $current_user;
    if (is_user_logged_in()):

        global $wpdb;
        $unread_mail = $wpdb->get_var ("SELECT COUNT(post_id) FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'wps_mail_unread' AND meta_value REGEXP '.*\"".$current_user->user_login."\".*'");

        if ($unread_mail > 0) {
            $items = str_replace("%m", " (".$unread_mail.")", $items);
        } else {
            $items = str_replace("%m", "", $items);
        }

    else:

        $items = str_replace("%m", "", $items);

    endif;

    return $items;
    
}        

?>