<?php 

/**
 * Template For Follow Author Register User Content
 * 
 * Handles to return design follow author register user content
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/follow-author/user.php
 *
 * @package Follow My Blog Post
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
	
	<button type="button" class="wpw-fp-follow-btn wpw-fp-button wpw_fp_left <?php echo $follow_class; ?>" data-status="<?php echo $follow_status; ?>" data-author-id="<?php echo $author_id; ?>" data-current-postid="<?php echo $current_post_id; ?>" data-follow-text="<?php echo $follow_text; ?>" data-following-text="<?php echo $following_text; ?>" data-unfollow-text="<?php echo $unfollow_text; ?>" >
		<span class="wpw-following-text"><?php echo $follow_label; ?></span>
		<span class="wpw-unfollowing-text wpw-fp-display-none"><?php echo $unfollow_text; ?></span>
		<span class="wpw_fp_follow_loader"><img src="<?php echo WPW_FP_IMG_URL . '/ajax-loader.gif'; ?>" alt="<?php echo __( '...', 'wpwfp' ); ?>" /></span>
	</button>
					
	<?php
		
		// Check follow message is not empty from meta or settings
		if( !empty( $follow_message ) ) {
			
			do_action( 'wpw_fp_follow_author_count_box', $follow_message, $author_id );
		}
	?>			
	<div class='wpw_fp_clear'></div>