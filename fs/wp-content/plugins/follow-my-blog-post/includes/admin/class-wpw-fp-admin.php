<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Handles all admin functionalities of plugin
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
class Wpw_Fp_Admin {

	var $model,$render,$scripts,$message;
	function __construct () {
				
		global $wpw_fp_model,$wpw_fp_render,$wpw_fp_script,
			$wpw_fp_message;
		$this->model = $wpw_fp_model;
		$this->render = $wpw_fp_render;
		$this->scripts = $wpw_fp_script;
		$this->message = $wpw_fp_message;
	}
	
	/**
	 * Register All need admin menu page
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	function wpw_fp_add_admin_menu() {
		
		// follow blog post page
		add_menu_page( __( 'Follow My Blog Post', 'wpwfp' ), __( 'Follow My Blog Post', 'wpwfp' ), wpwfplevel, 'wpw-fp-post', array( $this, 'wpw_fp_list_users' ), WPW_FP_IMG_URL . '/wpweb-menu-icon.png' ); 
		
		add_submenu_page('wpw-fp-post',  __('Follow My Blog Post - Followed Posts', 'wpwfp'), __('Followed Posts', 'wpwfp'), wpwfplevel, 'wpw-fp-post', array( $this, 'wpw_fp_list_users' ) );
		
		// Followed Terms page
		add_submenu_page('wpw-fp-post',  __('Follow My Blog Post - Followed Terms', 'wpwfp'), __('Followed Terms', 'wpwfp'), wpwfplevel, 'wpw-fp-term', array( $this, 'wpw_fp_list_terms' ) );
		
		// Followed Authors page
		add_submenu_page('wpw-fp-post',  __('Follow My Blog Post - Followed Authors', 'wpwfp'), __('Followed Authors', 'wpwfp'), wpwfplevel, 'wpw-fp-author', array( $this, 'wpw_fp_list_authors' ) );
		
		// Send Emails To Followers
		$send_email_page = add_submenu_page('wpw-fp-post',  __('Follow My Blog Post - Send Emails', 'wpwfp'), __('Send Emails', 'wpwfp'), wpwfplevel, 'wpw-fp-send-email', array( $this, 'wpw_fp_send_email_page' ) );
		
		// settings page
		$settings_page = add_submenu_page( 'wpw-fp-post', __( 'Follow My Blog Post - Settings', 'wpwfp' ), __( 'Settings', 'wpwfp' ), wpwfplevel, 'wpw-fp-settings', array( $this, 'wpw_fp_settings_page' ) ); // add setting page
		
		add_action( "admin_head-$settings_page", array( $this->scripts, 'wpw_fp_settings_page_load_scripts' ) );
		
		add_action( "admin_head-$send_email_page", array( $this->scripts, 'wpw_fp_send_email_page_load_scripts' ) );
		
		// add action to add popup html for email templates of follow email
		add_action( "admin_footer-$settings_page", array( $this,'wpw_fp_preview_purchse_receipt_popup' ) );
		
	}
	
	/**
	 * Admin Options Page
	 * 
	 * Handles to display settings for
	 * admin
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 **/
	function wpw_fp_settings_page(){
		
		//admin options page
		include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-plugin-settings.php');
	}
	
	/**
	 * Admin Options Page
	 * 
	 * Handles to send emails to followers
	 * 
	 * 
	 * @package Follow My Blog Post
	 * @since 1.5.0
	 **/
	function wpw_fp_send_email_page(){
		
		//admin options page
		include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-send-email.php');
	}
	
	/**
	 * Pop Up On Editor
	 *
	 * Includes the pop up on the WordPress editor
	 *
	 * @package Follow My Blog Post
	 * @since 1.2.0
	 */
	public function wpw_fp_shortcode_popup() {
		
		include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-admin-popup.php' );
	}
	
	/**
	 * List Users Page
	 * 
	 * List of all following user
	 * display
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 **/
	function wpw_fp_list_users(){
		
		if( isset( $_GET['postid'] ) && isset( $_GET['logid'] ) ) {
			
			//display following post user logs list page
			include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-users-logs-list.php');
			
		} else if( isset( $_GET['postid'] ) ) {
			
			//display following post users list page
			include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-users-list.php');
			
		} else {
			
			//display following posts list page
			include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-list.php');
			
		}
	}
	
