<?php

// Init
add_action('init', 'wps_groups_admin_init');
function wps_groups_admin_init() {
	wp_enqueue_script('wps-groups-js', plugins_url('wps_groups.js', __FILE__), array('jquery'));
	wp_localize_script('wps-groups-js', 'wpspro_groups', array( 'plugins_url' => plugins_url( '', __FILE__ ) ));
}


// Quick Start
add_action('wps_admin_quick_start_hook', 'wps_admin_quick_start_groups');
function wps_admin_quick_start_groups() {

	echo '<div style="margin-right:10px; float:left">';
	echo '<form action="" method="POST">';
	echo '<input type="hidden" name="wpspro_quick_start" value="group" />';
	echo '<input type="submit" class="button-secondary" value="'.__('Add Group Pages', WPS2_TEXT_DOMAIN).'" />';
	echo '</form></div>';
}

add_action('wps_admin_quick_start_form_save_hook', 'wps_admin_quick_start_group_save', 10, 1);
function wps_admin_quick_start_group_save($the_post) {

	if (isset($the_post['wpspro_quick_start']) && $the_post['wpspro_quick_start'] == 'group'):

		// Group Page
		$post_content = '['.WPS_PREFIX.'-group-image]
['.WPS_PREFIX.'-group-title]
['.WPS_PREFIX.'-group-admin before="'.__('Group admin:', WPS2_TEXT_DOMAIN).' '.'"] ['.WPS_PREFIX.'-group-edit] ['.WPS_PREFIX.'-group-delete]
['.WPS_PREFIX.'-group-description]
['.WPS_PREFIX.'-group-join-button]
&nbsp;
['.WPS_PREFIX.'-group-post]
['.WPS_PREFIX.'-group-activity]';

		$post = array(
		  'post_content'   => $post_content,
		  'post_name'      => 'group',
		  'post_title'     => 'Group',
		  'post_status'    => 'publish',
		  'post_type'      => 'page',
		  'ping_status'    => 'closed',
		  'comment_status' => 'closed',
		);  

		$new_id = wp_insert_post( $post );
		update_option('wpspro_group_page', $new_id);

		echo '<div class="wps_success">';
			echo sprintf(__('Group Page (%s) added. [<a href="%s">view</a>]', WPS2_TEXT_DOMAIN), get_permalink($new_id), get_permalink($new_id)).'<br /><br />';
			echo '<strong>'.__('Do not add it again or you will create another WordPress page!', WPS2_TEXT_DOMAIN).'</strong><br /><br />';
			echo __('You should <strong><em>not</em></strong> add this to your WordPress menu.', WPS2_TEXT_DOMAIN);
		echo '</div>';

		// Groups Page
		$post_content = '['.WPS_PREFIX.'-group-create show="0"]
['.WPS_PREFIX.'-my-groups]
['.WPS_PREFIX.'-groups]';

		$post = array(
		  'post_content'   => $post_content,
		  'post_name'      => 'groups',
		  'post_title'     => 'Groups',
		  'post_status'    => 'publish',
		  'post_type'      => 'page',
		  'ping_status'    => 'closed',
		  'comment_status' => 'closed',
		);  

		$new_id = wp_insert_post( $post );

		echo '<div class="wps_success">';
			echo sprintf(__('Groups Page (%s) added. [<a href="%s">view</a>]', WPS2_TEXT_DOMAIN), get_permalink($new_id), get_permalink($new_id)).'<br /><br />';
			echo '<strong>'.__('Do not add it again or you will create another WordPress page!', WPS2_TEXT_DOMAIN).'</strong><br /><br />';
			echo sprintf(__('You <strong><em>can</em></strong> add this to your <a href="%s">WordPress menu</a>.', WPS2_TEXT_DOMAIN), 'nav-menus.php');
		echo '</div>';

	endif;

}

// Settings
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_group');
function wps_admin_getting_started_group() {

	// Show menu item	
  	echo '<div class="wps_admin_getting_started_menu_item" id="wps_admin_getting_started_menu_item_default" rel="wps_admin_getting_started_group">'.__('Groups', WPS2_TEXT_DOMAIN).'</div>';

  	// Show setup/help content
	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_group' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_group" style="display:'.$display.'">';
	?>

		<table class="form-table">
			<tr valign="top"> 
			<td scope="row"><label for="group_page"><?php echo __('Group Page', WPS2_TEXT_DOMAIN); ?></label></td>
			<td>
				<select name="group_page">
				 <?php 
				  $group_page = get_option('wpspro_group_page');
				  if (!$group_page) echo '<option value="0">'.__('Select page...', WPS2_TEXT_DOMAIN).'</option>';
				  if ($group_page) echo '<option value="0">'.__('Reset...', WPS2_TEXT_DOMAIN).'</option>';						
				  $pages = get_pages(); 
				  foreach ( $pages as $page ) {
				  	$option = '<option value="' . $page->ID . '"';
				  		if ($page->ID == $group_page) $option .= ' SELECTED';
				  		$option .= '>';
					$option .= $page->post_title;
					$option .= '</option>';
					echo $option;
				  }
				 ?>						
				</select>
				<span class="description"><?php echo __('WordPress page that group links go to.', WPS2_TEXT_DOMAIN); ?>
				<?php if ($group_page) {
					echo ' [<a href="post.php?post='.$group_page.'&action=edit">'.__('edit', WPS2_TEXT_DOMAIN).'</a>';
					echo '|<a href="'.get_permalink($group_page).'">'.__('view', WPS2_TEXT_DOMAIN).'</a>]';
				}
				?>
				</span></td> 
			</tr> 

		</table>

		<?php
	echo '</div>';

}

add_action( 'wps_admin_setup_form_save_hook', 'wps_profile_admin_options_group_save', 10, 1 );
function wps_profile_admin_options_group_save ($the_post) {

	if (isset($the_post['group_page']) && $the_post['group_page'] > 0):
		update_option('wpspro_group_page', $the_post['group_page']);
	else:
		delete_option('wpspro_group_page');
	endif;

}

?>