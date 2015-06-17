<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_events_init() {
	// JS and CSS
	wp_enqueue_style('wps-events-css', plugins_url('wps_events.css', __FILE__), 'css');
	// Anything else?
	do_action('wps_events_init_hook');
}

																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */


function wps_events($atts) {

	// Init
	add_action('wp_footer', 'wps_events_init');

	global $post;
	$html = '';
	global $current_user, $wpdb;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'count' => 100,
		'avatar_size' => 64,
		'date_format' => __('%s ago', WPS2_TEXT_DOMAIN),		
		'label_noevents' => __('No events.', WPS2_TEXT_DOMAIN),
		'format' => 'grid',
		'suffix' => 1,
		'class' => '',
		'before' => '',
		'after' => '',
	), $atts, 'wps_events' ) );

	global $current_user;
	$user_id = $current_user->ID;

	// Check for single post view
	if (isset($_GET['event'])):
		$post_id = $_GET['event'];
	else:
		$post_id = false;
	endif;

	if (!$post_id):

		// Show all items
		require_once('wps_event_list.php');

	else:

		// Show individual item
		require_once('wps_event_single.php');

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_events_backto($atts) {

	// Init
	add_action('wp_footer', 'wps_events_init');

	$html = '';

	if ( isset($_GET['event']) ): // showing a single post

		// Shortcode parameters
		extract( shortcode_atts( array(
			'label' => __('Back to events...', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_events_backto' ) );

		$url = wps_curPageURL();
		$url = preg_replace("/[&?]event=[0-9]+/","",$url);
		$html .= '<a href="'.$url.'">'.$label.'</a>';

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;

}


if (!is_admin()) {
	add_shortcode(WPS_PREFIX.'-events', 'wps_events');
	add_shortcode(WPS_PREFIX.'-events-backto', 'wps_events_backto');
}



?>
