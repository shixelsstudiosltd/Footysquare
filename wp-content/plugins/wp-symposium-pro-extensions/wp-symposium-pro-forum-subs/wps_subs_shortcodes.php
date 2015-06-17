<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_subs_init() {
	// JS and CSS
	wp_enqueue_script('wps-subs-js', plugins_url('wps_subs.js', __FILE__), array('jquery'));
	wp_localize_script('wps-subs-js', 'wps_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));		
	wp_enqueue_style('wps-subs-css', plugins_url('wps_subs.css', __FILE__), 'css');
}
																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */


function wps_manage_subscriptions($atts) {

    // Init
    add_action('wp_footer', 'wps_subs_init');

	$html = '';

	global $current_user;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'forums_label' => __('Forums', WPS2_TEXT_DOMAIN),
		'posts_label' => __('Posts', WPS2_TEXT_DOMAIN),
		'unsubscribe' => __('unsubscribe', WPS2_TEXT_DOMAIN),
		'subs_unsubscribe' => __('Cancel all forum subscriptions', WPS2_TEXT_DOMAIN),
		'subs_unsubscribe_msg' => __('You will no longer receive any forum email alerts.', WPS2_TEXT_DOMAIN),
		'status' => 'open',
		'closed_prefix' => __('closed', WPS2_TEXT_DOMAIN),
		'private_msg' => '',
		'before' => '',
		'after' => '',
	), $atts, 'wps_manage_subscriptions' ) );

	if (is_user_logged_in()):

		$has_subs = false;

		// ===== FORUMS

		$args = array (
			'post_type'              => 'wps_forum_subs',
			'posts_per_page'         => -1,
			'author'			 	 => $current_user->ID,
		);

		$subs = get_posts($args);
		if ($subs):

			$has_subs = true;

			$html .= '<h2>'.$forums_label.'</h2>';
			$html .= '<ul>';
			foreach ($subs as $sub):

				$forum_id = get_post_meta($sub->ID, 'wps_forum_id', true);
				$term = get_term_by('id', $forum_id, 'wps_forum');
				if ($term):

					$html .= '<li>';
						$forum_page = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
						$forum_url = get_permalink($forum_page);
						$html .= '<a href="'.$forum_url.'">'.$term->name.'</a>';
						$html .= ' [<a rel="'.$sub->ID.'" class="wps_manage_subs_forum_unsubscribe" href="javascript:void(0)">'.$unsubscribe.'</a>]';
					$html .= '</li>';

				endif;

			endforeach;
			$html .= '</ul>';

		endif;


		// ===== POSTS

		$args = array (
			'post_type'         => 'wps_subs',
			'posts_per_page'    => -1,
			'author'		 	=> $current_user->ID,
		);

		$subs = get_posts($args);
		if ($subs):

			$has_subs = true;

			$html .= '<h2>'.$posts_label.'</h2>';
			$html .= '<ul id="wps_manage_subs_post_all">';
			foreach ($subs as $sub):

				$the_post = get_post($sub->wps_post_id);

					if ($the_post && ($the_post->comment_status == $status || $status == 'all')):

					$html .= '<li>';

						$post_terms = get_the_terms( $the_post->ID, 'wps_forum' );
						if( $post_terms && !is_wp_error( $post_terms ) ):
						    foreach( $post_terms as $term ):

								if ( wps_using_permalinks() ):
									$url = '/'.$term->slug.'/'.$the_post->post_name;
								else:
									$forum_page_id = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
									$url = "/?page_id=".$forum_page_id."&post=".$the_post->post_name;
								endif;

								$the_title = esc_attr($the_post->post_title);
								$the_title = '<a href="'.$url.'">'.$the_title.'</a>';
								if ($the_post->comment_status == 'closed' && $closed_prefix) $the_title = '['.$closed_prefix.'] '.$the_title;
								$html .= $the_title;

						    endforeach;
						endif;

						$html .= ' [<a rel="'.$sub->ID.'" class="wps_manage_subs_post_unsubscribe" href="javascript:void(0)">'.$unsubscribe.'</a>]';
					$html .= '</li>';

				endif;

			endforeach;
			$html .= '</ul>';

		endif;

		if ($has_subs):

			$html .= '<a id="wps_manage_subs_post_unsubscribe_all" href="javascript:void(0)">'.$subs_unsubscribe.'</a>';

		endif;


	else:

		$html .= $private_msg;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;

}

