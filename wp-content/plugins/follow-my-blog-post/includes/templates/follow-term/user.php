<?php 

/**
 * Template For Follow Term Register User Content
 * 
 * Handles to return design follow term register user content
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/follow-term/user.php
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
	
	<button type="button" class="wpw-fp-follow-btn wpw-fp-button wpw_fp_left <?php echo $follow_class; ?>" data-status="<?php echo $follow_status; ?>" data-posttype="<?php echo $follow_posttype; ?>" data-taxonomy-slug="<?php echo $follow_taxonomy_slug; ?>" data-term-id="<?php echo $follow_term_id; ?>" data-current-postid="<?php echo $current_post_id; ?>" data-follow-text="<?php echo $follow_text; ?>" data-following-text="<?php echo $following_text; ?>" data-unfollow-text="<?php echo $unfollow_text; ?>" >
		<span class="wpw-following-text"><?php echo $follow_label; ?></span>
		<span class="wpw-unfollowing-text wpw-fp-display-none"><?php echo $unfollow_text; ?></span>
		<span class="wpw_fp_follow_loader"><img src="<?php echo WPW_FP_IMG_URL . '/ajax-loader.gif'; ?>" alt="..." /></span>
	</button>
					
	<?php
		
		// Check follow message is not empty from meta or settings
		if( !empty( $follow_message ) ) {
			
			do_action( 'wpw_fp_follow_term_count_box', $follow_message, $follow_term_id );
			
		}
	?>			
	<div class='wpw_fp_clear'></div>