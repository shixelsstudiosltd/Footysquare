<?php
/*
Template Name: Account
*/
	
 	global $px_theme_option;
	$px_node = new stdClass();
  	get_header();
	
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	
	if ( is_user_logged_in()) {
			
	$px_layout = '';
	if (have_posts()):
		while (have_posts()) : the_post();
		$post_xml = get_post_meta($post->ID, "post", true);	
		if ( $post_xml <> "" ) {
			$px_xmlObject = new SimpleXMLElement($post_xml);
			$px_layout = $px_xmlObject->sidebar_layout->px_layout;
			if ( $px_layout == "left") {
				$px_layout = "col-md-9";
			}
			else if ( $px_layout == "right" ) {
				$px_layout = "col-md-9";
			}
			else {
				$px_layout = "col-md-12";
			}
		}else{
			$px_layout = "col-md-12";
			$image_url = '';
			$px_xmlObject = new stdClass();
			$px_xmlObject->var_pb_post_social_sharing = '';
			$px_xmlObject->var_pb_post_featured = '';
			$px_xmlObject->var_pb_post_attachment = '';
			$px_xmlObject->var_pb_post_author = '';
		}
		$width = 768;
		$height = 403;
		$image_url = px_get_post_img_src($post->ID, $width, $height);	
		
		$current_user = get_userdata($user_id);
		
		$user_name=$current_user->user_login;
		$first_name=$current_user->user_firstname;
		$last_name=$current_user->user_lastname;
		$user_joined_date=$current_user->user_registered;
		$user_country=do_shortcode('[wps-usermeta meta="wpspro_country"]');
		?>
            <!--Left Sidebar Starts-->

	<?php if ($px_layout == 'col-md-9' and $px_xmlObject->sidebar_layout->px_layout == 'left'){ ?>

    <aside class="sidebar-left col-md-3"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_xmlObject->sidebar_layout->px_sidebar_left) ) : ?><?php endif; ?></aside>
	
	
		
    <?php wp_reset_query();} ?>
	<!--Left Sidebar End-->
    <div class="<?php echo $px_layout; ?>" >
    	<?php px_page_title();?>
		<div class="blog blog_detail">
            <article>
                <?php if($image_url <> '' && $px_xmlObject->var_pb_post_featured == 'on'){?>
                <figure>
                    <img src="<?php echo $image_url;?>" alt="<?php the_title();?>">
                </figure>
                <?php }?>
                <div class="pix-content-wrap">
                    <div class="detail_text user-profile">
						
						<!--Profile Content-->
						<div class="col-lg-12 col-md-12 col-xs-12 no-padding account-setting">
							
							<!--show favourite players-->
							  <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
								<div class="div-section">
									<div class="section-title"><i class="fa fa-group"></i>
										<p>Account Management</p>
									</div>
									
									<!--edit account-->
									<div class="ac-setting-sec">
										<div class="col-lg-12 col-md-12 col-xs-12 edit-profile-content">
											<p class="section-second-title"><i class="fa fa-edit"></i>edit account details :</p>
											<div class="edit-padding"><?php echo do_shortcode('[wps-usermeta-change]');?></div>
										</div>
									</div>
									
									<!--invite using social media-->
									<div class="ac-setting-sec">
										<div class="col-lg-12 col-md-12 col-xs-12">
											<p class="section-second-title"><i class="fa fa-envelope-o"></i>invite your friends to join footysquare :</p>
											<div class="invite-friends">
												<div class="col-lg-4 col-md-4 col-xs-12"><span id="via-fb"></span></div>
												<div class="col-lg-4 col-md-4 col-xs-12"><span id="via-tw"></span></div>
												<div class="col-lg-4 col-md-4 col-xs-12"><span id="via-mail"></span></div>
											</div>
										</div>
									</div>
									
									<!--notification setting-->
									<div class="ac-setting-sec">
										<div class="col-lg-12 col-md-12 col-xs-12 notification-sec">
											<p class="section-second-title"><i class="fa fa-bell-o"></i>change notification settings :</p>
											<div class="col-lg-12 col-md-12 col-xs-12">
												<div class="notification-alert">send me notification when :</div>
												
												<!--notification form-->
												<?php
												if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == "update_notification_settings") {
													
													//1
													//like_notify_footy_1
													$like_notify_footy_1 = $_POST['like_notify_footy_1'];
													//email_notify_1
													$email_notify_1 = $_POST['email_notify_1'];
													//notify_privacy_1
													$notify_privacy_1 = $_POST['notify_privacy_1'];
													
													//3
													//like_notify_footy_3
													$like_notify_footy_3 = $_POST['like_notify_footy_3'];
													//email_notify_3
													$email_notify_3 = $_POST['email_notify_3'];
													//notify_privacy_3
													$notify_privacy_3 = $_POST['notify_privacy_3'];
													
													//4
													//like_notify_footy_4
													$like_notify_footy_4 = $_POST['like_notify_footy_4'];
													//email_notify_4
													$email_notify_4 = $_POST['email_notify_4'];
													//notify_privacy_4
													$notify_privacy_4 = $_POST['notify_privacy_4'];
													
													//5
													//like_notify_footy_5
													$like_notify_footy_5 = $_POST['like_notify_footy_5'];
													//email_notify_5
													$email_notify_5 = $_POST['email_notify_5'];
													//notify_privacy_5
													$notify_privacy_5 = $_POST['notify_privacy_5'];
													
													$noti_fields = array($like_notify_footy_1, $email_notify_1, $notify_privacy_1,$like_notify_footy_3, $email_notify_3, $notify_privacy_3,$like_notify_footy_4, $email_notify_4, $notify_privacy_4,$like_notify_footy_5, $email_notify_5, $notify_privacy_5);

													for($i=0;$i<12;$i++){
														if($noti_fields[$i]==null)
															$noti_fields[$i]=0;
													}

													//like
													$update_like = '1'.$noti_fields[0].$noti_fields[1].$noti_fields[2];
													//mention
													$update_mention = '1'.$noti_fields[3].$noti_fields[4].$noti_fields[5];
													//comment
													$update_comment = '1'.$noti_fields[6].$noti_fields[7].$noti_fields[8];
													//follow
													$update_follow = '1'.$noti_fields[9].$noti_fields[10].$noti_fields[11];
													
													$get_notifications = $wpdb->get_results("SELECT * FROM `wp_notification_settings` WHERE `user_id`= $user_id");
													//check user already has setting included or not
													if(count($get_notifications)==null)
														$wpdb->query("INSERT INTO `wp_notification_settings`(`user_id`, `noti_setting_footy`, `noti_setting_mention`, `noti_setting_comment`, `noti_setting_follow`) VALUES ($user_id,$update_like,$update_mention,$update_comment,$update_follow)");
													else
														$wpdb->query("UPDATE `wp_notification_settings` SET `noti_setting_footy`=$update_like,`noti_setting_mention`=$update_mention,`noti_setting_comment`=$update_comment,`noti_setting_follow`=$update_follow  WHERE `user_id`= $user_id");
												}
												?>
												
												<?php
												//get notification settings
												global $wpdb;
												$get_noti_settings = $wpdb->get_results("SELECT * FROM `wp_notification_settings` WHERE `user_id` = $user_id");
												if($get_noti_settings){
													foreach($get_noti_settings as $get_noti_setting){
													
														$noti_setting_footy = str_split($get_noti_setting->noti_setting_footy);
														$noti_setting_mention = str_split($get_noti_setting->noti_setting_mention);
														$noti_setting_comment = str_split($get_noti_setting->noti_setting_comment);
														$noti_setting_follow = str_split($get_noti_setting->noti_setting_follow);
														
													}
												}
												
												$notification_settings_fields = array($noti_setting_footy,$noti_setting_mention,$noti_setting_comment,$noti_setting_follow);
												$get_main_settings = array();
												$k = 0;
												for($i=0;$i<4;$i++){
													$noti_set_field = $notification_settings_fields[$i];
													for($j=0;$j<4;$j++){
														if($noti_set_field[$j]==0)
															$get_main_settings[$k]='';
														else
														{
															if($j!=3)
																$get_main_settings[$k]='checked';
															else
																$get_main_settings[$k]=$noti_set_field[$j];
														}
														$k++;
														//echo $get_main_settings[$k];
													}
												}												
												
												$selcted = 'selected';
												
												?>
												<form method="post" name="update_notification_settings" action="">
													<p class="section-second-title"><i class="fa fa-thumbs-up"></i>someone likes my post :</p>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>Notify me on Footysquare :</p>
														<input type="checkbox" name="like_notify_footy_1" value="1" <?php echo $get_main_settings[1];?> >
													</div>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>Notify me via mail :</p>
														<input type="checkbox" name="email_notify_1" value="1" <?php echo $get_main_settings[2];?>>
													</div>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>From :</p>
														<select name="notify_privacy_1">
															<option value="1" <?php if($get_main_settings[3]==1) echo $selcted;?>>EVERYONE</option>
															<option value="2" <?php if($get_main_settings[3]==2) echo $selcted;?>>FRIENDS ONLY</option>
															<option value="3" <?php if($get_main_settings[3]==3) echo $selcted;?>>ONLY ME</option>
														</select>
													</div>
													
													<p class="section-second-title">@ someone mention me in their post/comments :</p>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>Notify me on Footysquare :</p>
														<input type="checkbox" name="like_notify_footy_3" value="1" <?php echo $get_main_settings[5];?>>
													</div>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>Notify me via mail :</p>
														<input type="checkbox" name="email_notify_3" value="1" <?php echo $get_main_settings[6];?>>
													</div>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>From :</p>
														<select name="notify_privacy_3">
															<option value="1" <?php if($get_main_settings[7]==1) echo $selcted;?>>PEOPLE I FOLLOW</option>
															<option value="2" <?php if($get_main_settings[7]==2) echo $selcted;?>>FRIENDS ONLY</option>
															<option value="3" <?php if($get_main_settings[7]==3) echo $selcted;?>>ONLY ME</option>
														</select>
													</div>
												
													<p class="section-second-title"><i class="fa fa-comment-o"></i>someone comment on my post :</p>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>Notify me on Footysquare :</p>
														<input type="checkbox" name="like_notify_footy_4" value="1" <?php echo $get_main_settings[9];?>>
													</div>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>Notify me via mail :</p>
														<input type="checkbox" name="email_notify_4" value="1" <?php echo $get_main_settings[10];?>>
													</div>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>From :</p>
														<select name="notify_privacy_4">
															<option value="1" <?php if($get_main_settings[11]==1) echo $selcted;?>>EVERYONE</option>
															<option value="2" <?php if($get_main_settings[11]==2) echo $selcted;?>>FRIENDS ONLY</option>
															<option value="3" <?php if($get_main_settings[11]==3) echo $selcted;?>>ONLY ME</option>
														</select>
													</div>
												
													<p class="section-second-title"><i class="fa fa-check"></i>someone follow me :</p>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>Notify me on Footysquare :</p>
														<input type="checkbox" name="like_notify_footy_5" value="1" <?php echo $get_main_settings[13];?>>
													</div>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>Notify me via mail :</p>
														<input type="checkbox" name="email_notify_5" value="1" <?php echo $get_main_settings[14];?>>
													</div>
													<div class="col-lg-4 col-md-4 col-xs-12">
														<p>From :</p>
														<select name="notify_privacy_5">
															<option value="1" <?php if($get_main_settings[15]==1) echo $selcted;?>>N/A</option>
															<option value="2" <?php if($get_main_settings[15]==2) echo $selcted;?>>FRIENDS ONLY</option>
															<option value="3" <?php if($get_main_settings[15]==3) echo $selcted;?>>ONLY ME</option>
														</select>
													</div>
													<div class="col-lg-12 col-md-12 col-xs-12 text-center">
														<input type="hidden" name="action" value="update_notification_settings" />
														<input type="submit" id="update_notification_settings" class="btn-green" value="Update">
													</div>
												</form>
											</div>
										</div>
									</div>
									
									
									<!--deactivate account-->
									<div class="ac-setting-sec account-deactive">
										<div class="col-lg-12 col-md-12 col-xs-12">
											<p class="section-second-title"><i class="fa fa-power-off"></i>deactivate account :</p>
											<div id="deactivate-options">
												
												<label><input type="radio" name="deactivate_account" value="1">I don't feel safe on Footysquare.</label>
												<label><input type="radio" name="deactivate_account" value="2">I don't find Footysquare useful.</label>
												<label><input type="radio" name="deactivate_account" value="2">My account was hacked.</label>
												<label><input type="radio" name="deactivate_account" value="2">I spend too much time using Footysquare.</label>
												<label><input type="radio" name="deactivate_account" value="2">I get too many emails, invitations, and requests from Footysquare.</label>
												<label><input type="radio" name="deactivate_account" value="2">I have another Footysquare account.</label>
												<label><input type="radio" name="deactivate_account" value="2">I don't understand how to use Footysquare.</label>
												<label><input type="radio" name="deactivate_account" value="2">I have a privacy concern.</label>
												<label><input type="radio" name="deactivate_account" value="2" checked>This is temporary. I'll be back.</label>
												<label><input type="radio" id="reason_other" name="deactivate_account" value="2">Other</label>
												<textarea name="opt-other" id="other-reason" placeholder="Please explain further..."></textarea>
												
												<?php 
												$obj_deactive=new ja_disable_users;
												$obj_deactive->deactivate_user_account();?>
											</div>
										</div>
									</div>
									
									<script>
									jQuery(document).ready(function() {
									   jQuery('input[type="radio"]').click(function() {
										   if($(this).attr('id') == 'reason_other') {
												jQuery('#other-reason').show("slow");           
										   }
										   else {
												jQuery('#other-reason').hide("slow");   
										   }
									   });
									});
									</script>
									
							   </div>
							</div>
							
						</div>
					</div>
				</div>
            </article>
        </div>
        
	</div>
	 <style>
.footer-widget{
	top:100px;
}
 </style>
 
     <?php
	endwhile;   
	endif;
 	if ( $px_layout == 'col-md-9' and $px_xmlObject->sidebar_layout->px_layout == 'right'){ ?>
 		<aside class="sidebar-right col-md-3">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_xmlObject->sidebar_layout->px_sidebar_right) ) : ?><?php endif; ?>
        </aside>
 	<?php }
	get_footer();
	}
	else
	wp_redirect( home_url() ); exit;
 ?>
 
