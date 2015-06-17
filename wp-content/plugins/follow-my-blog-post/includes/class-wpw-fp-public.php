<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Class
 * 
 * Handles all public functionalities of plugin
 * 
 * @package Follow My Blog Post
 * @since 1.0.0
 */
class Wpw_Fp_Public {

	var $model, $message;
	public function __construct () {
				
		global $wpw_fp_model, $wpw_fp_message;
		$this->model = $wpw_fp_model;
		$this->message = $wpw_fp_message;
	}
		
	/**
	 * Process Functionality Of Post Comment
	 *
	 * Handles to process functionality when 
	 * comment post
	 *
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_comment_insert( $comment_ID, $comment_data ) {
		
		global $wpdb, $user_ID, $user_email;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$follow_status = '1';
		
		// get commented post id
		$comment_post_ID = $comment_data->comment_post_ID;
		
		// if user not logged in, then take input email as user_email
		if ( !is_user_logged_in() ) {
			
			$user_email = $comment_data->comment_author_email;
			
			// store this email to session for later use
			$_SESSION['wpw_fp_post_email'] = $user_email;
		}
		
		// args to check if this user_email is subscribed on this commented post
		$args = array( 
						'post_status'	=>	'publish',
						'post_parent' 	=>	$comment_post_ID,
						'posts_per_page'=>	'-1',
						'post_type' 	=>	WPW_FP_POST_TYPE,
						'meta_key'		=>	$prefix.'post_user_email',
						'meta_value'	=>	$user_email
					);
		
		$data = get_posts( $args );
		
		// if not then create new post with subscribe this user email
		if( count( $data ) <= 0 ) {
				
			$follow_post_args = array(
										'post_title'		=>	$user_ID,
										'post_content'		=>	'',
										'post_status'		=>	'publish',
										'post_type'			=>	WPW_FP_POST_TYPE,
										'post_parent'		=>	$comment_post_ID
									);
			if( is_user_logged_in() ) {
				$follow_post_args['author'] = $user_ID;
			}
			$followed_post_id = wp_insert_post( $follow_post_args );
			
			if( $followed_post_id ) {
			
				// update follow status
				update_post_meta( $followed_post_id, $prefix.'follow_status', $follow_status );
				// update post user email
				update_post_meta( $followed_post_id, $prefix.'post_user_email', $user_email );
			}
		
		} else { // if get data then update its meta fields
			update_post_meta( $data[0]->ID, $prefix.'follow_status', $follow_status );
			// update post user email
			update_post_meta( $data[0]->ID, $prefix.'post_user_email', $user_email );
				
		}
		
		if( isset( $comment_data->comment_approved ) && $comment_data->comment_approved == '1' ) {
			
			// if status is approved, then send email and create log
			$this->model->wpw_fp_create_comments( $comment_data );
		}
	}
	
	/**
	 * Send comment subscription email when comment approved by admin
	 *
	 * Handles to send comment subscription email when comment approved by admin
	 *
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_comment_unapproved_to_approved ( $comment_data ){
		
		// if status is approved, then send email and create log
		$this->model->wpw_fp_create_comments( $comment_data );
		
	}
	
	/**
	 * Check If Clicked On Unsubscribe URL
	 * 
	 * Handles to unsubscribe users to the post
	 * by clicking on unsubscribe url
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_email_unsubscribe() {
		
		// To prevent global $post object notice/warnings in admin post listing page
		if( is_admin() ) return; 
		
		global $wpw_fp_options, $post;
		
		$prefix = WPW_FP_META_PREFIX;
		
		if( isset( $_GET['wpw_fp_action'] ) && !empty( $_GET['wpw_fp_action'] )
			&& base64_decode( $_GET['wpw_fp_action'] ) == 'unsubscribe'
			&& isset( $_GET['wpw_fp_email'] ) && !empty( $_GET['wpw_fp_email'] ) ) {
			
			$email = base64_decode( $_GET['wpw_fp_email'] );
			$email = rawurldecode( $email );
			
			$all_follows = $this->model->wpw_fp_check_follow_email( $email );
			if( !empty( $all_follows ) ) { // Check email is exist or not
				
				// Check email exist in follow posts
				if( isset( $all_follows['follow_posts'] ) && !empty( $all_follows['follow_posts'] ) ) {
					
					foreach ( $all_follows['follow_posts'] as $follow_post_id ) {
						
						// unsubscribe email from followers list
						update_post_meta( $follow_post_id, $prefix.'follow_status', '0' );
						
					}
				}
				
				// Check email exist in follow terms
				if( isset( $all_follows['follow_terms'] ) && !empty( $all_follows['follow_terms'] ) ) {
						
					foreach ( $all_follows['follow_terms'] as $follow_term_id ) {
						
						// unsubscribe email from followers list
						update_post_meta( $follow_term_id, $prefix.'follow_status', '0' );
						
					}
				}
				
				// Check email exist in follow authors
				if( isset( $all_follows['follow_authors'] ) && !empty( $all_follows['follow_authors'] ) ) {
						
					foreach ( $all_follows['follow_authors'] as $follow_author_id ) {
						
						// unsubscribe email from followers list
						update_post_meta( $follow_author_id, $prefix.'follow_status', '0' );
						
					}
				}
					
				//set session to unsubscribe message
				$this->message->add_session( 'wpw-fp-unsubscribe', __( 'Your email is unsubscribed successfully.', 'wpwfp' ), 'success' );
				
			} else {
				
				//set message to unsubscribe message
				$this->message->add( 'wpw-fp-unsubscribe', __( 'Sorry, This email id does not exist in our system.', 'wpwfp' ) );
				
			}
			$unsubscribe_page_id = isset( $wpw_fp_options['unsubscribe_page'] ) && !empty( $wpw_fp_options['unsubscribe_page'] ) ? $wpw_fp_options['unsubscribe_page'] : $post->ID; 
			$url = get_permalink( $unsubscribe_page_id );
			wp_redirect( $url );
			exit;
		}
		
	}
	
	/**
	 * Subscribe email by confirmation link
	 * 
	 * Handles to subscribe email by confirmation link
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_email_subscribe() {
		
		// To prevent global $post object notice/warnings in admin post listing page
		if( is_admin() ) return; 
		
		global $wpdb, $user_ID, $user_email, $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		// get current post id
		$current_post_ID = get_the_ID();
		
		// Check Confirmation email from user
		if( isset( $_GET['wpw_fp_action'] ) && !empty( $_GET['wpw_fp_action'] )
			 && base64_decode( $_GET['wpw_fp_action'] ) == 'subscribe'
			&& isset( $_GET['wpw_fp_email'] ) && !empty( $_GET['wpw_fp_email'] )
			&& isset( $_GET['wpw_fp_post_id'] ) && !empty( $_GET['wpw_fp_post_id'] ) ) {
			
			// get post id
			$post_id = base64_decode( $_GET['wpw_fp_post_id'] );
					
			$follow_status = '1';
			$follow_user_email = base64_decode( $_GET['wpw_fp_email'] );
			$follow_user_email = rawurldecode( $follow_user_email );
			
			// args to check user is following this post?
			$args = array( 
							'post_status'	=>	'publish',
							'post_parent' 	=>	$post_id,
							'posts_per_page'=>	'-1',
							'post_type' 	=>	WPW_FP_POST_TYPE,
							'meta_key' 		=>	$prefix.'post_user_email',
							'meta_value' 	=>	$follow_user_email
						);
						
			// get results from args		
			$result = get_posts( $args );
			
			if( empty( $result ) ) {
				
				// args for create custom post type for following user
				$follow_post_args = array(
											'post_title'		=>	$user_ID,
											'post_content'		=>	'',
											'post_status'		=>	'publish',
											'post_type'			=>	WPW_FP_POST_TYPE,
											'post_parent'		=>	$post_id,
										);
				if( is_user_logged_in() ) {
					$follow_post_args['author'] = $user_ID;
				}
				$followed_post_id = wp_insert_post( $follow_post_args );
				
				// if post is created successfully
				if( $followed_post_id ) {
					
					// update follow status
					update_post_meta( $followed_post_id, $prefix.'follow_status', $follow_status );
					
					// update post user email
					update_post_meta( $followed_post_id, $prefix.'post_user_email', $follow_user_email );
					
					if( !empty( $follow_user_email ) ) {
						
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $post_id, __( 'Your email is subscribed successfully.', 'wpwfp' ), 'success' );
					}
				}
			} else if( count( $result ) > 0 ) {
				
				// update follow status
				update_post_meta( $result[0]->ID, $prefix.'follow_status', $follow_status );
				
				// update post user email
				update_post_meta( $result[0]->ID, $prefix.'post_user_email', $follow_user_email );
			
				//set session to subscribe message
				$this->message->add_session( 'wpw-fp-email-subscribe-' . $post_id, __( 'Your email is already subscribed for this post.', 'wpwfp' ) );
				
				$follow_args = array(
										  'ID'      	=> $result[0]->ID,
										  'post_title' 	=> $user_ID,
										  'post_author' => $user_ID
									);
			  	wp_update_post( $follow_args );
					
			}
			wp_redirect( get_permalink( $current_post_ID ) );
			exit;
		}
		
		// Check Confirmation email from user
		if( isset( $_GET['wpw_fp_action'] ) && !empty( $_GET['wpw_fp_action'] )
			&& base64_decode( $_GET['wpw_fp_action'] ) == 'subscribeterm'
			&& isset( $_GET['wpw_fp_email'] ) && !empty( $_GET['wpw_fp_email'] )
			&& isset( $_GET['wpw_fp_term_id'] ) && !empty( $_GET['wpw_fp_term_id'] )
			&& isset( $_GET['wpw_fp_taxonomy'] ) && !empty( $_GET['wpw_fp_taxonomy'] ) ) {
			
			// get posttype
			$posttype = base64_decode( $_GET['wpw_fp_posttype'] );
				
			// get taxonomy
			$taxonomy = base64_decode( $_GET['wpw_fp_taxonomy'] );
				
			// get term id
			$term_id = base64_decode( $_GET['wpw_fp_term_id'] );
					
			$follow_status = '1';
			$follow_user_email = base64_decode( $_GET['wpw_fp_email'] );
			$follow_user_email = rawurldecode( $follow_user_email );
			
			// args to check user is following this post?
			$args = array( 
							'post_status'	=>	'publish',
							'post_type' 	=>	WPW_FP_TERM_POST_TYPE,
							'post_parent' 	=>	$term_id,
							'posts_per_page'=>	'-1',
							'meta_key'		=>	$prefix.'term_user_email',
							'meta_value'	=>	$follow_user_email
						);
						
			// get results from args		
			$result = get_posts( $args );
			
			if( empty( $result ) ) {
				
				// args for create custom post type for following user
				$follow_post_args = array(
											'post_title'		=>	$user_ID,
											'post_content'		=>	'',
											'post_status'		=>	'publish',
											'post_type'			=>	WPW_FP_TERM_POST_TYPE,
											'post_parent' 		=>	$term_id,
										);
				if( is_user_logged_in() ) {
					$follow_post_args['author'] = $user_ID;
				}
				$followed_post_id = wp_insert_post( $follow_post_args );
				
				// if post is created successfully
				if( $followed_post_id ) {
					
					// update follow status
					update_post_meta( $followed_post_id, $prefix.'follow_status', $follow_status );
					
					// update category user email
					update_post_meta( $followed_post_id, $prefix.'term_user_email', $follow_user_email );
					
					// update post type
					update_post_meta( $followed_post_id, $prefix.'post_type', $posttype );
					
					// update taxonomy
					update_post_meta( $followed_post_id, $prefix.'taxonomy_slug', $taxonomy );
					
					if( !empty( $follow_user_email ) ) {
						
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Your email is subscribed successfully.', 'wpwfp' ), 'success' );
					}
				}
			} else if( count( $result ) > 0 ) {
				
				// update follow status
				update_post_meta( $result[0]->ID, $prefix.'follow_status', $follow_status );
				
				// update category user email
				update_post_meta( $result[0]->ID, $prefix.'term_user_email', $follow_user_email );
				
				// update post type
				update_post_meta( $result[0]->ID, $prefix.'post_type', $posttype );
				
				// update taxonomy
				update_post_meta( $result[0]->ID, $prefix.'taxonomy_slug', $taxonomy );
				
				//set session to subscribe message
				$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Your email is already subscribed for this term.', 'wpwfp' ) );
				
				$follow_args = array(
										  'ID'     	 	=> $result[0]->ID,
										  'post_title' 	=> $user_ID,
										  'post_author' => $user_ID
									);
			  	wp_update_post( $follow_args );
					
			}
			wp_redirect( get_permalink( $current_post_ID ) );
			exit;
		}
		
		// Check Confirmation email from user
		if( isset( $_GET['wpw_fp_action'] ) && !empty( $_GET['wpw_fp_action'] )
			&& base64_decode( $_GET['wpw_fp_action'] ) == 'subscribeauthor'
			&& isset( $_GET['wpw_fp_email'] ) && !empty( $_GET['wpw_fp_email'] )
			&& isset( $_GET['wpw_fp_author_id'] ) && !empty( $_GET['wpw_fp_author_id'] )){
				
			// get author id
			$author_id = base64_decode( $_GET['wpw_fp_author_id'] );
					
			$follow_status = '1';
			$follow_user_email = base64_decode( $_GET['wpw_fp_email'] );
			$follow_user_email = rawurldecode( $follow_user_email );
			
			// args to check user is following this post?
			$args = array( 
							'post_status'	=>	'publish',
							'post_type' 	=>	WPW_FP_AUTHOR_POST_TYPE,
							'post_parent' 	=>	$author_id,
							'posts_per_page'=>	'-1',
							'meta_key'		=>	$prefix.'author_user_email',
							'meta_value'	=>	$follow_user_email
						);
						
			// get results from args		
			$result = get_posts( $args );
			
			if( empty( $result ) ) {
				
				// args for create custom post type for following user
				$follow_post_args = array(
											'post_title'		=>	$user_ID,
											'post_content'		=>	'',
											'post_status'		=>	'publish',
											'post_type'			=>	WPW_FP_AUTHOR_POST_TYPE,
											'post_parent' 		=>	$author_id,
										);
				if( is_user_logged_in() ) {
					$follow_post_args['author'] = $user_ID;
				}
				$followed_post_id = wp_insert_post( $follow_post_args );
				
				// if post is created successfully
				if( $followed_post_id ) {
					
					// update follow status
					update_post_meta( $followed_post_id, $prefix.'follow_status', $follow_status );
					
					// update category user email
					update_post_meta( $followed_post_id, $prefix.'author_user_email', $follow_user_email );
					
					if( !empty( $follow_user_email ) ) {
						
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Your email is subscribed successfully.', 'wpwfp' ), 'success' );
					}
				}
			} else if( count( $result ) > 0 ) {
				
				// update follow status
				update_post_meta( $result[0]->ID, $prefix.'follow_status', $follow_status );
				
				// update category user email
				update_post_meta( $result[0]->ID, $prefix.'author_user_email', $follow_user_email );
				
				//set session to subscribe message
				$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Your email is already subscribed for this author.', 'wpwfp' ) );
				
				$follow_args = array(
										  'ID'     	 	=> $result[0]->ID,
										  'post_title' 	=> $user_ID,
										  'post_author' => $user_ID
									);
			  	wp_update_post( $follow_args );
					
			}
			wp_redirect( get_permalink( $current_post_ID ) );
			exit;
		}
		
	}
	
	/**
	 * Display Follow Button with Content
	 *
	 * Handles to display follow button with content
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_follow_content_filter( $content ) {
		
		global $wpw_fp_options;
		
		$html = '';
		
		// check settings for form position
		if( $wpw_fp_options['follow_form_position'] == 'right' ) {
			$follow_pos_class = " wpw_fp_right ";
		} else {
			$follow_pos_class = " wpw_fp_left ";
		}
		
		ob_start();
		do_action( 'wpw_fp_follow_post', array( 'follow_pos_class' => $follow_pos_class ) );
		$html .= ob_get_clean();
		
		// check settings for form location
		if( isset( $wpw_fp_options['follow_form_location'] ) && $wpw_fp_options['follow_form_location'] == 'before_content') {
			
			$content = $html.$content;
			
		} else {
			
			$content = $content.$html;
		}
		return $content;
	}
	
	/**
	 * Display Follow Message
	 * 
	 * Handle to display follow message
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_follow_display_message( $content ) {
		
		global $wpw_fp_options, $post;
		
		$html = '';
		
		if( is_user_logged_in() || ( !isset( $wpw_fp_options['disable_follow_guest'] ) || ( isset( $wpw_fp_options['disable_follow_guest'] ) && empty( $wpw_fp_options['disable_follow_guest'] ) ) ) ) {
			$html .= '<div class="wpw_fp_follow_message">';
			if( $this->message->size( 'wpw-fp-email-subscribe-' . $post->ID ) > 0 ) { //make success message
				$html .= $this->message->output( 'wpw-fp-email-subscribe-' . $post->ID );
			}
			$html .= '</div><!--wpw_fp_follow_message-->';
		}
		
		return $html.$content;
	}
	
	/**
	 * Initial Loaded
	 * 
	 * Handle to add functionality when page loaded
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_follow_loaded() {
		
		// To prevent global $post object notice/warnings in admin post listing page
		if( is_admin() ) return; 
		
		global $post;
  		
		$wpw_fp_pid = isset($post->ID) ? $post->ID : '';
		
		// get enable follow check value from backend
		$enable_follow_check = $this->model->wpw_fp_check_enable_follow( $wpw_fp_pid );
		
		if(  !( $this->model->wpw_fp_has_shortcode( 'wpw_follow_me' ) ) && $enable_follow_check == true ) {
				
			//change the content using filter
			add_filter( 'the_content', array( $this, 'wpw_fp_follow_content_filter' ) );
			
		}
		
		//change the content using filter
		add_filter( 'the_content', array( $this, 'wpw_fp_follow_display_message' ) );
		
	}
	
	/**
	 * Follow Post
	 * 
	 * Handle to follow post
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_follow_post() {
		
		global $user_ID, $user_email, $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$current_post_ID 	= isset( $_POST['currentpostid'] ) && !empty( $_POST['currentpostid'] ) ? $_POST['currentpostid'] : '';
		$post_ID 			= isset( $_POST['postid'] ) && !empty( $_POST['postid'] ) ? $_POST['postid'] : '';
		$follow_status 		= isset( $_POST['status'] ) && !empty( $_POST['status'] ) ? $_POST['status'] : '0';
		$follow_user_email 	= isset( $_POST['email'] ) && !empty( $_POST['email'] ) ? $_POST['email'] : $user_email;
		$email_confirmation = '';
		
		// args to check user is following this post?
		$args = array( 
						'post_status'	=>	'publish',
						'post_parent' 	=>	$post_ID,
						'posts_per_page'=>	'-1',
						'post_type' 	=>	WPW_FP_POST_TYPE,
						'meta_key'		=>	$prefix.'post_user_email',
						'meta_value'	=>	$follow_user_email
					);
					
		// get results from args		
		$result = get_posts( $args );
		
		// Check Require Email confirmation from settings and user is not logged in
		if( empty( $result ) && !empty( $follow_user_email ) && $follow_status == '1'
			 && ( ( isset( $wpw_fp_options['double_opt_in'] ) && $wpw_fp_options['double_opt_in'] == '1' && is_user_logged_in() ) 
			 		|| !isset( $wpw_fp_options['disable_follow_guest'] ) && !is_user_logged_in() ) ) {
			
			$email_args = array(
									'wpw_fp_email'		=> $follow_user_email,
									'post_id'			=> $post_ID,
									'current_post_id'	=> $current_post_ID,
								);
			$this->model->wpw_fp_confirmation_email( $email_args );
			
			//set session to subscribe message
			if( !is_user_logged_in() ) { //if user is not logged in then show message after sending email for confirmation
				$this->message->add_session( 'wpw-fp-email-subscribe-' . $post_ID, __( 'Please check your email inbox to confirm subscribtion.', 'wpwfp' ), 'success' );
			}
			$email_confirmation = 'true';
			echo 'confirm';
			exit;
		}
		
		if( empty( $email_confirmation ) ) {
			
			if( empty( $result ) ) {
				
				// args for create custom post type for following user
				$follow_post_args = array(
											'post_title'		=>	$user_ID,
											'post_content'		=>	'',
											'post_status'		=>	'publish',
											'post_type'			=>	WPW_FP_POST_TYPE,
											'post_parent'		=>	$post_ID,
										);
				if( is_user_logged_in() ) {
					$follow_post_args['author'] = $user_ID;
				}
				$followed_post_id = wp_insert_post( $follow_post_args );
				
				// if post is created successfully
				if( $followed_post_id ) {
					
					// update follow status
					update_post_meta( $followed_post_id, $prefix.'follow_status', $follow_status );
					
					// update post user email
					update_post_meta( $followed_post_id, $prefix.'post_user_email', $follow_user_email );
					
					if( !is_user_logged_in() ) {
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $post_ID, __( 'Your email is subscribed successfully.', 'wpwfp' ), 'success' );
					}
				}
			} else if( count( $result ) > 0 ) {
				
				$exist_follow_status = get_post_meta( $result[0]->ID, $prefix.'follow_status', true );
				
				// update follow status
				update_post_meta( $result[0]->ID, $prefix.'follow_status', $follow_status );
				
				// update post user email
				update_post_meta( $result[0]->ID, $prefix.'post_user_email', $follow_user_email );
				
				if( !is_user_logged_in() ) {
					
					if( $exist_follow_status == '1' ) {
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $post_ID, __( 'Your email is already subscribed for this post.', 'wpwfp' ) );
					} else {
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $post_ID, __( 'Your email is subscribed successfully.', 'wpwfp' ), 'success' );
					}
				} else {
					$follow_args = array(
											  'ID'      	=> $result[0]->ID,
										 	  'post_title' 	=> $user_ID,
											  'post_author' => $user_ID
										);
				  	wp_update_post( $follow_args );
				}
			}
		}
		
		// get user counts
		$numn = wpw_fp_get_post_followers_count( $post_ID );
		
		echo $numn;
		exit;
	}
	
	/**
	 * Follow Category
	 * 
	 * Handle to follow category
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_follow_term() {
		
		global $user_ID, $user_email, $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$current_post_ID 	= isset( $_POST['currentpostid'] ) && !empty( $_POST['currentpostid'] ) ? $_POST['currentpostid'] : '';
		$follow_posttype 	= isset( $_POST['posttype'] ) && !empty( $_POST['posttype'] ) ? $_POST['posttype'] : '';
		$follow_taxonomy	= isset( $_POST['taxonomyslug'] ) && !empty( $_POST['taxonomyslug'] ) ? $_POST['taxonomyslug'] : '';
		$follow_termid 		= isset( $_POST['termid'] ) && !empty( $_POST['termid'] ) ? $_POST['termid'] : '';
		$follow_status 		= isset( $_POST['status'] ) && !empty( $_POST['status'] ) ? $_POST['status'] : '0';
		$follow_user_email 	= isset( $_POST['email'] ) && !empty( $_POST['email'] ) ? $_POST['email'] : $user_email;
		$email_confirmation = '';
		
		// args to check user is following this post?
		$args = array( 
						'post_status'	=>	'publish',
						'post_type' 	=>	WPW_FP_TERM_POST_TYPE,
						'post_parent' 	=>	$follow_termid,
						'posts_per_page'=>	'-1',
						'meta_key'		=>	$prefix.'term_user_email',
						'meta_value'	=>	$follow_user_email
					);
					
		// get results from args		
		$result = get_posts( $args );
		
		// Check Require Email confirmation from settings and user is not logged in
		if( empty( $result ) && !empty( $follow_user_email ) && $follow_status == '1'
			 && ( ( isset( $wpw_fp_options['double_opt_in'] ) && $wpw_fp_options['double_opt_in'] == '1' && is_user_logged_in() ) 
			 		|| !isset( $wpw_fp_options['disable_follow_guest'] ) && !is_user_logged_in() ) ) {
			
			$email_args = array(
									'wpw_fp_email'		=> $follow_user_email,
									'posttype'			=> $follow_posttype,
									'taxonomy'			=> $follow_taxonomy,
									'term_id'			=> $follow_termid,
									'current_post_id'	=> $current_post_ID,
								);
			$this->model->wpw_fp_term_confirmation_email( $email_args );
			
			//set session to subscribe message
			if( !is_user_logged_in() ) { //if user is not logged in then show message after sending email for confirmation
				$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Please check your email inbox to confirm subscribtion.', 'wpwfp' ), 'success' );
			}
			$email_confirmation = 'true';
			echo 'confirm';
			exit;
		}
		
		if( empty( $email_confirmation ) && !empty( $follow_termid ) && !empty( $follow_taxonomy ) ) {
			
			if( empty( $result ) ) {
				
				// args for create custom post type for following user
				$follow_post_args = array(
											'post_title'		=>	$user_ID,
											'post_content'		=>	'',
											'post_status'		=>	'publish',
											'post_type'			=>	WPW_FP_TERM_POST_TYPE,
											'post_parent'		=>	$follow_termid,
										);
				if( is_user_logged_in() ) {
					$follow_post_args['author'] = $user_ID;
				}
				$followed_post_id = wp_insert_post( $follow_post_args );
				
				// if post is created successfully
				if( $followed_post_id ) {
					
					// update follow status
					update_post_meta( $followed_post_id, $prefix.'follow_status', $follow_status );
					
					// update category user email
					update_post_meta( $followed_post_id, $prefix.'term_user_email', $follow_user_email );
					
					// update post type
					update_post_meta( $followed_post_id, $prefix.'post_type', $follow_posttype );
					
					// update taxonomy slug
					update_post_meta( $followed_post_id, $prefix.'taxonomy_slug', $follow_taxonomy );
					
					if( !is_user_logged_in() ) {
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Your email is subscribed successfully.', 'wpwfp' ), 'success' );
					}
				}
			} else if( count( $result ) > 0 ) {
				
				$exist_follow_status = get_post_meta( $result[0]->ID, $prefix.'follow_status', true );
				
				// update follow status
				update_post_meta( $result[0]->ID, $prefix.'follow_status', $follow_status );
				
				// update category user email
				update_post_meta( $result[0]->ID, $prefix.'term_user_email', $follow_user_email );
				
				// update post type
				update_post_meta( $result[0]->ID, $prefix.'post_type', $follow_posttype );
				
				// update taxonomy slug
				update_post_meta( $result[0]->ID, $prefix.'taxonomy_slug', $follow_taxonomy );
				
				if( !is_user_logged_in() ) {
					
					if( $exist_follow_status == '1' ) {
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' .$current_post_ID, __( 'Your email is already subscribed for this term.', 'wpwfp' ) );
					} else {
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Your email is subscribed successfully.', 'wpwfp' ), 'success' );
					}
				} else {
					$follow_args = array(
											  'ID'      	=> $result[0]->ID,
										  	  'post_title' 	=> $user_ID,
											  'post_author' => $user_ID
										);
				  	wp_update_post( $follow_args );
				}
			}
		}
		
		// get user counts
		$numn = wpw_fp_get_term_followers_count( $follow_termid );
		
		echo $numn;
		exit;
	}
	
	/**
	 * Follow Author
	 * 
	 * Handle to follow Author
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	public function wpw_fp_follow_author() {
		
		global $user_ID, $user_email, $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$current_post_ID 	= isset( $_POST['currentpostid'] ) && !empty( $_POST['currentpostid'] ) ? $_POST['currentpostid'] : '';
		$follow_authorid 	= isset( $_POST['authorid'] ) && !empty( $_POST['authorid'] ) ? $_POST['authorid'] : '';
		$follow_status 		= isset( $_POST['status'] ) && !empty( $_POST['status'] ) ? $_POST['status'] : '0';
		$follow_user_email 	= isset( $_POST['email'] ) && !empty( $_POST['email'] ) ? $_POST['email'] : $user_email;
		$email_confirmation = '';
		
		// args to check user is following this post?
		$args = array( 
						'post_status'	=>	'publish',
						'post_type' 	=>	WPW_FP_AUTHOR_POST_TYPE,
						'post_parent' 	=>	$follow_authorid,
						'posts_per_page'=>	'-1',
						'meta_key'		=>	$prefix.'author_user_email',
						'meta_value'	=>	$follow_user_email
					);
					
		// get results from args		
		$result = get_posts( $args );
		
		// Check Require Email confirmation from settings and user is not logged in
		if( empty( $result ) && !empty( $follow_user_email ) && $follow_status == '1'
			 && ( ( isset( $wpw_fp_options['double_opt_in'] ) && $wpw_fp_options['double_opt_in'] == '1' && is_user_logged_in() ) 
			 		|| !isset( $wpw_fp_options['disable_follow_guest'] ) && !is_user_logged_in() ) ) {
			
			$email_args = array(
									'wpw_fp_email'		=> $follow_user_email,
									'author_id'			=> $follow_authorid,
									'current_post_id'	=> $current_post_ID,
								);
			$this->model->wpw_fp_author_confirmation_email( $email_args );
			
			//set session to subscribe message
			if( !is_user_logged_in() ) { //if user is not logged in then show message after sending email for confirmation
				$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Please check your email inbox to confirm subscribtion.', 'wpwfp' ), 'success' );
			}
			$email_confirmation = 'true';
			echo 'confirm';
			exit;
		}
		
		if( empty( $email_confirmation ) && !empty( $follow_authorid )) {
			
			if( empty( $result ) ) {
				
				// args for create custom post type for following user
				$follow_post_args = array(
											'post_title'		=>	$user_ID,
											'post_content'		=>	'',
											'post_status'		=>	'publish',
											'post_type'			=>	WPW_FP_AUTHOR_POST_TYPE,
											'post_parent'		=>	$follow_authorid,
										);
				if( is_user_logged_in() ) {
					$follow_post_args['author'] = $user_ID;
				}
				$followed_post_id = wp_insert_post( $follow_post_args );
				
				// if post is created successfully
				if( $followed_post_id ) {
					
					// update follow status
					update_post_meta( $followed_post_id, $prefix.'follow_status', $follow_status );
					
					// update category user email
					update_post_meta( $followed_post_id, $prefix.'author_user_email', $follow_user_email );
					
					if( !is_user_logged_in() ) {
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Your email is subscribed successfully.', 'wpwfp' ), 'success' );
					}
				}
			} else if( count( $result ) > 0 ) {
				
				$exist_follow_status = get_post_meta( $result[0]->ID, $prefix.'follow_status', true );
				
				// update follow status
				update_post_meta( $result[0]->ID, $prefix.'follow_status', $follow_status );
				
				// update category user email
				update_post_meta( $result[0]->ID, $prefix.'author_user_email', $follow_user_email );
				
				if( !is_user_logged_in() ) {
					
					if( $exist_follow_status == '1' ) {
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Your email is already subscribed for this author.', 'wpwfp' ) );
					} else {
						//set session to subscribe message
						$this->message->add_session( 'wpw-fp-email-subscribe-' . $current_post_ID, __( 'Your email is subscribed successfully.', 'wpwfp' ), 'success' );
					}
				} else {
					$follow_args = array(
											  'ID'      	=> $result[0]->ID,
										  	  'post_title' 	=> $user_ID,
											  'post_author' => $user_ID
										);
				  	wp_update_post( $follow_args );
				}
			}
		}
		
		// get user counts
		$numn = wpw_fp_get_author_followers_count( $follow_authorid );
		
		echo $numn;
		exit;
	}
	
	/**
	 * Load Page Template
	 * 
	 * Handles to load page template for follow my blog post pages
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_load_template( $template ) {
		
		global $wpw_fp_options;
		
		$find = array( 'follow-my-blog-post.php' );
		$file = '';
		
		if ( is_page( $wpw_fp_options['subscribe_manage_page'] ) ) { //check it is subscription manage page
			
			$file 	= 'subscribe-manage.php';
			$find[] = $file;
			$find[] = 'follow-my-blog-post/' . $file;
			
		} else if ( is_page( $wpw_fp_options['unsubscribe_page'] ) ) { //check it is unsubscribe page
			
			$file 	= 'unsubscribe.php';
			$find[] = $file;
			$find[] = 'follow-my-blog-post/' . $file;
			
		}
		
		if ( $file ) {
			$template = locate_template( $find );
			if ( ! $template ) $template = wpw_fp_get_templates_dir() . $file;
		}
		
		return $template;
	}
	
	/**
	 * After user registration
	 * 
	 * Handles to save data after user registration
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_user_registration( $user_id ) {
		
		$user = get_user_by( 'id', $user_id );
		
		if( !empty( $user ) ) { // Check user is not exist
			
			$user_email = $user->user_email;
			
			// args to check if this user_email is exist for follow post
			$args = array( 
							'wpw_fp_email' => $user_email,
							'fields' => 'ids'
						);
			
			$follow_posts = $this->model->wpw_fp_get_follow_post_users_data( $args );
			
			foreach ( $follow_posts as $follow_post_id ) {
				
				$follow_args = array(
										  'ID'      	=> $follow_post_id,
										  'post_title' 	=> $user->ID,
										  'post_author' => $user->ID
									);
			  	wp_update_post( $follow_args );
			  	
			}
			
			// args to check if this user_email is exist for follow term
			$args = array( 
							'wpw_fp_email' => $user_email,
							'fields' => 'ids'
						);
			
			$follow_terms = $this->model->wpw_fp_get_follow_term_users_data( $args );
			
			foreach ( $follow_terms as $follow_term_id ) {
				
				$follow_args = array(
										  'ID'      	=> $follow_term_id,
										  'post_title' 	=> $user->ID,
										  'post_author' => $user->ID
									);
			  	wp_update_post( $follow_args );
			  	
			}
			
		}
	}
	
	/**
	 * Send Unsubscribe Confirmation Email
	 * 
	 * Handles to send unsubscribe confirmation email
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_send_unsubscribe_conformation() {
		
		// Check unsubscribe click button
		if( isset( $_POST['wpw_fp_unsubscribe_submit'] ) && !empty( $_POST['wpw_fp_unsubscribe_submit'] ) ) {
			
			// Check unsubscribe email is not empty
			if( isset( $_POST['wpw_fp_unsubscribe_email'] ) && !empty( $_POST['wpw_fp_unsubscribe_email'] ) ) {
			
				// Check unsubscribe email is valid
				if( is_email( $_POST['wpw_fp_unsubscribe_email'] ) ) {
					
					$email = $_POST['wpw_fp_unsubscribe_email'];
					
					$all_follows = $this->model->wpw_fp_check_follow_email( $email );
					if( !empty( $all_follows ) ) { // Check email is exist or not
						
						$this->model->wpw_fp_confirmation_unsubscribe_email( array( 'wpw_fp_email' => $email ) );
						
						//set message to unsubscribe message
						$this->message->add( 'wpw-fp-unsubscribe', __( 'Please check your email inbox to confirm unsubscribtion.', 'wpwfp' ), 'success' );
						
					} else {
						
						//set message to unsubscribe message
						$this->message->add( 'wpw-fp-unsubscribe', __( 'Sorry, This email id does not exist in our system.', 'wpwfp' ) );
						
					}
					
				} else {
					
					//set message to unsubscribe message
					$this->message->add( 'wpw-fp-unsubscribe', __( 'Please enter valid email.', 'wpwfp' ) );
					
				}
			} else {
				
				//set message to unsubscribe message
				$this->message->add( 'wpw-fp-unsubscribe', __( 'Please enter email.', 'wpwfp' ) );
				
			}
		}
	}
	
	/**
	 * AJAX call 
	 * 
	 * Handles to show details of with ajax
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_follow_posts_ajax() {

		if ( is_user_logged_in() ) {
			ob_start();
			//do action to load follow posts html via ajax
			do_action( 'wpw_fp_manage_follow_posts' );
			echo ob_get_clean();
			exit;
		} else {
			return __( 'You have not follow any posts yet.', 'wpwfp' );
		}
	}
	
	/**
	 * AJAX call 
	 * 
	 * Handles to show details of with ajax
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_follow_terms_ajax() {

		if ( is_user_logged_in() ) {
			ob_start();
			//do action to load follow terms html via ajax
			do_action( 'wpw_fp_manage_follow_terms' );
			echo ob_get_clean();
			exit;
		} else {
			return __( 'You have not follow any terms yet.', 'wpwfp' );
		}
	}
	
	/**
	 * AJAX call 
	 * 
	 * Handles to show details of with ajax
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	public function wpw_fp_follow_authors_ajax() {

		if ( is_user_logged_in() ) {
			ob_start();
			//do action to load follow authors html via ajax
			do_action( 'wpw_fp_manage_follow_authors' );
			echo ob_get_clean();
			exit;
		} else {
			return __( 'You have not follow any authors yet.', 'wpwfp' );
		}
	}
	
	/**
	 * AJAX call 
	 * 
	 * Handles to show details of with ajax
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_bulk_action_post() {

		if( isset( $_POST['bulkaction'] ) && $_POST['bulkaction'] == 'delete'
			&& isset( $_POST['ids'] ) && !empty( $_POST['ids'] ) && $_POST['ids'] != ',' ) {
	
			$ids = explode( ',', trim( $_POST['ids'], ',' ) );
			foreach ( $ids as $id ) {
 				$log_ids = $this->model->wpw_fp_get_follow_post_user_logs_data( array( 'logid' => $id, 'fields' => 'ids' ) );
 				foreach ( $log_ids as $log_id ) {
 					wp_delete_post( $log_id, true );
 				}
 				wp_delete_post( $id, true );
 			}
 			ob_start();
			//do action to load follow terms html via ajax
			do_action( 'wpw_fp_manage_follow_posts' );
			echo ob_get_clean();
			exit;
		}
	}
	
	/**
	 * AJAX call 
	 * 
	 * Handles to show details of with ajax
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_bulk_action_term() {

		if( isset( $_POST['bulkaction'] ) && $_POST['bulkaction'] == 'delete'
			&& isset( $_POST['ids'] ) && !empty( $_POST['ids'] ) && $_POST['ids'] != ',' ) {
	
			$ids = explode( ',', trim( $_POST['ids'], ',' ) );
			
			foreach ( $ids as $id ) {
 				$log_ids = $this->model->wpw_fp_get_follow_term_user_logs_data( array( 'logid' => $id, 'fields' => 'ids' ) );
 				foreach ( $log_ids as $log_id ) {
 					wp_delete_post( $log_id, true );
 				}
 				wp_delete_post( $id, true );
 			}
 			ob_start();
			//do action to load follow terms html via ajax
			do_action( 'wpw_fp_manage_follow_terms' );
			echo ob_get_clean();
			exit;
		}
	}
	
	/**
	 * AJAX call 
	 * 
	 * Handles to show details of with ajax
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	public function wpw_fp_bulk_action_author() {

		if( isset( $_POST['bulkaction'] ) && $_POST['bulkaction'] == 'delete'
			&& isset( $_POST['ids'] ) && !empty( $_POST['ids'] ) && $_POST['ids'] != ',' ) {
	
			$ids = explode( ',', trim( $_POST['ids'], ',' ) );
			
			foreach ( $ids as $id ) {
 				$log_ids = $this->model->wpw_fp_get_follow_author_user_logs_data( array( 'logid' => $id, 'fields' => 'ids' ) );
 				foreach ( $log_ids as $log_id ) {
 					wp_delete_post( $log_id, true );
 				}
 				wp_delete_post( $id, true );
 			}
 			ob_start();
			//do action to load follow authors html via ajax
			do_action( 'wpw_fp_manage_follow_authors' );
			echo ob_get_clean();
			exit;
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function add_hooks() {

		//add action to send comment subscription email when comment inserted and approved
		add_action( 'wp_insert_comment', array( $this, 'wpw_fp_comment_insert' ), 99, 2 );

		//add action to send comment subscription email when comment approved by admin
		add_action( 'comment_unapproved_to_approved', array( $this, 'wpw_fp_comment_unapproved_to_approved' ) );
		
		//wp call
		add_action( 'wp', array( $this, 'wpw_fp_email_unsubscribe' ) );
		
		//wp call
		add_action( 'wp', array( $this, 'wpw_fp_email_subscribe' ) );
		
		//wp call
		add_action( 'wp', array( $this, 'wpw_fp_follow_loaded' ) );
		
		//AJAX call for follow post
		add_action( 'wp_ajax_wpw_fp_follow_post', array( $this, 'wpw_fp_follow_post' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_follow_post', array( $this, 'wpw_fp_follow_post' ) );
		
		//AJAX call for follow category
		add_action( 'wp_ajax_wpw_fp_follow_term', array( $this, 'wpw_fp_follow_term' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_follow_term', array( $this, 'wpw_fp_follow_term' ) );
		
		//AJAX call for follow author
		add_action( 'wp_ajax_wpw_fp_follow_author', array( $this, 'wpw_fp_follow_author' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_follow_author', array( $this, 'wpw_fp_follow_author' ) );
		
		//ajax pagination for follow posts
		add_action( 'wp_ajax_wpw_fp_follow_post_next_page', array( $this, 'wpw_fp_follow_posts_ajax' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_follow_post_next_page', array( $this, 'wpw_fp_follow_posts_ajax' ) );
		
		//ajax pagination for follow terms
		add_action( 'wp_ajax_wpw_fp_follow_term_next_page', array( $this, 'wpw_fp_follow_terms_ajax' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_follow_term_next_page', array( $this, 'wpw_fp_follow_terms_ajax' ) );
		
		//ajax pagination for follow authors
		add_action( 'wp_ajax_wpw_fp_follow_author_next_page', array( $this, 'wpw_fp_follow_authors_ajax' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_follow_author_next_page', array( $this, 'wpw_fp_follow_authors_ajax' ) );
		
		//ajax pagination for follow terms
		add_action( 'wp_ajax_wpw_fp_bulk_action_post', array( $this, 'wpw_fp_bulk_action_post' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_bulk_action_post', array( $this, 'wpw_fp_bulk_action_post' ) );
		
		//ajax pagination for follow terms
		add_action( 'wp_ajax_wpw_fp_bulk_action_term', array( $this, 'wpw_fp_bulk_action_term' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_bulk_action_term', array( $this, 'wpw_fp_bulk_action_term' ) );
		
		//ajax pagination for follow authors
		add_action( 'wp_ajax_wpw_fp_bulk_action_author', array( $this, 'wpw_fp_bulk_action_author' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_bulk_action_author', array( $this, 'wpw_fp_bulk_action_author' ) );
		
		//template loader
		add_filter( 'template_include', array( $this, 'wpw_fp_load_template' ) );
		
		//user registraion
		add_action( 'user_register', array( $this, 'wpw_fp_user_registration' ) );

		//unsubscribe confirmation
		add_action( 'wp', array( $this, 'wpw_fp_send_unsubscribe_conformation' ) );
	}
}
?>