<?php

// Add friends after user registers
add_action('user_register','wps_add_default_friends');
function wps_add_default_friends($user_id){

	$values = get_option('wps_default_friends');
	if ($values):

		foreach($values as $value):

			// For each user login...
			$default_friend = get_user_by('login', $value);

			if ($default_friend):

				$friends = wps_are_friends($default_friend->ID, $user_id);
				if (!$friends['status']):

					// Create post object
					$user = get_user_by('id', $user_id);
    
                    if ($user->user_login != $default_friend->user_login):

                        $my_post = array(
                            'post_title' 	=> $default_friend->user_login.' - '.$user->user_login,
                            'post_name'	=> sanitize_title_with_dashes($default_friend->user_login.' '.$user->user_login),
                            'post_type'	=> 'wps_friendship',
                            'post_status'	=> 'publish'
                        );

                        // Insert the post into the database
                        if ($post_id = wp_insert_post( $my_post )):

                            // Update meta data
                            update_post_meta( $post_id, 'wps_member1', $default_friend->ID );
                            update_post_meta( $post_id, 'wps_member2', $user_id );
                            update_post_meta( $post_id, 'wps_friendship_since', current_time( 'mysql', 1 ) );

                        endif;
    
                    endif;

				endif;			

			endif;

		endforeach;

	endif;
}

?>