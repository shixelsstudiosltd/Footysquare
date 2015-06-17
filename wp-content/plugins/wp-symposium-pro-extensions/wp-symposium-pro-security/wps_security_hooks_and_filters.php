<?php
// Init
function wps_security_init_hook() {
        
	wp_enqueue_script('wps-security-js', plugins_url('wps_security.js', __FILE__), array('jquery'));	
	// Select2 replacement drop-down list from core
	wp_enqueue_script('wps-select2-js', plugins_url('../../wp-symposium-pro/js/select2.min.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-select2-css', plugins_url('../../wp-symposium-pro/js/select2.css', __FILE__), 'css');

}

// Enhance check for profile security
add_filter('wps_check_profile_security_filter', 'wps_check_profile_security', 5, 3);
function wps_check_profile_security($user_can_see, $the_user_id, $current_user_id) {

	$profile_security = get_user_meta($the_user_id, 'wpspro_profile_security', true);
    if (!$profile_security):
        $profile_security = get_option('wpspro_profile_security');
        $profile_security = $profile_security ? $profile_security : 'friends';
    endif;

	if ($the_user_id == $current_user_id || $profile_security == 'public' || current_user_can('manage_options')):
		$r = true;
    elseif ($profile_security == 'nobody'):
    	$r = false;
    elseif ($profile_security == 'friends'):
		$friends = wps_are_friends($the_user_id, $current_user_id);
		$r = ($friends['status'] == 'publish');
    elseif ($profile_security == 'members'):
    	global $current_user;
		$r = ($current_user->ID);
	else:
		$r = false;
	endif;

	// Any more changes?
	$r = apply_filters( 'wps_check_profile_security_extend_filter', $r, $profile_security, $the_user_id, $current_user_id );

	return $r;
}

// Enhance check for activity security
add_filter('wps_check_activity_security_filter', 'wps_check_activity_security', 5, 3);
function wps_check_activity_security($user_can_see, $the_user_id, $current_user_id) {

	$activity_security = get_user_meta($the_user_id, 'wpspro_activity_security', true);
    if (!$activity_security):
        $activity_security = get_option('wpspro_activity_security');
        $activity_security = $activity_security ? $activity_security : 'friends';
    endif;

	if ($the_user_id == $current_user_id || $activity_security == 'public' || current_user_can('manage_options')):
		$r = true;
    elseif ($activity_security == 'nobody'):
    	$r = false;
    elseif ($activity_security == 'friends'):
		$friends = wps_are_friends($the_user_id, $current_user_id);
		$r = ($friends['status'] == 'publish');
    elseif ($activity_security == 'members'):
    	global $current_user;
		$r = ($current_user->ID);
	else:
		$r = false;
	endif;

	// Any more changes?
	$r = apply_filters( 'wps_check_activity_security_extend_filter', $r, $activity_security, $the_user_id, $current_user_id );

	return $r;
}

// Enhance check for friends security
add_filter('wps_check_friends_security_filter', 'wps_check_friends_security', 5, 3);
function wps_check_friends_security($user_can_see, $the_user_id, $current_user_id) {

	$friends_security = get_user_meta($the_user_id, 'wpspro_friends_security', true);
    if (!$friends_security):
        $friends_security = get_option('wpspro_friends_security');
        $friends_security = $friends_security ? $friends_security : 'members';
    endif;

	if ($the_user_id == $current_user_id || $friends_security == 'public'):
		$r = true;
    elseif ($friends_security == 'nobody'):
    	$r = false;
    elseif ($friends_security == 'friends'):
		$friends = wps_are_friends($the_user_id, $current_user_id);
		$r = ($friends['status'] == 'publish');
    elseif ($friends_security == 'members'):
    	global $current_user;
		$r = ($current_user->ID);
	else:
		$r = false;
	endif;

	// Any more changes?
	$r = apply_filters( 'wps_check_friends_security_extend_filter', $r, $friends_security, $the_user_id, $current_user_id );

	return $r;
}

// Enhance check for directory
add_filter('wps_check_directory_security_filter', 'wps_check_diretory_security', 5, 3);
function wps_check_diretory_security($user_can_see, $the_user_id, $current_user_id) {

	$directory_security = get_user_meta($the_user_id, 'wpspro_directory_security', true);
    if (!$directory_security):
        $directory_security = get_option('wpspro_directory_security');
        $directory_security = $directory_security ? $directory_security : 'members';
    endif;

	if ($the_user_id == $current_user_id || $directory_security == 'public'):
		$r = true;
    elseif ($directory_security == 'nobody'):
    	$r = false;
    elseif ($directory_security == 'friends'):
		$friends = wps_are_friends($the_user_id, $current_user_id);
		$r = ($friends['status'] == 'publish');
    elseif ($directory_security == 'members'):
    	global $current_user;
		$r = ($current_user->ID);
	else:
		$r = false;
	endif;

	// Any more changes?
	$r = apply_filters( 'wps_check_directory_security_extend_filter', $r, $directory_security, $the_user_id, $current_user_id );

	return $r;
}

// Add profile security options to edit edit profile
add_filter('wps_usermeta_change_filter', 'wps_security_extend', 5, 3); // High priority
function wps_security_extend($form_html, $atts, $user_id) {

	// Init
	add_action('wp_footer', 'wps_security_init_hook');

	// Shortcode parameters
	extract( shortcode_atts( array(
		'profile_security' => __('Who can see your profile information?', WPS2_TEXT_DOMAIN),
		'friends_security' => __('Who can see your friendships?', WPS2_TEXT_DOMAIN),
		'directory_security' => __('Visibility in the directory?', WPS2_TEXT_DOMAIN),
		'activity_security' => __('Who can see your activity?', WPS2_TEXT_DOMAIN),
		'meta_class' => 'wps_usermeta_change_label',	
		'nobody' => __('Just me', WPS2_TEXT_DOMAIN),	
		'friends' => __('Friends', WPS2_TEXT_DOMAIN),	
		'members' => __('All Members', WPS2_TEXT_DOMAIN),	
		'public' => __('Public', WPS2_TEXT_DOMAIN),	
	), $atts, 'wps_usermeta_change' ) );

	// Meta and avatar
    if (!get_option('wpspro_profile_security_hide')):
    	$form_html .= '<div class="wps_usermeta_change_item">';

    		$form_html .= '<div class="'.$meta_class.'">'.$profile_security.'</div>';
            $form_html .= '<select name="wpspro_profile_security" id="wpspro_profile_security">';

            $profile_security = get_user_meta($user_id, 'wpspro_profile_security', true);
            if (!$profile_security):
            	$profile_security = get_option('wpspro_profile_security');
            	$profile_security = $profile_security ? $profile_security : 'friends';
            endif;

            $options = '<option value="nobody"';
            	if ($profile_security == 'nobody') $options .= ' SELECTED';
            	$options .= '>'.$nobody.'</option>';

            $options .= '<option value="friends"';
            	if ($profile_security == 'friends') $options .= ' SELECTED';
            	$options .= '>'.$friends.'</option>';

            $options .= '<option value="members"';
            	if ($profile_security == 'members') $options .= ' SELECTED';
            	$options .= '>'.$members.'</option>';

            $options .= '<option value="public"';
            	if ($profile_security == 'public') $options .= ' SELECTED';
            	$options .= '>'.$public.'</option>';

            $form_html .= $options;
            $form_html .= '</select>';

    	$form_html .= '</div>';
    endif;

	// Activity
    if (!get_option('wpspro_activity_security_hide')):
    	$form_html .= '<div class="wps_usermeta_change_item">';

    		$form_html .= '<div class="'.$meta_class.'">'.$activity_security.'</div>';
            $form_html .= '<select name="wpspro_activity_security" id="wpspro_activity_security">';

            $activity_security = get_user_meta($user_id, 'wpspro_activity_security', true);
            if (!$activity_security):
            	$activity_security = get_option('wpspro_activity_security');
            	$activity_security = $activity_security ? $activity_security : 'friends';
            endif;

            $options = '<option value="nobody"';
            	if ($activity_security == 'nobody') $options .= ' SELECTED';
            	$options .= '>'.$nobody.'</option>';

            $options .= '<option value="friends"';
            	if ($activity_security == 'friends') $options .= ' SELECTED';
            	$options .= '>'.$friends.'</option>';

            $options .= '<option value="members"';
            	if ($activity_security == 'members') $options .= ' SELECTED';
            	$options .= '>'.$members.'</option>';

            $options .= '<option value="public"';
            	if ($activity_security == 'public') $options .= ' SELECTED';
            	$options .= '>'.$public.'</option>';

            $form_html .= $options;
            $form_html .= '</select>';

    	$form_html .= '</div>';
    endif;

	// Friends
    if (!get_option('wpspro_friends_security_hide')):
    	$form_html .= '<div class="wps_usermeta_change_item">';

    		$form_html .= '<div class="'.$meta_class.'">'.$friends_security.'</div>';
            $form_html .= '<select name="wpspro_friends_security" id="wpspro_friends_security">';

            $friends_security = get_user_meta($user_id, 'wpspro_friends_security', true);
            if (!$friends_security):
            	$friends_security = get_option('wpspro_friends_security');
            	$friends_security = $friends_security ? $friends_security : 'members';
            endif;

            $options = '<option value="nobody"';
            	if ($friends_security == 'nobody') $options .= ' SELECTED';
            	$options .= '>'.$nobody.'</option>';

            $options .= '<option value="friends"';
            	if ($friends_security == 'friends') $options .= ' SELECTED';
            	$options .= '>'.$friends.'</option>';

            $options .= '<option value="members"';
            	if ($friends_security == 'members') $options .= ' SELECTED';
            	$options .= '>'.$members.'</option>';

            $options .= '<option value="public"';
            	if ($friends_security == 'public') $options .= ' SELECTED';
            	$options .= '>'.$public.'</option>';

            $form_html .= $options;
            $form_html .= '</select>';

    	$form_html .= '</div>';
    endif;

	// Directory
	if (function_exists('wps_admin_getting_started_directory') && !get_option('wpspro_directory_security_hide')):
    	$form_html .= '<div class="wps_usermeta_change_item">';

    		$form_html .= '<div class="'.$meta_class.'">'.$directory_security.'</div>';
            $form_html .= '<select name="wpspro_directory_security" id="wpspro_directory_security">';

            $directory_security = get_user_meta($user_id, 'wpspro_directory_security', true);
            if (!$directory_security):
            	$directory_security = get_option('wpspro_directory_security');
            	$directory_security = $directory_security ? $directory_security : 'members';
            endif;

            $options = '<option value="nobody"';
            	if ($directory_security == 'nobody') $options .= ' SELECTED';
            	$options .= '>'.$nobody.'</option>';

            $options .= '<option value="friends"';
            	if ($directory_security == 'friends') $options .= ' SELECTED';
            	$options .= '>'.$friends.'</option>';

            $options .= '<option value="members"';
            	if ($directory_security == 'members') $options .= ' SELECTED';
            	$options .= '>'.$members.'</option>';

            $options .= '<option value="public"';
            	if ($directory_security == 'public') $options .= ' SELECTED';
            	$options .= '>'.$public.'</option>';

            $form_html .= $options;
            $form_html .= '</select>';

    	$form_html .= '</div>';
	endif;

	return $form_html;

}

// Extend wps_usermeta_change save
add_action( 'wps_usermeta_change_hook', 'wps_security_extend_save', 10, 4 );
function wps_security_extend_save($user_id, $atts, $the_form, $the_files) {

	if (isset($the_form['wpspro_profile_security']))
		update_user_meta($user_id, 'wpspro_profile_security', $the_form['wpspro_profile_security']);

	if (isset($the_form['wpspro_activity_security']))
		update_user_meta($user_id, 'wpspro_activity_security', $the_form['wpspro_activity_security']);

	if (isset($the_form['wpspro_friends_security']))
		update_user_meta($user_id, 'wpspro_friends_security', $the_form['wpspro_friends_security']);

	if (isset($the_form['wpspro_directory_security']))
		update_user_meta($user_id, 'wpspro_directory_security', $the_form['wpspro_directory_security']);

}


?>