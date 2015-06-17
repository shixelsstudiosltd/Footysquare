<?php


// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_security');
function wps_admin_getting_started_security() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_security">'.__('Profile Security', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_security' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_security" style="display:'.$display.'">';

	?>
	<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Default values', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<?php

				echo '<div id="wpspro_profile_security">';
					echo '<p class="description">'.__('Who can see the user\'s avatar, and profile fields, included extended fields?', WPS2_TEXT_DOMAIN).'</p>';
			        $profile_security = get_option('wpspro_profile_security');
			        echo '<select name="wpspro_profile_security" id="wpspro_profile_security">';

			        if (!$profile_security) $profile_security = 'friends';

			        $options = '<option value="nobody"';
			        	if ($profile_security == 'nobody') $options .= ' SELECTED';
			        	$options .= '>'.__('Just the user', WPS2_TEXT_DOMAIN).'</option>';

			        $options .= '<option value="friends"';
			        	if ($profile_security == 'friends') $options .= ' SELECTED';
			        	$options .= '>'.__('The user and their friends', WPS2_TEXT_DOMAIN).'</option>';

			        $options .= '<option value="members"';
			        	if ($profile_security == 'members') $options .= ' SELECTED';
			        	$options .= '>'.__('All site members (logged in)', WPS2_TEXT_DOMAIN).'</option>';

			        $options .= '<option value="public"';
			        	if ($profile_security == 'public') $options .= ' SELECTED';
			        	$options .= '>'.__('Everybody (public, even if not logged in)', WPS2_TEXT_DOMAIN).'</option>';

			        echo $options;
			        echo '</select> ';
					echo '<input type="checkbox" ';
					if (get_option('wpspro_profile_security_hide')) echo 'CHECKED ';
					echo 'style="width:10px" name="wpspro_profile_security_hide" />';
					echo '<span class="description">'.__('Hide from Edit Profile page.', WPS2_TEXT_DOMAIN).'</span><br />';
				echo '</div><br />';



				echo '<div id="wpspro_activity_security">';
					echo '<p class="description">'.__('Who can see the user\'s activity posts (note: replies inherit the original post permissions)?', WPS2_TEXT_DOMAIN).'</p>';
			        $activity_security = get_option('wpspro_activity_security');
			        echo '<select name="wpspro_activity_security" id="wpspro_activity_security">';

			        if (!$activity_security) $activity_security = 'friends';

			        $options = '<option value="nobody"';
			        	if ($activity_security == 'nobody') $options .= ' SELECTED';
			        	$options .= '>'.__('Just the user', WPS2_TEXT_DOMAIN).'</option>';

			        $options .= '<option value="friends"';
			        	if ($activity_security == 'friends') $options .= ' SELECTED';
			        	$options .= '>'.__('The user and their friends', WPS2_TEXT_DOMAIN).'</option>';

			        $options .= '<option value="members"';
			        	if ($activity_security == 'members') $options .= ' SELECTED';
			        	$options .= '>'.__('All site members (logged in)', WPS2_TEXT_DOMAIN).'</option>';

			        $options .= '<option value="public"';
			        	if ($activity_security == 'public') $options .= ' SELECTED';
			        	$options .= '>'.__('Everybody (public, even if not logged in)', WPS2_TEXT_DOMAIN).'</option>';

			        echo $options;
			        echo '</select> ';
					echo '<input type="checkbox" ';
					if (get_option('wpspro_activity_security_hide')) echo 'CHECKED ';
					echo 'style="width:10px" name="wpspro_activity_security_hide" />';
					echo '<span class="description">'.__('Hide from Edit Profile page.', WPS2_TEXT_DOMAIN).'</span><br />';
				echo '</div><br />';

				if (function_exists('wps_admin_getting_started_directory')):
					echo '<div id="wpspro_directory_security">';
						echo '<p class="description">'.__('Who can see the user in the directory?', WPS2_TEXT_DOMAIN).'</p>';
				        $directory_security = get_option('wpspro_directory_security');
				        echo '<select name="wpspro_directory_security" id="wpspro_directory_security">';

				        if (!$directory_security) $directory_security = 'members';

				        $options = '<option value="nobody"';
				        	if ($directory_security == 'nobody') $options .= ' SELECTED';
				        	$options .= '>'.__('Just the user', WPS2_TEXT_DOMAIN).'</option>';

				        $options .= '<option value="friends"';
				        	if ($directory_security == 'friends') $options .= ' SELECTED';
				        	$options .= '>'.__('The user and their friends', WPS2_TEXT_DOMAIN).'</option>';

				        $options .= '<option value="members"';
				        	if ($directory_security == 'members') $options .= ' SELECTED';
				        	$options .= '>'.__('All site members (logged in)', WPS2_TEXT_DOMAIN).'</option>';

				        $options .= '<option value="public"';
				        	if ($directory_security == 'public') $options .= ' SELECTED';
				        	$options .= '>'.__('Everybody (public, even if not logged in)', WPS2_TEXT_DOMAIN).'</option>';

				        echo $options;
				        echo '</select> ';
						echo '<input type="checkbox" ';
						if (get_option('wpspro_directory_security_hide')) echo 'CHECKED ';
						echo 'style="width:10px" name="wpspro_directory_security_hide" />';
						echo '<span class="description">'.__('Hide from Edit Profile page.', WPS2_TEXT_DOMAIN).'</span><br />';
					echo '</div><br />';
				endif;

				echo '<div id="wpspro_friends_security">';
					echo '<p class="description">'.__('Who can see the user\'s friendships?', WPS2_TEXT_DOMAIN).'</p>';
			        $friends_security = get_option('wpspro_friends_security');
			        echo '<select name="wpspro_friends_security" id="wpspro_friends_security">';

			        if (!$friends_security) $friends_security = 'members';

			        $options = '<option value="nobody"';
			        	if ($friends_security == 'nobody') $options .= ' SELECTED';
			        	$options .= '>'.__('Just the user', WPS2_TEXT_DOMAIN).'</option>';

			        $options .= '<option value="friends"';
			        	if ($friends_security == 'friends') $options .= ' SELECTED';
			        	$options .= '>'.__('The user and their friends', WPS2_TEXT_DOMAIN).'</option>';

			        $options .= '<option value="members"';
			        	if ($friends_security == 'members') $options .= ' SELECTED';
			        	$options .= '>'.__('All site members (logged in)', WPS2_TEXT_DOMAIN).'</option>';

			        $options .= '<option value="public"';
			        	if ($friends_security == 'public') $options .= ' SELECTED';
			        	$options .= '>'.__('Everybody (public, even if not logged in)', WPS2_TEXT_DOMAIN).'</option>';

			        echo $options;
			        echo '</select> ';
					echo '<input type="checkbox" ';
					if (get_option('wpspro_friends_security_hide')) echo 'CHECKED ';
					echo 'style="width:10px" name="wpspro_friends_security_hide" />';
					echo '<span class="description">'.__('Hide from Edit Profile page.', WPS2_TEXT_DOMAIN).'</span><br />';
				echo '</div><br />';

				echo '<div id="wpspro_security_remove">';
					echo '<input type="checkbox" style="width:10px" name="wpspro_security_remove" />';
					echo '<span class="description">'.__('Reset all user security settings, changing them to the above.', WPS2_TEXT_DOMAIN).'<br />';
					echo '<div style="padding-left:20px;">'.__('User\'s can then over-ride the default settings on their Edit Profile page.', WPS2_TEXT_DOMAIN).'</div></span>';
				echo '</div><br />';

				do_action( 'wps_admin_getting_started_security_hook' );
			?>
		</td>
	</tr> 
	</table>
	<?php

	echo '</div>';

}

