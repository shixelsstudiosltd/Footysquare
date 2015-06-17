<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Post Type Functions
 *
 * Handles all custom post types
 * 
 * @package Follow My Blog Post
 * @since 1.0.0 
 */

/**
 * Setup Follow Post PostTypes
 *
 * Registers the follow post posttypes
 * 
 * @package Follow My Blog Post
 * @since 1.0.0 
 */

function wpw_fp_register_post_types() {
	
	//follow post - post type
	$follow_post_labels = array(
						    'name'				=> __('Follow Post','wpwfp'),
						    'singular_name' 	=> __('Follow Post','wpwfp'),
						    'add_new' 			=> __('Add New','wpwfp'),
						    'add_new_item' 		=> __('Add New Follow Post','wpwfp'),
						    'edit_item' 		=> __('Edit Follow Post','wpwfp'),
						    'new_item' 			=> __('New Follow Post','wpwfp'),
						    'all_items' 		=> __('All Follow Posts','wpwfp'),
						    'view_item' 		=> __('View Follow Post','wpwfp'),
						    'search_items' 		=> __('Search Follow Post','wpwfp'),
						    'not_found' 		=> __('No follow posts found','wpwfp'),
						    'not_found_in_trash'=> __('No follow posts found in Trash','wpwfp'),
						    'parent_item_colon' => '',
						    'menu_name' 		=> __('Follow Posts','wpwfp'),
						);
	$follow_post_args = array(
						    'labels' 				=> $follow_post_labels,
						    'public' 				=> false,
						    'query_var' 			=> false,
						    'rewrite' 				=> false,
						    'capability_type' 		=> WPW_FP_POST_TYPE,
						    'hierarchical' 			=> false,
						    'supports' 				=> array( 'title' ),
						);
	
	//register follow posts post type
	register_post_type( WPW_FP_POST_TYPE, $follow_post_args );
	
	//follow post logs - post type
	$follow_post_logs_labels = array(
						    'name'				=> __('Follow Post Logs','wpwfp'),
						    'singular_name' 	=> __('Follow Post Log','wpwfp'),
						    'add_new' 			=> __('Add New','wpwfp'),
						    'add_new_item' 		=> __('Add New Follow Post Log','wpwfp'),
						    'edit_item' 		=> __('Edit Follow Post Log','wpwfp'),
						    'new_item' 			=> __('New Follow Post Log','wpwfp'),
						    'all_items' 		=> __('All Follow Post Logs','wpwfp'),
						    'view_item' 		=> __('View Follow Post Log','wpwfp'),
						    'search_items' 		=> __('Search Follow Post Log','wpwfp'),
						    'not_found' 		=> __('No follow post logs found','wpwfp'),
						    'not_found_in_trash'=> __('No follow post logs found in Trash','wpwfp'),
						    'parent_item_colon' => '',
						    'menu_name' 		=> __('Follow Post Logs','wpwfp'),
						);
	$follow_post_logs_args = array(
						    'labels' 				=> $follow_post_logs_labels,
						    'public' 				=> false,
						    'query_var' 			=> false,
						    'rewrite' 				=> false,
						    'capability_type' 		=> WPW_FP_LOGS_POST_TYPE,
						    'hierarchical' 			=> false,
						    'supports' 				=> array( 'title' )
					 	);
	
	//register follow posts logs post type
	register_post_type( WPW_FP_LOGS_POST_TYPE, $follow_post_logs_args );
	
	//follow term - post type
	$follow_term_labels = array(
						    'name'				=> __('Follow Term','wpwfp'),
						    'singular_name' 	=> __('Follow Term','wpwfp'),
						    'add_new' 			=> __('Add New','wpwfp'),
						    'add_new_item' 		=> __('Add New Follow Term','wpwfp'),
						    'edit_item' 		=> __('Edit Follow Term','wpwfp'),
						    'new_item' 			=> __('New Follow Term','wpwfp'),
						    'all_items' 		=> __('All Follow Terms','wpwfp'),
						    'view_item' 		=> __('View Follow Term','wpwfp'),
						    'search_items' 		=> __('Search Follow Term','wpwfp'),
						    'not_found' 		=> __('No follow terms found','wpwfp'),
						    'not_found_in_trash'=> __('No follow terms found in Trash','wpwfp'),
						    'parent_item_colon' => '',
						    'menu_name' 		=> __('Follow Terms','wpwfp'),
						);
	$follow_term_args = array(
						    'labels' 				=> $follow_term_labels,
						    'public' 				=> false,
						    'query_var' 			=> false,
						    'rewrite' 				=> false,
						    'capability_type' 		=> WPW_FP_TERM_POST_TYPE,
						    'hierarchical' 			=> false,
						    'supports' 				=> array( 'title' )
						);
	
	//register follow term post type
	register_post_type( WPW_FP_TERM_POST_TYPE, $follow_term_args );
	
	//follow term logs - post type
	$follow_term_logs_labels = array(
						    'name'				=> __('Follow Term Logs','wpwfp'),
						    'singular_name' 	=> __('Follow Term Log','wpwfp'),
						    'add_new' 			=> __('Add New','wpwfp'),
						    'add_new_item' 		=> __('Add New Follow Term Log','wpwfp'),
						    'edit_item' 		=> __('Edit Follow Term Log','wpwfp'),
						    'new_item' 			=> __('New Follow Term Log','wpwfp'),
						    'all_items' 		=> __('All Follow Term Logs','wpwfp'),
						    'view_item' 		=> __('View Follow Term Log','wpwfp'),
						    'search_items' 		=> __('Search Follow Term Log','wpwfp'),
						    'not_found' 		=> __('No follow term logs found','wpwfp'),
						    'not_found_in_trash'=> __('No follow term logs found in Trash','wpwfp'),
						    'parent_item_colon' => '',
						    'menu_name' 		=> __('Follow Term Logs','wpwfp'),
						);
	$follow_term_logs_args = array(
						    'labels' 				=> $follow_term_logs_labels,
						    'public' 				=> false,
						    'query_var' 			=> false,
						    'rewrite' 				=> false,
						    'capability_type' 		=> WPW_FP_TERM_LOGS_POST_TYPE,
						    'hierarchical' 			=> false,
						    'supports' 				=> array( 'title' )
					 	);
	
	//register follow terms logs post type
	register_post_type( WPW_FP_TERM_LOGS_POST_TYPE, $follow_term_logs_args );
	
	//follow author - post type
	$follow_author_labels = array(
						    'name'				=> __('Follow Author','wpwfp'),
						    'singular_name' 	=> __('Follow Author','wpwfp'),
						    'add_new' 			=> __('Add New','wpwfp'),
						    'add_new_item' 		=> __('Add New Follow Author','wpwfp'),
						    'edit_item' 		=> __('Edit Follow Author','wpwfp'),
						    'new_item' 			=> __('New Follow Author','wpwfp'),
						    'all_items' 		=> __('All Follow Authors','wpwfp'),
						    'view_item' 		=> __('View Follow Author','wpwfp'),
						    'search_items' 		=> __('Search Follow Author','wpwfp'),
						    'not_found' 		=> __('No follow authors found','wpwfp'),
						    'not_found_in_trash'=> __('No follow authors found in Trash','wpwfp'),
						    'parent_item_colon' => '',
						    'menu_name' 		=> __('Follow Authors','wpwfp'),
						);
	$follow_author_args = array(
						    'labels' 				=> $follow_term_labels,
						    'public' 				=> false,
						    'query_var' 			=> false,
						    'rewrite' 				=> false,
						    'capability_type' 		=> WPW_FP_AUTHOR_POST_TYPE,
						    'hierarchical' 			=> false,
						    'supports' 				=> array( 'title' )
						); 
	
	//register follow author post type
	register_post_type( WPW_FP_AUTHOR_POST_TYPE, $follow_author_args );
	
	//follow Author logs - post type
	$follow_author_logs_labels = array(
						    'name'				=> __('Follow Author Logs','wpwfp'),
						    'singular_name' 	=> __('Follow Author Log','wpwfp'),
						    'add_new' 			=> __('Add New','wpwfp'),
						    'add_new_item' 		=> __('Add New Follow Author Log','wpwfp'),
						    'edit_item' 		=> __('Edit Follow Author Log','wpwfp'),
						    'new_item' 			=> __('New Follow Author Log','wpwfp'),
						    'all_items' 		=> __('All Follow Author Logs','wpwfp'),
						    'view_item' 		=> __('View Follow Author Log','wpwfp'),
						    'search_items' 		=> __('Search Follow Author Log','wpwfp'),
						    'not_found' 		=> __('No follow author logs found','wpwfp'),
						    'not_found_in_trash'=> __('No follow author logs found in Trash','wpwfp'),
						    'parent_item_colon' => '',
						    'menu_name' 		=> __('Follow Author Logs','wpwfp'),
						);
	$follow_author_logs_args = array(
						    'labels' 				=> $follow_author_logs_labels,
						    'public' 				=> false,
						    'query_var' 			=> false,
						    'rewrite' 				=> false,
						    'capability_type' 		=> WPW_FP_AUTHOR_LOGS_POST_TYPE,
						    'hierarchical' 			=> false,
						    'supports' 				=> array( 'title' )
					 	);
	
	//register follow authors logs post type
	register_post_type( WPW_FP_AUTHOR_LOGS_POST_TYPE, $follow_author_logs_args );
	
}
//register custom post type
add_action( 'init', 'wpw_fp_register_post_types', 100 ); // we need to keep priority 100, because we need to execute this init action after all other init action called.
?>