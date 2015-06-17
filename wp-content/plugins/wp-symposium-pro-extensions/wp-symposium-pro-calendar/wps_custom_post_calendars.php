<?php

/* Create Events custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_calendar() {
	$labels = array(
		'name'               => __( 'Calendars', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Calendar', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New', WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Event', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Calendar', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Calendar', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Calendars', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Calendar', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Calendars', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No calendar found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No calendar found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Calendars', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our calendar specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title', 'editor' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_calendar', $args );
}
add_action( 'init', 'wps_custom_post_calendar' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_calendar_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_calendar'] = array(
		0 => '', 
		1 => __('Calendar updated.', WPS2_TEXT_DOMAIN),
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Calendar updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('Calendar restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Calendar published.', WPS2_TEXT_DOMAIN),
		7 => __('Calendar saved.', WPS2_TEXT_DOMAIN),
		8 => __('Calendar submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('Calendar scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Calendar draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_calendar_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'calendar_info_box' );
function calendar_info_box() {
    add_meta_box( 
        'calendar_info_box',
        __( 'Calendar Details', WPS2_TEXT_DOMAIN ),
        'calendar_info_box_content',
        'wps_calendar',
        'side',
        'high'
    );
}

function calendar_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'caledar_info_box_content', 'caledar_info_box_content_nonce' );

	echo '<p>'.sprintf(__('To add events to this calendar, <a href="%s">click here</a>.', WPS2_TEXT_DOMAIN), 'edit.php?post_type=wps_event').'</p>';

	echo '<p><em>'.__('Roles that can view this calendar:', WPS2_TEXT_DOMAIN).'</em></p>';

		$saved_roles = get_post_meta( $post->ID, 'wps_calendar_view_roles', true);
	    global $wp_roles;
		$roles = $wp_roles->get_names();
		foreach ( $wp_roles->roles as $key=>$value ):
			echo '<input type="checkbox" id="wps_calendar_view_role_'.$key.'" style="width:10px" name="wps_calendar_view_role[]" ';
			if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
			echo 'value="'.$key.'"> <label for="wps_calendar_view_role_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
		endforeach;
		$key = 'visitor';
		$name = __('Visitor', WPS2_TEXT_DOMAIN);
		echo '<input type="checkbox" id="wps_calendar_view_role_'.$key.'" style="width:10px" name="wps_calendar_view_role[]" ';
		if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
		echo 'value="'.$key.'"> <label for="wps_calendar_view_role_'.$key.'">'.$name.' ('.$key.')</label><br />';

	echo '</p>';	

	echo '<p><em>'.__('Roles that can add events:', WPS2_TEXT_DOMAIN).'</em></p>';

		$saved_roles = get_post_meta( $post->ID, 'wps_calendar_roles', true);
	    global $wp_roles;
		$roles = $wp_roles->get_names();
		foreach ( $wp_roles->roles as $key=>$value ):
			echo '<input type="checkbox" id="wps_calendar_role_'.$key.'" style="width:10px" name="wps_calendar_role[]" ';
			if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
			echo 'value="'.$key.'"> <label for="wps_calendar_role_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
		endforeach;

	echo '</p>';	

}

add_action( 'save_post', 'calendar_info_box_save' );
function calendar_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['caledar_info_box_content_nonce']) || !wp_verify_nonce( $_POST['caledar_info_box_content_nonce'], 'caledar_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	$roles = array();
	if(!empty($_POST['wps_calendar_role'])):

	    foreach($_POST['wps_calendar_role'] as $check):
			$roles[] = $check;
	    endforeach;
	    update_post_meta( $post_id, 'wps_calendar_roles', $roles );

	else:

		delete_post_meta( $post_id, 'wps_calendar_roles' );

	endif;

	$roles = array();
	if(!empty($_POST['wps_calendar_view_role'])):

	    foreach($_POST['wps_calendar_view_role'] as $check):
			$roles[] = $check;
	    endforeach;
	    update_post_meta( $post_id, 'wps_calendar_view_roles', $roles );

	else:

		delete_post_meta( $post_id, 'wps_calendar_view_roles' );

	endif;

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'calendar_columns_head');
add_action('manage_posts_custom_column', 'calendar_columns_content', 10, 2);

// ADD NEW COLUMN
function calendar_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_calendar') {
		$defaults['col_calendar_slug'] = __('Slug', WPS2_TEXT_DOMAIN);
		$defaults['col_calendar_events'] = __('Events', WPS2_TEXT_DOMAIN);
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function calendar_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_calendar_slug') {
    	$calendar = get_post($post_ID);
    	echo $calendar->post_name;
    }
    if ($column_name == 'col_calendar_events') {
    	global $wpdb;
    	$sql = "SELECT COUNT(ID) AS event_count FROM ".$wpdb->prefix."posts p LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_id WHERE post_type='wps_event' AND m.meta_key='wps_event_calendar' AND m.meta_value=%d";
    	$event_count = $wpdb->get_var($wpdb->prepare($sql, $post_ID));
    	echo $event_count;
    }
}

/* =========================== ALTER VIEW POST LINKS =========================== */

function wps_change_calendar_link( $permalink, $post ) {

	if ($post->post_type == 'wps_calendar'):

		// Can change link to calendar here

	endif;

    return $permalink;

}
add_filter('post_type_link',"wps_change_calendar_link",10,2);





?>