<?php
/*
Template Name:Profile
*/
	
 	global $px_theme_option;
	$px_node = new stdClass();
  	get_header();
	
	global $current_user;
	get_currentuserinfo();
	
	//if there is some other user
	if(isset($_GET['user_id'])){
		//$current_user = get_userdata($_GET['user_id']);
		$user_id = $_GET['user_id'];
	}
	else
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
		
		$current_user_data = get_userdata($user_id);
		
		$user_name=$current_user_data->user_login;
		$first_name=$current_user_data->user_firstname;
		$last_name=$current_user_data->user_lastname;
		$user_joined_date=$current_user_data->user_registered;
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
						<!--Profile Head-->
						<div class="col-lg-12 col-md-12 col-xs-12 no-padding profile-head">
							<div class="col-lg-12 col-md-12 col-xs-12 p-head-top">
								<div class="p-title">
									<span><?php echo $user_name;?></span>
									<?php 
									
									//check if user is not following then don't show the profile picture
									//if($user_id == wp_get_current_user()->ID){
									?>
										
											<?php
												$follow_authors = wpw_fp_check_user_follow(wp_get_current_user()->ID);
												//var_dump($follow_authors);
												if($follow_authors){
													if(in_array($_GET['user_id'],$follow_authors))
													{
														echo do_shortcode('[wps-avatar text="Click here to change your photo" link=1 size="100"]'); 
														//echo '<label id="edit-profile">Edit Picture</label>';
													}
													else if((get_current_user_id()==$user_id && $user_id==$_GET['user_id']) || !(isset($_GET['user_id']))){
														if($current_user->ID==$user_id){
															//echo '<a href="?page_id=2123">';
																echo do_shortcode('[wps-avatar text="Click here to change your photo" size="100"]'); 
																echo do_shortcode('[wps-avatar-change label="Upload Photo"]'); 
																//echo '<label id="edit-profile">Edit Picture</label>';
															//echo '</a>';
														}
														else{
															echo do_shortcode('[wps-avatar text="Click here to change your photo" link=0 size="100"]'); 
															
														}
													}
												}
												else if((get_current_user_id()==$user_id && $user_id==$_GET['user_id']) || !(isset($_GET['user_id']))){
													if($current_user->ID==$user_id){
														echo do_shortcode('[wps-avatar text="Click here to change your photo" size="100"]'); 
														echo do_shortcode('[wps-avatar-change label="Upload Photo"]'); 
													}
												}
											?>
										
									<?php //}?>
								</div>
								<?php
									if($current_user->ID!=$user_id)
										echo do_shortcode('[wpw_follow_author_me author_id="'.$user_id.'" disablecount="true"][/wpw_follow_author_me]'); 
								?>
								<div class="col-lg-12 col-md-12 col-xs-12">
									
									<span class="follow-label">Followed by </span>
									<?php 
										wpw_fp_get_author_followers_Names($user_id);
										$follower_count=wpw_fp_get_author_followers_count( $user_id );
										if($follower_count==0)
											echo 'None';
										else if($follower_count>2)
										{
									?>
											<span class="follow-label">and</span>
											<span class="other-name"><?php echo $follower_count-2; ?></span>
											<span class="follow-label">others</span>
									<?php 
										}
									?>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-xs-12 no-padding p-meta country-content">
								<div class="col-lg-6 col-md-6 col-xs-12  b-info-1">
									<table>
									<tr>
										<td>
											Full Name :
										</td>
										<td>
											<?php if($first_name || $last_name) echo $first_name.'&nbsp;'.$last_name; else echo 'N/A';?>
										</td>
									</tr>
									<tr>
										<td>
											Country :
										</td>
										<td>
											<span class="flag flag-<?php get_country_code($user_country);?>"></span><?php echo strtoupper($user_country); ?>
										</td>
									</tr>
									<tr>
										<td>
											Joined :
										</td>
										<td>
											<?php echo date('F,Y', strtotime($user_joined_date));?>
										</td>
									</tr>
									<tr>
										<td>
											Bio :
										</td>
										<td class="p-bio">
											<?php echo do_shortcode('[wps-extended slug="bio"]');?>
										</td>
									</tr>
								</table>
								</div>
								<div class="col-lg-6 col-md-6 col-xs-12  b-info-2">
									<table>
									<tr>
										<td>
											Total Followers :
										</td>
										<td>
											<?php echo wpw_fp_get_author_followers_count( $user_id ); ?> followers
										</td>
									</tr>
									<tr>
										<td>
											Follower's Nationalities :
										</td>
										<td id="show-follower-flag">
											<?php wpw_fp_get_author_followers_Names($user_id,'get countries');?>
											<!--<p class="text-uppercase">other countries (11k)</p>-->
										</td>
									</tr>
									
								</table>
								</div>
							</div>
						</div>
						
						<!--Profile Content-->
						<div class="col-lg-12 col-md-12 col-xs-12 no-padding profile-content">
							
							<!--show favourite players-->
							  <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
								<div class="div-section no-padding user-activities row-centered">
									<div class="section-title"><i class="fa fa-group"></i>
										<p>favorite players</p>
									</div>
									<!--add shortcode here-->
									<?php echo do_shortcode('[getfavposts posttype="player"]');?>
							   </div>
							</div>
							
							<!--show favourite players-->
							  <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
								<div class="div-section no-padding user-activities row-centered">
									<div class="section-title"><i class="fa fa-group"></i>
										<p>favorite country</p>
									</div>
									<!--add shortcode here-->
									<?php echo do_shortcode('[getfavposts posttype="country"]');?>
							   </div>
							</div>
							
							<!--show user activities-->
							  <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
								<div class="div-section no-padding user-activities">
									<div class="section-title"><i class="fa fa-user"></i>
										<p>latest activities</p>
									</div>
									<!--add shortcode here-->
									<?php echo do_shortcode('[wps-alerts-activity-list]');?>
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
 <script>
 jQuery( document ).ready(function() {
	 jQuery(".see-all").click(function(){
		jQuery(".user-latest-activity").toggleClass( "set-all-height" );
		jQuery(".see-all").toggleClass( "hide-see-all-height" );
	});
});
//alert('<?php echo $_GET['user_id'];?>');
 </script>
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
 
