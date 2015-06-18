<?php

/* Create Events custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_event() {
	$labels = array(
		'name'               => __( 'Events', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Event', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New', WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Event', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Event', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Event', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Calendar Events', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Event', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Events', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No event found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No event found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Events', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our event specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title', 'thumbnail', 'editor', 'comments' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_event', $args );
}
add_action( 'init', 'wps_custom_post_event' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_event_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_event'] = array(
		0 => '', 
		1 => __('Event updated.', WPS2_TEXT_DOMAIN),
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Event updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('Event restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Event published.', WPS2_TEXT_DOMAIN),
		7 => __('Event saved.', WPS2_TEXT_DOMAIN),
		8 => __('Event submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('Event scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Event draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_event_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'event_info_box' );
function event_info_box() {
    add_meta_box( 
        'event_info_box',
        __( 'Event Details', WPS2_TEXT_DOMAIN ),
        'event_info_box_content',
        'wps_event',
        'side',
        'high'
    );
}

function event_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'event_info_box_content', 'event_info_box_content_nonce' );

	$wps_calendar = ($value = get_post_meta( $post->ID, 'wps_event_calendar', true )) ? $value : '';
	$wps_event_start = ($value = get_post_meta( $post->ID, 'wps_event_start', true )) ? $value : '';
	$wps_event_start_time = ($value = get_post_meta( $post->ID, 'wps_event_start_time', true )) ? $value : '';
	$wps_event_end = ($value = get_post_meta( $post->ID, 'wps_event_end', true )) ? $value : '';
	$wps_event_end_time = ($value = get_post_meta( $post->ID, 'wps_event_end_time', true )) ? $value : '';

    $calendars = get_posts( array(
        'post_type' => 'wps_calendar',
        'posts_per_page' => -1
    ) );
    
	echo '<p>'.__('Calendar', WPS2_TEXT_DOMAIN).'<br />';
    if ( $calendars ) {
		echo '<select name="wps_event_calendar" id="wps_event_calendar" style="width:100%">';
    	echo '<option value="0">'.__('Select calendar...', WPS2_TEXT_DOMAIN).'</option>';
        foreach ( $calendars as $calendar ) {
        	echo '<option value="'.$calendar->ID.'"';
        	if ($calendar->ID == $wps_calendar) echo ' SELECTED';
        	echo '>'.$calendar->post_title.'</option>';
        }
		echo '</select>';
    } else {
    	echo sprintf(__('<a href="%s">Create a calendar</a>.', WPS2_TEXT_DOMAIN), 'edit.php?post_type=wps_calendar');
    }
    echo '</p>';

	echo __('Start Date', WPS2_TEXT_DOMAIN).'<br />';
	echo '<input id="wps_event_start" name="wps_event_start" value="'.$wps_event_start.'" type="text" /><br />';

	echo __('Start Time', WPS2_TEXT_DOMAIN).'<br />';
	echo '<input id="wps_event_start_time" name="wps_event_start_time" value="'.$wps_event_start_time.'" type="text" /><br />';

	echo '<br />';

	echo __('End Date', WPS2_TEXT_DOMAIN).'<br />';
	echo '<input id="wps_event_end" name="wps_event_end" value="'.$wps_event_end.'" type="text" /><br />';

	echo __('End Time', WPS2_TEXT_DOMAIN).'<br />';
	echo '<input id="wps_event_end_time" name="wps_event_end_time" value="'.$wps_event_end_time.'" type="text" />';

}

add_action( 'save_post', 'event_info_box_save' );
function event_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['event_info_box_content_nonce']) || !wp_verify_nonce( $_POST['event_info_box_content_nonce'], 'event_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	if ($_POST['wps_event_calendar']):
		update_post_meta( $post_id, 'wps_event_calendar', $_POST['wps_event_calendar'] );
	else:
		delete_post_meta( $post_id, 'wps_event_calendar' );
	endif;

	if ($_POST['wps_event_start']):
		update_post_meta( $post_id, 'wps_event_start', $_POST['wps_event_start'] );
	else:
		delete_post_meta( $post_id, 'wps_event_start' );
	endif;

	if ($_POST['wps_event_start_time']):
		update_post_meta( $post_id, 'wps_event_start_time', $_POST['wps_event_start_time'] );
	else:
		delete_post_meta( $post_id, 'wps_event_start_time' );
	endif;

	if ($_POST['wps_event_end']):
		update_post_meta( $post_id, 'wps_event_end', $_POST['wps_event_end'] );
	else:
		delete_post_meta( $post_id, 'wps_event_end' );
	endif;

	if ($_POST['wps_event_end_time']):
		update_post_meta( $post_id, 'wps_event_end_time', $_POST['wps_event_end_time'] );
	else:
		delete_post_meta( $post_id, 'wps_event_end_time' );
	endif;

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'event_columns_head');
add_action('manage_posts_custom_column', 'event_columns_content', 10, 2);

// ADD NEW COLUMN
function event_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_event') {
		$defaults['col_event_start'] = __('Start', WPS2_TEXT_DOMAIN);
		$defaults['col_event_end'] = __('End', WPS2_TEXT_DOMAIN);
		$defaults['col_event_image'] = __('Image', WPS2_TEXT_DOMAIN);
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function event_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_event_start') {
		$wps_event_start = get_post_meta( $post_ID, 'wps_event_start', true );
		$wps_event_start_time = get_post_meta( $post_ID, 'wps_event_start_time', true );
		if ($wps_event_start) echo $wps_event_start;
		if ($wps_event_start_time) echo ', '.$wps_event_start_time;
    }
    if ($column_name == 'col_event_end') {
		$wps_event_end = get_post_meta( $post_ID, 'wps_event_end', true );
		$wps_event_end_time = get_post_meta( $post_ID, 'wps_event_end_time', true );
		if ($wps_event_end) echo $wps_event_end;
		if ($wps_event_end_time) echo ', '.$wps_event_end_time;
    }
    if ($column_name == 'col_event_image') {
		if (has_post_thumbnail($post_ID)):
			echo get_the_post_thumbnail($post_ID, array(37,37));
		endif;
    }

}

/* =========================== ALTER VIEW POST LINKS =========================== */

function wps_change_event_link( $permalink, $post ) {

	if ($post->post_type == 'wps_event'):

		global $wpdb;
		$sql = "SELECT ID FROM ".$wpdb->prefix."posts WHERE (post_content LIKE '%%[wps-events %%' OR post_content LIKE '%%[wps-events]%%') AND post_type = 'page' AND post_status = %s;";
		if ($pages = $wpdb->get_results($wpdb->prepare($sql, 'publish'))):
			$page = $pages[0];
			$url = get_permalink($page->ID);
			$permalink = $url.wps_query_mark($url).'event='.$post->ID;
		endif;

	endif;

    return $permalink;

}
add_filter('post_type_link',"wps_change_event_link",10,2);



?>