<?php

/**
 * Follow Terms Listing
 * 
 * Template for follow terms Listing
 * 
 * Override this template by copying it to yourtheme/follow-my-blog-post/subscribe-manage/follow-terms-listing/follow-terms-listing.php
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
	
	$followtext 	= $wpw_fp_options['term_follow_buttons']['follow'];
	$followingtext 	= $wpw_fp_options['term_follow_buttons']['following'];
	$unfollowtext 	= $wpw_fp_options['term_follow_buttons']['unfollow'];
	
	$followtext 	= str_replace( '{term_name}', '', $followtext );
	$followingtext 	= str_replace( '{term_name}', '', $followingtext );
	$unfollowtext 	= str_replace( '{term_name}', '', $unfollowtext );
?>
<h3><?php _e( 'Terms I Follow', 'wpwfp' ); ?></h3>
<div class="wpw-fp-bulk-action-wrapper">
	<select class="wpw-fp-bulk-action">
		<option value=""><?php _e( 'Bulk Actions', 'wpwfp' ); ?></option>
		<option value="delete"><?php _e( 'Delete', 'wpwfp' ); ?></option>
	</select>
	<input type="button" class="wpw-fp-bulk-action-term-btn wpw-fp-btn" value="<?php _e( 'Apply', 'wpwfp' ); ?>" />
	<span class="wpw-fp-follow-loader wpw-fp-bulk-action-loader">
		<img src="<?php echo WPW_FP_IMG_URL;?>/loader.gif"/>
	</span><!--.wpw-fp-follow-loader-->
</div>
<input type="hidden" class="wpw-fp-bulk-paging" value="<?php echo $page; ?>" />
<div class="wpw-fp-clear" ></div>
<table class="wpw-fp-follow-term-table">
	<thead>
		<tr class="wpw-fp-follow-term-row-head">
				<?php 
						//do action to add header title of orders list before
						do_action('wpw_fp_follow_term_header_before');
				?>
				<th width="5%" class="wpw-fp-cb-terms">
					<input type="checkbox" class="wpw-fb-cb-terms wpw-fp-cb-terms-1" id="wpw_fp_cb_terms_1">
				</th>
				<th width="20%"><?php _e( 'Term Title','wpwfp' );?></th>
				<th width="15%"><?php _e( 'Taxonomy','wpwfp' );?></th>
				<th width="15%"><?php _e( 'Post Type','wpwfp' );?></th>
				<th width="20%"><?php _e( 'Followed Date','wpwfp' );?></th>
				<th width="25%"><?php _e( 'Actions','wpwfp' );?></th>
				<?php 
						//do action to add header title of orders list after
						do_action('wpw_fp_follow_term_header_after');
				?>
		</tr>
	</thead>
	
	<tbody>
	<?php
		foreach ( $followterms as $followterm ) {
			
			$termid = isset( $followterm['post_parent'] ) && !empty( $followterm['post_parent'] ) ? $followterm['post_parent'] : '';
			
			if( !empty( $termid ) ) { // Check post parent is not empty
				
				// Get Follow Post Type
				$posttype = get_post_meta( $followterm['ID'], $prefix.'post_type', true );
				
				// Get Follow Post Type Name
				$post_type_name = !empty( $posttype ) && isset( $post_types[$posttype]->labels->singular_name ) ? $post_types[$posttype]->labels->singular_name : '';
				
				// Get Follow Taxonomy
				$taxonomy = get_post_meta( $followterm['ID'], $prefix.'taxonomy_slug', true );
				
				// Get Follow Taxonomy Name
    			$taxonomy_data = get_taxonomy( $taxonomy );
				$taxonomy_name = !empty( $taxonomy_data ) && isset( $taxonomy_data->labels->singular_name ) ? $taxonomy_data->labels->singular_name : '';
			
				// Get Follow Term Name
				$term_data 	= get_term_by( 'id', $termid, $taxonomy );
    			$term_name 	= isset( $term_data->name ) ? $term_data->name : '';
				
				// Get Follow Date
				$followdate = $model->wpw_fp_get_date_format( $followterm['post_date'] );
				
	?>			
		<tr class="wpw-fp-follow-term-row-body">
			<?php 
					//do action to add row for orders list before
					do_action( 'wpw_fp_follow_term_row_before', $followterm['ID'] ); 
			?>
			<td><input type="checkbox" class="wpw-fp-cb-term" value="<?php echo $followterm['ID']; ?>" name="term[]"></td>
			<td><?php echo $model->wpw_fp_short_content( $term_name );?></td>
			<td><?php echo $taxonomy_name;?></td>
			<td><?php echo $post_type_name;?></td>
			<td><?php echo $followdate;?></td>
			<td>
				<?php
					$args = array(
										'follow_posttype'	=> $posttype,
										'follow_taxonomy'	=> $taxonomy,
										'follow_term_id'	=> $termid,
										'current_post_id'	=> $followterm['ID'],
										'follow_message'	=> '',
										'follow_buttons'	=> array(
																			'follow' 	=> trim( $followtext ),
																			'following' => trim( $followingtext ),
																			'unfollow' 	=> trim( $unfollowtext ),
																		),
									);
					do_action( 'wpw_fp_follow_term', $args );
				?>
			</td>
			<?php 
					//do action to add row for orders list after
					do_action( 'wpw_fp_follow_term_row_after', $followterm['ID'] ); 
			?>
		</tr>
<?php	} } ?>
	</tbody>
	<tfoot>
		<tr class="wpw-fp-follow-term-row-foot">
			<?php 
					//do action to add row in footer before
					do_action('wpw_fp_follow_term_footer_before');
			?>
			<th class="wpw-fp-cb-terms">
				<input type="checkbox" class="wpw-fb-cb-terms wpw-fp-cb-terms-2" id="wpw_fp_cb_terms_2">
			</th>
			<th><?php _e( 'Term Title','wpwfp' );?></th>
			<th><?php _e( 'Taxonomy','wpwfp' );?></th>
			<th><?php _e( 'Post Type','wpwfp' );?></th>
			<th><?php _e( 'Followed Date','wpwfp' );?></th>
			<th><?php _e( 'Actions','wpwfp' );?></th>
			<?php 
					//do action to add row in footer after
					do_action('wpw_fp_follow_term_footer_after');
			?>
		</tr>
	</tfoot>
</table>

<div class="wpw-fp-paging wpw-fp-follow-terms-paging">
	<div id="wpw-fp-tablenav-pages" class="wpw-fp-tablenav-pages">
		<?php echo $paging->getOutput(); ?>
	</div><!--.wpw-fp-tablenav-pages-->
</div><!--.wpw-fp-paging-->
<div class="wpw-fp-follow-loader wpw-fp-follow-terms-loader">
	<img src="<?php echo WPW_FP_IMG_URL;?>/loader.gif"/>
</div><!--.wpw-fp-sales-loader-->