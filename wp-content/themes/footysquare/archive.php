<?php
get_header();
	global  $px_theme_option; 
	if(!isset($px_theme_option['px_layout'])){ $px_layout = 'right'; }
	if(isset($px_theme_option['px_layout'])){ $px_layout = $px_theme_option['px_layout']; }else{ $px_layout = '';} 
?>	
        <?php
			if ( $px_layout <> '' and $px_layout  <> "none" and $px_layout  == 'left') :  ?>
				<aside class="left-content col-md-3">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_theme_option['px_sidebar_left']) ) : endif; ?>
				</aside>
		<?php endif; ?>	
        <div class="<?php px_default_pages_meta_content_class( $px_layout ); ?>">
        <?php px_page_title();?>
	       	<div class="pix-blog blog-medium">
                <!-- Blog Post Start -->
                 <?php 
				 if(is_author()){
					 global $author;
					 $userdata = get_userdata($author);
				 }
				 if(category_description() || is_tag() || (is_author() && isset($userdata->description) && !empty($userdata->description))){
					echo '<div class="rich_editor_text pix-content-wrap">';
					if(is_author()){
						echo $userdata->description;
					} elseif ( is_category() ) {
						 echo category_description();
					} elseif(is_tag()){
						$tag_description = tag_description();
                           if ( ! empty( $tag_description ) )
                                echo apply_filters( 'tag_archive_meta', $tag_description );
					}
					echo '</div>';
					
				}?>
				<?php
                    if (empty($_GET['page_id_all']))
                        $_GET['page_id_all'] = 1;
                    if (!isset($_GET["s"])) {
                        $_GET["s"] = '';
                    }
                    rewind_posts();
					$taxonomy = 'category';
					$taxonomy_tag = 'post_tag';
					$args_cat = array();
					if(is_author()){
						$args_cat = array('author' => $wp_query->query_vars['author']);
						$post_type = array( 'post', 'events', 'px_cause');
					} elseif(is_date()){
						if(is_month() || is_year() || is_day() || is_time()){
							$args_cat = array('m' => $wp_query->query_vars['m'],'year' => $wp_query->query_vars['year'],'day' => $wp_query->query_vars['day'],'hour' => $wp_query->query_vars['hour'], 'minute' => $wp_query->query_vars['minute'], 'second' => $wp_query->query_vars['second']);
						}
						$post_type = array( 'post');
					} elseif (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])){
						$taxonomy = $wp_query->query_vars['taxonomy'];
						$taxonomy_category='';
							$taxonomy_category=$wp_query->query_vars[$taxonomy];
						if( $wp_query->query_vars['taxonomy']=='team-category') {
						  $args_cat = array( $taxonomy => "$taxonomy_category");
						  $post_type='player';
							
					  } else if( $wp_query->query_vars['taxonomy']=='event-category' || $wp_query->query_vars['taxonomy']=='event-tag') {
						  $args_cat = array( $taxonomy => "$taxonomy_category");
						  $post_type='events';
					} else if( $wp_query->query_vars['taxonomy']=='px_gallery-category') {
						  $args_cat = array( $taxonomy => "$taxonomy_category");
						  $post_type='px_gallery';
							  
						} else {
							$taxonomy = 'category';
							$args_cat = array();
							$post_type='post';
						}
					} elseif(is_category()){
						$taxonomy = 'category';
						$args_cat = array();
						$category_blog = $wp_query->query_vars['cat'];
						$post_type='post';
						$args_cat = array( 'cat' => "$category_blog");
					} elseif(is_tag()){
						$taxonomy = 'category';
						$args_cat = array();
						$tag_blog = $wp_query->query_vars['tag'];
						$post_type='post';
						$args_cat = array( 'tag' => "$tag_blog");
					} else {
						$taxonomy = 'category';
						$args_cat = array();
						$post_type='post';
					}
					$args = array( 
					'post_type'		=> $post_type, 
					'paged'			=> $_GET['page_id_all'],
					'post_status'	=> 'publish',
					'orderby'		=> 'date',
					'order'			=> 'DESC',
				);
				$args = array_merge($args_cat,$args);
				$custom_query = new WP_Query($args);
                 ?>
                <?php if ( $custom_query->have_posts() ): ?>
	                <?php
                    while ( $custom_query->have_posts() ) : $custom_query->the_post();
						 px_defautlt_artilce();
					
					 endwhile; 
					 
					  
                         $qrystr = '';
                        // pagination start
                        	if ($custom_query->found_posts > get_option('posts_per_page')) {
                            	echo "<nav class='pagination'><ul>";
                                     if ( isset($_GET['page_id']) ) $qrystr .= "&page_id=".$_GET['page_id'];
									 if ( isset($_GET['author']) ) $qrystr .= "&author=".$_GET['author'];
									 if ( isset($_GET['tag']) ) $qrystr .= "&tag=".$_GET['tag'];
									 if ( isset($_GET['cat']) ) $qrystr .= "&cat=".$_GET['cat'];
									 if ( isset($_GET['event-category']) ) $qrystr .= "&event-category=".$_GET['event-category'];
									 if ( isset($_GET['team-category']) ) $qrystr .= "&team-category=".$_GET['team-category'];
									 if ( isset($_GET['event-tag']) ) $qrystr .= "&event-tag=".$_GET['event-tag'];
									 if ( isset($_GET['m']) ) $qrystr .= "&m=".$_GET['m'];
 						        echo px_pagination($custom_query->found_posts,get_option('posts_per_page'), $qrystr);
                                echo "</ul></nav>";
                            }
                        // pagination end
                    
					 
					  endif;  ?>
        		</div>
        </div>  
       <?php
	if ( $px_layout <> '' and $px_layout  <> "none" and $px_layout  == 'right') :  ?>
		<aside class="left-content col-md-3">
			<?php 
            if(isset($px_theme_option['px_sidebar_right'])){
                if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_theme_option['px_sidebar_right']) ) : endif;
            }else{
                if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-1') ) : endif;
            }
              ?>
		</aside>
<?php endif; ?>
<?php get_footer(); ?>