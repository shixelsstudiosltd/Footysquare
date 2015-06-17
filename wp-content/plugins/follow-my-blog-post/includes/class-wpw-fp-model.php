<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Model Class
 *
 * Handles generic plugin functionality.
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
class Wpw_Fp_Model {

	public function __construct () {
				
	}
	
	/**
	 * Escape Tags & Slashes
	 *
	 * Handles escapping the slashes and tags
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
   
	public function wpw_fp_escape_attr($data){
		return esc_attr(stripslashes($data));
	}
	
	/**
	 * Strip Slashes From Array
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
   
	public function wpw_fp_escape_slashes_deep( $data = array(), $flag=false, $limited = false ){
			
		if($flag != true) {
			$data = $this->wpw_fp_nohtml_kses($data);
		} else {
	   
		   if( $limited == true ) {
		    $data = wp_kses_post( $data );
		   }
		   
		  }
		$data = stripslashes_deep($data);
		return $data;
	}
	
	/**
	 * Strip Html Tags 
	 * 
	 * It will sanitize text input (strip html tags, and escape characters)
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_nohtml_kses($data = array()) {
		
		if ( is_array($data) ) {
			
			$data = array_map(array($this,'wpw_fp_nohtml_kses'), $data);
			
		} elseif ( is_string( $data ) ) {
			
			$data = wp_filter_nohtml_kses($data);
		}
		
		return $data;
	}
	
	/**
	 * Get Post data
	 * 
	 * Handles to get post data for
	 * followpost post type
	 * 
	 * @package Follow My Blog Post
 	 * @since 1.0.0
	 */
	public function wpw_fp_get_follow_post_data( $args = array() ) {
		
		// Check if post type selected from filter
		if( isset( $args['post_type'] ) && !empty( $args['post_type'] ) ) {
			$post_types = array( $args['post_type'] );
		} else {
			// get all custom post types
			$post_types = get_post_types( array( 'public' => true ), 'names' );
		}
		
		//check if its custom post types created or not
		$checkpostargs = array( 'post_type' => WPW_FP_POST_TYPE, 'post_status' => 'publish', 'posts_per_page' => '-1' );
		
		//fire query in to table for retriving data
		$result = new WP_Query( $checkpostargs );
		
		if( !empty( $result->posts ) ) {
			foreach ( $result->posts as $key => $value ) {
			
				//if custom post type is created for that post, than store its id
				$postids[] = $value->post_parent;
			}
		}
		
		//if we dont get any id that take it empty
		if( empty( $postids ) ) {
			$postids[] = 0;
		}
		
		$followpostargs = array( 
									'post_type' => $post_types,
									'post__in' => $postids,
									'posts_per_page' => '-1'
								);
		
		//if search is called then retrive searching data
		if( isset( $args['search'] ) ) {
			$followpostargs['s'] = $args['search'];
		}
		
		//fire query in to table for retriving data
		$result = new WP_Query( $followpostargs );
		
		//retrived data is in object format so assign that data to array for listing
		$followpostslist = $this->wpw_fp_object_to_array($result->posts);
		
		// Check if post type counter from filter
		if( isset( $args['count'] ) && !empty( $args['count'] ) ) {
			return count( $followpostslist );
		}
		
		return $followpostslist;
	}
	
	/**
	 * Get Post Users data
	 * 
	 * Handles to get users data for
	 * post type
	 * 
	 * @package Follow My Blog Post
 	 * @since 1.0.0
	 */
	public function wpw_fp_get_follow_post_users_data( $args = array() ) {
		
		$prefix = WPW_FP_META_PREFIX;
		
		$followpostusersargs = array( 
										'post_type' 		=> WPW_FP_POST_TYPE,
										'post_status' 		=> 'publish'
									);
		
		//show how many per page records
		if( isset( $args['posts_per_page'] ) && !empty( $args['posts_per_page'] ) ) {
			$followpostusersargs['posts_per_page'] = $args['posts_per_page'];
		} else {
			$followpostusersargs['posts_per_page'] = '-1';
		}
		
		//show per page records
		if( isset( $args['paged']) && !empty( $args['paged'] ) ) {
			$followpostusersargs['paged'] = $args['paged'];
		}
		
		//if search using post parent
		if( isset( $args['postid'] ) ) {
			$followpostusersargs['post_parent'] = $args['postid'];
		}
		
		//if search using user
		if( isset( $args['author'] ) ) {
			$followpostusersargs['author'] = $args['author'];
		}
		
		//if search is called then retrive searching data
		if( isset( $args['search'] ) ) {
			//$followpostusersargs['s'] = $args['search'];
			$metaargs[] = array(
									'key' 	=> $prefix.'post_user_email',
									'value' => $args['search'],
									'compare' => 'LIKE'
								);
		}
		
		if( isset( $args['wpw_fp_status'] ) && !empty( $args['wpw_fp_status'] ) ) {
			$status = $args['wpw_fp_status'] == 'subscribe' ? '1' : '0';
			$metaargs[] = array(
									'key' 	=> $prefix.'follow_status',
									'value' => $status
								);
		}
		
		if( isset( $args['wpw_fp_email'] ) && !empty( $args['wpw_fp_email'] ) ) {
			$metaargs[] = array(
									'key' 	=> $prefix.'post_user_email',
									'value' => $args['wpw_fp_email']
								);
		}
		
		if( !empty( $metaargs ) ) {
			$followpostusersargs['meta_query'] = $metaargs;
		}
		
		//if returns only id
		if( isset( $args['fields'] ) && !empty( $args['fields'] ) ) {
			$followpostusersargs['fields'] = $args['fields'];
		}
		
		//get order by records
		$followpostusersargs['order'] = 'DESC';
		$followpostusersargs['orderby'] = 'date';
		
		//fire query in to table for retriving data
		$result = new WP_Query( $followpostusersargs );
		
		if( isset( $args['count'] ) && $args['count'] == '1') {
			$followpostuserslist = $result->post_count;	
		}  else {
			//retrived data is in object format so assign that data to array for listing
			$followpostuserslist = $this->wpw_fp_object_to_array($result->posts);
		}
		
		return $followpostuserslist;
	}
	
	/**
	 * Get Post User Logs data
	 * 
	 * Handles to get user logs data for
	 * post type
	 * 
	 * @package Follow My Blog Post
 	 * @since 1.0.0
	 */
	public function wpw_fp_get_follow_post_user_logs_data( $args = array() ) {
		
		$prefix = WPW_FP_META_PREFIX;
		
		$followeduserlogssargs = array(
											'post_type'		=>	WPW_FP_LOGS_POST_TYPE,
											'post_status'	=>	'publish',
											'posts_per_page' => '-1'
										);
		
		//if search using post parent
		if( isset( $args['logid'] ) ) {
			$followeduserlogssargs['post_parent'] = $args['logid'];
		}
		
		//if returns only id
		if( isset( $args['fields'] ) && !empty( $args['fields'] ) ) {
			$followeduserlogssargs['fields'] = $args['fields'];
		}
		
		//if search is called then retrive searching data
		if( isset( $args['search'] ) ) {
			//$followeduserlogssargs['s'] = $args['search'];
			$metaargs[] = array(
									'key' 	=> $prefix.'log_email_data',
									'value' => $args['search'],
									'compare' => 'LIKE'
								);
		}
		
		if( !empty( $metaargs ) ) {
			$followeduserlogssargs['meta_query'] = $metaargs;
		}
		
		//fire query in to table for retriving data
		$result = new WP_Query( $followeduserlogssargs );
		
		//retrived data is in object format so assign that data to array for listing
		$followeduserlogslist = $this->wpw_fp_object_to_array($result->posts);
		
		return $followeduserlogslist;
	}
	
	/**
 	 * Bulk Follow Post Delete Action
 	 * 
 	 * @package Follow My Blog Post
 	 * @since 1.0.0
 	 */
 	public function wpw_fp_bulk_follow_post_delete( $args = array() ) {
 		
 		if( isset( $args['parent_id'] ) && !empty( $args['parent_id'] ) ) {
 			foreach ( $args['parent_id'] as $parent_id ) {
	 			$ids = $this->wpw_fp_get_follow_post_users_data( array( 'postid' => $parent_id, 'fields' => 'ids' ) );
	 			foreach ( $ids as $id ) {
	 				$log_ids = $this->wpw_fp_get_follow_post_user_logs_data( array( 'logid' => $id, 'fields' => 'ids' ) );
	 				foreach ( $log_ids as $log_id ) {
	 					wp_delete_post( $log_id, true );
	 				}
	 				wp_delete_post( $id, true );
	 			}
 			}
 		}
 	}
 	
	/**
 	 * Bulk Follow Term Delete Action
 	 * 
 	 * @package Follow My Blog Post
 	 * @since 1.0.0
 	 */
 	public function wpw_fp_bulk_follow_term_delete( $args = array() ) {
 		
 		if( isset( $args['termid'] ) && !empty( $args['termid'] ) ) {
 			foreach ( $args['termid'] as $termid ) {
	 			$ids = $this->wpw_fp_get_follow_term_users_data( array( 'termid' => $termid, 'fields' => 'ids' ) );
	 			foreach ( $ids as $id ) {
	 				$log_ids = $this->wpw_fp_get_follow_term_user_logs_data( array( 'logid' => $id, 'fields' => 'ids' ) );
	 				foreach ( $log_ids as $log_id ) {
	 					wp_delete_post( $log_id, true );
	 				}
	 				wp_delete_post( $id, true );
	 			}
 			}
 		}
 	}
 	
