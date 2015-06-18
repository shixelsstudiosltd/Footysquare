<?php
// AJAX functions for activity
add_action( 'wp_ajax_wps_activity_attachments_delete', 'wps_activity_attachments_delete' ); 

/* DELETE ATTACHMENT */
function wps_activity_attachments_delete() {

    wp_delete_attachment( $_POST['attachment_id'], false );
    echo $_POST['attachment_id'];

}

// Init
add_action('wps_activity_init_hook', 'wps_attachments_init');
function wps_attachments_init() {

    wp_enqueue_script('wps-attachments-js', plugins_url('wps_attachments.js', __FILE__), array('jquery'));    
    wp_enqueue_style('wps-attachments-css', plugins_url('wps_attachments.css', __FILE__), 'css');

    wp_localize_script( 'wps-attachments-js', 'wps_attachments_ajax', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'plugins_url' => plugins_url( '', __FILE__ )
    ));     

}

/**
 * Filter to add action to attach an image
 **/
add_filter('wps_activity_post_pre_form_filter', 'wps_activity_post_form_attachments',10,4);
function wps_activity_post_form_attachments($html, $atts, $user_id, $current_user_id) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'attachment_label' => __('Attach an image', WPS2_TEXT_DOMAIN),
    ), $atts, 'wps_activity' ) );

    $html .= '<div class="wps_activity_image_upload_button att_multiple"><input title="'.$attachment_label.'" id="wps_activity_image_upload" name="wps_activity_image_upload[]" size="50" multiple type="file" /></div>';
    $html .= '<div class="wps_activity_image_upload_button att_single" style="display:none"><input title="'.$attachment_label.'" id="wps_activity_image_upload" name="wps_activity_image_upload" size="50" type="file" /></div>';

    return $html;

}

/**
 * Filter to show any image attachments
 **/
add_filter('wps_activity_item_filter', 'wps_activity_attachments',10,7);
function wps_activity_attachments($item_html, $atts, $item_id, $item_title, $user_id, $current_user_id, $shown_count) {

    $attachments = get_posts( array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_parent' => $item_id,
    ) );
    
    if ( $attachments ) {
        foreach ( $attachments as $attachment ) {

            // Get extensions and file type
            $file_ext = strtolower(substr(strrchr(get_attached_file($attachment->ID),'.'),1));
            
            // Images, setting defaults (use filters to change)
            $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
            $valid_image_exts = apply_filters( 'wps_activity_attachments_valid_image_extensions_filter', $valid_image_exts, $atts );

            if (in_array($file_ext, $valid_image_exts)):
                $item_html .= '<div id="activity_attachment_'.$attachment->ID.'" style="position: relative" class="wps_activity_item_attachment">'.wp_get_attachment_image($attachment->ID, 'thumbnail');            
                    $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
                    $image_src = wp_get_attachment_image_src( $attachment->ID, 'full' );
                    $item_html .= '<div data-width="'.$image_src[1].'" data-height="'.$image_src[2].'" class="wps_activity_item_attachment_full">'.$image_src[0].'</div>';
            if ($user_id == $current_user_id || current_user_can('manage_options')) $item_html .= '<img title="'.__('Delete attachment', WPS2_TEXT_DOMAIN).'" class="wps_activity_delete_attachment" rel="'.$attachment->ID.'" style="cursor:pointer;float:left;height:15px;width:15px;position:absolute;left:5px;top:5px;" src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                $item_html .= '</div>'; 
            endif;

            // Other (documents), setting defaults (use filters to change)
            $valid_document_exts = array('txt', 'rtf', 'pdf');
            $valid_document_exts = apply_filters( 'wps_activity_attachments_valid_document_extensions_filter', $valid_document_exts, $atts );

            if (in_array($file_ext, $valid_document_exts)):
                $item_html .= '<a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">'.basename(get_attached_file($attachment->ID)).'</a>';
            endif;

        }

        $item_html .= '<br style="clear:both" />';
        
    }

    return $item_html;

}

/**
 * Hook to process uploaded files and attach to new post
 **/
add_action( 'wps_activity_post_add_hook', 'wps_process_post_attachment', 10, 3 );
function wps_process_post_attachment( $post_vars, $files_var, $new_id ) {

    if ($files_var) {

        $files = $files_var['wps_activity_image_upload'];
        
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

        if (!is_int($files['size'])):
        
            // Multiple files
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
                        $valid_image_exts = apply_filters( 'wps_activity_attachments_valid_image_extensions_filter', $valid_image_exts );

                        $valid_document_exts = array('txt', 'rtf', 'pdf');
                        $valid_document_exts = apply_filters( 'wps_activity_attachments_valid_document_extensions_filter', $valid_document_exts );

                        if (in_array($file_ext, $valid_image_exts) || in_array($file_ext, $valid_document_exts)):

                            // Images            
                            if (in_array($file_ext, $valid_image_exts)):
                                if (!class_exists('SimpleImage')) require_once('SimpleImage.php');
                                $image = new SimpleImage();
                                $image->load(get_attached_file( $attach_id ));
                                $image->resizeToWidth(1600);
                                $image->save(get_attached_file( $attach_id ));
                            endif;

                            // Other (documents)            
                            $valid_document_types = array('text/plain', 'application/rtf', 'application/pdf');
                            $valid_document_types = apply_filters( 'wps_activity_attachments_valid_document_types_filter', $valid_document_types );

                        else:

                            // Delete from media library if not a valid file type
                            wp_delete_attachment($attach_id, true);

                        endif;

                    endforeach;
                    
                }

            endforeach; // for multiple file

        else:
        
            // Single files
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
                $valid_image_exts = apply_filters( 'wps_activity_attachments_valid_image_extensions_filter', $valid_image_exts, array() );

                $valid_document_exts = array('txt', 'rtf', 'pdf');
                $valid_document_exts = apply_filters( 'wps_activity_attachments_valid_document_extensions_filter', $valid_document_exts, array() );
                
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

        endif;

    }

}

