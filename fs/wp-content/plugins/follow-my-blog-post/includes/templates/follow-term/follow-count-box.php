<?php 

/**
 * Template For Follow Term Count Box
 * 
 * Handles to return design follow term count box
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/follow-term/follow-count-box.php
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
	<div class="wpw_fp_followers_message wpw_fp_left">
		<div class="wpw-fp-tooltip-inner"><?php echo $follow_message; ?></div>
	</div><!--wpw_fp_followers_message-->