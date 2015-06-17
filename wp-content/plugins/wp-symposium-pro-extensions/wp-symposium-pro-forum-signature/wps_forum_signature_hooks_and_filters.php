<?php

// Show at foot of original post
add_filter('wps_forum_item_filter', 'wps_forum_signature_post_footer', 10, 4);
function wps_forum_signature_post_footer($post_html, $atts, $the_post, $post_content) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'signature_max_size' => 20,
        'signature_image_size' => 64
    ), $atts, 'wps_forum' ) );

    $author = $the_post->post_author;
    
    if ($author):
        $text = get_user_meta($author, 'wps_forum_signature', true) ? get_user_meta($author, 'wps_forum_signature', true) : '';
        $text = wps_get_words($text, $signature_max_size, '');
        $text = wps_bbcode_replace(convert_smilies(make_clickable(wpautop($text))));
        $image = ($image = get_user_meta($author, 'wps_forum_signature_image', true)) ? $image : false;

        if ($text || $image):
            $post_html .= '<div class="wps_forum_signature" style="margin-top:10px; clear: both;">';
                if ($image)
                    $post_html .= '<img src="'.$image.'" style="width:'.$signature_image_size.'px; float: left; margin-right:15px; margin-top:5px;" />';
                if ($text):
                    $post_html .= '<div class="wps_forum_signature_text">'.$text.'</div>';
                endif;
            $post_html .= '</div>';
        endif;
    endif;

    return $post_html;
}

// Show at foot of each comment
add_filter('wps_forum_item_comment_filter', 'wps_forum_signature_comment_footer', 10, 4);
function wps_forum_signature_comment_footer($post_html, $atts, $the_post, $comment_content) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'signature_max_size' => 20,
        'signature_image_size' => 64
    ), $atts, 'wps_forum' ) );

    $author = $the_post->comment_author;

    if ($author):
        $author = get_user_by('login', $author);
        if ($author):
            $author = $author->ID;
            $text = get_user_meta($author, 'wps_forum_signature', true) ? get_user_meta($author, 'wps_forum_signature', true) : '';
            $text = wps_get_words($text, $signature_max_size, '');
            $text = wps_bbcode_replace(convert_smilies(make_clickable(wpautop($text))));
            $image = ($image = get_user_meta($author, 'wps_forum_signature_image', true)) ? $image : false;

            if ($text || $image):
                $post_html .= '<div class="wps_forum_signature" style="clear: both;">';
                    if ($image)
                        $post_html .= '<img src="'.$image.'" style="width:'.$signature_image_size.'px; float: left; margin-right:15px; margin-top:5px;" />';
                    if ($text):
                        $post_html .= '<div class="wps_forum_signature_text">'.$text.'</div>';
                    endif;
                $post_html .= '</div>';
            endif;
        endif;

    endif;

    return $post_html;
}

// Add signature to wps_usermeta_change
add_filter('wps_usermeta_change_filter', 'wps_forum_signature_usermeta_extend', 10, 3);
function wps_forum_signature_usermeta_extend($form_html, $atts, $user_id) {

	// Shortcode parameters
	extract( shortcode_atts( array(
		'label_forum_signature' => __('Signature to add to all forum posts/replies', WPS2_TEXT_DOMAIN),
        'label_forum_signature_image' => __('Choose an image...', WPS2_TEXT_DOMAIN),
        'label_forum_signature_image_remove' => __('tick to remove image', WPS2_TEXT_DOMAIN),
	), $atts, 'wps_usermeta_change' ) );

	$form_html .= '<div class="wps_usermeta_change_item">';
		$form_html .= '<div class="wps_usermeta_change_label">'.$label_forum_signature.'</div>';
		$image = get_user_meta($user_id, 'wps_forum_signature_image', true) ? get_user_meta($user_id, 'wps_forum_signature_image', true) : false;
		if ($image)
			$form_html .= '<img src="'.$image.'" style="width:64px; float: left; margin-right:15px; margin-bottom:10px;" />';
        $form_html .= '<input title="'.$label_forum_signature_image.'" id="wps_forum_signature_image" name="wps_forum_signature_image[]" size="50" type="file" />';
        $value = get_user_meta($user_id, 'wps_forum_signature', true) ? get_user_meta($user_id, 'wps_forum_signature', true) : '';
        if ($image)
            $form_html .= '<div style="font-size:0.8em"><input name="wps_forum_signature_image_remove" type="checkbox" /> '.$label_forum_signature_image_remove.'</div>';
        $form_html .= '<input name="wps_forum_signature" type="text" style="width:100%" value="'.$value.'" />';
	$form_html .= '</div>';

	return $form_html;

}

// Extend wps_usermeta_change save
add_action( 'wps_usermeta_change_hook', 'wps_forum_signature_usermeta_save', 10, 4 );
function wps_forum_signature_usermeta_save($user_id, $atts, $the_form, $the_files) {

	if (isset($the_form['wps_forum_signature'])):
        $signature = $the_form['wps_forum_signature'];
        $signature = str_replace(
          array('"', '<', '>'), 
          array("'", '&#91;', '&#93;'), 
          $signature
        );                                          
		update_user_meta($user_id, 'wps_forum_signature', $signature);
	else:
		delete_user_meta($user_id, 'wps_forum_signature');
	endif;

    if (isset($the_form['wps_forum_signature_image_remove'])):

        $value = get_user_meta($user_id, 'wps_forum_signature_image_path', true);
        @unlink( $value );
        delete_user_meta($user_id, 'wps_forum_signature_image');
        delete_user_meta($user_id, 'wps_forum_signature_image_path');

	elseif ($the_files):

        $files = $the_files['wps_forum_signature_image'];

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

        foreach ($files['name'] as $key => $value):

            if ($files['name'][$key]):

                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );  

                $overrides = array('test_form' => false);
                $the_file = wp_handle_upload($file, $overrides);
                if ( isset($the_file['error']) ){
                    die( $the_file['error'] );
                }

                if (!(($the_file["type"] == "image/gif") || ($the_file["type"] == "image/jpeg") || ($the_file["type"] == "image/png") || ($the_file["type"] == "image/pjpeg") || ($the_file["type"] == "image/x-png"))):

                    echo '<div class="wps_error">'.__('Forum signature image must be an image file type!', WPS2_TEXT_DOMAIN).'</div>';

                else:

                    // Update user meta
                    update_user_meta($user_id, 'wps_forum_signature_image', $the_file['url']);
                    update_user_meta($user_id, 'wps_forum_signature_image_path', $the_file['file']);

                    // Re-size for use to avoid large files
                    if (!class_exists('SimpleImage')) require_once('SimpleImage.php');
                    $image = new SimpleImage();
                    $image->load( $the_file['file'] );
                    $image->resizeToWidth(128);
                    $image->save( $the_file['file'] );

                endif;

            endif;

        endforeach; // for each file

    endif;

}


?>