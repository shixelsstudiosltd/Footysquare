<?php
																	/* **** */
																	/* INIT */
																	/* **** */

function wps_directory_init() {
	// JS and CSS
	wp_enqueue_style('wps-directory-css', plugins_url('wps_directory.css', __FILE__), 'css');
	wp_enqueue_script('wps-directory-js', plugins_url('wps_directory.js', __FILE__), array('jquery'));	
	wp_localize_script('wps-directory-js', 'wps_directory_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));
	// Select2 replacement drop-down list from core
	wp_enqueue_script('wps-select2-js', plugins_url('../../wp-symposium-pro/js/select2.min.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-select2-css', plugins_url('../../wp-symposium-pro/js/select2.css', __FILE__), 'css');
	// Anything else?
	do_action('wps_directory_init_hook');

}
																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */
function wps_directory_search($atts) {

	// Init
	add_action('wp_footer', 'wps_directory_init');

	$html = '';

	// Shortcode parameters
	extract( shortcode_atts( array(
		'class' => '',
		'url' => '',
        'mode' => 'text', // text|list, will probably want to change placeholder to match mode
		'placeholder' => __('Enter part of a name...', WPS2_TEXT_DOMAIN), // if mode=>"text" requires HTML5
		'quick_select' => 0, // requires mode=>"list"
		'show_user_login' => 0,
		'search_label' => __('Member search', WPS2_TEXT_DOMAIN),
		'label' => __('Search', WPS2_TEXT_DOMAIN),
		'show_with_results' => 1,
		'private' => 0,
		'private_msg' => __('You need to login first.', WPS2_TEXT_DOMAIN),
		'include_meta_show' => 0,
		'before' => '',
		'after' => '',
	), $atts, 'wps_directory_search' ) );

	// Over-ride include_meta_show (if showing results)?
	if (isset($_POST['wps_include_meta_show']) && $_POST['wps_include_meta_show']=='1') $include_meta_show = 1;

	// Check if to show
	$show = true;
	if (!$show_with_results):

		$this_page = get_post(get_the_ID());
		if (strpos($this_page->post_content, '[wps-directory') !== FALSE) $show = false;
		wp_reset_query();

	endif;

	if ($show):

		if (!$private || is_user_logged_in()):

			$value = isset($_POST['wps_directory_search_entry']) ? stripslashes($_POST['wps_directory_search_entry']) : '';

			$form_html = '<div id="wps_directory_search">';

				$url = $url ? $url : '#';

				$form_html .= '<form ACTION="'.$url.'" METHOD="POST">';
				$form_html .= '<input type="hidden" id="wps_show_user_login" value="'.$show_user_login.'" />';
				$form_html .= '<input type="hidden" id="wps_include_meta_show" name="wps_include_meta_show" value="'.$include_meta_show.'" />';

				if (!$include_meta_show):
					$form_html .= '<div id="wps_directory_search_member">';
						if ($label && !$quick_select) $form_html .= '<div id="wps_directory_search_label">'.$search_label.'</div>';
                        if ($mode == 'text'):
						  $form_html .= '<input class="wps_directory_search_entry_field" name="wps_directory_search_entry" placeholder="'.$placeholder.'" value="'.$value.'" />';
                        else:
                            $form_html .= '<input type="hidden" class="wps_directory_search_entry" name="wps_directory_search_entry" data-quick-select="'.$quick_select.'" data-placeholder="'.$placeholder.'" value="'.$value.'" />';
                        endif;
					$form_html .= '</div>';
				endif;

				if (!$quick_select) $form_html = apply_filters( 'wps_directory_search_form_filter', $form_html, $atts, $_POST );

				$form_html .= '<input type="text" id="wps_directory_search_default" style="display:none" value="'.$value.'" />';
				if (!$quick_select):
					$form_html .= '<div style="clear:both;">';
					$form_html .= '<input type="submit" id="wps_directory_search_submit" name="wps_directory_search_submit" class="wps_submit '.$class.'" value="'.$label.'" />';
					$form_html .= '</div>';
				endif;

				$form_html .= '</form>';

			$form_html .= '</div>';

			$html .= $form_html;

		else:

			if (!$quick_select) $html .= $private_msg;

		endif;

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	endif;

	return $html;

}

function wps_directory ($atts) {

	// Init
	add_action('wp_footer', 'wps_directory_init');

	$html = '';

	global $current_user;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'order' => 'last_active', // any user field, such as last_active or user_registered
		'orderby' => 'DESC', 
		'no_results_msg' => __('No users found.', WPS2_TEXT_DOMAIN),
		'number' => 50,
		'limit' => '',
		'avatar_size' => 64,
		'link' => 1,
		'show_location' => 1,
		'show_date' => 1,
		'date_label' => __('Last active', WPS2_TEXT_DOMAIN),
		'last_active_format' => __('%s ago', WPS2_TEXT_DOMAIN),
		'show_by_default' => 1,
		'show_friendship_status' => 1,
		'show_registered' => 1,
		'registered_label' => __('Joined', WPS2_TEXT_DOMAIN),
		'friends_yes' => __('You are friends', WPS2_TEXT_DOMAIN),
		'friends_pending' => __('You have requested to be friends', WPS2_TEXT_DOMAIN),
		'friend_request' => __('You have a friends request', WPS2_TEXT_DOMAIN),
		'friends_no' => __('You are not friends', WPS2_TEXT_DOMAIN),
		'layout' => 'list', // list|fluid
		'include_friendship_action' => 1,
		'friendship_class' => '',
		'friend_add_label' => __('Make friends', WPS2_TEXT_DOMAIN),
		'friend_cancel_label' => __('Cancel friendship', WPS2_TEXT_DOMAIN),
		'friend_cancel_request_label' => __('Cancel friendship request', WPS2_TEXT_DOMAIN),		
		'accept_request_label' => __('Accept friendship request', WPS2_TEXT_DOMAIN),
		'reject_request_label' => __('Reject', WPS2_TEXT_DOMAIN),
		'include_self' => 0,
		'before' => '',
		'after' => '',
	), $atts, 'wps_directory' ) );

	$value = isset($_POST['wps_directory_search_entry']) ? $_POST['wps_directory_search_entry'] : '';
	// check or advanced search
	if (isset($_POST['wps_directory_search_entry_text']) && $_POST['wps_directory_search_entry_text'] != '') $value = $_POST['wps_directory_search_entry_text'];

	if ($show_by_default || $value):

		global $wpdb;

		// SELECT
		$get_users_sql = "SELECT u.ID, u.display_name, u.user_login, u.user_registered FROM ".$wpdb->base_prefix."users u ";
		$get_users_sql = apply_filters( 'wps_directory_get_users_sql_filter', $get_users_sql, $atts, $_POST );

		// WHERE
		$get_users_sql_where = "WHERE (u.user_login LIKE %s OR u.display_name LIKE %s)";
		$get_users_sql_where = $wpdb->prepare($get_users_sql_where, '%'.$value.'%', '%'.$value.'%');
		$get_users_sql_where = apply_filters( 'wps_directory_get_users_sql_where_filter', $get_users_sql_where, $atts, $_POST );

		// ORDER BY
		$get_users_sql_order = "ORDER BY %s ".$orderby;
		$get_users_sql_order = $wpdb->prepare($get_users_sql_order, $order);
		$get_users_sql_order = apply_filters( 'wps_directory_get_users_sql_order_filter', $get_users_sql_order, $atts, $_POST );

		// LIMIT
		$get_users_sql_limit = $limit ? "LIMIT 0, %d" : "";
		if ($limit) $get_users_sql_limit = $wpdb->prepare($get_users_sql_limit, $limit);
		$get_users_sql_limit = apply_filters( 'wps_directory_get_users_sql_limit_filter', $get_users_sql_limit, $atts, $_POST );

		// Build SQL
		$sql = $get_users_sql.' '.$get_users_sql_where.' '.$get_users_sql_order.' '.$get_users_sql_limit;

		// Get users
		$users = $wpdb->get_results($sql);
    
		if ($users):

			$items = array();

			foreach ($users as $u):

				$last_active = get_user_meta($u->ID, 'wpspro_last_active', true);
				if (!wps_is_account_closed($u->ID)):
    
                    // If multi-site, check if a member
                    if (is_multisite()):
                        $user_blogs = get_blogs_of_user( $u->ID  );
                        $include = false;
                        foreach ($user_blogs AS $user_blog) {
                            if (get_current_blog_id() == $user_blog->userblog_id) $include = true;
                        }
                    else:
                        $include = true;
                    endif;
    
                    if ($include):
    
                        if ($last_active):
                            array_push($items, array(
                                'ID' => $u->ID, 
                                'display_name' => $u->display_name, 
                                'user_login' => $u->user_login,
                                'last_active' => $last_active,
                                'user_registered' => $u->user_registered
                            ));
                        else:
                            array_push($items, array(
                                'ID' => $u->ID, 
                                'display_name' => $u->display_name, 
                                'user_login' => $u->user_login,
                                'last_active' => 0,
                                'user_registered' => $u->user_registered
                            ));
                        endif;
    
                    endif;
    
                endif;
    
			endforeach;
    
            if ($items):

                // First sort the array, so we can remove duplicates (for users not yet logged in/active)
                $sort = array();
                foreach($items as $k=>$v) {
                    $sort['ID'][$k] = $v['ID'];
                    $sort['last_active'][$k] = $v['last_active'];
                    $sort['user_registered'][$k] = $v['user_registered'];
                }
                array_multisort($sort['ID'], SORT_ASC, $sort['last_active'], SORT_DESC, $items);

                // Now go through a remove duplicate
                $previous_id = 0;
                $final_array = array();
                foreach ($items as $item):
                    if ($item['ID'] != $previous_id && ($item['ID'] != $current_user->ID || $include_self || $value)):
                        // Now check if permissions allow inclusion
                        $user_can_see_profile = true;
                        $user_can_see_profile = apply_filters( 'wps_check_directory_security_filter', $user_can_see_profile, $item['ID'], $current_user->ID );
                        if ($user_can_see_profile):
                            $previous_id = $item['ID'];
                            array_push($final_array, $item);
                        endif;
                    endif;
                endforeach;
                $items = $final_array;

                if ($items):
                    // Sort the users by $orderby/$order
                    $sort = array();
                    foreach($items as $k=>$v) {
                        $sort[$order][$k] = $v[$order];
                    }
                    $orderby = strtoupper($orderby);
                    $orderby = $orderby == "ASC" ? SORT_ASC : SORT_DESC;
                    array_multisort($sort[$order], $orderby, $items);
                endif;
    
			endif;

			$user_list = '';

			$shown_count = 0;

			foreach ($items as $item):

				$user_list = apply_filters( 'wps_directory_pre_filter', $user_list, $atts );

				$item_html = '';

				$item_html .= '<div class="wps_directory_item" id="wps_directory_'.$item['ID'].'" style="position:relative;padding-left: '.($avatar_size+10).'px;';
					if ($layout == 'fluid') $item_html .= 'min-width: 235px;float:left;"';
					$item_html .= '">';

					$item_html .= '<div id="wps_directory_'.$item['ID'].'_content" class="wps_directory_content" >';

						// Avatar
						$item_html .= '<div class="wps_directory_item_avatar" style="margin-left: -'.($avatar_size+10).'px">';
							$item_html .= user_avatar_get_avatar($item['ID'], $avatar_size);
						$item_html .= '</div>';

						// User
						$item_html .= '<div class="wps_directory_item_display_name">';
							$item_html .= wps_display_name(array('user_id'=>$item['ID'], 'link'=>$link));
						$item_html .= '</div>';

						// Registered
						if ($show_registered):
							$item_html .= '<div class="wps_directory_item_registered">';
								if ($item['user_registered']):
									if ($registered_label) $item_html .= $registered_label.' ';
									$item_html .= sprintf($last_active_format, human_time_diff(strtotime($item['user_registered']), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
								endif;
							$item_html .= '</div>';
						endif;

						// Last active
						if ($show_date):
							$item_html .= '<div class="wps_directory_item_last_active">';
								if ($item['last_active']):
									if ($date_label) $item_html .= $date_label.' ';
									$item_html .= sprintf($last_active_format, human_time_diff(strtotime($item['last_active']), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
								endif;
							$item_html .= '</div>';
						endif;

						// Location
						if ($show_location):
							$item_html .= '<div class="wps_directory_item_location">';
								$home = wps_usermeta( array('user_id'=> $item['ID'], 'meta'=>'wpspro_home'));
								$country = wps_usermeta( array('user_id'=> $item['ID'], 'meta'=>'wpspro_country'));
								if ($home && $home != '') $item_html .= $home;
								if ($home && $country) $item_html .= ', ';
								if ($country && $country != '') $item_html .= $country;
							$item_html .= '</div>';
						endif;

						// Filter to include extra entry info, such as profile extensions
						$item_html = apply_filters( 'wps_directory_item_content_filter', $item_html, $atts, $item );

						// Friendship status
						if ($show_friendship_status):
							$status = wps_friends_status(array('user_id' => $item['ID'], 'friends_yes' => $friends_yes, 'friends_pending' => $friends_pending, 'friend_request' => $friend_request, 'friends_no' => $friends_no));
							if ($status):
								$item_html .= '<div class="wps_directory_item_friends_status">';
									if ($include_friendship_action):
										$status = wps_are_friends($item['ID'], $current_user->ID);
										if ($status['status'] == 'pending' && $status['direction'] == 'to'):
											$item_html .= '<input type="submit" rel="'.$status['ID'].'" class="wps_submit wps_friends_accept '.$friendship_class.'" value="'.$accept_request_label.'" />';
											$item_html .= '<input type="submit" rel="'.$status['ID'].'" class="wps_submit wps_friends_reject '.$friendship_class.'" value="'.$reject_request_label.'" />';
										else:
											$item_html .= wps_friends_add_button(array('user_id' => $item['ID'], 'label' => $friend_add_label, 'cancel_label' => $friend_cancel_label, 'cancel_request_label' => $friend_cancel_request_label));
										endif;
									else:
										$item_html .= $status;
									endif;
								$item_html .= '</div>';
							endif;
						endif;

						// Filter for handling anything else
						// Passes $item_html, shortcodes options ($atts), current post ID ($item->ID), post title ($item->post_stitle), user page ($user_id), current users ID ($current_user->ID)
						$item_html = apply_filters( 'wps_directory_item_filter', $item_html, $atts, $item['ID'] );

					$item_html .= '</div>';

				$item_html .= '</div>';

				$user_list .= $item_html;

				$shown_count++;
				if ($shown_count == $number) break;

			endforeach;

			$user_list = apply_filters( 'wps_directory_post_filter', $user_list, $atts );

			if (!$user_list) $html .= $no_results_msg;
			$html .= $user_list;

		else:

			$html .= $no_results_msg;

		endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

if (!is_admin()) {
	add_shortcode(WPS_PREFIX.'-directory', 'wps_directory');
	add_shortcode(WPS_PREFIX.'-directory-search', 'wps_directory_search');
}


?>
