<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page
 *
 * The code for the send emails to followers
 *
 * @package Follow My Blog Post
 * @since 1.5.0
 */
 ?>
<div class="wrap">

<?php
	global $wpdb,$wpw_fp_model,$wpw_fp_message; // call globals to use them in this page
	
	// model class
	$model = $wpw_fp_model; 
	
	// message class
	$message = $wpw_fp_message;
	
	$prefix = WPW_FP_META_PREFIX;
	
	// get all custom post types
	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	if( isset( $post_types['attachment'] ) ) { // Check attachment post type exists
		unset( $post_types['attachment'] );
	}
	
	$followers_msg = '';
	//Get message after sent email to followers
	if( $message->size( 'wpw-fp-sent-mail-message' ) > 0 ) {
		$followers_msg = $message->messages[0]['text'];
	}
	
?>
<!-- wpweb logo -->
<img src="<?php echo WPW_FP_IMG_URL . '/wpweb-logo.png'; ?>" class="wpweb-logo" alt="<?php _e( 'WP Web Logo', 'wpwfp' );?>" />
<!-- plugin name -->
<h2><?php _e( 'Send Emails', 'wpwfp' ); ?></h2><br />

<?php
	if( !empty( $followers_msg ) ) {
?>
		<div class="updated fade below-h2" id="message"><p><strong><?php echo $followers_msg; ?></strong></p></div>
<?php
	}
?>
	<!-- beginning of the general settings meta box -->
