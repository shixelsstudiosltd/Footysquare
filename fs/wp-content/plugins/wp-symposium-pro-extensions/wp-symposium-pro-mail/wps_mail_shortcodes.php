<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_mail_init() {
	// JS and CSS
	wp_enqueue_script('wps-mail-js', plugins_url('wps_mail.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-mail-css', plugins_url('wps_mail.css', __FILE__), 'css');
	wp_localize_script( 'wps-mail-js', 'wps_mail_ajax', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	));		
	// Select2 replacement drop-down list from core
	wp_enqueue_script('wps-select2-js', plugins_url('../../wp-symposium-pro/js/select2.min.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-select2-css', plugins_url('../../wp-symposium-pro/js/select2.css', __FILE__), 'css');
	// Anything else?
	do_action('wps_mail_init_hook');
}

																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */
function wps_mail_to_user_post($atts) {

	// Init
	add_action('wp_footer', 'wps_mail_init');

	$html = '';
	global $current_user;

	if (is_user_logged_in()) {	

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'user_id' => 0,
			'friends_only' => 1,
			'label' => __('Message %s', WPS2_TEXT_DOMAIN),
			'popup_label' => __('Send', WPS2_TEXT_DOMAIN),
			'popup_cancel_label' => __('Cancel', WPS2_TEXT_DOMAIN),
			'popup' => 0, // 0 = go to mail page, 1 = handle as popup
			'before' => '',
			'after' => '',
		), $atts, 'wps_mail_to_user_post' ) );

		if (!$user_id) $user_id = wps_get_user_id();

		if ($user_id != $current_user->ID && !wps_is_account_closed($user_id)):

 			$is_friend = wps_are_friends($user_id, $current_user->ID);
			if (!$friends_only || $is_friend["status"] == 'publish'):

				if (strpos($label, '%s')):
					$user = get_user_by('id', $user_id);
					$label = str_replace('%s', $user->display_name, $label);
				endif;

				if (!$popup):

					$html .= '<div id="wps_mail_to_user_post_div">';

						$html .= '<form action="'.wps_get_mail_page().'" method="POST">';
						$html .= '<input type="hidden" name="recipient" value="'.$user_id.'" />';
						$html .= '<input id="wps_mail_to_user_post" type="submit" class="wps_submit '.$class.'" value="'.$label.'" />';
						$html .= '</form>';

					$html .= '</div>';

				else:

					$html .= '<input class="wps_mail_to_user_post_popup" rel="'.$user_id.'" type="submit" class="wps_submit '.$class.'" value="'.$label.'" />';

					$html .= '<div id="wps_mail_to_user_post_popup_div_'.$user_id.'" rel="'.$user_id.'" class="wps_mail_to_user_post_popup_div" style="display: none;">';
						$html .= '<div class="wps_mail_popup_recipient">'.$user->display_name.'</div>';
						$html .= '<textarea class="wps_mail_popup_message"></textarea>';
						$html .= '<input class="wps_mail_popup_cancel_button" rel="'.$user_id.'" type="submit" class="wps_submit '.$class.'" value="'.$popup_cancel_label.'" />';
						$html .= '<input class="wps_mail_popup_button" rel="'.$user_id.'" type="submit" class="wps_submit '.$class.'" value="'.$popup_label.'" />';
					$html .= '</div>';

				endif;

			endif;

		endif;

	}

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_mail_post($atts) {

	// Init
	add_action('wp_footer', 'wps_mail_init');

	$html = '';
	global $current_user;

	if (is_user_logged_in() && !isset($_GET['mail'])) {	

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'show' => 0,
			'recipients_label' => __('Recipient(s)', WPS2_TEXT_DOMAIN),
			'to_label' => __('To:', WPS2_TEXT_DOMAIN),
			'title_label' => __('Subject', WPS2_TEXT_DOMAIN),
			'content_label' => __('Private Message Content', WPS2_TEXT_DOMAIN),
			'label' => __('New Message', WPS2_TEXT_DOMAIN),
			'cancel_label' => __('Cancel', WPS2_TEXT_DOMAIN),
			'no_friends' => __('You can only send messages if you have friends.', WPS2_TEXT_DOMAIN),
			'select_recipient' => __('Select at least one recipient...', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_mail_post' ) );

		if (isset($_POST['recipient'])) $show=1;

		$user_id = $current_user->ID;

		$form_html = '';
		$form_html .= '<div id="wps_mail_post_div">';
			
			$form_html .= '<div id="wps_mail_post_form"';
				if (!$show) $form_html .= ' style="display:none;"';
				$form_html .= '>';

				$form_html .= '<form enctype="multipart/form-data" id="wps_mail_post_theuploadform">';

				$form_html .= '<input type="hidden" id="wps_mail_plugins_url" value="'.plugins_url( '', __FILE__ ).'" />';
				$form_html .= '<input type="hidden" name="action" value="wps_mail_post_add" />';
				$form_html .= '<input type="hidden" name="wps_mail_post_author" value="'.$current_user->ID.'" />';

				$form_html = apply_filters( 'wps_mail_post_pre_form_filter', $form_html, $atts, $current_user->ID );

                if (!get_option('wpspro_mail_all')):
                    $friends = wps_get_friends($current_user->ID);
                else:
                    // Set to allow messages to all
                    global $wpdb;
                    $sql = "SELECT * FROM ".$wpdb->base_prefix."users WHERE ID != %d";
                    $users = $wpdb->get_results($wpdb->prepare($sql, $current_user->ID));
                    $friends = array();
                    if ($users):
                        foreach ($users as $user):
                            array_push($friends, array('ID' => $user->ID));
                        endforeach;
                    endif;
                endif;
        
				$wps_default_mail_recipient = isset($_POST['recipient']) ? $_POST['recipient'] : false;

				if ($friends || $wps_default_mail_recipient):

					$form_html .= '<div id="wps_mail_post_select_recipients_label" style="display:none" class="wps_error">'.$select_recipient.'</div>';

					if (!$wps_default_mail_recipient):

						$form_html .= '<div id="wps_mail_post_recipients_label">'.$recipients_label.'</div>';
						$form_html .= '<select multiple="multiple" id="wps_mail_recipients" name="wps_mail_recipients[]" style="width:100%">';
						foreach ($friends as $friend):
							$user = get_user_by('id', $friend['ID']);
                            if ($user && !wps_is_account_closed($friend['ID'])):
                                $selected = (isset($_POST['recipient']) && $_POST['recipient'] == $user->ID) ? 'selected' : '';
                                $form_html .= sprintf('<option %s value="%s">%s</option>', $selected, $user->user_login, $user->display_name);
                            endif;
						endforeach;
						$form_html .= '</select>';
						$wps_default_mail_recipient_user = '';

					else:

						$wps_default_mail_recipient_user = get_user_by('id', $wps_default_mail_recipient);
						$form_html .= '<div id="wps_mail_post_recipients_label">'.sprintf('%s %s', $to_label, $wps_default_mail_recipient_user->display_name).'</div>';
						$wps_default_mail_recipient_user = $wps_default_mail_recipient_user->user_login;
						$form_html .= '<input type="hidden" id="wps_default_mail_recipient_user" name="wps_default_mail_recipient_user" value="'.$wps_default_mail_recipient_user.'" />';

					endif;

					$form_html .= '<div id="wps_mail_post_title_label">'.$title_label.'</div>';
					$form_html .= '<input type="text" id="wps_mail_title" name="wps_mail_title" />';

					$form_html .= '<div id="wps_mail_post_content_label">'.$content_label.'</div>';
					$form_html = apply_filters( 'wps_mail_textarea_pre_form_filter', $form_html, $atts, $current_user->ID );
					$form_html .= '<textarea id="wps_mail_textarea" name="wps_mail_textarea"></textarea>';

				else:
					$form_html .= '<p>'.$no_friends.'</p>';
				endif;

				$form_html = apply_filters( 'wps_mail_post_post_form_filter', $form_html, $atts, $current_user->ID );

				if (!$wps_default_mail_recipient) $form_html .= '<input id="wps_mail_post_close_button" type="submit" class="wps_submit '.$class.'" value="'.$cancel_label.'" />';

			$form_html .= '</div>';

			$form_html .= '<input id="wps_mail_post_button" type="submit" class="wps_submit '.$class.'" value="'.$label.'" />';
			$form_html .= '</form>';

		$form_html .= '</div>';

		$html .= $form_html;

	}

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_mail_comment($atts) {

	// Init
	add_action('wp_footer', 'wps_mail_init');

	$html = '';

	if (is_user_logged_in() && isset($_GET['mail']) ): // showing a single post

		global $current_user;
		$post_id = $_GET['mail'];

		// Recipients
		$recipients = get_post_meta( $post_id, 'wps_mail_recipients', true );
		if (!empty($recipients)):

			$recipients = array_unique($recipients);
    
            if (in_array($current_user->user_login, $recipients) || current_user_can('manage_options')):

                // Shortcode parameters
                extract( shortcode_atts( array(
                    'class' => '',
                    'show' => 1,
                    'content_label' => '',
                    'label' => __('Add Reply', WPS2_TEXT_DOMAIN),
                    'before' => '',
                    'after' => '',
                ), $atts, 'wps_forum_comment' ) );

                $form_html = '';

                $form_html .= '<div id="wps_mail_comment_div">';

                    $form_html .= '<div id="wps_mail_comment_form"';

                        if (!$show) $form_html .= ' style="display:none;"';
                        $form_html .= '>';

                        $form_html .= '<form id="wps_mail_comment_theuploadform">';
                        $form_html = apply_filters( 'wps_mail_comment_pre_form_filter', $form_html, $atts, $current_user->ID );

                        $form_html .= '<input type="hidden" id="wps_mail_plugins_url" value="'.plugins_url( '', __FILE__ ).'" />';
                        $form_html .= '<input type="hidden" name="action" value="wps_mail_comment_add" />';
                        $form_html .= '<input type="hidden" name="post_id" value="'.$_GET['mail'].'" />';

                        $form_html .= '<div id="wps_mail_comment_content_label">'.$content_label.'</div>';
                        $form_html .= '<textarea id="wps_mail_comment" name="wps_mail_comment"></textarea>';

                        $form_html = apply_filters( 'wps_mail_comment_post_form_filter', $form_html, $atts, $current_user->ID );

                    $form_html .= '</div>';

                    $form_html .= '<input id="wps_mail_comment_button" type="submit" class="wps_submit '.$class.'" value="'.$label.'" />';

                    $form_html .= '</form>';

                $form_html .= '</div>';

                $html .= $form_html;
    
            endif;
    
        endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_mail_recipients($atts) {

	// Init
	add_action('wp_footer', 'wps_mail_init');

	// Check for single post view
	if (isset($_GET['mail']) && is_user_logged_in()):

		$recipients_html = '';

		// Shortcode parameters
		extract( shortcode_atts( array(
			'size' => 96,
            'add_recipients' => __('Add recipient(s)', WPS2_TEXT_DOMAIN),
            'cancel_label' => __('Cancel', WPS2_TEXT_DOMAIN),
            'add_image_url' => plugins_url('images/add_recipient.png', __FILE__),
			'before' => '',
			'after' => '',
		), $atts, 'wps_mail' ) );

		global $current_user;
		$post_id = $_GET['mail'];

		// Recipients
		$recipients = get_post_meta( $post_id, 'wps_mail_recipients', true );
		if (!empty($recipients)):
    
            $the_message = get_post($post_id);

			$mail_item['recipients'] = array_unique($recipients);
    
            if (in_array($current_user->user_login, $mail_item['recipients']) || current_user_can('manage_options')):
                
                $started_div = false;
                foreach ($mail_item['recipients'] as $mail_item_recipient):
                    if (!$started_div):
                        $recipients_html .= '<div id="wps_mail_message_recipients">';
                        $started_div = true;
                    endif;
                    $recipient = get_user_by('login', $mail_item_recipient);
                    if ($recipient):
                        $recipients_html .= '<div class="wps_mail_message_recipient" style="position:relative;width: '.($size).'px;">';
                        $recipients_html .= user_avatar_get_avatar( $recipient->ID, $size );
                        $recipients_html .= wps_display_name(array('user_id'=>$recipient->ID, 'link'=>1));
                        if (
                            (current_user_can('manage_options') && $the_message->post_author != $recipient->ID) || 
                            ($the_message->post_author == $current_user->ID && $the_message->post_author != $recipient->ID) || 
                            ($current_user->ID == $recipient->ID && $the_message->post_author != $recipient->ID)
                        ) $recipients_html .= '<img title="'.__('Delete recipient', WPS2_TEXT_DOMAIN).'" class="wps_mail_delete_recipient" data-post-id="'.$post_id.'" rel="'.$mail_item_recipient.'" src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                        $recipients_html .= '</div>';
                    endif;
                endforeach;
                if ($add_recipients):
                    $recipients_html .= '<div id="wps_add_recipient" class="wps_mail_message_recipient">';
                    $recipients_html .= '<img style="width: '.($size).'px;" src="'.$add_image_url.'" title="'.$add_recipients.'" />';
                    $recipients_html .= '</div>';
                endif;

                if ($add_recipients):
                    $friends = wps_get_friends($current_user->ID);
                    $recipients_html .= '<div id="wps_mail_new_recipients_div">';
                    $recipients_html .= '<select multiple="multiple" id="wps_mail_new_recipients" class="wps_mail_new_recipient" name="wps_mail_recipients[]" style="width:100%">';
                    foreach ($friends as $friend):
                        $user = get_user_by('id', $friend['ID']);
                        if ($user && !in_array($user->user_login, $recipients) && !wps_is_account_closed($friend['ID'])):
                            $selected = (isset($_POST['recipient']) && $_POST['recipient'] == $user->ID) ? 'selected' : '';
                            $recipients_html .= sprintf('<option %s value="%s">%s</option>', $selected, $user->user_login, $user->display_name);
                        endif;
                    endforeach;
                    $recipients_html .= '</select><br />';
                    $recipients_html .= '<input type="submit" rel="'.$post_id.'" id="wps_mail_new_recipients_submit" ="wps_submit" value="'.$add_recipients.'" />';
                    $recipients_html .= '<input type="submit" rel="'.$post_id.'" id="wps_mail_new_recipients_cancel" ="wps_submit" value="'.$cancel_label.'" />';
                    $recipients_html .= '</div>';
                endif;
    
                if ($started_div):
                    $recipients_html .= '</div>';
                endif;
                
            endif;

		endif;

		if ($recipients_html) $recipients_html = htmlspecialchars_decode($before).$recipients_html.htmlspecialchars_decode($after);

		return $recipients_html;

	endif;

}

function wps_mail_backto($atts) {

	// Init
	add_action('wp_footer', 'wps_mail_init');

	$html = '';

	if ( isset($_GET['mail']) ): // showing a single post

		// Shortcode parameters
		extract( shortcode_atts( array(
			'label' => __('Back to messages...', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_mail_backto' ) );

		$url = wps_curPageURL();
        list($file, $parameters) = explode('?', $url);
        parse_str($parameters, $output);
        unset($output['mail']);
        unset($output['n']);
        unset($output['term']);

        $url = $file . '?' . http_build_query($output); // Rebuild the url

		$html .= '<a class="wps_mail_backto_link" href="'.$url.'">'.$label.'</a>';

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;

}

function wps_mail_search($atts) {

	// Init
	add_action('wp_footer', 'wps_mail_init');

	$html = '';

    // Shortcode parameters
    extract( shortcode_atts( array(
        'class' => '',
        'placeholder' => __('Search Messages...', WPS2_TEXT_DOMAIN),
        'reset' => __('Reset', WPS2_TEXT_DOMAIN),
        'before' => '',
        'after' => '',
    ), $atts, 'wps_mail_backto' ) );
    
    $url = get_permalink();
    $html .= '<div id="wps_mail_search_div"><input id="wps_mail_search" type="text" data-url="'.$url.wps_query_mark($url).'" placeholder="'.$placeholder.'" class="wps_mail_search '.$class.'" />';
    $html .= '<a class="wps_mail_reset" href="'.$url.'">'.$reset.'</a></div>';

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;

}


function wps_mail($atts) {

	// Init
	add_action('wp_footer', 'wps_mail_init');

	global $post;
	$html = '';
	global $current_user, $wpdb;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'count' => 100,
		'mark_all_read_text' => __('Mark all as read', WPS2_TEXT_DOMAIN),
		'private_msg' => __('You must be logged in to view your messages.', WPS2_TEXT_DOMAIN),
        'show_hidden_text' => __('Show hidden messages', WPS2_TEXT_DOMAIN),
        'hide_hidden_text' => __('Hide hidden messages', WPS2_TEXT_DOMAIN),
		'login_url' => '',
		'label_nomail' => __('No messages.', WPS2_TEXT_DOMAIN),
		'date_format' => __('%s ago', WPS2_TEXT_DOMAIN),		
        'comment_avatar_size' => 64,
		'before' => '',
		'after' => '',
	), $atts, 'wps_mail' ) );

	if (is_user_logged_in()) {

		global $current_user;
		$user_id = $current_user->ID;

		// Check for single post view
		if (isset($_GET['mail'])):
			$post_id = $_GET['mail'];
		else:
			$post_id = false;
		endif;

		if (!$post_id):

			// Show all items
			require_once('wps_mail_messages.php');

		else:

			// Show individual item
			require_once('wps_mail_message.php');

		endif;

	} else {

		$query = wps_query_mark(get_bloginfo('url').$login_url);
		if ($login_url) $html .= sprintf('<a href="%s%s%sredirect=%s">', get_bloginfo('url'), $login_url, $query, site_url( $_SERVER['REQUEST_URI'] ));
		$html .= $private_msg;
		if ($login_url) $html .= '</a>';

	}

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_get_mail_page() {

	$mail_page = get_option('wpspro_mail_page');

	$return = $mail_page ? get_permalink($mail_page) : get_bloginfo('url');
    return $return;

}

function wps_alerts_mail($atts) {

    if (is_user_logged_in()) {	
        
        // Init
        add_action('wp_footer', 'wps_mail_init');

        $mail_items = array();
        global $current_user,$post, $wpdb;

        $html = '';

        // Shortcode parameters
        extract( shortcode_atts( array(
            'flag_size' => 24,
            'flag_unread_size' => 10,
            'flag_unread_top' => 6,
            'flag_unread_left' => 8,
            'flag_unread_radius' => 8,
            'flag_url' => false,
            'flag_src' => false,
            'single_redirect' => true,
            'after' => '',
            'before' => '',
        ), $atts, 'wps_alerts_mail' ) );

        $args = array(
            'post_type' => 'wps_mail',
            'orderby' => 'ID',
            'order' => 'DESC',
            'post_status' => 'publish',
            'posts_per_page' => 1000,
            'meta_query' => array(
                array( 
                    'key' => 'wps_mail_recipients',
                    'value' => $current_user->user_login,
                    'compare' => 'LIKE'
                ),
                array( 
                    'key' => 'wps_mail_unread',
                )
            ),
        );

        $posts = get_posts($args);
        
        $unread_count = 0; // unread count
        $mail_id = 0; // default value
        
        foreach ($posts as $mail):

            if ($mail->wps_mail_unread && in_array($current_user->user_login, $mail->wps_mail_unread)):
                $unread_count++;
                $mail_id = $mail->ID;
            endif;

        endforeach;

        wp_reset_query();		

        if ($unread_count == 1 && $single_redirect) $flag_url .= '/?mail='.$mail_id;
        
        $html .= '<div id="wps_alerts_mail_flag" style="width:'.$flag_size.'px; height:'.$flag_size.'px;" >';
        $html .= '<a href="'.$flag_url.'">';
        $src = (!$flag_src) ? plugins_url('images/mail'.get_option('wpspro_flag_colors').'.png', __FILE__) : $flag_src;
        $html .= '<img style="width:'.$flag_size.'px; height:'.$flag_size.'px;" src="'.$src.'" />';
        if ($unread_count):
            $html .= '<div id="wps_alerts_mail_flag_unread" style="position: absolute; padding-top: '.($flag_unread_size*0.2).'px; line-height:'.($flag_unread_size*0.8).'px; font-size:'.($flag_unread_size*0.8).'px; border-radius: '.$flag_unread_radius.'px; top:'.$flag_unread_top.'px; left:'.$flag_unread_left.'px; width:'.$flag_unread_size.'px; height:'.$flag_unread_size.'px;">'.$unread_count.'</div>';
        endif;
        $html .= '</a></div>';
        if (!$flag_url) $html .= '<div class="wps_error">'.__('Set flag_url in shortcode', WPS2_TEXT_DOMAIN).'</div>';

        return $html;	
        
    }
}



function unread_msg_count($atts) {
	
	global $post;
	global $current_user, $wpdb;

	if (is_user_logged_in()) {
		$user_id = $current_user->ID;
		require('wps_mail_messages.php');
	} 
	wp_reset_query();
	return $count_unread_msg;
}

if (!is_admin()) {
    add_shortcode(WPS_PREFIX.'-mail', 'wps_mail');
	add_shortcode(WPS_PREFIX.'-mail-post', 'wps_mail_post');
	add_shortcode(WPS_PREFIX.'-mail-comment', 'wps_mail_comment');
	add_shortcode(WPS_PREFIX.'-mail-recipients', 'wps_mail_recipients');
	add_shortcode(WPS_PREFIX.'-mail-backto', 'wps_mail_backto');
	add_shortcode(WPS_PREFIX.'-mail-search', 'wps_mail_search');
	add_shortcode(WPS_PREFIX.'-mail-to-user', 'wps_mail_to_user_post');
	add_shortcode(WPS_PREFIX.'-alerts-mail', 'wps_alerts_mail');
	
	add_shortcode('unreadmsgs', 'unread_msg_count');
}



?>
