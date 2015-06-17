<?php
// Add Extended (meta) field below forum reply field
add_filter('wps_forum_comment_post_form_filter', 'wps_show_resave_forum_extensions', 10, 5); // Priority slightly higher, to go above attachments, for example
function wps_show_resave_forum_extensions($form_html, $atts, $current_user_id, $term, $post_id) {

	$the_post = get_post($post_id);
	$user_can_edit_forum = $the_post->post_author == $current_user_id ? true : false;
	$user_can_edit_forum = apply_filters( 'wps_forum_post_user_can_edit_filter', $user_can_edit_forum, $the_post, $current_user_id, $term->term_id );

	if ($user_can_edit_forum || current_user_can('manage_options')):

		// Init
		add_action('wp_footer', 'wps_forum_extended_search_init');	

		// Get applicable extensions for this forum
		$saved_extensions = wps_get_term_meta( $term->term_id, 'wps_forum_extensions' , true);

		if ($saved_extensions):

			// Add Extensions to page output
			$args = array (
				'post_type'              => 'wps_forum_extension',
				'posts_per_page'         => -1,
				'meta_key'				 => 'wps_forum_extension_order',
				'orderby'				 => 'wps_forum_extension_order',
				'suppress_filters'		 => TRUE,
				'order'					 => 'ASC',
			);

			$forum_extensions = get_posts( $args );

			if ($forum_extensions):
				foreach ($forum_extensions as $extension):

					if (in_array($extension->ID, $saved_extensions)):

						$item_html = '';

						if ( wps_using_permalinks() || is_multisite() ):    	
							$forum_slug = explode('/', get_post_permalink($extension->ID));
							$key = $forum_slug[count($forum_slug)-2];
				    	else:
							$forum_slug = explode('=', get_post_permalink($extension->ID));
							$key = $forum_slug[1];
						endif;

						if ($extension->wps_forum_extension_resave):

							$key = 'wps_fe_'.$key;

							$item_html = '<div class="wps_forum_extension_item">';
								$label = $extension->post_title;
								$item_html .= '<div class="wps_forum_extension_label">'.$label.'</div>';
								if ($extension->post_content)
									$item_html .= '<div class="wps_forum_extension_description">'.$extension->post_content.'</div>';
								if ($extension->wps_forum_extension_type == 'text' || $extension->wps_forum_extension_type == 'url'):
									$item_html .= '<input class="wps_forum_extension_text" name="'.$key.'" type="text" value="'.get_post_meta($post_id, $key, true).'" />';
								endif;
								if ($extension->wps_forum_extension_type == 'textarea'):
									$item_html .= '<textarea class="wps_forum_extension_textarea" name="'.$key.'">'.get_post_meta($post_id, $key, true).'</textarea>';
								endif;
								if ($extension->wps_forum_extension_type == 'list'):
									$current = get_post_meta($post_id, $key, true);
									$selected = $current ? $current : '';
									$item_html .= '<input id="wps_forum_extension_'.$extension->ID.'_default" type="hidden" value="'.$selected.'" />';
									$item_html .= '<input type="text" id="wps_forum_extension_meta_'.$extension->ID.'" class="wps_forum_extension_list" rel="'.$extension->ID.'" name="'.$key.'" />';
								endif;
							$item_html .= '</div>';

							$form_html .= $item_html;

						endif;

					endif;

				endforeach;
			endif;

			wp_reset_query();

		endif;

	endif;

	return $form_html;

}

// Check for extended fields when saving comment
add_action( 'wps_forum_comment_add_hook', 'wps_show_resave_forum_extensions_save', 10, 4 );
function wps_show_resave_forum_extensions_save($the_comment, $the_files, $post_id, $comment_id) {

	$args = array (
		'post_type'              => 'wps_forum_extension',
		'posts_per_page'         => -1,
		'suppress_filters'		 => TRUE
	);

	$extensions = get_posts( $args );
	if ($extensions):
		foreach ($extensions as $extension):

			if ( wps_using_permalinks() || is_multisite() ):    	
				$slug = explode('/', get_post_permalink($extension->ID));
				$key = 'wps_fe_'.$slug[count($slug)-2];
	    	else:
				$slug = explode('=', get_post_permalink($extension->ID));
				$key = 'wps_fe_'.$slug[1];
			endif;

			if (isset($the_comment[$key]) && $the_comment[$key])
				update_post_meta($post_id, $key, $the_comment[$key]);

		endforeach;
	endif;

}

