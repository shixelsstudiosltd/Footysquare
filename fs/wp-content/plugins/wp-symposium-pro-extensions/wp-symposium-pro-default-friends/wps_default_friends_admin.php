<?php


// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_default_friends');
function wps_admin_getting_started_default_friends() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_default_friends">'.__('Default Friends', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_default_friends' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_default_friends" style="display:'.$display.'">';

	?>
	<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Default friends', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<?php
			$value = get_option('wps_default_friends');
			if ($value) $value = implode(',', $value);
			?>
			<input type="text" name="wps_default_friends" value="<?php echo $value; ?>" />
			<br /><span class="description"><?php _e('Enter user logins, comma separated, to be added as a friend with new users.', WPS2_TEXT_DOMAIN);?></span>
            <br /><br /><input type="checkbox" name="wps_default_friends_replace" />
            <span class="description"><?php _e('Make all existing users friends with the above user(s).', WPS2_TEXT_DOMAIN);?></span>
		</td>
	</tr> 
	</table>
	<?php

	echo '</div>';

}

add_action('wps_admin_setup_form_get_hook', 'wps_admin_getting_started_default_friends_save', 10, 2);
add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_default_friends_save', 10, 2);
function wps_admin_getting_started_default_friends_save($the_post) {

    if (isset($the_post['wps_default_friends'])):

        if ($the_post['wps_default_friends']):
            $value = explode(',', $the_post['wps_default_friends']);
            update_option('wps_default_friends', $value);
    
            // Make all existing users friends with the above?
            if (isset($_POST['wps_default_friends_replace'])):

                global $wpdb;
                // Loop through all users to get ($user_id)
                $sql = "SELECT * FROM ".$wpdb->base_prefix."users";
                $users = $wpdb->get_results($sql);
                foreach ($users as $user):
                    wps_add_default_friends($user->ID);
                endforeach;
        
            endif;

        else:
            delete_option('wps_default_friends');
        endif;
    
    endif;

}

?>
