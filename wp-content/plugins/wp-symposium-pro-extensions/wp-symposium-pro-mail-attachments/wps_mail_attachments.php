<?php
// Init
add_action('wps_mail_init_hook', 'wps_mail_attachments_init');
function wps_mail_attachments_init() {
        
    wp_enqueue_script('wps-mail-attachments-js', plugins_url('wps_mail_attachments.js', __FILE__), array('jquery'));    
    wp_enqueue_style('wps-mail-attachments-css', plugins_url('wps_mail_attachments.css', __FILE__), 'css');

}

/**
 * Filter to add div for showing images
 **/
add_filter('wps_mail_message_pre_filter', 'wps_mail_prepare_attachment_div',10,5);
function wps_mail_prepare_attachment_div($html, $atts, $mail_item, $recipients) {

    $html .= '<div id="wps_mail_attachment_dialog"></div>';

    return $html;

}


/**
 * Filter to add action to attach an image (post)
 **/
add_filter('wps_mail_textarea_pre_form_filter', 'wps_mail_post_form_attachments',10,4);
function wps_mail_post_form_attachments($html, $atts, $current_user_id) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'attach_label' => __('Attach a file', WPS2_TEXT_DOMAIN),
    ), $atts, 'wps_mail' ) );

    $html .= '<div class="wps_activity_image_upload_button"><input title="'.$attach_label.'" id="wps_mail_image_upload" name="wps_mail_image_upload[]" size="50" multiple type="file" /></div>';

    return $html;

}

/**
 * Filter to add action to attach an image (comment)
 **/
add_filter('wps_mail_comment_pre_form_filter', 'wps_mail_comment_form_attachments',10,4);
function wps_mail_comment_form_attachments($html, $atts, $current_user_id) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'attach_label' => __('Attach a file', WPS2_TEXT_DOMAIN),
    ), $atts, 'wps_mail' ) );

    $html .= '<div class="wps_activity_image_upload_button"><input title="'.$attach_label.'" id="wps_mail_comment_image_upload" name="wps_mail_comment_image_upload[]" size="50" multiple type="file" /></div>';

    return $html;

}

/**
 * Filter to show any attachments for post
 **/
add_filter('wps_mail_item_filter', 'wps_mail_attachments',10,4);
function wps_mail_attachments($item_html, $atts, $item, $content) {

    $attachments = get_posts( array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_parent' => $item->ID,
    ) );
    
    if ( $attachments ) {
        foreach ( $attachments as $attachment ) {

            // Get extensions and file type
            $file_ext = strtolower(substr(strrchr(get_attached_file($attachment->ID),'.'),1));
            
            // Images, setting defaults (use filters to change)
            $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
            $valid_image_exts = apply_filters( 'wps_mail_attachments_valid_image_extensions_filter', $valid_image_exts, $atts );

            if (in_array($file_ext, $valid_image_exts)):
                $item_html .= '<div class="wps_mail_item_attachment">'.wp_get_attachment_image($attachment->ID, 'thumbnail');            
                    $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
                    $image_src = wp_get_attachment_image_src( $attachment->ID, 'full' );
                    $item_html .= '<div data-width="'.$image_src[1].'" data-height="'.$image_src[2].'" class="wps_mail_item_attachment_full">'.$image_src[0].'</div>';
                $item_html .= '</div>'; 
            endif;

            // Other (documents), setting defaults (use filters to change)
            $valid_document_exts = array('txt', 'rtf', 'pdf');
            $valid_document_exts = apply_filters( 'wps_mail_attachments_valid_document_extensions_filter', $valid_document_exts, $atts );

            if (in_array($file_ext, $valid_document_exts)):
                $item_html .= '<a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">'.basename(get_attached_file($attachment->ID)).'</a>';
            endif;

        }
        
    }

    return $item_html;

}

/**
 * Filter to show any attachments for comment
 **/
