<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Templates Functions
 *
 * Handles to manage templates of plugin
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 *
 */ 


/**
 * Returns the path to the Follow My Blog Post templates directory
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */
function wpw_fp_get_templates_dir() {
	
	return apply_filters( 'wpw_fp_template_dir', WPW_FP_DIR . '/includes/templates/' );
	
}
/**
 * Get template part.
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */ 
function wpw_fp_get_template_part( $slug, $name='' ) {
	
	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/follow-my-blog-post/slug-name.php
	if ( $name )
		$template = locate_template( array ( $slug.'-'.$name.'.php', wpw_fp_get_templates_dir().$slug.'-'.$name.'.php' ) );

	// Get default slug-name.php
	if ( !$template && $name && file_exists( wpw_fp_get_templates_dir().$slug.'-'.$name.'.php' ) )
		$template = wpw_fp_get_templates_dir().$slug.'-'.$name.'.php';

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/follow-my-blog-post/slug.php
	if ( !$template )
		$template = locate_template( array ( $slug.'.php', wpw_fp_get_templates_dir().$slug.'.php' ) );

	if ( $template )
		load_template( $template, false );
}


/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 * 
 */
function wpw_fp_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	
	if ( ! $template_path ) $template_path = WPW_FP_BASENAME . '/';//wpw_fp_get_templates_dir();
	if ( ! $default_path ) $default_path = wpw_fp_get_templates_dir();
	
	// Look within passed path within the theme - this is priority
	
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);
	
	// Get default template
	if ( ! $template )
		$template = $default_path . $template_name;

	// Return what we found
	return apply_filters('wpw_fp_locate_template', $template, $template_name, $template_path);
}

/**
 * Get other templates (e.g. follow my blog post attributes) passing attributes and including the file.
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 * 
 */

function wpw_fp_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	
	if ( $args && is_array($args) )
		extract( $args );

	$located = wpw_fp_locate_template( $template_name, $template_path, $default_path );
	
	do_action( 'wpw_fp_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'wpw_fp_after_template_part', $template_name, $template_path, $located, $args );
}

/************************************ Call Follow Post Functions ***************************/

