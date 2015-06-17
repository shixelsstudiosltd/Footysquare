<?php 

/**
 * Template For Manage Follow Authors Page
 * 
 * Handles to return design of manage follow authors
 * page
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/subscribe-manage/follow-authors.php
 *
 * @package Follow My Blog Post
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="wpw-fp-follows wpw-fp-manage-follow-authors">
	<?php 
		global $current_user,$wpw_fp_model,$wpw_fp_options;
		
				
		if ( is_user_logged_in() ) { //check user is logged in or not
		
			$perpage = '10';
			
			//model class
			$model = $wpw_fp_model;
			
			// creating new array for all follow authors count
			$argscount = array(
									'author' 	=>	$current_user->ID,
									'count'		=>	'1'
								);
			
			//getting all sold follow authors count
			$datacount = $model->wpw_fp_get_follow_author_users_data( $argscount );
			
			// start paging
			$paging = new Wpw_Fp_Pagination_Public( 'wpw_fp_follow_author_ajax_pagination' );
				
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
			$followauthors = $model->wpw_fp_get_follow_author_users_data( $argsdata );
			
			if( !empty( $followauthors ) ) { //check follow authors are not empty
				
				//do action add something before follow authors table
				do_action( 'wpw_fp_follow_authors_table_before', $followauthors );
						
				// start displaying the paging if needed
				//do action add follow authors listing table
				do_action( 'wpw_fp_follow_authors_table', $followauthors, $paging );
		
				//do action add something after follow authors table after	
				do_action( 'wpw_fp_follow_authors_table_after', $followauthors );
			
			} else { //if user is not follow any authors
			?>
				
				<div class="wpw-fp-no-record-message"><?php _e( 'You have not follow any authors yet.','wpwfp' );?></div>
				
			<?php
			
			} //end else
			
		} else { //if user is not logged in
			
		?>
			<p><?php _e( 'You need to be logged in to your account to see your followed  authors.', 'wpwfp' );?></p>
			
		<?php
		
		} //end else user is not logged in
	?>
</div><!--.wpw-fp-follows-->