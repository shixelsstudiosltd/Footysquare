<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//include the main class file
require_once ( WPW_FP_META_DIR . '/meta-box-class.php' );

if ( is_admin() ) {
	
global $wpw_fp_message;
	
	/* 
	 * prefix of meta keys, optional
	 * use underscore (_) at the beginning to make keys hidden, for example $prefix = '_ba_';
	 *  you also can make prefix empty to disable it
	 * 
	 */
	$prefix = WPW_FP_META_PREFIX;
	
	/* 
	 * configure your meta box
	 */
	$config = array(
		'id' => 'wpw_fp_follow_me_metabox',					// meta box id, unique per meta box
		'title' => __( 'Follow My Blog Post Settings', 'wpwfp'),		// meta box title
		'pages' => 'all',
		'context' => 'normal',							// where the meta box appear: normal (default), advanced, side; optional
		'priority' => 'high',							// order of meta box: high (default), low; optional
		'fields' => array(),							// list of meta fields (can be added by field arrays)
		'local_images' => false,						// Use local or hosted images (meta box images for add/remove)
	);
	
	/*
	 * Initiate your meta box
	 */
	$wpw_fp_meta_reviews = new Wpw_Fp_Custom_Meta_Box( $config );
	
	// Followers Count
	$wpw_fp_meta_reviews->addFollowersCounter( $prefix . 'followers_count', array( 'name'=> __( 'Followers Count:', 'wpwfp' ), 'desc' => 'Displays the followers count for the this post.', 'wpwfp' ) );
	
	// disable follow me
	$wpw_fp_meta_reviews->addCheckbox( $prefix . 'disable_follow_me', array( 'name'=> __( 'Disable Post Follow Button:', 'wpwfp' ), 'desc' => __( 'Check this box if you want to hide the post follow button for this post. Leave it unchecked if you want to use the default settings from the settings page.', 'wpwfp' ) ) );
	
		
	// disable email notification
	$wpw_fp_meta_reviews->addCheckbox( $prefix . 'disable_email_notification', array( 'name'=> __( 'Disable Email Notification:', 'wpwfp' ), 'desc' => __( 'Check this box if you want to disable all type of Email Notifications for this post. Leave it unchecked if you want to use the default settings from the settings page.', 'wpwfp' ) ) );
	
	// Email Subject
	$wpw_fp_meta_reviews->addText( $prefix . 'email_subject', array( 'validate_func' => 'escape_html', 'name'=> __( 'Edit Post Subscription Email Subject:', 'wpwfp' ), 'desc' => __( 'This is the subject of the email that will be sent to the followers when post is updated. Leave it blank if you want to use the default settings from the settings page.', 'wpwfp').
											'<br /><code>{post_name}</code>- '.__( 'displays the title of the post', 'wpwfp').
											'<br /><code>{site_name}</code>- '.__( 'displays the name of your site', 'wpwfp' ) ) );
	
	// Email body
	$wpw_fp_meta_reviews->addWysiwyg( $prefix . 'email_body', array( 'validate_func' => 'escape_html', 'name'=> __( 'Edit Post Subscription Email Body:', 'wpwfp' ),'desc' => __( 'This is the body, main content of the email that will be sent to the followers when post is updated. Leave it blank if you want to use the default settings from the settings page.', 'wpwfp').
											'<br /><code>{post_name}</code>- '.__( 'displays the title of the post', 'wpwfp' ).
											'<br /><code>{post_link}</code>- '.__( 'displays the post title with link', 'wpwfp' ).
											'<br /><code>{site_name}</code>- '.__( 'displays the name of your site', 'wpwfp' ).
											'<br /><code>{site_link}</code>- '.__( 'displays the site name with link', 'wpwfp' ) ) );
	
	// Term Email Subject
	$wpw_fp_meta_reviews->addText( $prefix . 'term_email_subject', array( 'validate_func' => 'escape_html', 'name'=> __( 'New Post Term Subscription Email Subject:', 'wpwfp' ), 'desc' => __( 'This is the subject of the email that will be sent to the followers when new post published. Leave it blank if you want to use the default settings from the settings page.', 'wpwfp').
											'<br><code>{post_name}</code>- '.__( 'displays the title of the post', 'wpwfp').
											'<br><code>{site_name}</code>- '.__( 'displays the name of your site', 'wpwfp' ) ) );
	
	// Term Email body
	$wpw_fp_meta_reviews->addWysiwyg( $prefix . 'term_email_body', array( 'validate_func' => 'escape_html', 'name'=> __( 'New Post Term Subscription Email Body:', 'wpwfp' ), 'desc' => __( 'This is the body, main content of the email that will be sent to the followers when new post published. Leave it blank if you want to use the default settings from the settings page.', 'wpwfp').
											'<br /><code>{post_name}</code> - '.__( 'displays the title of the post', 'wpwfp').
											'<br /><code>{post_description}</code> - '.__( 'displays the description of the post', 'wpwfp').
											'<br /><code>{post_link}</code> - '.__( 'displays the post title with link', 'wpwfp').
											'<br /><code>{term_name}</code> - '.__( 'displays the title of the term', 'wpwfp').
											'<br /><code>{taxonomy_name}</code> - '.__( 'displays the title of the taxonomy', 'wpwfp').
											'<br /><code>{site_name}</code> - '.__( 'displays the name of your site', 'wpwfp').
											'<br /><code>{site_link}</code> - '.__( 'displays the site name with link', 'wpwfp' ) ) );
	
	// Author Email Subject
	$wpw_fp_meta_reviews->addText( $prefix . 'author_email_subject', array( 'validate_func' => 'escape_html', 'name'=> __( 'New Post Author Subscription Email Subject:', 'wpwfp' ), 'desc' => __( 'This is the subject of the email that will be sent to the all followers of the author of that new post when new post published. Leave it blank if you want to use the default settings from the settings page.', 'wpwfp').
											'<br><code>{post_name}</code>- '.__( 'displays the title of the post', 'wpwfp').
											'<br><code>{site_name}</code>- '.__( 'displays the name of your site', 'wpwfp' ) ) );
	
	// Author Email body
	$wpw_fp_meta_reviews->addWysiwyg( $prefix . 'author_email_body', array( 'validate_func' => 'escape_html', 'name'=> __( 'New Post Author Subscription Email Body:', 'wpwfp' ), 'desc' => __( 'This is the body, main content of the email that will be sent to the all followers of the author of that new post when new post published. Leave it blank if you want to use the default settings from the settings page.', 'wpwfp').
											'<br /><code>{post_name}</code> - '.__( 'displays the title of the post', 'wpwfp').
											'<br /><code>{post_description}</code> - '.__( 'displays the description of the post', 'wpwfp').
											'<br /><code>{post_link}</code> - '.__( 'displays the post title with link', 'wpwfp').
											'<br /><code>{author_name}</code> - '.__( 'displays the name of the author', 'wpwfp').
											'<br /><code>{site_name}</code> - '.__( 'displays the name of your site', 'wpwfp').
											'<br /><code>{site_link}</code> - '.__( 'displays the site name with link', 'wpwfp' ) ) );
	
	// Comment Email Subject
	$wpw_fp_meta_reviews->addText( $prefix . 'comment_email_subject', array( 'validate_func' => 'escape_html', 'name'=> __( 'Comment Subscription Email Subject:', 'wpwfp' ), 'desc' => __( 'This is the subject of the email that will be sent to the followers when comment is added and get approved.. Leave it blank if you want to use the default settings from the settings page.', 'wpwfp').
											'<br /><code>{post_name}</code> - '.__( 'displays the title of the post', 'wpwfp').
											'<br /><code>{user_name}</code> - '.__( 'displays the user name<br />', 'wpwfp' ) ) );
	
	// Comment Email body
	$wpw_fp_meta_reviews->addWysiwyg( $prefix . 'comment_email_body', array( 'validate_func' => 'escape_html', 'name'=> __( 'Comment Subscription Email Body:', 'wpwfp' ), 'desc' => __( 'This is the body, main content of the email that will be sent to the followers when comment is added and get approved. Leave it blank if you want to use the default settings from the settings page.', 'wpwfp').
											'<br /><code>{post_name}</code> - '.__( 'displays the title of the post', 'wpwfp').
											'<br /><code>{site_name}</code> - '.__( 'displays the name of your site', 'wpwfp').
											'<br /><code>{user_name}</code> - '.__( 'displays the user name', 'wpwfp').
											'<br /><code>{comment_text}</code> - '.__( 'displays the comment text of the post', 'wpwfp' ) ) );
	
	/*
	 * Don't Forget to Close up the meta box decleration
	 */
	//Finish Meta Box Decleration
	$wpw_fp_meta_reviews->Finish();
}