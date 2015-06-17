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
                <div class="pix-content-wrap">
                    <div class="detail_text">
						<!--Country Head Starts-->
						<div class="col-lg-12 no-padding">
							<div class="col-lg-12 text-center"><h1><?php the_title();?></h1></div>
							<!--country flag and follow section-->
							<div class="col-lg-2 col-md-2 col-xs-12 country-flag-sec no-pad-left">
								<?php the_post_thumbnail( 'thumbnail' );?>
								<p class="footy-btn"><i class="fa fa-star"></i><span><?php if (function_exists('wpfp_link')) { wpfp_link(); } ?></span></p>
								<!--Follow Btn-->
								<?php echo do_shortcode('[wpw_follow_me disablecount="true" followtext="FOLLOW" followingtext="UNFOLLOW" unfollowtext="UNFOLLOW"][/wpw_follow_me]');?>
								<p class="follower-count"><?php echo wpw_fp_get_post_followers_count(get_the_ID());?> FOLLOWERS</p>
							</div>
							
							<!--country cover section-->
							<div class="col-lg-10 col-md-10 col-xs-12 country-cover no-pad-right">
								<?php $cover_photo = get_field('cover_photo'); 
										$cover_pic= $cover_photo['url'];
										if($cover_photo['url']==null) 
												$cover_pic=get_site_url().'/wp-content/themes/footysquare/images/country/cover.jpg';
								?>
								<div class="c-cover-sec" style="background:url('<?php echo $cover_pic; ?>')no-repeat;">
									<div class="col-lg-12 col-md-12 col-xs-12 no-padding cover-sec-content">
										<!--cover section 1-->
										<div class="col-lg-4 col-md-4 col-xs-12 cover-sec">
											<div class="col-lg-3 col-md-3 col-xs-3">
												<span class="c-icon c-cup"></span>
											</div>
											<div class="col-lg-7 col-md-7 col-xs-7">
												<p class="c-sec-title">UEFA CHAMPIONS LEAGUE</p>
												<p class="c-sec-value">
													<?php if(get_field('uefa_champions_league')==null) 
																echo '<span>N/A</span>'; 
															else 
																echo get_field('uefa_champions_league');
													?>
												</p>
											</div>
										</div>
										
										<!--cover section 2-->
										<div class="col-lg-4 col-md-4 col-xs-12 cover-sec">
											<div class="col-lg-3 col-md-3 col-xs-3">
												<span class="c-icon c-bell"></span>
											</div>
											<div class="col-lg-7 col-md-7 col-xs-7">
												<p class="c-sec-title">UEFA SUPER CUP</p>
												<p class="c-sec-value">
												<?php if(get_field('uefa_super_cup')==null) 
																echo '<span>N/A</span>'; 
															else 
																echo get_field('uefa_super_cup');
													?>
												</p>
											</div>
										</div>
										
										<!--cover section 3-->
										<div class="col-lg-4 col-md-4 col-xs-12 cover-sec">
											<div class="col-lg-3 col-md-3 col-xs-3">
												<span class="c-icon c-rank"></span>
											</div>
											<div class="col-lg-7 col-md-7 col-xs-7">
												<p class="c-sec-title">UEFA CUP WINNER'S CUP</p>
												<p class="c-sec-value">
												<?php if(get_field('uefa_cup_winnerss_cup')==null) 
																echo '<span>N/A</span>'; 
															else 
																the_field('uefa_cup_winnerss_cup');
													?>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--Country Head Ends-->
						
						<div class="country-content col-lg-12 col-md-12 col-xs-12 no-padding">
						
						<!--Basic info-->
						  <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
							<div class="div-section no-padding">
								<div class="section-title"><p>basic info : </p></div>
								<div class="col-lg-6 col-md-6 col-xs-12 b-info-1">
									<table>
										<tr>
											<td>Competition :</td>
											<td class="text-upper">
												<?php 
													$league_id=get_field('competition')->ID; 
													echo get_field('competition')->post_name; 
													echo get_the_post_thumbnail( $league_id, array( 20, 20) );
												?>
											</td>
										</tr>
										<tr>
											<td>Founded :</td>
											<td><?php the_field('founded');?></td>
										</tr>
										<tr>
											<td>Average Age :</td>
											<td><?php the_field('average_age');?></td>
										</tr>
										<tr>
											<td>Squad Size :</td>
											<td><?php the_field('squad_size');?></td>
										</tr>
										<tr>
											<td>Legionaries :</td>
											<td><?php the_field('legionaries');?></td>
										</tr>
										<tr>
											<td>Current A National Players :</td>
											<td class="text-upper"><?php the_field('current_a_national_players');?></td>
										</tr>
										<tr>
											<td>Manager :</td>
											<td class="text-upper"><?php the_field('manager');?></td>
										</tr>
									</table>
								</div>
								<div class="col-lg-6 col-md-6 col-xs-12 b-info-2">
									<table>
										<tr>
											<td>National Championship Titles :</td>
											<td><?php the_field('national_championship_titles');?></td>
										</tr>
										<tr>
											<td>National Cup Victories :</td>
											<td><?php the_field('national_cup_victories');?></td>
										</tr>
										<tr>
											<td>Stadium :</td>
											<td><?php the_field('stadium');?></td>
										</tr>
										<tr>
											<td>Current Transfer Record :</td>
											<td><?php the_field('current_transfer_record');?></td>
										</tr>
										<tr>
											<td>Market Value :</td>
											<td><?php the_field('market_value');?></td>
										</tr>
									</table>
								</div>
								
						   </div>
						  </div>
							
							<!--Match Box New-->
							<?php do_shortcode('[matchbox pid="'.get_the_ID().'"]'); ?>
							
							<!--Match Box-->
							<?php //do_shortcode('[matchbox country="'.get_the_title().'"]'); ?>
							
							<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
							
							<!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>-->
							
						   
							<?php //do_shortcode('[adbanner]');
							//Ad Banner
							$ad_banner = get_field('ad_banner'); 
							$banner= $ad_banner['url'];
							if($ad_banner['url']==null) 
									$banner=get_site_url().'/wp-content/themes/footysquare/images/banner.jpg';
							echo '<div class="col-lg-12 col-md-12 col-xs-12 no-padding ad-banner"><img src="'.$banner.'" alt="Club Ad Banner"></div>';
							?>
							
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
							
							<!--shoutbox forum starts-->
							
							<div class="col-lg-10 col-md-10 col-xs-12 no-pad-left">
								<div class="div-section no-padding" id="shoutbox">
									<?php do_shortcode('[shoutbox postid="'.get_the_ID().'"]');?>
								</div>
							</div>
							
							<!--shoutbox forum ends-->
							
							
							 <div class="col-lg-2 col-md-2 col-xs-12 no-pad-right">
								<div class="next-match">
									<div class="section-title">
										<p>Next Match</p>
									</div>
									<?php do_shortcode('[club-next-match term="'.get_the_title().'"]');?>
									
								</div>
								
								<div class="next-match">
									<div class="section-title">
										<p>Most active users</p>
									</div>
									<div id="show-follower-flag">
										<?php
											//echo do_shortcode('[get-term-id term="'.$post_title.'"]');
											wpw_fp_get_post_followers_country($post_id,'get countries','get user names');
										?>
									</div>
								</div>

								<?php
									//Ad Sidebar Banner
									
									$sidebar_banner = get_field('sidebar_banner'); 
									$side_banner= $sidebar_banner['url'];
									if($sidebar_banner['url']==null) 
											$side_banner=get_site_url().'/wp-content/uploads/2014/11/country-banner.jpg';
									echo '<div class="col-lg-12 col-md-12 col-xs-12 no-padding sidebar-banner"><img src="'.$side_banner.'" alt="sidebar banner"></div>';
								?>
							 </div>
							 
							<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
								<?php do_shortcode('[trending-player-by-term post_id='.$post_id.']');?>
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
	
			<?php 
				$sec=$_GET['section'];
			?>
			//alert('<?php echo $sec;?>');
			$('html, body').animate({
				scrollTop: $("#<?php echo $sec;?>").offset().top-150
			}, 500);
			//$('body').animate( { scrollTop: 100 + 'px' }, 2250);
		
</script>