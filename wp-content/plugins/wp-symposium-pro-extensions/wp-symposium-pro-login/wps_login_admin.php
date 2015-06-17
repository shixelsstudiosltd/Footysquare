<?php


// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_login');
function wps_admin_getting_started_login() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_login">'.__('Login and Register', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_login' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_login" style="display:'.$display.'">';

	?>
	<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Roles', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<?php

			$saved_roles = get_option( 'wps_login_redirect');

		    global $wp_roles;
			$roles = $wp_roles->get_names();
			foreach ( $wp_roles->roles as $key=>$value ):
				if ($key != 'administrator'):
					echo '<input type="checkbox" id="wps_login_edit_role_'.$key.'" style="width:10px" name="wps_login_edit_role[]" ';
					if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
					echo 'value="'.$key.'"> <label for="wps_login_edit_role_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
				endif;
			endforeach;
			?>
			<span class="description"><?php _e('Roles that are allowed to view the WordPress admin dashboard.', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 
	</table>
	<?php

	echo '</div>';

}

add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_login_save', 10, 2);
function wps_admin_getting_started_login_save($the_post) {

	$roles = array();
	if(!empty($_POST['wps_login_edit_role'])):
	    
	    foreach($_POST['wps_login_edit_role'] as $check):
			$roles[] = $check;
	    endforeach;
	    update_option( 'wps_login_redirect', $roles );

	else:

		delete_option( 'wps_login_redirect' );

	endif;
}

?>
