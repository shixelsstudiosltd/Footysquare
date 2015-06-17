<?php
 	global $px_theme_option;
	$px_node = new stdClass();
  	get_header();
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
		$post_id=$post->ID;		
		$post_title=get_the_title();
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
                <div class="pix-content-wrap player-page">
                    <div class="detail_text">
						<!--Country Head Starts-->
						<div class="col-lg-12 no-padding">
							<div class="col-lg-12 player-page-head">
								<div class="col-lg-10 col-md-8 col-xs-4">
									<div id="player-title"><?php the_title();?></div>
								</div>
								
								<?php 
									$post_object = get_field('current_club');
									if( $post_object ): 
										// override $post
										$post = $post_object;
										setup_postdata( $post );
											$the_permalink = get_the_permalink();
											$the_title = get_the_title();
											$club_flag = get_the_post_thumbnail();
										wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly 
									endif;
								?>
								
								<div class="col-lg-2 col-md-4 col-xs-8">
									<div class="player-flag col-lg-6 col-md-6 col-xs-6">
										<?php 
										$country_flag = get_field('country_flag'); 
										$country_flag_url = $country_flag['url'];
										if($country_flag_url) 
										{
											$country_flag_url_val = $country_flag_url;
										}
										else {
											$country_flag_url_val=get_site_url().'/wp-content/themes/footysquare/images/flags/eng.png';
										 } 
										echo '<img src="'.$country_flag_url_val.'" alt="country-flag">';
										?>
									</div>
									
									<div class="player-flag col-lg-6 col-md-6 col-xs-6 club-flag">
										<?php 
										if($club_flag)
											echo $club_flag; 
										 else
											echo '<img src="wp-content/themes/footysquare/images/flags/span.png" alt="country-flag">';
										 ?>
									</div>
								</div>
							</div>
							<!--country flag and follow section-->
							<div class="col-lg-2 col-md-2 col-xs-12 country-flag-sec no-pad-left">
								<?php the_post_thumbnail( 'thumbnail' );?>
								<p class="footy-btn"><i class="fa fa-star"></i><span><?php if (function_exists('wpfp_link')) { wpfp_link(); } ?></span></p>
								<!--Follow Btn-->
								<?php echo do_shortcode('[wpw_follow_me disablecount="true" followtext="FOLLOW" followingtext="UNFOLLOW" unfollowtext="UNFOLLOW"][/wpw_follow_me]');?>
								<p class="follower-count"><?php echo wpw_fp_get_post_followers_count(get_the_ID());?> FOLLOWERS</p>
							</div>
							<!--Country Head Ends-->
							<div class="country-content col-lg-10 col-md-10 col-xs-12 no-pad-right">
						
								<!--Basic info-->
								<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
									<div class="div-section no-padding">
									<div class="section-title"><p>basic info : </p></div>
									<div class="col-lg-6 col-md-6 col-xs-12 b-info-1">
										<table>
											<tr>
												<td>Date of Birth :</td>
												<td class="text-upper">
													<?php 
														//dob
														the_field('dob');
														
														//birth city
														$birth_city = get_field('birth_city');
														if($birth_city)
															echo '<div class="small-text">; in </div>'.$birth_city;
														
														//country flag
														$player_country = get_field('nationality');
														
															echo ' <span class="flag flag-';
															echo get_country_code($player_country);
															echo '"></span>';
													?>
												</td>
											</tr>
											<tr>
												<td>Age :</td>
												<td><?php the_field('age');?></td>
											</tr>
											<tr>
												<td>Nationality :</td>
												<td>
													<?php
														echo $player_country; 
														echo ' <span class="flag flag-';
														echo get_country_code($player_country);
														echo '"></span>';
													?>
												</td>
											</tr>
											<tr>
												<td>Height :</td>
												<td><?php the_field('height');?></td>
											</tr>
										</table>
										
										
									</div>
									<div class="col-lg-6 col-md-6 col-xs-12 b-info-2">
										<table>
											<tr>
												<td>Current Club :</td>
												<td>
													<div>
														<a href="<?php echo $the_permalink; ?>"><?php echo $the_title; ?></a>
													</div>
												</td>
											</tr>
											<tr>
												<td>Position :</td>
												<td class="text-upper"><?php the_field('position');?></td>
											</tr>
										</table>
									</div>
									
							   </div>
								</div>
								
								<!-- player gallery -->
								<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
									<div class="div-section no-padding">
										<div class="section-title"><p>player gallery </p></div>
										<div id="player-slider">
											<?php 
											$images = get_field('player_gallery');

											if( $images ): ?>
												
												<div class="player-slider">
													
														<?php foreach( $images as $image ): ?>
															<div class="slide">
																<a class="fancybox" href="<?php echo $image['sizes']['large']; ?>" data-fancybox-group="gallery">
																	<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
																</a>
															</div>
														<?php endforeach; ?>
													
												</div>
											<?php endif; ?>
											
										</div>
									</div>
								</div>
							
							</div>
							
							<link href="<?php echo get_site_url(); ?>/wp-content/themes/footysquare/css/jquery.bxslider.css" rel="stylesheet" type="text/css" />
							<script type="text/javascript" src="<?php echo get_site_url(); ?>/wp-content/themes/footysquare/scripts/frontend/jquery.bxslider.js"></script>
							
							<script>
								jQuery(document).ready(function(){
								  jQuery('.player-slider').bxSlider({
									slideWidth: 200,
									minSlides: 5,
									maxSlides: 15,
									slideMargin: 10
								  });
								});
							</script>
							
							<!--show shoutbox thread-->
							 
							<?php
							//update front end post
							if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == "frontend_post_add") 
							{
								
								$content   = $_POST['post_content'];
								$pid   = $_POST['postid'];
								$title = substr($content, 0, 50);
								$post_type = 'wps_forum_post';
								
								if($title==null && $content==null)
									return false;
									
								//add_filter('wp_insert_post_data', 'update_post_slug');
								//the array of arguements to be inserted with wp_insert_post
								$update_post = array(
								'ID'           => $pid,
								'post_title'    => $title,
								'post_content'  => $content 
								);
								
								//print_r($update_post);
								wp_update_post($update_post);
								//remove_filter('wp_insert_post_data', array(&$this, 'update_post_slug'));
							}
							
							?>
							<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
								<!--shoutbox forum starts-->
								<div class="col-lg-10 col-md-10 col-xs-12 no-pad-left">
									<div class="div-section no-padding" id="shoutbox">
										<?php do_shortcode('[shoutbox postid="'.get_the_ID().'"]');?>
									</div>
								</div>
								
								<!--shoutbox forum ends-->
								<div class="col-lg-2 col-md-2 col-xs-12 no-pad-right">
									<?php
										//Ad Sidebar Banner
										
										$sidebar_banner = get_field('sidebar_banner'); 
										$side_banner= $sidebar_banner['url'];
										if($sidebar_banner['url']==null) 
												$side_banner=get_site_url().'/wp-content/uploads/2014/11/country-banner.jpg';
										echo '<div class="col-lg-12 col-md-12 col-xs-12 no-padding sidebar-banner"><img src="'.$side_banner.'" alt="sidebar banner"></div>';
									?>
								 </div>
							</div>
							
						</div>
					</div>
				</div>
            </article>
        </div>
        
	</div>
     <?php
	endwhile;   
	endif;
 	if ( $px_layout == 'col-md-9' and $px_xmlObject->sidebar_layout->px_layout == 'right'){ ?>
 		<aside class="sidebar-right col-md-3">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_xmlObject->sidebar_layout->px_sidebar_right) ) : ?><?php endif; ?>
        </aside>
 	<?php }
	get_footer();
 ?>
 
 <style>
 .div-section{
	display:block;
}
.footer-widget{
	top:100px;
}
 </style>
 <script>
	
			<?php $sec=$_GET['section'];?>
			$('html, body').animate({
				scrollTop: $("#<?php echo $sec;?>").offset().top-150
			}, 500);
			//$('body').animate( { scrollTop: 100 + 'px' }, 2250);
		
</script>
