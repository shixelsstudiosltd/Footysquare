<?php
if (isset($_POST['action'])) {
  switch ($_POST['action']) {
    case 'version':
      echo '14.12.1';
      break;
    case 'info':
      $obj = new stdClass();
      $obj->slug = 'wp_symposium_pro_extensions.php';
      $obj->plugin_name = 'wp_symposium_pro_extensions.php';
      $obj->new_version = '14.12.1';
      $obj->requires = '3.5';
      $obj->tested = '4.0.1';
      $obj->last_updated = '2014-11-26';
      $obj->sections = array(
        'description' => 'Extensions for WP Symposium Pro',
        'Support' => 'For information, please visit www.wpsymposiumpro.com'
      );
      $obj->download_link = 'http://www.wpsymposiumpro.com/wp-content/plugins/wp-symposium-pro-extensions/update.php';
      echo serialize($obj);
    case 'license':
      echo 'false';
      break;
  }
} else {
    header('Cache-Control: public');
    header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
    readfile('wp-symposium-pro-extensions.zip');
}

?>