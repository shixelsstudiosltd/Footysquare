<?php
/*
Template Name: Full Notification
*/
	
  	get_header();
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	
	if ( is_user_logged_in()) {
		$current_user = get_userdata($user_id);
	?>
    
    <div class="col-lg-12 col-md-12 col-xs-12">
		<div class="blog blog_detail">
                <div class="pix-content-wrap">
                    <div class="detail_text user-profile">
						
						<!--Profile Content-->
						<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
							
							<!--show favourite players-->
							  <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
								<div class="div-section">
									<div class="section-title"><i class="fa fa-group"></i>
										<p>All Notifications</p>
									</div>
									<div class="col-lg-12 col-md-12 col-xs-12">
										<?php
											show_all_notifications();
										?>
									</div>
									
							   </div>
							</div>
							
						</div>
					</div>
				</div>
        </div>
        
	</div>
	 <style>
.footer-widget{
	top:100px;
}
 </style>
 
 	<?php 
	get_footer();
	}
	else
	wp_redirect( home_url() ); exit;
 ?>
 
