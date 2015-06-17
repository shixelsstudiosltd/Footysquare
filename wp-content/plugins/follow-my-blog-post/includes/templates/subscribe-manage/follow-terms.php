<?php 

/**
 * Template For Manage Follow Terms Page
 * 
 * Handles to return design of manage follow terms
 * page
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/subscribe-manage/follow-terms.php
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="wpw-fp-follows wpw-fp-manage-follow-terms">
	<?php 
		global $current_user,$wpw_fp_model,$wpw_fp_options;
		
				
		if ( is_user_logged_in() ) { //check user is logged in or not
		
			$perpage = '10';
			
			//model class
			$model = $wpw_fp_model;
			
			// creating new array for all follow terms count
			$argscount = array(
									'author' 	=>	$current_user->ID,
									'count'		=>	'1'
								);
			
			//getting all sold follow terms count
			$datacount = $model->wpw_fp_get_follow_term_users_data( $argscount );
			
			// start paging
			$paging = new Wpw_Fp_Pagination_Public( 'wpw_fp_follow_term_ajax_pagination' );
				
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
			$followterms = $model->wpw_fp_get_follow_term_users_data( $argsdata );
			
			if( !empty( $followterms ) ) { //check follow terms are not empty
				
				//do action add something before follow terms table
				do_action( 'wpw_fp_follow_terms_table_before', $followterms );
						
				// start displaying the paging if needed
				//do action add follow terms listing table
				do_action( 'wpw_fp_follow_terms_table', $followterms, $paging );
		
				//do action add something after follow terms table after	
				do_action( 'wpw_fp_follow_terms_table_after', $followterms );
			
			} else { //if user is not follow any terms
			?>
				
				<div class="wpw-fp-no-record-message"><?php _e( 'You have not follow any terms yet.','wpwfp' );?></div>
				
			<?php
			
			} //end else
			
		} else { //if user is not logged in
			
		?>
			<p><?php _e( 'You need to be logged in to your account to see your followed  terms.', 'wpwfp' );?></p>
			
		<?php
		
		} //end else user is not logged in
	?>
</div><!--.wpw-fp-follows-->