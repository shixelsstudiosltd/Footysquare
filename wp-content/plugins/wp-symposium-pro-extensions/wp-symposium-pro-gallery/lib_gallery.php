<?php
while(!is_file('wp-config.php')){
	if(is_dir('../')) chdir('../');
	else die('Could not find WordPress config file.');
}
include_once( 'wp-config.php' );

$action = isset($_POST['action']) ? $_POST['action'] : false;

global $current_user;
get_currentuserinfo();

if ( is_user_logged_in() ) {

    /* ADD FILE(S) */
    if ($action == 'wps_gallery_add') {

        if ($_FILES) {

            $item_count=0;
            $items = array();

            $files = $_FILES['wps_gallery_upload'];

            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');

            // Get allowed file extensions
            $image_types = explode(',', $_POST['wps_image_types']);
            $document_types = explode(',', $_POST['wps_document_types']);

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

                    $attach_id = media_handle_upload( $file, $_POST['post_id'] ); 

                    $uploaded_file = get_attached_file( $attach_id );
                    $file_ext = strtolower(substr(strrchr($uploaded_file,'.'),1));

                    $valid_image_exts = $image_types;
                    $valid_image_exts = apply_filters( 'wps_gallery_attachments_valid_image_extensions_filter', $valid_image_exts );

                    $valid_document_exts = $document_types;
                    $valid_document_exts = apply_filters( 'wps_gallery_attachments_valid_document_extenions_filter', $valid_document_exts );

                    if (in_array($file_ext, $valid_image_exts) || in_array($file_ext, $valid_document_exts)):

                        // Images            
                        if (in_array($file_ext, $valid_image_exts)):
                            if (!class_exists('SimpleImage')) require_once('SimpleImage.php');
                            $image = new SimpleImage();
                            $image->load(get_attached_file( $attach_id ));
                            $image->resizeToWidth(1600);
                            $image->save(get_attached_file( $attach_id ));
                        endif;

                        // Check for an existing featured image, and if none, make this image it
                        $featured_image = get_post_thumbnail_id($_POST['post_id']);
                        if (!$featured_image):
                            update_post_meta($_POST['post_id'], '_thumbnail_id', $attach_id);
                        endif;

                        $item_count++;
                        array_push($items, $attach_id);

                    else:

                        // Delete from media library if not a valid file type
                        wp_delete_attachment($attach_id, true);

                    endif;

                endforeach;

            endforeach; // for each file

            // Update album date
            update_post_meta( $_POST['post_id'], 'wps_gallery_updated', current_time('Y-m-d H:i:s', 0) );                

            /* ADD TO ACTIVITY */
            if ($items):

                $item_text = ($item_count != 1) ? __('items', WPS2_TEXT_DOMAIN) : __('item', WPS2_TEXT_DOMAIN);
                $album = get_post($_POST['post_id']);
                $url = get_page_link(get_option('wpspro_gallery_page'));
                $url = $url.wps_query_mark($url).'user_id='.$current_user->ID.'&gallery_id='.$_POST['post_id'];

                $items_html = '[items]';

                foreach ( $items as $attachment ) {
                    $items_html .= $attachment.',';
                }

                $post = array(
                  'post_title'     => sprintf(__('added %d %s to [a] href=%s[a2]%s[/a]', WPS2_TEXT_DOMAIN), $item_count, $item_text, $url, $album->post_title).$items_html,
                  'post_status'    => 'publish',
                  'post_type'      => 'wps_activity',
                  'post_author'    => $current_user->ID,
                  'ping_status'    => 'closed',
                  'comment_status' => 'open',
                );  
                $new_id = wp_insert_post( $post );

                if ($new_id) update_post_meta( $new_id, 'wps_target', $current_user->ID );

            endif;

            // Any further actions?
            do_action( 'wps_gallery_add', $_POST, $_FILES, $new_id );

        }
    }

}

?>
