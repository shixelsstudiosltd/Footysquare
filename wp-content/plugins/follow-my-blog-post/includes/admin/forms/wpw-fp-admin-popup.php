<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortocde UI
 * 
 * This is the code for the pop up editor, which shows up when an user clicks
 * on the follow my blog post icon within the WordPress editor.
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 * 
 **/

// Get all post types
$post_types = get_post_types( array( 'public' => true ), 'objects' );

?>

<div class="wpw-fp-popup-content">
	
	<div class="wpw-fp-header">
		<div class="wpw-fp-header-title"><?php _e( 'Add A Follow My Blog Post Shortcode', 'wpwfp' );?></div>
		<div class="wpw-fp-popup-close"><a href="javascript:void(0);" class="wpw-fp-close-button"><img src="<?php echo WPW_FP_IMG_URL;?>/tb-close.png" alt="<?php _e( 'Close', 'wpwfp' );?>" /></a></div>
	</div>
	
	<div class="wpw-fp-popup">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label><?php _e( 'Select A Shortcode', 'wpwfp' );?></label>
					</th>
					<td>
					    <select id="wpw_fp_shortcodes" data-placeholder="<?php _e( '-- Select --', 'wpwfp' );?>" class="chosen-select" tabindex="2">
							<option value=""><?php _e( '-- Select --', 'wpwfp' );?></option>
							<option value="wpw_follow_me"><?php _e( 'Post Follow Button', 'wpwfp' );?></option>
							<option value="wpw_follow_term_me"><?php _e( 'Term Follow button', 'wpwfp' );?></option>
							<option value="wpw_follow_author_me"><?php _e( 'Author Follow button', 'wpwfp' );?></option>
							<option value="wpw_follow_post_list"><?php _e( 'Posts I Follow', 'wpwfp' );?></option>
							<option value="wpw_follow_term_list"><?php _e( 'Terms I Follow', 'wpwfp' );?></option>
							<option value="wpw_follow_author_list"><?php _e( 'Authors I Follow', 'wpwfp' );?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div id="wpw_fp_post_options" class="wpw-fp-shortcodes-options">
			
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="wpw_fp_post_id"><?php _e( 'Post ID:', 'wpwfp' );?></label>
						</th>
						<td>
							<input type="text" id="wpw_fp_post_id" class="small-text" value="" /><br/>
							<span class="description"><?php _e( 'Enter a post / page id. Leave it empty to use the current post id.', 'wpwfp' );?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="wpw_fp_disable_followers_count"><?php _e( 'Disable Followers Count:', 'wpwfp' );?></label>
						</th>
						<td>
							<input type="checkbox" id="wpw_fp_disable_followers_count" value="1" /><br />
							<span class="description"><?php _e( 'Check this box if you want to hide followers count.', 'wpwfp' );?></span>
						</td>
					</tr>
					<tr class="wpw-fp-disable-count-msg-wrap">
						<th scope="row">
							<label for="wpw_fp_followers_count_msg"><?php _e( 'Followers Counter Message:', 'wpwfp' );?></label>
						</th>
						<td>
							<input type="text" id="wpw_fp_followers_count_msg" class="regular-text" value="" /><br />
							<span class="description"><?php printf( __( 'Here you can give the custom message to show followers count. Leave it empty to use the default one from the settings page.%s - displays the followers count.', 'wpwfp' ), '<br><code>{followers_count}</code>');?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="wpw_fp_follow_button_text"><?php _e( 'Follow Button Text:', 'wpwfp' );?></label>
						</th>
						<td>
							<input type="text" id="wpw_fp_follow_text" class="medium-text" value="" />
							<input type="text" id="wpw_fp_following_text" class="medium-text" value="" />
							<input type="text" id="wpw_fp_unfollow_text" class="medium-text" value="" /><br />
							<span class="description"><?php _e( 'Enter Follow, Following and Unfollow button text respectively. Leave it empty to use the default one from the settings page.', 'wpwfp' );?></span>
						</td>
					</tr>
				</tbody>
			</table>
			
		</div><!--wpw_fp_post_options-->
		
		<div id="wpw_fp_term_options" class="wpw-fp-shortcodes-options">
			
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="wpw_fp_term_taxonomy"><?php _e( 'Select Taxonomy:', 'wpwfp' );?></label>
						</th>
						<td>
							<div class="wpw-fp-taxonomy-select-wrap">
								<select id="wpw_fp_term_taxonomy" data-placeholder="<?php _e( '-- Select --', 'wpwfp' );?>" class="chosen-select" tabindex="2">
									<option value=""><?php _e( '-- Select --', 'wpwfp' );?></option>
									<?php 
										foreach ( $post_types as $post_key => $post_type ) { 
											
											if( $post_key != 'attachment' ) { // Check not media type
												
												$all_taxonomy = get_object_taxonomies( $post_key );
												if( !empty( $all_taxonomy ) ) { // Check taxonomy is not empty
													
													echo '<optgroup label="' . $post_type->labels->name . '">';
													foreach ( $all_taxonomy as $taxonomy_slug ) {
															
														if( $taxonomy_slug != 'post_format' ) {
															
															$tax = get_taxonomy( $taxonomy_slug );
															
															echo '<option value="' . $taxonomy_slug . '" data-posttype="' . $post_key . '">' . $tax->label . '</option>';
														}
													}
													echo '</optgroup>';
												}
											}
										}
									?>
								</select>
							</div>
							<span class="wpw-fp-follow-loader"><img src="<?php echo WPW_FP_IMG_URL . '/loader.gif'; ?>" alt="..." /></span>
							<div class="clear"></div>
							<span class="description"><?php _e( 'Select a taxonomy like category, tags.', 'wpwfp' );?></span>
						</td>
					</tr>
					<tr class="wpw-fp-display-none wpw-fp-term-slug-tr">
						<th scope="row">
							<label for="wpw_fp_term_term_id"><?php _e( 'Select Term:', 'wpwfp' );?></label>
						</th>
						<td>
							<select id="wpw_fp_term_term_id" data-placeholder="<?php _e( '-- Select --', 'wpwfp' );?>" class="chosen-select" tabindex="2">
							</select><br/>
							<span class="description"><?php _e( 'Displays the terms like category / tag based on selected taxonomy.', 'wpwfp' );?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="wpw_fp_term_disable_followers_count"><?php _e( 'Disable Followers Count:', 'wpwfp' );?></label>
						</th>
						<td>
							<input type="checkbox" id="wpw_fp_term_disable_followers_count" value="1" /><br />
							<span class="description"><?php _e( 'Check this box if you want to hide followers count.', 'wpwfp' );?></span>
						</td>
					</tr>
					<tr class="wpw-fp-term-disable-count-msg-wrap">
						<th scope="row">
							<label for="wpw_fp_term_followers_count_msg"><?php _e( 'Followers Counter Message:', 'wpwfp' );?></label>
						</th>
						<td>
							<input type="text" id="wpw_fp_term_followers_count_msg" class="regular-text" value="" /><br />
							<span class="description"><?php printf( __( 'Here you can give the custom message to show followers count. Leave it empty to use the default one from the settings page.%s - displays the followers count.', 'wpwfp' ), '<br><code>{followers_count}</code>');?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="wpw_fp_term_follow_button_text"><?php _e( 'Follow Button Text:', 'wpwfp' );?></label>
						</th>
						<td>
							<input type="text" id="wpw_fp_term_follow_text" class="medium-text" value="" />
							<input type="text" id="wpw_fp_term_following_text" class="medium-text" value="" />
							<input type="text" id="wpw_fp_term_unfollow_text" class="medium-text" value="" /><br />
							<span class="description"><?php printf( __( 'Enter Follow, Following and Unfollow button text respectively. Leave it empty to use the default one from the settings page.%s - displays the title of the term.', 'wpwfp' ), '<br><code>{term_name}</code>');?></span>
						</td>
					</tr>
				</tbody>
			</table>
			
		</div><!--wpw_fp_term_options-->
		
		<div id="wpw_fp_author_options" class="wpw-fp-shortcodes-options">
			
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="wpw_fp_author_taxonomy"><?php _e( 'Select Author:', 'wpwfp' );?></label>
						</th>
						<td>
							<select id="wpw_fp_author_nm" data-placeholder="<?php _e( 'Choose an Author...','wpwfp');?>" class="chosen-select wpw_fp_author_name">
						    <option value=""><?php _e( 'Choose an Author...','wpwfp');?></option>
						    </select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="wpw_fp_author_disable_followers_count"><?php _e( 'Disable Followers Count:', 'wpwfp' );?></label>
						</th>
						<td>
							<input type="checkbox" id="wpw_fp_author_disable_followers_count" value="1" /><br />
							<span class="description"><?php _e( 'Check this box if you want to hide followers count.', 'wpwfp' );?></span>
						</td>
					</tr>
					<tr class="wpw-fp-author-disable-count-msg-wrap">
						<th scope="row">
							<label for="wpw_fp_author_followers_count_msg"><?php _e( 'Followers Counter Message:', 'wpwfp' );?></label>
						</th>
						<td>
							<input type="text" id="wpw_fp_author_followers_count_msg" class="regular-text" value="" /><br />
							<span class="description"><?php printf( __( 'Here you can give the custom message to show followers count. Leave it empty to use the default one from the settings page.%s - displays the followers count.', 'wpwfp' ), '<br><code>{followers_count}</code>');?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="wpw_fp_author_follow_button_text"><?php _e( 'Follow Button Text:', 'wpwfp' );?></label>
						</th>
						<td>
							<input type="text" id="wpw_fp_author_follow_text" class="medium-text" value="" />
							<input type="text" id="wpw_fp_author_following_text" class="medium-text" value="" />
							<input type="text" id="wpw_fp_author_unfollow_text" class="medium-text" value="" /><br />
							<span class="description"><?php printf( __( 'Enter Follow, Following and Unfollow button text respectively. Leave it empty to use the default one from the settings page.%s - displays the name of the author.', 'wpwfp' ), '<br><code>{author_name}</code>');?></span>
						</td>
					</tr>
				</tbody>
			</table>
			
		</div><!--wpw_fp_author_options-->
		
		<div id="wpw_fp_insert_container" >
			<input type="button" class="button-secondary" id="wpw_fp_insert_shortcode" value="<?php _e( 'Insert Shortcode', 'wpwfp' ); ?>">
		</div>
		
	</div><!--.wpw-fp-popup-->
	
</div><!--.wpw-fp-popup-content-->
<div class="wpw-fp-popup-overlay"></div>