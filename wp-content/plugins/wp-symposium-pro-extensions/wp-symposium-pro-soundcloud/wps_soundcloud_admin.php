<?php


// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_soundcloud');
function wps_admin_getting_started_soundcloud() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_soundcloud">'.__('SoundCloud', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_soundcloud' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_soundcloud" style="display:'.$display.'">';

	?>
	<table class="form-table">
	<tr class="form-field">
		<td scope="row" valign="top">
			<label for="wps_soundcloud_client_id"><?php _e('Client Id', WPS2_TEXT_DOMAIN); ?></label>
		</td>
		<td>
			<input type="text" name="wps_soundcloud_client_id" value="<?php echo get_option('wps_soundcloud_client_id'); ?>" /><br />
			<span class="description">Log into SoundCloud, <a target="_blank" href="https://soundcloud.com/you/apps">create an application</a> and copy/paste the Client ID above.</span>
		</td>
	</tr> 
	</table>
	<?php

	echo '</div>';

}

add_action('wps_admin_setup_form_get_hook', 'wps_admin_getting_started_soundcloud_save', 10, 2);
add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_soundcloud_save', 10, 2);
function wps_admin_getting_started_soundcloud_save($the_post) {

	if (isset($the_post['wps_soundcloud_client_id'])):
		update_option('wps_soundcloud_client_id', $the_post['wps_soundcloud_client_id']);
	else:
		delete_option('wps_soundcloud_client_id');
	endif;


}

?>
