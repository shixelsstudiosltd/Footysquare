<?php


// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_rewards');
function wps_admin_getting_started_rewards() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_rewards">'.__('Rewards', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_rewards' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_rewards" style="display:'.$display.'">';

	?>
	<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_rewards_reset"><?php _e('Reset', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<p><?php _e('Check below and save to remove all rewards currently awarded to users.', WPS2_TEXT_DOMAIN); ?></p>
			<?php
			echo '<input type="checkbox" id="wps_rewards_reset" style="width:10px" name="wps_rewards_reset" />';
			?>
			<span class="description"><?php _e('Note: this cannot be undone.', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 
	</table>
	<?php

	echo '</div>';

}

add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_rewards_save', 10, 2);
function wps_admin_getting_started_rewards_save($the_post) {

	if(!empty($_POST['wps_rewards_reset'])):
	    
	    global $wpdb;
		$sql = "DELETE FROM ".$wpdb->prefix."posts WHERE post_type = 'wps_reward'";
		$wpdb->query($sql);

		echo '<div class="wps_success">'.__('All user rewards deleted.', WPS2_TEXT_DOMAIN).'</div>';

	endif;
}

?>
