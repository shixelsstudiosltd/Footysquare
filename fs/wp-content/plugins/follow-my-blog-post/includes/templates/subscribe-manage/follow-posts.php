<?php 

/**
 * Template For Manage Follow Posts Page
 * 
 * Handles to return design of manage follow posts
 * page
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/subscribe-manage/follow-posts.php
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="wpw-fp-follows wpw-fp-manage-follow-posts">
	<?php 
		global $current_user,$wpw_fp_model,$wpw_fp_options;
		
				
		if ( is_user_logged_in() ) { //check user is logged in or not
		
			$perpage = '10';
			
			//model class
			$model = $wpw_fp_model;
			
			// creating new array for all follow posts count
			$argscount = array(
									'author' 	=>	$current_user->ID,
									'count'		=>	'1'
								);
			
			//getting all sold follow posts count
			$datacount = $model->wpw_fp_get_follow_post_users_data( $argscount );
			
			// start paging
			$paging = new Wpw_Fp_Pagination_Public( 'wpw_fp_follow_post_ajax_pagination' );
				
			$paging->items( $datacount ); 
			$paging->limit( $perpage ); // limit entries per page
			
			if( isset( $_POST['paging'] ) ) {
				$paging->currentPage( $_POST['paging'] ); // gets and validates the current page
			}
			
			$paging->calculate(); // calculates what to show
			$paging->parameterName( 'paging' );
			
			// setting the limit to start
			$limit_start = ( $paging->page - 1 ) * $paging->limit;
			
			if(isset($_POST['paging'])) { 
				
				//ajax call pagination
				$argsdata = array(
									'author' 			=>	$current_user->ID,
									'posts_per_page' 	=>	$perpage,
									'paged'				=>	$_POST['paging']
								);
				
			} else {
				//on page load 
				$argsdata = array(
									'author' 			=>	$current_user->ID,
									'posts_per_page' 	=>	$perpage,
									'paged'				=>	'1'
								);
			}
			$followposts = $model->wpw_fp_get_follow_post_users_data( $argsdata );
			
			if( !empty( $followposts ) ) { //check follow posts are not empty
				
				//do action add something before follow posts table
				do_action( 'wpw_fp_follow_posts_table_before', $followposts );
						
				// start displaying the paging if needed
				//do action add follow posts listing table
				do_action( 'wpw_fp_follow_posts_table', $followposts, $paging );
		
				//do action add something after follow posts table after	
				do_action( 'wpw_fp_follow_posts_table_after', $followposts );
			
			} else { //if user is not follow any posts
			?>
				
				<div class="wpw-fp-no-record-message"><?php _e( 'You have not follow any posts yet.','wpwfp' );?></div>
				
			<?php
			
			} //end else
			
		} else { //if user is not logged in
			
		?>
			<p><?php _e( 'You need to be logged in to your account to see your followed posts.', 'wpwfp' );?></p>
			
		<?php
		
		} //end else user is not logged in
	?>
</div><!--.wpw-fp-follows-->