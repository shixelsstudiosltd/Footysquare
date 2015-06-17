<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_calendar_init() {
	// JS and CSS
	wp_enqueue_style('wps-calendar-css', plugins_url('wps_calendar.css', __FILE__), 'css');
	// Anything else?
	do_action('wps_calendar_init_hook');
}

																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */


function wps_calendar_post($atts) {

	// Init
	add_action('wp_footer', 'wps_calendar_init');

    $html = '';
    
	if (!isset($_GET['event']) || isset($_GET['event_action'])): // Don't show on single event

		// Shortcode parameters
		extract( shortcode_atts( array(
			'slug' => '',
			'class' => '',
			'show' => 0,
			'label' => __('Add Event', WPS2_TEXT_DOMAIN),
			'update_label' => __('Update Event', WPS2_TEXT_DOMAIN),
			'title_label' => __('Event Title', WPS2_TEXT_DOMAIN),
			'content_label' => __('Event Description', WPS2_TEXT_DOMAIN),
			'start_date_label' => __('Start Date', WPS2_TEXT_DOMAIN),
			'start_time_label' => __('Start Time', WPS2_TEXT_DOMAIN),
			'end_date_label' => __('End Date', WPS2_TEXT_DOMAIN),
			'end_time_label' => __('End Time', WPS2_TEXT_DOMAIN),
			'image_label' => __('Event Image', WPS2_TEXT_DOMAIN),
			'comments_label' => __('Event comments', WPS2_TEXT_DOMAIN),
			'comments_allowed_label' => __('Allow comments', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_calendar_post' ) );

		if ($slug == ''):

			$html .= '<div class="wps_error">'.__('Please add slug="xxx" to the shortcode, where xxx is the slug of the calendar.', WPS2_TEXT_DOMAIN).'</div>';

		else:

			if (is_user_logged_in()):

				$calendar = get_page_by_path($slug, OBJECT, 'wps_calendar');

				if ($calendar):
    
                    $saved_roles = get_post_meta( $calendar->ID, 'wps_calendar_roles', true);

                    $continue = false;
                    if ($saved_roles):
                        foreach ( $saved_roles as $role => $name ) :
                            if (current_user_can($name)) $continue = true;
                        endforeach;
                    else:
                        if (current_user_can('manage_options')) $continue = true;
                    endif;

                    if ($continue):

                        global $current_user;
                        $form_html = '<div id="wps_calendar_post_div">';

                            $form_html .= '<div id="wps_calendar_post_form"';
                                if (!$show && !isset($_GET['event_action'])) $form_html .= ' style="display:none;"';
                                $form_html .= '>';

                                $event = false;
                                if (isset($_GET['event_action'])) $event = get_post($_GET['event_id']);
                                $post_title = $event ? $event->post_title : '';
                                $post_content = $event ? $event->post_content : '';
                                $wps_event_start = $event ? get_post_meta($event->ID, 'wps_event_start', true) : '';
                                $wps_event_start_time = $event ? get_post_meta($event->ID, 'wps_event_start_time', true) : '';
                                $wps_event_end = $event ? get_post_meta($event->ID, 'wps_event_end', true) : '';
                                $wps_event_end_time = $event ? get_post_meta($event->ID, 'wps_event_end_time', true) : '';

                                $form_html .= '<form enctype="multipart/form-data" id="wps_event_theuploadform">';
                                $form_html .= '<input type="hidden" id="wps_calendar_plugins_url" value="'.plugins_url( '', __FILE__ ).'" />';
                                $form_html .= '<input type="hidden" name="wps_calendar_post_author" value="'.$current_user->ID.'" />';
                                $form_html .= '<input type="hidden" name="action" value="wps_calendar_post_add" />';
                                $form_html .= '<input type="hidden" name="calendar_id" value="'.$calendar->ID.'" />';
                                $url = wps_curPageURL();
                                $url = preg_replace("/[&?]event_action=edit+/","",$url);
                                $form_html .= '<input type="hidden" id="calendar_edit_redirect_url" value="'.$url.'" />';
                                if ($event)
                                    $form_html .= '<input type="hidden" name="event_id" value="'.$event->ID.'" />';

                                $form_html = apply_filters( 'wps_calendar_post_pre_form_filter', $form_html, $atts, $current_user->ID );

                                $form_html .= '<div class="wps_event_label">'.$title_label.'</div>';
                                $form_html .= '<input type="text" id="wps_calendar_title" name="wps_calendar_title" autocomplete="off" value="'.$post_title.'" />';
                                $form_html .= '<div class="wps_event_label">'.$content_label.'</div>';
                                $form_html .= '<textarea id="wps_calendar_post" name="wps_calendar_post" autocomplete="off">'.$post_content.'</textarea>';

                                $form_html .= '<div class="wps_calendar_edit_column">';

                                    $form_html .= '<div class="wps_event_label">'.$start_date_label.'</div>';
                                    $form_html .= '<input id="wps_event_start" name="wps_event_start" style="width:100px;" value="'.$wps_event_start.'" type="text" /><br />';

                                    $form_html .= '<div class="wps_event_label">'.$start_time_label.'</div>';
                                    $form_html .= '<input id="wps_event_start_time" name="wps_event_start_time" style="width:100px" value="'.$wps_event_start_time.'" type="text" /><br />';

                                    $form_html .= '<input id="wps_event_comments" name="wps_event_comments" style="margin-top:17px;margin-bottom:16px;" type="checkbox"';
                                        if ($event && $event->comment_status == 'open') $form_html .= ' CHECKED';
                                        $form_html .= '> '.$comments_allowed_label;

                                $form_html .= '</div>';
                                $form_html .= '<div class="wps_calendar_edit_column">';

                                    $form_html .= '<div class="wps_event_label">'.$end_date_label.'</div>';
                                    $form_html .= '<input id="wps_event_end" name="wps_event_end" style="width:100px"value="'.$wps_event_end.'" type="text" /><br />';

                                    $form_html .= '<div class="wps_event_label">'.$end_time_label.'</div>';
                                    $form_html .= '<input id="wps_event_end_time" name="wps_event_end_time" style="width:100px" value="'.$wps_event_end_time.'" type="text" />';

                                $form_html .= '</div>';

                                $form_html .= '<div class="wps_calendar_edit_column">';

                                    $form_html .= '<div class="wps_event_label">'.$image_label.'</div>';
                                    if ($event && has_post_thumbnail($event->ID)):
                                        $feat_image = wp_get_attachment_url( get_post_thumbnail_id($event->ID) );
                                        $form_html .= '<img style="width:100px;" src="'.$feat_image.'" /><br />';
                                    endif;
                                    $form_html .= '<input title="'.$image_label.'" id="wps_event_image" name="wps_event_image" size="50" type="file" />';

                                $form_html .= '</div>';

                                $form_html .= '</form>';

                            $form_html .= '</div>';

                            $form_html .= '<div style="clear:both; margin-top:30px;">';
                                $label = $event ? $update_label : $label;
                                $form_html .= '<input id="wps_calendar_post_button" type="submit" class="wps_submit '.$class.'" value="'.$label.'" />';
                            $form_html .= '</div>';

                        $form_html .= '</div>';

                        $html .= $form_html;

                    endif;

                    if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);
    
                endif;

			endif;

		endif;

	endif;

	return $html;

}

function wps_calendar($atts) {

	// Init
	add_action('wp_footer', 'wps_calendar_init');

	global $post;
	$html = '';
	global $current_user, $wpdb;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'slug' => '',
		'days' => __('Sun,Mon,Tue,Wed,Thu,Fri,Sat', WPS2_TEXT_DOMAIN),
		'months' => __('January,February,March,April,May,June,July,August,September,October,November,December', WPS2_TEXT_DOMAIN),
		'suffix' => __('st,nd,rd,th,th,th,th,th,th,th,th,th,th,th,th,th,th,th,th,th,st,nd,rd,th,th,th,th,th,th,th,st', WPS2_TEXT_DOMAIN),
		'private_msg' => __('You do not have permission to view this calendar.', WPS2_TEXT_DOMAIN),
        'left' => '&laquo;',
        'right' => '&raquo;',
        'today' => __('Today', WPS2_TEXT_DOMAIN),
        'thumbnails' => true,
        'titles' => true,
		'avatar_size' => 64, // single view...
		'date_format' => __('%s ago', WPS2_TEXT_DOMAIN),		
		'label_noevents' => __('No events.', WPS2_TEXT_DOMAIN),
		'comment_label' => __('Add Comment', WPS2_TEXT_DOMAIN),
		'comment_avatar_size' => 64,
		'attachment_label' => __('Attach an image', WPS2_TEXT_DOMAIN),
		'delete_label' => __('Delete', WPS2_TEXT_DOMAIN),
		'before' => '',
		'after' => '',
	), $atts, 'wps_events' ) );

	if ($slug == ''):

		$html .= '<div class="wps_error">'.__('Please add slug="xxx" to the shortcode, where xxx is the slug of the calendar.', WPS2_TEXT_DOMAIN).'</div>';

	else:

        global $current_user;
		$user_id = $current_user->ID;
    
		// Check user role can see calendar
		$calendar = get_page_by_path($slug, OBJECT, 'wps_calendar');
        if ($calendar):
            $saved_roles = get_post_meta( $calendar->ID, 'wps_calendar_view_roles', true);

            $continue = false;
            if ($saved_roles):
                foreach ( $saved_roles as $role => $name ) :
                    if (current_user_can($name) || $name == 'visitor') $continue = true;
                endforeach;
            else:
                if (is_user_logged_in()) $continue = true;
            endif;		

            if ($continue):

                // Check for single post view
                if (isset($_GET['event_id'])):
                    $post_id = $_GET['event_id'];
                else:
                    $post_id = false;
                endif;

                if (!$post_id):

                    // Show all items
                    require_once('wps_calendar_list.php');

                else:

                    // Show individual item
                    if (!isset($_GET['event_action']))
                        require_once('wps_calendar_single.php');

                endif;

            else:

                $html .= $private_msg;

            endif;
    
        endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_calendar_backto($atts) {

	// Init
	add_action('wp_footer', 'wps_calendar_init');

	$html = '';

	if ( isset($_GET['event_id']) ): // showing a single post

		// Shortcode parameters
		extract( shortcode_atts( array(
			'label' => __('Back to calendar...', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_events_backto' ) );

		$url = wps_curPageURL();
		$url = preg_replace("/[&?]event_id=[0-9]+/","",$url);
		$url = preg_replace("/[&?]event_action=edit+/","",$url);
		$html .= '<a href="'.$url.'">'.$label.'</a>';

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;

}


if (!is_admin()) {
	add_shortcode(WPS_PREFIX.'-calendar', 'wps_calendar');
	add_shortcode(WPS_PREFIX.'-calendar-post', 'wps_calendar_post');
	add_shortcode(WPS_PREFIX.'-calendar-backto', 'wps_calendar_backto');
}



?>
