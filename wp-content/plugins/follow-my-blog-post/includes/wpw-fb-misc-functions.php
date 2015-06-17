<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Misc Functions
 * 
 * All misc functions handles to 
 * different functions 
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 *
 */

/**
 * Get Followers Count for Post / Page / Custom Post Types
 * 
 * Handles to get followers count of post / page 
 * / custom post type by post id
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 * @return 
 **/
function wpw_fp_get_post_followers_count( $post_id ) {
	
	//check if post id empty then return zero
	if( empty( $post_id ) ) { return 0; }
	
	$prefix = WPW_FP_META_PREFIX;
	
	//arguments to collect followers data by post
	$args = array( 
					'post_status'	=>	'publish',
					'post_parent' 	=>	$post_id,
					'posts_per_page'=>	'-1',
					'post_type' 	=>	WPW_FP_POST_TYPE,
					'meta_key'		=>	$prefix.'follow_status',
					'meta_value'	=>	'1'
				);
	
	//get data for post followed by users
	$data = get_posts( $args );
	
	//get followers count
	$counts = count( $data );
	
	//return followers count
	return $counts;
}

/**
 * Get Followers Countzz for Post / Page / Custom Post Types
 * 
 * Handles to get followers count of post / page 
 * / custom post type by post id
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 * @return 
 **/
function wpw_fp_get_post_followers_country( $post_id,$get_countries=null,$get_names=null ) {
	
	//check if post id empty then return zero
	if( empty( $post_id ) ) { return 0; }
	
	$prefix = WPW_FP_META_PREFIX;
	//echo $post_id;
	//arguments to collect followers data by post
	$args = array( 
					'post_status'	=>	'publish',
					'post_parent' 	=>	$post_id,
					'posts_per_page'=>	'-1',
					'post_type' 	=>	WPW_FP_POST_TYPE,
					'meta_key'		=>	$prefix.'follow_status',
					'meta_value'	=>	'1'
				);
	
	//get data for post followed by users
	$userdata = get_posts( $args );
	$count_countries=0;
	$total_countries = count( $userdata );
	$show_countries=5;
	
	if($get_names){
		foreach($userdata as $user_data){
			$userID=$user_data->post_author;
			$follow_user = get_userdata($userID);
			$user_country=do_shortcode('[wps-usermeta meta="wpspro_country"]');
			echo '<p>'.$follow_user->user_login.' <span class="flag flag-';
				get_country_code($user_country);
			echo '"></span><span>'.$user_country.'</span></p>';
			$count_countries++;
			if($count_countries>$show_countries){
				//get followers count
				echo '<p class="text-uppercase">'.$total_countries-$show_countries.'</p>';
				break;
			}
		}
	}
	
	else if($get_countries){
		foreach($userdata as $user_data){
			$userID=$user_data->post_author;
			$follow_user = get_userdata($userID);
			$user_country=do_shortcode('[wps-usermeta meta="wpspro_country"]');
			echo '<p><span class="flag flag-';
				get_country_code($user_country);
			echo '"></span><span>'.$user_country.'</span></p>';
			$count_countries++;
			if($count_countries>$show_countries){
				//get followers count
				echo '<p class="text-uppercase">'.$total_countries-$show_countries.'</p>';
				break;
			}
		}
	}
	else{
		foreach($userdata as $user_data){
			$userID=$user_data->post_author;
			$follow_user = get_userdata($userID);
			echo '<a href="'.get_site_url().'/?page_id=1770&user_id='.$userID.'"><span class="follower-name">'.$follow_user->user_login.'</span></a>';
		}
	}
	
}


/**
 * Get Taxonomy Terms Followers Count
 * 
 * Handles to get followers count of term by term id
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 *
 */
function wpw_fp_get_term_followers_count( $term_id ) {
	
	//check if term id empty then return zero
	if( empty( $term_id ) ) { return 0; }
		
	$prefix = WPW_FP_META_PREFIX;
	
	//arguments to collect followers data by term
	$args = array( 
					'post_status'	=>	'publish',
					'post_type' 	=>	WPW_FP_TERM_POST_TYPE,
					'post_parent' 	=>	$term_id,
					'posts_per_page'=>	'-1',
					'meta_key'		=>	$prefix.'follow_status',
					'meta_value'	=>	'1'
				);
	
	//get data for term followed by users
	$data = get_posts( $args );
	
	//get followers count
	$counts = count( $data );
	
	return $counts;
}

/**
 * Get Authors Followers Count
 * 
 * Handles to get followers count of author by author id
 * 
 * @package Follow My Blog Post
 * @since 1.4.0
 *
 */
