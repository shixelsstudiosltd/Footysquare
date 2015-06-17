<?php

// Init
if (is_admin()) add_action('in_admin_footer', 'wps_extensions_init');
function wps_extensions_init() {        
    wp_enqueue_script('wps-extensions-js', plugins_url('wps_extensions.js', __FILE__), array('jquery'));    
}

// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_extensions', 1);
function wps_admin_getting_started_extensions() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_extensions">'.__('Extensions', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_extensions' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_extensions" style="display:'.$display.'">';

	?>
	<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Activate Extensions', WPS2_TEXT_DOMAIN); ?></label><br />
            <a href="javascript:void(0)" style="font-weight:normal" id="wps_check_all_extensions"><?php _e('Activate all', WPS2_TEXT_DOMAIN); ?></a> |
            <a href="javascript:void(0)" style="font-weight:normal" id="wps_uncheck_all_extensions"><?php _e('Dectivate all', WPS2_TEXT_DOMAIN); ?></a>
		</th>
		<td>
			<?php
			echo '<p><em>'.sprintf(__('For more information, help and videos, click the%s after each extension.<br />A book, <a target="_blank" href="%s">The Complete Guide to WP Symposium Pro</a>, is available online.', WPS2_TEXT_DOMAIN), '<img style="width:16px;height:16px" src="'.plugins_url('../wp-symposium-pro/css/images/help.png', __FILE__).'" title="'.__('help', WPS2_TEXT_DOMAIN).'" />', 'http://www.wpsymposiumpro.com/book').'</em></p>';

			$values = get_option('wps_default_extensions');
			$values = $values ? explode(',', $values) : array();
			echo '<p style="font-size:2.0em;">'.__('Core', WPS2_TEXT_DOMAIN).'</p>';
			echo wps_show_extensions($values, 'ext-alerts-customise', 	__('Customize Email alerts', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/alerts', '');
			echo wps_show_extensions($values, 'ext-login', 				__('Login/Redirect, add restrict access to WordPress dashboard', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/login-redirect/', '');
			echo wps_show_extensions($values, 'ext-system-messages', 	__('System Messages', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-menu-alerts', 		__('Menu Alerts', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/private-mail/', '');

			echo '<p style="margin-top:10px;font-size:2.0em;">'.__('Activity', WPS2_TEXT_DOMAIN).'</p>';
			echo wps_show_extensions($values, 'ext-activity-whoto', 	__('Allow users to choose activity recipient(s)', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/activity/', '');
			echo wps_show_extensions($values, 'ext-crowds', 			__('Let users create activity share lists (requires above to be activated)', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-attachments', 		__('Add images to activity posts', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-soundcloud', 		__('Automatically show Soundcloud on activity', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-youtube', 			__('Automatically show YouTube videos when URL included', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/youtube-plugins/', '');
			echo wps_show_extensions($values, 'ext-remote', 		    __('Automatically show website link previews', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-likes',	 	 	    __('Allow users to like/dislike activity posts and replies', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-favourites',	      	__('Let members keep track of favorite activity posts', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/favorites/', '');

			echo '<p style="margin-top:10px;font-size:2.0em;">'.__('Members', WPS2_TEXT_DOMAIN).'</p>';
			echo wps_show_extensions($values, 'ext-extended', 			__('Add custom profile extensions', WPS2_TEXT_DOMAIN), '', 'http://www.wpsymposiumpro.com/getting-started-videos/user-profile-extensions');
			echo wps_show_extensions($values, 'ext-security', 			__('Allow members to personalise profile security', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-directory', 			__('Display a directory of members', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/member-directory/', '');
			echo wps_show_extensions($values, 'ext-default-friends', 	__('Set default friends when a user joins', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-rewards',		 	__('Reward users with points for actions taken', WPS2_TEXT_DOMAIN), '', 'http://www.wpsymposiumpro.com/getting-started-videos/the-rewards-extension');
			echo wps_show_extensions($values, 'ext-gallery',	 	    __('Allow users to add image galleries', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/galleries/', '');

			echo '<p style="margin-top:10px;font-size:2.0em;">'.__('Forum', WPS2_TEXT_DOMAIN).'</p>';
			echo wps_show_extensions($values, 'ext-forum-attachments', 	__('Allow image and document attachments', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/forum/', '');
			echo wps_show_extensions($values, 'ext-forum-extended', 	__('Set custom fields for new forum posts', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-forum-search', 		__('Add a forum search', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/forum-search-options/', '');
			echo wps_show_extensions($values, 'ext-forum-security', 	__('Fine tune access and permissions for forums', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-forum-signature', 	__('Let members add a personalised forum signature (via Edit Profile)', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-forum-subs', 		__('Email subscriptions for forum posts and replies', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/forum-subscriptions/', '');
			echo wps_show_extensions($values, 'ext-forum-to-activity', 	__('Send forum posts and replies to profile activity', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-forum-toolbar', 		__('Add a WYSIWYG or BBCode toolbar to the forum', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-forum-youtube', 		__('Automatically show YouTube videos when URL included', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/youtube-plugins/', '');

			echo '<p style="margin-top:10px;font-size:2.0em;">'.__('Groups', WPS2_TEXT_DOMAIN).'</p>';
			echo wps_show_extensions($values, 'ext-groups', 			__('Allow members to create groups for group activity', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/groups/', '');
			echo wps_show_extensions($values, 'ext-default-groups', 	__('Set default group membership for new users', WPS2_TEXT_DOMAIN), '', '');

			echo '<p style="margin-top:10px;font-size:2.0em;">'.__('Private Messages', WPS2_TEXT_DOMAIN).'</p>';
			echo wps_show_extensions($values, 'ext-mail', 				__('Private messages between one or more recipients', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/private-mail/', '');
			echo wps_show_extensions($values, 'ext-mail-attachments', 	__('Allow mail attachments', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-mail-youtube', 		__('Automatically show YouTube videos when URL included', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/youtube-plugins/', '');
			echo wps_show_extensions($values, 'ext-mail-subs', 			__('Setup email alerts for new mail and replies', WPS2_TEXT_DOMAIN), '', '');

			echo '<p style="margin-top:10px;font-size:2.0em;">'.__('Miscellaneous', WPS2_TEXT_DOMAIN).'</p>';
			echo wps_show_extensions($values, 'ext-lounge',	      		__('A site-wide chat area ("Lounge") for all members', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-calendar',	 		__('Display calendars on your site', WPS2_TEXT_DOMAIN), '', '');
			echo wps_show_extensions($values, 'ext-show-posts', 		__('Show blog posts, activity, mail, forum activity - amazingly powerful!', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shortcodes/show-posts/', '');
			echo wps_show_extensions($values, 'ext-migrate', 			__('WP Symposium forum migration tool', WPS2_TEXT_DOMAIN), '', '');
			?>
		</td>
	</tr> 
	</table>
	<?php

	echo '</div>';

}

add_action('wps_admin_setup_form_get_hook', 'wps_admin_getting_started_extensions_save', 10, 2);
add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_extensions_save', 10, 2);
function wps_admin_getting_started_extensions_save($the_post) {

	$current_extensions = get_option('wps_default_extensions', true);	

	$wps_default_extensions = '';
	if (isset($the_post['ext-alerts-customise'])) 	$wps_default_extensions .= 'ext-alerts-customise,';
	if (isset($the_post['ext-login'])) 				$wps_default_extensions .= 'ext-login,';
	if (isset($the_post['ext-soundcloud'])) 		$wps_default_extensions .= 'ext-soundcloud,';
	if (isset($the_post['ext-system-messages'])) 	$wps_default_extensions .= 'ext-system-messages,';
	if (isset($the_post['ext-menu-alerts'])) 		$wps_default_extensions .= 'ext-menu-alerts,';

	if (isset($the_post['ext-activity-whoto'])) 	$wps_default_extensions .= 'ext-activity-whoto,';
	if (isset($the_post['ext-crowds'])) 			$wps_default_extensions .= 'ext-crowds,';
	if (isset($the_post['ext-attachments'])) 		$wps_default_extensions .= 'ext-attachments,';
	if (isset($the_post['ext-youtube'])) 			$wps_default_extensions .= 'ext-youtube,';
	if (isset($the_post['ext-remote'])) 			$wps_default_extensions .= 'ext-remote,';
	if (isset($the_post['ext-likes'])) 			    $wps_default_extensions .= 'ext-likes,';
    
	if (isset($the_post['ext-gallery'])) 			$wps_default_extensions .= 'ext-gallery,';

	if (isset($the_post['ext-extended'])) 			$wps_default_extensions .= 'ext-extended,';
	if (isset($the_post['ext-security'])) 			$wps_default_extensions .= 'ext-security,';
	if (isset($the_post['ext-directory'])) 			$wps_default_extensions .= 'ext-directory,';
	if (isset($the_post['ext-default-friends'])) 	$wps_default_extensions .= 'ext-default-friends,';
	if (isset($the_post['ext-rewards'])) 			$wps_default_extensions .= 'ext-rewards,';

	if (isset($the_post['ext-forum-attachments'])) 	$wps_default_extensions .= 'ext-forum-attachments,';
	if (isset($the_post['ext-forum-extended'])) 	$wps_default_extensions .= 'ext-forum-extended,';
	if (isset($the_post['ext-forum-search'])) 		$wps_default_extensions .= 'ext-forum-search,';
	if (isset($the_post['ext-forum-security'])) 	$wps_default_extensions .= 'ext-forum-security,';
	if (isset($the_post['ext-forum-signature'])) 	$wps_default_extensions .= 'ext-forum-signature,';
	if (isset($the_post['ext-forum-subs'])) 		$wps_default_extensions .= 'ext-forum-subs,';
	if (isset($the_post['ext-forum-to-activity'])) 	$wps_default_extensions .= 'ext-forum-to-activity,';
	if (isset($the_post['ext-forum-toolbar'])) 		$wps_default_extensions .= 'ext-forum-toolbar,';
	if (isset($the_post['ext-forum-youtube'])) 		$wps_default_extensions .= 'ext-forum-youtube,';

	if (isset($the_post['ext-groups'])) 			$wps_default_extensions .= 'ext-groups,';
	if (isset($the_post['ext-default-groups'])) 	$wps_default_extensions .= 'ext-default-groups,';

	if (isset($the_post['ext-mail'])) 				$wps_default_extensions .= 'ext-mail,';
	if (isset($the_post['ext-mail-attachments'])) 	$wps_default_extensions .= 'ext-mail-attachments,';
	if (isset($the_post['ext-mail-subs'])) 			$wps_default_extensions .= 'ext-mail-subs,';
	if (isset($the_post['ext-mail-youtube'])) 		$wps_default_extensions .= 'ext-mail-youtube,';

	if (isset($the_post['ext-favourites'])) 		$wps_default_extensions .= 'ext-favourites,';
	if (isset($the_post['ext-lounge'])) 			$wps_default_extensions .= 'ext-lounge,';
	if (isset($the_post['ext-calendar'])) 			$wps_default_extensions .= 'ext-calendar,';
	if (isset($the_post['ext-show-posts'])) 		$wps_default_extensions .= 'ext-show-posts,';
	if (isset($the_post['ext-migrate'])) 			$wps_default_extensions .= 'ext-migrate,';

	update_option('wps_default_extensions', $wps_default_extensions);

	if ($current_extensions != $wps_default_extensions):
		echo '<div class="wps_success">'.__('Extensions saved, please <a href="">click here</a> to update screen options.', WPS2_TEXT_DOMAIN).'</div>';
	endif;

}

function wps_show_extensions($values, $field, $label, $help, $video) {
	$html = '';
	$html .= '<input type="checkbox" class="wps_extension_checkbox" style="width:10px" name="'.$field.'"';
	if (in_array($field, $values)) $html .= ' CHECKED';
	$html .= '>'.__($label, WPS2_TEXT_DOMAIN);
	if ($help) $html .= sprintf('<a href="%s" target="_blank"><img style="width:16px;height:16px" src="'.plugins_url('../wp-symposium-pro/css/images/help.png', __FILE__).'" title="'.__('help', WPS2_TEXT_DOMAIN).'" /></a>', $help);
	if ($video) $html .= sprintf('<a href="%s" target="_blank"><img style="width:16px;height:16px" src="'.plugins_url('../wp-symposium-pro/css/images/video.png', __FILE__).'" title="'.__('video', WPS2_TEXT_DOMAIN).'" /></a>', $video);
	$html .= '<br />';
	return $html;
}
?>