	/**
 	 * Bulk Follow Author Delete Action
 	 * 
 	 * @package Follow My Blog Post
 	 * @since 1.4.0
 	 */
 	public function wpw_fp_bulk_follow_author_delete( $args = array() ) {
 		
 		if( isset( $args['authorid'] ) && !empty( $args['authorid'] ) ) {
 			foreach ( $args['authorid'] as $authorid ) {
	 			$ids = $this->wpw_fp_get_follow_author_users_data( array( 'authorid' => $authorid, 'fields' => 'ids' ) );
	 			foreach ( $ids as $id ) {
	 				$log_ids = $this->wpw_fp_get_follow_author_user_logs_data( array( 'logid' => $id, 'fields' => 'ids' ) );
	 				foreach ( $log_ids as $log_id ) {
	 					wp_delete_post( $log_id, true );
	 				}
	 				wp_delete_post( $id, true );
	 			}
 			}
 		}
 	}
 	
	/**
 	 * Bulk Delete Action
 	 * 
 	 * @package Follow My Blog Post
 	 * @since 1.0.0
 	 */
 	public function wpw_fp_bulk_delete( $args = array() ) {
 		
 		if( isset( $args['id'] ) && !empty( $args['id'] ) ) {
		
			wp_delete_post( $args['id'], true );
		}
 	}
 	
 	/**
 	 * Bulk Subscribe Action
 	 * 
 	 * @package Follow My Blog Post
 	 * @since 1.0.0
 	 */
 	public function wpw_fp_bulk_subscribe( $args = array() ) {
 		
		$prefix = WPW_FP_META_PREFIX;
		
 		if( isset( $args['id'] ) && !empty( $args['id'] ) ) {
			
			update_post_meta( $args['id'], $prefix.'follow_status', '1' );
		}
 	}
	
 	/**
 	 * Bulk Unsubscribe Action
 	 * 
 	 * @package Follow My Blog Post
 	 * @since 1.0.0
 	 */
 	public function wpw_fp_bulk_unsubscribe( $args = array() ) {
 		
		$prefix = WPW_FP_META_PREFIX;
		
 		if( isset( $args['id'] ) && !empty( $args['id'] ) ) {
		
			update_post_meta( $args['id'], $prefix.'follow_status', '0' );
		}
 	}
 	
	/**
	 * Convert Object To Array
	 *
	 * Converting Object Type Data To Array Type
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	
	public function wpw_fp_object_to_array( $result )
	{
	    $array = array();
	    foreach ($result as $key=>$value)
	    {	
	        if (is_object($value))
	        {
	            $array[$key]=$this->wpw_fp_object_to_array($value);
	        } else {
	        	$array[$key]=$value;
	        }
	    }
	    return $array;
	}
	
	/**
	 * Check Enable Follow
	 * 
	 * Handles to check enable follow me
	 * checkbox check or not for
	 * particular post and post type
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_check_enable_follow( $post_id ) {
		
		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$post_type = get_post_type( $post_id );
		
		// get disable follow value from post meta
		$disable_follow = get_post_meta( $post_id, $prefix.'disable_follow_me', true );
		
		// get post ids in which follow me will display
		$selected_posts = isset( $wpw_fp_options['prevent_item_'.$post_type] ) ? $wpw_fp_options['prevent_item_'.$post_type] : array();
		
		// get post types in which follow me will display
		$selected_post_types = isset( $wpw_fp_options['prevent_type'] ) ? $wpw_fp_options['prevent_type'] : array();
		
		// check if post have permission to display follow me form & checkbox
		/*if( $disable_follow == '1' ) {
			return false;
		} else if( in_array( $post_id, $selected_posts ) || in_array( $post_type, $selected_post_types ) ) {
			return true;
		} else {
			return false;
		}*/
		
		// check if not set disable from metabox and set filter on from setting page
		
