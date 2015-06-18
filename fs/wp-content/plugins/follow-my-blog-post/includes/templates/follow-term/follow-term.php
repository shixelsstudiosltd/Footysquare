<?php 

/**
 * Template For Follow Term Wrapper
 * 
 * Handles to return design follow term wrapper
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/follow-term/follow-term.php
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

	<div class="wpw-fp-follow-term-wrapper wpw-fp-follow-btn-wrapper">
		<?php
			do_action( 'wpw_fp_follow_term_content', $args );
		?>
	</div><!-- wpw-fp-follow-term-wrapper -->