<div id="wpw-fp-general" class="post-box-container">
	<div class="metabox-holder">	
		<div class="meta-box-sortables ui-sortable">
			<div id="general" class="postbox">	
				<div class="handlediv" title="<?php _e( 'Click to toggle', 'wpwfp' ); ?>"><br /></div>
					<!-- general settings box title -->
					<h3 class="hndle">
						<span style='vertical-align: top;'><?php _e( 'Send Emails', 'wpwfp' ); ?></span>
					</h3>
					<div class="inside">
					<form name="send-email" method="POST">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="followed_type"><?php _e( 'Followed Type:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<input type="radio" name="followed_type" id="followed_post" value="followed_post" class="followed_type" checked /><label for="followed_post"><?php _e( 'Followed Posts', 'wpwfp' ); ?></label>
									<input type="radio" name="followed_type" id="followed_terms" value="followed_terms" class="followed_type" /><label for="followed_terms"><?php _e( 'Followed Terms', 'wpwfp' ); ?></label>
									<input type="radio" name="followed_type" id="followed_authors" value="followed_authors" class="followed_type" /><label for="followed_authors"><?php _e( 'Followed Authors', 'wpwfp' ); ?></label><br />
									<span class="description"><?php _e( 'Select followed type.', 'wpwfp' ); ?></span>
								</td>
							</tr>
							
							<!-- All Post Types -->
							<tr valign="top" class="followed_type_post">
								<th scope="row">
									<label for="followed_type_post"><?php _e( 'Select Post Type:', 'wpwfp' ); ?><span class="wpw_fp_email_error">*</span></label>
								</th>
								<td>
								<div class="wpw-fp-post-select-wrap">
									<select name="followed_type_post" id="followed_type_post" class="chosen-select">
									<option value=""><?php _e( '-- Select --', 'wpwfp' );?></option>
										<?php   												
											
											foreach ( $post_types as $post_key => $post_type ) {
											$args['post_type'] = $post_key;
											$post_name = $this->model->wpw_fp_get_follow_post_data( $args );
											
												if( !empty( $post_name ) ){//check if not empty post name
												?>
												<option value="<?php echo $post_type->labels->name; ?>" data-posttype="<?php echo $post_key; ?>">
													<?php echo $post_type->labels->name; ?>
												</option>
											<?php
												}
											}
										?> 														
									</select>
									</div>
									<span class="wpw-fp-follow-loader wpw-fp-post-follow-loader"><img src="<?php echo WPW_FP_IMG_URL . '/loader.gif'; ?>" alt="..." /></span>
									<div class="clear"></div>
									<span class="description"><?php _e( 'Select post type.', 'wpwfp' );?></span>
									<div class="followed_type_post_error wpw_fp_email_error"></div>
								</td>
							</tr>
								
							<!-- Post Name -->
							<tr class="wpw-fp-display-none wpw-fp-post-tr">
								<th scope="row">
									<label for="followed_type_post_name"><?php _e( 'Select Post Name:', 'wpwfp' );?><span class="wpw_fp_email_error">*</span></label>		
								</th>
								<td>
									<select id="followed_type_post_name" name="followed_type_post_name" data-placeholder="<?php _e( '-- Select --', 'wpwfp' );?>" class="chosen-select" tabindex="2">
									</select><br/>
									<span class="description"><?php _e( 'select post name', 'wpwfp' );?></span>
									<div class="followed_type_post_name_error wpw_fp_email_error"></div>
								</td>
							</tr>
							
							<!-- All taxonomy -->
							<tr valign="top" class="followed_type_terms wpw-fp-display-none">
								<th scope="row">
									<label for="followed_type_terms"><?php _e( 'Select Taxonomy:', 'wpwfp' ); ?><span class="wpw_fp_email_error">*</span></label>
								</th>
								<td>
								<div class="wpw-fp-taxonomy-select-wrap">
									<select name="followed_type_terms" id="followed_type_terms" class="chosen-select">
									<option value=""><?php _e( '-- Select --', 'wpwfp' );?></option>
									<?php
										if ( !empty( $post_types ) ) {
											
											foreach ( $post_types as $key => $post_type ) {
						
												$all_taxonomy = get_object_taxonomies( $key );
												if( !empty( $all_taxonomy ) ) { // Check taxonomy is not empty
													
													echo '<optgroup label="' . $post_type->labels->name . '">';
													foreach ( $all_taxonomy as $taxonomy_slug ) {
												
														if( $taxonomy_slug != 'post_format' ) {
															
															$tax = get_taxonomy( $taxonomy_slug );
															
															$args = array();
											
															if( !empty( $key ) ) {
																$args['post_type']	= $key;
																$args['wpw_fp_taxonomy']= $taxonomy_slug;
																$args['count']	= true;
															}
															
															//get followed post list count data from database
															$taxonomy_count = $this->model->wpw_fp_get_follow_term_data( $args );
															if( !empty( $taxonomy_count ) ) {
																
																echo '<option value="' . $taxonomy_slug . '" data-posttype="' . $key . '" ' . selected( isset( $_GET['wpw_fp_taxonomy'] ) ? $_GET['wpw_fp_taxonomy'] : '', $taxonomy_slug, false ) . '>' . $tax->label . '</option>';
															}
														}
													}
													echo '</optgroup>';
												}
											}
										}
									?>
									</select>
									</div>
									<span class="wpw-fp-follow-loader wpw-fp-term-follow-loader"><img src="<?php echo WPW_FP_IMG_URL . '/loader.gif'; ?>" alt="..." /></span>
									<div class="clear"></div>
									<span class="description"><?php _e( 'Select a taxonomy like category, tags.', 'wpwfp' );?></span>
									<div class="followed_type_terms_error wpw_fp_email_error"></div>
								</td>
							</tr>
							
							<!-- All Terms -->
							<tr class="wpw-fp-display-none wpw-fp-term-slug-tr">
								<th scope="row">
									<label for="wpw_fp_term_id"><?php _e( 'Select Term:', 'wpwfp' );?><span class="wpw_fp_email_error">*</span></label>		
								</th>
								<td>
									<select id="wpw_fp_term_id" name="wpw_fp_term_id" data-placeholder="<?php _e( '-- Select --', 'wpwfp' );?>" class="chosen-select" tabindex="2">
									</select><br/>
									<span class="description"><?php _e( 'Displays the terms like category / tag based on selected taxonomy.', 'wpwfp' );?></span>
									<div class="followed_type_term_id_error wpw_fp_email_error"></div>
								</td>
							</tr>
							
							
							<!-- All Authors -->
							<tr valign="top" class="followed_type_author wpw-fp-display-none">
								<th scope="row">
									<label for="followed_type_author"><?php _e( 'Select Author:', 'wpwfp' ); ?><span class="wpw_fp_email_error">*</span></label>
								</th>
								<td>
									<select name="followed_type_author" id="followed_type_author" class="chosen-select">
									<option value=""><?php _e( '-- Select --', 'wpwfp' );?></option>
										<?php   												
											$all_authors = $this->model->wpw_fp_get_follow_author_data();
											foreach ( $all_authors as $key => $value ) {
											$authordata = get_user_by( 'id', $value['post_parent'] );
										?>
											<option value="<?php echo $value['post_parent']; ?>">
												<?php echo $authordata->display_name; ?>
											</option>
										<?php
											}
										?>
									</select><br />
									<span class="description"><?php _e( 'Select author name', 'wpwfp' ); ?></span>
									<div class="followed_type_author_error wpw_fp_email_error"></div>
								</td>
							</tr>

							<!-- Email Subject -->
							<tr valign="top">
								<th scope="row">
									<label for="followed_email_subject"><?php _e( 'Email Subject:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<input type="text" name="followed_email_subject" class="followed_email_subject" id="followed_email_subject" value="" size="76" /></br>
									<span class="description"><?php _e( 'This is the subject of the email that will be sent to the followers', 'wpwfp' ); ?></span>
									
								</td>
							</tr>

							<!-- Email Body -->
							<tr valign="top" class="followed_email_body">
								<th scope="row">
									<label for="followed_email_body"><?php _e( 'Email Body:', 'wpwfp' ); ?><span class="wpw_fp_email_error">*</span></label>
								</th>
								<td>
									<?php 
										$settings = array( 'teeny' => true );
										wp_editor( '', 'followed_email_body', $settings );
									?></br>
									<span class="description"><?php _e( 'This is the body, main content of the email that will be sent to the followers.', 'wpwfp' ); ?></span>
									<div class="followed_email_body_error wpw_fp_email_error"></div>
								</td>
							</tr>

							<!-- Terms Follow Settings Start -->
							<tr>
								<th></th>
								<td>
									<input type="hidden" name="wpw_fp_send_email_submit" value="1" />
									<?php
										echo apply_filters ( 'wpweb_fb_settings_submit_button', '<input class="button-primary wpw-fp-send-email-submit" type="submit" name="wpw_fp_send_email_button" value="'.__( 'Send Email','wpwfp' ).'" />' );
									?>
								</td>
							</tr>
						</tbody>
					</table>
				  </form>
				</div><!-- .inside -->
			</div><!-- #general -->
		</div><!-- .meta-box-sortables ui-sortable -->
	</div><!-- .metabox-holder -->
</div><!-- #wpw-fp-general -->
</div><!--end .wrap-->