function wpw_fp_get_author_followers_count( $author_id ) {
	
	//check if author id empty then return zero
	if( empty( $author_id ) ) { return 0; }
		
	$prefix = WPW_FP_META_PREFIX;
	
	//arguments to collect followers data by author
	$args = array( 
					'post_status'	=>	'publish',
					'post_type' 	=>	WPW_FP_AUTHOR_POST_TYPE,
					'post_parent' 	=>	$author_id,
					'posts_per_page'=>	'-1',
					'meta_key'		=>	$prefix.'follow_status',
					'meta_value'	=>	'1'
				);
	
	//get data for author followed by users
	$data = get_posts( $args );
	
	//get followers count
	$counts = count( $data );
	
	return $counts;
}


/**
 * Get Authors Followers Names
 * 
 * Handles to get followers count of author by author id
 * 
 * @package Follow My Blog Post
 * @since 1.4.0
 *
 */
function wpw_fp_get_author_followers_Names( $author_id,$get_countries=null ) {
	
	//check if author id empty then return zero
	if( empty( $author_id ) ) { return 0; }
		
	$prefix = WPW_FP_META_PREFIX;
	
	//arguments to collect only 2 followers data by author
	$args = array( 
					'post_status'	=>	'publish',
					'post_type' 	=>	WPW_FP_AUTHOR_POST_TYPE,
					'post_parent' 	=>	$author_id,
					'posts_per_page'=>	'2',
					'meta_key'		=>	$prefix.'follow_status',
					'meta_value'	=>	'1'
				);
	
	//get data for author followed by users
	$userdata = get_posts( $args );
	$count_countries=0;
	$total_countries = count( $userdata );
	$show_countries=5;
	
	if($get_countries){
		foreach($userdata as $user_data){
			$userID=$user_data->post_author;
			$follow_user = get_userdata($userID);
			$user_country=do_shortcode('[wps-usermeta meta="wpspro_country"]');
			echo '<p><span class="flag flag-';
				get_country_code($user_country);
			echo '"></span><span>'.$user_country.'</span></p>';
			$count_countries++;
			if($count_countries>$show_countries){
				//get followers count
				echo '<p class="text-uppercase">'.$total_countries-$show_countries.'</p>';
				break;
			}
		}
	}
	else{
		foreach($userdata as $user_data){
			$userID=$user_data->post_author;
			$follow_user = get_userdata($userID);
			echo '<a href="'.get_site_url().'/?page_id=1770&user_id='.$userID.'"><span class="follower-name">'.$follow_user->user_login.'</span></a>';
		}
	}
	
}


/**
 * Get Authors Followers Names
 * 
 * Handles to get followers count of author by author id
 * 
 * @package Follow My Blog Post
 * @since 1.4.0
 *
 */
function wpw_fp_check_user_follow($author_id) {
	
	global $wpw_fp_model;
	$model = $wpw_fp_model;
	//check if author id empty then return zero
	if( empty( $author_id ) ) { return 0; }
		
	$prefix = WPW_FP_META_PREFIX;
	
	//arguments to collect only 2 followers data by author
	$argsdata = array(
						'author' 			=>	$author_id,
						'posts_per_page' 	=>	$perpage,
						'paged'				=>	'1'
					);
	$followauthors = $model->wpw_fp_get_follow_author_users_data( $argsdata );
	//get data for author followed by users
	//$userdata = get_posts( $argsdata );
	//var_dump($followauthors);
	foreach($followauthors as $followauthor){
		$follow_authors[] = $followauthor['post_parent'];
		//echo $author_id.' follows '.$followauthor['post_parent'].'<br/>';
	}
	return $follow_authors;
	//}
	
}

/**
 * Get Authors Followers By Country
 * 
 * Handles to get followers count of author by author id
 * 
 * @package Follow My Blog Post
 * @since 1.4.0
 *
 */
function wpw_fp_get_author_followers_Country( $author_id ) {
	
	//check if author id empty then return zero
	if( empty( $author_id ) ) { return 0; }
		
	$prefix = WPW_FP_META_PREFIX;
	
	//arguments to collect only 2 followers data by author
	$args = array( 
					'post_status'	=>	'publish',
					'post_type' 	=>	WPW_FP_AUTHOR_POST_TYPE,
					'post_parent' 	=>	$author_id,
					'posts_per_page'=>	'5',
					'meta_key'		=>	$prefix.'follow_status',
					'meta_value'	=>	'1'
				);
	
	//get data for author followed by users
	$userdata = get_posts( $args );
	foreach($userdata as $user_data){
		$userID=$user_data->post_author;
		$follow_user = get_userdata($userID);
		
		$user_country=do_shortcode('[wps-usermeta meta="wpspro_country"]');
		$country=get_country_code($user_country);
		
		echo '<a href="'.get_site_url().'/?page_id=1770&user_id='.$userID.'"><p class="follower-name">';
		echo '<span class="flag flag-'.$country.'"></span>';
		echo $follow_user->user_login.'</p></a>';
	}
}

?>