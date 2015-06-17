<?php 

/**
 * Template For Follow Post Register User Content
 * 
 * Handles to return design follow post register user content
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/follow-post/user.php
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
		<button type="button" class="wpw-fp-follow-btn wpw-fp-button wpw_fp_left <?php echo $follow_class; ?>" data-status="<?php echo $follow_status; ?>" data-postid="<?php echo $post_id; ?>" data-current-postid="<?php echo $current_post_id; ?>" data-follow-text="<?php echo $follow_text; ?>" data-following-text="<?php echo $following_text; ?>" data-unfollow-text="<?php echo $unfollow_text; ?>" >
			<span class="wpw-following-text"><?php echo $follow_label; ?></span>
			<span class="wpw-unfollowing-text wpw-fp-display-none"><?php echo $unfollow_text; ?></span>
			<span class="wpw_fp_follow_loader"><img src="<?php echo WPW_FP_IMG_URL . '/ajax-loader.gif'; ?>" alt="..." /></span>
		</button>
						
		<?php
			
			// Check follow message is not empty from meta or settings
			if( !empty( $follow_message ) ) {
				
				do_action( 'wpw_fp_follow_post_count_box', $follow_message, $post_id );
				
			}
		?>
	<?php 
		if( !empty( $follow_pos_class ) ) {
			echo "</div>";
		}
	?>
	<div class='wpw_fp_clear'></div>