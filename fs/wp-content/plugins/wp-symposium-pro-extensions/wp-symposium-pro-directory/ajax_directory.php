<?php
// AJAX for directory
add_action( 'wp_ajax_nopriv_wps_get_directory_users', 'wps_get_directory_users_ajax' ); // Logged out
add_action( 'wp_ajax_wps_get_directory_users', 'wps_get_directory_users_ajax' ); // Logged in
function wps_get_directory_users_ajax() {

	global $wpdb;
	$term = isset($_POST['term']) ? $_POST['term'] : '';
	$show_user_login = isset($_POST['show_user_login']) ? $_POST['show_user_login'] : '';
	$sql = "SELECT ID, user_login, display_name FROM ".$wpdb->base_prefix."users WHERE user_login like '%%%s%%' OR display_name like '%%%s%%' ORDER BY display_name";
	$rows = $wpdb->get_results($wpdb->prepare($sql, $term, $term));

	$return_arr = array();
	foreach ($rows as $row) {
        if (!wps_is_account_closed($row->ID)):
            $row_array['value'] = $row->user_login;
            $label = $row->display_name;
            if ($show_user_login) $label .= ' ('.$row->user_login.')';
            $row_array['label'] = $label;
            array_push($return_arr,$row_array);
        endif;
	}
	echo json_encode($return_arr);	

	exit;

}

add_action( 'wp_ajax_nopriv_wps_get_directory_users_quick_select', 'wps_get_directory_users_quick_select_ajax' ); // Logged out
add_action( 'wp_ajax_wps_get_directory_users_quick_select', 'wps_get_directory_users_quick_select_ajax' ); // Logged in
function wps_get_directory_users_quick_select_ajax() {

	global $wpdb;

	if ( wps_using_permalinks() ):	
		$parameters = sprintf('%s', urlencode($_POST['user_login']));
		$permalink = get_permalink(get_option('wpspro_profile_page'));
		$url = $permalink.$parameters;
	else:
		$user = get_user_by('login', $_POST['user_login']);
		$parameters = sprintf('user_id=%d', $user->ID);
		$permalink = get_permalink(get_option('wpspro_profile_page'));
		$url = $permalink.'&'.$parameters;
	endif;

	echo $url;

	exit;

}




?>
