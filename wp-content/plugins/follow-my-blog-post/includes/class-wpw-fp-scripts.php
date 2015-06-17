<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
class Wpw_Fp_Scripts {
	
	public function __construct() {
		
	}
	
	/**
	 * Enqueue Styles for admin
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_admin_print_styles( $hook_suffix ) {
		
		$pages_hook_suffix = array( 'post.php', 'post-new.php', 'follow-my-blog-post_page_wpw-fp-send-email', 'follow-my-blog-post_page_wpw-fp-settings', 'toplevel_page_wpw-fp-post', 'follow-my-blog-post_page_wpw-fp-term', 'follow-my-blog-post_page_wpw-fp-author' );
		
		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {
			
			wp_register_style( 'chosen-style', WPW_FP_URL . 'includes/css/chosen/chosen.css' );
			wp_enqueue_style( 'chosen-style' );
			
			wp_register_style( 'chosen-custom-style', WPW_FP_URL . 'includes/css/chosen/chosen-custom.css' );
			wp_enqueue_style( 'chosen-custom-style' );
			
			wp_register_style( 'wpw-fp-admin-styles', WPW_FP_URL . 'includes/css/wpw-fp-admin.css', array(), null );
			wp_enqueue_style( 'wpw-fp-admin-styles' );
		
			wp_register_style( 'wpw-fp-popup-styles', WPW_FP_URL . 'includes/css/wpw-fp-prettyphoto.css', array(), null );
			wp_enqueue_style( 'wpw-fp-popup-styles' );
		}
	}
	
	/**
	 * Enqueue Scripts for backend
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_admin_enqueue_scripts( $hook_suffix ) {
		
		global $wp_version;
		
		$pages_hook_suffix = array( 'post.php', 'post-new.php', 'follow-my-blog-post_page_wpw-fp-send-email', 'follow-my-blog-post_page_wpw-fp-settings', 'toplevel_page_wpw-fp-post', 'follow-my-blog-post_page_wpw-fp-term', 'follow-my-blog-post_page_wpw-fp-author' );
		
		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {

			wp_register_script( 'chosen', WPW_FP_URL . 'includes/js/chosen/chosen.jquery.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'chosen' );
			
			wp_register_script( 'ajax-chosen', WPW_FP_URL . 'includes/js/chosen/ajax-chosen.jquery.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'ajax-chosen' );
			
			wp_register_script( 'wpw-fp-admin-scripts', WPW_FP_URL . 'includes/js/wpw-fp-admin.js', array('jquery','jquery-ui-datepicker', 'jquery-ui-sortable' ) , null, true );
			wp_enqueue_script( 'wpw-fp-admin-scripts' );
			
			//localize script
			$newui = $wp_version >= '3.5' ? '1' : '0'; //check wp version for showing media uploader
			
			wp_localize_script( 'wpw-fp-admin-scripts', 'WpwFpSettings', array( 
																					'new_media_ui' 		=> $newui,
																					'resetmsg'			=> __( 'Click OK to reset all options. All settings will be lost!', 'wpwfp' ),
																					'testemailsuccess'	=> __( 'Test email has been sent successfully.', 'wpwfp' ),
																					'testemailerror'	=> __( 'Test email could not sent.', 'wpwfp' )
																				) );
			if (function_exists('wp_enqueue_media')){
				wp_enqueue_media();
			}
			
		
			wp_register_script( 'wpw-fp-popup-scripts', WPW_FP_URL . 'includes/js/wpw-fp-prettyphoto.js', array('jquery'), null );
			wp_enqueue_script( 'wpw-fp-popup-scripts' );
			
			// loads the required scripts for the meta boxes
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );
				
		}
	}
	
	/**
	 * Enqueue Styles for public
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_public_print_styles() {
		
		global $wpw_fp_options;
		
		wp_register_style( 'wpw-fp-public-style', WPW_FP_URL . 'includes/css/wpw-fp-public.css' );
		wp_enqueue_style( 'wpw-fp-public-style' );
	}
	
	/**
	 * Loading Additional Java Script
	 *
	 * Loads the JavaScript required for toggling the meta boxes on the theme settings page.
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_settings_page_load_scripts( $hook_suffix ) { 
		?>				
			<script type="text/javascript">
				//<![CDATA[
				jQuery(document).ready( function($) {
					$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
					postboxes.add_postbox_toggles( 'follow-my-blog-post_page_wpw-fp-settings' );
				});
				//]]>
			</script>
		<?php 	
			//Test preview for email template from email settings
		?>	
			<script type="text/javascript">
	
				//<![CDATA[
				jQuery(document).ready(function(){
					
				   	jQuery('a[rel^="wpw_fp_preview_follow_email"]').prettyPhoto({
				   		social_tools:"",
				   		default_width:1000,
				   		theme:"facebook", /* light_rounded / dark_rounded / light_square / dark_square / facebook */
				   		changepicturecallback: function(){
							jQuery('#wpw_fp_preview_follow_email').hide();
				   			jQuery('.pp_content_container .pp_content').css('height', 'auto');
				   			jQuery('.pp_content_container .pp_content').css('padding-bottom', '35px');
				   			var email_template = jQuery('.wpw-fp-email-template').val();
							jQuery('.wpw-fp-preview-popup').hide();
							if( email_template != '' ) {
								jQuery('.wpw-fp-preview-'+email_template+'-popup').show();
							} else {
								jQuery('.wpw-fp-preview-default-popup').show();
							}
				   		}
				   	});
				});
				//]]>
			</script>
		<?php
	}
	
	/**
	 * Loading Additional Java Script
	 *
	 * Loads the JavaScript required for toggling the meta boxes on the theme settings page.
	 *
	 * @package Follow My Blog Post
	 * @since 1.4.4
	 */
	public function wpw_fp_send_email_page_load_scripts( $hook_suffix ) { 
		?>				
			<script type="text/javascript">
				//<![CDATA[
				jQuery(document).ready( function($) {
					$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
					postboxes.add_postbox_toggles( 'follow-my-blog-post_page_wpw-fp-send-email' );
				});
				//]]>
			</script>
	<?php
	}
	
	/**
	 * Enqueue Scripts for frontside
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function wpw_fp_front_scripts() {

		global $wpw_fp_options;
		
		wp_register_script( 'wpw-fp-public-scripts', WPW_FP_URL . 'includes/js/wpw-fp-public.js', array('jquery' ) , null, true );
		wp_enqueue_script( 'wpw-fp-public-scripts' );
		
		$loggin_flag = is_user_logged_in() ? '1' : '0';
		wp_localize_script( 'wpw-fp-public-scripts', 'WpwFpPublic', array( 
																				'ajaxurl'		=> admin_url('admin-ajax.php'),
																				'emailempty' 	=> __( 'Please enter email.', 'wpwfp' ),
																				'emailinvalid'	=> __( 'Please enter valid email.', 'wpwfp' ),
																				'loginflag'		=> $loggin_flag,
																				'checkemail'	=> __( 'Please check your email inbox to confirm subscribtion.', 'wpwfp' ),
																				'processing'	=> __( 'Processing', 'wpwfp')
																			) );
		
	}
	
	/**
	 * Display button in post / page container
	 *
	 * Handles to display button in post / page container
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_shortcode_display_button( $buttons ) {
	 
		array_push( $buttons, "|", "wpw_fp_follow_post" );
		return $buttons;
	}
	
	/**
	 * Include js for add button in post / page container
	 *
	 * Handles to include js for add button in post / page container
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_shortcode_button($plugin_array) {
	 
		$plugin_array['wpw_fp_follow_post'] = WPW_FP_URL . 'includes/js/wpw-fp-shortcodes.js';
		return $plugin_array;
	}
	
	/**
	 * Display button in post / page container
	 * 
	 * Handles to display button in post / page container
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	public function wpw_fp_add_shortcode_button() {
		
		if( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) ) {
			add_filter( 'mce_external_plugins', array( $this, 'wpw_fp_shortcode_button' ) );
   			add_filter( 'mce_buttons', array( $this, 'wpw_fp_shortcode_display_button' ) );
		}
		
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding proper hoocks for the scripts.
	 *
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//add styles for admin
		add_action( 'admin_enqueue_scripts', array( $this, 'wpw_fp_admin_print_styles' ) );
		
		//add scripts for admin 
		add_action( 'admin_enqueue_scripts', array( $this, 'wpw_fp_admin_enqueue_scripts') );
		
		//add styles for public
		add_action( 'wp_enqueue_scripts', array( $this, 'wpw_fp_public_print_styles' ) );
		
		//script for front side
		add_action( 'wp_enqueue_scripts', array( $this, 'wpw_fp_front_scripts' ) );
		
		// add filters for add add button in post / page container
		add_action( 'admin_init', array( $this, 'wpw_fp_add_shortcode_button' ) );
		
	}
}
?>