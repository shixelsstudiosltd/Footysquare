<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page General Tab
 * 
 * The code for the plugins settings page general tab
 * 
 * @package Follow My Blog Post
 * @since 1.0.0
 */

//get all pages
$get_pages = get_pages();

?>
<!-- beginning of the general settings meta box -->
<div id="wpw-fp-general" class="post-box-container">
	<div class="metabox-holder">
		<div class="meta-box-sortables ui-sortable">
			<div id="general" class="postbox">
				<div class="handlediv" title="<?php _e( 'Click to toggle', 'wpwfp' ); ?>"><br /></div>
					
					<!-- general settings box title -->
					<h3 class="hndle">
						<span style='vertical-align: top;'><?php _e( 'General Settings', 'wpwfp' ); ?></span>
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
							
								// do action for add setting before general settings
								do_action( 'wpw_fp_before_general_setting', $wpw_fp_options );
								
							?>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[del_all_options]"><?php _e( 'Delete Options:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<input type="checkbox" name="wpw_fp_options[del_all_options]" id="wpw_fp_options[del_all_options]" value="1" <?php checked( '1', isset( $wpw_fp_options['del_all_options'] ) ? $wpw_fp_options['del_all_options'] : '' ) ?> /><br />
									<span class="description"><?php _e( 'If you don\'t want to use the Follow My Blog Post Plugin on your site anymore, you can check that box. This makes sure, that all the settings and tables are being deleted from the database when you deactivate the plugin.', 'wpwfp' ); ?></span>
								</td>
							</tr>
							
							<tr>
								<th scope="row">
									<label for="wpw_fp_options[disable_follow_guest]"><?php _e('Disable Guest Followes:', 'wpwfp');?></label>
								</th>
								<td>
									<input type="checkbox" id="wpw_fp_options[disable_follow_guest]" name="wpw_fp_options[disable_follow_guest]" value="1" <?php checked( '1', isset( $wpw_fp_options['disable_follow_guest'] ) ? $wpw_fp_options['disable_follow_guest'] : '' ) ?>/><br />
									<span class="description"><?php _e( 'Guests (non-logged-in users) have the permission to follow any post by default. If you check this option, then Follow button would not be displayed for Guest users.', 'wpwfp' ); ?></span>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[enable_log]"><?php _e( 'Enable Email Logs:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<input type="checkbox" name="wpw_fp_options[enable_log]" id="wpw_fp_options[enable_log]" value="1" <?php checked( '1', isset( $wpw_fp_options['enable_log'] ) ? $wpw_fp_options['enable_log'] : '' ) ?> /><br />
									<span class="description"><?php printf( __( 'Check this box if you want to log all emails into database. Enabling this will add a new column on followers listing page to view the log entries.', 'wpwfp' ).'<br><strong>'.__('Note', 'wpwfp') .': </strong>'.__( 'It is', 'wpwfp').'<b> '.__( 'not recommended', 'wpwfp').'</b> '.__( 'to enable this option, as this will slow down the plugin.', 'wpwfp' ) ); ?></span>
								</td>
							</tr>
							
							<?php
							
								// do action for add setting after enable email settings
								do_action( 'wpw_fp_after_enable_email_log_setting', $wpw_fp_options );
								
							?>
							<!-- Posts Follow Settings Start -->
							<tr>
								<td colspan="2">
									<strong><?php _e( 'Posts Follow Settings', 'wpwfp' ); ?></strong>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[follow_buttons]"><?php _e( 'Follow Button Text:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<input type="text" name="wpw_fp_options[follow_buttons][follow]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['follow_buttons']['follow'] ); ?>" />
									<input type="text" name="wpw_fp_options[follow_buttons][following]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['follow_buttons']['following'] ); ?>" />
									<input type="text" name="wpw_fp_options[follow_buttons][unfollow]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['follow_buttons']['unfollow'] ); ?>" /></br>
									<span class="description"><?php _e( 'Enter Follow, Following and Unfollow button text respectively. Default button text are Follow, Following and Unfollow respectively.', 'wpwfp' ); ?></span>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[follow_message]"><?php _e( 'Followers Counter Message:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<input type="text" name="wpw_fp_options[follow_message]" id="follow_message" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['follow_message'] ); ?>" size="76" /></br>
									<span class="description"><?php printf( __( 'Here you can give the custom message to show followers count for Post / Page. Leave it blank to hide the followers counter. %s - displays the followers count.', 'wpwfp' ), '<br /><code>{followers_count}</code>'); ?></span>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[follow_form_location]"><?php _e( 'Follow Button Location:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<select name="wpw_fp_options[follow_form_location]" id="wpw_fp_options[follow_form_location]" class="chosen-select">
										<?php   												
											$select_location = array( 'after_content' => __( 'After Content', 'wpwfp' ), 'before_content' => __( 'Before Content', 'wpwfp' ) );
															
											foreach ( $select_location as $key => $option ) {											
												?>
												<option value="<?php echo $key; ?>" <?php selected( $wpw_fp_options['follow_form_location'], $key ); ?>>
													<?php esc_html_e( $option ); ?>
												</option>
												<?php
											}														
										?> 														
									</select><br />
									<span class="description"><?php _e( 'Select the location where you want the follow button being displayed.', 'wpwfp' ); ?></span>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[follow_form_position]"><?php _e( 'Follow Button Position:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<select name="wpw_fp_options[follow_form_position]" id="wpw_fp_options[follow_form_position]" class="chosen-select">
										<?php 
											$select_location = array( 'left' => __( 'Left', 'wpwfp' ), 'right' => __( 'Right', 'wpwfp' ) );
											
											foreach ( $select_location as $key => $option ) { 
												?>
												<option value="<?php echo $key; ?>" <?php selected( $wpw_fp_options['follow_form_position'], $key ); ?>>
													<?php esc_html_e( $option ); ?>
												</option><?php
											}
										?>
									</select> <br />
									<span class="description"><?php _e( 'Select the position where you want the follow button being displayed.', 'wpwfp' ); ?></span>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[prevent_type]"><?php _e( 'Enable Follow Button On:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<ul class="wpw_fp_indvisual" id="wpw_fp_indvisual">
									<?php 
										$all_types = get_post_types( array( 'public' => true ), 'objects');
										$all_types = is_array( $all_types ) ? $all_types : array();
										unset( $all_types['attachment'] );
										
										if( !empty( $wpw_fp_options['prevent_type'] ) ) {
											$prevent_types = $wpw_fp_options['prevent_type'];
										} else {
											$prevent_types = '';
										}
										
										$prevent_types = is_array( $prevent_types ) ? $prevent_types : array();
										
										foreach ( $all_types as $type ) {
											if( !empty( $wpw_fp_options['prevent_item_'.$type->name] ) ) {
												$items_count = count( $wpw_fp_options['prevent_item_'.$type->name] );
											} else {
												$wpw_fp_options['prevent_item_'.$type->name] = '';
												$items_count = 0;
											}
											
											if ( !is_object( $type ) ) continue;
												$label = @$type->labels->name ? $type->labels->name : $type->name;
												$selected = ( in_array( $type->name, $prevent_types ) ) ? 'checked="checked"' : '';
												
												$individual = '<a href="javascript:void(0);" class="wpw_fp_indivisual_entries" onclick="wpw_fp_popup_indivisual( \'' . $type->name . '\', \'\' )">' . __( 'Choose individual entries', 'wpwfp' ) . '</a>';
									?>
										
										<li>
											<input type="checkbox" id="wpw_fp_prevent_<?php echo $type->name; ?>" name="wpw_fp_options[prevent_type][]" value="<?php echo $type->name; ?>" <?php echo $selected; ?>/>
											
											<label for="wpw_fp_prevent_<?php echo $type->name; ?>"><?php echo $label; ?></label>
												<?php echo $individual; ?>&nbsp;&nbsp;&nbsp;<label id="wpw_fp_selected_indivisual_<?php echo $type->name; ?>"><?php echo '( ' . $items_count . ' ) ';?><?php _e( 'Selected', 'wpwfp' ); ?></label>
										</li>
										
									<?php 	$args = array( 'post_type' => $type->name, 'posts_per_page' => '-1' );
											$wp_query = null;
											$wp_query = new WP_Query;
											$wp_query->query( $args );
											
											if( !empty( $wpw_fp_options['prevent_item_'.$type->name] ) ) {
												$prevent_items = $wpw_fp_options['prevent_item_'.$type->name];
											} else {
												$prevent_items = '';
											}
														
											$prevent_items = is_array( $prevent_items ) ? $prevent_items : array();
									?>
																																		
											<!-- listing all post related post -->
											<div id="fp_indivisual_data_<?php echo $type->name; ?>" class="wpw_fp_indivisual_entries_data" style="display:none;">
												<div class="wpw_fp_indivisual_header">
													<div class="wpw_fp_indivisual_title">
														<h2><?php echo $label; ?></h2>
													</div>
													
													<div class="wpw_fp_indivisual_submit" >
														<?php //if( !empty( $all_ind_items ) ) { ?>
															
															<a href="javascript:void(0);" onclick="wpw_fp_checkall('<?php echo $type->name;?>', '');"><?php _e( 'Check all', 'wpwfp' ); ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="wpw_fp_uncheckall('<?php echo $type->name;?>', '');"><?php _e( 'Uncheck all', 'wpwfp' ); ?></a>&nbsp;&nbsp;&nbsp;
															
															<?php //} ?>
															
															<input type="submit" value="<?php _e( 'Done', 'wpwfp' ); ?>" id="wpw_fp_set_submit_indivisual" class="button-primary">
													</div><!-- .wpw_fp_indivisual_submit -->
												</div><!-- .wpw_fp_indivisual_header -->
												
												<?php 	echo '<ul>';
													
													//if( !empty( $all_ind_items ) ) {
														
														if ( $wp_query->have_posts() ) {
														while ( $wp_query->have_posts() ) { $wp_query->the_post();
														
														//foreach ( $all_ind_items as $items ) { 
															
															$checked = ( in_array( $wp_query->post->ID, $prevent_items ) ) ? 'checked="checked"' : '';
															$posttitle = $wp_query->post->post_title;
																if( strlen( $posttitle ) > 50 ) {
																	$posttitle = substr( $posttitle, 0, 50 );
																	$posttitle = $posttitle.'...';
																} else {
																	$posttitle = $posttitle;
																}?>
															
															<li>
																<input type="checkbox" id="wpw_fp_prevent_<?php echo $wp_query->post->ID; ?>" name="wpw_fp_options[prevent_item_<?php echo $type->name; ?>][]" value="<?php echo $wp_query->post->ID; ?>" <?php echo $checked; ?> />
																
																<label for="wpw_fp_prevent_<?php echo $wp_query->post->ID;?>"><?php echo $posttitle; ?></label>
															</li><?php	
														}
													} else { ?>
														<li><?php esc_attr_e( 'No Entries for this type.', 'wpwfp' ); ?></li><?php	
													} 
													
													wp_reset_query(); 
													
													//} 
													
													//}
													
													echo '</ul>';?>
											</div><!--fp_indivisual_data_--><?php	
										} ?>
									</ul>
									<span class="description"><?php _e( 'Check each of the post types on which you want the follow button being displayed. You will see a list of all available content types from your site. If you click on the checkbox beside a content type, this means that all content of this type will have the follow button added automatically. Click on "choose individual entries" and a pop up window will appear from which you can select individual posts on which you want to have the follow button being added.', 'wpwfp' ); ?></span>
								</td>
							</tr>
							<!-- Posts Follow Settings End -->
							
							<!-- Terms Follow Settings Start -->
							<tr>
								<td colspan="2">
									<strong><?php _e( 'Terms Follow Settings', 'wpwfp' ); ?></strong>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[term_follow_buttons]"><?php _e( 'Follow Button Text:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<input type="text" name="wpw_fp_options[term_follow_buttons][follow]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['term_follow_buttons']['follow'] ); ?>" />
									<input type="text" name="wpw_fp_options[term_follow_buttons][following]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['term_follow_buttons']['following'] ); ?>" />
									<input type="text" name="wpw_fp_options[term_follow_buttons][unfollow]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['term_follow_buttons']['unfollow'] ); ?>" /></br>
									<span class="description"><?php printf( __( 'Enter Follow, Following and Unfollow button text respectively. Default button text are Follow, Following and Unfollow respectively. %s - displays the title of the term.', 'wpwfp' ), '<br /><code>{term_name}</code>' ); ?></span>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[term_follow_message]"><?php _e( 'Followers Counter Message:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<input type="text" name="wpw_fp_options[term_follow_message]" id="term_follow_message" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['term_follow_message'] ); ?>" size="76" /></br>
									<span class="description"><?php printf( __( 'Here you can give the custom message to show followers count for Terms. Leave it blank to hide the followers counter.%s - displays the followers count.', 'wpwfp' ), '<br /><code>{followers_count}</code>' ); ?></span>
								</td>
							</tr>
							<!-- Terms Follow Settings End -->
							
							<!-- Author Follow Settings Start -->
							<tr>
								<td colspan="2">
									<strong><?php _e( 'Authors Follow Settings', 'wpwfp' ); ?></strong>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[authors_follow_buttons]"><?php _e( 'Follow Button Text:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<input type="text" name="wpw_fp_options[authors_follow_buttons][follow]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['authors_follow_buttons']['follow'] ); ?>" />
									<input type="text" name="wpw_fp_options[authors_follow_buttons][following]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['authors_follow_buttons']['following'] ); ?>" />
									<input type="text" name="wpw_fp_options[authors_follow_buttons][unfollow]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['authors_follow_buttons']['unfollow'] ); ?>" /></br>
									<span class="description"><?php printf( __( 'Enter Follow, Following and Unfollow button text respectively. Default button text are Follow, Following and Unfollow respectively. %s - displays the name of the author.', 'wpwfp' ), '<br /><code>{author_name}</code>' ); ?></span>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="wpw_fp_options[authors_follow_message]"><?php _e( 'Followers Counter Message:', 'wpwfp' ); ?></label>
								</th>
								<td>
									<input type="text" name="wpw_fp_options[authors_follow_message]" id="authors_follow_message" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['authors_follow_message'] ); ?>" size="76" /></br>
									<span class="description"><?php printf( __( 'Here you can give the custom message to show followers count for Authors. Leave it blank to hide the followers counter.%s - displays the followers count.', 'wpwfp' ), '<br /><code>{followers_count}</code>' ); ?></span>
								</td>
							</tr>
							<!-- Author Follow Settings End -->
							
							<!-- Page Settings Start -->
							<tr>
								<td colspan="2">
									<strong><?php _e( 'Pages Settings', 'wpwfp' ); ?></strong>
								</td>
							</tr>
							
							<tr>
								<th scope="row">
									<label for="wpw_fp_options[subscribe_manage_page]"><?php _e( 'Subscription Manage Page:', 'wpwfp' );?></label>
								</th>
								<td>
									<select id="wpw_fp_options[subscribe_manage_page]" name="wpw_fp_options[subscribe_manage_page]" class="chosen-select">
										<option value=""><?php _e('--Select A Page--','wpwfp');?></option>
										<?php foreach ( $get_pages as $page ) { ?>
												<option value="<?php echo $page->ID;?>" <?php selected( $page->ID, $wpw_fp_options['subscribe_manage_page'], true ); ?>><?php _e( $page->post_title );?></option>
										<?php } ?>
									</select><br />
									<span class="description"><?php _e( 'This is subscription manage page whcih shows followed posts list and followed terms list to user.', 'wpwfp' ); ?></span>
								</td>
							</tr>
							
							<tr>
								<th scope="row">
									<label for="wpw_fp_options[unsubscribe_page]"><?php _e( 'Unsubscription Manage Page:', 'wpwfp' );?></label>
								</th>
								<td>
									<select id="wpw_fp_options[unsubscribe_page]" name="wpw_fp_options[unsubscribe_page]" class="chosen-select">
										<option value=""><?php _e('--Select A Page--','wpwfp');?></option>
										<?php foreach ( $get_pages as $page ) { ?>
												<option value="<?php echo $page->ID;?>" <?php selected( $page->ID, $wpw_fp_options['unsubscribe_page'], true ); ?>><?php _e( $page->post_title );?></option>
										<?php } ?>
									</select><br />
									<span class="description"><?php _e( 'This is unsubscription manage Page whcih contains the form to unsubscribe the email address for the users.', 'wpwfp' ); ?></span>
								</td>
							</tr>
							<!-- Page Settings End --><?php
							
							// do action for add setting after general settings
							do_action( 'wpw_fp_after_general_setting', $wpw_fp_options );
							
							?>
							<tr>
								<td colspan="2"><?php
									echo apply_filters ( 'wpweb_fb_settings_submit_button', '<input class="button-primary wpw-fp-save-btn" type="submit" name="wpw-fp-set-submit" value="'.__( 'Save Changes','wpwfp' ).'" />' );?>
								</td>
							</tr>
						</tbody>
					</table>
				</div><!-- .inside -->
			</div><!-- #general -->
		</div><!-- .meta-box-sortables ui-sortable -->
	</div><!-- .metabox-holder -->
</div><!-- #wpw-fp-general -->