function wps_forum_extended_search_init() {
	// JS and CSS
	wp_enqueue_script('wps-forum-extended-js', plugins_url('wps_forum_extended.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-forum-extended-css', plugins_url('wps_forum_extended.css', __FILE__), 'css');
	wp_localize_script('wps-forum-extended-js', 'wps_forum_extended', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));    	

	// Select2 replacement drop-down list from core
	wp_enqueue_script('wps-select2-js', plugins_url('../../wp-symposium-pro/js/select2.min.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-select2-css', plugins_url('../../wp-symposium-pro/js/select2.css', __FILE__), 'css');
}

// Check for added extensions as columns when viewing a forum
add_filter('wps_forum_post_columns_header_filter', 'wps_forum_extensions_add_headers_to_forum', 10, 3); // Priority slightly higher, to go above attachments, for example
function wps_forum_extensions_add_headers_to_forum($forum_html, $term_id, $atts) {

	// Init
	add_action('wp_footer', 'wps_forum_extended_search_init');	

	// Get this forums extensions
	$saved_extensions = wps_get_term_meta( $term_id, 'wps_forum_extensions' , true);

	if ($saved_extensions):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'extensions' => '',
		), $atts, 'wps_forum_show_posts' ) );

		if ($extensions):

			$extensions_to_include = explode(',', $extensions);

			$args = array (
				'post_type'              => 'wps_forum_extension',
				'posts_per_page'         => -1,
				'meta_key'				 => 'wps_forum_extension_order',
				'orderby'				 => 'wps_forum_extension_order',
				'order'					 => 'ASC',
				'suppress_filters'		 => true,
			);

			$extensions = query_posts( $args );

			if ($extensions):

				foreach ($extensions as $extension):

					if (in_array($extension->ID, $saved_extensions)):

						if ( wps_using_permalinks() || is_multisite() ):    	
							$post_slug = explode('/', get_post_permalink($extension->ID));
							$key = $post_slug[count($post_slug)-2];
				    	else:
							$post_slug = explode('=', get_post_permalink($extension->ID));
							$key = $post_slug[1];
						endif;

						if (in_array($key, $extensions_to_include)):

							$key = 'wps_fe_'.$key;

							$forum_html .= '<div class="wps_forum_extension_header_col">';
								$forum_html .= $extension->post_title;
							$forum_html .= '</div>';

						endif;

					endif;

				endforeach;
			endif;

			wp_reset_query();

		endif;

	endif;

	return $forum_html;
}

// Check for added extensions as columns when viewing a forum
add_filter('wps_forum_post_columns_filter', 'wps_forum_extensions_add_to_forum', 10, 3); 
function wps_forum_extensions_add_to_forum($forum_html, $post_id, $atts) {

	// Get this forums extensions
	$saved_extensions = false;
	$post_terms = get_the_terms( $post_id, 'wps_forum' );
	foreach ($post_terms as $term):
		$saved_extensions = wps_get_term_meta( $term->term_id, 'wps_forum_extensions' , true);
	endforeach;

	if ($saved_extensions):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'extensions' => '',
		), $atts, 'wps_forum_show_posts' ) );

		if ($extensions):

			$extensions_to_include = explode(',', $extensions);

			$args = array (
				'post_type'              => 'wps_forum_extension',
				'posts_per_page'         => -1,
				'meta_key'				 => 'wps_forum_extension_order',
				'orderby'				 => 'wps_forum_extension_order',
				'order'					 => 'ASC',
				'suppress_filters'		 => true,
			);

			$extensions = query_posts( $args );

			if ($extensions):

				foreach ($extensions as $extension):

					if (in_array($extension->ID, $saved_extensions)):

						if ( wps_using_permalinks() || is_multisite() ):    	
							$post_slug = explode('/', get_post_permalink($extension->ID));
							$key = $post_slug[count($post_slug)-2];
				    	else:
							$post_slug = explode('=', get_post_permalink($extension->ID));
							$key = $post_slug[1];
						endif;

						if (in_array($key, $extensions_to_include)):

							$forum_html .= '<div class="wps_forum_extension_col">';

								$key = 'wps_fe_'.$key;

								if ($extension->wps_forum_extension_type == 'text' | $extension->wps_forum_extension_type == 'url'):
									$forum_html .= make_clickable(get_post_meta($post_id, $key, true));
								endif;
								if ($extension->wps_forum_extension_type == 'textarea'):
									$forum_html .= get_post_meta($post_id, $key, true);
								endif;
								if ($extension->wps_forum_extension_type == 'list'):
									$forum_html .= get_post_meta($post_id, $key, true);
								endif;

							$forum_html .= '</div>';

						endif;

					endif;

				endforeach;
			endif;

			wp_reset_query();

		endif;

	endif;

	return $forum_html;
}

