<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page
 *
 * The code for the plugins main settings page
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */
 ?>
<div class="wrap">

	<?php
		global $wpdb,$wpw_fp_model,$wpw_fp_options,$wpw_fp_message; // call globals to use them in this page
		
		// model class
		$model = $wpw_fp_model; 
		
		// message class
		$message = $wpw_fp_message;
		
	?>
	<!-- wpweb logo -->
	<img src="<?php echo WPW_FP_IMG_URL . '/wpweb-logo.png'; ?>" class="wpweb-logo" alt="<?php _e( 'WP Web Logo', 'wpwfp' );?>" />
	<!-- plugin name -->
	<h2><?php _e( 'Follow My Blog Post - Settings', 'wpwfp' ); ?></h2><br />
	<!-- settings reset -->
	<?php
		if( isset( $_POST['wpw_fp_reset_settings'] ) && $_POST['wpw_fp_reset_settings'] == __( 'Reset All Settings', 'wpwfp' ) ) {
			
			wpw_fp_default_settings(); // set default settings
			
			//reseting facebook fan page posting setting session
			echo '<div id="message" class="updated fade"><p><strong>' . __( 'All Settings Reset Successfully.', 'wpwfp') . '</strong></p></div>'; 
			
		} else if( isset( $_GET['settings-updated'] ) && !empty( $_GET['settings-updated'] ) ) { //check settings updated or not
			
			//reseting facebook fan page posting setting session
			echo '<div id="message" class="updated fade"><p><strong>' . __( 'Changes Saved.', 'wpwfp') . '</strong></p></div>'; 
		}
	?>
		
	<!--plugin reset settings button-->		
	<?php
		echo apply_filters ( 'wpweb_fb_settings_submit_button', '<form action="" method="POST" id="wpw-fp-reset-settings-form">
			<div class="wpw-fp-reset-wrapper">
				<input type="submit" class="button-primary" name="wpw_fp_reset_settings" id="wpw_fp_reset_settings" value="' . __( 'Reset All Settings', 'wpwfp' ) . '" />
			</div>
		</form>' );
	?>
	
	<form action="options.php" method="post">
		
		<?php settings_fields( 'wpw_fp_plugin_options' );
	    	    
	    $general_tab = $notification_tab = $emails_tab = '';
		$general_content = $notification_content = $emails_content = '';
		$selected_tab = 'general';
		
		if( $message->size( 'wpw-fp-selected-tab' ) > 0 ) { //make tab selected 
			$selected_tab = $message->messages[0]['text'];
		}
		
		switch( $selected_tab ) {
				
			case 'notification' : 
							$notification_tab = ' nav-tab-active';
							$notification_content = ' wpw-fp-selected-tab';
							break;
			case 'emails' : 
							$emails_tab = ' nav-tab-active';
							$emails_content = ' wpw-fp-selected-tab';
							break;
			case 'general' : 
							$general_tab = ' nav-tab-active';
							$general_content = ' wpw-fp-selected-tab';
							break;
		}
	?>
	
		<!-- beginning of the left meta box section -->
		<div class="content wpw-fp-content-section">
		
			<h2 class="nav-tab-wrapper wpw-fp-h2">
				
		        <a class="nav-tab<?php echo $general_tab; ?>" href="#wpw-fp-tab-general"><?php _e('General','wpwfp');?></a>
		        <a class="nav-tab<?php echo $notification_tab; ?>" href="#wpw-fp-tab-notification"><?php _e('Notification','wpwfp');?></a>
		        <a class="nav-tab<?php echo $emails_tab; ?>" href="#wpw-fp-tab-emails"><?php _e('Emails','wpwfp');?></a>
		        
		    </h2><!--nav-tab-wrapper-->
		    <input type="hidden" id="wpw_fp_selected_tab" name="wpw_fp_options[selected_tab]" value="<?php echo $selected_tab;?>"/>
		    <!--beginning of tabs panels-->
			 <div class="wpw-fp-content">
			 	
			 	<div class="wpw-fp-tab-content<?php echo $general_content; ?>" id="wpw-fp-tab-general"> 
				 	<?php   
				 		// General
						require( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-general-settings.php' ); 
					?>
			 	</div>
			 	<div class="wpw-fp-tab-content<?php echo $notification_content; ?>" id="wpw-fp-tab-notification"> 
			 		<?php   
				 		// Payment Gateways
						require( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-notification-settings.php' ); 
					?>
			 	</div>
			 	
			 	<div class="wpw-fp-tab-content<?php echo $emails_content; ?>" id="wpw-fp-tab-emails"> 
			 		<?php   
				 		// Emails
						require( WPW_FP_ADMIN_DIR . '/forms/wpw-fp-emails-settings.php' ); 
					?>
			 	</div>
			 	
			 <!--end of tabs panels-->
			 </div>
		<!--end of the left meta box section -->
		</div><!--.content wpw-fp-content-section-->
	</form>
	<div class="wpw_fp_overlay"></div>
	<!-- including the about box -->
<!--end .wrap-->
</div>