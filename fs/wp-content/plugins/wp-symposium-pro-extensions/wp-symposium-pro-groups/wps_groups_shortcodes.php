<?php

																	/* **** */
																	/* INIT */
																	/* **** */


function wps_group_init() {

	$permalink = get_permalink(get_option('wpspro_group_page'));
	$group_url = $permalink.wps_query_mark($permalink);

	// JS and CSS
	wp_enqueue_script('wps-groups-js', plugins_url('wps_groups.js', __FILE__), array('jquery'));	
	wp_localize_script('wps-groups-js', 'wpspro_groups', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ), 
		'plugins_url' => plugins_url( '', __FILE__ ), 
		'profile_page_url' => get_page_link(get_option('wpspro_profile_page')),
		'group_page_url' => $group_url,
        'areyousure' => __('Are you sure? This cannot be undone!', WPS2_TEXT_DOMAIN),
	));	
	wp_enqueue_style('wps-groups-css', plugins_url('wps_groups.css', __FILE__), 'css');	
    
	// Anything else?
	do_action('wps_group_init_hook');

}


																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */
function wps_group_id($atts) {

	$html = '';

	if (isset($_GET['group_id'])):

		$html = $_GET['group_id'];

	endif;

	return $html;
}

function wps_group_url($atts) {

	$html = '';

	if (isset($_GET['group_id'])):

		$url = get_permalink(get_option('wpspro_group_page'));
		$html = $url.wps_query_mark($url).'group_id='.$_GET['group_id'];

	endif;

	return $html;
}