add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_security_save', 10, 2);
function wps_admin_getting_started_security_save($the_post) {

	if (isset($the_post['wpspro_profile_security']))
		update_option( 'wpspro_profile_security', $the_post['wpspro_profile_security'] );

	if (isset($the_post['wpspro_activity_security']))
		update_option( 'wpspro_activity_security', $the_post['wpspro_activity_security'] );

	if (isset($the_post['wpspro_friends_security']))
		update_option( 'wpspro_friends_security', $the_post['wpspro_friends_security'] );

	if (isset($the_post['wpspro_directory_security']))
		update_option( 'wpspro_directory_security', $the_post['wpspro_directory_security'] );

	if (isset($the_post['wpspro_profile_security_hide'])):
		update_option('wpspro_profile_security_hide', true);
	else:
		delete_option('wpspro_profile_security_hide');
	endif;

	if (isset($the_post['wpspro_activity_security_hide'])):
		update_option('wpspro_activity_security_hide', true);
	else:
		delete_option('wpspro_activity_security_hide');
	endif;

	if (isset($the_post['wpspro_friends_security_hide'])):
		update_option('wpspro_friends_security_hide', true);
	else:
		delete_option('wpspro_friends_security_hide');
	endif;

	if (isset($the_post['wpspro_directory_security_hide'])):
		update_option('wpspro_directory_security_hide', true);
	else:
		delete_option('wpspro_directory_security_hide');
	endif;



	if (isset($the_post['wpspro_security_remove'])):
		global $wpdb;
		$wpdb->query("DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'wpspro_profile_security'");
		$wpdb->query("DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'wpspro_activity_security'");
		$wpdb->query("DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'wpspro_friends_security'");
		$wpdb->query("DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'wpspro_directory_security'");
		echo '<div class="wps_success">'.__('All user profile security settings reset.', WPS2_TEXT_DOMAIN).'</div>';
	endif;

}

?>
