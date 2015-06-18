<?php
// Custom Post Type
require_once('wps_custom_post_mail.php');

// Re-write rules
add_filter( 'rewrite_rules_array','wps_mail_subs_extension_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_mail_subs_extension_flush_rewrite_rules' );

function wps_mail_subs_extension_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;
	$newrules = array();
	
	$newrules['wps_mail/?'] = '/';

	return $newrules + $rules;
}
// Flush re-write rules if need be
function wps_mail_subs_extension_flush_rewrite_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_mail/?'] ) ) $flush = true;		

	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// Shortcodes
require_once('wps_mail_shortcodes.php');

// Hooks and Filters
require_once('wps_mail_hooks_and_filters.php');

// AJAX
require_once('ajax_mail.php');

// Getting Started/Help
if (is_admin())
	require_once('wps_mail_help.php');


// Flush the re-write ruless, if WPS Pro rules are not yet included
function wps_mail_flush_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_mail/?'] ) ) $flush = true;		

	// If required, flush re-write rules
	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// Add WPS Pro re-write rules
function wps_mail_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;

	$newrules = array();

	$newrules['wps_mail/?'] = '/';

	return $newrules + $rules;
}


add_filter( 'rewrite_rules_array','wps_mail_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_mail_flush_rules' );

/* ADMIN */

// Settings
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_mail');
function wps_admin_getting_started_mail() {

	// Show menu item	
  	echo '<div class="wps_admin_getting_started_menu_item" id="wps_admin_getting_started_menu_item_default" rel="wps_admin_getting_started_mail">'.__('Private Messages', WPS2_TEXT_DOMAIN).'</div>';

  	// Show setup/help content
  	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_mail' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_mail" style="display:'.$display.'">';
	?>
    
		<table class="form-table">

            <tr valign="top"> 
			<td colspan="2">
                <?php _e('If you have larger buttons on your site, you may need to change the height, and maybe width, of the pop-up message window (if used).', WPS2_TEXT_DOMAIN); ?><br />
                <?php echo sprintf(__('Add the following to <a href="%s">Custom CSS</a>, changing the numbers accordingly:', WPS2_TEXT_DOMAIN), "admin.php?page=wps_pro_custom_css"); ?>
                <pre>.wps_mail_to_user_post_popup_div { width: 400px !important; height: 600px !important; }</pre>
            </td> 
			</tr> 
            
			<tr valign="top"> 
			<td scope="row"><label for="mail_page"><?php echo __('Messages Page', WPS2_TEXT_DOMAIN); ?></label></td>
			<td>
				<select name="mail_page">
				 <?php 
				  $mail_page = get_option('wpspro_mail_page');
				  if (!$mail_page) echo '<option value="0">'.__('Select page...', WPS2_TEXT_DOMAIN).'</option>';
				  if ($mail_page) echo '<option value="0">'.__('Reset...', WPS2_TEXT_DOMAIN).'</option>';						
				  $pages = get_pages(); 
				  foreach ( $pages as $page ) {
				  	$option = '<option value="' . $page->ID . '"';
				  		if ($page->ID == $mail_page) $option .= ' SELECTED';
				  		$option .= '>';
					$option .= $page->post_title;
					$option .= '</option>';
					echo $option;
				  }
				 ?>						
				</select>
				<span class="description"><?php echo __('WordPress page that messages links go to.', WPS2_TEXT_DOMAIN); ?>
				<?php if ($mail_page) {
					echo ' [<a href="post.php?post='.$mail_page.'&action=edit">'.__('edit', WPS2_TEXT_DOMAIN).'</a>';
					echo '|<a href="'.get_permalink($mail_page).'">'.__('view', WPS2_TEXT_DOMAIN).'</a>]';
				}
				?>
				</span></td> 
			</tr> 
            
			<tr valign="top"> 
			<td scope="row"><label for="mail_page"><?php echo __('Allow all', WPS2_TEXT_DOMAIN); ?></label></td>
			<td>
				<div id="wpspro_mail_all">
					<input type="checkbox" style="width:10px" name="wpspro_mail_all" <?php if (get_option('wpspro_mail_all')) echo ' CHECKED'; ?> />
					<span class="description"><?php _e('Allow users to send private messages to all users, even if not friends.', WPS2_TEXT_DOMAIN); ?></span>
				</span></td> 
			</tr> 

		</table>

		<?php

	echo '</div>';

}

add_action( 'wps_admin_setup_form_save_hook', 'wps_mail_admin_options_save', 10, 1 );
function wps_mail_admin_options_save ($the_post) {

	if (isset($the_post['mail_page']) && $the_post['mail_page'] > 0):
		update_option('wpspro_mail_page', $the_post['mail_page']);
	else:
		delete_option('wpspro_mail_page');
	endif;

	if (isset($the_post['wpspro_mail_all']) && $the_post['wpspro_mail_all']):
		update_option('wpspro_mail_all', true);
	else:
		delete_option('wpspro_mail_all');
	endif;

}

?>