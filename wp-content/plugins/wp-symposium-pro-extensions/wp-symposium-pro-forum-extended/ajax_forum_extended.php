<?php
// AJAX functions for forum extensions
add_action( 'wp_ajax_wps_forum_extended_get_meta_ajax', 'wps_forum_extended_get_meta_ajax' ); 

/* GET EXTENDED META VALUES */
function wps_forum_extended_get_meta_ajax() {

	$return_arr = array();
	$values = explode(',', get_post_meta($_POST['id'], 'wps_forum_extension_default', true));
	if ($values):
		foreach($values as $value):
			if ($_POST['term'] == '' || strpos(strtolower($value), strtolower($_POST['term'])) !== false):
			    $row_array['value'] = $value;
		    	$row_array['label'] = $value;
			    array_push($return_arr,$row_array);
			endif;
		endforeach;
	endif;

	echo json_encode($return_arr);	
	exit;

}

?>