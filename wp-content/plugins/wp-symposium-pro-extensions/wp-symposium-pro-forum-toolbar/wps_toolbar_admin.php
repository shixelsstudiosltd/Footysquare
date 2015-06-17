<?php
																	/* ***** */
																	/* ADMIN */
																	/* ***** */


add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_toolbar');
function wps_admin_getting_started_toolbar() {

	// Show menu item	
  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_toolbar">'.__('Forum Toolbar', WPS2_TEXT_DOMAIN).'</div>';

  	// Show setup/help content
	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_toolbar' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_toolbar" style="display:'.$display.'">';

	?>
		<table class="form-table">

		<tr valign="top"> 
		<td scope="row">
			<label for="wps_pro_toolbar"><?php echo __('Toolbar Style', WPS2_TEXT_DOMAIN); ?></label>
		</td>
		<td>
			<select name="wps_pro_toolbar">
				<?php			
				echo '<option value="bbcode"';
					if (get_option('wps_pro_toolbar') == 'bbcode') echo ' SELECTED';
					echo '>'.__('BB Codes', WPS2_TEXT_DOMAIN);
					echo '</option>';
				echo '<option value="wysiwyg"';
					if (!get_option('wps_pro_toolbar') || get_option('wps_pro_toolbar') == 'wysiwyg') echo ' SELECTED';
					echo '>'.__('WYSIWYG', WPS2_TEXT_DOMAIN);
					echo '</option>';
				?>
			</select>
			<span class="description">
				<br /><?php echo __('WYSIWYG editor currently only available in English, more languages coming soon.', WPS2_TEXT_DOMAIN); ?>
			</span>
			</td> 
		</tr> 

		<tr valign="top"> 
		<td scope="row">
			<label for="wps_pro_toolbar"><?php echo __('WYSIWYG icons', WPS2_TEXT_DOMAIN); ?></label>
		</td>
		<td>
			<?php
			$wps_pro_toolbar_icons = ($value = get_option('wps_pro_toolbar_icons')) ? $value : "html,formatting,bold,italic,deleted,unorderedlist,orderedlist,outdent,indent,table,link,alignment,horizontalrule";
			echo '<input style="width:100%" type="text" name="wps_pro_toolbar_icons" value="'.stripslashes($wps_pro_toolbar_icons).'" />';
			?>
			<span class="description">
				<br /><?php echo sprintf(__('Default: %s', WPS2_TEXT_DOMAIN), "bold,italic,deleted,unorderedlist,orderedlist,link"); ?>
				<br /><?php echo sprintf(__('Also available: %s', WPS2_TEXT_DOMAIN), "formatting,html,outdent,indent,table,link,alignment,horizontalrule"); ?>
			</span>
		</td> 
		</tr> 		

	</table>
	<?php

	echo '</div>';
}

add_action( 'wps_admin_setup_form_save_hook', 'wps_alerts_admin_toolbar_save', 10, 1 );
function wps_alerts_admin_toolbar_save ($the_post) {

    if (isset($the_post['wps_pro_toolbar'])):

        if ($value = $the_post['wps_pro_toolbar']):
            update_option('wps_pro_toolbar', $value);
        else:
            update_option('wps_pro_toolbar', 'wysiwyg');
        endif;

        if ($value = $the_post['wps_pro_toolbar_icons']):
            update_option('wps_pro_toolbar_icons', $value);
        else:
            update_option('wps_pro_toolbar_icons', 'bold,italic,deleted,unorderedlist,orderedlist,link');
        endif;
    
    endif;

}

?>