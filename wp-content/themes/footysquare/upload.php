<?php
/**
 * This is just an example of how a file could be processed from the
 * upload script. It should be tailored to your own requirements.
 */
include_once('../../../wp-config.php');
include_once(ABSPATH . 'wp-includes/functions.php');
// Only accept files with these extensions
$whitelist = array('jpg', 'jpeg', 'png', 'gif');
$name      = null;
$error     = 'No file uploaded.';

if (isset($_FILES)) {
	if (isset($_FILES['file'])) {
		$tmp_name = $_FILES['file']['tmp_name'];
		$name     = basename($_FILES['file']['name']);
		$error    = $_FILES['file']['error'];
		
		if ($error === UPLOAD_ERR_OK) {
			$extension = pathinfo($name, PATHINFO_EXTENSION);

			if (!in_array($extension, $whitelist)) {
				$error = 'Invalid file type uploaded.';
			} else {
				$upload_dir = wp_upload_dir();
				$up_url = $upload_dir['path'];
				$t=time();
				$name = $t.$name;
				move_uploaded_file($tmp_name,$up_url.'/'.$name);
				$_SESSION["upload_file_name"] = $name;
			}
		}
	}
}
echo json_encode(array(
	'name'  => $name,
	'error' => $error,
));
die();
