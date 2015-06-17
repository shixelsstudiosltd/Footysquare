<?php
while(!is_file('wp-config.php')){
	if(is_dir('../')) chdir('../');
	else die('Could not find WordPress config file.');
}
include_once( 'wp-config.php' );

$action = isset($_POST['action']) ? $_POST['action'] : false;

if ($action) {

	global $current_user;
	get_currentuserinfo();

	if ( is_user_logged_in() ) {

		/* ADD EVENT */
		if ($action == 'wps_calendar_post_add') {

			$the_post = $_POST;

			if (isset($the_post['event_id'])):

				// Update event
				$post = array(
				  'ID'			   => $the_post['event_id'],
				  'post_content'   => $the_post['wps_calendar_post'],
				  'post_name'      => sanitize_title_with_dashes($the_post['wps_calendar_title']),
				  'post_title'     => $the_post['wps_calendar_title'],
				  'post_status'    => 'publish',
				  'post_type'      => 'wps_event',
				  'ping_status'    => 'closed',
				  'comment_status' => $the_post['wps_event_comments'] ? 'open' : 'closed',
				);  
				$new_event_id = $the_post['event_id'];
				wp_update_post( $post );		

			else:

				// Insert event
				$post = array(
				  'post_content'   => $the_post['wps_calendar_post'],
				  'post_name'      => sanitize_title_with_dashes($the_post['wps_calendar_title']),
				  'post_title'     => $the_post['wps_calendar_title'],
				  'post_status'    => 'publish',
				  'post_type'      => 'wps_event',
				  'ping_status'    => 'closed',
				  'comment_status' => $the_post['wps_event_comments'] ? 'open' : 'closed',
				);  
				$new_event_id = wp_insert_post( $post );		

			endif;

			if ($new_event_id):

				update_post_meta( $new_event_id, 'wps_event_calendar', $the_post['calendar_id'] );
				update_post_meta( $new_event_id, 'wps_event_start', $the_post['wps_event_start'] );
				update_post_meta( $new_event_id, 'wps_event_start_time', $the_post['wps_event_start_time'] );
				update_post_meta( $new_event_id, 'wps_event_end', $the_post['wps_event_end'] );
				update_post_meta( $new_event_id, 'wps_event_end_time', $the_post['wps_event_end_time'] );

				if ($_FILES):

			        $files = $_FILES['wps_event_image'];

			    	if ($files):

				        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
				        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

		                $file = array(
		                    'name' => $files['name'],
		                    'type' => $files['type'],
		                    'tmp_name' => $files['tmp_name'],
		                    'error' => $files['error'],
		                    'size' => $files['size']
		                );  


		                $overrides = array('test_form' => false);
		                $the_file = wp_handle_upload($file, $overrides);
		                if ( isset($the_file['error']) ){
		                    die( $the_file['error'] );
		                }

		                if (!(($the_file["type"] == "image/gif") || ($the_file["type"] == "image/jpeg") || ($the_file["type"] == "image/png") || ($the_file["type"] == "image/pjpeg") || ($the_file["type"] == "image/x-png"))):

		                    // Not an image type

		                else:

		                    // Re-size for use to avoid large files
		                    if (!class_exists('SimpleImage')) require_once('SimpleImage.php');
		                    $image = new SimpleImage();
		                    $image->load( $the_file['file'] );
		                    $image->resizeToWidth(1600);
		                    $image->save( $the_file['file'] );

							// $filename should be the path to a file in the upload directory.
							$filename = $the_file['file'];

							// Get the path to the upload directory.
							$wp_upload_dir = wp_upload_dir();
							// Win32 fix:
							$filename = str_replace( strtolower(str_replace('\\', '/', $wp_upload_dir['basedir'])), $wp_upload_dir['basedir'], $filename);

							// The ID of the post this attachment is for.
							$parent_post_id = $new_event_id;

							// Check the type of tile. We'll use this as the 'post_mime_type'.
							$filetype = wp_check_filetype( basename( $filename ), null );

							// Prepare an array of post data for the attachment.
							$attachment = array(
								'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
								'post_mime_type' => $filetype['type'],
								'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
								'post_content'   => '',
								'post_status'    => 'inherit'
							);

							// Insert the attachment.
							$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

							if ( !is_wp_error($attach_id) ):
								// Generate the metadata for the attachment, and update the database record.
								$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
								wp_update_attachment_metadata( $attach_id, $attach_data );
								set_post_thumbnail( $new_event_id, $attach_id );
							endif;

		                endif;

		            endif;

			    endif;

				// Any further actions?
				do_action( 'wps_calendar_event_add_hook', $the_post, $_FILES, $new_event_id );

			endif;

		}

		/* ADD COMMENT */
		if ($action == 'wps_calendar_comment_add') {

			$the_comment = $_POST;

			$data = array(
			    'comment_post_ID' => $the_comment['post_id'],
			    'comment_content' => $the_comment['wps_event_comment'],
			    'comment_type' => '',
			    'comment_parent' => 0,
			    'comment_author' => $current_user->user_login,
			    'comment_author_email' => $current_user->user_email,
			    'user_id' => $current_user->ID,
			    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
			    'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
			    'comment_approved' => 1,
			);

			$new_id = wp_insert_comment($data);

			if ($new_id):

			    if ($_FILES):

			        $files = $_FILES['wps_event_comment_image_upload'];

			        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

			        $attachment_ids = array();

		            foreach ($files['name'] as $key => $value):

		                if ($files['name'][$key]) {
		                    $file = array(
		                        'name' => $files['name'][$key],
		                        'type' => $files['type'][$key],
		                        'tmp_name' => $files['tmp_name'][$key],
		                        'error' => $files['error'][$key],
		                        'size' => $files['size'][$key]
		                    );  

		                }

		                $_FILES = array("attachment" => $file);
		                foreach ($_FILES as $file => $array):

		                    $attach_id = media_handle_upload( $file, $new_id ); 

		                    $uploaded_file = get_attached_file( $attach_id );

		                    $file_ext = strtolower(substr(strrchr($uploaded_file,'.'),1));

		                    $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
		                    $valid_image_exts = apply_filters( 'wps_calendar_attachments_valid_image_extensions_filter', $valid_image_exts, '' );

		                    $valid_document_exts = array('txt', 'rtf', 'pdf', 'zip');
		                    $valid_document_exts = apply_filters( 'wps_calendar_attachments_valid_document_extensions_filter', $valid_document_exts, '' );
		                    
		                    if (in_array($file_ext, $valid_image_exts) || in_array($file_ext, $valid_document_exts)):

		                        // Images            
		                        if (in_array($file_ext, $valid_image_exts)):
		                            array_push($attachment_ids, $attach_id);
		                            if (!class_exists('SimpleImage')) require_once('SimpleImage.php');
		                            $image = new SimpleImage();
		                            $image->load(get_attached_file( $attach_id ));
		                            $image->resizeToWidth(1600);
		                            $image->save(get_attached_file( $attach_id ));
		                        endif;

		                        // Other (documents)            
		                        if (in_array($file_ext, $valid_document_exts)):
		                            array_push($attachment_ids, $attach_id);
		                        endif;                

		                    else:

		                        // Delete from media library if not a valid file type
		                        wp_delete_attachment($attach_id, true);

		                    endif;

		                endforeach;

		                if (count($attachment_ids))
		                    update_comment_meta($new_id, 'wps_attachment_id', $attachment_ids);

		            endforeach; // for multiple comments

	            endif;

				// Any further actions?
				do_action( 'wps_calendar_comment_add_hook', $the_comment, $_FILES, $the_comment['post_id'], $new_id );

			endif;

		}
		
	}
		
}

?>