// Add extensions to forum setup edit form
add_action('wps_forum_taxonomy_metadata_edit_hook', 'wps_forum_taxonomy_metadata_edit_extensions', 10, 1);
function wps_forum_taxonomy_metadata_edit_extensions($tag) {

	$args = array (
		'post_type'              => 'wps_forum_extension',
		'posts_per_page'         => -1,
		'meta_key'				 => 'wps_forum_extension_order',
		'orderby'				 => 'wps_forum_extension_order',
		'suppress_filters'		 => TRUE,
		'order'					 => 'ASC',
	);

	$forum_extensions = get_posts( $args );

	if ($forum_extensions):

		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<h2><?php _e('Extensions', WPS2_TEXT_DOMAIN); ?></h2>
			</th>
			<td></td>
		</tr> 
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="wps_forum_extensions"><?php _e('Extensions', WPS2_TEXT_DOMAIN); ?></label>
			</th>
			<td>
				<?php

				$saved_extensions = wps_get_term_meta( $tag->term_id, 'wps_forum_extensions' , true);

				foreach ( $forum_extensions as $extension ):
					echo '<input type="checkbox" id="wps_taxonomy_metadata_extensions_'.$extension->ID.'" style="width:10px" name="wps_taxonomy_metadata_extension[]" ';
					if ($saved_extensions && in_array($extension->ID, $saved_extensions)) echo 'CHECKED ';
					echo 'value="'.$extension->ID.'"> <label for="wps_taxonomy_metadata_extension_'.$extension->ID.'">'.$extension->post_title.' ('.$extension->post_name.')</label><br />';
				endforeach;
				?>
				<span class="description"><?php _e('Forum extensions that apply to this forum.', WPS2_TEXT_DOMAIN); ?></span>
			</td>
		</tr> 
		<?php
	endif;
}

add_action('wps_forum_taxonomy_metadata_edit_roles_save_hook', 'wps_forum_taxonomy_metadata_edit_extensions_save', 10, 2);
function wps_forum_taxonomy_metadata_edit_extensions_save($term_id, $the_post) {

	$extensions = array();
	if(!empty($_POST['wps_taxonomy_metadata_extension'])):
	    
	    foreach($_POST['wps_taxonomy_metadata_extension'] as $check):
			$extensions[] = $check;
	    endforeach;
	    wps_update_term_meta( $term_id, 'wps_forum_extensions', $extensions );

	else:

		wps_delete_term_meta( $term_id, 'wps_forum_extensions' );

	endif;

}


// Add Extended (meta) fields to wps_forum_post (adding post)
add_filter('wps_forum_post_pre_form_filter', 'wps_forum_extensions_forum_post', 9, 4); // Priority slightly higher, to go above attachments, for example
function wps_forum_extensions_forum_post($form_html, $atts, $user_id, $term) {

	// Init
	add_action('wp_footer', 'wps_forum_extended_search_init');	

	// Get applicable extensions for this forum
	$saved_extensions = wps_get_term_meta( $term->term_id, 'wps_forum_extensions' , true);

	if ($saved_extensions):

		// Add Extensions to page output
		$args = array (
			'post_type'              => 'wps_forum_extension',
			'posts_per_page'         => -1,
			'meta_key'				 => 'wps_forum_extension_order',
			'orderby'				 => 'wps_forum_extension_order',
			'suppress_filters'		 => TRUE,
			'order'					 => 'ASC',
		);

		$forum_extensions = get_posts( $args );

		if ($forum_extensions):
    
            $form_html .= '<div class="wps_forum_extension_items">';
			foreach ($forum_extensions as $extension):

				if (in_array($extension->ID, $saved_extensions)):

					$item_html = '';

					if ( wps_using_permalinks() || is_multisite()):    	
						$slug = explode('/', get_post_permalink($extension->ID));
						$key = 'wps_fe_'.$slug[count($slug)-2];
			    	else:
						$slug = explode('=', get_post_permalink($extension->ID));
						$key = 'wps_fe_'.$slug[1];
					endif;

					$required = get_post_meta($extension->ID, 'wps_forum_extension_required', true);
					$item_html .= '<div class="wps_forum_extension_item">';
						$label = $extension->post_title;
						$item_html .= '<div class="wps_forum_extension_label';
							if ($required) $item_html .= ' wps_mandatory';
							$item_html .= '">'.$label.'</div>';
						if ($extension->post_content)
							$item_html .= '<div class="wps_forum_extension_description">'.$extension->post_content.'</div>';
						if ($extension->wps_forum_extension_type == 'text' | $extension->wps_forum_extension_type == 'url'):
							$item_html .= '<input class="wps_forum_extension_text';
							if ($required) $item_html .= ' wps_mandatory_field';
							$item_html .= '" name="'.$key.'" type="text" value="" />';
						endif;
						if ($extension->wps_forum_extension_type == 'textarea'):
							$item_html .= '<textarea class="wps_forum_extension_textarea';
							if ($required) $item_html .= ' wps_mandatory_field';
							$item_html .= '" name="'.$key.'"></textarea>';
						endif;
						if ($extension->wps_forum_extension_type == 'list'):
							$item_html .= '<input type="text" id="wps_forum_extension_meta_'.$extension->ID.'" class="wps_forum_extension_list" rel="'.$extension->ID.'" name="'.$key.'" />';
						endif;
					$item_html .= '</div>';

					$form_html .= $item_html;

				endif;

			endforeach;
            $form_html .= '</div>';
		endif;

		wp_reset_query();

	endif;

	return $form_html;

}

