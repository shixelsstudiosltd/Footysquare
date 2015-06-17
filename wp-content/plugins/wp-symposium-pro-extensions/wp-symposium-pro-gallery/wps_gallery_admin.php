<?php

// Quick Start
add_action('wps_admin_quick_start_hook', 'wps_admin_quick_start_gallery');
function wps_admin_quick_start_gallery() {

	echo '<div style="margin-right:10px; float:left">';
	echo '<form action="" method="POST">';
	echo '<input type="hidden" name="wpspro_quick_start" value="gallery" />';
	echo '<input type="submit" class="button-secondary" value="'.__('Add Gallery Page', WPS2_TEXT_DOMAIN).'" />';
	echo '</form></div>';
}

add_action('wps_admin_quick_start_form_save_hook', 'wps_admin_quick_start_gallery_save', 10, 1);
function wps_admin_quick_start_gallery_save($the_post) {

	if (isset($the_post['wpspro_quick_start']) && $the_post['wpspro_quick_start'] == 'gallery'):

		// Gallery Page
		$post_content = '['.WPS_PREFIX.'-gallery]';

		$post = array(
		  'post_content'   => $post_content,
		  'post_name'      => 'gallery',
		  'post_title'     => 'Gallery',
		  'post_status'    => 'publish',
		  'post_type'      => 'page',
		  'ping_status'    => 'closed',
		  'comment_status' => 'closed',
		);  

		$new_id = wp_insert_post( $post );
		update_option('wpspro_gallery_page', $new_id);

		echo '<div class="wps_success">';
			echo sprintf(__('Gallery Page (%s) added. [<a href="%s">view</a>]', WPS2_TEXT_DOMAIN), get_permalink($new_id), get_permalink($new_id)).'<br /><br />';
			echo '<strong>'.__('Do not add it again or you will create another WordPress page!', WPS2_TEXT_DOMAIN).'</strong><br /><br />';
			echo sprintf(__('You <strong><em>can</em></strong> add this to your <a href="%s">WordPress menu</a>.', WPS2_TEXT_DOMAIN), 'nav-menus.php');
		echo '</div>';

	endif;

}

// Settings
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_gallery');
function wps_admin_getting_started_gallery() {

	// Show menu item	
  	echo '<div class="wps_admin_getting_started_menu_item" id="wps_admin_getting_started_menu_item_default" rel="wps_admin_getting_started_gallery">'.__('Galleries', WPS2_TEXT_DOMAIN).'</div>';

  	// Show setup/help content
	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_gallery' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_gallery" style="display:'.$display.'">';
	?>

		<table class="form-table">
			<tr valign="top"> 
			<td scope="row"><label for="gallery_page"><?php echo __('Gallery Page', WPS2_TEXT_DOMAIN); ?></label></td>
			<td>
				<select name="gallery_page">
				 <?php 
				  $gallery_page = get_option('wpspro_gallery_page');
				  if (!$gallery_page) echo '<option value="0">'.__('Select page...', WPS2_TEXT_DOMAIN).'</option>';
				  if ($gallery_page) echo '<option value="0">'.__('Reset...', WPS2_TEXT_DOMAIN).'</option>';						
				  $pages = get_pages(); 
				  foreach ( $pages as $page ) {
				  	$option = '<option value="' . $page->ID . '"';
				  		if ($page->ID == $gallery_page) $option .= ' SELECTED';
				  		$option .= '>';
					$option .= $page->post_title;
					$option .= '</option>';
					echo $option;
				  }
				 ?>						
				</select>
				<span class="description"><?php echo __('WordPress page that gallery links go to.', WPS2_TEXT_DOMAIN); ?>
				<?php if ($gallery_page) {
					echo ' [<a href="post.php?post='.$gallery_page.'&action=edit">'.__('edit', WPS2_TEXT_DOMAIN).'</a>';
					echo '|<a href="'.get_permalink($gallery_page).'">'.__('view', WPS2_TEXT_DOMAIN).'</a>]';
				}
				?>
				</span></td> 
			</tr> 

		</table>

		<?php
	echo '</div>';

}

add_action( 'wps_admin_setup_form_save_hook', 'wps_profile_admin_options_gallery_save', 10, 1 );
function wps_profile_admin_options_gallery_save ($the_post) {

	if (isset($the_post['gallery_page']) && $the_post['gallery_page'] > 0):
		update_option('wpspro_gallery_page', $the_post['gallery_page']);
	else:
		delete_option('wpspro_gallery_page');
	endif;

}

?>