	/**
	 * List Terms Page
	 * 
	 * List of all following terms
	 * display
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 **/
	function wpw_fp_list_terms(){
		
		if( isset( $_GET['termid'] ) && isset( $_GET['taxonomy'] ) && isset( $_GET['logid'] ) ) {
			
			//display following term user logs list page
			include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-users-logs-list-terms.php');
			
		} else if( isset( $_GET['termid'] ) && isset( $_GET['taxonomy'] ) ) {
			
			//display following term users list page
			include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-users-list-terms.php');
			
		} else {
			
			//display following terms list page
			include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-list-terms.php');
			
		}
	}
	
	/**
	 * List author Page
	 * 
	 * List of all following authors
	 * display
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4
	 **/
	function wpw_fp_list_authors(){
		
		if( isset( $_GET['authorid'] ) && isset( $_GET['logid'] ) ) {
			
			//display following author user logs list page
			include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-users-logs-list-authors.php');
			
		} else if( isset( $_GET['authorid'] )) {
			
			//display following author users list page
			include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-users-list-authors.php');
			
		} else {
			
			//display following authors list page
			include_once( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-list-authors.php');
			
		}
	}
	
	/**
	 * Register Settings 
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 * 
	 */
	public function wpw_fp_admin_register_settings() {
		
		register_setting( 'wpw_fp_plugin_options', 'wpw_fp_options', array( $this, 'wpw_fp_validate_options' ) );
	}
	
	/**
	 * Validate Settings Options
	 * 
	 * Handle settings page values
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_validate_options( $input ) {
		
		global $wpw_fp_options;
		
		// sanitize text input (strip html tags, and escape characters)
		$input['follow_buttons']					= $this->model->wpw_fp_escape_slashes_deep( $input['follow_buttons'] );
		$input['follow_buttons']['follow']			= isset( $input['follow_buttons']['follow'] ) && !empty( $input['follow_buttons']['follow'] ) && trim( $input['follow_buttons']['follow'] != '' ) ? $input['follow_buttons']['follow'] : __( 'Follow', 'wpwfp' );
		$input['follow_buttons']['following']		= isset( $input['follow_buttons']['following'] ) && !empty( $input['follow_buttons']['following'] ) && trim( $input['follow_buttons']['following'] != '' ) ? $input['follow_buttons']['following'] : __( 'Following', 'wpwfp' );
		$input['follow_buttons']['unfollow']		= isset( $input['follow_buttons']['unfollow'] ) && !empty( $input['follow_buttons']['unfollow'] ) && trim( $input['follow_buttons']['unfollow'] != '' ) ? $input['follow_buttons']['unfollow'] : __( 'Unfollow', 'wpwfp' );
		$input['follow_message']					= $this->model->wpw_fp_escape_slashes_deep( $input['follow_message'] );
		$input['term_follow_buttons']				= $this->model->wpw_fp_escape_slashes_deep( $input['term_follow_buttons'] );
		$input['term_follow_buttons']['follow']		= isset( $input['term_follow_buttons']['follow'] ) && !empty( $input['term_follow_buttons']['follow'] ) && trim( $input['term_follow_buttons']['follow'] != '' ) ? $input['term_follow_buttons']['follow'] : __( 'Follow {term_name}', 'wpwfp' );
		$input['term_follow_buttons']['following']	= isset( $input['term_follow_buttons']['following'] ) && !empty( $input['term_follow_buttons']['following'] ) && trim( $input['term_follow_buttons']['following'] != '' ) ? $input['term_follow_buttons']['following'] : __( 'Following {term_name}', 'wpwfp' );
		$input['term_follow_buttons']['unfollow']	= isset( $input['term_follow_buttons']['unfollow'] ) && !empty( $input['term_follow_buttons']['unfollow'] ) && trim( $input['term_follow_buttons']['unfollow'] != '' ) ? $input['term_follow_buttons']['unfollow'] : __( 'Unfollow {term_name}', 'wpwfp' );
		$input['term_follow_message']				= $this->model->wpw_fp_escape_slashes_deep( $input['term_follow_message'] );
		$input['recipient_per_email']				= $this->model->wpw_fp_escape_slashes_deep( $input['recipient_per_email'], true );
		$input['from_email']						= $this->model->wpw_fp_escape_slashes_deep( $input['from_email'], true );
		$input['unsubscribe_message']				= $this->model->wpw_fp_escape_slashes_deep( $input['unsubscribe_message'], true );
		$input['email_subject']						= $this->model->wpw_fp_escape_slashes_deep( $input['email_subject'] );
		$input['email_body']						= $this->model->wpw_fp_escape_slashes_deep( $input['email_body'] );
		$input['term_email_subject']				= $this->model->wpw_fp_escape_slashes_deep( $input['term_email_subject'] );
		$input['term_email_body']					= $this->model->wpw_fp_escape_slashes_deep( $input['term_email_body'] );
		$input['comment_email_subject']				= $this->model->wpw_fp_escape_slashes_deep( $input['comment_email_subject'] );
		$input['comment_email_body']				= $this->model->wpw_fp_escape_slashes_deep( $input['comment_email_body'] );
		$input['confirm_email_subject']				= $this->model->wpw_fp_escape_slashes_deep( $input['confirm_email_subject'] );
		$input['confirm_email_body']				= $this->model->wpw_fp_escape_slashes_deep( $input['confirm_email_body'] );
		$input['term_confirm_email_subject']		= $this->model->wpw_fp_escape_slashes_deep( $input['term_confirm_email_subject'] );
		$input['term_confirm_email_body']			= $this->model->wpw_fp_escape_slashes_deep( $input['term_confirm_email_body'] );
		$input['unsubscribe_confirm_email_subject']	= $this->model->wpw_fp_escape_slashes_deep( $input['unsubscribe_confirm_email_subject'] );
		$input['unsubscribe_confirm_email_body']	= $this->model->wpw_fp_escape_slashes_deep( $input['unsubscribe_confirm_email_body'] );
		
		//set session to set tab selected in settings page
		$selectedtab = isset( $input['selected_tab'] ) ? $input['selected_tab'] : '';
		$this->message->add_session( 'wpw-fp-selected-tab', strtolower( $selectedtab ) );
		
		// apply filters for validate settings
		$input = apply_filters( 'wpw_fp_validate_settings', $input, $wpw_fp_options );
		
		//filter to save all settings to database
		return $input;
	}
	
	/**
	 * Bulk actions
	 * 
	 * Handles bulk action functinalities
	 * for follow post
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	function wpw_fp_process_bulk_actions() {
		
		// Code for followed post
		if( ( isset( $_GET['action'] ) || isset( $_GET['action2'] ) )
			&& ( isset( $_GET['page'] ) && $_GET['page'] == 'wpw-fp-post' )
			&& ( isset( $_GET['post'] ) || isset( $_GET['user'] ) || isset( $_GET['userlog'] ) ) ) {
			
			// check if we get user OR get userlogs 
			if( isset( $_GET['post'] ) ) {
				$action_on_id = $_GET['post'];
			} else if( isset( $_GET['user'] ) ) {
				$action_on_id = $_GET['user'];
			} else {
				$action_on_id = $_GET['userlog'];
			}
			
			// check if we dont get array of IDs
			if( !is_array( $action_on_id ) ) {
				$action_on_id = array( $action_on_id );
			}
			
			// redirect string for userlist page
			$newstr = add_query_arg( array( 'userlog' 	=> false, 
											'post' 		=> false, 
											'user'		=> false, 
											'action' 	=> false, 
											'action2'	=> false ) 
										);
			
			//if there is multiple checkboxes are checked then call delete in loop
			foreach ( $action_on_id as $id ) {
				
				//parameters for delete function
				$args = array ( 'id' => $id );
				
				if( ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) ||
					( isset( $_GET['action2'] ) && $_GET['action2'] == 'delete' ) ) {
					
					if( isset( $_GET['post'] ) ) {
						$args['parent_id'] = $_GET['post'];
						//delete record from database
						$this->model->wpw_fp_bulk_follow_post_delete( $args );
					} else {
						//delete record from database
						$this->model->wpw_fp_bulk_delete( $args );
					}
					$newstr = add_query_arg( array(	'message' => '3' ), $newstr );
					
				} else if( ( isset( $_GET['action'] ) && $_GET['action'] == 'subscribe' ) ||
						( isset( $_GET['action2'] ) && $_GET['action2'] == 'subscribe' ) ) {
					
					//subscribe users
					$this->model->wpw_fp_bulk_subscribe( $args );
					
					$newstr = add_query_arg( array( 'message' => '1' ), $newstr );
					
				} else if( ( isset( $_GET['action'] ) && $_GET['action'] == 'unsubscribe' ) ||
						( isset( $_GET['action2'] ) && $_GET['action2'] == 'unsubscribe') ) {
					
					//unsubscribe users
					$this->model->wpw_fp_bulk_unsubscribe( $args );
					
					$newstr = add_query_arg( array( 'message' => '2' ), $newstr );
				}
			}
			
			wp_redirect( $newstr );
			exit;
		}
		
		// Code for followed term
		if( ( isset( $_GET['action'] ) || isset( $_GET['action2'] ) )
			&& ( isset( $_GET['page'] ) && $_GET['page'] == 'wpw-fp-term' )
			&& ( isset( $_GET['term'] ) || isset( $_GET['user'] ) || isset( $_GET['userlog'] ) ) ) {
			
			// check if we get user OR get userlogs 
			if( isset( $_GET['term'] ) ) {
				$action_on_id = $_GET['term'];
			} else if( isset( $_GET['user'] ) ) {
				$action_on_id = $_GET['user'];
			} else {
				$action_on_id = $_GET['userlog'];
			}
			
			// check if we dont get array of IDs
			if( !is_array( $action_on_id ) ) {
				$action_on_id = array( $action_on_id );
			}
			
			// redirect string for userlist page
			$newstr = add_query_arg( array( 'userlog' 	=> false, 
											'term' 		=> false, 
											'user'		=> false, 
											'action' 	=> false, 
											'action2'	=> false ) 
										);
			
			//if there is multiple checkboxes are checked then call delete in loop
			foreach ( $action_on_id as $id ) {
				
				//parameters for delete function
				$args = array ( 'id' => $id );
				
				if( ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) ||
					( isset( $_GET['action2'] ) && $_GET['action2'] == 'delete' ) ) {
					
					if( isset( $_GET['term'] ) ) {
						$args['termid'] = $_GET['term'];
						//delete record from database
						$this->model->wpw_fp_bulk_follow_term_delete( $args );
					} else {
						//delete record from database
						$this->model->wpw_fp_bulk_delete( $args );
					}
					$newstr = add_query_arg( array(	'message' => '3' ), $newstr );
					
				} else if( ( isset( $_GET['action'] ) && $_GET['action'] == 'subscribe' ) ||
						( isset( $_GET['action2'] ) && $_GET['action2'] == 'subscribe' ) ) {
					
					//subscribe users
					$this->model->wpw_fp_bulk_subscribe( $args );
					
					$newstr = add_query_arg( array( 'message' => '1' ), $newstr );
					
				} else if( ( isset( $_GET['action'] ) && $_GET['action'] == 'unsubscribe' ) ||
						( isset( $_GET['action2'] ) && $_GET['action2'] == 'unsubscribe') ) {
					
					//unsubscribe users
					$this->model->wpw_fp_bulk_unsubscribe( $args );
					
					$newstr = add_query_arg( array( 'message' => '2' ), $newstr );
				}
			}
			
			wp_redirect( $newstr );
			exit;
		}
		
		
		// Code for followed author
		if( ( isset( $_GET['action'] ) || isset( $_GET['action2'] ) )
			&& ( isset( $_GET['page'] ) && $_GET['page'] == 'wpw-fp-author' )
			&& ( isset( $_GET['author'] ) || isset( $_GET['user'] ) || isset( $_GET['userlog'] ) ) ) {
			
			// check if we get user OR get userlogs 
			if( isset( $_GET['author'] ) ) {
				$action_on_id = $_GET['author'];
			} else if( isset( $_GET['user'] ) ) {
				$action_on_id = $_GET['user'];
			} else {
				$action_on_id = $_GET['userlog'];
			}
			
			// check if we dont get array of IDs
			if( !is_array( $action_on_id ) ) {
				$action_on_id = array( $action_on_id );
			}
			
			// redirect string for userlist page
			$newstr = add_query_arg( array( 'userlog' 	=> false, 
											'author' 	=> false, 
											'user'		=> false, 
											'action' 	=> false, 
											'action2'	=> false ) 
										);
			
			//if there is multiple checkboxes are checked then call delete in loop
			foreach ( $action_on_id as $id ) {
				
				//parameters for delete function
				$args = array ( 'id' => $id );
				
				if( ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) ||
					( isset( $_GET['action2'] ) && $_GET['action2'] == 'delete' ) ) {
					
					if( isset( $_GET['author'] ) ) {
						$args['authorid'] = $_GET['author'];
						//delete record from database
						$this->model->wpw_fp_bulk_follow_author_delete( $args );
					} else {
						//delete record from database
						$this->model->wpw_fp_bulk_delete( $args );
					}
					$newstr = add_query_arg( array(	'message' => '3' ), $newstr );
					
				} else if( ( isset( $_GET['action'] ) && $_GET['action'] == 'subscribe' ) ||
						( isset( $_GET['action2'] ) && $_GET['action2'] == 'subscribe' ) ) {
					
					//subscribe users
					$this->model->wpw_fp_bulk_subscribe( $args );
					
					$newstr = add_query_arg( array( 'message' => '1' ), $newstr );
					
				} else if( ( isset( $_GET['action'] ) && $_GET['action'] == 'unsubscribe' ) ||
						( isset( $_GET['action2'] ) && $_GET['action2'] == 'unsubscribe') ) {
					
					//unsubscribe users
					$this->model->wpw_fp_bulk_unsubscribe( $args );
					
					$newstr = add_query_arg( array( 'message' => '2' ), $newstr );
				}
			}
			
			wp_redirect( $newstr );
			exit;
		}
				
	}
	
	/**
	 * Save Post
	 * 
	 * Handle to check post after save post
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	function wpw_fp_save_post( $post_id, $post ) {
		
		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$post_type_object = get_post_type_object( $post->post_type );
		
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                // Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )        // Check Revision
		|| ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) )        // Check permission
		|| ( $post->post_status != 'publish' ) )  {
		  return $post_id;
		}
	
		// Get post published meta 
		$post_published = get_post_meta( $post_id, $prefix.'post_published', true );
		
		// Get disable email notification meta 
		$disable_email_notification = get_post_meta( $post_id, $prefix.'disable_email_notification', true );
		
		if( ( empty( $post_published ) && $disable_email_notification != '1' )
			|| ( isset( $_POST[ $prefix.'email_notification' ] ) && $_POST[ $prefix.'email_notification' ] == '1' ) ) {
			
			// apply filters for verify send email after post create/update
			$has_send_email = apply_filters( 'wpw_fp_verify_send_email', true, $post_id, $wpw_fp_options, $_POST );
			
			if( $has_send_email ) { // Verified for send email
					
				// Check first time publish
				// Check disable enail notification is checked
				if( empty( $post_published ) && $disable_email_notification != '1' ) {
					
					// if data changed then send email and create term log
					$success_mail = $this->model->wpw_fp_term_create_logs( $post_id );
					$success_author_mail = $this->model->wpw_fp_author_create_logs( $post_id );
					if( $success_mail || $success_author_mail) { 
				
						//redirect to custom url after saving post
						add_filter('redirect_post_location', array( $this, 'wpw_fp_redirect_save_post') );
						update_post_meta( $post_id, $prefix.'post_published', '1' );
					}
				}
				
				// Check email notification from publish meta box
				if( isset( $_POST[ $prefix.'email_notification' ] ) && $_POST[ $prefix.'email_notification' ] == '1' ) {
					
					// if data changed then send email and create log
					$success_mail = $this->model->wpw_fp_create_logs( $post_id );
					if( $success_mail ) { 
				
						//redirect to custom url after saving post
						add_filter('redirect_post_location', array( $this, 'wpw_fp_redirect_save_post') );
					}
				}
			}
		}
	}
	
	/**
	 * Add perameter in url after save post
	 * 
	 * Handle to add perameter in url after save post
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_redirect_save_post( $loc ) {
		
		return add_query_arg( 'wpw-fp-successmail', '1', $loc);
		
	}
	
	/**
	 * Display Success Message
	 * 
	 * Handle to display success message for followers email
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_admin_notices() {
		
		if ( !isset( $_GET['wpw-fp-successmail'] ) ) return false;
		
		if( isset( $_GET['wpw-fp-successmail'] ) && !empty( $_GET['wpw-fp-successmail'] ) ) {
			
			echo '<div class="updated"><p>' . __( 'Email successfully sent to all followers.', 'wpwfp' ) . '</p></div>';
		}
	
	}
	
	/**
	 * Get all terms by taxonomy
	 * 
	 * Handle to get all terms by taxonomy
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_terms() {
		
		$html = '';
		if( isset( $_POST['posttype'] ) && !empty( $_POST['posttype'] )
			&& isset( $_POST['taxonomy'] ) && !empty( $_POST['taxonomy'] ) ) {
			
			$posttype = $_POST['posttype'];
			$taxonomy = $_POST['taxonomy'];
			
			$catargs = array(
								'type'     		=> $posttype,
								'taxonomy' 		=> $taxonomy,
								'order'    		=> 'DESC',
								'hide_empty'	=> '0',
							);
			$categories = get_categories( $catargs );
			foreach ( $categories as $cat ) {
				$html .= '<option value="' . $cat->term_id . '">' . $cat->name . '</option>';
			}
		}
		echo $html;
		exit;
	}
	
	/**
	 * Get all terms by taxonomy
	 * 
	 * Handle to get all terms by taxonomy
	 * 
	 * @package Follow My Blog Post
	 * @since 1.5.0
	 */
	public function wpw_fp_custom_terms() {
		
		$html = '';
		if( isset( $_POST['posttype'] ) && !empty( $_POST['posttype'] )
			&& isset( $_POST['taxonomy'] ) && !empty( $_POST['taxonomy'] ) ) {
			
			//$args['wpw_fp_taxonomy'];
			$data = $this->model->wpw_fp_get_follow_term_data();
			$posttype = $_POST['posttype'];
			$taxonomy = $_POST['taxonomy'];
			
			foreach ($data as $key => $value){
				
				$termdata = get_term_by( 'id', $value['post_parent'], $taxonomy );	
				if( !empty( $termdata->term_id ) && !empty( $termdata->name ) ){
					$html .= '<option value="' . $termdata->term_id . '">' . $termdata->name . '</option>';
				}
			}
		}
		
		echo $html;
		exit;
	}
	
	/**
	 * Get all post name for send email
	 * 
	 * Handle to get all post name
	 * 
	 * @package Follow My Blog Post
	 * @since 1.5.0
	 */
	public function wpw_fp_post_name() {
		
		$args = array();
		$html = '';
		if( isset( $_POST['posttype'] ) && !empty( $_POST['posttype'] ) ) {
			
			$posttype = $_POST['posttype'];
			
			$args['post_type'] = $posttype;
			$data = $this->model->wpw_fp_get_follow_post_data( $args );
			
				foreach ($data as $key => $value){
						
				$html .= '<option value="' . $data[$key]['ID'] . '">' . $data[$key]['post_title'] . '</option>';
						
				}			
		}
		echo $html;
		exit;
	}
	
	/**
	 * Delete all follow post when delete main post / page
	 * 
	 * Handle to delete all follow post when delete main post / page
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_delete_main_post( $pid ) {
		
		$args = array(
						'parent_id'	=> array( $pid )
					);
  		$this->model->wpw_fp_bulk_follow_post_delete( $args );
	  	return true;
	}
	
	/**
	 * Delete all follow term when delete main term
	 * 
	 * Handle to delete all follow term when delete main term
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_delete_main_term( $tid ) {
		
		$args = array(
						'termid' => array( $tid )
					);
  		$this->model->wpw_fp_bulk_follow_term_delete( $args );
	  	return true;
	}
	
	/**
	 * Add Enable Email Notification Meta in publish meta box
	 * 
	 * Handle to add meta in publish box
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_publish_meta() {

		global $post;
    
		$prefix = WPW_FP_META_PREFIX;
		
		if( isset( $post->post_status ) && $post->post_status == 'publish' && $this->model->wpw_fp_check_post_update_notification() ) {
		
		    echo '<div class="misc-pub-section misc-pub-section-last">
			         <span id="timestamp">
			         	<label for="wpw_fp_enable_email_notify">' . __( 'Notify followers for this update:', 'wpwfp' ) . '<input type="checkbox" id="wpw_fp_enable_email_notify" value="1" name="' . $prefix . 'email_notification" /></label>
			         </span>
		         </div>';
		}
	}
	
	/**
	 * Popup for Email Template
	 * 
	 * Handles to load popup for email template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.2.0
	 */
	public function wpw_fp_preview_purchse_receipt_popup() {
		
		global $wpw_fp_options, $current_user;
		
		//get user email template value from settings page
		$message = isset( $wpw_fp_options['email_body'] ) ? $wpw_fp_options['email_body'] : '';
		
		// replace email shortcodes with content
		$message = $this->model->wpw_fp_replace_shortcodes( '1' , $message );
		
		$message = nl2br($message);
		
		// Get all email templates
		$email_templates = $this->model->wpw_fp_email_get_templates();
		
		?>
		<div id="wpw_fp_preview_follow_email">
			<?php
				foreach ( $email_templates as $key => $option ) {
					$key = !empty( $key ) ? $key : 'default';
			?>
				<div class="wpw-fp-preview-<?php echo $key; ?>-popup wpw-fp-preview-popup">
					<?php
						$html = ''; 
						$html .= apply_filters( 'wpw_fp_email_template_css_' . $key, $html );
						$html .= apply_filters( 'wpw_fp_email_template_' . $key, $html, $message, true );
						$html .= '<div class="clear"></div>';
						echo $html;
					?>
				</div>
			<?php } ?>
		</div>
		<?php
	}
	
	/**
	 * Change template design for default email template
	 *
	 * Handles to change template design for default email template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.2.0
	 */
	public function wpw_fp_email_template_default( $html, $message, $unsubscribe = false ) {
		
		ob_start();
		do_action( 'wpw_fp_default_email_template', $message, $unsubscribe );
		$html .= ob_get_clean();
		
		return $html;
	}
	
	/**
	 * Change template design for plain email template
	 *
	 * Handles to change template design for plain email template
	 * 
	 * @package Follow My Blog Post
	 * @since 1.2.0
	 */
	public function wpw_fp_email_template_plain( $html, $message, $unsubscribe = false ) {
		
		global $wpw_fp_options;
		
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
			
			$unsubscribe_message = "\n\r" . "\n\r" . $unsubscribe_message;
			
			$message .= nl2br( $unsubscribe_message );
			
		}
		
		$html .= $message;
		
		return $html;
	}
	
