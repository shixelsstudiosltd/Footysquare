<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page Notification Tab
 *
 * The code for the plugins settings page notification tab
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */

?>
<!-- beginning of the notification settings meta box -->
<div id="wpw-fp-notification" class="post-box-container">
	<div class="metabox-holder">	
		<div class="meta-box-sortables ui-sortable">
			<div id="notification" class="postbox">	
				<div class="handlediv" title="<?php _e( 'Click to toggle', 'wpwfp' ); ?>"><br /></div>

					<!-- notification settings box title -->
					<h3 class="hndle">
						<span style='vertical-align: top;'><?php _e( 'Notification Settings', 'wpwfp' ); ?></span>
					</h3>

					<div class="inside">
					
						<table class="form-table">
							<tbody>
							
								<tr>
									<td colspan="2">
										<?php
											echo apply_filters ( 'wpweb_fb_settings_submit_button', '<input class="button-primary wpw-fp-save-btn" type="submit" name="wpw-fp-set-submit" value="'.__( 'Save Changes','wpwfp' ).'" />' );
										?>
									</td>
								</tr>
								
								<?php
								
									// do action for add setting before notification settings
									do_action( 'wpw_fp_before_notification_setting', $wpw_fp_options );
									
								?>
								
			<?php /*
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[post_revision]"><?php _e( 'Enable Analyzed Content:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="checkbox" id="wpw_fp_options[post_revision][title]" name="wpw_fp_options[post_revision][title]" value="1" <?php checked( '1', isset( $wpw_fp_options['post_revision']['title'] ) ? $wpw_fp_options['post_revision']['title'] : '' ) ?> />
										<label for="wpw_fp_options[post_revision][title]"><?php _e( 'Title', 'wpwfp' ); ?></label><br />
										<input type="checkbox" id="wpw_fp_options[post_revision][content]" name="wpw_fp_options[post_revision][content]" value="1"  <?php checked( '1', isset( $wpw_fp_options['post_revision']['content'] ) ? $wpw_fp_options['post_revision']['content'] : '' ) ?> />
										<label for="wpw_fp_options[post_revision][content]"><?php _e( 'Content', 'wpwfp' ); ?></label><br />
										<input type="checkbox" id="wpw_fp_options[post_revision][excerpt]" name="wpw_fp_options[post_revision][excerpt]" value="1"  <?php checked( '1', isset( $wpw_fp_options['post_revision']['excerpt'] ) ? $wpw_fp_options['post_revision']['excerpt'] : '' ) ?> />
										<label for="wpw_fp_options[post_revision][excerpt]"><?php _e( 'Excerpt', 'wpwfp' ); ?></label><br />
										<span class="description"><?php _e('Check if you want to send mail to followers after updated posts for specific option.','wpwfp');?></span>
									</td>
								</tr>
			*/ ?>
			
								<tr valign="top">
									<th scope="row">
										<label for="wpw_fp_options[notification_type]"><?php _e( 'Send Emails for:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<ul class="wpw_fp_indvisual" id="wpw_fp_indvisual">
										<?php 
											$all_types = get_post_types( array( 'public' => true ), 'objects');
											$all_types = is_array( $all_types ) ? $all_types : array();
											unset( $all_types['attachment'] );
											
											if( !empty( $wpw_fp_options['notification_type'] ) ) {
												$notification_types = $wpw_fp_options['notification_type'];
											} else {
												$notification_types = '';
											}
															
											$notification_types = is_array( $notification_types ) ? $notification_types : array();
														
											foreach ( $all_types as $type ) {
												if( !empty( $wpw_fp_options['notification_item_'.$type->name] ) ) {
													$items_count = count( $wpw_fp_options['notification_item_'.$type->name] );
												} else {
													$wpw_fp_options['notification_item_'.$type->name] = '';
													$items_count = 0;
												}
															
												if ( !is_object( $type ) ) continue;															
													$label = @$type->labels->name ? $type->labels->name : $type->name;
													$selected = ( in_array( $type->name, $notification_types ) ) ? 'checked="checked"' : '';
																	
													$individual = '<a href="javascript:void(0);" class="wpw_fp_indivisual_entries" onclick="wpw_fp_popup_indivisual( \'' . $type->name . '\', \'notification\' )">' . __( 'Choose individual entries', 'wpwfp' ) . '</a>';
										?>
											
											<li>
												<input type="checkbox" id="wpw_fp_notification_<?php echo $type->name; ?>" name="wpw_fp_options[notification_type][]" value="<?php echo $type->name; ?>" <?php echo $selected; ?>/>
																						
												<label for="wpw_fp_notification_<?php echo $type->name; ?>"><?php echo $label; ?></label>
													<?php echo $individual; ?>&nbsp;&nbsp;&nbsp;<label id="wpw_fp_selected_indivisual_notification_<?php echo $type->name; ?>"><?php echo '( ' . $items_count . ' ) ';?><?php _e( 'Selected', 'wpwfp' ); ?></label>
											</li>
											
										<?php 	$args = array( 'post_type' => $type->name, 'posts_per_page' => '-1' );
												$wp_query = null;
												$wp_query = new WP_Query;
												$wp_query->query( $args );
												
												if( !empty( $wpw_fp_options['notification_item_'.$type->name] ) ) {
													$notification_items = $wpw_fp_options['notification_item_'.$type->name];
												} else {
													$notification_items = '';
												}
															
												$notification_items = is_array( $notification_items ) ? $notification_items : array();
										?>
																																			
												<!-- listing all post related post -->
												<div id="fp_indivisual_data_notification_<?php echo $type->name; ?>" class="wpw_fp_indivisual_entries_data" style="display:none;">
													<div class="wpw_fp_indivisual_header">
														<div class="wpw_fp_indivisual_title">
															<h2><?php echo $label; ?></h2>
														</div>
																								
														<div class="wpw_fp_indivisual_submit" >
															<?php //if( !empty( $all_ind_items ) ) { ?>
																									
																<a href="javascript:void(0);" onclick="wpw_fp_checkall('<?php echo $type->name;?>', 'notification');"><?php _e( 'Check all', 'wpwfp' ); ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="wpw_fp_uncheckall('<?php echo $type->name;?>', 'notification');"><?php _e( 'Uncheck all', 'wpwfp' ); ?></a>&nbsp;&nbsp;&nbsp;
																												
																<?php //} ?>
																					
																<input type="submit" value="<?php _e( 'Done', 'wpwfp' ); ?>" id="wpw_fp_set_submit_indivisual" class="button-primary">
														</div><!-- .wpw_fp_indivisual_submit -->					
													</div><!-- .wpw_fp_indivisual_header -->
																							
													<?php 	echo '<ul>';
																								
														//if( !empty( $all_ind_items ) ) {
														
															if ( $wp_query->have_posts() ) {
															while ( $wp_query->have_posts() ) { $wp_query->the_post();
																									
															//foreach ( $all_ind_items as $items ) { 
																											
																$checked = ( in_array( $wp_query->post->ID, $notification_items ) ) ? 'checked="checked"' : '';
																$posttitle = $wp_query->post->post_title;
																	if( strlen( $posttitle ) > 50 ) {
																		$posttitle = substr( $posttitle, 0, 50 );
																		$posttitle = $posttitle.'...';
																	} else {
																		$posttitle = $posttitle;
																	}
													?>
																										
														<li>
															<input type="checkbox" id="wpw_fp_notification_<?php echo $wp_query->post->ID; ?>" name="wpw_fp_options[notification_item_<?php echo $type->name; ?>][]" value="<?php echo $wp_query->post->ID; ?>" <?php echo $checked; ?> />
																											
															<label for="wpw_fp_notification_<?php echo $wp_query->post->ID;?>"><?php echo $posttitle; ?></label>
														</li>
														
													<?php	} 
													} else { ?>
														<li><?php esc_attr_e( 'No Entries for this type.', 'wpwfp' ); ?></li>
												<?php	} ?>
													<?php wp_reset_query(); ?>
																											
													<?php	//} 
																							
														//}
																							
														echo '</ul>'; 
													?>
																						
												</div><!--fp_indivisual_data_-->
											<?php	} ?>
										</ul>
										<span class="description"><?php echo __( 'Check each of the post types for which you want the follows should receive an emails when any post is updated. You will see a list of all available content types from your site. If you click on the checkbox beside a content type, this means when any post of selected post type gets updated, all followers of that post will get an notification email. Click on', 'wpwfp').' "'.__( 'choose individual entries', 'wpwfp').'" '.__( 'and a pop up window will appear from which you can select individual posts for which notification emails will be send to followers.', 'wpwfp' ); ?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[double_opt_in]"><?php _e('Require Email Confirmation:', 'wpwfp');?></label>
									</th>
									<td>
										<input type="checkbox" id="wpw_fp_options[double_opt_in]" name="wpw_fp_options[double_opt_in]" value="1" <?php checked( '1', isset( $wpw_fp_options['double_opt_in'] ) ? $wpw_fp_options['double_opt_in'] : '' ) ?>/><br />
										<span class="description"><?php echo __( 'When checked, Registered users will be sent a confirmation email to subscribe, and will only be added once they confirmed the subscription.', 'wpwfp').'<br><strong>'.__( 'Note', 'wpwfp').' :</strong> '.__( 'Guest users will always need to confirm their email first for subscription. This setting is just for registered users.', 'wpwfp' ); ?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[recipient_per_email]"><?php _e('Recipients Per Email:', 'wpwfp');?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[recipient_per_email]" name="wpw_fp_options[recipient_per_email]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['recipient_per_email'] ); ?>" class="small-text" /><br />
										<span class="description"><?php _e( 'Restrict the number of recipients per email. Leave it 0 for unlimited.', 'wpwfp' ); ?></span>
									</td>
								</tr>
								
								<!-- Posts Notification Events Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'Posts Notification Events', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[post_trigger_notification]"><?php _e('Trigger Emails:', 'wpwfp');?></label>
									</th>
									<td>
										<input type="checkbox" id="wpw_fp_options[post_trigger_notification][post_update]" name="wpw_fp_options[post_trigger_notification][post_update]" value="1" <?php checked( '1', isset( $wpw_fp_options['post_trigger_notification']['post_update'] ) ? $wpw_fp_options['post_trigger_notification']['post_update'] : '' ) ?>/>
										<label for="wpw_fp_options[post_trigger_notification][post_update]" class="wpw-fp-checkbox-label"><?php _e( 'When post / page updated', 'wpwfp' );?></label><br />
										<input type="checkbox" id="wpw_fp_options[post_trigger_notification][new_comment]" name="wpw_fp_options[post_trigger_notification][new_comment]" value="1" <?php checked( '1', isset( $wpw_fp_options['post_trigger_notification']['new_comment'] ) ? $wpw_fp_options['post_trigger_notification']['new_comment'] : '' ) ?>/>
										<label for="wpw_fp_options[post_trigger_notification][new_comment]" class="wpw-fp-checkbox-label"><?php _e( 'When new comment addded', 'wpwfp' );?></label><br />
										<span class="description"><?php echo __( 'With an Trigger Emails, you can automatically send emails at specified event performed.', 'wpwfp' );?></span>
									</td>
								</tr>
								<!-- Posts Notification Events End -->
								
								<!-- Terms Notification Events Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'Terms Notification events', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[term_trigger_notification]"><?php _e('Trigger Emails:', 'wpwfp');?></label>
									</th>
									<td>
										<input type="checkbox" id="wpw_fp_options[term_trigger_notification][new_post]" name="wpw_fp_options[term_trigger_notification][new_post]" value="1" <?php checked( '1', isset( $wpw_fp_options['term_trigger_notification']['new_post'] ) ? $wpw_fp_options['term_trigger_notification']['new_post'] : '' ) ?>/>
										<label for="wpw_fp_options[term_trigger_notification][new_post]" class="wpw-fp-checkbox-label"><?php _e( 'When new post published', 'wpwfp' );?></label><br />
										<input type="checkbox" id="wpw_fp_options[term_trigger_notification][post_update]" name="wpw_fp_options[term_trigger_notification][post_update]" value="1" <?php checked( '1', isset( $wpw_fp_options['term_trigger_notification']['post_update'] ) ? $wpw_fp_options['term_trigger_notification']['post_update'] : '' ) ?>/>
										<label for="wpw_fp_options[term_trigger_notification][post_update]" class="wpw-fp-checkbox-label"><?php _e( 'When post / page updated', 'wpwfp' );?></label><br />
										<input type="checkbox" id="wpw_fp_options[term_trigger_notification][new_comment]" name="wpw_fp_options[term_trigger_notification][new_comment]" value="1" <?php checked( '1', isset( $wpw_fp_options['term_trigger_notification']['new_comment'] ) ? $wpw_fp_options['term_trigger_notification']['new_comment'] : '' ) ?>/>
										<label for="wpw_fp_options[term_trigger_notification][new_comment]" class="wpw-fp-checkbox-label"><?php _e( 'When new comment addded', 'wpwfp' );?></label><br />
										<span class="description"><?php echo __( 'With an Trigger Emails, you can automatically send emails at specified event performed.', 'wpwfp' );?></span>
									</td>
								</tr>
								<!-- Terms Notification Events End -->
								
								<!-- Authors Notification Events Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'Authors Notification events', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[author_trigger_notification]"><?php _e('Trigger Emails:', 'wpwfp');?></label>
									</th>
									<td>
										<input type="checkbox" id="wpw_fp_options[author_trigger_notification][new_post]" name="wpw_fp_options[author_trigger_notification][new_post]" value="1" <?php checked( '1', isset( $wpw_fp_options['author_trigger_notification']['new_post'] ) ? $wpw_fp_options['author_trigger_notification']['new_post'] : '' ) ?>/>
										<label for="wpw_fp_options[author_trigger_notification][new_post]" class="wpw-fp-checkbox-label"><?php _e( 'When new post published', 'wpwfp' );?></label><br />
										<input type="checkbox" id="wpw_fp_options[author_trigger_notification][post_update]" name="wpw_fp_options[author_trigger_notification][post_update]" value="1" <?php checked( '1', isset( $wpw_fp_options['author_trigger_notification']['post_update'] ) ? $wpw_fp_options['author_trigger_notification']['post_update'] : '' ) ?>/>
										<label for="wpw_fp_options[author_trigger_notification][post_update]" class="wpw-fp-checkbox-label"><?php _e( 'When post / page updated', 'wpwfp' );?></label><br />
										<input type="checkbox" id="wpw_fp_options[author_trigger_notification][new_comment]" name="wpw_fp_options[author_trigger_notification][new_comment]" value="1" <?php checked( '1', isset( $wpw_fp_options['author_trigger_notification']['new_comment'] ) ? $wpw_fp_options['author_trigger_notification']['new_comment'] : '' ) ?>/>
										<label for="wpw_fp_options[author_trigger_notification][new_comment]" class="wpw-fp-checkbox-label"><?php _e( 'When new comment addded', 'wpwfp' );?></label><br />
										<span class="description"><?php echo __( 'With an Trigger Emails, you can automatically send emails at specified event performed.', 'wpwfp' );?></span>
									</td>
								</tr>
								<!-- Authors Notification Events End -->
								
								<?php
								
									// do action for add setting after notification settings
									do_action( 'wpw_fp_after_notification_setting', $wpw_fp_options );
									
								?>
								
								<tr>
									<td colspan="2">
										<?php
											echo apply_filters ( 'wpweb_fb_settings_submit_button', '<input class="button-primary wpw-fp-save-btn" type="submit" name="wpw-fp-set-submit" value="'.__( 'Save Changes','wpwfp' ).'" />' );
										?>
									</td>
								</tr>
							</tbody>
						</table>
					 
				</div><!-- .inside -->
			</div><!-- #notification -->
		</div><!-- .meta-box-sortables ui-sortable -->
	</div><!-- .metabox-holder -->
</div><!-- #wpw-fp-notification -->