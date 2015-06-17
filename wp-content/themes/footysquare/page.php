<?php get_header();  
					wp_reset_query();
					$width =1100;
					$height = 556;
					$image_url ='';
					if (post_password_required()) { 
						echo '<div class="rich_editor_text">'.px_password_form().'</div>';
					}else{
					$px_meta_page = px_meta_page('px_page_builder');
					if (count($px_meta_page) > 0) {
						 ?>
                         <?php if ( $px_meta_page->sidebar_layout->px_layout <> '' and $px_meta_page->sidebar_layout->px_layout <> "none" and $px_meta_page->sidebar_layout->px_layout == 'left') : ?>
                            <aside class="col-md-3">
                                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_meta_page->sidebar_layout->px_sidebar_left) ) : endif; ?>
                             </aside>
                        <?php endif; ?>
               	 		<div class="<?php echo px_meta_content_class();?>">
						<?php
							px_page_title();
 							wp_reset_query();
							$image_url = px_get_post_img_src($post->ID, $width, $height);
							if($image_url <> ''){ 
								echo '<figure class="featured-img"><a href="'.get_permalink().'" ><img src="'.$image_url.'" alt="" ></a></figure>';
							}
 							if( $px_meta_page->page_content == "on"  && get_the_content() <> ''){
 							echo '<div class="rich_editor_text pix-content-wrap">';
 								if( $px_meta_page->page_content == "on"  && get_the_content() <> ''){
									//do_shortcode('[adbanner]');
									
									//wpfp_link();
									
									the_content();
									wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'Rocky' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
								}
 							echo '</div>';
						}
						global $px_counter_node;
						foreach ( $px_meta_page->children() as $px_node ) {
							if ( $px_node->getName() == "blog" ) {
								if ( !isset($_SESSION["px_page_back"]) ||  isset($_SESSION["px_page_back"])){
									$_SESSION["px_page_back"] = get_the_ID();
								}
								$px_counter_node++;
								get_template_part( 'page_blog', 'page' );
							} else if ( $px_node->getName() == "gallery_albums" ) {
								$px_counter_node++;
  								if ( $px_node->px_gal_album_cat <> "" ) {
									get_template_part( 'page_gallery_albums', 'page' );
								}
 							}else if ( $px_node->getName() == "gallery" ) {
								$px_counter_node++;
  								if ( $px_node->album <> "" and $px_node->album <> "0" ) {
									get_template_part( 'page_gallery', 'page' );
								}
							}else if ( $px_node->getName() == "slider" ) {
								$px_counter_node++;
								if ( $px_node->slider <> "" and $px_node->slider <> "0" ) {
									get_template_part( 'page_slider', 'page' );
								}
							}else if ( $px_node->getName() == "event" ) {
								if ( !isset($_SESSION["px_page_back_event"]) ||  isset($_SESSION["px_page_back_event"])){
									$_SESSION["px_page_back_event"] = get_the_ID();
								}
								$px_counter_node++;
									get_template_part( 'page_event', 'page' );
							}elseif($px_node->getName() == "team"){
							   	$px_counter_node++;
								get_template_part( 'page_team', 'page' );
							}elseif($px_node->getName() == "map"){
							   	$px_counter_node++;
								echo px_map_page();
							}elseif($px_node->getName() == "fixtures"){
							   	$px_counter_node++;
								px_fixtures_page();
							
 							}elseif($px_node->getName() == "contact"){
							   $px_counter_node++;
							   get_template_part('page_contact','page');
							}elseif($px_node->getName() == "column"){
								$px_counter_node++;
								px_column_page();
							}
							elseif($px_node->getName() == "pointtable"){
								$px_counter_node++;
								get_template_part( 'page_pointtable', 'page' );
							}
						}
                     	wp_reset_query(); 
					 	/*if ( comments_open() ) : 
					 		comments_template('', true); 
		   				endif; */
						//echo_views(get_the_ID());
						?>
                 </div>
					<?php if ( $px_meta_page->sidebar_layout->px_layout <> '' and $px_meta_page->sidebar_layout->px_layout <> "none" and $px_meta_page->sidebar_layout->px_layout == 'right') : ?>
                            <aside class="col-md-3">
                                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_meta_page->sidebar_layout->px_sidebar_right) ) : endif; ?>
                             </aside>
                        <?php endif; ?>	
                       
             		<?php }else{
						
						px_page_title();
						 ?> 
                    
            		<div class="rich_editor_text pix-content-wrap">
					<?php 
                        while (have_posts()) : the_post();
							$image_url = px_get_post_img_src($post->ID, $width, $height);
								if($image_url <> ''){ 
									echo '<figure class="featured-img"><a href="'.get_permalink().'" ><img src="'.$image_url.'" alt="" ></a></figure>';
								}
                            the_content();
							wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'Rocky' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
                        endwhile; 
						if ( comments_open() ) { 
					 		comments_template('', true); 
						}
						wp_reset_query();
                    ?>
                	</div>
			<?php }
			} 
		?>
<?php get_footer();?>
<!-- Columns End -->