<?php
/**
 * Plugin Name: Follow My Blog Post
 * Plugin URI: http://wpweb.co.in
 * Description: Follow My Blog Post plugin allows your visitors to follow changes on your site for particular post, page, category, tags, authors etc. 
 * Version: 1.5.4
 * Author: WPWeb
 * Author URI: http://wpweb.co.in
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions 
 * 
 * @package Follow My Blog Post
 * @since 1.0.0
 */
global $wpdb;

if( !defined( 'WPW_FP_DIR' ) ) {
	define( 'WPW_FP_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'WPW_FP_URL' ) ) {
	define( 'WPW_FP_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'WPW_FP_ADMIN_DIR' ) ) {
	define( 'WPW_FP_ADMIN_DIR', WPW_FP_DIR . '/includes/admin' ); // plugin admin dir
}
if( !defined( 'WPW_FP_IMG_URL' ) ) {
	define( 'WPW_FP_IMG_URL', WPW_FP_URL . 'includes/images' ); // plugin images url
}
if( !defined( 'WPW_FP_POST_TYPE' ) ) {
	define( 'WPW_FP_POST_TYPE', 'wpwfollowpost' ); // follow post custom post type's slug
}
if( !defined( 'WPW_FP_LOGS_POST_TYPE' ) ) {
	define( 'WPW_FP_LOGS_POST_TYPE', 'wpwfollowpostlogs' ); // follow post logs custom post type's slug
}
if( !defined( 'WPW_FP_TERM_POST_TYPE' ) ) {
	define( 'WPW_FP_TERM_POST_TYPE', 'wpwfollowterm' ); // follow term custom post type's slug
}
if( !defined( 'WPW_FP_TERM_LOGS_POST_TYPE' ) ) {
	define( 'WPW_FP_TERM_LOGS_POST_TYPE', 'wpwfollowtermlogs' ); // follow term custom post type's slug
}
if( !defined( 'WPW_FP_AUTHOR_POST_TYPE' ) ) {
	define( 'WPW_FP_AUTHOR_POST_TYPE', 'wpwfollowauthor' ); // follow author custom post type's slug
}
if( !defined( 'WPW_FP_AUTHOR_LOGS_POST_TYPE' ) ) {
	define( 'WPW_FP_AUTHOR_LOGS_POST_TYPE', 'wpwfollowauthorlogs' ); // follow author custom post type's slug
}
if( !defined( 'WPW_FP_BASENAME' ) ) {
	define( 'WPW_FP_BASENAME', 'follow-my-blog-post' ); // base name
}
if( !defined( 'wpwfplevel' ) ) {
	define( 'wpwfplevel', 'manage_options' );
}
if( !defined( 'WPW_FP_META_DIR' ) ) {
	define( 'WPW_FP_META_DIR', WPW_FP_DIR . '/includes/meta-boxes' ); // dir path to meta boxes
}
if( !defined( 'WPW_FP_META_PREFIX' ) ) {
	define( 'WPW_FP_META_PREFIX', '_wpw_fp_' ); // dir path to meta boxes
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
load_plugin_textdomain( 'wpwfp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Activation Hook
 *
 * Register plugin activation hook.
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wpw_fp_install' );

/**
 * Deactivation Hook
 *
 * Register plugin deactivation hook.
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wpw_fp_uninstall');

/**
 * Plugin Setup (On Activation)
 *
 * Does the initial setup,
 * stest default values for the plugin options.
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
function wpw_fp_install() {
	
	global $wpdb, $user_ID;
	
	//register post type
	wpw_fp_register_post_types();
	
	//IMP Call of Function
	//Need to call when custom post type is being used in plugin
	flush_rewrite_rules();
	
	//get all options of settings
	$wpw_fp_options = get_option( 'wpw_fp_options' );
	
	$wpw_fp_set_option = get_option( 'wpw_fp_set_option' );
	
	//check if option is emtry
	if( empty( $wpw_fp_options ) ) {
		
		$subscribe_manage_page = array(
										'post_type' 	=> 'page',
										'post_status' 	=> 'publish',
										'post_title' 	=> __( 'Subscription Management','wpwfp' ),
										'post_content' 	=> '[wpw_follow_post_list][/wpw_follow_post_list]' . "\n\r" . '[wpw_follow_term_list][/wpw_follow_term_list]'  . "\n\r" . '[wpw_follow_author_list][/wpw_follow_author_list]',
										'post_author' 	=> $user_ID,
										'menu_order' 	=> 0,
										'comment_status'=> 'closed'
									);
							
		//create subscribe manage page
		$subscribe_manage_page_id = wp_insert_post( $subscribe_manage_page );
		
		$unsubscribe_page = array(
										'post_type' 	=> 'page',
										'post_status' 	=> 'publish',
										'post_parent'	=>	$subscribe_manage_page_id,
										'post_title' 	=> __( 'Unsubscribe','wpwfp' ),
										'post_content' 	=> '',
										'post_author' 	=> $user_ID,
										'menu_order' 	=> 0,
										'comment_status'=> 'closed'
									);
							
		//create unsubscribe page
		$unsubscribe_page_id = wp_insert_post( $unsubscribe_page );
		
		// this option contains all page ID(s) to just pass it to ww_fp_default_settings function
		update_option( 'wpw_fp_set_pages', array(
														'subscribe_manage_page'	=> 	$subscribe_manage_page_id,
														'unsubscribe_page'		=> 	$unsubscribe_page_id,
													));
		
		wpw_fp_default_settings(); // set default settings
		
		//update plugin version to option 
		update_option( 'wpw_fp_set_option', '1.0' );
		
	} //check fp options empty or not 
	
	if( $wpw_fp_set_option == '1.0' ) {
		
		$udpopt = false;
		
		if( !isset( $wpw_fp_options['term_follow_buttons'] ) ) { //check Category / Tags Follow Button Text is set or not
			$term_follow_buttons = array( 
									'term_follow_buttons'=> array( 
																'follow'	=> __( 'Follow', 'wpwfp' ) . ' {term_name}',
																'following'	=> __( 'Following', 'wpwfp' ) . ' {term_name}',
																'unfollow'	=> __( 'Unfollow', 'wpwfp' ) . ' {term_name}' 
															)
										);
			$wpw_fp_options = array_merge( $wpw_fp_options, $term_follow_buttons );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['term_follow_message'] ) ) { //check Category / Tags Followers Counter Message is set or not
			//$term_follow_message = array( 'term_follow_message'=> __( '( ', 'wpwfp' ) . '{followers_count}' . __( ' Followers )', 'wpwfp' ) );
			$term_follow_message = array('term_follow_message'=> '( {followers_count} '.__( 'Followers', 'wpwfp') . ' )' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $term_follow_message );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['recipient_per_email'] ) ) { //check Recipients Per Email is set or not
			$recipient_per_email = array( 'recipient_per_email'=> '0' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $recipient_per_email );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['post_trigger_notification'] ) ) { //check Post / Page Notification Events Trigger Emails is set or not
			$post_trigger_notification = array( 'post_trigger_notification'=> array( 'post_update' => '1' ) );
			$wpw_fp_options = array_merge( $wpw_fp_options, $post_trigger_notification );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['term_trigger_notification'] ) ) { //check Category / Tags Notification Events Trigger Emails is set or not
			$term_trigger_notification = array( 'term_trigger_notification'=> array( 'new_post' => '1' ) );
			$wpw_fp_options = array_merge( $wpw_fp_options, $term_trigger_notification );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['enable_unsubscribe_url'] ) ) { //check Add Unsubscribe link to email Message is set or not
			$enable_unsubscribe_url = array( 'enable_unsubscribe_url'=> '1' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $enable_unsubscribe_url );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['unsubscribe_message'] ) ) { //check Unsubscribe Message is set or not
			$unsubscribe_message = array( 'unsubscribe_message'=> sprintf( __( 'If you want to unsubscribe, click on %s', 'wpwfp' ), '{unsubscribe_url}' ) );
			$wpw_fp_options = array_merge( $wpw_fp_options, $unsubscribe_message );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['comment_email_subject'] ) ) { //check Comment Email Subject is set or not
			$comment_email_subject = array( 'comment_email_subject'=> sprintf( __( 'New comment on %s by %s', 'wpwfp' ), '"{post_name}"', '{user_name}' ) );
			$wpw_fp_options = array_merge( $wpw_fp_options, $comment_email_subject );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['comment_email_body'] ) ) { //check Comment Email Body is set or not
			$comment_email_body = array( 'comment_email_body'=> sprintf(__( 'New comment added on the post %s by %s, see below', 'wpwfp' ), '"{post_name}"', '{user_name}' )." :\n\n".'{comment_text}' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $comment_email_body );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['term_email_subject'] ) ) { //check Category / Tags Subscription Email Subject is set or not
			$term_email_subject = array( 'term_email_subject'=> __( '[New Post]', 'wpwfp').' {post_name}' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $term_email_subject );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['term_email_body'] ) ) { //check Category / Tags Subscription Email Body is set or not
			$term_email_body = array( 'term_email_body'=> __( 'New post added under the', 'wpwfp').' {taxonomy_name} "{term_name}":'."\n\n".'{post_name}'."\n\n".'{post_description}'."\n\n".__( 'If you want to see page click below link', 'wpwfp')."\n\n".'{post_link} '.__( 'for', 'wpwfp' ).' {site_link}' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $term_email_body );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['term_confirm_email_subject'] ) ) { //check Category / Tags Confirmation Email Subject is set or not
			$term_confirm_email_subject = array( 'term_confirm_email_subject'=> __( 'Follow', 'wpwfp') .'{term_name} - {site_name}' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $term_confirm_email_subject );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['term_confirm_email_subject'] ) ) { //check Category / Tags Confirmation Email Body is set or not
			//$term_confirm_email_subject = array( 'term_confirm_email_subject'=> __( 'Hello'."\n\n".'You recently followed the {taxonomy_name} "{term_name}". This means you will receive an email when any new post is published under the {taxonomy_name} "{term_name}".'."\n\n".'To activate, click confirm below. If you did not request this, please feel free to disregard this notice!'."\n\n".'{subscribe_url}'."\n\n".'Thanks', 'wpwfp' ) );
			$term_confirm_email_subject = array( 'term_confirm_email_subject'=> __( 'Hello', 'wpwfp' )."\n\n".__( 'You recently followed the', 'wpwfp').' {taxonomy_name} "{term_name}". '.__( 'This means you will receive an email when any new post is published under the', 'wpwfp') . ' {taxonomy_name} "{term_name}".'."\n\n".__( 'To activate, click confirm below. If you did not request this, please feel free to disregard this notice!', 'wpwfp')."\n\n".'{subscribe_url}'."\n\n".__( 'Thanks', 'wpwfp') );
			$wpw_fp_options = array_merge( $wpw_fp_options, $term_confirm_email_subject );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['unsubscribe_confirm_email_subject'] ) ) { //check Unsubscribe Confirmation Email Subject is set or not
			//$unsubscribe_confirm_email_subject = array( 'unsubscribe_confirm_email_subject'=> __( '[{site_name}] Please confirm your unsubscription request', 'wpwfp' ) );
			$unsubscribe_confirm_email_subject = array( 'unsubscribe_confirm_email_subject'=> '[{site_name}] ' . __( 'Please confirm your unsubscription request', 'wpwfp' ) );
			$wpw_fp_options = array_merge( $wpw_fp_options, $unsubscribe_confirm_email_subject );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['unsubscribe_confirm_email_body'] ) ) { //check Unsubscribe Confirmation Email Body is set or not
			$unsubscribe_confirm_email_body = array( 'unsubscribe_confirm_email_body'=> '{site_name} ' . __('has received a request to unsubscribe for this email address. To complete your request please click on the link below', 'wpwfp' ).":\n\n".'{confirm_url}'."\n\n".__( 'If you did not request this, please feel free to disregard this notice!', 'wpwfp') );
			$wpw_fp_options = array_merge( $wpw_fp_options, $unsubscribe_confirm_email_body );
			$udpopt = true;
		}
		//create subscribe manage page
		if( !isset( $wpw_fp_options['subscribe_manage_page'] ) ) {
		
			$subscribe_manage_page = array(
											'post_type' 	=> 'page',
											'post_status' 	=> 'publish',
											'post_title' 	=> __( 'Subscription Management','wpwfp' ),
											'post_content' 	=> '[wpw_follow_post_list][/wpw_follow_post_list]' . "\n\r" . '[wpw_follow_term_list][/wpw_follow_term_list]' . "\n\r" . '[wpw_follow_author_list][/wpw_follow_author_list]',
											'post_author' 	=> $user_ID,
											'menu_order' 	=> 0,
											'comment_status'=> 'closed'
										);
								
			//create subscribe manage page
			$subscribe_manage_page_id = wp_insert_post( $subscribe_manage_page );
			
			$unsubscribe_page = array(
											'post_type' 	=> 'page',
											'post_status' 	=> 'publish',
											'post_parent'	=>	$subscribe_manage_page_id,
											'post_title' 	=> __( 'Unsubscribe','wpwfp' ),
											'post_content' 	=> '',
											'post_author' 	=> $user_ID,
											'menu_order' 	=> 0,
											'comment_status'=> 'closed'
										);
								
			//create unsubscribe page
			$unsubscribe_page_id = wp_insert_post( $unsubscribe_page );
			
			//get set pages option data
			$wpw_fp_set_pages = get_option( 'wpw_fp_set_pages' );
			
			//store subscription manage page to already created page
			$wpw_fp_set_pages['subscribe_manage_page'] = $subscribe_manage_page_id;
			$wpw_fp_set_pages['unsubscribe_page'] = $unsubscribe_page_id;
			
			//update new pages data
			update_option( 'wpw_fp_set_pages', $wpw_fp_set_pages );
			
			$followpage = array( 
									'subscribe_manage_page'	=>	$subscribe_manage_page_id,
									'unsubscribe_page'		=>	$unsubscribe_page_id
								);
			$wpw_fp_options = array_merge( $wpw_fp_options, $followpage );
			$udpopt = true;
			
		} //end if to check unsubscribe page
		
		if( isset( $wpw_fp_options['prevent_type'] ) && isset( $wpw_fp_options['notification_type'] ) ) {
			
			//get all post type
			$post_types = get_post_types( array( 'public' => true ), 'names' );
			
			foreach ( $post_types as $key => $post_type ) {
				if( $key == 'attachment' ) {
					unset( $post_types[$key] );
				}
			}
			
			$post_types_enable = array( 'notification_type'=> $post_types, 'prevent_type'=> $post_types, );
			$wpw_fp_options = array_merge( $wpw_fp_options, $post_types_enable );
			$udpopt = true;
			
		}
		
		if( $udpopt == true ) { // if any of the settings need to be updated 				
			update_option( 'wpw_fp_options', $wpw_fp_options );
		}
		
		//update plugin version to option 
		update_option( 'wpw_fp_set_option', '1.1.0' );
		
	} //check plugin set option value is 1.0
	
	$wpw_fp_set_option = get_option( 'wpw_fp_set_option' );
	
	if( $wpw_fp_set_option == '1.1.0' ) {
		
		$udpopt = false;
		
		if( !isset( $wpw_fp_options['email_template'] ) ) { //check Email Template is set or not
			$email_template = array( 'email_template'=> 'plain' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $email_template );
			$udpopt = true;
		}
		
		if( $udpopt == true ) { // if any of the settings need to be updated 				
			update_option( 'wpw_fp_options', $wpw_fp_options );
		}
		
		//update plugin version to option 
		update_option( 'wpw_fp_set_option', '1.2.0' );
		
		// future code will be done here
	} //check plugin set option value is 1.1.0
	
	$wpw_fp_set_option = get_option( 'wpw_fp_set_option' );
	
	if( $wpw_fp_set_option == '1.2.0' ) {
		
		$udpopt = false;
		
		if( !isset( $wpw_fp_options['authors_follow_buttons'] ) ) { //check Author Follow Button Text is set or not
			$authors_follow_buttons = array( 
									'authors_follow_buttons'=> array( 
																'follow'	=> __( 'Follow', 'wpwfp' ) . ' {author_name}',
																'following'	=> __( 'Following', 'wpwfp' ) . ' {author_name}',
																'unfollow'	=> __( 'Unfollow', 'wpwfp' ) . ' {author_name}' 
															)
										);
			$wpw_fp_options = array_merge( $wpw_fp_options, $authors_follow_buttons );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['authors_follow_message'] ) ) { //check Author Followers Counter Message is set or not
			$authors_follow_message = array('authors_follow_message'=> '( {followers_count} '.__( 'Followers', 'wpwfp') . ' )' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $authors_follow_message );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['author_trigger_notification'] ) ) { //check Author Notification Events Trigger Emails is set or not
			$author_trigger_notification = array( 'author_trigger_notification'=> array( 'new_post' => '1' ) );
			$wpw_fp_options = array_merge( $wpw_fp_options, $author_trigger_notification );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['author_confirm_email_subject'] ) ) { //check Author Subscription Email Subject is set or not
			$author_confirm_email_subject = array( 'author_confirm_email_subject'=> __( 'Follow', 'wpwfp' ). ' {author_name} - {site_name}' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $author_confirm_email_subject );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['author_confirm_email_body'] ) ) { //check Author Subscription Email Body is set or not
			$author_confirm_email_body = array( 'author_confirm_email_body'=> __( 'Hello', 'wpwfp')."\n\n".__( 'You recently followed the author', 'wpwfp').' "{author_name}". '.__( 'This means you will receive an email when any new post is published by the author', 'wpwfp') .' "{author_name}".'."\n\n".__( 'To activate, click confirm below. If you did not request this, please feel free to disregard this notice!', 'wpwfp')."\n\n".'{subscribe_url}'."\n\n".__( 'Thanks', 'wpwfp' ) );
			$wpw_fp_options = array_merge( $wpw_fp_options, $author_confirm_email_body );
			$udpopt = true;
		}
		
		if( !isset( $wpw_fp_options['author_email_subject'] ) ) { //check Author Subscription Email Subject is set or not
			$author_email_subject = array( 'author_email_subject'=> __( '[New Post]', 'wpwfp').' {post_name}' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $author_email_subject );
			$udpopt = true;
		}
		if( !isset( $wpw_fp_options['author_email_body'] ) ) { //check Author Subscription Email Body is set or not
			$author_email_body = array( 'author_email_body'=> __( 'New post added by the author', 'wpwfp').' "{author_name}":'."\n\n".'{post_name}'."\n\n".'{post_description}'."\n\n".__( 'If you want to see page click below link', 'wpwfp')."\n\n".'{post_link} '.__( 'for', 'wpwfp' ).' {site_link}' );
			$wpw_fp_options = array_merge( $wpw_fp_options, $author_email_body );
			$udpopt = true;
		}
		
		if( $udpopt == true ) { // if any of the settings need to be updated 				
			update_option( 'wpw_fp_options', $wpw_fp_options );
		}
		
		update_option( 'wpw_fp_set_option', '1.4.0' );
		
	} //check plugin set option value is 1.2.0
	
	$wpw_fp_set_option = get_option( 'wpw_fp_set_option' );
	
	if( $wpw_fp_set_option == '1.4.0' ) {
		
		// future code will be done here
		
	} //check plugin set option value is 1.4.0
}

/**
 * Plugin Setup (On Deactivation)
 *
 * Delete  plugin options.
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
function wpw_fp_uninstall() {

	global $wpdb;
	
	//IMP Call of Function
	//Need to call when custom post type is being used in plugin
	flush_rewrite_rules();
	
	//get all options of settings
	$wpw_fp_options = get_option( 'wpw_fp_options' );
	
	if(isset($wpw_fp_options['del_all_options']) && !empty($wpw_fp_options['del_all_options']) && $wpw_fp_options['del_all_options'] == '1') {
		
		//get all page ID(s) which are created when plugin is activating first time
		$pages = get_option('wpw_fp_set_pages');
		wp_delete_post( $pages['subscribe_manage_page'],true );//delete subscribe manage page
		wp_delete_post( $pages['unsubscribe_page'],true );//delete unsubscribe page
		
		delete_option( 'wpw_fp_options' );
		delete_option( 'wpw_fp_set_pages' );
		delete_option( 'wpw_fp_set_option' );
		
		$post_types = array( 'wpwfollowpost', 'wpwfollowpostlogs', 'wpwfollowterm', 'wpwfollowtermlogs' , 'wpwfollowauthor', 'wpwfollowauthorlogs' );
		
		foreach ( $post_types as $post_type ) {
			$args = array( 'post_type' => $post_type, 'post_status' => 'any', 'numberposts' => '-1' );
			$all_posts = get_posts( $args );
			foreach ( $all_posts as $post ) {
				wp_delete_post( $post->ID, true);
			}
		}
	}
}

/**
 * Get Settings From Option Page
 * 
 * Handles to return all settings value
 * 
 * @package Follow My Blog Post
 * @since 1.0.0
 */
function wpw_fp_get_settings() {
	
	$settings = is_array(get_option('wpw_fp_options')) 	? get_option('wpw_fp_options') 	: array();
	
	return $settings;
}

/**
 * Plugin Setup (On First Time Activation)
 *
 * Does the initial setup when plugin is going to activate first time,
 * stest default values for the plugin options.
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
function wpw_fp_default_settings() {
	
	global $wpw_fp_options;
	
	//get values for created pages
	$pages = get_option( 'wpw_fp_set_pages' );
	
	//default for all created pages
	$unsubscribe = $subscribemanage = '';
	
	//get all post type
	$post_types = get_post_types( array( 'public' => true ), 'names' );
	
	foreach ( $post_types as $key => $post_type ) {
		if( $key == 'attachment' ) {
			unset( $post_types[$key] );
		}
	}
	
	//check pages are created or not
	if( !empty( $pages ) ) {
		
		//check if subscribe manage page is created then set to default
		if ( isset( $pages['subscribe_manage_page'] ) ) { $subscribemanage = $pages['subscribe_manage_page'];}
		if ( isset( $pages['unsubscribe_page'] ) ) { $unsubscribe = $pages['unsubscribe_page'];}
	}
	
	$from_email = get_option( 'blogname' ) . ' <' . get_option( 'admin_email' ) . '>';
	
	//set default options values
	$wpw_fp_options = array(
								'subscribe_manage_page'				=> 	$subscribemanage,
								'unsubscribe_page'					=> 	$unsubscribe,
								'follow_buttons'					=>	array(
																					'follow'	=>	__( 'Follow', 'wpwfp' ),
																					'following'	=>	__( 'Following', 'wpwfp' ),
																					'unfollow'	=>	__( 'Unfollow', 'wpwfp' ),
																				),
								'follow_message'					=>	'( {followers_count} ' . __( 'Followers', 'wpwfp' ) . ' )',
								'follow_form_location'				=>	'before_content',
								'follow_form_position'				=>	'left',
								'prevent_type'						=>	$post_types,
								'term_follow_buttons'				=>	array(
																					'follow'	=>	__( 'Follow', 'wpwfp' ) . ' {term_name}',
																					'following'	=>	__( 'Following', 'wpwfp' ) . ' {term_name}',
																					'unfollow'	=>	__( 'Unfollow', 'wpwfp' ) . ' {term_name}',
																				),
								'term_follow_message'				=>	'( {followers_count} ' . __( 'Followers', 'wpwfp' ) . ' )',
								'authors_follow_buttons'			=>	array(
																					'follow'	=>	__( 'Follow', 'wpwfp' ) . ' {author_name}',
																					'following'	=>	__( 'Following', 'wpwfp' ) . ' {author_name}',
																					'unfollow'	=>	__( 'Unfollow', 'wpwfp' ) . ' {author_name}',
																				),
								'authors_follow_message'			=>	'( {followers_count} ' . __( 'Followers', 'wpwfp' ) . ' )',
								//'post_revision'					=>	array( 'title' => '1', 'content' => '1' ),
								'notification_type'					=>	$post_types,
								'recipient_per_email'				=>	'0',
								'post_trigger_notification'			=>	array( 'post_update' => '1' ),
								'term_trigger_notification'			=>	array( 'new_post' => '1' ),
								'author_trigger_notification'		=>	array( 'new_post' => '1' ),
								'email_template'					=>	'plain',
								'from_email'						=>	$from_email,
								'enable_unsubscribe_url'			=>	'1',
								'unsubscribe_message'				=>  __( 'If you want to unsubscribe, click on', 'wpwfp' ) . ' {unsubscribe_url}',
								'email_subject'						=>	sprintf( __( 'Post %s updated at %s', 'wpwfp' ), '{post_name}', '{site_name}' ),
								'email_body'						=>	sprintf( __( 'Post %s updated', 'wpwfp'), '{post_name}' )."\n\n".__( 'If you want to see page click below link', 'wpwfp')."\n\n".'{post_link} '.__( 'for', 'wpwfp' ).' {site_link}',
								'confirm_email_subject'				=>	__( 'Follow', 'wpwfp' ) . ' {post_name} - {site_name}',
								'confirm_email_body'				=>	__('Hello', 'wpwfp') ."\n\n".__( 'You recently followed below blog post. This means you will receive an email when post is updated.', 'wpwfp') ."\n\n".__( 'Blog Post URL', 'wpwfp').': {post_link}'."\n\n".__( 'To activate, click confirm below. If you did not request this, please feel free to disregard this notice!', 'wpwfp')."\n\n".'{subscribe_url}'."\n\n".__( 'Thanks', 'wpwfp'),
								'comment_email_subject'				=>	sprintf( __( 'New comment on %s by %s', 'wpwfp' ), '"{post_name}"', '{user_name}' ),
								'comment_email_body'				=>	sprintf( __( 'New comment added on the post %s by %s, see below :', 'wpwfp' ), '"{post_name}"', '{user_name}')."\n\n".'{comment_text}',
								'term_email_subject'				=>	__( '[New Post]', 'wpwfp').' {post_name}',
								'term_email_body'					=>	__( 'New post added under the', 'wpwfp').' {taxonomy_name} "{term_name}":'."\n\n".'{post_name}'."\n\n".'{post_description}'."\n\n".__( 'If you want to see page click below link', 'wpwfp')."\n\n".'{post_link} '.__( 'for', 'wpwfp' ).' {site_link}',
								'author_email_subject'				=>	__( '[New Post]', 'wpwfp').' {post_name}',
								'author_email_body'					=>	__( 'New post added by the author', 'wpwfp').' "{author_name}":'."\n\n".'{post_name}'."\n\n".'{post_description}'."\n\n".__( 'If you want to see page click below link', 'wpwfp')."\n\n".'{post_link} '.__( 'for', 'wpwfp' ).' {site_link}',
								'term_confirm_email_subject'		=>	__( 'Follow', 'wpwfp' ). ' {term_name} - {site_name}',
								'term_confirm_email_body'			=>	__( 'Hello', 'wpwfp')."\n\n".__( 'You recently followed the', 'wpwfp').' {taxonomy_name} "{term_name}". '.__( 'This means you will receive an email when any new post is published under the', 'wpwfp') .' {taxonomy_name} "{term_name}".'."\n\n".__( 'To activate, click confirm below. If you did not request this, please feel free to disregard this notice!', 'wpwfp')."\n\n".'{subscribe_url}'."\n\n".__( 'Thanks', 'wpwfp' ),
								'author_confirm_email_subject'		=>	__( 'Follow', 'wpwfp' ). ' {author_name} - {site_name}',
								'author_confirm_email_body'			=>	__( 'Hello', 'wpwfp')."\n\n".__( 'You recently followed the author', 'wpwfp').' "{author_name}". '.__( 'This means you will receive an email when any new post is published by the author', 'wpwfp') .' "{author_name}".'."\n\n".__( 'To activate, click confirm below. If you did not request this, please feel free to disregard this notice!', 'wpwfp')."\n\n".'{subscribe_url}'."\n\n".__( 'Thanks', 'wpwfp' ),
								'unsubscribe_confirm_email_subject'	=>	'[{site_name}] ' . __( 'Please confirm your unsubscription request', 'wpwfp' ),
								'unsubscribe_confirm_email_body'	=>	sprintf(__('%s has received a request to unsubscribe for this email address. To complete your request please click on the link below:','wpwfp'), '{site_name}')."\n\n".'{confirm_url}'."\n\n".__( 'If you did not request this, please feel free to disregard this notice!', 'wpwfp')
							);
	
	// apply filters for default settings
	$wpw_fp_options = apply_filters( 'wpw_fp_default_settings', $wpw_fp_options );
					
	//update default options
	update_option( 'wpw_fp_options', $wpw_fp_options );
	
	//overwrite global variable when option is update
	$wpw_fp_options = wpw_fp_get_settings();
}

/**
 * Start Session
 * 
 * @package Follow My Blog Post
 * @since 1.0.0
 */
function wpw_fp_start_session() {
	
	if( !session_id() ) { 
		session_start();
	}
}
/**
 * Settings Link
 *
 * Adds a settings link to the plugin list.
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
function wpw_fp_plugin_action_links ( $links, $file ) {
	
	static $this_plugin;
	if ( !$this_plugin ) $this_plugin = plugin_basename( __FILE__ );
	if ( $file == $this_plugin ) {
		$settings_link = '<a href="'.add_query_arg( array( 'page' => 'wpw-fp-settings' ), admin_url( 'admin.php') ).'">'.__( 'Settings', 'wpwfp').'</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
//add plugin settings link to plugin listing page
add_filter( 'plugin_action_links', 'wpw_fp_plugin_action_links', 10, 2 );

//global variables
global $wpw_fp_model,$wpw_fp_public,$wpw_fp_admin,
		$wpw_fp_script,$wpw_fp_options,
		$wpw_fp_message,$wpw_fp_shortcode;

//Misc Functions File
require_once( WPW_FP_DIR . '/includes/wpw-fb-misc-functions.php' );

$wpw_fp_options = wpw_fp_get_settings();
		
require_once( WPW_FP_DIR . '/includes/class-wpw-fp-message-stack.php'); // message class, handles the messages after review submission
$wpw_fp_message = new Wpw_Fp_Message_Stack();

//Script Class to add styles and scripts to admin and public side
require_once( WPW_FP_DIR . '/includes/class-wpw-fp-scripts.php' );
$wpw_fp_script = new Wpw_Fp_Scripts();
$wpw_fp_script->add_hooks();

//Register Post Types
require_once( WPW_FP_DIR . '/includes/wpw-fp-post-types.php' );

//Pagination Class
require_once( WPW_FP_DIR . '/includes/class-wpw-fp-pagination-public.php' ); // front end pagination class

//Model class handles most of functionalities related Data in plugin
require_once( WPW_FP_DIR . '/includes/class-wpw-fp-model.php' );
$wpw_fp_model = new Wpw_Fp_Model();

//Shortcodes class for handling shortcodes
require_once( WPW_FP_DIR . '/includes/class-wpw-fp-shortcodes.php' );
$wpw_fp_shortcode = new Wpw_Fp_Shortcodes();
$wpw_fp_shortcode->add_hooks();

//Public Class to handles most of functionalities of public side
require_once( WPW_FP_DIR . '/includes/class-wpw-fp-public.php' );
$wpw_fp_public = new Wpw_Fp_Public();
$wpw_fp_public->add_hooks();

include_once( WPW_FP_META_DIR . '/class-wpw-fp-meta.php' );
include_once( WPW_FP_META_DIR . '/meta-box-settings.php' );

//Admin Pages Class for admin side
require_once( WPW_FP_ADMIN_DIR . '/class-wpw-fp-admin.php' );
$wpw_fp_admin = new Wpw_Fp_Admin();
$wpw_fp_admin->add_hooks();

// loads the Templates Functions file
require_once ( WPW_FP_DIR . '/includes/wpw-fp-template-functions.php' );

//Load Template Hook File
require_once( WPW_FP_DIR . '/includes/wpw-fp-template-hooks.php' );

//add action init for starting a session
add_action( 'init', 'wpw_fp_start_session');