<?php
// AJAX functions for activity
add_action( 'wp_ajax_wps_forum_attachments_delete', 'wps_forum_attachments_delete' ); 

/* DELETE ATTACHMENT */
function wps_forum_attachments_delete() {

    wp_delete_attachment( $_POST['attachment_id'], false );
    echo $_POST['attachment_id'];

}

// Init
add_action('wps_forum_init_hook', 'wps_forum_attachments_init');
function wps_forum_attachments_init() {
    wp_enqueue_script('wps-forum-attachments-js', plugins_url('wps_forum_attachments.js', __FILE__), array('jquery'));    
    wp_enqueue_style('wps-forum-attachments-css', plugins_url('wps_forum_attachments.css', __FILE__), 'css');
    wp_localize_script( 'wps-forum-attachments-js', 'wps_forum_attachments_ajax', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ));     

}

/**
 * Filter to add action to attach an image (post)
 **/
add_filter('wps_forum_post_pre_form_filter', 'wps_forum_post_form_attachments',10,4);
function wps_forum_post_form_attachments($html, $atts, $current_user_id, $term) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'attachment_label' => __('Attach a file', WPS2_TEXT_DOMAIN),
    ), $atts, 'wps_forum_post' ) );

    $html .= '<div class="wps_forum_image_upload_button att_multiple"><input title="'.$attachment_label.'" id="wps_forum_post_image_upload" name="wps_forum_post_image_upload[]" size="50" multiple type="file" /></div>';
    $html .= '<div class="wps_forum_image_upload_button att_single" style="display:none"><input title="'.$attachment_label.'" id="wps_forum_post_image_upload" name="wps_forum_post_image_upload" size="50" type="file" /></div>';

    return $html;

}

/**
 * Filter to add action to attach an image (comment)
 **/
add_filter('wps_forum_comment_pre_form_filter', 'wps_forum_comment_form_attachments',10,3);
function wps_forum_comment_form_attachments($html, $atts, $current_user_id) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'attachment_label' => __('Attach a file', WPS2_TEXT_DOMAIN),
    ), $atts, 'wps_mail' ) );

    $html .= '<div class="wps_forum_image_upload_button att_multiple"><input title="'.$attachment_label.'" id="wps_forum_comment_image_upload" name="wps_forum_comment_image_upload[]" multiple size="50" type="file" /></div>';
    $html .= '<div class="wps_forum_image_upload_button att_single" style="display:none"><input title="'.$attachment_label.'" id="wps_forum_comment_image_upload" name="wps_forum_comment_image_upload" size="50" type="file" /></div>';

    return $html;

}

/**
 * Filter to show any attachments for post
 **/
