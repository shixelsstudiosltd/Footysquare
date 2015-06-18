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

		/* ADD COMMENT */
		if ($action == 'wps_comment_post_add') {

			$item_id = $_POST['wps_comment_item_id'];
			
			global $current_user;
			$data = array(
			    'comment_post_ID' => $item_id,
			    'comment_content' => $_POST['post_comment_'.$item_id],
			    'comment_type' => '',
			    'comment_parent' => 0,
			    'comment_author' => $current_user->user_login,
			    'user_id' => $current_user->ID,
			    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
			    'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
			    'comment_approved' => 1,
			);

			$new_id = wp_insert_comment($data);

			if ($new_id):

				// Handle attachments

		        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

		        $files_passed = $_FILES;
		    	$files = $files_passed['wps_activity_image_upload_'.$item_id];

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
	                    $valid_image_exts = apply_filters( 'wps_activity_attachments_valid_image_extensions_filter', $valid_image_exts, '' );

	                    $valid_document_exts = array('txt', 'rtf', 'pdf', 'zip');
	                    $valid_document_exts = apply_filters( 'wps_activity_attachments_valid_document_extensions_filter', $valid_document_exts, '' );
	                    
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

				// Any further actions?
				do_action( 'wps_activity_comment_add_hook', $_POST, $new_id );

			else:

				echo 'Media failed to add';

			endif;

		} else {

			echo 'no relevant action';

		}

	} else {

		echo 'Not logged in';

	}

}


?>
