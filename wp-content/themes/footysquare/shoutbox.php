<?php
	include_once('../../../wp-config.php');
	include_once(ABSPATH . 'wp-includes/functions.php');

	global $withcomments;
	$withcomments = true;
	//add new post	
	function set_shoutbox_post($term_name){
	$user_id=get_current_user_id();
	$username=strtoupper(get_userdata(get_current_user_id())->user_login);
	$term_id_value = $_POST['term_id'];
		
		  if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == "frontend_post_add") {
					
			$content   = $_POST['content'];
			//$title     = $_POST['title'];
			$title = substr($content, 0, 50);
			$post_type = 'wps_forum_post';
			
			if($title==null && $content==null)
				return false;
			
			//the array of arguements to be inserted with wp_insert_post
			$new_post = array(
			'post_title'    => $title,
			'post_content'  => $content,
			'post_status'   => 'publish',          
			'post_type'     => $post_type 
			);
			
			
			
			//insert the the post into database by passing $new_post to wp_insert_post
			//store our post ID in a variable $pid
			$pid = wp_insert_post($new_post);
			
			
			wp_set_object_terms($pid,$term_name,'wps_forum', true);
			//mention module
			mention_notify($pid,$content);
			
			if(isset($_POST['featured_image']))
			{
				$file = $_POST['featured_image'];
				
				//$t=time();
				$file = $_SESSION["upload_file_name"];
				//unset($_SESSION['upload_file_name']);
				
				$upload_dir = wp_upload_dir();
				$image_url = $upload_dir['url']."/$file";
				
				$upload_dir = wp_upload_dir();
				//$image_data = file_get_contents($image_url);
				$filename = basename($image_url);
				if(wp_mkdir_p($upload_dir['path']))
					$file = $upload_dir['path'] . '/' . $filename;
				else
					$file = $upload_dir['basedir'] . '/' . $filename;
				//file_put_contents($file, $image_data);

				$wp_filetype = wp_check_filetype($filename, null );
				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => sanitize_file_name($filename),
					'post_content' => '',
					'post_status' => 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $file, $pid );
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				set_post_thumbnail( $pid, $attach_id );
				
				
			}
				
			
		   }
		 
	//}
	
	$args=array('post_type'=>'wps_forum_post',
					'tax_query' => array( 
								array(
									'taxonomy' => 'wps_forum',
									'field'    => 'id',
									'terms'    => $term_id_value,
								),
							), 'orderby'=>'ASC','posts_per_page'=>'10','post__in'=>array($pid));
	
	// Get current page and append to custom query parameters array
	
	$match=new WP_Query($args);

	/*shoutbox posts starts*/
	
	while ($match->have_posts()) : $match->the_post();
		//$match->the_post();
		echo '<div class="col-lg-12 col-md-12 club-chat-sec" id="post-'.get_the_ID().'">';
		
		echo '<div class="col-lg-2 col-md-2 col-xs-2">';
			echo get_the_author(); 
		echo '</div>';
		
		//post content start
		echo '<div class="col-lg-8 col-md-10 thread-content no-padding">';
		
		//title
		/*
		echo '<a href="';
		the_permalink();
		echo '" class="thread-title">';
		echo substr(the_title('', '', FALSE), 0, 44);
		echo '</a>';
		*/
		
		echo '<div class="post-content" id="'.get_the_ID().'">';
			$content = the_content();
		echo '</div>';
		echo '</div>';
		//end content
		
		//post thumbnail
		
		//post thumbnail
		$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		$post_thumbnail = $large_image_url[0];
		
		echo '<div class="col-lg-2 col-md-1 no-padding thread-thumbnail">';
			/*echo '<a href="';
			the_permalink();
			echo '">';
			*/
			echo "<a class='fancybox' href='$post_thumbnail' data-fancybox-group='gallery'>";
			if ( has_post_thumbnail() ) {
				the_post_thumbnail(array(34,40));
			}
			echo '</a>';
		echo '</div>';
		
		//thumbnail ends
		
		echo '<div class="col-lg-12 thread-bottom">';
		$post_time= get_the_modified_time('Y-m-j g:i:s');
		
		echo '<div class="col-lg-3 col-md-2 col-xs-2">'; if(function_exists('wp_ulike')) wp_ulike('get'); echo '</div>';
		echo '<div class="col-lg-3 col-md-2 col-xs-2 shout-comment-icon" id="'.get_the_ID().'"><i class="fa fa-comment-o"></i>';comments_number('0','1','%'); echo '</div>';
		echo '<div class="col-lg-3 col-md-2 col-xs-2"><i class="fa fa-clock-o"></i>';echo get_time_difference($post_time); echo '</div>';
		
		$user_country=do_shortcode('[wps-usermeta meta="wpspro_country"]');
		echo '<div class="col-lg-3 col-md-2 col-xs-2">';
		
			
			$update_post_id=get_the_ID();
			update_model_panel($update_post_id);
			
			echo '
				<i id="'.$update_post_id.'" class="fa fa-pencil update-front-post"></i>
			';
			
			//echo ' <span class="flag flag-';
			//echo get_country_code($user_country);
			//echo '"></span>';
		echo '</div>';
		//echo '';
		
		echo '<div class="col-lg-12 shout-comment" id="postid-'.get_the_ID().'">';
			comments_template('comments.php',true);//get_shoutbox_comment(get_the_title());
		echo '</div>';
		
		echo '</div></div>';
		$count++;
	//}	
		endwhile;
				
		wp_reset_postdata();
		
		if($count==0){
			echo '<div style="display: block !important;"><h3>No Thread Found.</h3></div>';
		}
		/*slider ends*/
	}
	
	set_shoutbox_post($_POST['term']);
	
?>

