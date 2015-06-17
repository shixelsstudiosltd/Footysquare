<?php
// Admin
if (is_admin()):
    require_once('wps_system_messages_admin.php');
	add_action('init', 'wps_system_messages_init');
endif;

function wps_system_messages_init() {

	wp_enqueue_script('wps-system-messages-js', plugins_url('wps_system_messages.js', __FILE__), array('jquery'));	
	wp_localize_script('wps-system-messages-js', 'wpspro', array( 'plugins_url' => plugins_url( '', __FILE__ ) ));
	wp_localize_script( 'wps-system-messages-js', 'wps_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );		

}

// Add default message to new users
add_action('user_register','wps_add_system_messages_default');
function wps_add_system_messages_default($user_id){

	$message = get_option('wps_system_messages_default');
	if ($message):

		$from_user = get_user_by('login', get_option('wps_system_messages_default_from'));
		if ($from_user):

			$post = array(
			  'post_title'     => $message,
			  'post_status'    => 'publish',
			  'author'		   => $from_user->ID,
			  'post_type'      => 'wps_activity',
			  'post_author'    => $from_user->ID,
			  'ping_status'    => 'closed',
			  'comment_status' => 'open',
			);  
			$new_id = wp_insert_post( $post );

			if ($new_id):

				update_post_meta( $new_id, 'wps_target', $user_id );

				$the_post = array();
				$the_post['wps_activity_post_author'] = $from_user->ID;
				$the_post['wps_activity_post'] = $message;

				// Any further actions?
				do_action( 'wps_activity_post_add_hook', $the_post, '', $new_id );

			endif;

		endif;

	endif;
}



?>