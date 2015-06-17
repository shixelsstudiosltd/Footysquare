<?php
// AJAX functions for lounge
add_action( 'wp_ajax_wps_favourites_toggle', 'wps_favourites_toggle' ); 

/* TOGGLE */
function wps_favourites_toggle() {

    global $current_user;
    
    if ( is_user_logged_in() ) {

        $favs = get_user_meta($current_user->ID, 'wps_favourites', true);
        if (!$favs) $favs = array();
        
        if ($_POST['change_to'] == 'on') {
        
            if (!in_array($_POST['post_id'], $favs)) array_push($favs, $_POST['post_id']);
            echo 'on';
        
        } else {

            if (in_array($_POST['post_id'], $favs)) {
                
                if(($key = array_search($_POST['post_id'], $favs)) !== false) {
                    unset($favs[$key]);
                    echo 'off';
                }
                
            }
            
        }
        
        update_user_meta($current_user->ID, 'wps_favourites', $favs);
        
    } else {
        
        echo 'not logged in';
        
    }
    
	exit;

}


?>