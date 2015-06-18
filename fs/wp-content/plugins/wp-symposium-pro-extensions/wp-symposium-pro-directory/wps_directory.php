<?php
// Shortcodes
require_once('wps_directory_shortcodes.php');

// AJAX
require_once('ajax_directory.php');

// Getting Started/Help
if (is_admin())
	require_once('wps_directory_setup.php');

// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_directory');
function wps_admin_getting_started_directory() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_directory">'.__('Member Directory', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_directory' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_directory" style="display:'.$display.'">';

		echo '<p>'.__('Copy the following shortcodes to a WordPress page to display your member directory.', WPS2_TEXT_DOMAIN).'</p>';
		echo '<p>';
	  	echo '<strong>['.WPS_PREFIX.'-directory-search]</strong> <span class="description">'.__("Adds a search form for your member directory", WPS2_TEXT_DOMAIN).'</span><br />';
	  	echo '<strong>['.WPS_PREFIX.'-directory]</strong> <span class="description">'.__("Shows the results for your member directory", WPS2_TEXT_DOMAIN).'</span><br />';
	  	echo '<span class="description"><a href="http://www.wpsymposiumpro.com/shortcodes" target="_blank">'.__('more examples...', WPS2_TEXT_DOMAIN).'</a></span>';
	  	echo '</p>';

	echo '</div>';

}

?>