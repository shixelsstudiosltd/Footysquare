<?php
																	/* **** */
																	/* INIT */
																	/* **** */

function wps_show_posts_init() {
	// CSS
	wp_enqueue_style('wps-show-posts-css', plugins_url('wps_show_posts.css', __FILE__), 'css');
	// Anything else?
	do_action('wps_show_posts_init_hook');
}
																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_show_site_posts($atts) {

	// Init
	add_action('wp_footer', 'wps_show_posts_init');

	$html = '';

	global $current_user;
	$user_id = is_user_logged_in() ? $current_user->ID : 'all';

	// Shortcode parameters
	extract( shortcode_atts( array(
		'user_id' => $user_id, // ID, all or user, defaults to current user ID if logged in, otherwise 0
		'table' => 0,
		'logged_in_only' => 0, // set to 1 to only show if logged in
		'type' => 'post', // Alternative's to post available if used with WP Symposium Pro (www.wpsymposiumpro.com)
		'post_status' => 'publish', // publish|pending
		'comment_status' => 'all', // all|open|closed
		'closed_prefix' => __('closed', WPS2_TEXT_DOMAIN),
		'count' => 10,
		'link' => 1,
		'show_image' => 1,
		'show_title' => 1,
		'image_size' => 64, // thumbnai shown as square
		'word_count' => 10,
		'orderby' => 'ID', // field or rand
		'order' => 'DESC',
		'show_author' => 1,
		'author_format' => __('By %s', WPS2_TEXT_DOMAIN),
		'author_link' => 1,
		'show_date' => 1,
		'show_date_format' => '%s ago',
		'convert_bbcode' => 1,
		'convert_smilies' => 1,
		'convert_links' => 1,
		'before' => '',
		'after' => '',
	), $atts, 'wps_show_posts' ) );

	if (!$logged_in_only || is_user_logged_in()):

		if ($user_id == 'user') $user_id = wps_get_user_id();

		global $wpdb;
		if ($user_id != 'all'):
			// one user or this user
			if ($orderby != 'rand'):
				$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_author = %d AND post_type = %s AND post_status = %s ORDER BY ".$orderby." ".$order." LIMIT 0,%d";
				$the_posts = $wpdb->get_results($wpdb->prepare($sql, $user_id, $type, $post_status, $count));
			else:
				$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_author = %d AND post_type = %s AND post_status = %s";
				$the_posts = $wpdb->get_results($wpdb->prepare($sql, $user_id, $type, $post_status));
				uksort($the_posts, "wps_show_posts_rand_cmp");
				$the_posts = array_slice($the_posts, 0, $count);
			endif;
		else:
			// all users
			if ($orderby != 'rand'):
				$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = %s AND post_status = %s ORDER BY ".$orderby." ".$order." LIMIT 0,%d";
				$the_posts = $wpdb->get_results($wpdb->prepare($sql, $type, $post_status, $count));
			else:
				$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = %s AND post_status = %s";
				$the_posts = $wpdb->get_results($wpdb->prepare($sql, $type, $post_status));
				uksort($the_posts, "wps_show_posts_rand_cmp");
				$the_posts = array_slice($the_posts, 0, $count);
			endif;
		endif;

		if ( ! empty( $the_posts ) ) {

			$post_id_for_url = 0;
			$c=0;
			$parameter = '';
			if ($type == "wps_activity"):
				$post_id_for_url = get_option('wpspro_profile_page');
				$parameter = 'view';
			endif;
			if ($type == "wps_mail"):
				$sql = "SELECT ID FROM ".$wpdb->prefix."posts WHERE (post_content LIKE '%%[wps-mail %%' OR post_content LIKE '%%[wps-mail]%%') AND post_type = 'page' AND post_status = %s;";
				if ($pages = $wpdb->get_results($wpdb->prepare($sql, 'publish'))):
					$page = $pages[0];
					$post_id_for_url = $page->ID;
					$parameter = 'mail';
				endif;
			endif;
			foreach ( $the_posts as $the_post ) {

				if ($comment_status == 'all' || $comment_status == $the_post->comment_status):

					$user_can_see = true;
					// If forum post, need permalink for this post's forum and check can see this forum
					if ($type == "wps_forum_post"):

						$post_terms = get_the_terms( $the_post->ID, 'wps_forum' );
						if( $post_terms && !is_wp_error( $post_terms ) ):
						    foreach( $post_terms as $term ):

						    	if (user_can_see_forum($current_user->ID, $term->term_id)):

						    		if (user_can_see_post($current_user->ID, $the_post->ID)):

										if ( wps_using_permalinks() ):
											$url = '/'.$term->slug.'/'.$the_post->post_name;
										else:
											$forum_page_id = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
											$url = "/?page_id=".$forum_page_id."&topic=".$forum_post['post_name'];
										endif;

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

						$sql = "SELECT ID FROM ".$wpdb->prefix."posts WHERE (post_content LIKE '%%[wps-forum%%' OR post_content LIKE '%%[wps-forum]%%') AND post_type = 'page' AND post_status = %s;";
						if ($pages = $wpdb->get_results($wpdb->prepare($sql, 'publish'))):
							$page = $pages[0];
							$post_id_for_url = $page->ID;
						endif;

					else:

						// Not a forum post
						if (!$post_id_for_url):
							$url = get_permalink($the_post->ID);
						else:
							$url = get_permalink($post_id_for_url);				
							$url .= wps_query_mark($url).$parameter.'='.$the_post->ID;
						endif;

					endif;

					if ($user_can_see):

						$title = $the_post->post_title;
						$title = str_replace(
						  array('[', ']'), 
						  array('&#91;', '&#93;'), 
						  $title
						);											
						if ($comment_status == 'all' && $the_post->comment_status == 'closed' && $closed_prefix) $title = '['.$closed_prefix.'] '.$title;

						if ($show_image):
							$the_thumbnail = has_post_thumbnail($the_post->ID) ? get_the_post_thumbnail($the_post->ID, array($image_size,$image_size)) : false;
							$the_image_size = $image_size;
							$image_size_padding = 10;
						else:
							$the_image_size = 0;
							$image_size_padding = 0;
						endif;

						$html .= '<div class="wps_show_posts_item" style="padding-left:'.($the_image_size+$image_size_padding).'px; overflow:hidden;">';

							if ($show_image):
								$html .= '<div class="wps_show_posts_item_image" style="width:'.$the_image_size.'px; margin-left:-'.$the_image_size.'px;">';
								if ( $the_thumbnail )
								    $html .= '<a href="'.get_permalink($the_post->ID).'">'.$the_thumbnail.'</a>';
								$html .= '</div>';
							endif;

							if ($show_image) $html .= '<div class="wps_show_posts_item_image_other" style="padding-left: '.$image_size_padding.'px;">';

							if ($show_title):
								if ($table) $html .= '<div class="wps_show_posts_item_title">';
								if ($link):
									$html .= '<div class="wps_show_post_title"><a href="'.$url.'">'.$title.'</a></div>';
								else:
									$html .= '<div class="wps_show_post_title">'.$title.'</div>';
								endif;
								if ($table) $html .= '</div>';
							endif;

							if ($show_date):
								if ($table) $html .= '<div class="wps_show_posts_item_date">';
								$html .= '<div class="wps_show_post_date">'.sprintf($show_date_format, human_time_diff(strtotime($the_post->post_date), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN).'</div>';
								if ($table) $html .= '</div>';
							endif;
	
							if ($show_author):
								if ($table) $html .= '<div class="wps_show_posts_item_author">';
								$html .= '<div class="wps_show_post_author">';
									if (function_exists('wps_display_name')):
										$html .= sprintf($author_format, wps_display_name(array('user_id'=>$the_post->post_author, 'link'=>$author_link)));
									else:
										$author_obj = get_user_by('id', $the_post->post_author);
										$html .= sprintf($author_format, $author_obj->display_name);
									endif;
								$html .= '</div>';										
								if ($table) $html .= '</div>';
							endif;

							if ($word_count):
								if ($table) $html .= '<div class="wps_show_posts_item_content">';
								$content = strip_tags($the_post->post_content);
								$content = str_replace(
								  array('[', ']'), 
								  array('&#91;', '&#93;'), 
								  $content
								);											
						        $words = explode(' ', $content, $word_count + 1);
						        if (count($words) > $word_count) {
						            array_pop($words);
						            array_push($words, '...');
						        }													
					            $words = implode(' ', $words);
								if ($convert_links) $words = make_clickable($words);
								if ($convert_smilies) $words = convert_smilies($words);
								if (function_exists('wps_bbcode_replace') && $convert_bbcode) $words = wps_bbcode_replace($words);
								$html .= '<div class="wps_show_post_content">'.$words.'</div>';
								if ($table) $html .= '</div>';
							endif;

							if ($show_image) $html .= '</div>';

						$html .= '</div>';

						$c++;
						if ($c == $count) break;

					endif;

				endif;

			}
		}


		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	endif;

	return $html;

}

// Function used to sort randomly
function wps_show_posts_rand_cmp($a, $b){
    return rand() > rand();
}


if (!is_admin()) add_shortcode(WPS_PREFIX.'-show-posts', 'wps_show_site_posts');




?>