function wps_subscribe_post($atts) {

	// Init
	add_action('wp_footer', 'wps_subs_init');

	$html = '';

	if (is_user_logged_in() && !isset($_GET['forum_action'])):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'style' => 'link',
			'class' => '',
			'subscribe' => __('Get notified via email when new comments are posted', WPS2_TEXT_DOMAIN),
			'unsubscribe' => __('Stop getting notifications for new comments', WPS2_TEXT_DOMAIN),
			'subscribed_msg' => __('You are now subscribed.', WPS2_TEXT_DOMAIN),
			'unsubscribed_msg' => __('You have unsubscribed.', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_subscribe_post' ) );

		$post_slug = get_query_var('topic');

		if ($post_slug):

			$args=array(
				'name' => $post_slug,
				'post_type' => 'wps_forum_post',
				'post_status' => 'publish',
				'posts_per_page' => 1
			);
			$wps_posts = get_posts($args);

			if ($wps_posts):

				$the_post = $wps_posts[0];

				global $current_user;
				if (user_can_see_post($current_user->ID, $the_post->ID)):

					global $current_user;
					$args = array (
						'post_type'			=> 'wps_subs',
						'posts_per_page'	=> -1,
						'author'			=> $current_user->ID,
					);

					$subs = get_posts( $args );
					$subscribed = false;
					if ($subs):
						foreach ($subs as $sub):
							$wps_post_id = get_post_meta ( $sub->ID, 'wps_post_id', true );
							if ((int)$wps_post_id == (int)$the_post->ID) $subscribed = $sub->ID;
						endforeach;
					endif;

					$html .= '<div style="display:none" id="wps_subscribed_msg">'.$subscribed_msg.'</div>';
					$html .= '<div style="display:none" id="wps_unsubscribed_msg">'.$unsubscribed_msg.'</div>';
					$html .= '<div style="display:none" id="wps_subs_plugins_url">'.plugins_url( '', __FILE__ ).'</div>';
					$html .= '<div style="display:none" id="wps_add_subscribe_unsubscribe_id">'.$subscribed.'</div>';
					$html .= '<div style="display:none" id="wps_add_subscribe_subscribe_post_id">'.$the_post->ID.'</div>';

					if ($subscribed):
						$html .= '<div style="display:none" id="wps_add_subscribe_unsubscribe_action">wps_ajax_subs_unsubscribe</div>';
					else:
						$html .= '<div style="display:none" id="wps_add_subscribe_unsubscribe_action">wps_ajax_subs_subscribe</div>';
					endif;							

					$html .= '<div id="wps_add_subscribe_unsubscribe_button">';

						if ($style == 'button'):
							if ($subscribed):
								$html .= '<input type="submit" class="wps_submit '.$class.'" value="'.$unsubscribe.'" />';
							else:
								$html .= '<input type="submit" class="wps_submit '.$class.'" value="'.$subscribe.'" />';
							endif;
						else:
							if ($subscribed):
								$html .= '<a href="javascript:void(0);">'.$unsubscribe.'</a>';
							else:
								$html .= '<a href="javascript:void(0);">'.$subscribe.'</a>';
							endif;
						endif;

					$html .= '</div>';

				endif;

			else:

				$html .= 'Sorry, couldn\'t find the post slug';

			endif;

		endif;

	endif;

	if ($html) htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;

}

function wps_subscribe_forum($atts) {

	// Init
	add_action('wp_footer', 'wps_subs_init');

	$html = '';

	global $current_user;
	if (is_user_logged_in()):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'slug' => '',
			'style' => 'link',
			'class' => '',
			'subscribe' => __('Get notified via email when new posts are added', WPS2_TEXT_DOMAIN),
			'unsubscribe' => __('Stop getting notifications of new posts', WPS2_TEXT_DOMAIN),
			'subscribed_msg' => __('You are now subscribed.', WPS2_TEXT_DOMAIN),
			'unsubscribed_msg' => __('You have unsubscribed.', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_subscribe_forum' ) );

		if (!get_query_var('topic')):

			if (!$slug):

				$html .= __('Please add slug="xxx" to the [wps-subscribe-forum] shortcode, where xxx is the slug of the forum.', WPS2_TEXT_DOMAIN);

			else:

				$term = get_term_by('slug', $slug, 'wps_forum');

				if (user_can_see_forum($current_user->ID, $term->term_id) || current_user_can('manage_options')):

					$args = array (
						'post_type'             => 'wps_forum_subs',
						'posts_per_page'        => -1,
						'author'			 	=> $current_user->ID,
					);

					$subs = get_posts( $args );

					$subscribed = false;
					if ($subs):
						foreach ($subs as $sub):
							$wps_forum_id = get_post_meta ( $sub->ID, 'wps_forum_id', true );
							if ((int)$wps_forum_id == (int)$term->term_id) $subscribed = $sub->ID;
						endforeach;
					endif;

					$html .= '<div style="display:none" id="wps_forum_subscribed_msg">'.$subscribed_msg.'</div>';
					$html .= '<div style="display:none" id="wps_forum_unsubscribed_msg">'.$unsubscribed_msg.'</div>';
					$html .= '<div style="display:none" id="wps_subs_plugins_url">'.plugins_url( '', __FILE__ ).'</div>';
					$html .= '<div style="display:none" id="wps_add_forum_subscribe_unsubscribe_id">'.$subscribed.'</div>';
					$html .= '<div style="display:none" id="wps_add_forum_subscribe_subscribe_forum_id">'.$term->term_id.'</div>';

					if ($subscribed):
						$html .= '<div style="display:none" id="wps_add_forum_subscribe_unsubscribe_action">wps_ajax_subs_forum_unsubscribe</div>';
					else:
						$html .= '<div style="display:none" id="wps_add_forum_subscribe_unsubscribe_action">wps_ajax_subs_forum_subscribe</div>';
					endif;							

					$html .= '<div id="wps_add_forum_subscribe_unsubscribe_button">';

						if ($style == 'button'):
							if ($subscribed):
								$html .= '<input type="submit" class="wps_submit '.$class.'" value="'.$unsubscribe.'" />';
							else:
								$html .= '<input type="submit" class="wps_submit '.$class.'" value="'.$subscribe.'" />';
							endif;
						else:
							if ($subscribed):
								$html .= '<a href="javascript:void(0);">'.$unsubscribe.'</a>';
							else:
								$html .= '<a href="javascript:void(0);">'.$subscribe.'</a>';
							endif;
						endif;

					$html .= '</div>';

				endif;

			endif;

		endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;

}

if (!is_admin()) add_shortcode(WPS_PREFIX.'-subscribe-forum', 'wps_subscribe_forum');
if (!is_admin()) add_shortcode(WPS_PREFIX.'-subscribe-post', 'wps_subscribe_post');
if (!is_admin()) add_shortcode(WPS_PREFIX.'-manage-subscriptions', 'wps_manage_subscriptions');




?>
