<?php
/*
Template Name:community
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
                    <div class="detail_text community-profile">
						<!--Profile Head-->
						<div class="col-lg-12 col-md-12 col-xs-12 no-padding profile-head">
							<div class="col-lg-12 p-head-top no-padding">
								<div class="p-title">
									<span>community activities?</span>
								</div>
								<i class="fa fa-angle-down"></i>
								<div class="clear"></div>
								<div class="col-lg-4 col-md-4 col-xs-12 no-padding">
									<p><i class="fa fa-shield"></i>clubs</p>
									<div class="col-lg-6 col-md-6 col-xs-12">
										<a href="#favorite-club"><i class="fa fa-star"></i></a>
									</div>
									<div class="col-lg-6 col-md-6 col-xs-12">
										<a href="#follow-club"><i class="fa fa-plus"></i></a>
									</div>
								</div>
								
								<div class="col-lg-4 col-md-4 col-xs-12 no-padding">
									<p><i class="fa fa-globe"></i>countries</p>
									<div class="col-lg-6 col-md-6 col-xs-12">
										<a href="#favorite-country"><i class="fa fa-star"></i></a>
									</div>
									<div class="col-lg-6 col-md-6 col-xs-12">
										<a href="#follow-country"><i class="fa fa-plus"></i></a>
									</div>
								</div>
								
								<div class="col-lg-4 col-md-4 col-xs-12 no-padding">
									<p><i class="fa fa-user"></i>players</p>
									<div class="col-lg-6 col-md-6 col-xs-12">
										<a href="#favorite-player"><i class="fa fa-star"></i></a>
									</div>
									<div class="col-lg-6 col-md-6 col-xs-12">
										<a href="#follow-player"><i class="fa fa-plus"></i></a>
									</div>
								</div>
								
							</div>
							<div class="profile-head-bottom">Click on the STAR and PLUS buttons to quickly scroll to the favourite and following sections respectively.<i class="fa fa-remove close-div"></i></div>
						</div>
						
						<!--Profile Content-->
						<div class="col-lg-12 col-md-12 col-xs-12 no-padding profile-content">
							
							<!--show community activities-->
							  <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
								<div class="div-section no-padding user-activities">
									<div class="section-title"><i class="fa fa-group"></i>
										<p>community activities</p>
									</div>
									<!--add shortcode here-->
									<?php echo do_shortcode('[wps-alerts-activity-list]');?>
									<div class="profile-head-bottom">
										You can follow 100 people on Footysquare. Currently you are following 
										<span><?php echo wpw_fp_get_author_followers_count( $user_id );?></span> people.
										<span>invite</span> your friends to Footysquare or
										<span>follow more</span>
										<i class="fa fa-remove close-div"></i>
									</div>
							   </div>
							</div>
							
							<!--show favourite clubs-->
								<?php  echo do_shortcode('[getfavcustomposts post_type="club"]');?>
							
							<!--show followed clubs-->
								<?php  echo do_shortcode('[getfollowpost post_type="club"]');?>
							
							<!--show favourite countries-->
								<?php  echo do_shortcode('[getfavcustomposts post_type="country"]');?>
								
							<!--show followed countries-->
								<?php  echo do_shortcode('[getfollowpost post_type="country"]');?>
								
							<!--show favourite players-->
								<?php  echo do_shortcode('[getfavcustomposts post_type="player"]');?>
								
							<!--show followed players-->
								<?php  echo do_shortcode('[getfollowpost post_type="player"]');?>
								
							<script>
							jQuery( document ).ready(function() {
							  // show comment box
							  $('body').on('click', '.shout-comment-icon', function() {
								  post_id_value = $(this).attr('id');
								  post_id = '#postid-'+post_id_value;
									
									$(post_id).toggle("fade", function() {
											//$(post_id).css("display","block");
									  });
								});
							});
							</script>
								
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
 
