<?php
// Quick Start
add_action('wps_admin_quick_start_hook', 'wps_admin_quick_start_event');
function wps_admin_quick_start_event() {

	echo '<div style="margin-right:10px; float:left">';
	echo '<form action="" method="POST">';
	echo '<input type="hidden" name="wpspro_quick_start" value="event" />';
	echo '<input type="submit" class="button-secondary" value="'.__('Add Calendar Page', WPS2_TEXT_DOMAIN).'" />';
	echo '</form></div>';
}

add_action('wps_admin_quick_start_form_save_hook', 'wps_admin_quick_start_event_save', 10, 1);
function wps_admin_quick_start_event_save($the_post) {

	if (isset($the_post['wpspro_quick_start']) && $the_post['wpspro_quick_start'] == 'event'):

		// New Calendar
		$post = array(
		  'post_content'   => __('A default calendar.', WPS2_TEXT_DOMAIN),
		  'post_name'      => 'default-calendar',
		  'post_title'     => __('Default Calendar', WPS2_TEXT_DOMAIN),
		  'post_status'    => 'publish',
		  'post_type'      => 'wps_calendar',
		  'ping_status'    => 'closed',
		  'comment_status' => 'closed',
		);  
		$new_calendar_id = wp_insert_post( $post );		
		$roles[] = 'administrator';
	    update_post_meta( $new_calendar_id, 'wps_calendar_roles', $roles );
	    
		// New Calendar Event
		$post = array(
		  'post_content'   => __('This is a default event.', WPS2_TEXT_DOMAIN),
		  'post_name'      => 'default-event',
		  'post_title'     => __('A default event', WPS2_TEXT_DOMAIN),
		  'post_status'    => 'publish',
		  'post_type'      => 'wps_event',
		  'ping_status'    => 'closed',
		  'comment_status' => 'closed',
		);  
		$new_event_id = wp_insert_post( $post );		
		update_post_meta( $new_event_id, 'wps_event_calendar', $new_calendar_id );
		update_post_meta( $new_event_id, 'wps_event_start', current_time('Y-m-d', 0) );
		update_post_meta( $new_event_id, 'wps_event_start_time', '9:00' );
		update_post_meta( $new_event_id, 'wps_event_end', current_time('Y-m-d', 0) );
		update_post_meta( $new_event_id, 'wps_event_end_time', '17:00' );


$post_content = '['.WPS_PREFIX.'-calendar-backto]
['.WPS_PREFIX.'-calendar slug="default-calendar"]
['.WPS_PREFIX.'-calendar-post slug="default-calendar"]';

		// Calendar Page
		$post = array(
		  'post_content'   => $post_content,
		  'post_name'      => 'calendar',
		  'post_title'     => __('Calendar', WPS2_TEXT_DOMAIN),
		  'post_status'    => 'publish',
		  'post_type'      => 'page',
		  'ping_status'    => 'closed',
		  'comment_status' => 'closed',
		);  

		$new_id = wp_insert_post( $post );

		echo '<div class="wps_success">';
			echo sprintf(__('Calendar Page (%s) added. [<a href="%s">view</a>]', WPS2_TEXT_DOMAIN), get_permalink($new_id), get_permalink($new_id)).'<br /><br />';
			echo '<strong>'.__('Do not add it again or you will create another WordPress page!', WPS2_TEXT_DOMAIN).'</strong><br /><br />';
			echo sprintf(__('You might want to add it to your <a href="%s">WordPress menu</a>.', WPS2_TEXT_DOMAIN), "nav-menus.php");
		echo '</div>';

	endif;

}
?>