/**
 * Filter to add action to attach an image to comment
 **/
add_filter('wps_activity_new_comment_filter', 'wps_activity_new_comment_filter',10,5);
function wps_activity_new_comment_filter($html, $atts, $item_id, $user_id, $current_user_id) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'comment_attachment_label' => __('Attach an image', WPS2_TEXT_DOMAIN),
        'label' => __('Comment', WPS2_TEXT_DOMAIN),
        'class' => '',
    ), $atts, 'wps_activity' ) );

    $html = '<a name="wps_comment_'.$item_id.'"></a>';
    $html .= '<div class="wps_activity_post_comment_div">';
        $html .= '<form id="thecommentuploadform_'.$item_id.'">';
        $html .= '<input type="hidden" name="wps_comment_item_id" value="'.$item_id.'" />';
        $html .= '<input type="hidden" id="wps_comment_post_action" name="action" value="wps_comment_post_add" />';
        $html .= '<input type="hidden" name="wps_comment_post_target" value="'.$user_id.'" />';
        $html .= '<textarea class="wps_activity_post_comment" id="post_comment_'.$item_id.'" name="post_comment_'.$item_id.'"></textarea>';
        $html .= '<div class="wps_activity_comment_image_upload_button att_multiple"><input title="'.$comment_attachment_label.'" id="wps_activity_image_upload_'.$item_id.'" name="wps_activity_image_upload_'.$item_id.'[]" size="50" multiple type="file" /></div>';
        $html .= '<input type="submit" class="wps_submit '.$class.' wps_activity_post_comment_att_button" rel="'.$item_id.'" value="'.$label.'" />';
        $html .= '</form>';
    $html .= '</div>';


    return $html;

}

/**
 * Filter to show any attachments for comment
 **/
add_filter('wps_activity_post_comment_filter', 'wps_activity_comment_attachments',10,6);
function wps_activity_comment_attachments($item_html, $atts, $item_id, $comment_id, $user_id, $current_user_id) {

    $attachment_ids = get_comment_meta($comment_id, 'wps_attachment_id', true);

    if ($attachment_ids):

        global $current_user;

        $attachments = get_posts( array(
            'post_type' => 'attachment',
            'include' => $attachment_ids,
            'post_id' => $item_id,
        ) );

        $atts_to_pass = $atts ? $atts : '';

        if ( $attachments ) {
            foreach ( $attachments as $attachment ) {

                // Get extensions and file type
                $file_ext = strtolower(substr(strrchr(get_attached_file($attachment->ID),'.'),1));
                
                // Images, setting defaults (use filters to change)
                $valid_image_exts = array('jpg', 'png', 'gif', 'jpeg', 'svg');
                $valid_image_exts = apply_filters( 'wps_activity_attachments_valid_image_extensions_filter', $valid_image_exts, $atts_to_pass );

                if (in_array($file_ext, $valid_image_exts)):
                    $item_html .= '<div id="activity_attachment_'.$attachment->ID.'" style="position:relative" class="wps_activity_item_attachment">'.wp_get_attachment_image($attachment->ID, 'thumbnail');            
                        $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
                        $thumbimg = wp_get_attachment_image_src( $attachment->ID, 'full' );
                        $item_html .= '<div data-width="'.$thumbimg[1].'" data-height="'.$thumbimg[2].'" class="wps_activity_item_attachment_full">' . $thumbimg[0] . '</div>';
                        if ($user_id == $current_user_id || current_user_can('manage_options')) $item_html .= '<img title="'.__('Delete attachment', WPS2_TEXT_DOMAIN).'" class="wps_activity_delete_attachment" rel="'.$attachment->ID.'" style="cursor:pointer;float:left;height:15px;width:15px;position:absolute;left:5px;top:5px;" src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                    $item_html .= '</div>';
                endif;

                // Other (documents), setting defaults (use filters to change)
                $valid_document_exts = array('txt', 'rtf', 'pdf', 'zip');
                $valid_document_exts = apply_filters( 'wps_activity_attachments_valid_document_extensions_filter', $valid_document_exts, $atts_to_pass );
                if (in_array($file_ext, $valid_document_exts)):
                    if ($user_id == $current_user_id || current_user_can('manage_options')) $item_html .= '<img title="'.__('Delete attachment', WPS2_TEXT_DOMAIN).'" class="wps_activity_delete_attachment" rel="'.$attachment->ID.'" style="cursor:pointer;float:left;margin-right:10px;margin-top:3px;height:15px;width:15px;" src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                    $item_html .= '<div id="activity_attachment_'.$attachment->ID.'" class="wps_activity_document_attachment"><a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">'.basename(get_attached_file($attachment->ID)).'</a></div>';
                endif;

            }
        };

    endif; // No attachments for this comment

    return $item_html;

}
?>