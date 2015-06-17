<?php

/**
 * Follow Posts Listing
 * 
 * Template for follow posts Listing
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/subscribe-manage/follow-posts-listing/follow-posts-listing.php
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 **/

	global $wpw_fp_model, $wpw_fp_options;

	//model class
	$model = $wpw_fp_model;

	$prefix = WPW_FP_META_PREFIX;
	
	$page = isset( $_POST['paging'] ) ? $_POST['paging'] : '1';
	
	// get all custom post types
	$post_types = get_post_types( array( 'public' => true ), 'objects' );
?>
<h3><?php _e( 'Posts I Follow', 'wpwfp' ); ?></h3>
<div class="wpw-fp-bulk-action-wrapper">
	<select class="wpw-fp-bulk-action">
		<option value=""><?php _e( 'Bulk Actions', 'wpwfp' ); ?></option>
		<option value="delete"><?php _e( 'Delete', 'wpwfp' ); ?></option>
	</select>
	<input type="button" class="wpw-fp-bulk-action-post-btn wpw-fp-btn" value="<?php _e( 'Apply', 'wpwfp' ); ?>" />
	<span class="wpw-fp-follow-loader wpw-fp-bulk-action-loader">
		<img src="<?php echo WPW_FP_IMG_URL;?>/loader.gif"/>
	</span><!--.wpw-fp-follow-loader-->
</div>
<input type="hidden" class="wpw-fp-bulk-paging" value="<?php echo $page; ?>" />
<div class="wpw-fp-clear" ></div>
<table class="wpw-fp-follow-post-table">
	<thead>
		<tr class="wpw-fp-follow-post-row-head">
				<?php 
						//do action to add header title of orders list before
						do_action('wpw_fp_follow_post_header_before');
				?>
				<th width="5%" class="wpw-fp-cb-posts">
					<input type="checkbox" class="wpw-fb-cb-posts wpw-fp-cb-posts-1" id="wpw_fp_cb_posts_1">
				</th>
				<th width="30%"><?php _e( 'Post Title','wpwfp' );?></th>
				<th width="20%"><?php _e( 'Post Type','wpwfp' );?></th>
				<th width="20%"><?php _e( 'Followed Date','wpwfp' );?></th>
				<th width="25%"><?php _e( 'Actions','wpwfp' );?></th>
				<?php 
						//do action to add header title of orders list after
						do_action('wpw_fp_follow_post_header_after');
				?>
		</tr>
	</thead>
	
	<tbody>
	<?php
		foreach ( $followposts as $followpost ) {
			
			$post_parent = isset( $followpost['post_parent'] ) && !empty( $followpost['post_parent'] ) ? $followpost['post_parent'] : '';
			
			if( !empty( $post_parent ) ) { // Check post parent is not empty
				
				$posts = get_post( $post_parent );
				
				// Get Follow Post Name
				$post_name = isset( $posts->post_title ) ? $posts->post_title : '';
				
				// Get Follow Post Type
				$posttype = $posts->post_type;
				
				// Get Follow Post Type Name
				$post_type_name = !empty( $posttype ) && isset( $post_types[$posttype]->labels->singular_name ) ? $post_types[$posttype]->labels->singular_name : '';
				
				// Get Follow Date
				$followdate = $model->wpw_fp_get_date_format( $followpost['post_date'] );
	?>			
		<tr class="wpw-fp-follow-post-row-body">
			<?php 
					//do action to add row for orders list before
					do_action( 'wpw_fp_follow_post_row_before', $followpost['ID'] ); 
			?>
			<td><input type="checkbox" class="wpw-fp-cb-post" value="<?php echo $followpost['ID']; ?>" name="post[]"></td>
			<td><?php echo $model->wpw_fp_short_content( $post_name );?></td>
			<td><?php echo $post_type_name;?></td>
			<td><?php echo $followdate;?></td>
			<td>
				<?php
					$args = array(
										'post_id'			=> $post_parent,
										'current_post_id'	=> $post_parent,
										'follow_message'	=> ''
									);
					do_action( 'wpw_fp_follow_post', $args );
				?>
			</td>
			<?php 
					//do action to add row for orders list after
					do_action( 'wpw_fp_follow_post_row_after', $followpost['ID'] ); 
			?>
		</tr>
<?php	} } ?>
	</tbody>
	<tfoot>
		<tr class="wpw-fp-follow-post-row-foot">
			<?php 
					//do action to add row in footer before
					do_action('wpw_fp_follow_post_footer_before');
			?>
			<th class="wpw-fp-cb-posts">
				<input type="checkbox" class="wpw-fb-cb-posts wpw-fp-cb-posts-2" id="wpw_fp_cb_posts_2">
			</th>
			<th><?php _e( 'Post Title','wpwfp' );?></th>
			<th><?php _e( 'Post Type','wpwfp' );?></th>
			<th><?php _e( 'Followed Date','wpwfp' );?></th>
			<th><?php _e( 'Actions','wpwfp' );?></th>
			<?php 
					//do action to add row in footer after
					do_action('wpw_fp_follow_post_footer_after');
			?>
		</tr>
	</tfoot>
</table>

<div class="wpw-fp-paging wpw-fp-follow-posts-paging">
	<div id="wpw-fp-tablenav-pages" class="wpw-fp-tablenav-pages">
		<?php echo $paging->getOutput(); ?>
	</div><!--.wpw-fp-tablenav-pages-->
</div><!--.wpw-fp-paging-->
<div class="wpw-fp-follow-loader wpw-fp-follow-posts-loader">
	<img src="<?php echo WPW_FP_IMG_URL;?>/loader.gif"/>
</div><!--.wpw-fp-follow-loader-->