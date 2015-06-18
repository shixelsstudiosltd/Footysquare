<?php
// Admin
require_once('wps_alerts_customise_admin.php');

// Apply top/bottom HTML to email alerts
add_filter( 'wps_alerts_scheduled_job_content_filter', '__wps__wpspro_alerts_customise_alert_email', 10, 2 );
function __wps__wpspro_alerts_customise_alert_email ($content, $post_id) {

	return stripslashes(get_option('wps_alerts_customise_before')) . $content . stripslashes(get_option('wps_alerts_customise_after'));

}


?>