<?php
/*
Plugin Name: WP Symposium Pro (Extensions)
Plugin URI: http://www.wpsymposiumpro.com
Description: If an update for Extensions is available, please <strong>wait until the core WP Symposium Pro update is also available</strong> of the same version, and <strong>update the core first</strong>, followed by this Extensions plugin. Adds all the extensions available for <a href="http://www.wpsymposiumpro.com">WP Symposium Pro</a>. Activate via WPS Pro->Setup->Extensions.
Version: 14.12.2
Author: Simon Goodchild
Author URI: http://www.wpsymposiumpro.com
License: Commercial. Cannot be reproduced, replicated or modified without the express permission of the author.
*/

if ( !defined('WPS2_TEXT_DOMAIN') ) define('WPS2_TEXT_DOMAIN', 'wp-symposium-pro');
if ( !defined('WPS_PREFIX') ) define('WPS_PREFIX', 'wps');

// Auto updates
if (is_admin()) add_action('init', '__wps__wpspro_extensions_au');
function __wps__wpspro_extensions_au()
{
	require_once ('wp_autoupdate.php');
	$wptuts_plugin_current_version = '14.12.2';
	$wptuts_plugin_remote_path = 'http://www.wpsymposiumpro.com/wp-content/plugins/wp-symposium-pro-extensions/update.php';
	$wptuts_plugin_slug = plugin_basename(__FILE__);
	new __wps__wpspro_extensions_auto_update ($wptuts_plugin_current_version, $wptuts_plugin_remote_path, $wptuts_plugin_slug);
}

// Admin
if (is_admin())
	require_once('wp_symposium_pro_extensions_admin.php');

// Activation
$values = get_option('wps_default_extensions');
if ($values):
	$values = explode(',', $values);	
	// Core
	if (in_array('ext-alerts-customise', $values)) 	require_once('wp-symposium-pro-alerts-customise/wps_alerts_customise.php');
	if (in_array('ext-login', $values)) 			require_once('wp-symposium-pro-login/wps_login.php');
	if (in_array('ext-system-messages', $values)) 	require_once('wp-symposium-pro-system-messages/wps_system_messages.php');
	if (in_array('ext-menu-alerts', $values)) 		require_once('wp-symposium-pro-menu-alerts/wps_menu_alerts.php');
	// Activity
	if (in_array('ext-activity-whoto', $values)) 	require_once('wp-symposium-pro-activity-whoto/wps_activity_whoto.php');
	if (in_array('ext-crowds', $values)) 			require_once('wp-symposium-pro-crowds/wps_crowds.php');
	if (in_array('ext-attachments', $values)) 		require_once('wp-symposium-pro-attachments/wps_attachments.php');
	if (in_array('ext-soundcloud', $values)) 		require_once('wp-symposium-pro-soundcloud/wps_soundcloud.php');
	if (in_array('ext-youtube', $values)) 			require_once('wp-symposium-pro-youtube/wps_youtube.php');
	if (in_array('ext-remote', $values)) 			require_once('wp-symposium-pro-activity-url-preview/wps_activity_url_preview.php');
	// Members
	if (in_array('ext-extended', $values)) 			require_once('wp-symposium-pro-extended/wps_extended.php');
	if (in_array('ext-security', $values)) 			require_once('wp-symposium-pro-security/wps_security.php');
	if (in_array('ext-directory', $values)) 		require_once('wp-symposium-pro-directory/wps_directory.php');
	if (in_array('ext-default-friends', $values)) 	require_once('wp-symposium-pro-default-friends/wps_default_friends.php');
	if (in_array('ext-rewards', $values)) 			require_once('wp-symposium-pro-rewards/wps_rewards.php');
	if (in_array('ext-likes', $values)) 			require_once('wp-symposium-pro-likes/wps_likes.php');
	if (in_array('ext-gallery', $values)) 			require_once('wp-symposium-pro-gallery/wps_gallery.php');
	// Forum
	if (in_array('ext-forum-attachments', $values)) require_once('wp-symposium-pro-forum-attachments/wps_forum_attachments.php');
	if (in_array('ext-forum-extended', $values)) 	require_once('wp-symposium-pro-forum-extended/wps_forum_extended.php');
	if (in_array('ext-forum-search', $values)) 		require_once('wp-symposium-pro-forum-search/wps_forum_search.php');
	if (in_array('ext-forum-security', $values)) 	require_once('wp-symposium-pro-forum-security/wps_forum_security.php');
	if (in_array('ext-forum-signature', $values)) 	require_once('wp-symposium-pro-forum-signature/wps_forum_signature.php');
	if (in_array('ext-forum-subs', $values)) 		require_once('wp-symposium-pro-forum-subs/wps_forum_subs.php');
	if (in_array('ext-forum-to-activity', $values)) require_once('wp-symposium-pro-forum-to-activity/wps_forum_to_activity.php');
	if (in_array('ext-forum-toolbar', $values)) 	require_once('wp-symposium-pro-forum-toolbar/wps_forum_toolbar.php');
	if (in_array('ext-forum-youtube', $values)) 	require_once('wp-symposium-pro-forum-youtube/wps_forum_youtube.php');
	// Groups
	if (in_array('ext-groups', $values)) 			require_once('wp-symposium-pro-groups/wps_groups.php');
	if (in_array('ext-default-groups', $values)) 	require_once('wp-symposium-pro-default-groups/wps_default_groups.php');
	// Mail
	if (in_array('ext-mail', $values)) 				require_once('wp-symposium-pro-mail/wps_mail.php');
	if (in_array('ext-mail-attachments', $values)) 	require_once('wp-symposium-pro-mail-attachments/wps_mail_attachments.php');
	if (in_array('ext-mail-subs', $values)) 		require_once('wp-symposium-pro-mail-subs/wps_mail_subs.php');
	if (in_array('ext-mail-youtube', $values)) 		require_once('wp-symposium-pro-mail-youtube/wps_mail_youtube.php');
	// Miscellaneous
	if (in_array('ext-favourites', $values)) 		require_once('wp-symposium-pro-favourites/wps_favourites.php');
	if (in_array('ext-lounge', $values)) 			require_once('wp-symposium-pro-lounge/wps_lounge.php');
	if (in_array('ext-calendar', $values)) 			require_once('wp-symposium-pro-calendar/wps_calendar.php');
	if (in_array('ext-show-posts', $values)) 		require_once('wp-symposium-pro-show-posts/wps_show_posts.php');
	if (in_array('ext-migrate', $values)) 			require_once('wp-symposium-pro-migrate/wps_migrate.php');
