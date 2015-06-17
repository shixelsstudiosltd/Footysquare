<?php
get_header();
	global $px_node, $px_theme_option;
	$px_layout = '';
	if ( have_posts() ) :
	while ( have_posts() ) : the_post();
 		$px_player = get_post_meta($post->ID, "px_player", true);
		if ( $px_player <> "" ) {
			$px_xmlObject = new SimpleXMLElement($px_player);
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
		}
	$height = 390;
	$width = 390;
	$image_url = px_get_post_img_src($post->ID ,$height,$width);
	?>
    <!--Left Sidebar Starts-->
	<?php if ($px_layout == 'col-md-9' and $px_xmlObject->sidebar_layout->px_layout == 'left'){ ?>
        <aside class="sidebar-left col-md-3"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_xmlObject->sidebar_layout->px_sidebar_left) ) : ?><?php endif; ?></aside>
    <?php wp_reset_query();} ?>
    <!--Left Sidebar End-->
    <div class="<?php echo $px_layout; ?>">
    	<?php px_page_title();?>
          <!-- Element size 100 Start -->
            <div class="element_size_100">
                <!-- Teamdetail Start -->
                <div class="teamdetail">
                    <!-- Article Start -->
                    <article>
                       <div class="rich_text_editor detail_text">
                       		<div class="team-sec">
                                <?php if($image_url <> ''){?>
                                        <img src="<?php echo $image_url;?>" class="alignleft" alt="">
                                <?php }?>
								<div class="team-inn">
							<?php if($px_xmlObject->player_shirtnumber <> ''){?><span class="pix-player-no pix-bgcolr"><?php echo $px_xmlObject->player_shirtnumber;?></span><?php }?>
                             <div class="player-info <?php if($image_url == ''){echo 'fixtures-fullwidth';}?>">
                                <ul>
                                	<?php
									
									 if($px_xmlObject->player_dob <> ''){?>
                                        <li>
                                            <span><?php if($px_theme_option["trans_switcher"] == "on") {  _e("Born (age)",'Kings Club'); }else{  echo $px_theme_option["trans_player_born"];}?></span><time><?php echo date_i18n('F d, Y', strtotime($px_xmlObject->player_dob));?></time>
                                        </li>
                                     <?php }?>
                                     <?php if($px_xmlObject->player_birthplace <> ''){?>
                                    <li>
                                        <span><?php if($px_theme_option["trans_switcher"] == "on") {  _e("Location",'Kings Club'); }else{  echo $px_theme_option["trans_player_location"];}?></span><?php echo $px_xmlObject->player_birthplace;?>
                                    </li>
                                    <li>
                                        <span><?php if($px_theme_option["trans_switcher"] == "on") {  _e("Squad Number",'Kings Club'); }else{  echo $px_theme_option["trans_player_squad"];}?></span><?php echo $px_xmlObject->player_shirtnumber;?>
                                    </li>
                                    <?php }?>
                                    <?php if($px_xmlObject->player_debut <> ''){?>
                                    <li>
                                        <span><?php if($px_theme_option["trans_switcher"] == "on") {  _e("Debut date",'Kings Club'); }else{  echo $px_theme_option["trans_player_debut_date"];}?></span><time><?php echo date_i18n('F d, Y', strtotime($px_xmlObject->player_debut));?></time>
                                    </li>
                                    <?php }?>
                                </ul>
                               </div>
                                <?php if(isset($px_xmlObject->player_twittername) && $px_xmlObject->player_twittername <> ''){?>
                                    <div class="player-twitt-feeds">
                                        <?php px_footer_tweets($px_xmlObject->player_twittername, '5');?>
                                    </div>
                          		<?php }?>
                                <!-- Share Post -->
                        <div class="share-post">
                           	<?php 
								if ($px_xmlObject->var_pb_player_social_sharing == "on"){
									px_social_share();
								}
							?>
                        </div>
                        <!-- Share Post Close -->
                            </div></div>
                            <div class="team-detail-text">
							   <?php 
                                the_content();
                                wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'Kings Club' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
                               ?>
                               </div>
                            <?php if($px_xmlObject->player_gallery <> ''){?>
                                <div class="element_size_100 teamdetail">
								<?php if (isset($px_xmlObject->player_gallery_title) && $px_xmlObject->player_gallery_title <> '') {?>
                                        <header class="pix-heading-title">
                                            <h2 class="pix-section-title"><?php echo $px_xmlObject->player_gallery_title; ?></h2>
                                        </header>
                                <?php  }
									$width = '768';
             						$height = '403';
									if(isset($px_xmlObject->player_gallery) && $px_xmlObject->player_gallery <> '')
									  $slider_slug = $px_xmlObject->player_gallery;
									  if($slider_slug <> ''){
											  $args=array(
												'name' => (string)$slider_slug,
												'post_type' => 'px_gallery',
												'post_status' => 'publish',
												'showposts' => 1,
											  );
											  $get_posts = get_posts($args);
											  if($get_posts){
													$slider_id = (int)$get_posts[0]->ID;
													px_player_slider($width,$height,$slider_id, 'player');
											} else {
											  $slider_id = '';
											  echo '<div class="box-small no-results-found heading-color"> <h5>';
													  _e("No results found.",'KingsClub');
											  echo ' </h5></div>';
											}
									  }
									?>
                                </div>
                             <?php 
                                }
                             ?>
                        </div>
                    </article>
                    <!-- Article Closed -->
                </div>
                <!-- Teamdetail Closed  -->
            </div>
              <!-- Element size 100 Closed -->
          <?php
		  	 if(isset($px_xmlObject->var_pb_player_author) && $px_xmlObject->var_pb_player_author <> ''){
				px_author_description();
			 }
			//comments_template('', true); 
			?>
    </div>
    <?php if ( $px_xmlObject->sidebar_layout->px_layout == 'right'){ ?>
		<aside class="sidebar-right col-md-3"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_xmlObject->sidebar_layout->px_sidebar_right) ) : ?><?php endif; ?></aside>
	<?php wp_reset_query();} ?>
     <?php 
		 endwhile; endif;
	get_footer();