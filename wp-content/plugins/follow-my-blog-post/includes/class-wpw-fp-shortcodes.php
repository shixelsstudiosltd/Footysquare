<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortcodes Class
 *
 * Handles shortcodes functionality of plugin
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
class Wpw_Fp_Shortcodes {
	
	public $model;
	
	public function __construct(){
		
		global $wpw_fp_model;
		
		$this->model = $wpw_fp_model;
	}
	
	/**
	 * Follow Post Shortcode
	 *
	 * Handles to replace the shortcode follow post
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_follow_shortcode( $atts, $content ) {
		
		global $post, $wpw_fp_options;
		
		extract( shortcode_atts( array(	
	    	'id'				=>	$post->ID,
	    	'disablecount'		=> 	'false',
	    	'followerscountmsg'	=> 	'',
	    	'followtext'		=>	'',
	    	'followingtext'		=>	'',
	    	'unfollowtext'		=>	'',
		), $atts ) );
		
		$html = $followcountmsg = '';
		
		if( $disablecount != 'true' ) { // Check not disable follow count
			// follow counter message
			$followcountmsg = !empty( $followerscountmsg ) ? $followerscountmsg : $wpw_fp_options['follow_message'];
		}
		$args = array(
							'post_id'			=> $id,
							'follow_message'	=> $followcountmsg,
							'follow_buttons'	=> array(
																'follow' 	=> $followtext, // html_entity_decode( $followtext ),
																'following' => $followingtext, // html_entity_decode( $followingtext ),
																'unfollow' 	=> $unfollowtext, // html_entity_decode( $unfollowtext ),
															),
						);
					
		ob_start();
		do_action( 'wpw_fp_follow_post', $args );
		$html .= ob_get_clean();
		
		$content = $content.$html;
		
		return $content;
	}
	
	/**
	 * Follow Term Shortcode
	 *
	 * Handles to replace the shortcode follow term
	 *
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_follow_term_shortcode( $atts, $content ) {
		
		global $post, $wpw_fp_options;
		
		extract( shortcode_atts( array(	
	    	'posttype'			=>	'',
	    	'taxonomy'			=>	'',
	    	'termid'			=>	'',
	    	'disablecount'		=> 	'false',
	    	'followerscountmsg'	=> 	'',
	    	'followtext'		=>	'',
	    	'followingtext'		=>	'',
	    	'unfollowtext'		=>	'',
		), $atts ) );
		
		$html = $followcountmsg = '';
		if( !empty( $taxonomy ) && !empty( $termid ) ) { // Check texonomy and termid are not empty
			
			if( $disablecount != 'true' ) { // Check not disable follow count
				// follow counter message
				$followcountmsg 	= !empty( $followerscountmsg ) ? $followerscountmsg : $wpw_fp_options['term_follow_message'];
			}
			$args = array(
								'follow_posttype'	=> $posttype,
								'follow_taxonomy'	=> $taxonomy,
								'follow_term_id'	=> $termid,
								'follow_message'	=> $followcountmsg,
								'follow_buttons'	=> array(
																	'follow' 	=> $followtext, // html_entity_decode( $followtext ),
																	'following' => $followingtext, // html_entity_decode( $followingtext ),
																	'unfollow' 	=> $unfollowtext, // html_entity_decode( $unfollowtext ),
																),
							);
							
			ob_start();
			do_action( 'wpw_fp_follow_term', $args );
			$html .= ob_get_clean();
			
		}
		$content = $content.$html;
		
		return $content;
	}
	
	/**
	 * Follow Term Shortcode
	 *
	 * Handles to replace the shortcode follow author
	 *
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	public function wpw_fp_follow_author_shortcode( $atts, $content ) {
		
		global $post, $wpw_fp_options;
		
		$post_author = isset( $post->post_author ) ?  $post->post_author : '';
		extract( shortcode_atts( array(	
	    	'author_id'			=>	$post_author,
	    	'disablecount'		=> 	'false',
	    	'followerscountmsg'	=> 	'',
	    	'followtext'		=>	'',
	    	'followingtext'		=>	'',
	    	'unfollowtext'		=>	'',
		), $atts ) );
		
		$html = $followcountmsg = '';
		
		if( !empty( $author_id ) ) { // Check author id not empty
			
			if( $disablecount != 'true' ) { // Check not disable follow count
				// follow counter message
				$followcountmsg 	= !empty( $followerscountmsg ) ? $followerscountmsg : $wpw_fp_options['authors_follow_message'];
			}
			$args = array(
								'author_id'			=> $author_id,
								'follow_message'	=> $followcountmsg,
								'follow_buttons'	=> array(
																	'follow' 	=> $followtext, // html_entity_decode( $followtext ),
																	'following' => $followingtext, // html_entity_decode( $followingtext ),
																	'unfollow' 	=> $unfollowtext, // html_entity_decode( $unfollowtext ),
																),
							);
							
			ob_start();
			do_action( 'wpw_fp_follow_author', $args );
			$html .= ob_get_clean();
			
		}
		$content = $content.$html;
		
		return $content;
	}
	
	/**
	 * Manage Follow Posts
	 *
	 * Handles to replace the shortcode for manage follow posts
	 *
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_follow_post_list_shortcode( $atts, $content ) {
		
		ob_start();
		do_action( 'wpw_fp_manage_follow_posts' );
		$content .= ob_get_clean();
		
		return $content;
	}
	
	/**
	 * Manage Follow Terms
	 *
	 * Handles to replace the shortcode for manage follow terms
	 *
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_follow_term_list_shortcode( $atts, $content ) {
		
		ob_start();
		do_action( 'wpw_fp_manage_follow_terms' );
		$content .= ob_get_clean();
		
		return $content;
	}
	
	/**
	 * Manage Follow Authors
	 *
	 * Handles to replace the shortcode for manage follow author
	 *
	 * @package Follow My Blog Post
	 * @since 1.4.0
	 */
	public function wpw_fp_follow_author_list_shortcode( $atts, $content ) {
		
		ob_start();
		do_action( 'wpw_fp_manage_follow_authors' );
		$content .= ob_get_clean();
		
		return $content;
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding hooks for calling shortcodes.
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 **/
	public function add_hooks() {
		
		//add filter to use shortcode for text widget
		add_filter( 'widget_text', 'do_shortcode' );
		
		//change the content using shortcode
		add_shortcode( 'wpw_follow_me', array( $this, 'wpw_fp_follow_shortcode' ) );
		
		//change the content using shortcode
		add_shortcode( 'wpw_follow_term_me', array( $this, 'wpw_fp_follow_term_shortcode' ) );
	
		//change the content using shortcode
		add_shortcode( 'wpw_follow_author_me', array( $this, 'wpw_fp_follow_author_shortcode' ) );
	
		//change the content using shortcode
		add_shortcode( 'wpw_follow_post_list', array( $this, 'wpw_fp_follow_post_list_shortcode' ) );
		
		//change the content using shortcode
		add_shortcode( 'wpw_follow_term_list', array( $this, 'wpw_fp_follow_term_list_shortcode' ) );
		
		//change the content using shortcode
		add_shortcode( 'wpw_follow_author_list', array( $this, 'wpw_fp_follow_author_list_shortcode' ) );
		
	}
}
?>