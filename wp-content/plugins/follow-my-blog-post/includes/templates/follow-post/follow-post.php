<?php 

/**
 * Template For Follow Post Wrapper
 * 
 * Handles to return design follow post wrapper
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/follow-post/follow-post.php
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

	<div class="wpw-fp-follow-post-wrapper wpw-fp-follow-btn-wrapper">
		<?php
			do_action( 'wpw_fp_follow_post_content', $args );
		?>
	</div><!-- wpw-fp-follow-post-wrapper -->