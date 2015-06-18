<?php


// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_default_groups');
function wps_admin_getting_started_default_groups() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_default_groups">'.__('Default Groups', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_default_groups' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_default_groups" style="display:'.$display.'">';

	?>
	<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Default groups', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<?php
			$value = get_option('wps_default_groups');
			if ($value) $value = implode(',', $value);
			?>
			<input type="text" name="wps_default_groups" value="<?php echo $value; ?>" />
			<br /><span class="description"><?php echo sprintf(__('Enter <a href="%s">group IDs</a>, comma separated, to be added as a group for new users.', WPS2_TEXT_DOMAIN), "edit.php?post_type=wps_group"); ?></span>
		</td>
	</tr> 
	</table>
	<?php

	echo '</div>';

}

add_action('wps_admin_setup_form_get_hook', 'wps_admin_getting_started_default_groups_save', 10, 2);
add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_default_groups_save', 10, 2);
function wps_admin_getting_started_default_groups_save($the_post) {

    if (isset($the_post['wps_default_groups'])):

        if ($the_post['wps_default_groups']):
            $value = explode(',', $the_post['wps_default_groups']);
            update_option('wps_default_groups', $value);
        else:
            delete_option('wps_default_groups');
        endif;
    
    endif;

}

?>
