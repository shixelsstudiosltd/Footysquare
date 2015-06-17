<?php 

/**
 * Template For Unsubscribe Form
 * 
 * Handles to return design unsubscribe form
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/unsubscribe/content.php
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $wpw_fp_message;

	if( $wpw_fp_message->size( 'wpw-fp-unsubscribe' ) > 0 ) { //make success message
		echo $wpw_fp_message->output( 'wpw-fp-unsubscribe' );
	}
?>
	<div class="wpw-fp-unsubscribe-email-error"></div>
	<form method="post" action="">
		<table class="wpw-fp-unsubscribe-table">
			<tr>
				<td width="15%">
					<label for="wpw_fp_unsubscribe_email"><?php _e( 'Your email:', 'wpwfp' ) ?></label>
				</td>
				<td>
					<input type="text" name="wpw_fp_unsubscribe_email" id="wpw_fp_unsubscribe_email" value="" placeholder="<?php _e( 'Enter email address...', 'wpwfp' ) ?>" size="30" />
				</td>
			</tr>
			<tr>
				<td width="15%">
				</td>
				<td>
					<input type="submit" class="wpw-fp-btn" id="wpw_fp_unsubscribe_submit" name="wpw_fp_unsubscribe_submit" value="<?php _e( 'Unsubscribe', 'wpwfp' ) ?>">
				</td>
			</tr>
		</table>
	</form>