<?php
	//get all notifications which are unread
	$current_user_id=$_GET['uid'];
	$file_url = dirname(__FILE__).'/../../uploads/2015/01/notify/'.$current_user_id.'.txt';

	//check if file exist
	if (file_exists($file_url)) {
	
		include_once('../../../wp-config.php');
		include_once(ABSPATH . 'wp-includes/functions.php');
		global $wpdb;
		//echo "The file $filename exists";
		$get_notifications = $wpdb->get_results("SELECT * FROM `wp_notification` WHERE `status` = '0' AND `recipient_id` = $current_user_id AND `user_id` != $current_user_id");
		echo count($get_notifications);
		
	}
	else
		echo 0;
?>