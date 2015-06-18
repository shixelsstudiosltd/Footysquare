<?php 

/**
 * Template For Follow Post Guest User Content
 * 
 * Handles to return design follow post guest user content
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/follow-post/guest.php
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
	<?php 
		if( !empty( $follow_pos_class ) ) {
			echo "<div class='{$follow_pos_class}'>";
		}
	?>
	
		<a href="javascript:void(0);" class="wpw-fp-follow-button wpw-fp-button wpw_fp_left wpw-fp-guest-btn" >
			<span class="wpw-following-text"><?php echo $follow_text; ?></span>
		</a>
	
		<?php
			
			// Check follow message is not empty from meta or settings
			if( !empty( $follow_message ) ) {
				
				do_action( 'wpw_fp_follow_post_count_box', $follow_message, $post_id );
				
			}
		?>
		<div class="wpw-fp-follow-post-popup wpw-fp-follow-popup">
					
			<div class="wpw-fp-popup-header">
				<span class="wpw-fp-popup-close"><?php echo __( 'X', 'wpwfp' ); ?></span>
				<h3 id="wpw_fp_popup_label"><?php echo $follow_text; ?></h3>
			</div>
		
			<form class="wpw_fp_follow_form" name="wpwfollowform" method="post" action="">
		
				<div class="wpw-fp-popup-body">
					<input type="hidden" name="followstatus" value="1">
					<div class="wpw_fp_left wpw_fp_popup_label">
						<strong><?php echo __( 'E-mail :', 'wpwfp' ); ?> </strong>
					</div>
					<div class="wpw_fp_left wpw-fp-followsemail-wrp">
						<input type="text" name="followsemail" class="wpw-fp-followsemail" value="" />
					</div>
					<div class="wpw_fp_clear"></div>
					<div class="wpw_fp_follow_email_error"></div>
				</div>
		
				<div class="wpw-fp-popup-footer">
					<button type="button" class="wpw-fp-follow-button wpw-fp-button wpw-fp-follow-btn" data-status="1" data-postid="<?php echo $post_id; ?>" data-current-postid="<?php echo $current_post_id; ?>" data-follow-text="<?php echo $follow_text; ?>" data-following-text="<?php echo $following_text; ?>" data-unfollow-text="<?php echo $unfollow_text; ?>" >
						<span class="wpw-following-text"><?php echo $follow_text; ?></span>
						<span class="wpw-unfollowing-text wpw-fp-display-none"><?php echo $unfollow_text; ?></span>
						<span class="wpw_fp_follow_loader"><img src="<?php echo WPW_FP_IMG_URL . '/ajax-loader.gif'; ?>" alt="..." /></span>
					</button>
				</div>
			
			</form>
		
		</div><!--.wpw-fp-follow-post-popup-->
		
	<?php 
		if( !empty( $follow_pos_class ) ) {
			echo "</div>";
		}
	?>
	<div class="wpw-fp-post-popup-overlay wpw-fp-popup-overlay"></div>
	<div class='wpw_fp_clear'></div>