<?php
// AJAX functions for activity
add_action( 'wp_ajax_wps_calendar_comment_delete', 'wps_calendar_comment_delete' ); 

/* Delete Comment */
function wps_calendar_comment_delete() {
	wp_delete_comment($_POST['comment_id']);
}

?>