function wps_my_groups($atts) {
    
    global $wpdb,$current_user;
    $html = '';
    
    if (is_user_logged_in()):
    
        // Shortcode parameters
        extract( shortcode_atts( array(
            'header_text' => __('<h2>My groups</h2>', WPS2_TEXT_DOMAIN),
            'number' => 50,
            'link' => 1,
            'show_date' => 1,
            'date_label' => __('Last active %s ago', WPS2_TEXT_DOMAIN),
            'width' => '64', // set 0 to to hide
            'order' => 'DESC',
            'orderby' => 'active', // title, created, active (default)
            'before' => '',
            'after' => '',
        ), $atts, 'wps_my_groups' ) );
    
        $sql = "SELECT p.ID, m2.meta_value as group_id FROM ".$wpdb->prefix."posts p
                LEFT JOIN ".$wpdb->prefix."postmeta m1 ON p.ID = m1.post_id 
                LEFT JOIN ".$wpdb->prefix."postmeta m2 ON p.ID = m2.post_id
                WHERE post_type = 'wps_group_members' AND post_status = 'publish'
                  AND m1.meta_key = 'wps_member' AND m1.meta_value = %d";
        $memberships = $wpdb->get_results($wpdb->prepare($sql, $current_user->ID));
        if ($memberships):
    
            $group_ids = array();
            foreach ($memberships as $membership):
                $group_ids[] = $membership->group_id;
            endforeach;

            $args=array(
                'post__in' => $group_ids,
                'post_type' => 'wps_group',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'meta_key' => 'wps_group_updated',
                'orderby' => 'meta_value',
                'order' => $order,
            );

            $groups = get_posts( $args );	

            $groups_list = '';
            $groups_list = apply_filters( 'wps_my_groups_pre_filter', $groups_list, $atts );
    
            $html = $header_text ? $header_text : '';

            foreach ($groups as $item):

                $shown_count = 0;

                $item_html = '';

                $item_html .= '<div class="wps_group_item" id="wps_group_'.$item->ID.'" style="padding-left: '.($width+20).'px">';

                    // Group image
                    $item_html .= '<div class="wps_group_item_avatar" style="margin-left: -'.($width+20).'px">';
                    if ($width):
                        $attachments = get_posts( array(
                            'post_type' => 'attachment',
                            'posts_per_page' => 1,
                            'post_parent' => $item->ID,
                        ) );

                        if ( $attachments ):
                            $attachment = $attachments[0];
                            if ($attachment) {
                                $url = wp_get_attachment_image_src($attachment->ID, $width, false);
                                $item_html .= '<div id="wps_groups_image" style="width:'.$width.'px"><img style="width:'.$width.'px" src="'.$url[0].'" /></div>';            
                            }
                        endif;

                    endif;
                    $item_html .= '</div>';

                    $item_html .= '<div class="wps_groups_meta">';
                        // Group
                        $item_html .= '<div class="wps_group_item_display_name">';
                            $item_html .= wps_get_group_name($item->ID);
                        $item_html .= '</div>';

                        // Last active
                        if ($show_date):
                            $group_updated = get_post_meta($item->ID, 'wps_group_updated', true);
                            $item_html .= '<div class="wps_group_item_last_active">';
                            if ($group_updated && $group_updated != 1) $item_html .= sprintf($date_label, human_time_diff(strtotime($group_updated), current_time('timestamp', 1)));
                            $item_html .= '</div>';
                        endif;

                        // Filter for handling anything else
                        $item_html = apply_filters( 'wps_my_group_item_filter', $item_html, $atts, $item->ID );
                    $item_html .= '</div>';

                $item_html .= '</div>';

                $groups_list .= $item_html;

                $shown_count++;
                if ($shown_count == $number) break;

            endforeach;

            $groups_list = apply_filters( 'wps_my_groups_post_filter', $groups_list, $atts );

            $html .= $groups_list;

        endif;
    
    endif;

    if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_groups($atts) {

	// Init
	add_action('wp_footer', 'wps_group_init');

	global $current_user;
    
    $html = '';

	// Shortcode parameters
	extract( shortcode_atts( array(
		'header_text' => __('<h2>Groups</h2>', WPS2_TEXT_DOMAIN),
        'no_results_msg' => __('No groups found.', WPS2_TEXT_DOMAIN),
		'number' => 50,
		'link' => 1,
		'show_date' => 1,
		'date_label' => __('Last active %s ago', WPS2_TEXT_DOMAIN),
        'width' => '64', // set 0 to to hide
		'order' => 'DESC',
		'orderby' => 'active', // title, created, active (default)
		'before' => '',
		'after' => '',
	), $atts, 'wps_groups' ) );

	global $wpdb;

	switch($orderby)
	{
		case 'title':
			$_orderby = 'post_title';
		break;
		
		case 'created':
			$_orderby = 'ID';
		break;
		
		case 'active':
			$_orderby = 'active';
		break;
		
		default:
			$_orderby = 'active';
		break;
		
	}	

	if ($_orderby != 'active'):

		$args=array(
			'post_type' => 'wps_group',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'orderby' => $_orderby,
			'order' => $order,
		);

		$groups = get_posts( $args );	

	else:

		$sql = "SELECT DISTINCT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'wps_group_updated'";
		$groups = $wpdb->get_results($sql);

		$group_ids = array();
		if ($groups):
			foreach ($groups as $group):
				$group_ids[] = $group->post_id;
			endforeach;
		endif;

		$args=array(
			'post__in' => $group_ids,
			'post_type' => 'wps_group',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'meta_key' => 'wps_group_updated',
			'orderby' => 'meta_value',
			'order' => $order,
		);

		$groups = get_posts( $args );	

	endif;

	if ($groups):

		$groups_list = '';
        $groups_list = apply_filters( 'wps_groups_pre_filter', $groups_list, $atts );
    
        $html .= $header_text ? $header_text : '';

		foreach ($groups as $item):

			$shown_count = 0;

			$item_html = '';

			$item_html .= '<div class="wps_group_item" id="wps_group_'.$item->ID.'" style="padding-left: '.($width+20).'px">';

                // Group image
                $item_html .= '<div class="wps_group_item_avatar" style="margin-left: -'.($width+20).'px">';
                if ($width):
                    $attachments = get_posts( array(
                        'post_type' => 'attachment',
                        'posts_per_page' => 1,
                        'post_parent' => $item->ID,
                    ) );

                    if ( $attachments ):
                        $attachment = $attachments[0];
                        if ($attachment) {
                            $url = wp_get_attachment_image_src($attachment->ID, $width, false);
                            $item_html .= '<div id="wps_groups_image" style="width:'.$width.'px"><img style="width:'.$width.'px" src="'.$url[0].'" /></div>';            
                        }
                    endif;

                endif;
                $item_html .= '</div>';

                $item_html .= '<div class="wps_groups_meta">';
                    // Group
                    $item_html .= '<div class="wps_group_item_display_name">';
                        $item_html .= wps_get_group_name($item->ID);
                    $item_html .= '</div>';

                    // Last active
                    if ($show_date):
                        $group_updated = get_post_meta($item->ID, 'wps_group_updated', true);
                        $item_html .= '<div class="wps_group_item_last_active">';
                        if ($group_updated && $group_updated != 1) $item_html .= sprintf($date_label, human_time_diff(strtotime($group_updated), current_time('timestamp', 1)));
                        $item_html .= '</div>';
                    endif;

                    // Filter for handling anything else
                    $item_html = apply_filters( 'wps_group_item_filter', $item_html, $atts, $item->ID );
                $item_html .= '</div>';

			$item_html .= '</div>';

			$groups_list .= $item_html;

			$shown_count++;
			if ($shown_count == $number) break;

		endforeach;

		$groups_list = apply_filters( 'wps_groups_post_filter', $groups_list, $atts );

		$html .= $groups_list;

	else:

		$html .= $no_results_msg;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}


function wps_group_create($atts) {

	$html = '';

	// Init
	add_action('wp_footer', 'wps_group_init');

	global $current_user;

	if (is_user_logged_in()):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'show' => 1,
			'label' => __('Create Group', WPS2_TEXT_DOMAIN),
			'title_label' => __('Enter a group name', WPS2_TEXT_DOMAIN),
			'content_label' => __('Enter a brief description', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_group_create' ) );

		$form_html = '';			
		$form_html .= '<div id="wps_group_create_div">';
			
			$form_html .= '<div id="wps_group_create_form"';
				if (!$show) $form_html .= ' style="display:none;"';
				$form_html .= '>';

				$form_html .= '<form enctype="multipart/form-data" id="wps_group_create_form_theuploadform">';
				$form_html .= '<input type="hidden" name="action" value="wps_group_create_add" />';

				$form_html .= '<div id="wps_group_create_title_label">'.$title_label.'</div>';
				$form_html .= '<input type="text" id="wps_group_create_title" name="wps_group_create_title" />';

				$form_html .= '<div id="wps_group_create_content_label">'.$content_label.'</div>';
				$form_html .= '<textarea id="wps_group_create_textarea" name="wps_group_create_textarea"></textarea>';

			$form_html .= '</div>';

			$form_html .= '<input id="wps_group_create_form_button" type="submit" class="wps_submit '.$class.'" value="'.$label.'" />';
			$form_html .= '</form>';
		
		$form_html .= '</div>';

		$html .= $form_html;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_group_edit($atts) {

	$html = '';

	if (isset($_GET['group_id'])):

		// Init
		add_action('wp_footer', 'wps_group_init');

		// Shortcode parameters
		extract( shortcode_atts( array(
			'label' => __('Edit this group', WPS2_TEXT_DOMAIN),
			'updated' => __('Group details updated.', WPS2_TEXT_DOMAIN),
			'updated_ok' => __('Refresh page...', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_group_edit' ) );

		global $current_user;

		$group_admin = wps_group_administrator($_GET['group_id']);
		if (is_user_logged_in() && ($group_admin->ID == $current_user->ID || current_user_can('manage_options'))):

			require_once('wps_group_edit.php');

			if ( (isset($_GET['group_action']) && $_GET['group_action'] == 'edit') ):
				$html = wps_group_edit_form($_GET['group_id'], $atts);
			else:
				if ( ( isset($_POST['action']) && $_POST['action'] == 'wps_group_edit') ):
                    wps_save_group($_POST, $_FILES);
                    $html = '<div class="wps_group_edit_success wps_success">'.$updated.' <a href="">'.$updated_ok.'</a></div>';
                else:
                    $url = get_permalink(get_option('wpspro_group_page'));
                    $url = $url.wps_query_mark($url).'group_action=edit&group_id='.$_GET['group_id'];
                    $html .= '<a href="'.$url.'">'.$label.'</a>';
                endif;

				if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

			endif;

		endif;

	endif;

	return $html;
}

function wps_group_delete($atts) {

	$html = '';

	if (isset($_GET['group_id'])):

		// Init
		add_action('wp_footer', 'wps_group_init');

		global $current_user;

		$group_admin = wps_group_administrator($_GET['group_id']);
		if (is_user_logged_in() && ($group_admin->ID == $current_user->ID || current_user_can('manage_options')) && (!isset($_GET['group_action']) || $_GET['group_action'] != 'edit')):

			// Shortcode parameters
			extract( shortcode_atts( array(
				'label' => __('Delete this group', WPS2_TEXT_DOMAIN),
				'before' => '',
				'after' => '',
			), $atts, 'wps_group_delete' ) );

			$html .= '<a href="javascript:void(0)" rel="'.$_GET['group_id'].'" id="wps_group_delete">'.$label.'</a>';

		endif;

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	endif;

	return $html;
}

function wps_group_title($atts) {

	$html = '';

	if (isset($_GET['group_id'])):

		// Init
		add_action('wp_footer', 'wps_group_init');

		global $current_user;

		if (is_user_logged_in()):

			// Shortcode parameters
			extract( shortcode_atts( array(
				'before' => '',
				'after' => '',
			), $atts, 'wps_group' ) );

			$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : $group_id;
			$group = get_post($group_id);
			$html .= '<div id="wps_group_title">'.$group->post_title.'</div>';

		endif;

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	endif;

	return $html;
}

function wps_group_image($atts) {

	$html = '';

	if (isset($_GET['group_id'])):

		// Init
		add_action('wp_footer', 'wps_group_init');

		global $current_user;

		if (is_user_logged_in()):

            // Shortcode parameters
			extract( shortcode_atts( array(
                'width' => '200',
				'before' => '',
				'after' => '',
			), $atts, 'wps_group_image' ) );

            // Remove image?
            if (isset($_POST['wps_group_image_remove'])):
                $attachments = get_posts( array(
                    'post_type' => 'attachment',
                    'posts_per_page' => -1,
                    'post_parent' => $_GET['group_id'],
                ) );
                if ( $attachments ) {
                    foreach ( $attachments as $attachment ) {
                        wp_delete_attachment($attachment->ID, true);
                    }
                }    
            endif;   
    
            // Upload new image?
            if ($_FILES):
    
                $files = $_FILES['wps_group_image'];

                if ($files && $files['name']):

                    // Remove any previous group images
                    $attachments = get_posts( array(
                        'post_type' => 'attachment',
                        'posts_per_page' => -1,
                        'post_parent' => $_GET['group_id'],
                    ) );
                    if ( $attachments ) {
                        foreach ( $attachments as $attachment ) {
                            wp_delete_attachment($attachment->ID, true);
                        }

                    }    

                    // and proceed with upload
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
                        $image->resizeToWidth(800);
                        $image->save( $the_file['file'] );

                        // $filename should be the path to a file in the upload directory.
                        $filename = $the_file['file'];

                        // Get the path to the upload directory.
                        $wp_upload_dir = wp_upload_dir();
                        // Win32 fix:
                        $filename = str_replace( strtolower(str_replace('\\', '/', $wp_upload_dir['basedir'])), $wp_upload_dir['basedir'], $filename);

                        // The ID of the post this attachment is for.
                        $parent_post_id = $_GET['group_id'];

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
                            set_post_thumbnail( $_GET['group_id'], $attach_id );
                        endif;

                    endif;

                endif;
    
            endif; 

            // Show group image (if it has one)
            $attachments = get_posts( array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'post_parent' => $_GET['group_id'],
            ) );

            if ( $attachments ) {
                foreach ( $attachments as $attachment ) {
                    $url = wp_get_attachment_image_src($attachment->ID, $width, false);
                    $html .= '<img id="wps_group_image" style="width:'.$width.'px;" src="'.$url[0].'" />';
                }
            }

		endif;

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	endif;

	return $html;
}

function wps_group_admin($atts) {

	$html = '';

	if (isset($_GET['group_id'])):

		// Init
		add_action('wp_footer', 'wps_group_init');

		global $current_user;

		if (is_user_logged_in()):

			// Shortcode parameters
			extract( shortcode_atts( array(
				'link' => 1,
				'before' => '',
				'after' => '',
			), $atts, 'wps_group' ) );

			$group_admin = wps_group_administrator($_GET['group_id']);
			$html .= wps_display_name(array('user_id'=>$group_admin->ID, 'link'=>$link));

		endif;

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	endif;

	return $html;
}

function wps_group_description($atts) {

	// Init
	add_action('wp_footer', 'wps_group_init');

	$html = '';
	global $current_user;

	if (is_user_logged_in()):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'before' => '',
			'after' => '',
		), $atts, 'wps_group' ) );

		if (isset($_GET['group_id']) && !isset($_GET['group_action'])):

			$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : $group_id;
			$group = get_post($group_id);
			$html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($group->post_content)))));

		endif;

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);
    
        $html = '<div id="wps_group_description">'.$html.'</div>';

	endif;

	return $html;
}