	/**
	 * Search for Authors and return json
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4
	 */
	function wpw_fp_search_authors() {
	
		header( 'Content-Type: application/json; charset=utf-8' );
	
		$term = urldecode( stripslashes( strip_tags( $_GET['term'] ) ) );
	
		if ( empty( $term ) )
			die();
	
		$authors_query = new WP_User_Query( array(
			'fields'			=> 'all',
			'orderby'			=> 'display_name',
			'search'			=> '*' . $term . '*',
			'search_columns'	=> array( 'ID', 'user_login', 'user_email', 'user_nicename' )
		) );
	
		$authors = $authors_query->get_results();
	
		if ( $authors ) {
			foreach ( $authors as $author ) {
				$found_authors[ $author->ID ] = $author->display_name . ' (#' . $author->ID . ' &ndash; ' . sanitize_email( $author->user_email ) . ')';
			}
		}
	
		echo json_encode( $found_authors );
		die();
	}
	/**
	 * AJAX Call
	 * 
	 * Handles to ajax call to store social count to the database
	 * 
	 * @package Follow My Blog Post
	 * @since 1.2.0
	 */
	public function wpw_fp_send_test_email() {
		
		global $wpw_fp_options, $current_user;
		
		$email_template = isset( $_POST['template'] ) && !empty( $_POST['template'] ) ? $_POST['template'] : 'default';
		$email = isset( $current_user->user_email ) ? $current_user->user_email : get_option('admin_email');
		
		//get user email template value from settings page
		$subject = isset( $wpw_fp_options['email_subject'] ) ? $wpw_fp_options['email_subject'] : '';
		
		//get user email template value from settings page
		$message = isset( $wpw_fp_options['email_body'] ) ? $wpw_fp_options['email_body'] : '';
		
		// replace email shortcodes with content
		$subject = $this->model->wpw_fp_replace_shortcodes( '1', $subject );
		
		// replace email shortcodes with content
		$message = $this->model->wpw_fp_replace_shortcodes( '1' , $message );
		
		$this->model->wpw_fp_send_email( $email, $subject, $message, '', $email_template );
		
		echo 'success';
		exit;
	}
	
