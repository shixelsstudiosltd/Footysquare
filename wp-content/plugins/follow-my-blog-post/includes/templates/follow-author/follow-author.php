<?php 

/**
 * Template For Follow Author Wrapper
 * 
 * Handles to return design follow author wrapper
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/follow-author/follow-author.php
 *
 * @package Follow My Blog Post
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

	<div class="wpw-fp-follow-author-wrapper wpw-fp-follow-btn-wrapper">
		<?php
			do_action( 'wpw_fp_follow_author_content', $args );
		?>
	</div><!-- wpw-fp-follow-author-wrapper -->