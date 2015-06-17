<?php
add_action( 'wps_alerts_admin_setup_form_hook', 'wps_alerts_customise_admin_options', 10, 0 );
function wps_alerts_customise_admin_options () {

	echo '<table class="form-table">';

	echo '<tr valign="top">';
	echo '<td scope="row">';
		echo '<label for="wps_alerts_customise_before">'.__('HTML added to top of alert', WPS2_TEXT_DOMAIN).'</label>';
	echo '</td>';
	echo '<td>';
		echo '<textarea name="wps_alerts_customise_before" style="width:500px;height:150px;">'.stripslashes(get_option('wps_alerts_customise_before')).'</textarea>';
		echo '<span class="description">';
			echo '<br />'.__('Enter HTML that will be added before the alert email.', WPS2_TEXT_DOMAIN);
		echo '</span>';
		echo '</td>';
	echo '</tr>';

	echo '<tr valign="top">';
	echo '<td scope="row">';
		echo '<label for="wps_alerts_customise_after">'.__('HTML added to bottom of alert', WPS2_TEXT_DOMAIN).'</label>';
	echo '</td>';
	echo '<td>';
		echo '<textarea name="wps_alerts_customise_after" style="width:500px;height:150px;">'.stripslashes(get_option('wps_alerts_customise_after')).'</textarea>';
		echo '<span class="description">';
			echo '<br />'.__('Enter HTML that will be added after the alert email.', WPS2_TEXT_DOMAIN);
		echo '</span>';
		echo '</td>';
	echo '</tr>';

	echo '</table>';

}

add_action( 'wps_alerts_admin_setup_form_save_hook', 'wps_alerts_customise_admin_options_save', 10, 1 );
function wps_alerts_customise_admin_options_save ($the_post) {

    if (isset($the_post['wps_alerts_customise_before'])):

        update_option('wps_alerts_customise_before', $the_post['wps_alerts_customise_before']);
        update_option('wps_alerts_customise_after', $the_post['wps_alerts_customise_after']);
    
    endif;

}
?>