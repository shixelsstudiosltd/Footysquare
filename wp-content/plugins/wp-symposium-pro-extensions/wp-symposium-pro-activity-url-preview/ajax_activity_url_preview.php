<?php
// AJAX functions for lounge
add_action( 'wp_ajax_wps_activity_url_preview_delete', 'ajax_activity_url_preview_delete' ); 
add_action( 'wp_ajax_wps_activity_url_image_delete', 'ajax_activity_url_image_delete' ); 

/* REMOVE IMAGE */
function ajax_activity_url_image_delete() {

    global $current_user;
    
    if ( is_user_logged_in() ) {

        $item = get_post($_POST['item_id']);
        if ($item):
            if ($item->post_author = $current_user->ID || current_user_can('manage_options')):

                $meta = get_post_meta($_POST['item_id'], 'wps_url_preview_cache', true);
                $meta = array('attachment_id' => 'removed', 'title' => $meta['title'], 'description' => $meta['description'], 'url' => $meta['url']);
                update_post_meta($_POST['item_id'], 'wps_url_preview_cache', $meta);
        
            endif;

            echo $item->ID;
        else:
            echo false;
        endif;
        
    } else {
        
        echo false;
        
    }
    
	exit;

}

/* REMOVE PREVIEW */
function ajax_activity_url_preview_delete() {

    global $current_user;
    
    if ( is_user_logged_in() ) {

        $item = get_post($_POST['item_id']);
        if ($item):
            if ($item->post_author = $current_user->ID || current_user_can('manage_options'))
                update_post_meta($_POST['item_id'], 'wps_url_preview_cache', 'removed');        
            echo $_POST['item_id'];
        else:
            echo false;
        endif;
        
    } else {
        
        echo false;
        
    }
    
	exit;

}


?>