endif;

// Replace (pluggable) wp_notify_postauthor() to avoid sending WordPress comment notifications for mail and forum
if ( ! function_exists('wp_notify_postauthor') ) :

	function wp_notify_postauthor($comment_id, $deprecated = null) {
        
        $comment = get_comment($comment_id);
        $the_post = get_post($comment->comment_post_ID);
        if ($the_post->post_type == 'wps_mail' || $the_post->post_type == 'wps_forum_post'):
            return false;
        else:

            // Original WordPress function
        	if ( null !== $deprecated ) {
                _deprecated_argument( __FUNCTION__, '3.8' );
            }

            $comment = get_comment( $comment_id );
            if ( empty( $comment ) )
                return false;

            $post    = get_post( $comment->comment_post_ID );
            $author  = get_userdata( $post->post_author );

            // Who to notify? By default, just the post author, but others can be added.
            $emails = array();
            if ( $author ) {
                $emails[] = $author->user_email;
            }

            /**
             * Filter the list of email addresses to receive a comment notification.
             *
             * By default, only post authors are notified of comments. This filter allows
             * others to be added.
             *
             * @since 3.7.0
             *
             * @param array $emails     An array of email addresses to receive a comment notification.
             * @param int   $comment_id The comment ID.
             */
            $emails = apply_filters( 'comment_notification_recipients', $emails, $comment_id );
            $emails = array_filter( $emails );

            // If there are no addresses to send the comment to, bail.
            if ( ! count( $emails ) ) {
                return false;
            }

            // Facilitate unsetting below without knowing the keys.
            $emails = array_flip( $emails );

            /**
             * Filter whether to notify comment authors of their comments on their own posts.
             *
             * By default, comment authors aren't notified of their comments on their own
             * posts. This filter allows you to override that.
             *
             * @since 3.8.0
             *
             * @param bool $notify     Whether to notify the post author of their own comment.
             *                         Default false.
             * @param int  $comment_id The comment ID.
             */
            $notify_author = apply_filters( 'comment_notification_notify_author', false, $comment_id );

            // The comment was left by the author
            if ( $author && ! $notify_author && $comment->user_id == $post->post_author ) {
                unset( $emails[ $author->user_email ] );
            }

            // The author moderated a comment on their own post
            if ( $author && ! $notify_author && $post->post_author == get_current_user_id() ) {
                unset( $emails[ $author->user_email ] );
            }

            // The post author is no longer a member of the blog
            if ( $author && ! $notify_author && ! user_can( $post->post_author, 'read_post', $post->ID ) ) {
                unset( $emails[ $author->user_email ] );
            }

            // If there's no email to send the comment to, bail, otherwise flip array back around for use below
            if ( ! count( $emails ) ) {
                return false;
            } else {
                $emails = array_flip( $emails );
            }

            $comment_author_domain = @gethostbyaddr($comment->comment_author_IP);

            // The blogname option is escaped with esc_html on the way into the database in sanitize_option
            // we want to reverse this for the plain text arena of emails.
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

            switch ( $comment->comment_type ) {
                case 'trackback':
                    $notify_message  = sprintf( __( 'New trackback on your post "%s"' ), $post->post_title ) . "\r\n";
                    /* translators: 1: website name, 2: author IP, 3: author domain */
                    $notify_message .= sprintf( __('Website: %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
                    $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
                    $notify_message .= __('Excerpt: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
                    $notify_message .= __('You can see all trackbacks on this post here: ') . "\r\n";
                    /* translators: 1: blog name, 2: post title */
                    $subject = sprintf( __('[%1$s] Trackback: "%2$s"'), $blogname, $post->post_title );
                    break;
                case 'pingback':
                    $notify_message  = sprintf( __( 'New pingback on your post "%s"' ), $post->post_title ) . "\r\n";
                    /* translators: 1: comment author, 2: author IP, 3: author domain */
                    $notify_message .= sprintf( __('Website: %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
                    $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
                    $notify_message .= __('Excerpt: ') . "\r\n" . sprintf('[...] %s [...]', $comment->comment_content ) . "\r\n\r\n";
                    $notify_message .= __('You can see all pingbacks on this post here: ') . "\r\n";
                    /* translators: 1: blog name, 2: post title */
                    $subject = sprintf( __('[%1$s] Pingback: "%2$s"'), $blogname, $post->post_title );
                    break;
                default: // Comments
                    $notify_message  = sprintf( __( 'New comment on your post "%s"' ), $post->post_title ) . "\r\n";
                    /* translators: 1: comment author, 2: author IP, 3: author domain */
                    $notify_message .= sprintf( __('Author : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
                    $notify_message .= sprintf( __('E-mail : %s'), $comment->comment_author_email ) . "\r\n";
                    $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
                    $notify_message .= sprintf( __('Whois  : http://whois.arin.net/rest/ip/%s'), $comment->comment_author_IP ) . "\r\n";
                    $notify_message .= __('Comment: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
                    $notify_message .= __('You can see all comments on this post here: ') . "\r\n";
                    /* translators: 1: blog name, 2: post title */
                    $subject = sprintf( __('[%1$s] Comment: "%2$s"'), $blogname, $post->post_title );
                    break;
            }
            $notify_message .= get_permalink($comment->comment_post_ID) . "#comments\r\n\r\n";
            $notify_message .= sprintf( __('Permalink: %s'), get_comment_link( $comment_id ) ) . "\r\n";

            if ( user_can( $post->post_author, 'edit_comment', $comment_id ) ) {
                if ( EMPTY_TRASH_DAYS )
                    $notify_message .= sprintf( __('Trash it: %s'), admin_url("comment.php?action=trash&c=$comment_id") ) . "\r\n";
                else
                    $notify_message .= sprintf( __('Delete it: %s'), admin_url("comment.php?action=delete&c=$comment_id") ) . "\r\n";
                $notify_message .= sprintf( __('Spam it: %s'), admin_url("comment.php?action=spam&c=$comment_id") ) . "\r\n";
            }

            $wp_email = 'wordpress@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));

            if ( '' == $comment->comment_author ) {
                $from = "From: \"$blogname\" <$wp_email>";
                if ( '' != $comment->comment_author_email )
                    $reply_to = "Reply-To: $comment->comment_author_email";
            } else {
                $from = "From: \"$comment->comment_author\" <$wp_email>";
                if ( '' != $comment->comment_author_email )
                    $reply_to = "Reply-To: \"$comment->comment_author_email\" <$comment->comment_author_email>";
            }

            $message_headers = "$from\n"
                . "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";

            if ( isset($reply_to) )
                $message_headers .= $reply_to . "\n";

            /**
             * Filter the comment notification email text.
             *
             * @since 1.5.2
             *
             * @param string $notify_message The comment notification email text.
             * @param int    $comment_id     Comment ID.
             */
            $notify_message = apply_filters( 'comment_notification_text', $notify_message, $comment_id );

            /**
             * Filter the comment notification email subject.
             *
             * @since 1.5.2
             *
             * @param string $subject    The comment notification email subject.
             * @param int    $comment_id Comment ID.
             */
            $subject = apply_filters( 'comment_notification_subject', $subject, $comment_id );

            /**
             * Filter the comment notification email headers.
             *
             * @since 1.5.2
             *
             * @param string $message_headers Headers for the comment notification email.
             * @param int    $comment_id      Comment ID.
             */
            $message_headers = apply_filters( 'comment_notification_headers', $message_headers, $comment_id );

            foreach ( $emails as $email ) {
                @wp_mail( $email, wp_specialchars_decode( $subject ), $notify_message, $message_headers );
            }

            return true;

        endif;
	}
	
endif;

?>