// Hook into wps_forum_post_add_hook save
add_action( 'wps_forum_post_add_hook', 'wps_forum_extensions_forum_post_save', 10, 4 );
function wps_forum_extensions_forum_post_save($the_post, $the_files, $new_id) {

	global $current_user;

	// Double check logged in
	if (is_user_logged_in()):

		$args = array (
			'post_type'              => 'wps_forum_extension',
			'posts_per_page'         => -1,
			'suppress_filters'		 => TRUE
		);

		$extensions = get_posts( $args );
		if ($extensions):
			foreach ($extensions as $extension):

				if ( wps_using_permalinks() || is_multisite() ):    	
					$slug = explode('/', get_post_permalink($extension->ID));
					$key = 'wps_fe_'.$slug[count($slug)-2];
		    	else:
					$slug = explode('=', get_post_permalink($extension->ID));
					$key = 'wps_fe_'.$slug[1];
				endif;

				if (isset($the_post[$key])):
					update_post_meta($new_id, $key, $the_post[$key]);
				else:
					delete_post_meta($new_id, $key);
				endif;

			endforeach;
		endif;

	endif;

}

// Add extended fields after title when viewing a post
add_filter('wps_forum_post_post_title_filter', 'wps_forum_extensions_forum_show', 10, 4); 
function wps_forum_extensions_forum_show($html, $the_post, $atts, $current_user_id) {

	// Init
	add_action('wp_footer', 'wps_forum_extended_search_init');	

	// Get this forums extensions
	$saved_extensions = false;
	$post_terms = get_the_terms( $the_post->ID, 'wps_forum' );
	foreach ($post_terms as $term):
		$saved_extensions = wps_get_term_meta( $term->term_id, 'wps_forum_extensions' , true);
	endforeach;

	if ($saved_extensions):

		$args = array (
			'post_type'              => 'wps_forum_extension',
			'posts_per_page'         => -1,
			'meta_key'				 => 'wps_forum_extension_order',
			'orderby'				 => 'wps_forum_extension_order',
			'order'					 => 'ASC',
			'suppress_filters'		 => true,
		);

		$extensions = query_posts( $args );

		if ($extensions):

			foreach ($extensions as $extension):

				if (in_array($extension->ID, $saved_extensions)):

					if ( wps_using_permalinks() || is_multisite() ):    	
						$post_slug = explode('/', get_post_permalink($extension->ID));
						$key = $post_slug[count($post_slug)-2];
			    	else:
						$post_slug = explode('=', get_post_permalink($extension->ID));
						$key = $post_slug[1];
					endif;

					$key = 'wps_fe_'.$key;

                    $item_value = false;
					if ($extension->wps_forum_extension_type == 'text' || $extension->wps_forum_extension_type == 'textarea'):
						if ($value = get_post_meta($the_post->ID, $key, true))
							$item_value = '<div class="wps_forum_extension_value">'.wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($value))))).'</div>';
					endif;
					if ($extension->wps_forum_extension_type == 'list'):
						if ($value = get_post_meta($the_post->ID, $key, true))
							$item_value = '<div class="wps_forum_extension_value">'.wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($value))))).'</div>';
					endif;
					if ($extension->wps_forum_extension_type == 'url'):
						if ($value = get_post_meta($the_post->ID, $key, true))
							$item_value = '<div class="wps_forum_extension_value">'.wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($value))))).'</div>';
					endif;

					if ($item_value):
						$html .= '<div class="wps_forum_extension_label">'.$extension->post_title.'</div>';
						$html .= $item_value;
					endif;

				endif;

			endforeach;
		endif;

	endif;

	return $html;

}

