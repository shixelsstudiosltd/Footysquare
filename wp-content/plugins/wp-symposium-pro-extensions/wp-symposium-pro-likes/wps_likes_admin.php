<?php

// Settings
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_likes');
function wps_admin_getting_started_likes() {

	// Show menu item	
  	echo '<div class="wps_admin_getting_started_menu_item" id="wps_admin_getting_started_menu_item_default" rel="wps_admin_getting_started_likes">'.__('Likes and Dislikes', WPS2_TEXT_DOMAIN).'</div>';

  	// Show setup/help content
	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_likes' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_likes" style="display:'.$display.'">';
	?>

		<table class="form-table">

            <tr valign="top"> 
			<td scope="row"><label for="allowed_likes_target"><?php echo __('Likes', WPS2_TEXT_DOMAIN); ?></label></td>
			<td>
				<select name="allowed_likes_target">
				 <?php 
				    $allowed_likes_target = get_option('wps_allowed_likes_target');
                    echo '<option value=""';
                        if (!$allowed_likes_target) echo ' SELECTED';
                        echo '>'.__('All members', WPS2_TEXT_DOMAIN).'</option>';
                    echo '<option value="friends"';
                        if ($allowed_likes_target == 'friends') echo ' SELECTED';
                        echo '>'.__('Just friends', WPS2_TEXT_DOMAIN).'</option>';
                    echo '<option value="nobody"';
                        if ($allowed_likes_target == 'nobody') echo ' SELECTED';
                        echo '>'.__('Nobody (not active)', WPS2_TEXT_DOMAIN).'</option>';
				 ?>						
				</select>
				<span class="description"><?php echo __('Which members posts and replies can be liked.', WPS2_TEXT_DOMAIN); ?>
				</span></td> 
			</tr> 

            <tr valign="top"> 
			<td scope="row"><label for="allowed_dislikes_target"><?php echo __('Dislikes', WPS2_TEXT_DOMAIN); ?></label></td>
			<td>
				<select name="allowed_dislikes_target">
				 <?php 
				    $allowed_dislikes_target = get_option('wps_allowed_dislikes_target');
                    echo '<option value=""';
                        if (!$allowed_dislikes_target) echo ' SELECTED';
                        echo '>'.__('All members', WPS2_TEXT_DOMAIN).'</option>';
                    echo '<option value="friends"';
                        if ($allowed_dislikes_target == 'friends') echo ' SELECTED';
                        echo '>'.__('Just friends', WPS2_TEXT_DOMAIN).'</option>';
                    echo '<option value="nobody"';
                        if ($allowed_dislikes_target == 'nobody') echo ' SELECTED';
                        echo '>'.__('Nobody (not active)', WPS2_TEXT_DOMAIN).'</option>';
				 ?>						
				</select>
				<span class="description"><?php echo __('Which members posts and replies can be disliked.', WPS2_TEXT_DOMAIN); ?>
				</span></td> 
			</tr> 
            
		</table>

		<?php
	echo '</div>';

}

add_action( 'wps_admin_setup_form_save_hook', 'wps_profile_admin_options_likes_save', 10, 1 );
function wps_profile_admin_options_likes_save ($the_post) {

	if (isset($the_post['allowed_likes_target']) && $the_post['allowed_likes_target']):
        if ($the_post['allowed_likes_target'] == 'friends'):
            update_option('wps_allowed_likes_target', 'friends');
        else:
            update_option('wps_allowed_likes_target', 'nobody');
        endif;
    else:    
		delete_option('wps_allowed_likes_target');
	endif;

    if (isset($the_post['allowed_dislikes_target']) && $the_post['allowed_dislikes_target']):
        if ($the_post['allowed_dislikes_target'] == 'friends'):
            update_option('wps_allowed_dislikes_target', 'friends');
        else:
            update_option('wps_allowed_dislikes_target', 'nobody');
        endif;
    else:    
		delete_option('wps_allowed_dislikes_target');
	endif;
    
}

add_action( 'add_meta_boxes', 'activity_likes_dislikes_info_box' );
function activity_likes_dislikes_info_box() {
    add_meta_box( 
        'activity_likes_dislikes_info_box',
        __( 'Likes and Dislikes', WPS2_TEXT_DOMAIN ),
        'activity_likes_dislikes_info_box_content',
        'wps_activity',
        'normal',
        'high'
    );
}

function activity_likes_dislikes_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'activity_likes_dislikes_info_box', 'activity_likes_dislikes_info_box_nonce' );

    $likes = get_post_meta( $post->ID, 'wps_post_likes', true );
    $dislikes = get_post_meta( $post->ID, 'wps_post_dislikes', true );
    
    if ($likes || $dislikes):

        echo '<p>'.__('Click a name to remove.', WPS2_TEXT_DOMAIN).'</p>';

        echo '<div style="width:50%;float:left;overflow:auto;">';

            echo '<strong>'.__('Likes', WPS2_TEXT_DOMAIN).'</strong><br /><br />';
            if ($likes):
                $likes = array_reverse($likes);
                foreach ($likes as $user_id):
                    echo '<div><a class="wps_remove_like" data-action="like" data-post-id="'.$post->ID.'" data-user-id="'.$user_id.'" href="javascript:void(0)">'.wps_display_name(array('user_id'=>$user_id, 'link'=>0)).'</a></div>';
                endforeach;
            endif;

        echo '</div>';
        echo '<div style="overflow:auto;">';

            echo '<strong>'.__('DisLikes', WPS2_TEXT_DOMAIN).'</strong><br /><br />';
            if ($dislikes):
                $dislikes = array_reverse($dislikes);
                foreach ($dislikes as $user_id):
                    echo '<div><a class="wps_remove_like" data-action="like" data-post-id="'.$post->ID.'" data-user-id="'.$user_id.'" href="javascript:void(0)">'.wps_display_name(array('user_id'=>$user_id, 'link'=>0)).'</a></div>';
                endforeach;
            endif;

        echo '</div>';

        echo '<div style="clear:both"></div>';
    
    endif;
    
}

?>