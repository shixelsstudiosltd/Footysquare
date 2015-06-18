<?php


																	/* ***************** */
																	/* HOOKS AND FILTERS */
																	/* ***************** */


add_action( 'init', 'blockusers_init' );
function blockusers_init() {

	if ( is_admin() && !current_user_can( 'administrator' ) && !( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

		$saved_roles = get_option( 'wps_login_redirect');

		$continue = false;
		if ($saved_roles):

			foreach ( $saved_roles as $role => $name ) :
				if (current_user_can($name)) $continue = true;
			endforeach;
		endif;
		
		if (!$continue):
			wp_redirect( home_url() );
			exit;
		endif;

	}

}


?>