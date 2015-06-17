<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_forum_search_init() {
	// JS and CSS
	wp_enqueue_style('wps-forum-search-css', plugins_url('wps_forum_search.css', __FILE__), 'css');
}

																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */


function wps_forum_search($atts) {

	// Init
	add_action('wp_footer', 'wps_forum_search_init');

	$html = '';

	if ( !isset($_GET['forum_action']) ): // don't show if editing a post/comment or showing search results

		// Shortcode parameters
		extract( shortcode_atts( array(
			'url' => '',
			'class' => '',
			'style' => '',
			'label' => __('Search', WPS2_TEXT_DOMAIN),
			'show_with_results' => 1,
			'private' => 0,
			'private_msg' => __('You need to login first.', WPS2_TEXT_DOMAIN),
			'placeholder' => '',
			'before' => '',
			'after' => '',
		), $atts, 'wps_forum_search' ) );


		// Check if to show
		$show = true;
		if (!$show_with_results):

			$this_page = get_post(get_the_ID());
			if (strpos($this_page->post_content, '[wps-forum-search-results') !== FALSE) $show = false;
			wp_reset_query();

		endif;

		if ($show):

			if (!$private || is_user_logged_in()):

				$value = isset($_POST['wps_forum_search_entry']) ? stripslashes($_POST['wps_forum_search_entry']) : '';

				$form_html = '';
				$form_html .= '<div id="wps_forum_search">';

					$url = $url ? $url : '#';

					$form_html .= '<form ACTION="'.$url.'" METHOD="POST">';
					$form_html .= '<input type="hidden" name="forum_action" value="wps_forum_search" />';

					$style = $style == '' ? ' style="margin-right:10px;"' : ' style="'.$style.'"';
					$form_html .= '<input type="text" id="wps_forum_search_entry" name="wps_forum_search_entry"'.$style.' placeholder="'.$placeholder.'" value="'.$value.'" />';

					$form_html .= '<input type="submit" id="wps_forum_search_submit" name="wps_forum_search_submit" class="wps_submit '.$class.'" value="'.$label.'" />';

					$form_html .= '</form>';

				$form_html .= '</div>';

				$html .= $form_html;

			else:

				$html .= $private_msg;

			endif;

			if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

		endif;

	endif;

	return $html;

}

