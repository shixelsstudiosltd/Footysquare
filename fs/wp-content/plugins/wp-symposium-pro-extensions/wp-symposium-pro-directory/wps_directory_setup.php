<?php
// Quick Start
add_action('wps_admin_quick_start_hook', 'wps_admin_quick_start_directory');
function wps_admin_quick_start_directory() {

	echo '<div style="margin-right:10px; float:left">';
	echo '<form action="" method="POST">';
	echo '<input type="hidden" name="wpspro_quick_start" value="directory" />';
	echo '<input type="submit" class="button-secondary" value="'.__('Add Directory Page', WPS2_TEXT_DOMAIN).'" />';
	echo '</form></div>';
}

add_action('wps_admin_quick_start_form_save_hook', 'wps_admin_quick_start_directory_save', 10, 1);
function wps_admin_quick_start_directory_save($the_post) {

	if (isset($the_post['wpspro_quick_start']) && $the_post['wpspro_quick_start'] == 'directory'):

$post_content = '['.WPS_PREFIX.'-directory-search]
['.WPS_PREFIX.'-directory]';

		// Directory Page
		$post = array(
		  'post_content'   => $post_content,
		  'post_name'      => 'directory',
		  'post_title'     => 'Directory',
		  'post_status'    => 'publish',
		  'post_type'      => 'page',
		  'ping_status'    => 'closed',
		  'comment_status' => 'closed',
		);  

		$new_id = wp_insert_post( $post );

		echo '<div class="wps_success">';
			echo sprintf(__('Directory Page (%s) added. [<a href="%s">view</a>]', WPS2_TEXT_DOMAIN), get_permalink($new_id), get_permalink($new_id)).'<br /><br />';
			echo '<strong>'.__('Do not add it again or you will create another WordPress page!', WPS2_TEXT_DOMAIN).'</strong><br /><br />';
			echo sprintf(__('You might want to add it to your <a href="%s">WordPress menu</a>.', WPS2_TEXT_DOMAIN), "nav-menus.php");
		echo '</div>';

	endif;

}
?>