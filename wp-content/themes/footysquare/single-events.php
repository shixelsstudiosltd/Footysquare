<?php
	get_header();
	global $px_node, $px_theme_option, $px_event_meta;
	$px_layout = '';
	$px_counter_events=1;
 while ( have_posts() ) : the_post();
 	$post_xml = get_post_meta($post->ID, "px_event_meta", true);	
	if ( $post_xml <> "" ) {
		$px_event_meta = new SimpleXMLElement($post_xml);
		$px_layout = $px_event_meta->sidebar_layout->px_layout;
		if ( $px_layout == "left") {
			$px_layout = "col-md-9";
		}
		else if ( $px_layout == "right" ) {
			$px_layout = "col-md-9";
		}
		else {
			$px_layout = "col-md-12";
		}
  	}
	$hours = '00';
	$mints = '00';
	$px_event_from_date = get_post_meta($post->ID, "px_event_from_date", true); 
	$px_event_from_date_time = get_post_meta($post->ID, "px_event_from_date_time", true);
	$year_event = date("Y", strtotime($px_event_from_date));
	$month_event = date("m", strtotime($px_event_from_date));
	$date_event = date("d", strtotime($px_event_from_date));
		$width = 1098;
		$height = 260;
		$image_url = px_get_post_img_src($post->ID, $width, $height);
		px_enqueue_countdown_script();
	?>
     <?php if ( $px_event_meta->sidebar_layout->px_layout <> '' and $px_event_meta->sidebar_layout->px_layout <> "none" and $px_event_meta->sidebar_layout->px_layout == 'left') : ?>
                <aside class="col-md-3">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_event_meta->sidebar_layout->px_sidebar_left) ) : endif; ?>
                 </aside>
        <?php wp_reset_query(); endif; ?>
     <div class="col-lg-12 col-md-12 col-xs-12 lightbox">
     	<?php px_page_title();?>
                    	<div class="match-board">
                        	<div class="match-detail">
							
								<?php			
									echo '<div class="col-lg-12 col-md-12">
									<div class="div-section match-chat">
										<div class="section-title"><i class="fa fa-shield"></i>
										<p>match chat</p></div>
											<div class="match-board-inner">
												<div class="row result-row">
													<div class="style-post-img">
														<a href="';
															the_permalink();
														echo '">';
														
															$team1_row = px_get_term_object($px_event_meta->var_pb_event_team1);
															$team_img1 = px_team_data_front($team1_row->term_id);
															if($team_img1[0] <> '')
															{
															?>
																<img alt="" src="<?php echo $team_img1[0];?>">
															<?php 
															}
															else { echo '<div class="default_ball"></div>'; }
														
													echo '</div>';
													
													echo '<h1 class="match-result">'.$px_event_meta->event_score.'</h1>';
														/*Reteriving second image*/
													echo '<div class="style-post-img">';	
															$team2_row = px_get_term_object($px_event_meta->var_pb_event_team2);
															$team_img2 = px_team_data_front($team2_row->term_id);
															if($team_img2[0] <> ''){
															?>
																<img alt="" src="<?php echo $team_img2[0];?>">
															<?php }
															else { echo '<div class="default_ball"></div>'; }
													echo '</div>';
													
													echo '</a></div><a href="';
													the_permalink();
													echo '" class="match-chat-title">';
													
													$post_title = $team1_row->name.' vs '.$team2_row->name;
													$post_title = substr($post_title,0,30);
													if((strlen($post_title))>28)
														$post_title = $post_title.'...';
														echo $post_title;
													echo '</a>';
													echo '<div class="match-league">';
													$match_type = get_field('match_type');
													if(!$match_type)
														echo '<span class="event-type">N/A</span>';
													else
														echo '<span class="event-type">'.$match_type->name.'</span>';

													$current_date = date('m/d/yy');
													if($current_date > $px_event_from_date)
														echo '<div class="match-on">match in progress</div>';
													else
														echo '<div class="match-off">match is ended</div>';
														
													echo '</div><div class="match-league">';
													echo '<i class="fa fa-calendar"></i><time>'.date_i18n(get_option('date_format'), strtotime($px_event_from_date)).'</time>';
													$post_time= get_the_modified_time('Y-m-j g:i:s');
                                                    echo '<i class="fa fa-map-marker"></i>'.$px_event_meta->event_address;
													echo'</div>';
													/*
													echo '<div class="icons">
															<span class="float-r">';
																echo get_time_difference($post_time);
														echo '</span><i class="fa fa fa-clock-o"></i>
														</div>';
													*/
											echo '</div>
										</div>
									</div>';
								?>
							</div>
                        </div>
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
        <?php 
 endwhile;
get_footer();