		if( $disable_follow != '1' && ( in_array( $post_id, $selected_posts ) || in_array( $post_type, $selected_post_types ) )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Shortcode Replace
	 * 
	 * Handles to replace entered shortcodes 
	 * with corresponding values
	 * 
	 * @package Follow My Blog Post
 	 * @since 1.0.0
	 */
	public function wpw_fp_replace_shortcodes( $post_id, $text ) {
		
		global $current_user;
		
		$post_title = $post_content = '';
		
		// get user details
		$user_info = get_user_by('id',$current_user->ID);
		
		// get user name
		$user_name = isset( $user_info->user_login ) ? $user_info->user_login : '';
		
		if( !empty( $post_id ) ) {
			
			// get post data using post id
			$post_data = get_post( $post_id );
			
			$post_title = isset( $post_data->post_title ) && !empty( $post_data->post_title ) ? $post_data->post_title : __('Hello world!', 'wpwfp' );
			$post_content = isset( $post_data->post_content ) ? strip_shortcodes( $post_data->post_content ) : '';
		}
		// post name
		$post_name = isset( $_POST['post_title'] ) && !empty( $_POST['post_title'] ) ? $_POST['post_title'] : $post_title;
		$post_name = $this->wpw_fp_escape_slashes_deep( $post_name );
		
		// post description with 260 characters
		$post_description = $this->wpw_fp_short_content( $post_content, 260 );
		
		// post link
		$post_link = '<a href="'.get_permalink( $post_id ).'" >'.$post_name.'</a>';
		
		// site name with url
		$site_name = get_bloginfo('name');
		
		// site name with url
		$site_link = '<a href="'.site_url().'" >'.$site_name.'</a>';
		
		// replace of shortcodes
		$text = str_replace( '{post_name}', $post_name, $text );
		$text = str_replace( '{post_description}', $post_description, $text );
		$text = str_replace( '{post_link}', $post_link, $text );
		$text = str_replace( '{site_name}', $site_name, $text );
		$text = str_replace( '{site_link}', $site_link, $text );
		$text = str_replace( '{user_name}', $user_name, $text );
		
		//return replaced values
		return $text;
		
	}
	
	/**
	 * Send Emails With BCC for follow post
	 * 
	 * Handle to send email with bcc for follow post
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_post_send_mail_with_bcc( $followers_data, $subject, $message ) {
		
		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$setmail = false;
		$recipients = '';
		if ( $wpw_fp_options['recipient_per_email'] == 1 ) {
			
			// foreach loop for send email to every user, then create log
			foreach ( $followers_data as $value ) {
				
				$email = get_post_meta( $value['ID'], $prefix.'post_user_email', true );
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($email) || empty($email) ) { continue; }
				
				// send email to each user individually
				$setmail = $this->wpw_fp_send_email( $email, $subject, $message, '', '', true );
			}
			
		} else if ( empty( $wpw_fp_options['recipient_per_email'] )  ) {
			
			// foreach loop for send email to every user, then create log
			foreach ( $followers_data as $value ) {
				
				$email = get_post_meta( $value['ID'], $prefix.'post_user_email', true );
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($email) || empty($email) ) { continue; }
				
				if ( !empty( $email ) ) {
					
					 empty( $recipients ) ? $recipients = "$email" : $recipients .= ", $email";
					// Bcc Headers now constructed by phpmailer class
				}
			}
			
		} else {
			// we're using recipient_per_email
			$count = 1;
			$batch = array();
			// foreach loop for send email to every user, then create log
			foreach ( $followers_data as $value ) {
				
				$email = get_post_meta( $value['ID'], $prefix.'post_user_email', true );
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($email) || empty($email) ) { continue; }
				
				if ( !empty( $email ) ) {
					empty( $recipients ) ? $recipients = "$email" : $recipients .= ", $email";
					// Bcc Headers now constructed by phpmailer class
				}
				if ( $wpw_fp_options['recipient_per_email'] == $count ) {
					$count = 0;
					$batch[] = $recipients;
					$recipients = '';
				}
				$count++;
			}
			// add any partially completed batches to our batch array
			if ( !empty( $recipients ) ) {
				$batch[] = $recipients;
			}
		}
		
		// actually send mail
		if ( isset( $batch ) && !empty( $batch ) ) {
			foreach ( $batch as $recipients ) {
				$newheaders = "Bcc: $recipients\n";
				// send email
				$setmail = $this->wpw_fp_send_email( '', $subject, $message, $newheaders, '', true );
			}
		} else {
			if( !empty( $recipients ) ) {
				$newheaders = "Bcc: $recipients\n";
				// send email
				$setmail = $this->wpw_fp_send_email( '', $subject, $message, $newheaders, '', true );
			}
		}
		return $setmail;
	}
	
	/**
	 * Send Emails And Create Logs
	 * 
	 * Handle to send email to subscriber
	 * and create its logs
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_create_logs( $post_id ) {
		
		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$flag = false;
		
		$post_type = get_post_type( $post_id );
		
		// get post ids in which follow me will display
		$selected_posts = isset( $wpw_fp_options['notification_item_'.$post_type] ) ? $wpw_fp_options['notification_item_'.$post_type] : array();
		
		// get post types in which follow me will display
		$selected_post_types = isset( $wpw_fp_options['notification_type'] ) ? $wpw_fp_options['notification_type'] : array();
		
		// check if post have permission to display follow me form & checkbox
		if( !( in_array( $post_id, $selected_posts ) || in_array( $post_type, $selected_post_types ) ) ) {
			return false;
		}
		
		// Get Post subject from meta
		$post_email_subject = get_post_meta( $post_id , $prefix.'email_subject', true );
		
		// Get Post message from meta
		$post_email_body = get_post_meta( $post_id , $prefix.'email_body', true );
		
		if( isset( $post_email_subject ) && !empty( $post_email_subject ) ) {
			$subject = $post_email_subject;
		} else {
			$subject = $wpw_fp_options['email_subject'];
		}
		
		if( isset( $post_email_body ) && !empty( $post_email_body ) ) {
			$message = $post_email_body;
		} else {
			$message = $wpw_fp_options['email_body'];
		}
		
		// check if post have allow notification from Post / Page Notification Events > Trigger Emails > When post / page updated
		if( isset( $wpw_fp_options['post_trigger_notification']['post_update'] ) &&  $wpw_fp_options['post_trigger_notification']['post_update'] == '1' ) {
		
			$flag = $this->wpw_fp_post_send_mail( $subject, $message, $post_id );
		}
		
		// check if post have allow notification from Category / Tags Notification events > Trigger Emails > When post / page updated
		if( isset( $wpw_fp_options['term_trigger_notification']['post_update'] ) &&  $wpw_fp_options['term_trigger_notification']['post_update'] == '1' ) {
			
			$flag = $this->wpw_fp_all_term_send_mail( $subject, $message, $post_id );
		}
		
		// check if post have allow notification from author Notification events > Trigger Emails > When post / page updated
		if( isset( $wpw_fp_options['author_trigger_notification']['post_update'] ) &&  $wpw_fp_options['author_trigger_notification']['post_update'] == '1' ) {
			$post = get_post($post_id);
			$post_author = $post->post_author;
			$flag = $this->wpw_fp_all_author_send_mail( $subject, $message, $post_id ,$post_author);
		}
		
		if( $flag ) {
			return true;
		}
	}
	
	/**
	 * Send Emails And Create Logs
	 * 
	 * Handle to send email to subscriber
	 * and create its logs
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_post_send_mail( $subject, $message, $post_id ) {

		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$ids = array();
		
		// get the list of all users who are following this post
		$args = array( 
							'postid'			=> $post_id,
							'wpw_fp_status'		=> 'subscribe'
						);
		
		$followers_data = $this->wpw_fp_get_follow_post_users_data( $args );
		
		// check followers are not exists
		if( empty( $followers_data ) ) {
			return false;
		}
		
		$subject = $this->wpw_fp_replace_shortcodes( $post_id, $subject );
		
		// replace email shortcodes with content
		$message = $this->wpw_fp_replace_shortcodes( $post_id , $message );
		
		$flag = $this->wpw_fp_post_send_mail_with_bcc( $followers_data, $subject, $message );
		if( !$flag ) {
			return false;
		}
		
		// if mail is successfully send then create log based on enable_log from settings
		if( isset( $wpw_fp_options['enable_log'] ) && $wpw_fp_options['enable_log'] == '1' ) {
			
			// foreach loop for send email to every user, then create log
			foreach ( $followers_data as $value ) {
				
				// get mail data
				$mail_data = $subject. "%$%$%". $message;
				
				$args = array(
								'post_title'	=>	$value['post_author'],
								'post_content'	=>	'',
								'post_type'		=>	WPW_FP_LOGS_POST_TYPE,
								'post_status'	=>	'publish',
								'post_parent'	=>	$value['ID'],
								'post_author'	=>	$value['post_author']
							);
				
				$follow_post_log_id = wp_insert_post( $args );
				
				if( $follow_post_log_id ) {
					
					// update email data meta
					update_post_meta( $follow_post_log_id, $prefix.'log_email_data', nl2br($mail_data) );
				}
			}
		}
		return true;
	}
	
	/**
	 * Send Emails With BCC for follow term
	 * 
	 * Handle to send email with bcc for follow term
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_term_send_mail_with_bcc( $followers_data, $subject, $message ) {
		
		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$setmail = false;
		$recipients = '';
		if ( $wpw_fp_options['recipient_per_email'] == 1 ) {
			
			// foreach loop for send email to every user, then create log
			foreach ( $followers_data as $value ) {
				
				$email = get_post_meta( $value['ID'], $prefix.'term_user_email', true );
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($email) || empty($email) ) { continue; }
				
				// send email
				$setmail = $this->wpw_fp_send_email( $email, $subject, $message, '', '', true );
				
			}
			
		} else if ( empty( $wpw_fp_options['recipient_per_email'] ) ) {
			
			// foreach loop for send email to every user, then create log
			foreach ( $followers_data as $value ) {
				
				$email = get_post_meta( $value['ID'], $prefix.'term_user_email', true );
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($email) || empty($email) ) { continue; }
				
				if ( !empty( $email ) ) {
					empty( $recipients ) ? $recipients = "$email" : $recipients .= ", $email";
					// Bcc Headers now constructed by phpmailer class
				}
			}
			
		} else {
			// we're using recipient_per_email
			$count = 1;
			$batch = array();
			// foreach loop for send email to every user, then create log
			foreach ( $followers_data as $value ) {
				
				$email = get_post_meta( $value['ID'], $prefix.'term_user_email', true );
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($email) || empty($email) ) { continue; }
				
				if ( !empty( $email ) ) {
					empty( $recipients ) ? $recipients = "$email" : $recipients .= ", $email";
					// Bcc Headers now constructed by phpmailer class
				}
				if ( $wpw_fp_options['recipient_per_email'] == $count ) {
					$count = 0;
					$batch[] = $recipients;
					$recipients = '';
				}
				$count++;
			}
			// add any partially completed batches to our batch array
			if ( !empty( $recipients ) ) {
				$batch[] = $recipients;
			}
		}
		
		// actually send mail
		if ( isset( $batch ) && !empty( $batch ) ) {
			foreach ( $batch as $recipients ) {
				$newheaders = "Bcc: $recipients\n";
				// send email
				$setmail = $this->wpw_fp_send_email( '', $subject, $message, $newheaders, '', true );
			}
		} else {
			if( !empty( $recipients ) ) {
				$newheaders = "Bcc: $recipients\n";
				// send email
				$setmail = $this->wpw_fp_send_email( '', $subject, $message, $newheaders, '', true );
			}
		}
		return $setmail;
	}
	/**
	 * Send Emails With BCC for follow author
	 * 
	 * Handle to send email with bcc for follow author
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	public function wpw_fp_author_send_mail_with_bcc( $followers_data, $subject, $message ) {
		
		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$setmail = false;
		$recipients = '';
		if ( $wpw_fp_options['recipient_per_email'] == 1 ) {
			
			// foreach loop for send email to every user, then create log
			foreach ( $followers_data as $value ) {
				
				$email = get_post_meta( $value['ID'], $prefix.'author_user_email', true );
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($email) || empty($email) ) { continue; }
				
				// send email
				$setmail = $this->wpw_fp_send_email( $email, $subject, $message, '', '', true );
				
			}
			
		} else if ( empty( $wpw_fp_options['recipient_per_email'] ) ) {
			
			// foreach loop for send email to every user, then create log
			foreach ( $followers_data as $value ) {
				
				$email = get_post_meta( $value['ID'], $prefix.'author_user_email', true );
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($email) || empty($email) ) { continue; }
				
				if ( !empty( $email ) ) {
					empty( $recipients ) ? $recipients = "$email" : $recipients .= ", $email";
					// Bcc Headers now constructed by phpmailer class
				}
			}
			
		} else {
			// we're using recipient_per_email
			$count = 1;
			$batch = array();
			// foreach loop for send email to every user, then create log
			foreach ( $followers_data as $value ) {
				
				$email = get_post_meta( $value['ID'], $prefix.'author_user_email', true );
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($email) || empty($email) ) { continue; }
				
				if ( !empty( $email ) ) {
					empty( $recipients ) ? $recipients = "$email" : $recipients .= ", $email";
					// Bcc Headers now constructed by phpmailer class
				}
				if ( $wpw_fp_options['recipient_per_email'] == $count ) {
					$count = 0;
					$batch[] = $recipients;
					$recipients = '';
				}
				$count++;
			}
			// add any partially completed batches to our batch array
			if ( !empty( $recipients ) ) {
				$batch[] = $recipients;
			}
		}
		
		// actually send mail
		if ( isset( $batch ) && !empty( $batch ) ) {
			foreach ( $batch as $recipients ) {
				$newheaders = "Bcc: $recipients\n";
				// send email
				$setmail = $this->wpw_fp_send_email( '', $subject, $message, $newheaders, '', true );
			}
		} else {
			if( !empty( $recipients ) ) {
				$newheaders = "Bcc: $recipients\n";
				// send email
				$setmail = $this->wpw_fp_send_email( '', $subject, $message, $newheaders, '', true );
			}
		}
		return $setmail;
	}
	
	/**
	 * Send Emails And Create Logs for Term
	 * 
	 * Handle to send email to term subscriber
	 * and create its logs
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_term_create_logs( $post_id ) {
		
		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$flag = false;
		
		$post_type = get_post_type( $post_id );
		
		// get post ids in which follow me will display
		$selected_posts = isset( $wpw_fp_options['notification_item_'.$post_type] ) ? $wpw_fp_options['notification_item_'.$post_type] : array();
		
		// get post types in which follow me will display
		$selected_post_types = isset( $wpw_fp_options['notification_type'] ) ? $wpw_fp_options['notification_type'] : array();
		
		// check if post have permission to display follow me form & checkbox
		if( !( in_array( $post_id, $selected_posts ) || in_array( $post_type, $selected_post_types ) ) ) {
			return false;
		}
		
		// Get Term subject from meta
		$term_email_subject = get_post_meta( $post_id , $prefix.'term_email_subject', true );
		
		// Get Term subject from meta
		$term_email_body = get_post_meta( $post_id , $prefix.'term_email_body', true );
		
		if( isset( $term_email_subject ) && !empty( $term_email_subject ) ) {
			$subject = $term_email_subject;
		} else {
			$subject = $wpw_fp_options['term_email_subject'];
		}
		
		if( isset( $term_email_body ) && !empty( $term_email_body ) ) {
			$message = $term_email_body;
		} else {
			$message = $wpw_fp_options['term_email_body'];
		}
		
		// check if post have allow notification from Category / Tags Notification events > Trigger Emails > When new post published
		if( isset( $wpw_fp_options['term_trigger_notification']['new_post'] ) &&  $wpw_fp_options['term_trigger_notification']['new_post'] == '1' ) {
			
			$flag = $this->wpw_fp_all_term_send_mail( $subject, $message, $post_id );
		}
		
		if( $flag ) {
			return true;
		}
		return false;
	}
	/**
	 * Send Emails And Create Logs for author
	 * 
	 * Handle to send email to author subscriber
	 * and create its logs
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	public function wpw_fp_author_create_logs( $post_id ) {
		
		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$flag = false;
		
		$post_type = get_post_type( $post_id );
		
		// get post ids in which follow me will display
		$selected_posts = isset( $wpw_fp_options['notification_item_'.$post_type] ) ? $wpw_fp_options['notification_item_'.$post_type] : array();
		
		// get post types in which follow me will display
		$selected_post_types = isset( $wpw_fp_options['notification_type'] ) ? $wpw_fp_options['notification_type'] : array();
		
		// check if post have permission to display follow me form & checkbox
		if( !( in_array( $post_id, $selected_posts ) || in_array( $post_type, $selected_post_types ) ) ) {
			return false;
		}
		
		// Get author subject from meta
		$author_email_subject = get_post_meta( $post_id , $prefix.'author_email_subject', true );
		
		// Get author subject from meta
		$author_email_body = get_post_meta( $post_id , $prefix.'author_email_body', true );
		
		if( isset( $author_email_subject ) && !empty( $author_email_subject ) ) {
			$subject = $author_email_subject;
		} else {
			$subject = $wpw_fp_options['author_email_subject'];
		}
		
		if( isset( $author_email_body ) && !empty( $author_email_body ) ) {
			$message = $author_email_body;
		} else {
			$message = $wpw_fp_options['author_email_body'];
		}
		
		// check if post have allow notification from Authors Notification events > Trigger Emails > When new post published
		if( isset( $wpw_fp_options['author_trigger_notification']['new_post'] ) &&  $wpw_fp_options['author_trigger_notification']['new_post'] == '1' ) {
			$post = get_post($post_id);
			$post_author = $post->post_author;
					
			$flag = $this->wpw_fp_all_author_send_mail( $subject, $message, $post_id,$post_author );
		}
		
		if( $flag ) {
			return true;
		}
		return false;
	}
	/**
	 * Send Emails And Create Logs for Term
	 * 
	 * Handle to send email to term subscriber
	 * and create its logs
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_all_term_send_mail( $subject, $message, $post_id ) {
		
		$flag = false;
		
		$post_type = get_post_type( $post_id );
		
		// All taxonomy for current post type
		$all_taxonomy = get_object_taxonomies( $post_type );
		if( !empty( $all_taxonomy ) ) { // Check taxonomy is not empty
			
			foreach ( $all_taxonomy as $taxonomy_slug ) {
				
				// Get selected term for particular taxonomy 
				$terms = get_the_terms( $post_id, $taxonomy_slug );
		
				// check not generate error
				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$send_mail = $this->wpw_fp_term_send_mail( $term->term_id, $term->taxonomy, $subject, $message, $post_id );
						if( $send_mail ) {
							$flag = true;
						}
					}
				}
			}
		}
		
		if( $flag ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Send Emails And Create Logs for author
	 * 
	 * Handle to send email to author subscriber
	 * and create its logs
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	public function wpw_fp_all_author_send_mail( $subject, $message, $post_id, $post_author ) {
		
		$flag = false;
		
		$send_mail = $this->wpw_fp_author_send_mail( $post_author, $subject, $message, $post_id );
		if( $send_mail ) {
			$flag = true;
		}
			
		if( $flag ) {
			return true;
		}
		return false;
	}
	/**
	 * Send Emails And Create Logs for Term
	 * 
	 * Handle to send email to term subscriber
	 * and create its logs
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_term_send_mail( $termid, $taxonomy, $subject, $message, $post_id ) {

		global $wpw_fp_options;
		
		if( !empty( $termid ) && !empty( $taxonomy ) && !empty( $post_id ) ) {
			
			$ids = array();
			
			$prefix = WPW_FP_META_PREFIX;
		
			// get the list of all users who are following this post
			$args = array( 
								'termid'			=> $termid,
								'wpw_fp_taxonomy'	=> $taxonomy,
								'wpw_fp_status'		=> 'subscribe',
							);
			
			$followers_data = $this->wpw_fp_get_follow_term_users_data( $args );
			
			// check followers are not exists
			if( empty( $followers_data ) ) {
				return false;
			}
			
			$term_name = $taxonomy_name = '';
			
			// term name & term link
			$term_data = get_term_by( 'id', $termid, $taxonomy );
			if( !empty( $term_data ) && isset( $term_data->name ) ) {
				$term_name = $term_data->name;
			}
			
			// taxonomy name
			$taxonomy_data = get_taxonomy( $taxonomy );
			if( !empty( $taxonomy_data ) && isset( $taxonomy_data->labels ) && isset( $taxonomy_data->labels->singular_name ) ) {
				$taxonomy_name = $taxonomy_data->labels->singular_name;
			}
			
			$message = str_replace( '{term_name}', $term_name, $message );
			$message = str_replace( '{taxonomy_name}', $taxonomy_name, $message );
		
			// replace email shortcodes with content
			$message = $this->wpw_fp_replace_shortcodes( $post_id , $message );
			
			$subject = $this->wpw_fp_replace_shortcodes( $post_id, $subject );
			
			$flag = $this->wpw_fp_term_send_mail_with_bcc( $followers_data, $subject, $message );
			if( !$flag ) {
				return false;
			}
			
			// if mail is successfully send then create log based on enable_log from settings
			if( isset( $wpw_fp_options['enable_log'] ) && $wpw_fp_options['enable_log'] == '1' ) {
				
				// foreach loop for send email to every user, then create log
				foreach ( $followers_data as $value ) {
					
					// get mail data
					$mail_data = $subject. "%$%$%". $message;
					
					$args = array(
									'post_title'	=>	$value['post_author'],
									'post_content'	=>	'',
									'post_type'		=>	WPW_FP_TERM_LOGS_POST_TYPE,
									'post_status'	=>	'publish',
									'post_parent'	=>	$value['ID'],
									'post_author'	=>	$value['post_author']
								);
					
					$follow_post_log_id = wp_insert_post( $args );
					
					if( $follow_post_log_id ) {
						
						// update email data meta
						update_post_meta( $follow_post_log_id, $prefix.'log_email_data', nl2br($mail_data) );
					}
				}
			}
		}
		return true;
	}
	/**
	 * Send Emails And Create Logs for author
	 * 
	 * Handle to send email to author subscriber
	 * and create its logs
	 * 
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	public function wpw_fp_author_send_mail( $authorid, $subject, $message, $post_id ) {

		global $wpw_fp_options;
		
		if( !empty( $authorid ) && !empty( $post_id ) ) {
			
			$ids = array();
			
			$prefix = WPW_FP_META_PREFIX;
		
			// get the list of all users who are following this post
			$args = array( 
								'authorid'			=> $authorid,
								'wpw_fp_status'		=> 'subscribe',
							);
			
			$followers_data = $this->wpw_fp_get_follow_author_users_data( $args );
			
			// check followers are not exists
			if( empty( $followers_data ) ) {
				return false;
			}
			
			$author_name = '';
			
			// author name & author link
			$author_data = get_user_by( 'id', $authorid);
			if( !empty( $author_data ) && isset( $author_data->display_name ) ) {
				$author_name = $author_data->display_name;
			}
			
			$message = str_replace( '{author_name}', $author_name, $message );
			
			// replace email shortcodes with content
			$message = $this->wpw_fp_replace_shortcodes( $post_id , $message );
			
			$subject = $this->wpw_fp_replace_shortcodes( $post_id, $subject );
			
			$flag = $this->wpw_fp_author_send_mail_with_bcc( $followers_data, $subject, $message );
			if( !$flag ) {
				return false;
			}
			
			// if mail is successfully send then create log based on enable_log from settings
			if( isset( $wpw_fp_options['enable_log'] ) && $wpw_fp_options['enable_log'] == '1' ) {
				
				// foreach loop for send email to every user, then create log
				foreach ( $followers_data as $value ) {
					
					// get mail data
					$mail_data = $subject. "%$%$%". $message;
					
					$args = array(
									'post_title'	=>	$value['post_author'],
									'post_content'	=>	'',
									'post_type'		=>	WPW_FP_AUTHOR_LOGS_POST_TYPE,
									'post_status'	=>	'publish',
									'post_parent'	=>	$value['ID'],
									'post_author'	=>	$value['post_author']
								);
					
					$follow_post_log_id = wp_insert_post( $args );
					
					if( $follow_post_log_id ) {
						
						// update email data meta
						update_post_meta( $follow_post_log_id, $prefix.'log_email_data', nl2br($mail_data) );
					}
				}
			}
		}
		return true;
	}
	
	/**
	 * Send Emails And Create Logs
	 * 
	 * Handle to send email to subscriber
	 * and create its logs
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_create_comments( $comment_data ) {
		
		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$flag = false;
		
		$comment_text = $comment_author = '';
		if( !empty( $comment_data ) ) {
			
			// Get the post id
			$post_id = $comment_data->comment_post_ID;
			
			// Get disable email notification meta 
			$disable_email_notification = get_post_meta( $post_id, $prefix.'disable_email_notification', true );
			
			// Check disable enail notification is checked
			if( $disable_email_notification != '1' ) {
			
				$comment_text = isset( $comment_data->comment_content ) ? $comment_data->comment_content : '';
				$comment_author = isset( $comment_data->comment_author ) ? $comment_data->comment_author : '';	
				
				// Get Comment subject from meta
				$comment_email_subject = get_post_meta( $post_id , $prefix.'comment_email_subject', true );
				
				// Get Comment message from meta
				$comment_email_body = get_post_meta( $post_id , $prefix.'comment_email_body', true );
				
				if( isset( $comment_email_subject ) && !empty( $comment_email_subject ) ) {
					$subject = $comment_email_subject;
				} else {
					$subject = $wpw_fp_options['comment_email_subject'];
				}
				
				if( isset( $comment_email_body ) && !empty( $comment_email_body ) ) {
					$message = $comment_email_body;
				} else {
					$message = $wpw_fp_options['comment_email_body'];
				}
				
				$subject = str_replace( '{user_name}', $comment_author, $subject );
				
				$message = str_replace( '{comment_text}', $comment_text, $message );
				$message = str_replace( '{user_name}', $comment_author, $message );
			
				// check if post have allow notification from Post / Page Notification Events > Trigger Emails > When new comment addded
				if( isset( $wpw_fp_options['post_trigger_notification']['new_comment'] ) && $wpw_fp_options['post_trigger_notification']['new_comment'] == '1' ) {
					
					$flag = $this->wpw_fp_post_send_mail( $subject, $message, $post_id );
				}
				
				// check if post have allow notification from Category / Tags Notification events > Trigger Emails > When new comment addded
				if( isset( $wpw_fp_options['term_trigger_notification']['new_comment'] ) && $wpw_fp_options['term_trigger_notification']['new_comment'] == '1' ) {
					
					$flag = $this->wpw_fp_all_term_send_mail( $subject, $message, $post_id );
				}
				
				// check if post have allow notification from author Notification events > Trigger Emails > When new comment addded
				if( isset( $wpw_fp_options['author_trigger_notification']['new_comment'] ) && $wpw_fp_options['author_trigger_notification']['new_comment'] == '1' ) {
					$post = get_post($post_id);
					$post_author = $post->post_author;
					$flag = $this->wpw_fp_all_author_send_mail( $subject, $message, $post_id,$post_author );
				}
			}
		}
	}
	
	/**
	 * Check the current post if th shortcode has been added
	 *	
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */	
	public function wpw_fp_has_shortcode( $shortcode = '' ) {  
		
    	$post_to_check = get_post( get_the_ID() );  
    	// false because we have to search through the post content first  
    	$found = false;  
    	// if no shortcode was provided, return false  
    	if( !$shortcode ) {  
        	return $found;  
    	}  
    	// check the post content for the short code  
    	if( stripos( $post_to_check->post_content, '[' . $shortcode ) !== false ) {  
        	// we have found the short code  
        	$found = true;  
    	}  
    	// return our final results  
    	return $found;  
	}  
	
	/**
	 * Send Confirmation email
	 * 
	 * Handles to send confirmation email
	 *	
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_confirmation_email( $args = array() ) {
		
		global $wpw_fp_options;
		
		$follow_user_email = $confirm_email_link = $post_id = $current_post_id = '';
		if( isset( $args['wpw_fp_email'] ) && !empty( $args['wpw_fp_email'] ) ) {
			$follow_user_email = $args['wpw_fp_email'];
		}
		if( isset( $args['post_id'] ) && !empty( $args['post_id'] ) ) {
			$post_id = $args['post_id'];
		}
		if( isset( $args['current_post_id'] ) && !empty( $args['current_post_id'] ) ) {
			$current_post_id = $args['current_post_id'];
		}
		
		// subscribe url
		$url = get_permalink( $current_post_id );
		$url = add_query_arg( array( 'wpw_fp_post_id' => base64_encode( $post_id ), 'wpw_fp_email' => base64_encode( rawurlencode( $follow_user_email ) ), 'wpw_fp_action' => base64_encode( 'subscribe' ) ), $url );
		$subscribe_url = '<a target="_blank" href="'.$url.'" >'.__( 'Confirm Follow', 'wpwfp' ).'</a>';
		
		$subject = isset( $wpw_fp_options['confirm_email_subject'] ) ? $wpw_fp_options['confirm_email_subject'] : '';
		$message = isset( $wpw_fp_options['confirm_email_body'] ) ? $wpw_fp_options['confirm_email_body'] : '';
		
		if( !empty( $post_id ) ) {
			$subject = $this->wpw_fp_replace_shortcodes( $post_id, $subject );
			$message = $this->wpw_fp_replace_shortcodes( $post_id, $message );
		}
		$message = str_replace( '{subscribe_url}', $subscribe_url, $message );
		
		// Check message and email id are not empty
		if( !empty( $message ) && !empty( $follow_user_email ) && is_email( $follow_user_email ) ) {
			
			$setmail = $this->wpw_fp_send_email( $follow_user_email, $subject, $message );
		}
	}
	
	/**
	 * Send Confirmation email for follow term
	 * 
	 * Handles to send confirmation email for follow term
	 *	
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_term_confirmation_email( $args = array() ) {
		
		global $wpw_fp_options;
		
		$follow_user_email = $confirm_email_link = $post_id = $current_post_id = $term_name = $taxonomy_name = '';
		
		if( isset( $args['wpw_fp_email'] ) && !empty( $args['wpw_fp_email'] ) ) {
			$follow_user_email = $args['wpw_fp_email'];
		}
		if( isset( $args['posttype'] ) && !empty( $args['posttype'] ) ) {
			$posttype = $args['posttype'];
		}
		if( isset( $args['taxonomy'] ) && !empty( $args['taxonomy'] ) ) {
			$taxonomy = $args['taxonomy'];
		}
		if( isset( $args['term_id'] ) && !empty( $args['term_id'] ) ) {
			$term_id = $args['term_id'];
		}
		if( isset( $args['current_post_id'] ) && !empty( $args['current_post_id'] ) ) {
			$current_post_id = $args['current_post_id'];
		}
		
		// subscribe url
		$subscrib_args = array(
									'wpw_fp_posttype' 	=> base64_encode( $posttype ),
									'wpw_fp_taxonomy' 	=> base64_encode( $taxonomy ),
									'wpw_fp_term_id' 	=> base64_encode( $term_id ),
									'wpw_fp_email' 		=> base64_encode( rawurlencode( $follow_user_email ) ),
									'wpw_fp_action' 	=> base64_encode( 'subscribeterm' )
								);
		$url = get_permalink( $current_post_id );
		$url = add_query_arg( $subscrib_args, $url );
		$subscribe_url = '<a target="_blank" href="' . $url . '" >' . __( 'Confirm Follow', 'wpwfp' ) . '</a>';
		
		// term name & term link
		$term_data = get_term_by( 'id', $term_id, $taxonomy );
		if( !empty( $term_data ) && isset( $term_data->name ) ) {
			$term_name = $term_data->name;
		}
		
		// taxonomy name
		$taxonomy_data = get_taxonomy( $taxonomy );
		if( !empty( $taxonomy_data ) && isset( $taxonomy_data->labels ) && isset( $taxonomy_data->labels->singular_name ) ) {
			$taxonomy_name = $taxonomy_data->labels->singular_name;
		}
		
		$subject = isset( $wpw_fp_options['term_confirm_email_subject'] ) ? $wpw_fp_options['term_confirm_email_subject'] : '';
		$message = isset( $wpw_fp_options['term_confirm_email_body'] ) ? $wpw_fp_options['term_confirm_email_body'] : '';
		
		if( !empty( $current_post_id ) ) {
			$subject = $this->wpw_fp_replace_shortcodes( $current_post_id, $subject );
			$message = $this->wpw_fp_replace_shortcodes( $current_post_id, $message );
		}
		$subject = str_replace( '{term_name}', $term_name, $subject );
		
		$message = str_replace( '{term_name}', $term_name, $message );
		$message = str_replace( '{taxonomy_name}', $taxonomy_name, $message );
		$message = str_replace( '{subscribe_url}', $subscribe_url, $message );
		
		// Check message and email id are not empty
		if( !empty( $message ) && !empty( $follow_user_email ) && is_email( $follow_user_email ) ) {
			
			$setmail = $this->wpw_fp_send_email( $follow_user_email, $subject, $message );
		}
	}
	
	/**
	 * Send Confirmation email for follow author
	 * 
	 * Handles to send confirmation email for follow author
	 *	
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	public function wpw_fp_author_confirmation_email( $args = array() ) {
		
		global $wpw_fp_options;
		
		$follow_user_email = $confirm_email_link = $post_id = $current_post_id =  '';
		
		if( isset( $args['wpw_fp_email'] ) && !empty( $args['wpw_fp_email'] ) ) {
			$follow_user_email = $args['wpw_fp_email'];
		}
		
		if( isset( $args['author_id'] ) && !empty( $args['author_id'] ) ) {
			$author_id = $args['author_id'];
		}
		if( isset( $args['current_post_id'] ) && !empty( $args['current_post_id'] ) ) {
			$current_post_id = $args['current_post_id'];
		}
		
		// subscribe url
		$subscrib_args = array(
									'wpw_fp_author_id' 	=> base64_encode( $author_id ),
									'wpw_fp_email' 		=> base64_encode( rawurlencode( $follow_user_email ) ),
									'wpw_fp_action' 	=> base64_encode( 'subscribeauthor' )
								);
		$url = get_permalink( $current_post_id );
		$url = add_query_arg( $subscrib_args, $url );
		$subscribe_url = '<a target="_blank" href="' . $url . '" >' . __( 'Confirm Follow', 'wpwfp' ) . '</a>';
		
		// author name & author link
		$author_data = get_user_by( 'id', $author_id );
		if( !empty( $author_data ) && isset( $author_data->display_name ) ) {
			$author_name = $author_data->display_name;
		}
		
		
		$subject = isset( $wpw_fp_options['author_confirm_email_subject'] ) ? $wpw_fp_options['author_confirm_email_subject'] : '';
		$message = isset( $wpw_fp_options['author_confirm_email_body'] ) ? $wpw_fp_options['author_confirm_email_body'] : '';
		
		if( !empty( $current_post_id ) ) {
			$subject = $this->wpw_fp_replace_shortcodes( $current_post_id, $subject );
			$message = $this->wpw_fp_replace_shortcodes( $current_post_id, $message );
		}
		$subject = str_replace( '{author_name}', $author_name, $subject );
		
		$message = str_replace( '{author_name}', $author_name, $message );
		$message = str_replace( '{subscribe_url}', $subscribe_url, $message );
		
		// Check message and email id are not empty
		if( !empty( $message ) && !empty( $follow_user_email ) && is_email( $follow_user_email ) ) {
			
			$setmail = $this->wpw_fp_send_email( $follow_user_email, $subject, $message );
		}
	}
	
	/**
	 * Send Confirmation for unsubscribe email
	 * 
	 * Handles to send confirmation for unsubscribe email
	 *	
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_confirmation_unsubscribe_email( $args = array() ) {
		
		global $wpw_fp_options, $post;
		
		$follow_user_email = $confirm_email_link = '';
		if( isset( $args['wpw_fp_email'] ) && !empty( $args['wpw_fp_email'] ) ) {
			$follow_user_email = $args['wpw_fp_email'];
		}
		
		$subject = isset( $wpw_fp_options['unsubscribe_confirm_email_subject'] ) ? $wpw_fp_options['unsubscribe_confirm_email_subject'] : '';
		$message = isset( $wpw_fp_options['unsubscribe_confirm_email_body'] ) ? $wpw_fp_options['unsubscribe_confirm_email_body'] : '';
		
		$unsubscribe_page_id = isset( $wpw_fp_options['unsubscribe_page'] ) && !empty( $wpw_fp_options['unsubscribe_page'] ) ? $wpw_fp_options['unsubscribe_page'] : $post->ID; 
		$url = get_permalink( $unsubscribe_page_id );
		$url = add_query_arg( array( 'wpw_fp_action' => base64_encode( 'unsubscribe' ), 'wpw_fp_email' => base64_encode( rawurlencode( $follow_user_email ) ) ), $url );
		$confirm_email_link = '<a target="_blank" href="'.$url.'" >'.__( 'Confirm Unsubscription', 'wpwfp' ).'</a>';
		
		$subject = str_replace( '{email}', $follow_user_email, $subject );
		
		$message = str_replace( '{email}', $follow_user_email, $message );
		$message = str_replace( '{confirm_url}', $confirm_email_link, $message );
		
		if( !empty( $post->ID ) ) {
			$subject = $this->wpw_fp_replace_shortcodes( $post->ID, $subject );
			$message = $this->wpw_fp_replace_shortcodes( $post->ID, $message );
		}
		
		// Check message and email id are not empty
		if( !empty( $message ) && !empty( $follow_user_email ) && is_email( $follow_user_email ) ) {
			
			$setmail = $this->wpw_fp_send_email( $follow_user_email, $subject, $message );
		}
	}
	
	/**
	 * Filter for getting follow term data with grouped
	 * 
	 * Handles to get follow term data with grouped
	 *	
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function wpw_fp_follow_term_groupby( $groupby ) {
		
	    global $wpdb;
	    
	    $groupby = "{$wpdb->posts}.post_parent";
	    
	    return $groupby;
	}
	
	/**
	 * Filter for getting follow author data with grouped
	 * 
	 * Handles to get follow author data with grouped
	 *	
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	function wpw_fp_follow_author_groupby( $groupby ) {
		
	    global $wpdb;
	    
	    $groupby = "{$wpdb->posts}.post_parent";
	    
	    return $groupby;
	}
	
	/**
	 * Get Term data
	 * 
	 * Handles to get term data for
	 * followcategory post type
	 * 
	 * @package Follow My Blog Post
 	 * @since 1.1.0
	 */
	public function wpw_fp_get_follow_term_data( $args = array() ) {
		
		$prefix = WPW_FP_META_PREFIX;
		
		$followcategorylist = array();
		
		$metaquery = array();
		
		//check if its custom post types created or not
		$checkcategoryargs = array( 
									'post_status'	=>	'publish',
									'post_type' 	=>	WPW_FP_TERM_POST_TYPE,
									'posts_per_page' => '-1',
								);

		// Search with taxonomy
		if( isset( $args['wpw_fp_taxonomy'] ) && !empty( $args['wpw_fp_taxonomy'] ) ) {
			
			$metaquery[] = array(
								'key' 		=> $prefix.'taxonomy_slug',
								'value' 	=> $args['wpw_fp_taxonomy'],
							);
		} else {

			$metaquery[] = array(
									'key' 		=> $prefix.'taxonomy_slug',
									'compare' 	=> 'EXISTS',
								);
		}
		
		// Check if post type selected from filter
		if( isset( $args['post_type'] ) && !empty( $args['post_type'] ) ) {
			
			$metaquery[] = array(
									'key' 		=> $prefix.'post_type',
									'value' 	=> $args['post_type'],
								);
		}
		
		if( !empty( $metaquery ) ) {
			$checkcategoryargs['meta_query'] = $metaquery;
		}

		// add filter for getting follow term data with grouped
		add_filter( 'posts_groupby', array( $this, 'wpw_fp_follow_term_groupby' ) );
		
		//fire query in to table for retriving data
		$result = new WP_Query( $checkcategoryargs );
		
		// remove filter for groupby remove to post query
		remove_filter( 'posts_groupby', array( $this, 'wpw_fp_follow_term_groupby' ) );
				
		//retrived data is in object format so assign that data to array for listing
		$followcategorylist = $this->wpw_fp_object_to_array($result->posts);
		
		// Check if post type counter from filter
		if( isset( $args['count'] ) && !empty( $args['count'] ) ) {
			return count( $followcategorylist );
		}
		
		return $followcategorylist;
	}
	
	/**
	 * Get author data
	 * 
	 * Handles to get author data for
	 * followauthor post type
	 * 
	 * @package Follow My Blog Post
 	 * @since 1.4.0
	 */
	public function wpw_fp_get_follow_author_data( $args = array() ) {
		
		$prefix = WPW_FP_META_PREFIX;
		
		$followauthorlist = array();
		
		$metaquery = array();
		
		//check if its custom post types created or not
		$checkauthorargs = array( 
									'post_status'	=>	'publish',
									'post_type' 	=>	WPW_FP_AUTHOR_POST_TYPE,
									'posts_per_page' => '-1',
								);

		if( !empty( $metaquery ) ) {
			$checkauthorargs['meta_query'] = $metaquery;
		}

		// add filter for getting follow author data with grouped
		add_filter( 'posts_groupby', array( $this, 'wpw_fp_follow_author_groupby' ) );
		
		//fire query in to table for retriving data
		$result = new WP_Query( $checkauthorargs );
		
		// remove filter for groupby remove to post query
		remove_filter( 'posts_groupby', array( $this, 'wpw_fp_follow_author_groupby' ) );
				
		//retrived data is in object format so assign that data to array for listing
		$followauthorlist = $this->wpw_fp_object_to_array($result->posts);
		
		// Check if post type counter from filter
		if( isset( $args['count'] ) && !empty( $args['count'] ) ) {
			return count( $followauthorlist );
		}
		
		return $followauthorlist;
	}
	
	/**
	 * Get Term Users data
	 * 
	 * Handles to get term users data for
	 * post type
	 * 
	 * @package Follow My Blog Post
 	 * @since 1.1.0
	 */
	public function wpw_fp_get_follow_term_users_data( $args = array() ) {
		
		$prefix = WPW_FP_META_PREFIX;
		
		$followtermusersargs = array( 
										'post_type' => WPW_FP_TERM_POST_TYPE,
										'post_status' => 'publish'
									);
		
		//show how many per page records
		if( isset( $args['posts_per_page'] ) && !empty( $args['posts_per_page'] ) ) {
			$followtermusersargs['posts_per_page'] = $args['posts_per_page'];
		} else {
			$followtermusersargs['posts_per_page'] = '-1';
		}
		
		//show per page records
		if( isset( $args['paged']) && !empty( $args['paged'] ) ) {
			$followtermusersargs['paged'] = $args['paged'];
		}
		
		
		//if search using post parent
		if( isset( $args['termid'] ) ) {
			$followtermusersargs['post_parent'] = $args['termid'];
		}
		
		//if search using author
		if( isset( $args['author'] ) ) {
			$followtermusersargs['author'] = $args['author'];
		}
		
		//if search is called then retrive searching data
		if( isset( $args['search'] ) ) {
			//$followtermusersargs['s'] = $args['search'];
			$metaargs[] = array(
									'key' 		=> $prefix.'term_user_email',
									'value' 	=> $args['search'],
									'compare'	=> 'LIKE'
								);
		}
		
		if( isset( $args['wpw_fp_status'] ) && !empty( $args['wpw_fp_status'] ) ) {
			$status = $args['wpw_fp_status'] == 'subscribe' ? '1' : '0';
			$metaargs[] = array(
									'key' 	=> $prefix.'follow_status',
									'value' => $status
								);
		}
		
		if( isset( $args['wpw_fp_taxonomy'] ) && !empty( $args['wpw_fp_taxonomy'] ) ) {
			$metaargs[] = array(
									'key' 	=> $prefix.'taxonomy_slug',
									'value' => $args['wpw_fp_taxonomy']
								);
		}
		
		if( isset( $args['wpw_fp_email'] ) && !empty( $args['wpw_fp_email'] ) ) {
			$metaargs[] = array(
									'key' 	=> $prefix.'term_user_email',
									'value' => $args['wpw_fp_email']
								);
		}
		
		if( !empty( $metaargs ) ) {
			$followtermusersargs['meta_query'] = $metaargs;
		}
		
		//if returns only id
		if( isset( $args['fields'] ) && !empty( $args['fields'] ) ) {
			$followtermusersargs['fields'] = $args['fields'];
		}
		
		//get order by records
		$followtermusersargs['order'] = 'DESC';
		$followtermusersargs['orderby'] = 'date';
		
		//fire query in to table for retriving data
		$result = new WP_Query( $followtermusersargs );
		
		if( isset( $args['count'] ) && $args['count'] == '1') {
			$followtermuserslist = $result->post_count;	
		}  else {
			//retrived data is in object format so assign that data to array for listing
			$followtermuserslist = $this->wpw_fp_object_to_array($result->posts);
		}
		
		return $followtermuserslist;
	}
	
	/**
	 * Get author Users data
	 * 
	 * Handles to get author users data for
	 * post type
	 * 
	 * @package Follow My Blog Post
 	 * @since 1.4.0
	 */
	public function wpw_fp_get_follow_author_users_data( $args = array() ) {
		
		$prefix = WPW_FP_META_PREFIX;
		
		$followauthorusersargs = array( 
										'post_type' => WPW_FP_AUTHOR_POST_TYPE,
										'post_status' => 'publish'
									);
		
		//show how many per page records
		if( isset( $args['posts_per_page'] ) && !empty( $args['posts_per_page'] ) ) {
			$followauthorusersargs['posts_per_page'] = $args['posts_per_page'];
		} else {
			$followauthorusersargs['posts_per_page'] = '-1';
		}
		
		//show per page records
		if( isset( $args['paged']) && !empty( $args['paged'] ) ) {
			$followauthorusersargs['paged'] = $args['paged'];
		}
		
		
		//if search using post parent
		if( isset( $args['authorid'] ) ) {
			$followauthorusersargs['post_parent'] = $args['authorid'];
		}
		
		//if search using author
		if( isset( $args['author'] ) ) {
			$followauthorusersargs['author'] = $args['author'];
		}
		
		//if search is called then retrive searching data
		if( isset( $args['search'] ) ) {
			//$followauthorusersargs['s'] = $args['search'];
			$metaargs[] = array(
									'key' 		=> $prefix.'author_user_email',
									'value' 	=> $args['search'],
									'compare'	=> 'LIKE'
								);
		}
		
		if( isset( $args['wpw_fp_status'] ) && !empty( $args['wpw_fp_status'] ) ) {
			$status = $args['wpw_fp_status'] == 'subscribe' ? '1' : '0';
			$metaargs[] = array(
									'key' 	=> $prefix.'follow_status',
									'value' => $status
								);
		}
		
		if( isset( $args['wpw_fp_email'] ) && !empty( $args['wpw_fp_email'] ) ) {
			$metaargs[] = array(
									'key' 	=> $prefix.'author_user_email',
									'value' => $args['wpw_fp_email']
								);
		}
		
		if( !empty( $metaargs ) ) {
			$followauthorusersargs['meta_query'] = $metaargs;
		}
		
		//if returns only id
		if( isset( $args['fields'] ) && !empty( $args['fields'] ) ) {
			$followauthorusersargs['fields'] = $args['fields'];
		}
		
		//get order by records
		$followauthorusersargs['order'] = 'DESC';
		$followauthorusersargs['orderby'] = 'date';
		
		//fire query in to table for retriving data
		$result = new WP_Query( $followauthorusersargs );
		
		if( isset( $args['count'] ) && $args['count'] == '1') {
			$followauthoruserslist = $result->post_count;	
		}  else {
			//retrived data is in object format so assign that data to array for listing
			$followauthoruserslist = $this->wpw_fp_object_to_array($result->posts);
		}
		
		return $followauthoruserslist;
	}
	
	/**
	 * Get Term User Logs data
	 * 
	 * Handles to get term user logs data for
	 * post type
	 * 
	 * @package Follow My Blog Post
 	 * @since 1.1.0
	 */
	public function wpw_fp_get_follow_term_user_logs_data( $args = array() ) {
		
		$prefix = WPW_FP_META_PREFIX;
		
		$followeduserlogssargs = array(
											'post_type'		=>	WPW_FP_TERM_LOGS_POST_TYPE,
											'post_status'	=>	'publish',
											'posts_per_page' => '-1'
										);
		
		//if search using post parent
		if( isset( $args['logid'] ) ) {
			$followeduserlogssargs['post_parent'] = $args['logid'];
		}
		
		//if returns only id
		if( isset( $args['fields'] ) && !empty( $args['fields'] ) ) {
			$followeduserlogssargs['fields'] = $args['fields'];
		}
		
		//if search is called then retrive searching data
		if( isset( $args['search'] ) ) {
			//$followeduserlogssargs['s'] = $args['search'];
			$metaargs[] = array(
									'key' 	=> $prefix.'log_email_data',
									'value' => $args['search'],
									'compare' => 'LIKE'
								);
		}
		
		if( !empty( $metaargs ) ) {
			$followeduserlogssargs['meta_query'] = $metaargs;
		}
		
		//fire query in to table for retriving data
		$result = new WP_Query( $followeduserlogssargs );
		
		//retrived data is in object format so assign that data to array for listing
		$followeduserlogslist = $this->wpw_fp_object_to_array($result->posts);
		
		return $followeduserlogslist;
	}
	
	/**
	 * Get author User Logs data
	 * 
	 * Handles to get author user logs data for
	 * post type
	 * 
	 * @package Follow My Blog Post
 	 * @since 1.4.0
	 */
	public function wpw_fp_get_follow_author_user_logs_data( $args = array() ) {
		
		$prefix = WPW_FP_META_PREFIX;
		
		$followeduserlogssargs = array(
											'post_type'		=>	WPW_FP_AUTHOR_LOGS_POST_TYPE,
											'post_status'	=>	'publish',
											'posts_per_page' => '-1'
										);
		
		//if search using post parent
		if( isset( $args['logid'] ) ) {
			$followeduserlogssargs['post_parent'] = $args['logid'];
		}
		
		//if returns only id
		if( isset( $args['fields'] ) && !empty( $args['fields'] ) ) {
			$followeduserlogssargs['fields'] = $args['fields'];
		}
		
		//if search is called then retrive searching data
		if( isset( $args['search'] ) ) {
			//$followeduserlogssargs['s'] = $args['search'];
			$metaargs[] = array(
									'key' 	=> $prefix.'log_email_data',
									'value' => $args['search'],
									'compare' => 'LIKE'
								);
		}
		
		if( !empty( $metaargs ) ) {
			$followeduserlogssargs['meta_query'] = $metaargs;
		}
		
		//fire query in to table for retriving data
		$result = new WP_Query( $followeduserlogssargs );
		
		//retrived data is in object format so assign that data to array for listing
		$followeduserlogslist = $this->wpw_fp_object_to_array($result->posts);
		
		return $followeduserlogslist;
	}
	
	/**
	 * Check Follow Email is exist
	 * 
	 * Handles to check follow email is exist
	 * into follow posts, terms and author
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_check_follow_email( $email ) {
		
		// args to check if this user_email is exist for follow post
		$args = array( 
						'wpw_fp_email' 	=> $email,
						'wpw_fp_status' => 'subscribe',
						'fields' 		=> 'ids'
					);
		
		$follow_posts = $this->wpw_fp_get_follow_post_users_data( $args );
		
		// args to check if this user_email is exist for follow term
		$args = array( 
						'wpw_fp_email' 	=> $email,
						'wpw_fp_status' => 'subscribe',
						'fields' 		=> 'ids'
					);
		
		$follow_terms = $this->wpw_fp_get_follow_term_users_data( $args );
		
		// args to check if this user_email is exist for follow author
		$args = array( 
						'wpw_fp_email' 	=> $email,
						'wpw_fp_status' => 'subscribe',
						'fields' 		=> 'ids'
					);
		
		$follow_authors = $this->wpw_fp_get_follow_author_users_data( $args );
		
		if( !empty( $follow_posts ) || !empty( $follow_terms ) || !empty( $follow_authors )) {
			$all_follows = array( 'follow_posts' => $follow_posts, 'follow_terms' => $follow_terms , 'follow_authors' => $follow_authors );
			return $all_follows;
		}
		return array();
	}
	
	/**
	 * Get Date Format
	 * 
	 * Handles to return formatted date which format is set in backend
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_get_date_format( $date, $time = false ) {
		
		$format = $time ? get_option( 'date_format' ).' '.get_option('time_format') : get_option('date_format');
		$date = date_i18n( $format, strtotime($date));
		return $date;
	}
	
	/**
	 * Get Short Content From Long Content
	 * 
	 * Handles to return content with specific
	 * string length
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 **/
	public function wpw_fp_short_content( $content, $charlength = 30 ) {
		
		//check content is large then characterlenght
		if ( strlen( $content ) > $charlength ) {
			
			$subex = substr( $content, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut =- ( strlen( $exwords[ count( $exwords ) - 1 ] ) );
			
			if ( $excut < 0 ) {
				$content = substr( $subex, 0, $excut );
			} else {
				$content = $subex;
			}
			$content = trim( $content ).'...';
		}	
		//return short content if long passed then length otherwise original content will be return
		return $content;
	}
	
	/**
	 * Check Post Update Notification for post
	 * 
	 * Handles to check post update notification for
	 * current post when post is going to update
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 **/
	public function wpw_fp_check_post_update_notification() {
		
		global $post, $wpw_fp_options;
		
		$post_type = get_post_type( $post->ID );
		
		// get post ids in which follow me will display
		$selected_posts = isset( $wpw_fp_options['notification_item_'.$post_type] ) ? $wpw_fp_options['notification_item_'.$post_type] : array();
		
		// get post types in which follow me will display
		$selected_post_types = isset( $wpw_fp_options['notification_type'] ) ? $wpw_fp_options['notification_type'] : array();
		
		// check if post have permission to display follow me form & checkbox
		if( !( in_array( $post->ID, $selected_posts ) || in_array( $post_type, $selected_post_types ) ) ) {
			return false;
		}
		
		// check if post have allow notification from Posts Notification Events > Trigger Emails > When post / page updated
		if( isset( $wpw_fp_options['post_trigger_notification']['post_update'] ) &&  $wpw_fp_options['post_trigger_notification']['post_update'] == '1' ) {
			return true;
		}
		
		// check if post have allow notification from Terms Notification Events > Trigger Emails > When post / page updated
		if( isset( $wpw_fp_options['term_trigger_notification']['post_update'] ) &&  $wpw_fp_options['term_trigger_notification']['post_update'] == '1' ) {
			return true;
		}
		
		// check if post have allow notification from Authors Notification Events > Trigger Emails > When post / page updated
		if( isset( $wpw_fp_options['author_trigger_notification']['post_update'] ) &&  $wpw_fp_options['author_trigger_notification']['post_update'] == '1' ) {
			return true;
		}
	}
	
	/**
	 * Send Global Email
	 * 
	 * Handles to send global email
	 * 
	 * @package Follow My Blog Post
	 * @since 1.2.0
	 */
	public function wpw_fp_send_email( $email, $subject, $message, $appendheader = '', $email_template = '', $unsubscribe = false ) {
		
		global $wpw_fp_options;
		
		$fromEmail = isset( $wpw_fp_options['from_email'] ) ? $wpw_fp_options['from_email'] : get_option('admin_email');
		
		$headers = 'From: '. $fromEmail . "\r\n";
		$headers .= "Reply-To: ". $fromEmail . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= $appendheader;
		
		if( !empty( $email_template ) ) {
			$email_template_option = $email_template;
		} else if( isset( $wpw_fp_options['email_template'] ) && !empty( $wpw_fp_options['email_template'] ) ) {
			$email_template_option = $wpw_fp_options['email_template'];
		} else {
			$email_template_option = 'default';
		}
		
		$message = do_shortcode( $message );
		$message = nl2br($message);
		
		$html = '';
		$html .= '<html>
					<head></head>
					<body>';
		$html = apply_filters( 'wpw_fp_email_template_' . $email_template_option, $html, $message, $unsubscribe );
		$html .= '	</body>
				</html>';
		
		$html = apply_filters( 'wpw_fp_email_html', $html, $message );
		
		$setmail = wp_mail( $email, $subject, $html, $headers );
		
		return $setmail;
	}
	
	/**
	 * Get Email Templates
	 * 
	 * Handles to get all email templates
	 * 
	 * @package Follow My Blog Post
	 * @since 1.2.0
	 */
	public function wpw_fp_email_get_templates() {
		
		$templates = array( 
								'' 			=> __( 'HTML Template', 'wpwfp' ),
								'plain' 	=> __( 'No template, plain text only', 'wpwfp' )
							);
		
		return $templates;
	}
	
	/**
	 * Send Emails With BCC for followers
	 * 
	 * Handle to send email with bcc for followers
	 * 
	 * @package Follow My Blog Post
	 * @since 1.5.1
	 */
	public function wpw_fp_send_mail_with_bcc( $followers, $subject, $message, $appendheader = '', $email_template = '', $unsubscribe = false ) {
		
		global $wpw_fp_options;
		
		$prefix = WPW_FP_META_PREFIX;
		
		$setmail = false;
		$recipients = '';
		if ( $wpw_fp_options['recipient_per_email'] == 1 ) {
			
			// foreach loop for send email to every user, then create log
			foreach ( $followers as $follower_email ) {
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($follower_email) || empty($follower_email) ) { continue; }
				
				// send email to each user individually
				$setmail = $this->wpw_fp_send_email( $follower_email, $subject, $message, $appendheader, $email_template, $unsubscribe );
			}
			
		} else if ( empty( $wpw_fp_options['recipient_per_email'] )  ) {
			
			// foreach loop for send email to every user, then create log
			foreach ( $followers as $follower_email ) {
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($follower_email) || empty($follower_email) ) { continue; }
				
				if ( !empty( $follower_email ) ) {
					
					 empty( $recipients ) ? $recipients = "$follower_email" : $recipients .= ", $follower_email";
					// Bcc Headers now constructed by phpmailer class
				}
			}
			
		} else {
			// we're using recipient_per_email
			$count = 1;
			$batch = array();
			// foreach loop for send email to every user, then create log
			foreach ( $followers as $follower_email ) {
				
				// sanity check -- make sure we have a valid email
				if ( !is_email($follower_email) || empty($follower_email) ) { continue; }
				
				if ( !empty( $follower_email ) ) {
					empty( $recipients ) ? $recipients = "$follower_email" : $recipients .= ", $follower_email";
					// Bcc Headers now constructed by phpmailer class
				}
				if ( $wpw_fp_options['recipient_per_email'] == $count ) {
					$count = 0;
					$batch[] = $recipients;
					$recipients = '';
				}
				$count++;
			}
			// add any partially completed batches to our batch array
			if ( !empty( $recipients ) ) {
				$batch[] = $recipients;
			}
		}
		
		// actually send mail
		if ( isset( $batch ) && !empty( $batch ) ) {
			foreach ( $batch as $recipients ) {
				$appendheader .= "Bcc: $recipients\n";
				// send email
				$setmail = $this->wpw_fp_send_email( '', $subject, $message, $appendheader, $email_template, $unsubscribe );
			}
		} else {
			if( !empty( $recipients ) ) {
				$appendheader .= "Bcc: $recipients\n";
				// send email
				$setmail = $this->wpw_fp_send_email( '', $subject, $message, $appendheader, $email_template, $unsubscribe );
			}
		}
		return $setmail;
	}
}
?>