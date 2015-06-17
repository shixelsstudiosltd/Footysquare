<?php
// Hook into core get users AJAX function
add_action( 'wp_ajax_nopriv_wps_get_users', 'wps_get_users_ajax' );  // Logged out
add_action( 'wp_ajax_wps_get_users', 'wps_get_users_ajax' ); 

// AJAX functions for activity
add_action( 'wp_ajax_nopriv_wps_extended_get_meta', 'wps_extended_get_meta_ajax' );   // Logged out
add_action( 'wp_ajax_wps_extended_get_meta', 'wps_extended_get_meta_ajax' ); 

/* GET EXTENDED META VALUES */
function wps_extended_get_meta_ajax() {

	$labels = str_replace(',', '&', str_replace('_', ' ', $_POST['translations']));
    parse_str($labels, $labels);

	$return_arr = array();
	$values = explode(',', get_post_meta($_POST['id'], 'wps_extension_default', true));
	if ($values):
		foreach($values as $value):
		    $row_array['value'] = $value;
			if (isset($labels[str_replace(' ', '_', $value)])):
				$row_array['label'] = $labels[str_replace(' ', '_', $value)];
		    else:
		    	$row_array['label'] = $value;
		    endif;
		    array_push($return_arr,$row_array);
		endforeach;
	endif;

	echo json_encode($return_arr);	
	exit;

}

?>
