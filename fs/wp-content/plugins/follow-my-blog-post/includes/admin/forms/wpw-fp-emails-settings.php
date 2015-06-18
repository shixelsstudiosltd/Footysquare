<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page Emails Tab
 *
 * The code for the plugins settings page emails tab
 *
 * @package Follow My Blog Post
 * @since 1.0.0
 */

?>
<!-- beginning of the emails settings meta box -->
<div id="wpw-fp-emails" class="post-box-container">
	<div class="metabox-holder">	
		<div class="meta-box-sortables ui-sortable">
			<div id="emails" class="postbox">	
				<div class="handlediv" title="<?php _e( 'Click to toggle', 'wpwfp' ); ?>"><br /></div>

					<!-- emails settings box title -->
					<h3 class="hndle">
						<span style='vertical-align: top;'><?php _e( 'Emails Settings', 'wpwfp' ); ?></span>
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
								
									// do action for add setting before emails settings
									do_action( 'wpw_fp_before_emails_setting', $wpw_fp_options );
									
								?>
								
								<tr valign="top">
									<th scope="row"><label for="wpw_fp_options[email_template]"><?php _e( 'Email Template:', 'wpwfp' ); ?></label></th>
									<td>
										<select id="wpw_fp_options[email_template]" name="wpw_fp_options[email_template]" class="wpw-fp-email-template chosen-select">
											<?php 
												$email_templates = $model->wpw_fp_email_get_templates();
												
												foreach ( $email_templates as $key => $option ) { ?>
													<option value="<?php echo $key; ?>" <?php selected( isset($wpw_fp_options['email_template']) ? $wpw_fp_options['email_template'] : '', $key ); ?>>
														<?php esc_html_e( $option ); ?>
													</option><?php
												}
											?>
										</select><br />
										<span class="description"><?php echo __( 'Choose a template and Choose', 'wpwfp') . ' "'.__( 'Preview Test Email', 'wpwfp').'" '.__('to see the email template.', 'wpwfp'); ?></span>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row"></th>
									<td>
										<a class="button wpw-fp-preview-follow-email" href="#wpw_fp_preview_follow_email" rel="wpw_fp_preview_follow_email"><?php _e( 'Preview Test Email', 'wpwfp' ); ?></a>
										<a class="button wpw-fp-send-test-email" href="javascript:void(0);"><?php _e( 'Send Test Email', 'wpwfp' ); ?></a>
										<img class="wpw-fp-loader" src="<?php echo WPW_FP_IMG_URL ?>/loader.gif" alt="<?php _e( 'Loading...', 'wpwfp' ) ?>" />
										<span class="wpw-fp-send-email-msg"></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[from_email]"><?php _e( 'From Email Address:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[from_email]" name="wpw_fp_options[from_email]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['from_email'] ); ?>" class="large-text" /><br />
										<span class="description"><?php echo __( 'Example: Your Name &lt;sales@your-domain.com&gt;. These are the name and email address used as a from to followers emails. The email address is also being used as the','wpwfp').' "'.__( 'Reply To','wpwfp').'" '.__( 'email address when users send an email back.','wpwfp' );?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[enable_unsubscribe_url]"><?php _e('Add Unsubscribe link to email Message:', 'wpwfp');?></label>
									</th>
									<td>
										<input type="checkbox" id="wpw_fp_options[enable_unsubscribe_url]" name="wpw_fp_options[enable_unsubscribe_url]" value="1" <?php checked( '1', isset( $wpw_fp_options['enable_unsubscribe_url'] ) ? $wpw_fp_options['enable_unsubscribe_url'] : '' ) ?>/><br />
										<span class="description"><?php _e( 'When checked, Unsubscribe link with below custom message will be added to each subscription email Message.', 'wpwfp' ); ?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[unsubscribe_message]"><?php _e( 'Unsubscribe Message:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[unsubscribe_message]" name="wpw_fp_options[unsubscribe_message]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['unsubscribe_message'] ); ?>" class="large-text" /><br />
										<span class="description"><?php printf( __('Enter the custom Unsubscribe Message. Available template tags for unsubscribe message are : %s - displays the unsubscribe url for unsubscribe email','wpwfp'), '<br/><code>{unsubscribe_url}</code>');?></span>
									</td>
								</tr>
								
								<!-- Post / Page Subscription Email Template Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'Edit Post Subscription Email Template', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[email_subject]"><?php _e( 'Email Subject:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[email_subject]" name="wpw_fp_options[email_subject]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['email_subject'] ); ?>" class="large-text" /><br />
										<span class="description"><?php printf( __( 'This is the subject of the email that will be sent to the followers of that post when post is updated. Available template tags for subject fields are : %s - displays the title of the post %s - displays the name of your site %s','wpwfp'), '<br/><code>{post_name}</code>', '<br /><code>{site_name}</code>', '<br />' );?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options_email_body"><?php _e( 'Email Body:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<?php 
											$settings = array( 'textarea_name' => 'wpw_fp_options[email_body]', 'teeny' => true );
											wp_editor( $wpw_fp_options['email_body'], 'wpw_fp_options_email_body', $settings );
										?>
										<?php 
											$email_desc = 'This is the body, main content of the email that will be sent to the followers of that post when post is updated. The available tags are: <br />
											<code>{post_name}</code> - displays the title of the post<br />
											<code>{post_link}</code> - displays the post title with link<br />
											<code>{site_name}</code> - displays the name of your site<br />
											<code>{site_link}</code> - displays the site name with link';
										?>
										<span class="description"><?php _e( $email_desc, 'wpwfp' ); ?></span>
									</td>
								</tr>
								<!-- Post / Page Subscription Email Template End -->
								
								<!-- Category / Tags Subscription Email Template Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'New Post Term Subscription Email Template', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[term_email_subject]"><?php _e( 'Email Subject:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[term_email_subject]" name="wpw_fp_options[term_email_subject]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['term_email_subject'] ); ?>" class="large-text" /><br />
										<span class="description"><?php printf( __( 'This is the subject of the email that will be sent to the followers of the selected terms when new post published. Available template tags for subject fields are : %s - displays the title of the post %s - displays the name of your site','wpwfp'), '<br/><code>{post_name}</code>', '<br /><code>{site_name}</code>' );?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options_term_email_body"><?php _e( 'Email Body:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<?php 
											$settings = array( 'textarea_name' => 'wpw_fp_options[term_email_body]', 'teeny' => true );
											wp_editor( $wpw_fp_options['term_email_body'], 'wpw_fp_options_term_email_body', $settings );
										?>
										<?php 
											$term_email_desc = __('This is the body, main content of the email that will be sent to the followers of the selected terms when new post published. The available tags are:', 'wpwfp')
											.'<br /><code>{post_name}</code> - '.__( 'displays the title of the post', 'wpwfp').
											'<br /><code>{post_description}</code> - '.__( 'displays the description of the post', 'wpwfp').
											'<br /><code>{post_link}</code> - '.__( 'displays the post title with link', 'wpwfp').
											'<br /><code>{term_name}</code> - '.__('displays the title of the term', 'wpwfp').
											'<br /><code>{taxonomy_name}</code> - '.__('displays the title of the taxonomy', 'wpwfp').
											'<br /><code>{site_name}</code> - '.__( 'displays the name of your site', 'wpwfp').
											'<br /><code>{site_link}</code> - '.__( 'displays the site name with link', 'wpwfp');
										?>
										<span class="description"><?php echo $term_email_desc; ?></span>
									</td>
								</tr>
								<!-- Category / Tags Subscription Email Template End -->
								
								<!-- Author Subscription Email Template Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'New Post Author Subscription Email Template', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[author_email_subject]"><?php _e( 'Email Subject:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[author_email_subject]" name="wpw_fp_options[author_email_subject]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['author_email_subject'] ); ?>" class="large-text" /><br />
										<span class="description"><?php printf( __( 'This is the subject of the email that will be sent to the all followers of the author of that new post when new post published. Available template tags for subject fields are : %s - displays the title of the post %s - displays the name of your site','wpwfp'), '<br/><code>{post_name}</code>', '<br /><code>{site_name}</code>' );?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options_author_email_body"><?php _e( 'Email Body:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<?php 
											$settings = array( 'textarea_name' => 'wpw_fp_options[author_email_body]', 'teeny' => true );
											wp_editor( $wpw_fp_options['author_email_body'], 'wpw_fp_options_author_email_body', $settings );
										?>
										<?php 
											$author_email_desc = __('This is the body, main content of the email that will be sent to the all followers of the author of that new post when new post published. The available tags are:', 'wpwfp')
											.'<br /><code>{post_name}</code> - '.__( 'displays the title of the post', 'wpwfp').
											'<br /><code>{post_description}</code> - '.__( 'displays the description of the post', 'wpwfp').
											'<br /><code>{post_link}</code> - '.__( 'displays the post title with link', 'wpwfp').
											'<br /><code>{author_name}</code> - '.__('displays the name of the author', 'wpwfp').
											'<br /><code>{site_name}</code> - '.__( 'displays the name of your site', 'wpwfp').
											'<br /><code>{site_link}</code> - '.__( 'displays the site name with link', 'wpwfp');
										?>
										<span class="description"><?php echo $author_email_desc; ?></span>
									</td>
								</tr>
								<!-- Author Subscription Email Template End -->
								
								<!-- Comment Subscription Email Template Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'Comment Subscription Email Template', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[comment_email_subject]"><?php _e( 'Email Subject:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[comment_email_subject]" name="wpw_fp_options[comment_email_subject]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['comment_email_subject'] ); ?>" class="large-text" /><br />
										<span class="description"><?php printf( __('This is the subject of the email that will be sent to the followers when comment is added and get approved. Available template tags for subject fields are : %s - displays the title of the post %s - displays the user name','wpwfp'), '<br/><code>{post_name}</code>', '<br /><code>{user_name}</code>' );?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options_comment_email_body"><?php _e( 'Email Body:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<?php 
											$settings = array( 'textarea_name' => 'wpw_fp_options[comment_email_body]', 'teeny' => true );
											wp_editor( $wpw_fp_options['comment_email_body'], 'wpw_fp_options_comment_email_body', $settings );
										?>
										<?php 
											$comment_email_desc = __( 'This is the body, main content of the email that will be sent to the followers when comment is added and get approved. The available tags are:', 'wpwfp').
											'<br /><code>{post_name}</code> - '.__('displays the title of the post', 'wpwfp').
											'<br /><code>{site_name}</code> - '.__('displays the name of your site', 'wpwfp').
											'<br /><code>{user_name}</code> - '.__('displays the user name', 'wpwfp').
											'<br /><code>{comment_text}</code> - '.__('displays the comment text of the post', 'wpwfp');
										?>
										<span class="description"><?php echo $comment_email_desc; ?></span>
									</td>
								</tr>
								<!-- Comment Subscription Email Template End -->
								
								<!-- Posts Confirmation Email Template Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'Posts Confirmation Email Template', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[confirm_email_subject]"><?php _e( 'Email Subject:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[confirm_email_subject]" name="wpw_fp_options[confirm_email_subject]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['confirm_email_subject'] ); ?>" class="large-text" /><br />
										<span class="description"><?php printf( __('This is the subject of the email that will be sent to the user for confirming his email address for subscription for post / page. Available template tags for subject fields are : %s - displays the title of the post %s - displays the name of your site','wpwfp'), '<br /><code>{post_name}</code>', '<br /><code>{site_name}</code>' );?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options_confirm_email_body"><?php _e( 'Email Body:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<?php 
											$settings = array( 'textarea_name' => 'wpw_fp_options[confirm_email_body]', 'teeny' => true );
											wp_editor( $wpw_fp_options['confirm_email_body'], 'wpw_fp_options_confirm_email_body', $settings );
										?>
										<?php 
											$confirm_email_desc = __( 'This is the body, main content of the email that will be sent to the user for confirming his email address for subscription for post / page. The available tags are:', 'wpwfp' ).
											'<br /><code>{post_name}</code> - '.__( 'displays the title of the post', 'wpwfp' ).
											'<br /><code>{post_link}</code> - '.__( 'displays the post title with link', 'wpwfp' ).
											'<br /><code>{site_name}</code> - '.__( 'displays the name of your site', 'wpwfp' ).
											'<br /><code>{site_link}</code> - '.__( 'displays the site name with link', 'wpwfp' ).
											'<br /><code>{subscribe_url}</code> - '.__( 'displays the subscribe url for confirm email subscription.', 'wpwfp' );
										?>
										<span class="description"><?php echo $confirm_email_desc; ?></span>
									</td>
								</tr>
								<!-- Posts Confirmation Email Template End -->
								
								<!-- Terms Confirmation Email Template Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'Terms Confirmation Email Template', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[term_confirm_email_subject]"><?php _e( 'Email Subject:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[term_confirm_email_subject]" name="wpw_fp_options[term_confirm_email_subject]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['term_confirm_email_subject'] ); ?>" class="large-text" /><br />
										<span class="description"><?php printf( __( 'This is the subject of the email that will be sent to the user for confirming his email address for subscription of any terms. Available template tags for subject fields are : %s - displays the title of the term %s - displays the name of your site','wpwfp'), '<br /><code>{term_name}</code>', '<br /><code>{site_name}</code>');?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options_term_confirm_email_body"><?php _e( 'Email Body:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<?php 
											$settings = array( 'textarea_name' => 'wpw_fp_options[term_confirm_email_body]', 'teeny' => true );
											wp_editor( $wpw_fp_options['term_confirm_email_body'], 'wpw_fp_options_term_confirm_email_body', $settings );
										?>
										<?php 
											$term_confirm_email_desc = __( 'This is the body, main content of the email that will be sent to the user for confirming his email address for subscription of any terms. The available tags are:', 'wpwfp').
											'<br /><code>{term_name}</code> - '.__( 'displays the title of the term', 'wpwfp').
											'<br /><code>{taxonomy_name}</code> - '.__( 'displays the title of the taxonomy', 'wpwfp').
											'<br /><code>{site_name}</code> - '.__( 'displays the name of your site', 'wpwfp').
											'<br /><code>{site_link}</code> - '.__( 'displays the site name with link', 'wpwfp').
											'<br /><code>{subscribe_url}</code> - '.__( 'displays the subscribe url for confirm email subscription.', 'wpwfp');
										?>
										<span class="description"><?php echo $term_confirm_email_desc; ?></span>
									</td>
								</tr>
								<!-- Terms Confirmation Email Template End -->
								
								<!-- Authors Confirmation Email Template Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'Authors Confirmation Email Template', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[author_confirm_email_subject]"><?php _e( 'Email Subject:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[author_confirm_email_subject]" name="wpw_fp_options[author_confirm_email_subject]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['author_confirm_email_subject'] ); ?>" class="large-text" /><br />
										<span class="description"><?php printf( __( 'This is the subject of the email that will be sent to the user for confirming his email address for subscription of any authors. Available template tags for subject fields are : %s - displays the name of author %s - displays the name of your site','wpwfp'), '<br /><code>{author_name}</code>', '<br /><code>{site_name}</code>');?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options_author_confirm_email_body"><?php _e( 'Email Body:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<?php 
											$settings = array( 'textarea_name' => 'wpw_fp_options[author_confirm_email_body]', 'teeny' => true );
											wp_editor( $wpw_fp_options['author_confirm_email_body'], 'wpw_fp_options_author_confirm_email_body', $settings );
										?>
										<?php 
											$author_confirm_email_desc = __( 'This is the body, main content of the email that will be sent to the user for confirming his email address for subscription of any authors. The available tags are:', 'wpwfp').
											'<br /><code>{author_name}</code> - '.__( 'displays the name of the author', 'wpwfp').
											'<br /><code>{site_name}</code> - '.__( 'displays the name of your site', 'wpwfp').
											'<br /><code>{site_link}</code> - '.__( 'displays the site name with link', 'wpwfp').
											'<br /><code>{subscribe_url}</code> - '.__( 'displays the subscribe url for confirm email subscription.', 'wpwfp');
										?>
										<span class="description"><?php echo $author_confirm_email_desc; ?></span>
									</td>
								</tr>
								<!-- Authors Confirmation Email Template End -->
								
								<!-- Unsubscribe Confirmation Email Template Start -->
								<tr>
									<td colspan="2">
										<strong><?php _e( 'Unsubscribe Confirmation Email Template', 'wpwfp' ); ?></strong>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options[unsubscribe_confirm_email_subject]"><?php _e( 'Email Subject:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<input type="text" id="wpw_fp_options[unsubscribe_confirm_email_subject]" name="wpw_fp_options[unsubscribe_confirm_email_subject]" value="<?php echo $wpw_fp_model->wpw_fp_escape_attr( $wpw_fp_options['unsubscribe_confirm_email_subject'] ); ?>" class="large-text" /><br />
										<span class="description"><?php printf( __( 'This is the subject of the email that will be sent to the user for confirming his email address for unsubscription. Available template tags for subject fields are : %s - displays the followers email %s - displays the name of your site','wpwfp'), '<br /><code>{email}</code>', '<br /><code>{site_name}</code>' );?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="wpw_fp_options_unsubscribe_confirm_email_body"><?php _e( 'Email Body:', 'wpwfp' ); ?></label>
									</th>
									<td>
										<?php 
											$settings = array( 'textarea_name' => 'wpw_fp_options[unsubscribe_confirm_email_body]', 'teeny' => true );
											wp_editor( $wpw_fp_options['unsubscribe_confirm_email_body'], 'wpw_fp_options_unsubscribe_confirm_email_body', $settings );
										?>
										<?php 
											$unsubscribe_confirm_email_desc = __( 'This is the body, main content of the email that will be sent to the user for confirming his email address for unsubscription. The available tags are:', 'wpwfp' ).
											'<br /><code>{email}</code> - '.__( 'displays the followers email', 'wpwfp' ).
											'<br /><code>{site_name}</code> - '.__( 'displays the name of your site', 'wpwfp' ).
											'<br /><code>{site_link}</code> - '.__( 'displays the site name with link', 'wpwfp' ).
											'<br /><code>{confirm_url}</code> - '.__( 'displays the confirm url for confirm email unsubscription.', 'wpwfp' );
										?>
										<span class="description"><?php echo $unsubscribe_confirm_email_desc; ?></span>
									</td>
								</tr>
								<!-- Unsubscribe Confirmation Email Template End -->
									
								<?php
								
									// do action for add setting after emails settings
									do_action( 'wpw_fp_after_emails_setting', $wpw_fp_options );
									
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
			</div><!-- #emails -->
		</div><!-- .meta-box-sortables ui-sortable -->
	</div><!-- .metabox-holder -->
</div><!-- #wpw-fp-emails -->