add_filter('wps_forum_item_filter', 'wps_forum_attachments',10,4);
function wps_forum_attachments($item_html, $atts, $item, $content) {

    $attachments = get_posts( array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_parent' => $item->ID,
    ) );

    $atts_to_pass = $atts ? $atts : '';
    
    global $current_user;

    if ( $attachments ) {
        foreach ( $attachments as $attachment ) {

            // Get extensions and file type
            $file_ext = strtolower(substr(strrchr(get_attached_file($attachment->ID),'.'),1));
            
            // Images, setting defaults (use filters to change)
            $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
            $valid_image_exts = apply_filters( 'wps_forum_attachments_valid_image_extensions_filter', $valid_image_exts, $atts_to_pass );

            if (in_array($file_ext, $valid_image_exts)):
                $item_html .= '<div id="forum_attachment_'.$attachment->ID.'" style="position:relative" class="wps_forum_item_attachment">'.wp_get_attachment_image($attachment->ID, 'thumbnail');            
                    $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
                    $image_src = wp_get_attachment_image_src( $attachment->ID, 'full' );
                    $item_html .= '<div data-width="'.$image_src[1].'" data-height="'.$image_src[2].'" class="wps_forum_item_attachment_full">'.$image_src[0].'</div>';
                    if ($item->post_author == $current_user->ID || current_user_can('manage_options')) $item_html .= '<img title="'.__('Delete attachment', WPS2_TEXT_DOMAIN).'" class="wps_forum_delete_attachment" rel="'.$attachment->ID.'" style="cursor:pointer;float:left;height:15px;width:15px;position:absolute;left:5px;top:5px;" src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                $item_html .= '</div>'; 
            endif;

            // Other (documents), setting defaults (use filters to change)
            $valid_document_exts = array('txt', 'rtf', 'pdf', 'zip');
            $valid_document_exts = apply_filters( 'wps_forum_attachments_valid_document_extensions_filter', $valid_document_exts, $atts_to_pass );

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
add_filter('wps_forum_item_comment_filter', 'wps_forum_comment_attachments',10,4);
function wps_forum_comment_attachments($item_html, $atts, $item, $comment_content) {

    if (get_comment_meta($item->comment_ID, 'wps_attachment_id', true)):

        global $current_user;

        $attachments = get_posts( array(
            'post_type' => 'attachment',
            'include' => get_comment_meta($item->comment_ID, 'wps_attachment_id', true),
            'post_id' => $item->comment_ID,
        ) );

        $atts_to_pass = $atts ? $atts : '';

        if ( $attachments ) {
            foreach ( $attachments as $attachment ) {

                // Get extensions and file type
                $file_ext = strtolower(substr(strrchr(get_attached_file($attachment->ID),'.'),1));
                
                // Images, setting defaults (use filters to change)
                $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
                $valid_image_exts = apply_filters( 'wps_forum_attachments_valid_image_extensions_filter', $valid_image_exts, $atts_to_pass );

                if (in_array($file_ext, $valid_image_exts)):
                    $item_html .= '<div id="forum_attachment_'.$attachment->ID.'" style="position:relative" class="wps_forum_item_attachment">'.wp_get_attachment_image($attachment->ID, 'thumbnail');            
                        $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
                        $thumbimg = wp_get_attachment_image_src( $attachment->ID, 'full' );
                        $item_html .= '<div data-width="'.$thumbimg[1].'" data-height="'.$thumbimg[2].'" class="wps_forum_item_attachment_full">' . $thumbimg[0] . '</div>';
                        if ($item->user_id == $current_user->ID || current_user_can('manage_options')) $item_html .= '<img title="'.__('Delete attachment', WPS2_TEXT_DOMAIN).'" class="wps_forum_delete_attachment" rel="'.$attachment->ID.'" style="cursor:pointer;float:left;height:15px;width:15px;position:absolute;left:5px;top:5px;" src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                    $item_html .= '</div>';
                endif;

                // Other (documents), setting defaults (use filters to change)
                $valid_document_exts = array('txt', 'rtf', 'pdf', 'zip');
                $valid_document_exts = apply_filters( 'wps_forum_attachments_valid_document_extensions_filter', $valid_document_exts, $atts_to_pass );
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
add_action( 'wps_forum_post_add_hook', 'wps_process_post_forum_attachment', 10, 3 );
function wps_process_post_forum_attachment( $post_vars, $files_var, $new_id ) {

    if ($files_var) {

        $files = $files_var['wps_forum_post_image_upload'];

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

        if (!is_int($files['size'])):

            // Multiple file type

            foreach ($files['name'] as $key => $value):

                if ($files['name'][$key]) {
                    
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );  

                    $_FILES = array("attachment" => $file);
                    foreach ($_FILES as $file => $array):

                        $attach_id = media_handle_upload( $file, $new_id ); 

                        $uploaded_file = get_attached_file( $attach_id );
                        $file_ext = strtolower(substr(strrchr($uploaded_file,'.'),1));

                        $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
                        $valid_image_exts = apply_filters( 'wps_forum_attachments_valid_image_extensions_filter', $valid_image_exts, '' );

                        $valid_document_exts = array('txt', 'rtf', 'pdf', 'zip');
                        $valid_document_exts = apply_filters( 'wps_forum_attachments_valid_document_extensions_filter', $valid_document_exts, '' );
                        
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

                }

            endforeach; // for multiple files

        else:

            // Single file type

            $file = array(
                'name' => $files['name'],
                'type' => $files['type'],
                'tmp_name' => $files['tmp_name'],
                'error' => $files['error'],
                'size' => $files['size']
            );  

            $_FILES = array("attachment" => $file);
            foreach ($_FILES as $file => $array):

                $attach_id = media_handle_upload( $file, $new_id ); 

                $uploaded_file = get_attached_file( $attach_id );
                $file_ext = strtolower(substr(strrchr($uploaded_file,'.'),1));

                $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
                $valid_image_exts = apply_filters( 'wps_forum_attachments_valid_image_extensions_filter', $valid_image_exts, '' );

                $valid_document_exts = array('txt', 'rtf', 'pdf', 'zip');
                $valid_document_exts = apply_filters( 'wps_forum_attachments_valid_document_extensions_filter', $valid_document_exts, '' );
                
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

            endforeach; // for single file

        endif;

    }

}

/**
 * Hook to process uploaded files and attach to new comment
 **/
add_action( 'wps_forum_comment_add_hook', 'wps_process_comment_forum_attachment', 10, 4 );
function wps_process_comment_forum_attachment( $the_comment, $files_var, $post_id, $new_id ) {

    if ($files_var) {

        $files = $files_var['wps_forum_comment_image_upload'];

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

        $attachment_ids = array();
        if (!is_int($files['size'])):

            // Multiple file type

            foreach ($files['name'] as $key => $value):

                $file = false;
                if ($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );  

                    if (is_array($file)):

                        $_FILES = array("attachment" => $file);
                        foreach ($_FILES as $file => $array):

                            $attach_id = media_handle_upload( $file, $new_id ); 

                            $uploaded_file = get_attached_file( $attach_id );

                            $file_ext = strtolower(substr(strrchr($uploaded_file,'.'),1));

                            $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
                            $valid_image_exts = apply_filters( 'wps_forum_attachments_valid_image_extensions_filter', $valid_image_exts, '' );

                            $valid_document_exts = array('txt', 'rtf', 'pdf', 'zip');
                            $valid_document_exts = apply_filters( 'wps_forum_attachments_valid_document_extensions_filter', $valid_document_exts, '' );

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

                    endif;
                    
                }

            endforeach; // for multiple comments

        else:

            $file = array(
                'name' => $files['name'],
                'type' => $files['type'],
                'tmp_name' => $files['tmp_name'],
                'error' => $files['error'],
                'size' => $files['size']
            );  

            $_FILES = array("attachment" => $file);
            foreach ($_FILES as $file => $array):

                $attach_id = media_handle_upload( $file, $new_id ); 

                $uploaded_file = get_attached_file( $attach_id );

                $file_ext = strtolower(substr(strrchr($uploaded_file,'.'),1));

                $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
                $valid_image_exts = apply_filters( 'wps_forum_attachments_valid_image_extensions_filter', $valid_image_exts, '' );

                $valid_document_exts = array('txt', 'rtf', 'pdf', 'zip');
                $valid_document_exts = apply_filters( 'wps_forum_attachments_valid_document_extensions_filter', $valid_document_exts, '' );

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

            endforeach; // for single comment

            if (count($attachment_ids))
                update_comment_meta($new_id, 'wps_attachment_id', $attachment_ids);

        endif;

    }

}

?>