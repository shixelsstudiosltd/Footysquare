<?php

function wps_group_edit_form($group_id, $atts) {

	global $current_user;

	$html = '';

	$the_post = get_post($group_id);

	if ($current_user->ID == $the_post->post_author || current_user_can('manage_options')):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'title_label' => __('Group title', WPS2_TEXT_DOMAIN),
			'content_label' => __('Description', WPS2_TEXT_DOMAIN),
			'cancel_label' => __('Cancel', WPS2_TEXT_DOMAIN),
			'update_label' => __('Update Group', WPS2_TEXT_DOMAIN),
			'private_label' => __('Set group as private (join requests will need to be approved)', WPS2_TEXT_DOMAIN),
            'image_label' => __('Upload group image', WPS2_TEXT_DOMAIN),
            'image_remove_label' => __('Remove group image', WPS2_TEXT_DOMAIN),
		), $atts, 'wps_group_edit' ) );

		$form_html = '';
		$form_html .= '<div id="wps_group_edit_div">';
			
            $url = wps_curPageURL();
            $url = preg_replace("/[&?]group_action=edit&group_id=[0-9]+/","",$url);
            $url .= wps_query_mark($url).'group_id='.$_GET['group_id'];

            $form_html .= '<form enctype="multipart/form-data" ACTION="'.$url.'" METHOD="POST">';
            $form_html .= '<input type="hidden" name="action" value="wps_group_edit" />';
            $form_html .= '<input type="hidden" name="wps_group_id" value="'.$_GET['group_id'].'" />';

            $form_html .= '<div id="wps_group_create_title">'.$title_label.'</div>';
            $form_html .= '<input type="text" id="wps_group_edit_title" name="wps_group_edit_title" value="'.$the_post->post_title.'" />';

            $form_html .= '<div id="wps_group_content_label">'.$content_label.'</div>';
            $form_html .= '<textarea id="wps_group_edit_textarea" name="wps_group_edit_textarea">'.$the_post->post_content.'</textarea>';

            $form_html .= '<p><input type="checkbox" ';
                if (get_post_meta($_GET['group_id'], 'wps_group_privacy', true)) $form_html .= 'CHECKED ';
                $form_html .= 'name="wps_group_edit_private" /> ';
            $form_html .= $private_label.'</p>';

            $form_html .= '<div id="wps_group_image_upload">'.$image_label.'</div>';
                $form_html .= '<input title="'.$image_label.'" name="wps_group_image" size="50" type="file" /><br />';
                $form_html .= '<input type="checkbox" name="wps_group_image_remove" /> <span id="wps_group_image_remove_label">'.$image_remove_label.'</span>';
            $form_html .= '<br /><br />';

			$form_html .= '<input id="wps_group_edit_form_button" type="submit" class="wps_submit '.$class.'" value="'.$update_label.'" />';
			$form_html .= '</form>';
            
            $form_html .= '<br style="clear:both" /><a href="'.$url.'">'.$cancel_label.'</a>';
		
		$form_html .= '</div>';

		$html .= $form_html;

	else:

		$html .= __('Not the group owner', WPS2_TEXT_DOMAIN);

	endif;

	return $html;

}


function wps_save_group($post_data, $files_data) {

	global $current_user;
	
	$post_id = $post_data['wps_group_id'];
	if ($post_id):

		$current_post = get_post($post_id);
        if ( $current_user->ID == $current_post->post_author || current_user_can('manage_options') ):
    
		  	$my_post = array(
		      	'ID'           	=> $post_id,
		      	'post_title' 	=> $post_data['wps_group_edit_title'],
		      	'post_content' 	=> $post_data['wps_group_edit_textarea'],
		  	);
		  	wp_update_post( $my_post );		

			if (isset($_POST['wps_group_edit_private'])):
				update_post_meta($post_id, 'wps_group_privacy', $_POST['wps_group_edit_private']);
			else:
				delete_post_meta($post_id, 'wps_group_privacy');
			endif;		  		

			// Any further actions?
			do_action( 'wps_save_group_hook', $post_data, $files_data, $post_id );

		endif;

	endif;

}


?>