function wps_forum_search_results($atts) {

	// Init
	add_action('wp_footer', 'wps_forum_search_init');

    global $current_user;
    
	$html = '';

	if (isset($_POST['forum_action']) && $_POST['forum_action'] == 'wps_forum_search'):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'private' => 0,
			'private_msg' => __('You need to login first.', WPS2_TEXT_DOMAIN),
			'label_results' => __('Showing results for "%s":', WPS2_TEXT_DOMAIN),
			'no_results' => __('No results.', WPS2_TEXT_DOMAIN),
			'show_forum' => __('in %s', WPS2_TEXT_DOMAIN),
			'show_snippet' => 1,
			'snippet_length' => 200,
			'show_date' => 1,
			'status' => 'all',
			'date_format' => __('%s ago', WPS2_TEXT_DOMAIN),
			'max' => 100,
			'before' => '',
			'after' => '',
		), $atts, 'wps_forum_search' ) );

		if (!$private || is_user_logged_in()):

			$value = isset($_POST['wps_forum_search_entry']) ? $_POST['wps_forum_search_entry'] : '';

			if ($value = stripslashes($_POST['wps_forum_search_entry'])):

				$html .= '<h2 class="wps_forum_search_label_results">';
				$html .= sprintf($label_results, $value);
				$html .= '</h2>';

				$forum_posts = array();
				global $wpdb;

				// Get posts
				$comment_status = ($status == 'open' || $status == 'closed') ? "AND comment_status = '".$status."' " : '';

				$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_forum_post' ".$comment_status."AND post_status='publish' AND (post_title LIKE %s OR post_content LIKE %s)";
				$term = $_POST['wps_forum_search_entry'];
				$the_posts = $wpdb->get_results($wpdb->prepare($sql, '%'.$term.'%', '%'.$term.'%'));

				if ($the_posts):
					foreach ($the_posts as $the_post):

						$forum_post = array();
						$forum_post['ID'] = $the_post->ID;
						$forum_post['post_status'] = $the_post->post_status;
						$forum_post['post_author'] = $the_post->post_author;
						$forum_post['post_name'] = $the_post->post_name;
						$forum_post['post_title'] = $the_post->post_title;
						$forum_post['post_content'] = $the_post->post_content;
						$forum_post['post_date'] = $the_post->post_date;
						$forum_post['weight'] = 10;

						$forum_posts['p_'.$the_post->ID] = $forum_post;						

					endforeach;
				endif;

				// Get comments
				$sql = "SELECT * FROM ".$wpdb->prefix."comments WHERE comment_approved = 1 AND comment_content LIKE %s";
				$comments = $wpdb->get_results($wpdb->prepare($sql, '%'.addslashes($value).'%'));	

				if ($comments):
					foreach ($comments as $comment):

                        $private = get_comment_meta( $comment->comment_ID, 'wps_private_post', true );
                        $comment_post = get_post($comment->comment_post_ID);
                        if (!$private || $current_user->ID == $comment_post->post_author || $comment->user_id == $current_user->ID || current_user_can('manage_options')):
    
                            // Get parent post
                            if ($comment_post->post_type == 'wps_forum_post' && $comment_post->post_status == 'publish'):

                                $post_terms = get_the_terms( $comment_post->ID, 'wps_forum' );
                                if( $post_terms && !is_wp_error( $post_terms ) ):
                                    foreach( $post_terms as $term ):
                                        $public = wps_get_term_meta($term->term_id, 'wps_forum_public', true);
                                        if ($public || is_user_logged_in()):

                                            $original_post = get_post($comment->comment_post_ID);

                                            if ( ($status == 'all') || ($original_post->comment_status == $status) ):

                                                $forum_post = array();
                                                $forum_post['ID'] = $comment_post->ID;
                                                $comment_status = ($comment->comment_approved == 1) ? 'publish' : 'pending';
                                                $forum_post['post_status'] = $comment_status;
                                                $forum_post['post_author'] = $comment->user_id;
                                                $forum_post['post_content'] = $comment->comment_content;
                                                $forum_post['post_name'] = $original_post->post_name;
                                                $forum_post['post_title'] = $original_post->post_title;
                                                $forum_post['post_date'] = $comment->comment_date;
                                                $forum_post['weight'] = 5;

                                                $forum_posts['c_'.$comment_post->ID] = $forum_post;						

                                            endif;

                                        endif;

                                    endforeach;
                                endif;

                            endif;
    
                        endif;

					endforeach;

				endif;				

				if ($forum_posts):

					// Sort the posts by weight first, then last added
					$sort = array();
					foreach($forum_posts as $k=>$v) {
					    $sort['weight'][$k] = $v['weight'];
					    $sort['post_date'][$k] = $v['post_date'];
					}
					array_multisort($sort['weight'], SORT_DESC, $sort['post_date'], SORT_DESC, $forum_posts);

					// Show results
					$html .= '<div class="wps_forum_search_posts">';

						$c = 0;
						$previous_title = '';

						foreach ($forum_posts as $forum_post):

							if ($forum_post['post_status'] == 'publish' || current_user_can('edit_posts') || $forum_post['post_author'] = $current_user->ID):

								$forum_html = '';
								$post_terms = false;
								$user_can_see = true;
								global $current_user;

								if ($previous_title != esc_attr($forum_post['post_title'])):

									$forum_html .= '<div class="wps_forum_search_title">';
										$post_terms = get_the_terms( $forum_post['ID'], 'wps_forum' );
										if( $post_terms && !is_wp_error( $post_terms ) ):
										    foreach( $post_terms as $term ):

										    	if (user_can_see_forum($current_user->ID, $term->term_id)):

										    		if (user_can_see_post($current_user->ID, $forum_post['ID'])):

														if ( wps_using_permalinks() ):
															$url = '/'.$term->slug.'/'.$forum_post['post_name'];
														else:
															$forum_page_id = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
															$url = "/?page_id=".$forum_page_id."&topic=".$forum_post['post_name'];
														endif;

														$the_title = esc_attr($forum_post['post_title']);
														$the_title = str_replace(strtolower($value), '<span class="wps_forum_search_highlight">'.strtolower($value).'</span>', strtolower($the_title));
														$forum_html .= '<div class="wps_forum_search_title">';
														$forum_html .= '<a href="'.$url.'">'.$the_title.'</a>';
															if ($forum_post['post_status'] == 'closed') $forum_html .= '['.$closed_prefix.'] ';
															if ($show_forum):
																$post_terms = get_the_terms( $forum_post['ID'], 'wps_forum' );
																if ( $post_terms && !is_wp_error( $post_terms ) ):
																	foreach( $post_terms as $term ):

																		$page_id = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
																		if ( wps_using_permalinks() ):
																			if (!is_multisite()):
																				$url = get_permalink($page_id);
																				$forum_html .= ' '.sprintf($show_forum, '<a href="'.$url.'">'.$term->name.'</a>');
																			else:
																				$blog_details = get_blog_details($blog->blog_id);
																				$url = $blog_details->path.$slug.'/'.$forum_post['post_name'];
																				$forum_html .= ' '.sprintf($show_forum, '<a href="'.$url.'">'.$term->name.'</a>');
																			endif;
																		else:
																			if (!is_multisite()):
																				$forum_html .= ' '.sprintf($show_forum, '<a href="'.get_bloginfo('url')."/?page_id=".$page_id.'">'.$term->name.'</a>');
																			else:
																				$blog_details = get_blog_details($blog->blog_id);
																				$url = $blog_details->path."?page_id=".$page_id;
																				$forum_html .= ' '.sprintf($show_forum, '<a href="'.$url.'">'.$term->name.'</a>');
																			endif;
																		endif;

																	endforeach;
																endif;
															endif;
														$forum_html .= '</div>';

													else:

														$user_can_see = false;

													endif;

												else:

													$user_can_see = false;

												endif;

										    endforeach;

										else:

											$user_can_see = false;

										endif;
									$forum_html .= '</div>';

									$previous_title = esc_attr($forum_post['post_title']);

								endif;

								if ($post_terms && $user_can_see):
									if ($show_date):
										$forum_html .= '<div class="wps_forum_search_date">';
											$forum_html .= sprintf($date_format, human_time_diff(strtotime($forum_post['post_date']), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
										$forum_html .= '</div>';										
									endif;

									if ($show_snippet):
										$forum_html .= '<div class="wps_forum_search_snippet">';
											$snippet_text = strip_tags($forum_post['post_content']);
											if (strlen($snippet_text) > $snippet_length) $snippet_text = substr($snippet_text, 0, $snippet_length).' ...';
											$snippet_text = str_replace(strtolower($value), '<span class="wps_forum_search_highlight">'.strtolower($value).'</span>', strtolower($snippet_text));
											$forum_html .= $snippet_text;
										$forum_html .= '</div>';
									endif;

								endif;
								$html .= $forum_html;

								if ($user_can_see):
									$forum_html = '<div class="wps_forum_search_post">'.$forum_html.'</div>';
									$forum_html = apply_filters( 'wps_forum_search_post_item', $forum_html );
									$c++;
									if ($c == $max) { break; };
								endif;

							endif;

						endforeach;

					$html .= '</div>';

					if ($c == 0) { $html .= $no_results; };

				else:

					$html .= $no_results;

				endif;


			endif;

		else:

			$html .= $private_msg;

		endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;	

}


if (!is_admin()) add_shortcode(WPS_PREFIX.'-forum-search', 'wps_forum_search');
if (!is_admin()) add_shortcode(WPS_PREFIX.'-forum-search-results', 'wps_forum_search_results');



?>
