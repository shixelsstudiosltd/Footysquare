<?php
	include_once('../../../wp-config.php');
	include_once(ABSPATH . 'wp-includes/functions.php');
	global $wpdb;
	//get all notifications which are unread
	
	$current_user_id = get_current_user_id();
	$upload_dir = wp_upload_dir();
	
		$get_notify_settings = $wpdb->get_results("SELECT * FROM `wp_notification_settings` WHERE `user_id` = $current_user_id");
		foreach($get_notify_settings as $notif_set){
			$get_like_setting = $notif_set->noti_setting_footy;
			$get_comment_setting = $notif_set->noti_setting_comment;
			$get_follow_setting = $notif_set->noti_setting_follow;
		}
		
		$get_notifications = $wpdb->get_results("SELECT * FROM `wp_notification` WHERE `recipient_id` = $current_user_id AND `user_id` <> $current_user_id ORDER BY `wp_notification`.`time` DESC limit 25");
		
		foreach($get_notifications as $notif){
		
			$noti_type = $notif->notification_type;
			$notify_user_id = $notif->user_id;
			$recipient_id = $notif->recipient_id;
			$post_id = $notif->postid;
			$comment_id = $notif->comment_id;
			$post_time = $notif->time;
			$status_val = $notif->status;
			
			//status
			if($status_val==1)
				$status='st-checked';
			
			$current_user = get_userdata($current_user_id);
			
			//notify by user
			$notify_by_user = get_userdata($notify_user_id);
			$by_user_name = $notify_by_user->user_login;
			
			//notify to the user
			$recipient_user = get_userdata($recipient_id);
			$recipient_user_name = $recipient_user->user_login;
			
			//comment content
			$comment_data = get_comment( $comment_id ); 
			$comment_content = $comment_data->comment_content;
			$comment_content = substr($comment_content,0,20);
			if(strlen($comment_content)>20)
				$comment_content = $comment_content.'...';
			
			//post title
			$post_data = get_post($post_id); 
			$post_title = $post_data->post_title;
			$post_title = substr($post_title,0,20);
			if(strlen($post_title)>20)
				$post_title = $post_title.'...';
			
			//user avatar
			$avatar = "[wps-avatar user_id=$notify_user_id size=32]";
			
			//get permalink
			////get post taxonomy
			
			$terms = get_the_terms( $post_id, 'wps_forum' );
			if ( $terms && ! is_wp_error( $terms ) ) : 
				foreach ( $terms as $term ) {
					$post_term_id = $term->term_id;
				}
			endif;
			
			$terms = get_terms( 'wps_forum' );
			 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				 foreach ( $terms as $term ) {
					//term meta
					if($term->term_id==$post_term_id){
						$term_id_value = $term->term_id;
						}
				 }
			 }
			$term__parent_post_id=get_term_meta($term_id_value,'parentpostid', true);
			
			//like
			//echo $get_like_setting;
			if($get_like_setting >= 1100){
				if($noti_type == 1){
					echo '<div class="notification-item '.$status.'">
							<a href="'.get_site_url().'/?p='.$term__parent_post_id.'&section='.$post_id.'">
								<div class="col-lg-2">
									'.do_shortcode($avatar).'
								</div>
								<div class="col-lg-10">
									<strong>'.$by_user_name.'</strong> likes your post <strong>'.$post_title.'</strong>
									<p><i class="fa fa-clock-o"></i>'.get_time_difference($post_time).'</p>
								</div>
							</a>
						 </div>';
				}
			
				//like comment
				if($noti_type == 2){
					echo '<div class="notification-item '.$status.'">
							<a href="#">
								<div class="col-lg-2">
									'.do_shortcode($avatar).'
								</div>
								<div class="col-lg-10">
									<strong>'.$by_user_name.'</strong> likes your comment <strong>'.$comment_content.'</strong>
									<p><i class="fa fa-clock-o"></i>'.get_time_difference($post_time).'</p>
								</div>
							</a>
						 </div>';
				}
			}
			
			//make comment
			if($noti_type == 3){
				echo '<div class="notification-item '.$status.'">
						<a href="#">
							<div class="col-lg-2">
								'.do_shortcode($avatar).'
							</div>
							<div class="col-lg-10">
								<strong>'.$by_user_name.'</strong> comment on your post <strong>'.$post_title.'</strong>
								<p><i class="fa fa-clock-o"></i>'.get_time_difference($post_time).'</p>
							</div>
						</a>
					 </div>';
			}
			
			//mention user
			if($noti_type == 4){
					echo '<div class="notification-item '.$status.'">
							<a href="'.get_site_url().'/?p='.$term__parent_post_id.'&section='.$post_id.'">
								<div class="col-lg-2">
									'.do_shortcode($avatar).'
								</div>
								<div class="col-lg-10">
									<strong>'.$by_user_name.'</strong> mentioned you in his post <strong>'.$post_title.'</strong>
									<p><i class="fa fa-clock-o"></i>'.get_time_difference($post_time).'</p>
								</div>
							</a>
						 </div>';
			}
			
			//follow user
			if($noti_type == 5){
				echo '<div class="notification-item '.$status.'">
						<a href="#">
							<div class="col-lg-2">
								'.do_shortcode($avatar).'
							</div>
							<div class="col-lg-10">
								<strong>'.$by_user_name.'</strong> follows you
								<p><i class="fa fa-clock-o"></i>'.get_time_difference($post_time).'</p>
							</div>
						</a>
					 </div>';
			}
			$status='';
		}
		
		
		$wpdb->query("UPDATE `wp_notification` SET `status`=1 WHERE `recipient_id` = $current_user_id");
		 
		 //delete notify file
		 //fclose($file_url);
		$file_url = $upload_dir['path'].'/notify/'.$current_user_id.'.txt';
		if (file_exists($file_url)) {
			unlink($file_url);
		}
	
?>