add_filter('wps_mail_item_comment_filter', 'wps_mail_comment_attachments',10,4);
function wps_mail_comment_attachments($item_html, $atts, $item, $content) {

    if (get_comment_meta($item->comment_ID, 'wps_attachment_id', true)):

        $attachments = get_posts( array(
            'post_type' => 'attachment',
            'include' => get_comment_meta($item->comment_ID, 'wps_attachment_id', true),
            'post_id' => $item->comment_ID,
        ) );

        if ( $attachments ) {
            foreach ( $attachments as $attachment ) {

                // Get extensions and file type
                $file_ext = strtolower(substr(strrchr(get_attached_file($attachment->ID),'.'),1));
                
                // Images, setting defaults (use filters to change)
                $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
                $valid_image_exts = apply_filters( 'wps_mail_attachments_valid_image_extensions_filter', $valid_image_exts, $atts );

                if (in_array($file_ext, $valid_image_exts)):
                    $item_html .= '<div class="wps_mail_item_attachment">'.wp_get_attachment_image($attachment->ID, 'thumbnail');            
                        $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
                        $thumbimg = wp_get_attachment_image_src( $attachment->ID, 'full' );
                        $item_html .= '<div data-width="'.$thumbimg[1].'" data-height="'.$thumbimg[2].'" class="wps_mail_item_attachment_full">' . $thumbimg[0] . '</div>';
                    $item_html .= '</div>';                
                endif;

                // Other (documents), setting defaults (use filters to change)
                $valid_document_exts = array('txt', 'rtf', 'pdf');
                $valid_document_exts = apply_filters( 'wps_mail_attachments_valid_document_extensions_filter', $valid_document_exts, $atts );
                if (in_array($file_ext, $valid_document_exts)):
                    $item_html .= '<a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">'.basename(get_attached_file($attachment->ID)).'</a>';
                endif;

            }
        };

    endif; // No attachments for this comment

    return $item_html;

}

/**
 * Hook to process uploaded files and attach to new post
 **/
add_action( 'wps_mail_post_add_hook', 'wps_process_post_mail_attachment', 10, 3 );
function wps_process_post_mail_attachment( $post_vars, $files_var, $new_id ) {

    if ($files_var) {

        $files = $files_var['wps_mail_image_upload'];

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

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
                $valid_image_exts = apply_filters( 'wps_mail_attachments_valid_image_extensions_filter', $valid_image_exts );

                $valid_document_exts = array('txt', 'rtf', 'pdf');
                $valid_document_exts = apply_filters( 'wps_mail_attachments_valid_document_extenions_filter', $valid_document_exts );
                
                if (in_array($file_ext, $valid_image_exts) || in_array($file_ext, $valid_document_exts)):

                    // Images            
                    if (in_array($file_ext, $valid_image_exts)):
                        if (!class_exists('SimpleImage')) require_once('SimpleImage.php');
                        $image = new SimpleImage();
                        $image->load(get_attached_file( $attach_id ));
                        $image->resizeToWidth(1600);
                        $image->save(get_attached_file( $attach_id ));
                    endif;

                else:

                    // Delete from media library if not a valid file type
                    wp_delete_attachment($attach_id, true);

                endif;

            endforeach;

        endforeach; // for each file
    }

}

/**
 * Hook to process uploaded files and attach to new comment
 **/
add_action( 'wps_mail_comment_add_hook', 'wps_process_comment_mail_attachment', 10, 4 );
function wps_process_comment_mail_attachment( $post_vars, $files_var, $post_id, $new_id ) {

    if ($files_var) {

        $files = $files_var['wps_mail_comment_image_upload'];

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
                $valid_image_exts = apply_filters( 'wps_mail_attachments_valid_image_extensions_filter', $valid_image_exts );

                $valid_document_exts = array('txt', 'rtf', 'pdf');
                $valid_document_exts = apply_filters( 'wps_mail_attachments_valid_document_extenions_filter', $valid_document_exts );
                
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

        endforeach;

    }

}

?>