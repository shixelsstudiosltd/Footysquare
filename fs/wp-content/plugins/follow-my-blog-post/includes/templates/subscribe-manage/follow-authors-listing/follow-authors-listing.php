<?php

/**
 * Follow Author Listing
 * 
 * Template for follow authors Listing
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/subscribe-manage/follow-authors-listing/follow-authors-listing.php
 * 
 * @package Follow My Blog Post
 * @since 1.4
 **/

	global $wpw_fp_model, $wpw_fp_options;

	//model class
	$model = $wpw_fp_model;

	$prefix = WPW_FP_META_PREFIX;
	
	$page = isset( $_POST['paging'] ) ? $_POST['paging'] : '1';
	
	// get all custom post types
	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	
	$followtext 	= $wpw_fp_options['authors_follow_buttons']['follow'];
	$followingtext 	= $wpw_fp_options['authors_follow_buttons']['following'];
	$unfollowtext 	= $wpw_fp_options['authors_follow_buttons']['unfollow'];
	
	$followtext 	= str_replace( '{author_name}', '', $followtext );
	$followingtext 	= str_replace( '{author_name}', '', $followingtext );
	$unfollowtext 	= str_replace( '{author_name}', '', $unfollowtext );
?>
<h3><?php _e( 'Authors I Follow', 'wpwfp' ); ?></h3>
<div class="wpw-fp-bulk-action-wrapper">
	<select class="wpw-fp-bulk-action">
		<option value=""><?php _e( 'Bulk Actions', 'wpwfp' ); ?></option>
		<option value="delete"><?php _e( 'Delete', 'wpwfp' ); ?></option>
	</select>
	<input type="button" class="wpw-fp-bulk-action-author-btn wpw-fp-btn" value="<?php _e( 'Apply', 'wpwfp' ); ?>" />
	<span class="wpw-fp-follow-loader wpw-fp-bulk-action-loader">
		<img src="<?php echo WPW_FP_IMG_URL;?>/loader.gif"/>
	</span><!--.wpw-fp-follow-loader-->
</div>
<input type="hidden" class="wpw-fp-bulk-paging" value="<?php echo $page; ?>" />
<div class="wpw-fp-clear" ></div>
<table class="wpw-fp-follow-author-table">
	<thead>
		<tr class="wpw-fp-follow-author-row-head">
				<?php 
						//do action to add header title of orders list before
						do_action('wpw_fp_follow_author_header_before');
				?>
				<th width="5%" class="wpw-fp-cb-authors">
					<input type="checkbox" class="wpw-fb-cb-authors wpw-fp-cb-authors-1" id="wpw_fp_cb_authors_1">
				</th>
				<th width="35%"><?php _e( 'Author Name','wpwfp' );?></th>
				<th width="35%"><?php _e( 'Followed Date','wpwfp' );?></th>
				<th width="25%"><?php _e( 'Actions','wpwfp' );?></th>
				<?php 
						//do action to add header title of orders list after
						do_action('wpw_fp_follow_author_header_after');
				?>
		</tr>
	</thead>
	
	<tbody>
	<?php
		foreach ( $followauthors as $followauthor ) {
			
			$authorid = isset( $followauthor['post_parent'] ) && !empty( $followauthor['post_parent'] ) ? $followauthor['post_parent'] : '';
			
			if( !empty( $authorid ) ) { // Check post parent is not empty
				
				// Get Follow author Name
				$author_data 	= get_user_by( 'id', $authorid);
    			$author_name 	= isset( $author_data->display_name ) ? $author_data->display_name : '';
				
				// Get Follow Date
				$followdate = $model->wpw_fp_get_date_format( $followauthor['post_date'] );
				
	?>			
		<tr class="wpw-fp-follow-author-row-body">
			<?php 
					//do action to add row for orders list before
					do_action( 'wpw_fp_follow_author_row_before', $followauthor['ID'] ); 
			?>
			<td><input type="checkbox" class="wpw-fp-cb-author" value="<?php echo $followauthor['ID']; ?>" name="author[]"></td>
			<td><?php echo $model->wpw_fp_short_content( $author_name );?></td>
			<td><?php echo $followdate;?></td>
			<td>
				<?php
					$args = array(
										'author_id'			=> $authorid,
										'current_post_id'	=> $followauthor['ID'],
										'follow_message'	=> '',
										'follow_buttons'	=> array(
																			'follow' 	=> trim( $followtext ),
																			'following' => trim( $followingtext ),
																			'unfollow' 	=> trim( $unfollowtext ),
																		),
									);
					do_action( 'wpw_fp_follow_author', $args );
				?>
			</td>
			<?php 
					//do action to add row for orders list after
					do_action( 'wpw_fp_follow_author_row_after', $followauthor['ID'] ); 
			?>
		</tr>
<?php	} } ?>
	</tbody>
	<tfoot>
		<tr class="wpw-fp-follow-author-row-foot">
			<?php 
					//do action to add row in footer before
					do_action('wpw_fp_follow_author_footer_before');
			?>
			<th class="wpw-fp-cb-authors">
				<input type="checkbox" class="wpw-fb-cb-authors wpw-fp-cb-authors-2" id="wpw_fp_cb_authors_2">
			</th>
			<th><?php _e( 'Author Name','wpwfp' );?></th>
			<th><?php _e( 'Followed Date','wpwfp' );?></th>
			<th><?php _e( 'Actions','wpwfp' );?></th>
			<?php 
					//do action to add row in footer after
					do_action('wpw_fp_follow_author_footer_after');
			?>
		</tr>
	</tfoot>
</table>

<div class="wpw-fp-paging wpw-fp-follow-authors-paging">
	<div id="wpw-fp-tablenav-pages" class="wpw-fp-tablenav-pages">
		<?php echo $paging->getOutput(); ?>
	</div><!--.wpw-fp-tablenav-pages-->
</div><!--.wpw-fp-paging-->
<div class="wpw-fp-follow-loader wpw-fp-follow-authors-loader">
	<img src="<?php echo WPW_FP_IMG_URL;?>/loader.gif"/>
</div><!--.wpw-fp-sales-loader-->