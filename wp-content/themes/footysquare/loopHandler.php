<?php
include_once('../../../wp-config.php');
include_once(ABSPATH . 'wp-includes/functions.php');
global $withcomments;
$withcomments = true;

// Our include
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');
?>

<?php

	$posts_flagged = array();
	//get post id's which user has flagged
	$current_posts_flagged = $wpdb->get_results("SELECT `post_id` FROM wp_contentreports WHERE `status`='new' AND user_id = ".wp_get_current_user()->ID);
	foreach($current_posts_flagged as $flag){
		$posts_flagged[] = $flag->post_id;
	}
	
	
	$count=0;
	if((isset($_GET['pid'])) && $_GET['pid'] != '')
	{
		if(isset($_GET['pid']))
			$pid = $_GET['pid'];
		else
			$pid = 1277;
		$get_post_id= $pid;
		
		$term_arg = array(
			'hide_empty' => false, 
		); 
		
		$terms = get_terms( 'wps_forum' , $term_arg );
		 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			 foreach ( $terms as $term ) {
				//term meta
				$term__parent_post_value=get_term_meta($term->term_id,'parentpostid', true);
				//echo $term->name.'<br/>';
				if($term__parent_post_value==$get_post_id){
						$term_id_value = $term->term_id;
						$term_name = $term->name;
					}
			 }
		 }
		
		$get_filter_val = $_GET['filter-post'];
		
		//echo '<div id="shoutbox-inner">';
		
		// Our pagination variables
		$numPosts = (isset($_GET['numPosts'])) ? $_GET['numPosts'] : 0;
		$page = (isset($_GET['pageNumber'])) ? $_GET['pageNumber'] : 0;
		
		
		if (isset($_GET['filter-post'])){
			$filter_type = $_GET['filter-post'];
		
			if($filter_type==1){
							
			/*$args=array('post_type'=>'wps_forum_post',
							'tax_query' => array( 
										array(
											'taxonomy' => 'wps_forum',
											'field'    => 'id',
											'terms'    => $term_id_value,
										),
									), 
									
									'meta_query' => array(
										array(
											'key'     => 'date',
														'orderby' => 'meta_value',
														'order' => DESC
										),
									),
									
									'posts_per_page'=>$numPosts,'paged' => $page);*/
			$args=array('post_type'=>'wps_forum_post',
							'tax_query' => array( 
										array(
											'taxonomy' => 'wps_forum',
											'field'    => 'id',
											'terms'    => $term_id_value,
										),
									), 
									'posts_per_page'=>$numPosts,'paged' => $page,'post__not_in' => $posts_flagged);
								
			}
			else if($filter_type==2){						
			
			$args=array('post_type'=>'wps_forum_post',
							'tax_query' => array( 
										array(
											'taxonomy' => 'wps_forum',
											'field'    => 'id',
											'terms'    => $term_id_value,
										),
									), 
									
									'meta_query' => array(
										array(
											'key'     => 'likes',
														'orderby' => 'meta_value_num',
														'order' => DESC
											,
										),
										array(
											'key'     => 'date',
														'orderby' => 'meta_value',
														'order' => DESC
										),
									),
									
									'posts_per_page'=>$numPosts,'paged' => $page,'post__not_in' => $posts_flagged);
			
			}
			else{
			$args=array('post_type'=>'wps_forum_post',
							'tax_query' => array( 
										array(
											'taxonomy' => 'wps_forum',
											'field'    => 'id',
											'terms'    => $term_id_value,
										),
									), 
									'posts_per_page'=>$numPosts,'paged' => $page,'post__not_in' => $posts_flagged);
			}
		}
		
		// Get current page and append to custom query parameters array
		$match=new WP_Query($args);
		
		//get all authors current user followed
		$follow_authors = wpw_fp_check_user_follow(wp_get_current_user()->ID);
		/*shoutbox posts starts*/
		
		while ($match->have_posts()) : $match->the_post();
			
			echo '<div class="col-lg-12 col-md-12 col-xs-12 club-chat-sec" id="post-'.get_the_ID().'">';
			
			$user_country=do_shortcode('[wps-usermeta meta="wpspro_country"]');
			echo '<div class="col-lg-2 col-md-2 col-xs-2">';
				$author_id = get_the_author_ID();
				echo '<a href="?page_id=1770&user_id='.$author_id.'">';
					$update_post_id=get_the_ID();
					
					if($follow_authors){
						if(in_array($author_id,$follow_authors))
						{
							echo do_shortcode('[wps-avatar link=1 size="100" user_id='.$author_id.']'); 
						}
					}
					echo '<p class="text-center">'.get_the_author().'</p>'; 
				echo '</a>';
			echo '</div>';
			
			//post content
			echo '<div class="col-lg-8 col-md-8 col-xs-8 thread-content no-padding">';
			
			//post title

			echo '<div class="post-content" id="'.get_the_ID().'">';
				the_content();
			echo '</div>';
			
			echo '</div>';
			
			//post thumbnail
			$small_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
			$post_thumbnail = $small_image_url[0];
			
			$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
			$post_large_img = $large_image_url[0];
			
			$post_id_val = get_the_ID();
			
			echo '<div class="col-lg-2 col-md-1 col-xs-1 no-padding thread-thumbnail">';

				echo "<a href='#image-popup-$post_id_val' class='fancybox'>";
					if ( has_post_thumbnail() ) {
						the_post_thumbnail(array(34,40));
					}
				echo "</a>";
			echo '</div>';
			
			echo "
				<div id='image-popup-$post_id_val' class='fancybox image-popup'>
					<div class='col-lg-8 col-md-10 col-xs-12'>
						<img class='popup-post-image' src='$post_large_img' alt='img'/>
					</div>
					<div class='col-lg-6 col-md-6 col-xs-6 popup-post-like'>";
						if(function_exists('wp_ulike')) wp_ulike('get');
					echo "</div>
					<div class='col-lg-8 col-md-10 col-xs-12'>";
						comments_template('comments.php',true);
					echo "</div>
				</div>
			";
			
			//post like,comment and time
			echo '<div class="col-lg-12 col-md-12 col-xs-12 thread-bottom">';
			$post_time= get_the_modified_time('Y-m-j g:i:s');
			
			echo '<div class="col-lg-3 col-md-3 col-xs-3">'; if(function_exists('wp_ulike')) wp_ulike('get'); echo '</div>';
			echo '<div class="col-lg-3 col-md-3 col-xs-3 shout-comment-icon" id="'.get_the_ID().'"><i class="fa fa-comment-o"></i>';comments_number('','1','%'); echo '</div>';
			echo '<div class="col-lg-3 col-md-3 col-xs-3"><i class="fa fa-clock-o"></i>';
				echo get_time_difference($post_time); 
				wprc_report_submission_form(); 
				social_media(get_permalink());  
			echo '</div>';
			
			echo '<div class="col-lg-3 col-md-3 col-xs-3">';
				
				$update_post_id=get_the_ID();
				update_model_panel($update_post_id);
				
				$author_id = get_the_author_meta('ID');
				if ( is_user_logged_in() && $author_id==get_current_user_id()) {
				echo '
					<i id="'.$update_post_id.'" class="fa fa-pencil update-front-post"></i>
				';
				}
			echo '</div>';
			
			echo '<div class="col-lg-12 col-md-12 col-xs-12 shout-comment" id="postid-'.get_the_ID().'">';
				comments_template('comments.php',true);
			echo '</div>';
			
			echo '</div></div>';
			$count++;

			endwhile;
			
			wp_reset_postdata();
		
			/*slider ends*/
			//echo '</div>'; //end inner shoutbox
	}
	
	else if(isset($_GET['post_type']) && $_GET['post_type'] != '')
	{

		$p_type=$_GET['post_type'];
		
		$favorite_post_ids = wpfp_get_users_favorites();
	
		if(empty($favorite_post_ids))
			echo '<p class="not-found">No Favourite Entry Found.</p>';
		else{
			if(!($p_type))
				$p_type="player";
			
			// Our pagination variables
			$numPosts = (isset($_GET['numPosts'])) ? $_GET['numPosts'] : 0;
			$page = (isset($_GET['pageNumber'])) ? $_GET['pageNumber'] : 0;
			
			$args =array( 'post_type' => $p_type,'post__in' => $favorite_post_ids,'posts_per_page'=>$numPosts,'paged' => $page );
			$the_query = new WP_Query( $args );
			
			  if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
					echo "<div class='col-lg-2 col-md-3 col-xs-6 fav-post-item col-centered'><div class='fav-head'>";
					echo "<span class='fol-btn'>".do_shortcode('[wpw_follow_me id="'.get_the_ID().'" disablecount="true"][/wpw_follow_me]')."</span>";
					echo "<span class='fav-btn'>";
					 wpfp_link();
					echo "</span></div>";
					echo '<div class="style-post-img">';
						echo "<a href='".get_permalink()."' title='". get_the_title() ."'>";
							the_post_thumbnail( 'thumbnail' );
						echo "</a> ";
					echo '</div>';
					echo "<a class='fav-title' href='".get_permalink()."' title='". get_the_title() ."'>" . get_the_title() . "</a> ";
					echo "</div>";
					endwhile;
				else :
					echo '<p class="not-found">No Favourite Entry Found.</p>';
				endif;
		}
		
	}
	
	else if((isset($_GET['s'])) && $_GET['s'] != '')
	{
		
		$search_word = $_GET['s'];
		// Our pagination variables
		$numPosts = (isset($_GET['numPosts'])) ? $_GET['numPosts'] : 0;
		$page = (isset($_GET['pageNumber'])) ? $_GET['pageNumber'] : 0;
		
		$args=array('post_type'=>'wps_forum_post','taxonomy' => 'wps_forum','s'=>$search_word, 'orderby'=>'ASC','posts_per_page'=>$numPosts,'paged' => $page);
		
		$search_q=new WP_Query($args);
		while ( $search_q->have_posts() ) : $search_q->the_post();
							
			echo '<div class="col-lg-12 col-md-12 col-xs-12 club-chat-sec" id="post-'.get_the_ID().'">';
				echo '<div class="col-lg-2 col-md-2 col-xs-2">';
					//echo '<a href="?page_id=1770">';
						$update_post_id=get_the_ID();
						update_model_panel($update_post_id);
						echo get_the_author(); 
					//echo '</a>';
				echo '</div>';
				
				//post content
				echo '<div class="col-lg-8 col-md-8 col-xs-8 thread-content no-padding">';
				
				//post title

				echo '<div class="post-content" id="'.get_the_ID().'">';
					the_content();
				echo '</div>';
				
				echo '</div>';
				
				//post thumbnail
				
				echo '<div class="col-lg-2 col-md-1 col-xs-1 no-padding thread-thumbnail">';
					echo '<a href="';
					the_permalink();
					echo '">';
					if ( has_post_thumbnail() ) {
						the_post_thumbnail(array(34,40));
					}
					echo '</a>';
				echo '</div>';
				
				
				//post like,comment and time
				echo '<div class="col-lg-12 col-md-12 col-xs-12 thread-bottom">';
				$post_time= get_the_modified_time('Y-m-j g:i:s');
				
				echo '<div class="col-lg-3 col-md-3 col-xs-3">'; if(function_exists('wp_ulike')) wp_ulike('get'); echo '</div>';
				echo '<div class="col-lg-3 col-md-3 col-xs-3 shout-comment-icon" id="'.get_the_ID().'"><i class="fa fa-comment-o"></i>';comments_number('','1','%'); echo '</div>';
				echo '<div class="col-lg-3 col-md-3 col-xs-3"><i class="fa fa-clock-o"></i>';echo get_time_difference($post_time); echo '</div>';
				
				echo '<div class="col-lg-3 col-md-3 col-xs-3">';
					
					$update_post_id=get_the_ID();
					
					$author_id = get_the_author_meta('ID');
					if ( is_user_logged_in() && $author_id==get_current_user_id()) {
					echo '
						<i id="'.$update_post_id.'" class="fa fa-pencil update-front-post"></i>
					';
					}
				echo '</div>';
				
				echo '<div class="col-lg-12 col-md-12 col-xs-12 shout-comment" id="postid-'.get_the_ID().'">';
					comments_template('comments.php',true);
				echo '</div>';
					
				echo '</div></div>';
				$count++;
		endwhile;
	}
?>