	/**
	 * call function to send an email
	 *
	 * @package Follow My Blog Post
	 * @since 1.5.0
	 */
	public function wpw_fp_admin_send_email() {
		
		$prefix = WPW_FP_META_PREFIX;
		
		if( isset( $_POST['wpw_fp_send_email_submit'] ) && !empty( $_POST['followed_type'] ) ) {// check if not empty followed_type
		
			$followed_type 				= $_POST['followed_type'];
			$followed_type_post_name	= isset($_POST['followed_type_post_name']) ? $_POST['followed_type_post_name'] : '';
			$followed_type_terms 		= isset($_POST['followed_type_terms']) ? $_POST['followed_type_terms'] : '';
			$wpw_fp_term_id 			= isset($_POST['wpw_fp_term_id']) ? $_POST['wpw_fp_term_id'] : '';
			$followed_type_author 		= isset($_POST['followed_type_author']) ? $_POST['followed_type_author'] : '';
			$email_subject 				= isset($_POST['followed_email_subject']) ? $_POST['followed_email_subject'] : '';
			$email_body 				= isset($_POST['followed_email_body']) ? $_POST['followed_email_body'] : '';
			$email_subject 				= $this->model->wpw_fp_escape_slashes_deep($email_subject);
			$email_body 				= $this->model->wpw_fp_escape_slashes_deep($email_body, true, true);// limited html allowd
			
			$args = array();
			$followers_count = '';
			if( $followed_type == "followed_post" ){//check followed type is post
				
				$args['postid'] = $followed_type_post_name;
				$args['wpw_fp_status']  = 'subscribe';
				
				$data = $this->model->wpw_fp_get_follow_post_users_data( $args );	
				$followers_count = count( $data );
				
				foreach ($data as $key => $value){
					
					$user_email = get_post_meta( $value['ID'], $prefix.'post_user_email', true );
					
					$sentemail = $this->model->wpw_fp_send_email( $user_email, $email_subject, $email_body );
					
				}
			} elseif ( $followed_type == "followed_terms" ) {//check followed type is terms

				$args['termid'] = $wpw_fp_term_id;
				$args['wpw_fp_status']  = 'subscribe';
				
				$data = $this->model->wpw_fp_get_follow_term_users_data( $args );
				$followers_count = count( $data );
				
				foreach ($data as $key => $value){
					
					$user_email = get_post_meta( $value['ID'], $prefix.'term_user_email', true );
					
					$sentemail = $this->model->wpw_fp_send_email( $user_email, $email_subject, $email_body );					
				}
			} elseif ( $followed_type == "followed_authors" ) {//check followed type is authors
				
				$args['authorid'] = $followed_type_author;
				$args['wpw_fp_status']  = 'subscribe';
				$data = $this->model->wpw_fp_get_follow_author_users_data( $args );
				$followers_count = count( $data );
				
				foreach ($data as $key => $value){
					
					$user_email = get_post_meta( $value['ID'], $prefix.'author_user_email', true );
					
					$sentemail = $this->model->wpw_fp_send_email( $user_email, $email_subject, $email_body );
				}
			}
			
			if( !empty( $sentemail ) ) {
				
				//set session to set message for sent email
				$this->message->add_session( 'wpw-fp-sent-mail-message', sprintf( __( 'Mail sent successfully to %s followers.', 'wpwfp' ), $followers_count ) );
				
				wp_redirect( add_query_arg( array( 'page' => 'wpw-fp-send-email' ), admin_url( 'admin.php' ) ) );
				exit;
			}
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//add admin menu pages
		add_action ( 'admin_menu', array( $this, 'wpw_fp_add_admin_menu' ) );
		
		// mark up for popup
		add_action( 'admin_footer-post.php', array( $this,'wpw_fp_shortcode_popup' ) );
		add_action( 'admin_footer-post-new.php', array( $this,'wpw_fp_shortcode_popup' ) );
		
		//register settings in init
		add_action ( 'admin_init', array( $this, 'wpw_fp_admin_register_settings') );
		
		//save post action
		add_action ( 'save_post', array( $this, 'wpw_fp_save_post' ), 10, 2 );
		
		//process bulk subscribe in admin init
		add_action ( 'admin_init', array( $this, 'wpw_fp_process_bulk_actions' ) );
		
		//show admin notices
		add_action( 'admin_notices', array( $this, 'wpw_fp_admin_notices' ) );
		
		//AJAX call for post name
		add_action( 'wp_ajax_wpw_fp_post_name', array( $this, 'wpw_fp_post_name' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_post_name', array( $this, 'wpw_fp_post_name' ) );
		
		//AJAX call for follow category
		add_action( 'wp_ajax_wpw_fp_terms', array( $this, 'wpw_fp_terms' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_terms', array( $this, 'wpw_fp_terms' ) );
		
		//AJAX call for custom term name
		add_action( 'wp_ajax_wpw_fp_custom_terms', array( $this, 'wpw_fp_custom_terms' ) );
		add_action( 'wp_ajax_nopriv_wpw_fp_custom_terms', array( $this, 'wpw_fp_custom_terms' ) );
		
		//delete all follow post when delete main post / page
  		add_action( 'delete_post', array( $this, 'wpw_fp_delete_main_post' ) );
		
		//delete all follow post when delete main term
  		add_action( 'delete_term', array( $this, 'wpw_fp_delete_main_term' ) );
		
  		//add meta in publish box
  		add_action( 'post_submitbox_misc_actions', array( $this, 'wpw_fp_publish_meta' ) );

		// add filter to change template design for default email template
		add_filter( 'wpw_fp_email_template_default', array( $this, 'wpw_fp_email_template_default' ), 10, 3 );

		// add filter to change template design for plain email template
		add_filter( 'wpw_fp_email_template_plain', array( $this, 'wpw_fp_email_template_plain' ), 10, 3 );

		//ajax call to send test email
		add_action( 'wp_ajax_wpw_fp_test_email', array($this, 'wpw_fp_send_test_email'));
		add_action( 'wp_ajax_nopriv_wpw_fp_test_email',array( $this, 'wpw_fp_send_test_email'));
		
		//ajax call to search Authors
		add_action('wp_ajax_wpw_fp_search_authors', array( $this , 'wpw_fp_search_authors' ) );
		add_action('wp_ajax_nopriv_wpw_fp_search_authors', array( $this , 'wpw_fp_search_authors' ) );
		
		//send email
		add_action ( 'admin_init', array( $this, 'wpw_fp_admin_send_email') );
	}
}
?>