if( !function_exists( 'wpw_fp_follow_post' ) ) {

	/**
	 * Load Follow Post Template
	 * 
	 * Handles to load follow post template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_follow_post( $args ) {
		
		//follow post template
		wpw_fp_get_template( 'follow-post/follow-post.php', array( 'args' => $args ) );
		
	}
}

if( !function_exists( 'wpw_fp_follow_post_content' ) ) {

	/**
	 * Load Follow Term Content Template
	 * 
	 * Handles to load follow term content template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_follow_post_content( $args ) {
		
		global $wpdb, $user_ID, $user_email, $wpw_fp_options, $wpw_fp_model;
		
		$prefix = WPW_FP_META_PREFIX;
		
		// follow class
		$follow_pos_class 	= isset( $args['follow_pos_class'] ) ? $args['follow_pos_class'] : '';
		
		// post id
		$post_id 			= isset( $args['post_id'] ) && !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
		
		// current post id
		$current_post_id 	= isset( $args['current_post_id'] ) && !empty( $args['current_post_id'] ) ? $args['current_post_id'] : get_the_ID();
		
		// follow text
		$follow_text 		= isset( $args['follow_buttons']['follow'] ) && !empty( $args['follow_buttons']['follow'] ) ? $args['follow_buttons']['follow'] : $wpw_fp_options['follow_buttons']['follow'];
		
		// following text
		$following_text 	= isset( $args['follow_buttons']['following'] ) && !empty( $args['follow_buttons']['following'] ) ? $args['follow_buttons']['following'] : $wpw_fp_options['follow_buttons']['following'];
		
		// unfollow text
		$unfollow_text 		= isset( $args['follow_buttons']['unfollow'] ) && !empty( $args['follow_buttons']['unfollow'] ) ? $args['follow_buttons']['unfollow'] : $wpw_fp_options['follow_buttons']['unfollow'];
		
		// follow message
		$follow_message 	= isset( $args['follow_message'] ) ? $args['follow_message'] : $wpw_fp_options['follow_message'];
		
		$html = '';
		
		$follow_status = '0';
		
		// Check Disable Guest Followes from settings and followsemail is not empty
		if( ( !isset( $wpw_fp_options['disable_follow_guest'] ) || ( isset( $wpw_fp_options['disable_follow_guest'] ) && empty( $wpw_fp_options['disable_follow_guest'] ) ) )
			&& isset( $_POST['followsemail'] ) && !empty( $_POST['followsemail'] ) ) {
				
			$follow_email = $_POST['followsemail'];
		} else {
			$follow_email = $user_email;
		}
		
		// args to check user is following this post?
		$post_args = array( 
							'post_status'	=>	'publish',
							'post_parent' 	=>	$post_id,
							'post_type' 	=>	WPW_FP_POST_TYPE,
							'meta_key'		=>	$prefix.'post_user_email',
							'meta_value'	=>  $follow_email
						);
		
		// get results from args		
		$result = get_posts( $post_args );
		
		// if we get result then user is following this post
		if( count( $result ) > 0 ) {
			$follow_status = get_post_meta( $result[0]->ID, $prefix.'follow_status', true );
		}
		
		// get post type for post id
		$post_type = get_post_type( $current_post_id );
		
		// show follow me form is not on home page
		// OR enable follow me checked in meta for post
		
		if( $follow_status == '1' ) {
			$follow_status 	= '0';
			$follow_class	= 'wpw-fp-following-button';
			$follow_label 	= $following_text;
		} else {
			$follow_status 	= '1';
			$follow_class	= 'wpw-fp-follow-button';
			$follow_label 	= $follow_text;
		}
		
		// Check user is logged in
		if ( is_user_logged_in() ) {
		
			$user_args = array(
									'follow_message'		=> $follow_message,
									'follow_status'			=> $follow_status,
									'follow_label'			=> $follow_label,
									'follow_class'			=> $follow_class,
									'follow_pos_class'		=> $follow_pos_class,
									'post_id'				=> $post_id,
									'current_post_id'		=> $current_post_id,
									'follow_text'			=> $follow_text,
									'following_text'		=> $following_text,
									'unfollow_text'			=> $unfollow_text,
								);
			
			//follow term template for register user
			wpw_fp_get_template( 'follow-post/user.php', $user_args );
			
		} else if( ( !isset( $wpw_fp_options['disable_follow_guest'] ) || ( isset( $wpw_fp_options['disable_follow_guest'] ) && empty( $wpw_fp_options['disable_follow_guest'] ) ) ) ) {
			
			$guest_args = array(
									'follow_message'		=> $follow_message,
									'follow_status'			=> $follow_status,
									'follow_label'			=> $follow_label,
									'follow_class'			=> $follow_class,
									'follow_pos_class'		=> $follow_pos_class,
									'post_id'				=> $post_id,
									'current_post_id'		=> $current_post_id,
									'follow_text'			=> $follow_text,
									'following_text'		=> $following_text,
									'unfollow_text'			=> $unfollow_text,
								);
			
			//follow term template for guest user
			wpw_fp_get_template( 'follow-post/guest.php', $guest_args );
			
		}
	}
}

if( !function_exists( 'wpw_fp_follow_post_count_box' ) ) {

	/**
	 * Load Follow Post Count Box Template
	 * 
	 * Handles to load follow post count box tempate
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_follow_post_count_box( $follow_message, $post_id ) {
		
		// get followers counts
		$numn = wpw_fp_get_post_followers_count( $post_id );
		
		$follow_message = str_replace( '{followers_count}', '<span class="wpw_fp_followers_count">' . $numn . '</span>', $follow_message );
		
		//follow count box template
		wpw_fp_get_template( 'follow-post/follow-count-box.php', array( 'follow_message' => $follow_message ) );
		
	}
}

/************************************ Call Follow Term Functions ***************************/

if( !function_exists( 'wpw_fp_follow_term' ) ) {

	/**
	 * Load Follow Term Template
	 * 
	 * Handles to load follow term template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_follow_term( $args ) {
		
		//follow term template
		wpw_fp_get_template( 'follow-term/follow-term.php', array( 'args' => $args ) );
		
	}
}
if( !function_exists( 'wpw_fp_follow_term_content' ) ) {

	/**
	 * Load Follow Term Content Template
	 * 
	 * Handles to load follow term content template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_follow_term_content( $args ) {
		
		global $wpdb, $user_ID, $user_email, $wpw_fp_options, $wpw_fp_model;
		
		$prefix = WPW_FP_META_PREFIX;
		
		// current post id
		$current_post_id 	= isset( $args['current_post_id'] ) && !empty( $args['current_post_id'] ) ? $args['current_post_id'] : get_the_ID();
		
		// follow post type
		$follow_posttype 	= isset( $args['follow_posttype'] ) && !empty( $args['follow_posttype'] ) ? $args['follow_posttype'] : '';
		
		// follow taxonomy slug
		$follow_taxonomy_slug = isset( $args['follow_taxonomy'] ) && !empty( $args['follow_taxonomy'] ) ? $args['follow_taxonomy'] : '';
		
		// follow term slug
		$follow_term_id 	= isset( $args['follow_term_id'] ) && !empty( $args['follow_term_id'] ) ? $args['follow_term_id'] : '';
		
		// follow text
		$follow_text 		= isset( $args['follow_buttons']['follow'] ) && !empty( $args['follow_buttons']['follow'] ) ? $args['follow_buttons']['follow'] : $wpw_fp_options['term_follow_buttons']['follow'];
		
		// following text
		$following_text 	= isset( $args['follow_buttons']['following'] ) && !empty( $args['follow_buttons']['following'] ) ? $args['follow_buttons']['following'] : $wpw_fp_options['term_follow_buttons']['following'];
		
		// unfollow text
		$unfollow_text 		= isset( $args['follow_buttons']['unfollow'] ) && !empty( $args['follow_buttons']['unfollow'] ) ? $args['follow_buttons']['unfollow'] : $wpw_fp_options['term_follow_buttons']['unfollow'];
		
		// follow message
		$follow_message 	= isset( $args['follow_message'] ) ? $args['follow_message'] : $wpw_fp_options['term_follow_message'];
		
		$html = '';
		
		$follow_status = '0';
		
		// show follow me form is not on home page
		// Check texonomy and termid are not empty
		if( !empty( $follow_taxonomy_slug ) && !empty( $follow_term_id ) ) {
			
			$term_data = get_term_by( 'id', $follow_term_id, $follow_taxonomy_slug );
			
			if( !empty( $term_data ) ) { // Check term data is not empty
			
				$term_label = '';
				if( isset( $term_data->name ) && !empty( $term_data->name ) ) {
					$term_label = $term_data->name;
				}
				$follow_text 	= str_replace( '{term_name}', $term_label, $follow_text );
				$following_text = str_replace( '{term_name}', $term_label, $following_text );
				$unfollow_text 	= str_replace( '{term_name}', $term_label, $unfollow_text );
				
				// Check Disable Guest Followes from settings and followsemail is not empty
				if( ( !isset( $wpw_fp_options['disable_follow_guest'] ) || ( isset( $wpw_fp_options['disable_follow_guest'] ) && empty( $wpw_fp_options['disable_follow_guest'] ) ) )
					&& isset( $_POST['followsemail'] ) && !empty( $_POST['followsemail'] ) ) {
						
					$follow_email = $_POST['followsemail'];
				} else {
					$follow_email = $user_email;
				}
				
				// args to check user is following this post?
				$term_args = array( 
									'post_status'	=>	'publish',
									'post_type' 	=>	WPW_FP_TERM_POST_TYPE,
									'post_parent' 	=>	$follow_term_id,
									'meta_key'		=>	$prefix.'term_user_email',
									'meta_value'	=>  $follow_email
								);
				
				// get results from args		
				$result = get_posts( $term_args );
				
				// if we get result then user is following this post
				if( count( $result ) > 0 ) {
					$follow_status = get_post_meta( $result[0]->ID, $prefix.'follow_status', true );
				}
				
				// get post type for post id
				$post_type = get_post_type( $current_post_id );
				
				if( $follow_status == '1' ) {
					$follow_status 	= '0';
					$follow_class	= 'wpw-fp-following-button';
					$follow_label 	= $following_text;
				} else {
					$follow_status 	= '1';
					$follow_class	= 'wpw-fp-follow-button';
					$follow_label 	= $follow_text;
				}
				
				// Check user is logged in
				if ( is_user_logged_in() ) {
				
					$user_args = array(
											'follow_message'		=> $follow_message,
											'follow_status'			=> $follow_status,
											'follow_label'			=> $follow_label,
											'follow_class'			=> $follow_class,
											'follow_posttype'		=> $follow_posttype,
											'follow_taxonomy_slug'	=> $follow_taxonomy_slug,
											'follow_term_id'		=> $follow_term_id,
											'current_post_id'		=> $current_post_id,
											'follow_text'			=> $follow_text,
											'following_text'		=> $following_text,
											'unfollow_text'			=> $unfollow_text,
										);
					
					//follow term template for register user
					wpw_fp_get_template( 'follow-term/user.php', $user_args );
					
				} else if( ( !isset( $wpw_fp_options['disable_follow_guest'] ) || ( isset( $wpw_fp_options['disable_follow_guest'] ) && empty( $wpw_fp_options['disable_follow_guest'] ) ) ) ) {
					
					$guest_args = array(
											'follow_message'		=> $follow_message,
											'follow_status'			=> $follow_status,
											'follow_label'			=> $follow_label,
											'follow_class'			=> $follow_class,
											'follow_posttype'		=> $follow_posttype,
											'follow_taxonomy_slug'	=> $follow_taxonomy_slug,
											'follow_term_id'		=> $follow_term_id,
											'current_post_id'		=> $current_post_id,
											'follow_text'			=> $follow_text,
											'following_text'		=> $following_text,
											'unfollow_text'			=> $unfollow_text,
										);
					
					//follow term template for guest user
					wpw_fp_get_template( 'follow-term/guest.php', $guest_args );
					
				}
			}
		}		
	}
}

if( !function_exists( 'wpw_fp_follow_term_count_box' ) ) {

	/**
	 * Load Follow Count Box Template
	 * 
	 * Handles to load follow count box tempate
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_follow_term_count_box( $follow_message, $follow_term_id ) {
		
		// get user counts
		$numn = wpw_fp_get_term_followers_count( $follow_term_id );
		
		$follow_message = str_replace( '{followers_count}', '<span class="wpw_fp_followers_count">' . $numn . '</span>', $follow_message );
		
		//follow count box template
		wpw_fp_get_template( 'follow-term/follow-count-box.php', array( 'follow_message' => $follow_message ) );
		
	}
}

/************************************ Call Follow Author Functions ***************************/

if( !function_exists( 'wpw_fp_follow_author' ) ) {

	/**
	 * Load Follow Author Template
	 * 
	 * Handles to load follow author template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	function wpw_fp_follow_author( $args ) {
		
		//follow author template
		wpw_fp_get_template( 'follow-author/follow-author.php', array( 'args' => $args ) );
		
	}
}
if( !function_exists( 'wpw_fp_follow_author_content' ) ) {

	/**
	 * Load Follow Author Content Template
	 * 
	 * Handles to load follow author content template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	function wpw_fp_follow_author_content( $args ) {
		
		global $wpdb, $user_ID, $user_email, $wpw_fp_options, $wpw_fp_model,$post;
		
		$prefix = WPW_FP_META_PREFIX;
		
		// current post id
		$current_post_id 	= isset( $args['current_post_id'] ) && !empty( $args['current_post_id'] ) ? $args['current_post_id'] : get_the_ID();
		
		// current author id
		$author_id 			= isset( $args['author_id'] ) && !empty( $args['author_id'] ) ? $args['author_id'] : $post->post_author;
		
		//set_notification($post_id,5,$author_id);
		
		// follow text
		$follow_text 		= isset( $args['follow_buttons']['follow'] ) && !empty( $args['follow_buttons']['follow'] ) ? $args['follow_buttons']['follow'] : $wpw_fp_options['authors_follow_buttons']['follow'];
		
		// following text
		$following_text 	= isset( $args['follow_buttons']['following'] ) && !empty( $args['follow_buttons']['following'] ) ? $args['follow_buttons']['following'] : $wpw_fp_options['authors_follow_buttons']['following'];
		
		// unfollow text
		$unfollow_text 		= isset( $args['follow_buttons']['unfollow'] ) && !empty( $args['follow_buttons']['unfollow'] ) ? $args['follow_buttons']['unfollow'] : $wpw_fp_options['authors_follow_buttons']['unfollow'];
		
		// follow message
		$follow_message 	= isset( $args['follow_message'] ) ? $args['follow_message'] : $wpw_fp_options['authors_follow_message'];
		
		
		$html = '';
		
		$follow_status = '0';
		
		// show follow me form is not on home page
		// Check authorid are not empty
		if( !empty( $author_id ) ) {
			
			$author_data = get_user_by( 'id', $author_id );
			
			if( !empty( $author_data ) ) { // Check author data is not empty
			
				$author_label = '';
				if( isset( $author_data->display_name ) && !empty( $author_data->display_name ) ) {
					$author_label = $author_data->display_name;
				}
				$follow_text 	= str_replace( '{author_name}', $author_label, $follow_text );
				$following_text = str_replace( '{author_name}', $author_label, $following_text );
				$unfollow_text 	= str_replace( '{author_name}', $author_label, $unfollow_text );
				
				// Check Disable Guest Followes from settings and followsemail is not empty
				if( ( !isset( $wpw_fp_options['disable_follow_guest'] ) || ( isset( $wpw_fp_options['disable_follow_guest'] ) && empty( $wpw_fp_options['disable_follow_guest'] ) ) )
					&& isset( $_POST['followsemail'] ) && !empty( $_POST['followsemail'] ) ) {
						
					$follow_email = $_POST['followsemail'];
					
				} else {
					$follow_email = $user_email;
				}
				
				// args to check user is following this post?
				$author_args = array( 
									'post_status'	=>	'publish',
									'post_type' 	=>	WPW_FP_AUTHOR_POST_TYPE,
									'post_parent' 	=>	$author_id,
									'meta_key'		=>	$prefix.'author_user_email',
									'meta_value'	=>  $follow_email
								);
				
				// get results from args		
				$result = get_posts( $author_args );
				
				// if we get result then user is following this post
				if( count( $result ) > 0 ) {
					$follow_status = get_post_meta( $result[0]->ID, $prefix.'follow_status', true );
				}
				
				// get post type for post id
				$post_type = get_post_type( $current_post_id );
				
				
				if( $follow_status == '1' ) {
					$follow_status 	= '0';
					$follow_class	= 'wpw-fp-following-button';
					$follow_label 	= $following_text;
					
				} else {
				
					$follow_status 	= '1';
					$follow_class	= 'wpw-fp-follow-button';
					$follow_label 	= $follow_text;
				}
				
				// Check user is logged in
				if ( is_user_logged_in() ) {
				
					$user_args = array(
											'follow_message'		=> $follow_message,
											'follow_status'			=> $follow_status,
											'follow_label'			=> $follow_label,
											'follow_class'			=> $follow_class,
											'author_id'				=> $author_id,
											'current_post_id'		=> $current_post_id,
											'follow_text'			=> $follow_text,
											'following_text'		=> $following_text,
											'unfollow_text'			=> $unfollow_text,
										);
					
					//follow author template for register user
					wpw_fp_get_template( 'follow-author/user.php', $user_args );
					
					
				} else if( ( !isset( $wpw_fp_options['disable_follow_guest'] ) || ( isset( $wpw_fp_options['disable_follow_guest'] ) && empty( $wpw_fp_options['disable_follow_guest'] ) ) ) ) {
					
					$guest_args = array(
											'follow_message'		=> $follow_message,
											'follow_status'			=> $follow_status,
											'follow_label'			=> $follow_label,
											'follow_class'			=> $follow_class,
											'author_id'				=> $author_id,
											'current_post_id'		=> $current_post_id,
											'follow_text'			=> $follow_text,
											'following_text'		=> $following_text,
											'unfollow_text'			=> $unfollow_text,
										);
					
					//follow author template for guest user
					wpw_fp_get_template( 'follow-author/guest.php', $guest_args );
					
				}
			}
		}		
	}
}

if( !function_exists( 'wpw_fp_follow_author_count_box' ) ) {

	/**
	 * Load Follow Count Box Template
	 * 
	 * Handles to load follow count box tempate
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	function wpw_fp_follow_author_count_box( $follow_message, $author_id ) {
		
		// get user counts
		$numn = wpw_fp_get_author_followers_count( $author_id );
		
		$follow_message = str_replace( '{followers_count}', '<span class="wpw_fp_followers_count">' . $numn . '</span>', $follow_message );
		
		//follow count box template
		wpw_fp_get_template( 'follow-author/follow-count-box.php', array( 'follow_message' => $follow_message ) );
		
	}
}

/************************************ Call Subscription Manage Page Functions ***************************/

if( !function_exists( 'wpw_fp_subscribe_manage_content' ) ) {

	/**
	 * Load Subscription Manage Page Content Template
	 * 
	 * Handles to load subscription manage page content tempate
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_subscribe_manage_content() {
		
		// manage follow posts
		do_action( 'wpw_fp_manage_follow_posts' );
		
		// manage follow terms
		do_action( 'wpw_fp_manage_follow_terms' );
		
		// manage follow author
		do_action( 'wpw_fp_manage_follow_authors' );
	}
}

if( !function_exists( 'wpw_fp_manage_follow_posts' ) ) {

	/**
	 * Manage Follow Posts Template
	 * 
	 * Handles to manage follow posts tempate
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_manage_follow_posts() {
		
		//manage follow posts template
		wpw_fp_get_template( 'subscribe-manage/follow-posts.php', array() );
	}
}

if( !function_exists( 'wpw_fp_follow_posts_listing_content' ) ) {

	/**
	 * Load Follow Posts Listing Table Template 
	 * 
	 * Handles to load follow posts listing table template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_follow_posts_listing_content( $followposts, $paging ) {
		
		//follow posts template
		wpw_fp_get_template( 'subscribe-manage/follow-posts-listing/follow-posts-listing.php', array(	'followposts'	=> $followposts, 
																										'paging'		=> $paging ) );
	}
}

if( !function_exists( 'wpw_fp_manage_follow_terms' ) ) {

	/**
	 * Manage Follow Terms Template
	 * 
	 * Handles to manage follow terms tempate
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_manage_follow_terms() {
		
		//manage follow terms template
		wpw_fp_get_template( 'subscribe-manage/follow-terms.php', array() );
	}
}

if( !function_exists( 'wpw_fp_follow_terms_listing_content' ) ) {

	/**
	 * Load Follow Terms Listing Table Template 
	 * 
	 * Handles to load follow terms listing table template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_follow_terms_listing_content( $followterms, $paging ) {
		
		//follow terms template
		wpw_fp_get_template( 'subscribe-manage/follow-terms-listing/follow-terms-listing.php', array(	'followterms'	=> $followterms, 
																										'paging'		=> $paging ) );
	}
}

if( !function_exists( 'wpw_fp_manage_follow_authors' ) ) {

	/**
	 * Manage Follow authors Template
	 * 
	 * Handles to manage follow authors tempate
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	function wpw_fp_manage_follow_authors() {
		
		//manage follow authors template
		wpw_fp_get_template( 'subscribe-manage/follow-authors.php', array() );
	}
}

if( !function_exists( 'wpw_fp_follow_authors_listing_content' ) ) {

	/**
	 * Load Follow authors Listing Table Template 
	 * 
	 * Handles to load follow authors listing table template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	function wpw_fp_follow_authors_listing_content( $followauthors, $paging ) {
		
		//follow authors template
		wpw_fp_get_template( 'subscribe-manage/follow-authors-listing/follow-authors-listing.php', array(	'followauthors'	=> $followauthors, 
																											'paging'		=> $paging ) );
	}
}
/************************************ Call Unsubscribe Page Functions ***************************/

if( !function_exists( 'wpw_fp_unsubscribe_content' ) ) {

	/**
	 * Load Unsubscribe Page Content Template
	 * 
	 * Handles to load unsubscribe page content tempate
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_unsubscribe_content() {
		
		//follow count box template
		wpw_fp_get_template( 'unsubscribe/content.php' );
		
	}
}

/************************************ Call Email Template Functions ***************************/

if( !function_exists( 'wpw_fp_default_email_template' ) ) {

	/**
	 * Load Html Email Template Content Template
	 * 
	 * Handles to load html email  content tempate
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_default_email_template( $message, $unsubscribe = false ) {
		
		global $wpw_fp_options;
		
		$unsubscribe_message = '';
		
		// site name with url 
		$site_name = get_bloginfo( 'name' );
		
		// Check Append Unsubscribe URL is enable & unsubscribe page is exist & unsubscribe message is not empty
		if( $unsubscribe && isset( $wpw_fp_options['enable_unsubscribe_url'] ) && $wpw_fp_options['enable_unsubscribe_url'] == '1'
			&& isset( $wpw_fp_options['unsubscribe_page'] ) && !empty( $wpw_fp_options['unsubscribe_page'] )
			&& isset( $wpw_fp_options['unsubscribe_message'] ) && !empty( $wpw_fp_options['unsubscribe_message'] ) ) {
			
			$unsubscribe_message = $wpw_fp_options['unsubscribe_message'];
				
			$url = get_permalink( $wpw_fp_options['unsubscribe_page'] );
			$unsubscribe_url = '<a target="_blank" href="'.$url.'" >'.__( 'Unsubscribe', 'wpwfp' ).'</a>';
		
			$unsubscribe_message = str_replace( '{unsubscribe_url}', $unsubscribe_url, $unsubscribe_message );
			
		}
		
		$html_email_args = array(
										'site_name'				=> $site_name,
										'message'				=> $message,
										'unsubscribe_message'	=> $unsubscribe_message,
									);
		
		//html email template
		wpw_fp_get_template( 'emailtemplate/htmlemail.php', $html_email_args );
		
	}
}
?>