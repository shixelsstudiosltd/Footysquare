<?php
global $px_theme_option;
if ( comments_open() ) {
	if ( post_password_required() ) return;
?>

		<?php if ( have_comments() ) : ?>
        <div class="pix-content-wrap">
			<div id="comments">
				<header>
					<h2 class="heading-color section-title uppercase">
						<?php echo comments_number(__('No Comments', 'Kings Club'), __('1 Comment', 'Kings Club'), __('% Comments', 'Kings Club') );?>
                    </h2>
				</header>
                <ul>
                	<?php wp_list_comments( array( 'callback' => 'pixFill_comment' ) );	?>
                </ul>
				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
					<div class="navigation">
						<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'Kings Club') ); ?></div>
						<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'Kings Club') ); ?></div>
					</div> <!-- .navigation -->
				<?php endif; // check for comment navigation ?>
 				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
                    <div class="navigation">
                        <div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'Kings Club') ); ?></div>
                        <div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'Kings Club') ); ?></div>
                    </div><!-- .navigation -->
                <?php endif; ?>
			</div>
           </div>
		<?php endif; // end have_comments() ?>
        <div class="pix-content-wrap">
		<?php 
			global $post_id;
			$you_may_use = __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'Kings Club');
			$must_login = __( 'You must be <a href="%s">logged in</a> to post a comment.', 'Kings Club');
			$logged_in_as = __('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'Kings Club');
			$required_fields_mark = ' ' . __('Required fields are marked %s', 'Kings Club');
			$required_text = sprintf($required_fields_mark , '<span class="required">*</span>' );
	
			$defaults = array( 'fields' => apply_filters( 'comment_form_default_fields', 
				array(
					'notes' => '<p class="comment-notes">
                            </p>',
					'author' => '<p class="comment-form-author">'.
					'<label class="form-icons">
						<small class="fa fa-ellipsis-v"></small>
						<i class="fa fa-user"></i>
						
					</label>
					<input id="author" name="author" class="nameinput" type="text" value="' .
					esc_attr( __( 'Name', 'GreenPeace') ) . '" size="30" tabindex="1"  />' .
					'<span>'.( $req ? __( '(required)', 'Kings Club') : '' ) .'</span>'.
					'</p><!-- #form-section-author .form-section -->',
					
					'email'  => '<p class="comment-form-email">' .
					'<label class="form-icons">
						<small class="fa fa-ellipsis-v"></small>
						<i class="fa fa-envelope"></i>
					</label>'.
					'<input id="email" name="email" class="emailinput" type="text"  value="' . 
					esc_attr(  __( 'Email', 'GreenPeace')  ) . '" size="30" tabindex="2"/>' .
					'<span>'.( $req ? __( '(required)', 'Kings Club') : '' ) .'</span>'.
					'</p><!-- #form-section-email .form-section -->',
					
					'url'    => '<p class="comment-form-website">' .
					'<label class="form-icons">
						<small class="fa fa-ellipsis-v"></small>
						<i class="fa fa-phone"></i>
					</label>' .
					'<input id="url" name="url" type="text" class="websiteinput"  value="' . esc_attr( __( 'Website', 'GreenPeace') ) . '" size="30" tabindex="3" />' .
					'<span>'.( $req ? __( '(required)', 'Kings Club') : '' ) .'</span>'.
					'</p><!-- #<span class="hiddenSpellError" pre="">form-section-url</span> .form-section -->' ) ),
					
					'comment_field' => '<p class="comment-form-comment fullwidth">'.
					'<textarea id="comment" name="comment"  class="commenttextarea" rows="4" cols="39"></textarea>' .
					'</p><!-- #form-section-comment .form-section -->',
					
					'must_log_in' => '<p class="must-log-in">' .  sprintf( $must_login,	wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
					'logged_in_as' => '<p class="logged-in-as">' . sprintf( $logged_in_as, admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ),
					'comment_notes_before' => '',
					'comment_notes_after' =>  '',
					'id_form' => 'commentform',
					'id_submit' => 'submit-comment',
					'title_reply' => __( 'Leave a Comment', 'Kings Club' ),
					'title_reply_to' => __( 'Leave a Reply to %s', 'Kings Club' ),
					'cancel_reply_link' => __( 'Cancel reply', 'Kings Club' ),
					'label_submit' => __( 'Submit', 'Kings Club' ),); 
					comment_form($defaults, $post_id); 
				?>
			</div>
 <?php }?>