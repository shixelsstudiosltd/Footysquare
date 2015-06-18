<?php


// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_migrate');
function wps_admin_getting_started_migrate() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_migrate">'.__('WP Symposium Migration Tools', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_migrate' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_migrate" style="display:'.$display.'">';

    global $wpdb;
    $table_name = $wpdb->prefix."symposium_cats";
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name):

        echo '<p>'.sprintf(__('Please read <a href="%s">this migration guide</a> first!', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/migrating-wp-symposium-wp-symposium-pro/').'</p>';

        ?>
        <table class="form-table">
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="wps_forum_order"><?php _e('Select a forum to migrate', WPS2_TEXT_DOMAIN); ?></label>
            </th>
            <td>
                <?php

                    $sql = "SELECT cid, title FROM ".$wpdb->prefix."symposium_cats ORDER BY title";
                    $forums = $wpdb->get_results($sql);
                    if ($forums):
                        echo '<select name="wp_symposium_forums">';
                        foreach ($forums as $forum):
                            echo '<option value="'.$forum->cid.'"> '.$forum->title.'</option>';
                        endforeach;
                        echo '</select> ';
                        echo '<input type="checkbox" name="wp_symposium_forum_confirm" style="width:10px" value="1"> '.__('check to confirm', WPS2_TEXT_DOMAIN);
                        echo '<br /><span class="description">'.__('Migration can take quite a while depending on the size of your forum.', WPS2_TEXT_DOMAIN).'</span>';
                    else:
                        echo __('No forums.', WPS2_TEXT_DOMAIN);
                    endif;

                ?>
            </td>
        </tr> 
        </table>
    <?php
    else:
        echo __('WP Symposium not installed.', WPS2_TEXT_DOMAIN);
    endif;

	echo '</div>';

}

add_action('wps_admin_setup_form_get_hook', 'wps_admin_getting_started_migrate_save', 10, 2);
add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_migrate_save', 10, 2);
function wps_admin_getting_started_migrate_save($the_post) {

	if(isset($the_post['wp_symposium_forum_confirm'])):

		// Migrate WP Symposium forum
		require_once('wps_migrate_forum.php');

	endif;
}

?>
