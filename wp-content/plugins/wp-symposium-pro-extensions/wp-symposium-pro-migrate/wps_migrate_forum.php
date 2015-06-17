<?php
if (is_user_logged_in() && current_user_can('manage_options')):

	// Increase PHP script timeout
	set_time_limit(86400); // 24 hours
	global $wpdb, $blog_id;

	if ($the_post['wp_symposium_forum_confirm'] != 'continue'): // from first $_POST submit
	    $id = $the_post['wp_symposium_forums'];
		$start = 0;
		$step = 50;
	else: // From second+ step $_GET
		$id = $the_post['wp_symposium_forums_id'];
		$start = $the_post['wp_symposium_forums_start'];
		$step = $the_post['wp_symposium_forums_step'];
	endif;


	$sql = "SELECT title, stub, cat_desc FROM ".$wpdb->prefix."symposium_cats WHERE cid = %d";
	$wps = $wpdb->get_row($wpdb->prepare($sql, $id));

	// Create new forum in WPS Pro
	if (!isset($_GET['term_id'])):
		$new_term = wp_insert_term(
		  $wps->title, 
		  'wps_forum', 
		  array(
		    'description'=> $wps->cat_desc,
		    'slug' => sanitize_title_with_dashes($wps->title),
		  )
		);	
		if (is_wp_error($new_term)):
			$new_term_error = '<div class="error"><p>'.__('Failed to create forum', WPS2_TEXT_DOMAIN).' - '.$new_term->get_error_message().'.</p></div>';		
			$new_term_id = false;
		else:
			$new_term_id = $new_term['term_id'];
		endif;
	else:
		$new_term_id = $_GET['term_id'];
	endif;

	if (!$new_term_id):
		echo $new_term_error;
	else:
		// Get the new taxonomy term
		$term = get_term_by('id', $new_term_id, 'wps_forum');
		$term_slug = $term->slug;

		// Get total number of topics to migrate
		$sql = "SELECT count(*) AS cnt FROM ".$wpdb->prefix."symposium_topics WHERE topic_parent = 0 AND topic_category = %d";
		$cnt = $wpdb->get_var($wpdb->prepare($sql, $id));

		if ($start+$step < $cnt):
			echo '<div class="update-nag" style="width:100%; margin-left: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><p>';
			echo sprintf(__('<strong>Forum Migration in progress</strong> - migrated topics %d to %d of %d.', WPS2_TEXT_DOMAIN), $start+1, $start+$step, $cnt).'<br />';
			echo sprintf('<a href="admin.php?page=wps_pro_setup&wpspro_update=continue&wp_symposium_forum_confirm=%s&term_id=%d&wp_symposium_forums_id=%d&wp_symposium_forums_start=%d&wp_symposium_forums_step=%d">%s</a>', 'continue', $new_term_id, $id, $start+$step, $step, __('Continue', WPS2_TEXT_DOMAIN));
			echo '</p></div>';				
		endif;


		// Loop through posts, copying to new forum
		$sql = "SELECT * FROM ".$wpdb->prefix."symposium_topics WHERE topic_parent = 0 AND topic_category = %d ORDER BY tid LIMIT %d, %d";
		$posts = $wpdb->get_results($wpdb->prepare($sql, $id, $start, $step));
		if ($posts):
			foreach ($posts as $post):

				$new_post = array(
				  'post_title'     => $post->topic_subject,
				  'post_content'   => $post->topic_post,
				  'post_status'    => 'publish',
				  'author'		   => $post->topic_owner,
				  'post_type'      => 'wps_forum_post',
				  'post_author'    => $post->topic_owner,
				  'ping_status'    => 'closed',
				  'comment_status' => ($post->allow_replies = 'on') ? 'open' : 'closed',
				  'post_date'	   => $post->topic_date,
				  'post_date_gmt'  => $post->topic_date,
				);  
				$new_id = wp_insert_post( $new_post );
				wp_set_object_terms( $new_id, $term_slug, 'wps_forum' );

				// Handle any attachments
				if (isset($blog_id) && $blog_id > 1) {
					$targetPath = get_option(WPS_OPTIONS_PREFIX.'_img_path')."/".$blog_id."/forum/".$post->tid;
				} else {
					$targetPath = get_option(WPS_OPTIONS_PREFIX.'_img_path')."/forum/".$post->tid;
				}

				if (file_exists($targetPath)) {
					$handler = opendir($targetPath);
					$file_list = array();
					while ($file = readdir($handler)) {
						if ( ($file != "." && $file != ".." && $file != ".DS_Store") && (!is_dir($targetPath.'/'.$file)) ) {

							$image_file = $targetPath.'/'.$file;
							$time = current_time('mysql', 1);
							$wp_filetype = wp_check_filetype( $image_file, null );
							extract( $wp_filetype );
							$uploads = wp_upload_dir();
							$filename = wp_unique_filename( $uploads['path'], basename($image_file));

							// copy the file to the uploads dir
							$new_file = $uploads['path'] . '/' . $filename;
							copy( $image_file, $new_file );

							// Set correct file permissions
							$stat = stat( dirname( $new_file ));
							$perms = $stat['mode'] & 0000666;
							@ chmod( $new_file, $perms );
							// Compute the URL
							$url = $uploads['url'] . '/' . $filename;

							//Apply upload filters
							$return = apply_filters( 'wp_handle_upload', array( 'file' => $new_file, 'url' => $url, 'type' => $type ) );
							$new_file = $return['file'];
							$url = $return['url'];
							$type = $return['type'];

							$title = preg_replace('!\.[^.]+$!', '', basename($image_file));
							$content = '';

							$post_date = current_time('mysql');
							$post_date_gmt = current_time('mysql', 1);
							
							// Construct the attachment array
							$attachment = array(
								'post_mime_type' => $type,
								'guid' => $url,
								'post_parent' => $new_id,
								'post_title' => $title,
								'post_name' => $title,
								'post_content' => $content,
								'post_date' => $post_date,
								'post_date_gmt' => $post_date_gmt
							);

							//Win32 fix:
							$new_file = str_replace( strtolower(str_replace('\\', '/', $uploads['basedir'])), $uploads['basedir'], $new_file);

							// Save the data
							$attach_id = wp_insert_attachment($attachment, $new_file, $new_id);
							if ( !is_wp_error($attach_id) ) {
								$data = wp_generate_attachment_metadata( $attach_id, $new_file );
								wp_update_attachment_metadata( $attach_id, $data );
							}
							
						}
					}
					closedir($handler);
				}

				// Now migrate the comments for this post
				$sql = "SELECT * FROM ".$wpdb->prefix."symposium_topics WHERE topic_parent = %d AND topic_category = %d ORDER BY tid";
				$comments = $wpdb->get_results($wpdb->prepare($sql, $post->tid, $id));
				if ($comments):
					foreach ($comments as $comment):

						$owner = get_user_by('id', $comment->topic_owner);
						$author_email = ($owner) ? $owner->user_email : '';
						$author_user_login = ($owner) ? $owner->user_login : '';

						$data = array(
						    'comment_post_ID' => $new_id,
						    'comment_content' => $comment->topic_post,
						    'comment_type' => '',
						    'comment_parent' => 0,
						    'comment_author' => $author_user_login,
						    'comment_author_email' => $author_email,
						    'user_id' => $comment->topic_owner,
						    'comment_author_IP' => '',
						    'comment_agent' => '',
						    'comment_approved' => '1',
						    'comment_date'  => $comment->topic_date,
				  			'comment_date_gmt'  => $comment->topic_date,
						);

						$new_comment_id = wp_insert_comment($data);

						// Handle any attachments for comment
						if (isset($blog_id) && $blog_id > 1) {
							$targetPath = get_option(WPS_OPTIONS_PREFIX.'_img_path')."/".$blog_id."/forum/".$post->tid.'/'.$comment->tid;
						} else {
							$targetPath = get_option(WPS_OPTIONS_PREFIX.'_img_path')."/forum/".$post->tid.'/'.$comment->tid;
						}

						if (file_exists($targetPath)) {
							$attachment_ids = array();
							$handler = opendir($targetPath);
							$file_list = array();
							while ($file = readdir($handler)) {
								if ( ($file != "." && $file != ".." && $file != ".DS_Store") && (!is_dir($targetPath.'/'.$file)) ) {

									$image_file = $targetPath.'/'.$file;
									$time = current_time('mysql', 1);
									$wp_filetype = wp_check_filetype( $image_file, null );
									extract( $wp_filetype );
									$uploads = wp_upload_dir();
									$filename = wp_unique_filename( $uploads['path'], basename($image_file));

									// copy the file to the uploads dir
									$new_file = $uploads['path'] . '/' . $filename;
									copy( $image_file, $new_file );

									// Set correct file permissions
									$stat = stat( dirname( $new_file ));
									$perms = $stat['mode'] & 0000666;
									@ chmod( $new_file, $perms );
									// Compute the URL
									$url = $uploads['url'] . '/' . $filename;

									//Apply upload filters
									$return = apply_filters( 'wp_handle_upload', array( 'file' => $new_file, 'url' => $url, 'type' => $type ) );
									$new_file = $return['file'];
									$url = $return['url'];
									$type = $return['type'];

									$title = preg_replace('!\.[^.]+$!', '', basename($image_file));
									$content = '';

									$post_date = current_time('mysql');
									$post_date_gmt = current_time('mysql', 1);
									
									// Construct the attachment array
									$attachment = array(
										'post_mime_type' => $type,
										'guid' => $url,
										'post_parent' => $new_comment_id,
										'post_title' => $title,
										'post_name' => $title,
										'post_content' => $content,
										'post_date' => $post_date,
										'post_date_gmt' => $post_date_gmt
									);

									//Win32 fix:
									$new_file = str_replace( strtolower(str_replace('\\', '/', $uploads['basedir'])), $uploads['basedir'], $new_file);

									// Save the data
									$attach_id = wp_insert_attachment($attachment, $new_file);
									array_push($attachment_ids, $attach_id);
									if ( !is_wp_error($attach_id) ) {
										$data = wp_generate_attachment_metadata( $attach_id, $new_file );
										wp_update_attachment_metadata( $attach_id, $data );
									}
									
								}
							}
							closedir($handler);

							if (count($attachment_ids))
                				update_comment_meta($new_comment_id, 'wps_attachment_id', $attachment_ids);

						}

					endforeach;
				endif;

				// Mark post as re-opened to avoid automatically closing
				update_post_meta($new_id, 'wps_reopened_date', true);

			endforeach;
		endif;

		if ($start+$step >= $cnt):
			echo '<div class="updated"><p>';
			echo '<strong>'.__('Forum Migrated. Please read the following important next steps:', WPS2_TEXT_DOMAIN).'</strong><br />';
			echo sprintf(__('1. Create a new WordPress page for the forum shortcodes with the title <strong>%s</strong> and permalink <strong>%s</strong>.', WPS2_TEXT_DOMAIN), $wps->title, $term_slug).'<br />';
			echo __('2. Edit the new forum in Forum Setup, select your new page and check other settings.', WPS2_TEXT_DOMAIN);
			echo '</p></div>';
		endif;

	endif;

endif;
?>