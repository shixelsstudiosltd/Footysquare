<?php
/**
 * Template Hooks
 * 
 * Handles to add all hooks of template
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	/********************** Follow Post Hooks **************************/

	//add_action to load follow post - 5
	add_action( 'wpw_fp_follow_post'			, 'wpw_fp_follow_post'			, 5 );
	
	//add_action to load follow post content - 5
	add_action( 'wpw_fp_follow_post_content'	, 'wpw_fp_follow_post_content'	, 5 );
	
	//add_action to load follow post count box - 10
	add_action( 'wpw_fp_follow_post_count_box'	, 'wpw_fp_follow_post_count_box', 10, 2 );
	
	/********************** Follow Term Hooks **************************/

	//add_action to load follow term - 5
	add_action( 'wpw_fp_follow_term'			, 'wpw_fp_follow_term'			, 5 );
	
	//add_action to load follow term content - 5
	add_action( 'wpw_fp_follow_term_content'	, 'wpw_fp_follow_term_content'	, 5 );
	
	//add_action to load follow term count box - 10
	add_action( 'wpw_fp_follow_term_count_box'	, 'wpw_fp_follow_term_count_box', 10, 2 );
	
	/********************** Follow Author Hooks **************************/

	//add_action to load follow author - 5
	add_action( 'wpw_fp_follow_author'			, 'wpw_fp_follow_author'			, 5 );
	
	//add_action to load follow author content - 5
	add_action( 'wpw_fp_follow_author_content'	, 'wpw_fp_follow_author_content'	, 5 );
	
	//add_action to load follow author count box - 10
	add_action( 'wpw_fp_follow_author_count_box'	, 'wpw_fp_follow_author_count_box', 10, 2 );
	
	/********************** Subscription Manage Hooks **************************/

	//add_action to load subscribe manage content - 5
	add_action( 'wpw_fp_subscribe_manage_content', 'wpw_fp_subscribe_manage_content', 5 );
	
	//add_action to manage follow posts - 5
	add_action( 'wpw_fp_manage_follow_posts', 'wpw_fp_manage_follow_posts', 5 );
	
	//add_action to show follow posts listing table
	add_action( 'wpw_fp_follow_posts_table'	, 'wpw_fp_follow_posts_listing_content', 5, 2 );
	
	//add_action to manage follow terms - 5
	add_action( 'wpw_fp_manage_follow_terms', 'wpw_fp_manage_follow_terms', 5 );
	
	//add_action to show follow terms listing table
	add_action( 'wpw_fp_follow_terms_table'	, 'wpw_fp_follow_terms_listing_content', 5, 2 );
	
	//add_action to manage follow author - 5
	add_action( 'wpw_fp_manage_follow_authors', 'wpw_fp_manage_follow_authors', 5 );
	
	//add_action to show follow authors listing table
	add_action( 'wpw_fp_follow_authors_table'	, 'wpw_fp_follow_authors_listing_content', 5, 2 );
	
	/********************** Unsubscribe Hooks **************************/

	//add_action to load unsubscribe content - 5
	add_action( 'wpw_fp_unsubscribe_content'	, 'wpw_fp_unsubscribe_content'	, 5 );
	
	/********************** Email Template Hooks **************************/

	//add_action to load html email template content - 10
	add_action( 'wpw_fp_default_email_template'	, 'wpw_fp_default_email_template', 10, 2 );
	
?>