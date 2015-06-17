<?php

// Add unsubscribe to all in wps_usermeta_change
add_filter('wps_usermeta_change_filter', 'wps_mail_subs_usermeta_extend', 10, 3);
function wps_mail_subs_usermeta_extend($form_html, $atts, $user_id) {

	global $current_user;

	if (!get_user_meta($current_user->ID, 'wps_mail_subs_subscribe', true))
		update_user_meta($current_user->ID, 'wps_mail_subs_subscribe', 'on');

	// Shortcode parameters
	extract( shortcode_atts( array(
		'mail_subs_subscribe' => __('Receive email notifications of mail messages', WPS2_TEXT_DOMAIN),
		'meta_class' => 'wps_usermeta_change_label',
	), $atts, 'wps_usermeta_change' ) );

	$form_html .= '<div class="wps_usermeta_change_item">';
	$form_html .= '<div class="'.$meta_class.'"><input type="checkbox" name="wps_mail_subs_subscribe" ';
	if (get_user_meta($current_user->ID, 'wps_mail_subs_subscribe', true) == 'on')
		$form_html .= ' CHECKED';
	$form_html .= '/> '.$mail_subs_subscribe.'</div>';
	$form_html .= '</div>';

	return $form_html;

}

// Extend wps_usermeta_change save
add_action( 'wps_usermeta_change_hook', 'wps_mail_subs_usermeta_extend_save', 10, 4 );
function wps_mail_subs_usermeta_extend_save($user_id, $atts, $the_form, $the_files) {

	global $current_user;

	// Double check logged in
	if (is_user_logged_in()):

		if (isset($_POST['wps_mail_subs_subscribe'])):

			update_user_meta($current_user->ID, 'wps_mail_subs_subscribe', 'on');

		else:

			update_user_meta($current_user->ID, 'wps_mail_subs_subscribe', 'off');

		endif;

	endif;

}


// Hook into wps_mail_post_add_hook to send email for new messages
add_action( 'wps_mail_post_add_hook', 'wps_mail_subs_post_add_alerts', 10, 3 );
function wps_mail_subs_post_add_alerts($the_post, $the_files, $new_id) {

	wps_mail_send_new_post_alert($new_id);

}

// Hook into wps_mail_comment_add_hook to send email for new comments (replies)
add_action( 'wps_mail_comment_add_hook', 'wps_mail_subs_comment_add_alerts', 10, 4 );
function wps_mail_subs_comment_add_alerts($the_comment, $the_files, $post_id, $new_id) {

	wps_mail_send_new_comment_alert($new_id);

}


// Sends new mail alert
function wps_mail_send_new_post_alert($new_id) {

	if (post_type_exists('wps_alerts')):

		$new_post = get_post($new_id);

		$mail_recipients = get_post_meta( $new_id, 'wps_mail_recipients', true );

		$recipients = array();
		$c=0;
		if ($mail_recipients):
			foreach ($mail_recipients as $mail_recipient) {
				$c++;
				$recipients['sub '.$c] = $mail_recipient;
			}
		endif;

		$sent = array();
		global $current_user;
		get_currentuserinfo();

		if ($recipients):

			// Don't send to self
			array_push($sent, $current_user->user_login);

			// Do send to rest
			foreach ($recipients as $key=>$value):

				if ( !in_array($value, $sent) ):

					$user = get_user_by('login', $value);

					$status = 'publish';
					if (!get_user_meta($user->ID, 'wps_mail_subs_subscribe', true) || get_user_meta($user->ID, 'wps_mail_subs_subscribe', true) == 'on') $status = 'pending';

					array_push($sent, $value);

					$subject = __('New mail', WPS2_TEXT_DOMAIN);
					$subject = get_bloginfo('name').': '.$subject;

					$content = '';

					$content = apply_filters( 'wps_alert_before', $content );
			
					$content .= '<h1>'.$new_post->post_title.'</h1>';

					$author = get_user_by('id', $new_post->post_author);
					$msg = sprintf(__('You have received a new message from %s.', WPS2_TEXT_DOMAIN), $author->display_name);
					$content .= '<p>'.$msg.'</p>';

					$content .= '<p>'.$new_post->post_content.'</p>';

					$url = wps_get_mail_page();
					$url .= wps_query_mark($url).'mail='.$new_post->ID;
					$content .= '<p><a href="'.$url.'">'.$url.'</a></p>';

					$content = apply_filters( 'wps_alert_after', $content );

					wps_pro_insert_alert('mail', $subject, $content, $new_post->post_author, $user->ID, '', $url, $msg, $status);

				endif;
				
			endforeach;
		endif;

	endif;

}

// Sends new comment alert
function wps_mail_send_new_comment_alert($new_id) {

	if (post_type_exists('wps_alerts')):

		$the_comment = get_comment( $new_id );
		$new_post = get_post($the_comment->comment_post_ID);

		$mail_recipients = get_post_meta( $new_post->ID, 'wps_mail_recipients', true );

		$recipients = array();
		$c=0;
		if ($mail_recipients):
			foreach ($mail_recipients as $mail_recipient) {
				$c++;
				$recipients['sub '.$c] = $mail_recipient;
			}
		endif;

		$sent = array();
		global $current_user;
		get_currentuserinfo();

		if ($recipients):

			// Don't send to self
			array_push($sent, $current_user->user_login);

			// Do send to rest
			foreach ($recipients as $key=>$value):

				if ( !in_array($value, $sent) ):

					$user = get_user_by('login', $value);

					$status = 'publish';
					if (get_user_meta($user->ID, 'wps_mail_subs_subscribe', true)) $status = 'pending';

					array_push($sent, $value);

					$subject = __('New mail reply', WPS2_TEXT_DOMAIN);
					$subject = get_bloginfo('name').': '.$subject;

					$content = '';

					$content = apply_filters( 'wps_alert_before', $content );
			
					$msg = sprintf(__('A new private message reply from %s.', WPS2_TEXT_DOMAIN), $current_user->display_name);
					$content .= '<p>'.$msg.'</p>';
					$content .= '<p><em>'.$the_comment->comment_content.'</em></p>';				

					$post_author = get_user_by('id', $new_post->post_author);
					$content .= '<p><strong>'.sprintf(__('Original message by %s', WPS2_TEXT_DOMAIN), $post_author->display_name).'</strong></p>';
					$content .= '<h2>'.$new_post->post_title.'</h2>';
					$content .= '<p>'.$new_post->post_content.'</p>';

					$url = wps_get_mail_page();
					$url .= wps_query_mark($url).'mail='.$new_post->ID;						
					$content .= '<p><a href="'.$url.'">'.$url.'</a></p>';

					$content = apply_filters( 'wps_alert_after', $content );

					wps_pro_insert_alert('mail', $subject, $content, $new_post->post_author, $user->ID, '', $url, $msg, $status);

				endif;
				
			endforeach;
		endif;

	endif;

}




?>