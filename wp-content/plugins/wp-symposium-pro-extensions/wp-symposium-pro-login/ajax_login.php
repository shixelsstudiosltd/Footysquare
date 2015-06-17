<?php
// AJAX functions for mail
add_action( 'wp_ajax_nopriv_wps_login_check', 'wps_login_check' ); 
add_action( 'wp_ajax_wps_login_check', 'wps_login_check' ); 
add_action( 'wp_ajax_wps_register_check', 'wps_register_check' ); 
add_action( 'wp_ajax_nopriv_wps_register_check', 'wps_register_check' ); 
add_action( 'wp_ajax_wps_registration_get_meta', 'wps_registration_get_meta' ); 
add_action( 'wp_ajax_nopriv_wps_registration_get_meta', 'wps_registration_get_meta' ); 

/* POPULATE REGISTRATION META */
function wps_registration_get_meta() {

    $term = isset($_POST['term']) ? $_POST['term'] : '';
    
	$return_arr = array();
	$values = explode(',', get_post_meta($_POST['id'], 'wps_extension_default', true));
	if ($values):
		foreach($values as $value):
            if (!$term || (strpos(strtolower($value), strtolower($term)) !== false)):
                $row_array['value'] = $value;
                $row_array['label'] = $value;
                array_push($return_arr,$row_array);
            endif;
		endforeach;
	endif;

	echo json_encode($return_arr);	
	exit;

}

/* REGISTER (AND CHECK) */
function wps_register_check() {

	$err = '';
	session_start();
    $nickname = isset($_POST['nickname']) ? $_POST['nickname'] : $_POST['username'];
    $display_name = isset($_POST['display_name']) ? $_POST['display_name'] : $_POST['username'];
    global $wpdb;

	if (username_exists($_POST['username'])) $err = __('Username exists, please try another one', WPS2_TEXT_DOMAIN);
    if (!validate_username($_POST['username'])) $err = __( 'Username is invalid because it uses illegal characters. Please enter a valid username.', WPS2_TEXT_DOMAIN);
    
	if (email_exists($_POST['email'])) $err = __('Email exists, please try another one', WPS2_TEXT_DOMAIN);
	if (!is_email($_POST['email'])) $err = __('Invalid email address, please try another one', WPS2_TEXT_DOMAIN);
    $sql = "SELECT user_nicename FROM ".$wpdb->base_prefix."users WHERE user_nicename = %s";
        $check_nicename = $wpdb->get_var($wpdb->prepare($sql, $nickname));
        if ($check_nicename) $err = __('Nickname already exists, please try another one', WPS2_TEXT_DOMAIN);
    $sql = "SELECT display_name FROM ".$wpdb->base_prefix."users WHERE display_name = %s";
        $check_display_name = $wpdb->get_var($wpdb->prepare($sql, $nickname));
        if ($check_display_name) $err = __('Username/display name already exists, please try another one', WPS2_TEXT_DOMAIN);
	if ($_POST['captcha'] && trim(strtolower($_POST['captcha'])) != $_SESSION['captcha']) $err = __('Please re-enter the code', WPS2_TEXT_DOMAIN);

    if (isset($_POST['password']))
        if ($_POST['password'] != $_POST['password_confirm']) $err = __('Passwords do not match, please re-enter', WPS2_TEXT_DOMAIN);
    
	if (!$err):

		// Generate the password (or use one passed) and create the user
        $password = isset($_POST['password']) ? $_POST['password'] : wp_generate_password( 12, false );
		$user_id = wp_create_user( $_POST['username'], $password, $_POST['email'] );
        // Update user details
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
		// Add meta data
		wp_update_user(
			array(
			'ID'             =>    $user_id,
			'nickname'       =>    $nickname,
			'display_name'   =>    $display_name,
			'first_name'     =>    $first_name,
			'last_name'      =>    $last_name
			)
		);

		// Set the role
		$user = new WP_User( $user_id );
		$user->set_role( 'subscriber' );

		// Add profile extensions
		if (isset($_POST['wpspro_home']) && $_POST['wpspro_home'] != '') update_user_meta( $user_id, 'wpspro_home', $_POST['wpspro_home']);
		if (isset($_POST['wpspro_country']) && $_POST['wpspro_country'] != '') update_user_meta( $user_id, 'wpspro_country', $_POST['wpspro_country']);

        // Add admin-added profile extensions
        if (isset($_POST['exts'])):
            $extensions = $_POST['exts'];

            foreach ($extensions as $extension) {
                if ($extension['id'] && $extension['value'] && $extension['key']):
                    update_user_meta( $user_id, $extension['key'], $extension['value']);
                endif;
            }
        endif;
    
	  	// Email the user with password, whether supplied or not
        add_filter( 'wp_mail_content_type', 'wps_set_html_content_type' );
        $headers = 'From: '.get_bloginfo('admin_email').' <'.get_bloginfo('admin_email').'>' . "\r\n";
        $content = 'Your Password: ' . $password.'<br /><br />';
        $content .= home_url();
        $content = stripslashes(get_option('wps_alerts_customise_before')) . $content . stripslashes(get_option('wps_alerts_customise_after'));
        wp_mail($_POST['email'], home_url(), $content, $headers);
        remove_filter( 'wp_mail_content_type', 'wps_set_html_content_type' );

        // Log user in?
        if (isset($_POST['register_auto']) && $_POST['register_auto']):
            ob_start();
            wp_set_current_user($user_id, $_POST['username']);
            wp_set_auth_cookie($user_id);
            do_action('wp_login', $_POST['username']);
            ob_end_clean();
        endif;
    
        // If redirection url set, do not show message, go to url instead
        if (isset($_POST['registration_url']) && $_POST['registration_url'] !== ''):
            $err = 'URL'.$_POST['registration_url'];
        else:
            // Otherwise show sucess message
            $err = sprintf(__('OKThank you, your password has been sent to %s.', WPS2_TEXT_DOMAIN), $_POST['email']);
        endif;
    

	endif;

	echo $err;
    exit;

}

/* CHECK LOGIN */
function wps_login_check() {

	$err = '';

	$user = get_user_by('login', $_POST['username']);
	if (!$user) $user = get_user_by('email', $_POST['username']);
	if ($user) {
		$pw_ok = wp_check_password( $_POST['password'], $user->data->user_pass, $user->ID);
		if (!$pw_ok) $err .= __('Incorrect password.', WPS2_TEXT_DOMAIN).'<br />';
	} else {
		$err .= __('Username or email addres not found.', WPS2_TEXT_DOMAIN).'<br />';
	}

    if ($user):
        $closed_info = wps_is_account_closed($user->data->ID);
        if ($closed_info) $err .= __('This account has been closed.', WPS2_TEXT_DOMAIN).'<br />';
    endif;
    
	if (!$err):
		$creds = array();
		$creds['user_login'] = $user->data->user_login;
		$creds['user_password'] = $_POST['password'];
		$creds['remember'] = true;
		$user = wp_signon( $creds, false );
		if ( is_wp_error($user) )
			$err .= $user->get_error_message();
	endif;

	echo $err;
    exit;

}



?>