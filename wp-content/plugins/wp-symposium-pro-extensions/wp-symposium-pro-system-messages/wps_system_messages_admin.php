<?php


// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_system_messages');
function wps_admin_getting_started_system_messages() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_system_messages">'.__('System Messages', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_system_messages' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_system_messages" style="display:'.$display.'">';

	?>
	<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_system_messages_default"><?php _e('Activity message for new users', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<span class="description"><?php _e('Send message from which user?', WPS2_TEXT_DOMAIN); ?></span><br />
			<input type="text" id="wps_system_messages_default_from" style="width:300px" name="wps_system_messages_default_from" placeholder="Select user..." value="<?php echo get_option('wps_system_messages_default_from'); ?>" /><br />
			<span class="description"><?php _e('Activity message...', WPS2_TEXT_DOMAIN); ?></span><br />
			<textarea name="wps_system_messages_default" style="width:100%;height:200px;"><?php echo get_option('wps_system_messages_default'); ?></textarea>
			<br /><span class="description"><?php _e('This message will automatically appear on new user activity.', WPS2_TEXT_DOMAIN);?></span>
			<br /><br /><strong><?php _e('Preview', WPS2_TEXT_DOMAIN); ?></strong><br />
			<?php echo wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html(get_option('wps_system_messages_default')))))); ?>
		</td>
	</tr> 
	</table>
	<?php

	echo '</div>';

}

add_action('wps_admin_setup_form_get_hook', 'wps_admin_getting_started_system_messages_save', 10, 2);
add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_system_messages_save', 10, 2);
function wps_admin_getting_started_system_messages_save($the_post) {

    if (isset($the_post['wps_system_messages_default'])):
    
        if ($the_post['wps_system_messages_default']):
            update_option('wps_system_messages_default', stripslashes($the_post['wps_system_messages_default']));
        else:
            delete_option('wps_system_messages_default');
        endif; 

        if ($the_post['wps_system_messages_default_from']):
            update_option('wps_system_messages_default_from', $the_post['wps_system_messages_default_from']);
        else:
            delete_option('wps_system_messages_default_from');
        endif;
    
    endif;

}

?>