// Add extensions when editing a post
add_filter('wps_forum_post_edit_pre_form_filter', 'wps_forum_extensions_forum_edit', 10, 4); 
function wps_forum_extensions_forum_edit($form_html, $atts, $current_user_id, $post_id) {

	// Init
	add_action('wp_footer', 'wps_forum_extended_search_init');	

	// Get this forums extensions
	$saved_extensions = false;
	$post_terms = get_the_terms( $post_id, 'wps_forum' );
	foreach ($post_terms as $term):
		$saved_extensions = wps_get_term_meta( $term->term_id, 'wps_forum_extensions' , true);
	endforeach;

	if ($saved_extensions):

		$args = array (
			'post_type'              => 'wps_forum_extension',
			'posts_per_page'         => -1,
			'meta_key'				 => 'wps_forum_extension_order',
			'orderby'				 => 'wps_forum_extension_order',
			'order'					 => 'ASC',
			'suppress_filters'		 => true,
		);

		$extensions = query_posts( $args );

		if ($extensions):

			foreach ($extensions as $extension):

				if (in_array($extension->ID, $saved_extensions)):

					if ( wps_using_permalinks() || is_multisite() ):    	
						$post_slug = explode('/', get_post_permalink($extension->ID));
						$key = $post_slug[count($post_slug)-2];
			    	else:
						$post_slug = explode('=', get_post_permalink($extension->ID));
						$key = $post_slug[1];
					endif;

					$key = 'wps_fe_'.$key;

					$item_html = '<div class="wps_forum_extension_item">';
						$label = $extension->post_title;
						$item_html .= '<div class="wps_forum_extension_label">'.$label.'</div>';
						if ($extension->post_content)
							$item_html .= '<div class="wps_forum_extension_description">'.$extension->post_content.'</div>';
						if ($extension->wps_forum_extension_type == 'text' | $extension->wps_forum_extension_type == 'url'):
							$item_html .= '<input class="wps_forum_extension_text" name="'.$key.'" type="text" value="'.get_post_meta($post_id, $key, true).'" />';
						endif;
						if ($extension->wps_forum_extension_type == 'textarea'):
							$item_html .= '<textarea class="wps_forum_extension_textarea" name="'.$key.'">'.get_post_meta($post_id, $key, true).'</textarea>';
						endif;
						if ($extension->wps_forum_extension_type == 'list'):
							$current = get_post_meta($post_id, $key, true);
							$selected = $current ? $current : '';
							$item_html .= '<input id="wps_forum_extension_'.$extension->ID.'_default" type="hidden" value="'.$selected.'" />';
							$item_html .= '<input type="text" id="wps_forum_extension_meta_'.$extension->ID.'" class="wps_forum_extension_list" rel="'.$extension->ID.'" name="'.$key.'" />';
						endif;
					$item_html .= '</div>';

					$form_html .= $item_html;

				endif;

			endforeach;
		endif;

		wp_reset_query();

	endif;

	return $form_html;

}


// Hook into wps_forum_post_edit_hook save
add_action( 'wps_forum_post_edit_hook', 'wps_forum_extensions_forum_post_edit_save', 10, 4 );
function wps_forum_extensions_forum_post_edit_save($the_post, $files_data, $post_id ) {

	$args = array (
		'post_type'              => 'wps_forum_extension',
		'posts_per_page'         => -1,
		'suppress_filters'		 => TRUE
	);

	$extensions = get_posts( $args );
	if ($extensions):
		foreach ($extensions as $extension):

			if ( wps_using_permalinks() || is_multisite() ):    	
				$slug = explode('/', get_post_permalink($extension->ID));
				$key = 'wps_fe_'.$slug[count($slug)-2];
	    	else:
				$slug = explode('=', get_post_permalink($extension->ID));
				$key = 'wps_fe_'.$slug[1];
			endif;

			if (isset($the_post[$key])):
				update_post_meta($post_id, $key, $the_post[$key]);
			else:
				delete_post_meta($post_id, $key);
			endif;

		endforeach;
	endif;


}

?>