function wps_group_post($atts) {

	$html = '';

	if (isset($_GET['group_id']) && !isset($_GET['group_action'])):

		// Init
		add_action('wp_footer', 'wps_group_init');

		// Shortcode parameters
		extract( shortcode_atts( array(
			'private_msg' => '',
			'before' => '<div style="clear:both">',
			'after' => '</div>',
		), $atts, 'wps_group_post' ) );

		global $current_user;
		$is_member = wps_is_group_member($current_user->ID, $_GET['group_id']);

		if ($is_member['status'] == 'publish'):
			// If activity plugin exists
			if (function_exists('wps_activity_post')):
				$html .= wps_activity_post(array());
			endif;
		else:
			$html .= $private_msg;
		endif;

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	endif;

	return $html;
}


function wps_group_activity($atts) {

	$html = '';

	if (isset($_GET['group_id']) && !isset($_GET['group_action'])):

		// Init
		add_action('wp_footer', 'wps_group_init');

		// Shortcode parameters
		extract( shortcode_atts( array(
			'private_msg' => __('You are not a member of this group.', WPS2_TEXT_DOMAIN),
            'back_to' => __('Back to %s...', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_group_post' ) );

	    global $current_user;

		$is_member = wps_is_group_member($current_user->ID, $_GET['group_id']);

		if ($is_member['status'] == 'publish'):

			// If activity plugin exists
			if (function_exists('wps_activity')):
				$html .= wps_activity(array('type'=>'group', 'back_to'=>$back_to));
			endif;

		else:

			$html .= '<div id="wps_activity_items">';
			$html .= $private_msg;
			$html .= '</div>';

		endif;

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

		// Update group updated timestamp to reflect active group
		update_post_meta( $_GET['group_id'], 'wps_group_updated', current_time('mysql', 1) );


	endif;

	return $html;
}

function wps_group_members($atts) {

	$html = '';

	if (isset($_GET['group_id']) && !isset($_GET['group_action'])) {

		// Init
		add_action('wp_footer', 'wps_group_init');

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'status' => 'all', // all, member, pending
			'private_msg' => '',
			'avatar_size' => 64,
			'show_date' => 1,
			'date_label' => __('Last active:', WPS2_TEXT_DOMAIN),
			'user_link' => 1,
			'date_format' => __('%s ago', WPS2_TEXT_DOMAIN),			
			'before' => '',
			'after' => '',
		), $atts, 'wps_group_members' ) );
		if ($status == 'member') $status = 'publish';

	    global $current_user;

		$is_member = wps_is_group_member($current_user->ID, $_GET['group_id']);

		if (is_user_logged_in() && $is_member['status'] == 'publish'):

			$members = wps_get_group_members($_GET['group_id'], $status);
			if ($members):

				$member_list = array();
				foreach($members as $member):
					$member_status = wps_is_group_member($member, $_GET['group_id']);
					array_push($member_list, 
						array (
						'ID' => $member,
						'last_active' => get_user_meta($member, 'wpspro_last_active', true),
						'status' => $member_status['status'],
						)
					);
				endforeach;

				// Sort by when last active
				$sort = array();
				foreach($member_list as $k=>$v) {
				    $sort['status'][$k] = $v['status'];
				    $sort['last_active'][$k] = $v['last_active'];
				}
				array_multisort($sort['status'], SORT_ASC, $sort['last_active'], SORT_DESC, $member_list);

				$user_list = '';
				$group_admin = wps_group_administrator($_GET['group_id']);
				foreach ($member_list as $member):

					if ($status == 'all' || ($status == 'publish' && $member['status'] == 'publish') || ($status == 'pending' && $member['status'] == 'pending') ):

						if (!($group_admin->ID != $current_user->ID && $member['status'] == 'pending')):

							$item_html = '';
							$item_html .= '<div class="wps_group_member_item" id="wps_group_member_'.$member['ID'].'" style="position:relative;padding-left: '.($avatar_size+10).'px">';

								$item_html .= '<div id="wps_group_member_'.$member['ID'].'_content" class="wps_group_member_content">';

								// Avatar
								$item_html .= '<div class="wps_group_member_item_avatar" style="margin-left: -'.($avatar_size+10).'px">';
									$item_html .= user_avatar_get_avatar($member['ID'], $avatar_size);
								$item_html .= '</div>';

								// User
								$item_html .= '<div class="wps_group_member_item_display_name">';
									$item_html .= wps_display_name(array('user_id'=>$member['ID'], 'link'=>$user_link));
									if ($group_admin->ID == $current_user->ID):

										if ($member['status'] == 'pending'):
											$item_html .= ' ('.__('Pending', WPS2_TEXT_DOMAIN).')';
											$item_html .= '<br /><input class="wps_group_join_accept" rel="'.$_GET['group_id'].'" data-member-id="'.$member['ID'].'" type="button" class="'.$class.' wps_submit" value="'.__('Accept', WPS2_TEXT_DOMAIN).'" />';
											$item_html .= ' <input class="wps_group_join_reject" rel="'.$_GET['group_id'].'" data-member-id="'.$member['ID'].'" type="button" class="'.$class.' wps_submit" value="'.__('Reject', WPS2_TEXT_DOMAIN).'" />';
										endif;
										if ($member['status'] == 'publish'):
											if ($member['ID'] != $current_user->ID) $item_html .= '<br /><input class="wps_group_join_kick" data-member-id="'.$member['ID'].'" rel="'.$_GET['group_id'].'" type="button" class="'.$class.' wps_submit" value="'.__('Remove', WPS2_TEXT_DOMAIN).'" />';
										endif;

									endif;
								$item_html .= '</div>';

								// Last active
								if ($show_date):
									$item_html .= '<div class="wps_group_member_item_last_active">';
									if ($member['last_active']):
										if ($date_label) $item_html .= $date_label.' ';
										$item_html .= sprintf($date_format, human_time_diff(strtotime($member['last_active']), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
									endif;
									$item_html .= '</div>';
								endif;

								$item_html .= '</div>';

							$item_html .= '</div>';

							$user_list .= $item_html;

						endif;

					endif;
				endforeach;

				$html .= $user_list;

			endif;

		else:

			$html .= $private_msg;

		endif;

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	}

	return $html;
}

function wps_group_join_button($atts) {

	$html = '';
    
	if (is_user_logged_in() && isset($_GET['group_id']) && !isset($_GET['group_action'])):

		// Init
		add_action('wp_footer', 'wps_group_init');

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'label_join' => __('Join this group', WPS2_TEXT_DOMAIN),
			'label_leave' => __('Leave this group', WPS2_TEXT_DOMAIN),
			'label_cancel' => __('Cancel request', WPS2_TEXT_DOMAIN),
			'text_pending' => __('Your request to join this group is pending.', WPS2_TEXT_DOMAIN),
			'text_private' => __('Your request will need to be approved.', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_group_join_button' ) );

	    global $current_user;

		$is_member = wps_is_group_member($current_user->ID, $_GET['group_id']);

		if (!$is_member['status']) {

				$html .= '<input id="wps_group_join" rel="'.$_GET['group_id'].'" type="button" class="'.$class.'" value="'.$label_join.'" />';
				if (get_post_meta($_GET['group_id'], 'wps_group_privacy', true)) 
					$html .= ' '.$text_private;

		} else {

			if ($is_member['status'] == 'pending'):

				$html .= $text_pending.'<br />';
				$html .= '<input id="wps_group_cancel" rel="'.$_GET['group_id'].'" type="button" class="'.$class.' wps_submit" value="'.$label_cancel.'" />';

			else:

				$group_admin = wps_group_administrator($_GET['group_id']);
				if ($group_admin->ID != $current_user->ID)
					$html .= '<input id="wps_group_leave" rel="'.$_GET['group_id'].'" type="button" class="'.$class.' wps_submit" value="'.$label_leave.'" />';

			endif;

		}

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	endif;

	return $html;
}

if (!is_admin()) {
    // For groups
	add_shortcode(WPS_PREFIX.'-group-create', 'wps_group_create');
	add_shortcode(WPS_PREFIX.'-groups', 'wps_groups');
    add_shortcode(WPS_PREFIX.'-my-groups', 'wps_my_groups');
    // For group
	add_shortcode(WPS_PREFIX.'-group-title', 'wps_group_title');
    add_shortcode(WPS_PREFIX.'-group-image', 'wps_group_image');
	add_shortcode(WPS_PREFIX.'-group-description', 'wps_group_description');
	add_shortcode(WPS_PREFIX.'-group-admin', 'wps_group_admin');
	add_shortcode(WPS_PREFIX.'-group-edit', 'wps_group_edit');
	add_shortcode(WPS_PREFIX.'-group-delete', 'wps_group_delete');
	add_shortcode(WPS_PREFIX.'-group-join-button', 'wps_group_join_button');
	add_shortcode(WPS_PREFIX.'-group-post', 'wps_group_post');
	add_shortcode(WPS_PREFIX.'-group-activity', 'wps_group_activity');
    // Other
	add_shortcode(WPS_PREFIX.'-group-members', 'wps_group_members');    
    add_shortcode(WPS_PREFIX.'-group-id', 'wps_group_id');
	add_shortcode(WPS_PREFIX.'-group-url', 'wps_group_url');
}

?>
