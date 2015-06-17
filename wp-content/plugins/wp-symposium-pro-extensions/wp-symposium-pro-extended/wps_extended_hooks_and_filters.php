<?php

function wps_extended_search_init() {
	// JS and CSS
	wp_enqueue_script('wps-extended-js', plugins_url('wps_extended.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-extended-css', plugins_url('wps_extended.css', __FILE__), 'css');
	wp_localize_script('wps-extended-js', 'wps_usermeta', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));    	
	// Date/time picker
	wp_enqueue_script('wps-extended-datepicker-js', plugins_url('jquery.datetimepicker.js', __FILE__), array('jquery'));		
	wp_enqueue_style('wps-extended-datepicker-css', plugins_url('jquery.datetimepicker.css', __FILE__), 'css');
	// Select2 replacement drop-down list from core
	wp_enqueue_script('wps-select2-js', plugins_url('../../wp-symposium-pro/js/select2.min.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-select2-css', plugins_url('../../wp-symposium-pro/js/select2.css', __FILE__), 'css');
}


// Add filter to directory search
add_filter('wps_directory_search_form_filter', 'wps_extension_usermeta_extend_directory', 10, 3);
function wps_extension_usermeta_extend_directory($form_html, $atts, $the_post) {

	// Init
	add_action('wp_footer', 'wps_extended_search_init');	

	// Shortcode parameters
	extract( shortcode_atts( array(
		'include_meta' => 1,
		'include_meta_show' => 0,
		'include_meta_show_prompt' => __('Use advanced search', WPS2_TEXT_DOMAIN),
		'label_translations' => '',
		'value_translations' => '',

	), $atts, 'wps_directory_search' ) );

	// Over-ride include_meta_show (if showing results)?
	if (isset($_POST['wps_include_meta_show']) && $_POST['wps_include_meta_show']=='1') $include_meta_show = 1;

	$label_translations_array = str_replace(',', '&', $label_translations);
    parse_str($label_translations_array, $label_translations_array);

	$showed_label = false;

	if ($include_meta):

		// Loop through extensions
		$args = array (
			'post_type'              => 'wps_extension',
			'posts_per_page'         => -1,
			'meta_key'				 => 'wps_extension_order',
			'orderby'				 => 'meta_value_num',
			'order'					 => 'ASC',
			'suppress_filters'		 => true
		);

		$extensions = query_posts( $args );
		if ($extensions):

			foreach ($extensions as $extension):

				$id = $extension->ID;
                if (get_post_meta($id, 'wps_extension_include', true) && wps_can_see_extension($extension->post_name)):

					if (!$showed_label):
						$showed_label = true;

						if (!$include_meta_show):
							$form_html .= '<div id="wps_directory_extended_advanced_show_prompt">';
								$form_html .= $include_meta_show_prompt;
							$form_html .= '</div>';
						endif;

						$form_html .= '<div id="wps_directory_extended_advanced"';
							if (!$include_meta_show) $form_html .= ' style="display:none"';
							$form_html .='>';

						$form_html .= '<div class="wps_directory_meta_label">'.__('Display name', WPS2_TEXT_DOMAIN).'</div>';
							$value = isset($_POST['wps_directory_search_entry_text']) ? $_POST['wps_directory_search_entry_text'] : '';
							$form_html .= '<input type="text" class="wps_directory_meta_name" id="wps_directory_search_entry_text" name="wps_directory_search_entry_text" value="'.$value.'" />';

					endif;

					$label = $extension->post_title;
					if (isset($label_translations_array[$label])) $label = $label_translations_array[$label];

					$type = get_post_meta($id, 'wps_extension_type', true);

					$form_html .= '<div class="wps_directory_meta_label">'.$label.'</div>';

					$form_html .= '<div class="wps_directory_meta_value">';

						if ($type != 'list'):
							$text_value = isset($_POST['wps_directory_meta_'.$id]) ? $_POST['wps_directory_meta_'.$id] : '';
							$form_html .= '<input type="text" class="wps_directory_meta_text" id="wps_directory_meta_'.$id.'" rel="'.$id.'" name="wps_directory_meta_'.$id.'" value="'.$text_value.'" />';
						endif;

						if ($type == 'list'):
							$form_html .= '<input id="wps_directory_meta_'.$id.'_translations" type="hidden" value="'.$value_translations.'" />';
							$form_html .= '<input type="text" class="wps_directory_meta_list" id="wps_directory_meta_'.$id.'" rel="'.$id.'" name="wps_directory_meta_'.$id.'"/>';
						endif;

					$form_html .= '</div>';

				endif;

			endforeach;

		endif;

		wp_reset_query();

		if ($showed_label)
			$form_html .= '</div>';

	endif;


	return $form_html;

}

// Add to search filter to handle additional possible parameters
add_filter('wps_directory_get_users_sql_where_filter', 'wps_extension_usermeta_extend_directory_search_where', 10, 3);
function wps_extension_usermeta_extend_directory_search_where($where, $atts, $the_post) {
	foreach( $the_post as $key => $value ):
		if ($value):
			if (strpos($key, 'wps_directory_meta_') !== FALSE):
				$extension_id = str_replace('wps_directory_meta_', '', $key);
				$extension = get_post($extension_id);
				$where .= sprintf(" AND (m%d.meta_key = '%s' AND m%d.meta_value LIKE '%%%s%%')", $extension_id, 'wps_'.$extension->post_name, $extension_id, $value);
			endif;
		endif;
	endforeach;

	return $where;
}

add_filter('wps_directory_get_users_sql_filter', 'wps_extension_usermeta_extend_directory_search_join', 10, 3);
function wps_extension_usermeta_extend_directory_search_join($sql, $atts, $the_post) {
    global $wpdb;
	foreach( $the_post as $key => $value ):
		if ($value && strpos($key, 'wps_directory_meta_') !== FALSE):
			$extension_id = str_replace('wps_directory_meta_', '', $key);
			$sql .= sprintf(" LEFT JOIN ".$wpdb->prefix."usermeta m%d ON u.ID = m%d.user_id", $extension_id, $extension_id);
		endif;
	endforeach;

	return $sql;
}

// Filter value showing on profile via [wps-usermeta] in case of translations
add_filter('wps_usermeta_value_filter', 'wps_usermeta_value_filter_translate', 10, 3);
function wps_usermeta_value_filter_translate($value, $atts, $user_id) {

	// Shortcode options
	extract( shortcode_atts( array(
		'value_translations' 	 => '',
	), $atts, 'wps_usermeta' ) );

	$value_translations_array = str_replace(',', '&', $value_translations);
    parse_str($value_translations_array, $value_translations_array);

	if (isset($value_translations_array[str_replace(' ', '_', $value)])) $value = $value_translations_array[str_replace(' ', '_', $value)];

	return $value;

}

// Add Extended (meta) fields to wps_usermeta_change
add_filter('wps_usermeta_change_filter', 'wps_extension_usermeta_extend', 10, 3);
function wps_extension_usermeta_extend($form_html, $atts, $user_id) {

	// Init
	add_action('wp_footer', 'wps_extended_search_init');	

	// Shortcode options
	extract( shortcode_atts( array(
		'label_translations' 	 => '',
		'value_translations' 	 => '',
	), $atts, 'wps_usermeta_change' ) );

	$label_translations_array = str_replace(',', '&', $label_translations);
    parse_str($label_translations_array, $label_translations_array);

	$value_translations_array = str_replace(',', '&', $value_translations);
    parse_str($value_translations_array, $value_translations_array);

	// Add Extensions to page output
	$args = array (
		'post_type'              => 'wps_extension',
		'posts_per_page'         => -1,
		'meta_key'				 => 'wps_extension_order',
		'orderby'				 => 'meta_value_num',
		'suppress_filters'		 => TRUE,
		'order'					 => 'ASC',
	);

	$extensions = get_posts( $args );

	if ($extensions):
		foreach ($extensions as $extension):

			$item_html = '';

			if ( wps_using_permalinks() ):    	
				$slug = explode('/', get_post_permalink($extension->ID));
				$key = $slug[count($slug)-2];
	    	else:
	    		$permalink = get_post_permalink($extension->ID).'<br>';
	    		if (strpos($permalink, '=')):
					$slug = explode('=', get_post_permalink($extension->ID));
					$key = $slug[1];
				else:
					if (strpos($permalink, 'blog/')) $permalink = str_replace('blog/', '', $permalink);
					$slug = explode('/', $permalink);
					$key = $slug[count($slug)-2];
				endif;
			endif;

			$key = 'wps_'.$key;

			$item_html .= '<div class="wps_usermeta_change_item">';
			$label = $extension->post_title;
			if (isset($label_translations_array[$label])) $label = $label_translations_array[$label];
			$item_html .= '<div class="wps_usermeta_change_label">'.$label.'</div>';
			if ($extension->post_content)
				$item_html .= '<div class="wps_usermeta_description">'.$extension->post_content.'</div>';
			if ($extension->wps_extension_type == 'youtube' || $extension->wps_extension_type == 'text' || $extension->wps_extension_type == 'url'):
				$value = get_user_meta($user_id, $key, true) ? get_user_meta($user_id, $key, true) : '';
				$item_html .= '<input class="wps_extension_text" name="'.$key.'" type="text" value="'.$value.'" />';
			endif;
			if ($extension->wps_extension_type == 'date'):
				$value = get_user_meta($user_id, $key, true) ? get_user_meta($user_id, $key, true) : '';
				$item_html .= '<input class="wps_extension_date" name="'.$key.'" type="text" value="'.$value.'" />';
			endif;
			if ($extension->wps_extension_type == 'textarea'):
				$value = get_user_meta($user_id, $key, true) ? get_user_meta($user_id, $key, true) : '';
				$item_html .= '<textarea class="wps_extension_textarea" name="'.$key.'">'.$value.'</textarea>';
			endif;
			if ($extension->wps_extension_type == 'list'):
				$values = explode(',', get_post_meta($extension->ID, 'wps_extension_default', true));
				$current = get_user_meta($user_id, $key, true);
				$selected = $current ? $current : '';

				if (isset($value_translations_array[str_replace(' ', '_', $selected)])) $selected = $value_translations_array[str_replace(' ', '_', $selected)];
				$item_html .= '<input id="wps_directory_meta_'.$extension->ID.'_default" type="hidden" value="'.$selected.'" />';
				$item_html .= '<input id="wps_directory_meta_'.$extension->ID.'_translations" type="hidden" value="'.$value_translations.'" />';
				$item_html .= '<input type="text" id="wps_directory_meta_'.$extension->ID.'" class="wps_extension_list wps_directory_meta_list" rel="'.$extension->ID.'" name="'.$key.'" value="'.$selected.'" />';
			endif;
			if ($extension->wps_extension_type == 'image'):
				$image = get_user_meta($user_id, $key, true) ? get_user_meta($user_id, $key, true) : false;
				if ($image)
					$item_html .= '<img src="'.$image.'" style="max-width:100px;float: left; margin-right:10px;margin-bottom:15px;" />';
		        $item_html .= '<input name="'.$key.'[]" size="50" type="file" />';
				if ($image)
			        $item_html .= '<input type="checkbox" name="'.$key.'_remove"  /><span class="wps_usermeta_description">'.__('remove', WPS2_TEXT_DOMAIN).'</span>';
		        if ($extension->wps_extension_image_url):
					$image_url = get_user_meta($user_id, $key.'_url', true) ? get_user_meta($user_id, $key.'_url', true) : false;
					$item_html .= '<div class="wps_usermeta_description">'.__('Optional link for image:', WPS2_TEXT_DOMAIN).'</div>';
			        $item_html .= '<input class="wps_extension_text" style="width:50%" name="'.$key.'_url" type="text" value="'.$image_url.'" />';
			    endif;
			endif;
			$item_html .= '</div>';

			if (!get_post_meta($extension->ID, 'wps_extension_admin_only', true) || current_user_can('manage_options')):
				$form_html .= $item_html;
			else:
				$form_html .= '<div style="display:none">'.$item_html.'</div>';
			endif;

		endforeach;
	endif;

	wp_reset_query();

	return $form_html;

}

// Extend wps_usermeta_change save
add_action( 'wps_usermeta_change_hook', 'wps_extension_usermeta_extend_save', 10, 4 );
function wps_extension_usermeta_extend_save($user_id, $atts, $the_form, $the_files) {

	global $current_user;

	// Double check logged in
	if (is_user_logged_in()):

		$args = array (
			'post_type'              => 'wps_extension',
			'posts_per_page'         => -1,
			'suppress_filters'		 => TRUE
		);

		$extensions = get_posts( $args );
		if ($extensions):
			global $current_user;
			foreach ($extensions as $extension):

				if ( wps_using_permalinks() ):    
					$slug = explode('/', get_post_permalink($extension->ID));
					$key = $slug[count($slug)-2];
		    	else:
		    		$permalink = get_post_permalink($extension->ID).'<br>';
		    		if (strpos($permalink, '=')):
						$slug = explode('=', get_post_permalink($extension->ID));
						$key = $slug[1];
					else:
						if (strpos($permalink, 'blog/')) $permalink = str_replace('blog/', '', $permalink);
						$slug = explode('/', $permalink);
						$key = $slug[count($slug)-2];
					endif;
				endif;

				$key = 'wps_'.$key;
				$extension_key = $key;

				if ($extension->wps_extension_type == 'image'):

					// Check to remove?
					if (isset($the_form[$extension_key.'_remove'])):

	                    // Delete user meta
	                    delete_user_meta($user_id, $extension_key);
	                    delete_user_meta($user_id, $extension_key.'_file');
	                    delete_user_meta($user_id, $extension_key.'_url');

					else:

						// Update URL, in case it has changed
	                    if (isset($the_form[$extension_key.'_url']))
	                    	update_user_meta($user_id, $extension_key.'_url', $the_form[$extension_key.'_url']);

				        $files = $the_files[$key];

				    	if ($files):

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

					                    echo '<div class="wps_error">'.__('Image must be an image file type!', WPS2_TEXT_DOMAIN).'</div>';

					                else:

					                    // Update user meta
					                    update_user_meta($user_id, $extension_key, $the_file['url']);
					                    update_user_meta($user_id, $extension_key.'_file', $the_file['file']);

					                    if (isset($the_form[$extension_key.'_url']))
					                    	update_user_meta($user_id, $extension_key.'_url', $the_form[$extension_key.'_url']);

					                    // Re-size for use to avoid large files
					                    if (!class_exists('SimpleImage')) require_once('SimpleImage.php');
					                    $image = new SimpleImage();
					                    $image->load( $the_file['file'] );
					                    $image->resizeToWidth(640);
					                    $image->save( $the_file['file'] );

					                endif;

					            endif;

					        endforeach; // for each file

					    endif;

					endif;

				else:

					if (isset($the_form[$key])):
						update_user_meta($user_id, $key, $the_form[$key]);
					else:
						delete_user_meta($user_id, $key);
					endif;

				endif;

			endforeach;
		endif;

	endif;

}

// Add Extended (meta) fields to wps_directory
add_filter('wps_directory_item_content_filter', 'wps_directory_add_item_content_filter', 10, 3);
function wps_directory_add_item_content_filter($item_html, $atts, $item) {

	// Shortcode parameters
	extract( shortcode_atts( array(
		'profile_extensions' => '',
		'profile_extensions_layout' => '',
		'profile_extensions_labels' => '',
	), $atts, 'wps_directory' ) );

	if ($profile_extensions):    
		$exts = explode(',', $profile_extensions);
		$layouts = explode(',', $profile_extensions_layout);
		$labels = explode(',', $profile_extensions_labels);
		$item_html .= '<div class="wps_directory_item_profile_extensions">';
		$l = 0;
		foreach ($exts as $extension):
			$l++;
            $layout = count($layouts) >= $l ? ' style="margin-right: 6px;float:'.$layouts[$l-1].'"' : '';
            $prefix = count($labels) >= $l ? $labels[$l-1] : 1;
            $item_html .= '<div class="wps_directory_item_profile_extension"'.$layout.'>'.wps_extended(array('user_id' => $item['ID'], 'slug' => $extension, 'show_if_empty' => 0, 'label_prefix' => $prefix)).'</div>';
		endforeach;
		$item_html .= '</div>';
	endif;

	return $item_html;

}

?>