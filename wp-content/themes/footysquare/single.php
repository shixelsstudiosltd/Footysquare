<?php
 	global $px_theme_option;
	$px_node = new stdClass();
  	get_header();
	$px_layout = '';
	if (have_posts()):
		while (have_posts()) : the_post();
		$post_xml = get_post_meta($post->ID, "post", true);			 setPostViews(get_the_ID()); 
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
    	<?php px_page_title(); ?>
		<div class="blog blog_detail">
		
            <article>
                <?php if($image_url <> '' && $px_xmlObject->var_pb_post_featured == 'on'){?>
                <figure>
                    <img src="<?php echo $image_url;?>" alt="<?php the_title();?>">
                </figure>
                <?php }?>
                <div class="pix-content-wrap">
                    <div class="detail_text rich_editor_text">
                    <?php px_posted_on(true,false,false,false,true,false);?>
                    <?php 
					
                    the_content();
                    wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'Kings Club' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
					
                   ?>
                </div>
                    <!-- Share Post -->
                    <div class="share-post">
                        <?php
						$before_tag = "<div class='post-tags'>".__( 'tags','Kings Club').": ";
						$tags_list = get_the_term_list ( get_the_id(), 'post_tag', $before_tag, ' ', '</div>' );
						if ( $tags_list ){
							printf( __( '%1$s', 'Kings Club'),$tags_list );
						} // End if categories
						
                         if ($px_xmlObject->var_pb_post_social_sharing == "on"){
                             px_social_share();
                         }
                          //px_next_prev_post();
                         ?>
                    </div>
                    <!-- Share Post Close -->
                    <div class="prev-nex-btn">
                    	<?php px_next_prev_custom_links('post');?>
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