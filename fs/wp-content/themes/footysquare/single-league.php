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
                    <div class="detail_text rich_editor_text">
						<?php //px_posted_on(true,false,false,false,true,false);?>
						<div class="league-title col-lg-5 col-md-5 col-xs-12 no-padding">
							<div class="league-image"><img src="<?php echo $image_url;?>" alt="<?php the_title();?>"></div>
							<h2><?php the_title(); echo ' ('; the_field('league_country'); echo ')'; ?></h2>
						</div>
						<div class="other-league-title col-lg-6 col-md-6 col-xs-12">
							<h2>checkout other leagues : </h2>
							<?php echo do_shortcode( '[leagues lid="'.get_the_ID().'"]' );?>
						</div>
						<div class="league-content col-lg-12 col-md-12 col-xs-12 no-padding">
                    <?php 
                    //the_content();
                    //wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'Kings Club' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
                   ?>
				   
					<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">
					<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
					
					<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
					<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
					<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
					<script src="<?php get_site_url(); ?>/fs/wp-content/themes/footysquare/scripts/frontend/jquery.dataTables.yadcf.js"></script>
					
					<script>
					$(document).ready(function(){
					  $('#example').dataTable().yadcf([
							//{column_number : 1, text_data_delimiter: ",", filter_type: "auto_complete"},
							]);
					});
					</script>
				   <!--show league point table-->
				   <div class="div-section league-table">
						<div class="section-title">
							<img src="<?php echo $image_url;?>" alt="<?php the_title();?>" class="club-tb-icon">
							<p>clubs in <?php the_title();?></p>
						</div>
				   <?php echo do_shortcode( '[leaguept lid="'.get_the_ID().'"]' );?>
				   </div>
				   <!--show league point table-->
					   <div class="div-section league-table col-lg-12 col-md-12 col-xs-12 no-padding">
						<div class="section-title">
							<img src="<?php echo $image_url;?>" alt="<?php the_title();?>" class="club-tb-icon">
							<p>clubs in <?php the_title();?></p>
						</div>
					   <?php echo do_shortcode( '[leagueclubs lid="'.get_the_ID().'"]' ); ?>
					   </div>
					
					<?php //do_shortcode('[adbanner]');
							//Ad Banner
							$ad_banner = get_field('ad_banner'); 
							$banner= $ad_banner['url'];
							if($ad_banner['url']==null) 
									$banner=get_site_url().'/wp-content/themes/footysquare/images/banner.jpg';
							echo '<div class="col-lg-12 col-md-12 col-xs-12 no-padding ad-banner"><img src="'.$banner.'" alt="Club Ad Banner"></div>';
							?>
					
					<?php 
						$fetch_country = get_field('league_country');
					   
						 $fetch_league = get_field('league_forum');
					   $fetch_league_id=$fetch_league[0]->term_id;
					?>
					
					<script>
					$( document ).ready(function() {
					  // show comment box 1
					  $('body').on('click', '.shout-comment-icon', function() {
						  post_id_value = $(this).attr('id');
						  post_id = '#postid-'+post_id_value;
							
							$(post_id).toggle("fade", function() {
							  });
						});
					
					  // show comment box 2
					  $('body').on('click', '.shout-comment-icon-active', function() {
						  post_id_value = $(this).attr('id');
						  post_id = '#rpostid-'+post_id_value;
							
							$(post_id).toggle("fade", function() {
							  });
						});
					});
					</script>
					
					<p class="section-second-title">related thread :</p>
					 <!--show league recent thread-->
					 <div class="col-lg-6 col-md-6 col-xs-12 no-pad-left">
					  <div class="div-section no-padding recent-thread fs-sec-slider">
							<div class="section-title"><i class="fa fa-group"></i>
							<p>most recent thread in <?php echo $fetch_country;?></p></div>
					   <?php echo do_shortcode( '[recentthread lid="'.$fetch_league_id.'"]' );?>
					  </div>
					 </div> 
					  <!--show league active thread-->
					  <div class="col-lg-6 col-md-6 col-xs-12 no-pad-right">
						<div class="div-section no-padding active-thread fs-sec-slider">
							<div class="section-title"><i class="fa fa-group"></i>
							<p>most active thread in <?php echo $fetch_country;?></p></div>
					   <?php  echo do_shortcode( '[activethread lid="'.$fetch_league_id.'"]' );?>
					   </div>
					  </div>
					   
						<script src="<?php get_site_url(); ?>/fs/wp-content/themes/footysquare/scripts/frontend/jquery.bxslider.js"></script>

						<script>
						$('.threadslider').bxSlider({
						  mode: 'fade',
						  captions: true
						});
						</script>
					  
					</div>
					</div>
				
					 <?php 
					 $post_attachment = '';
					 if(!isset($px_xmlObject->var_pb_post_attachment)){
						 $post_attachment = 'on';
					 } else if (isset($px_xmlObject->var_pb_post_attachment) && $px_xmlObject->var_pb_post_attachment == "on"){
						 $post_attachment = $px_xmlObject->var_pb_post_attachment;
					 }
					 if (isset($post_attachment) && $post_attachment == "on"){
					$args = array(
					   'post_type' => 'attachment',
					   'numberposts' => -1,
					   'post_status' => null,
					   'post_parent' => $post->ID
					  );
					  $attachments = get_posts( $args );
						if ( $attachments ) {
					 ?>
					 <div class="pix-media-attachment mediaelements-post">
						 <?php if (isset($px_xmlObject->var_pb_post_attachment_title ) && $px_xmlObject->var_pb_post_attachment_title <> '') { ?>
								<header class="pix-heading-title">
									<h2 class=" pix-section-title"><?php echo $px_xmlObject->var_pb_post_attachment_title; ?></h2>
								</header>
						<?php  }  
							
									 foreach ( $attachments as $attachment ) {
										$attachment_title = apply_filters( 'the_title', $attachment->post_title );
									   $type = get_post_mime_type( $attachment->ID );
									   if($type=='image/jpeg'){
										  ?>
										   <a <?php if ( $attachment_title <> '' ) { echo 'data-title="'.$attachment_title.'"'; }?> href="<?php echo $attachment->guid; ?>" data-rel="<?php echo "prettyPhoto[gallery1]"?>" class="me-imgbox"><?php echo wp_get_attachment_image( $attachment->ID, array(240,180),true ) ?></a>
										<?php
										
										} elseif($type=='audio/mpeg') {
											?>
										   <!-- Button to trigger modal -->
											<a href="#audioattachment<?php echo $attachment->ID;?>" role="button" data-toggle="modal" class="iconbox"><i class="fa fa-microphone"></i></a>
											<!-- Modal -->
											<div class="modal fade" id="audioattachment<?php echo $attachment->ID;?>" tabindex="-1" role="dialog" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
													  <div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													  </div>
													  <div class="modal-body">
													   <audio style="width:100%;" src="<?php echo $attachment->guid; ?>" type="audio/mp3" controls="controls"></audio>
														
													  </div>
													  
													</div><!-- /.modal-content -->
												  </div>
											
											</div>
										
										<?php
										} elseif($type=='video/mp4') {
										 ?>
											<a href="#videoattachment<?php echo $attachment->ID;?>" role="button" data-toggle="modal" class="iconbox"><i class="fa fa-video-camera"></i></a>
											<div class="modal fade" id="videoattachment<?php echo $attachment->ID;?>" tabindex="-1" role="dialog" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
													  <div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													  </div>
													  <div class="modal-body">
														<video width="100%" height="360" poster="">
																<source src="<?php echo $attachment->guid; ?>" type="video/mp4" title="mp4">
														</video>
													  </div>
													</div><!-- /.modal-content -->
												  </div>
											</div>
										<?php
										}
									  }
							  ?>
							</div>
					 <?php  }
					 
					 }?>
				</div>
            </article>
        </div>
            <?php 
			if(isset($px_xmlObject->var_pb_post_author) && $px_xmlObject->var_pb_post_author <> ''){
				px_author_description();
			}
            comments_template('', true); 
			?>
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