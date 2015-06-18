<?php

/* Create Extended (User meta) custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_extension() {
	$labels = array(
		'name'               => __( 'Extensions', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Extension', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New',  WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Extension', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Extensions', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Extension', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Profile Extensions', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Extension', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Extensions', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No extension found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No extension found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Extension', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our extension specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title', 'editor', 'thumbnail' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_extension', $args );
}
add_action( 'init', 'wps_custom_post_extension' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_extension_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_extension'] = array(
		0 => '', 
		1 => __('Profile Extension updated.'), WPS2_TEXT_DOMAIN,
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Profile Extension updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('Profile Extension restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Profile Extension published.', WPS2_TEXT_DOMAIN),
		7 => __('Profile Extension saved.', WPS2_TEXT_DOMAIN),
		8 => __('Profile Extension submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('Extension scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Profile Extension draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_extension_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'extension_info_box' );
function extension_info_box() {
    add_meta_box( 
        'extension_info_box',
        __( 'Extension Details', WPS2_TEXT_DOMAIN ),
        'extension_info_box_content',
        'wps_extension',
        'side',
        'high'
    );
}

function extension_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'extension_info_box_content', 'extension_info_box_content_nonce' );
	?>
	<p>Use the editor to enter an optional description for users.</p>
	<p>
	<strong><?php _e('Type', WPS2_TEXT_DOMAIN); ?></strong><br />
	<select name="wps_extension_type" id="wps_extension_type">
		<option value="text"<?php if (get_post_meta($post->ID, 'wps_extension_type', true) == 'text') echo ' SELECTED'; ?>><?php _e('Text', WPS2_TEXT_DOMAIN); ?></option>
		<option value="textarea"<?php if (get_post_meta($post->ID, 'wps_extension_type', true) == 'textarea') echo ' SELECTED'; ?>><?php _e('Textarea', WPS2_TEXT_DOMAIN); ?></option>
		<option value="list"<?php if (get_post_meta($post->ID, 'wps_extension_type', true) == 'list') echo ' SELECTED'; ?>><?php _e('List', WPS2_TEXT_DOMAIN); ?></option>
		<option value="url"<?php if (get_post_meta($post->ID, 'wps_extension_type', true) == 'url') echo ' SELECTED'; ?>><?php _e('URL', WPS2_TEXT_DOMAIN); ?></option>
		<option value="date"<?php if (get_post_meta($post->ID, 'wps_extension_type', true) == 'date') echo ' SELECTED'; ?>><?php _e('Date', WPS2_TEXT_DOMAIN); ?></option>
		<option value="image"<?php if (get_post_meta($post->ID, 'wps_extension_type', true) == 'image') echo ' SELECTED'; ?>><?php _e('Image', WPS2_TEXT_DOMAIN); ?></option>
		<option value="youtube"<?php if (get_post_meta($post->ID, 'wps_extension_type', true) == 'youtube') echo ' SELECTED'; ?>><?php _e('YouTube', WPS2_TEXT_DOMAIN); ?></option>
        <option value="divider"<?php if (get_post_meta($post->ID, 'wps_extension_type', true) == 'divider') echo ' SELECTED'; ?>><?php _e('Divider', WPS2_TEXT_DOMAIN); ?></option>
	</select>
	<div id="wps_extension_type_msg" <?php if (get_post_meta($post->ID, 'wps_extension_type', true) != 'url') echo ' style="display:none;"'; ?>>
		<p><input type="checkbox" name="wps_extension_target" style="width:10px" <?php if (get_post_meta($post->ID, 'wps_extension_target', true)) echo ' CHECKED'; ?> />
		<em><?php _e('open in new window', WPS2_TEXT_DOMAIN); ?></em></p>
		<p><?php _e('To show the URL as an image, set the featured image of this extension.', WPS2_TEXT_DOMAIN); ?></p>

	</div>
	<div id="wps_extension_type_image_msg" <?php if (get_post_meta($post->ID, 'wps_extension_type', true) != 'image') echo ' style="display:none;"'; ?>>
		<p><input type="checkbox" name="wps_extension_image_url" style="width:10px" <?php if (get_post_meta($post->ID, 'wps_extension_image_url', true)) echo ' CHECKED'; ?> />
		<em><?php _e('allow link URL to be entered', WPS2_TEXT_DOMAIN); ?></em></p>
		<p><input type="checkbox" name="wps_extension_target" style="width:10px" <?php if (get_post_meta($post->ID, 'wps_extension_target', true)) echo ' CHECKED'; ?> />
		<em><?php _e('open in new window', WPS2_TEXT_DOMAIN); ?></em></p>
		<strong><?php _e('Size', WPS2_TEXT_DOMAIN); ?></strong><br />
		<input type="text" name="wps_extension_image_width" style="width:60px" value="<?php echo get_post_meta($post->ID, 'wps_extension_image_width', true); ?>" /> <em><?php _e('width, include px or %', WPS2_TEXT_DOMAIN); ?></em><br />
		<input type="text" name="wps_extension_image_height" style="width:60px" value="<?php echo get_post_meta($post->ID, 'wps_extension_image_height', true); ?>" /> <em><?php _e('height, include px or auto', WPS2_TEXT_DOMAIN); ?></em>
		</p>
	</div>
	
	<div id="wps_extension_type_list_msg" <?php if (get_post_meta($post->ID, 'wps_extension_type', true) != 'list') echo ' style="display:none;"'; ?>>
		<p>
		<strong><?php _e('Values', WPS2_TEXT_DOMAIN); ?></strong><br />
		<input type="text" name="wps_extension_default" style="width:100%" value="<?php echo get_post_meta($post->ID, 'wps_extension_default', true); ?>" />
		<em><?php _e('Seperate values with commas.', WPS2_TEXT_DOMAIN); ?></em>
		</p>
	</div>

	<div id="wps_extension_type_youtube_msg" <?php if (get_post_meta($post->ID, 'wps_extension_type', true) != 'youtube') echo ' style="display:none;"'; ?>>
		<strong><?php _e('Size', WPS2_TEXT_DOMAIN); ?></strong><br />
		<input type="text" name="wps_extension_youtube_width" style="width:60px" value="<?php echo get_post_meta($post->ID, 'wps_extension_youtube_width', true); ?>" /> <em><?php _e('width, include px or %', WPS2_TEXT_DOMAIN); ?></em><br />
		<input type="text" name="wps_extension_youtube_height" style="width:60px" value="<?php echo get_post_meta($post->ID, 'wps_extension_youtube_height', true); ?>" /> <em><?php _e('height, include px or auto', WPS2_TEXT_DOMAIN); ?></em>
		</p>
	</div>

	<p>
	<strong><?php _e('Order', WPS2_TEXT_DOMAIN); ?></strong><br />
	<input type="text" name="wps_extension_order" style="width:50px" value="<?php echo get_post_meta($post->ID, 'wps_extension_order', true); ?>" />
	</p>

	<p>
	<input type="checkbox" name="wps_extension_admin_only" style="float:right; width:10px" <?php if (get_post_meta($post->ID, 'wps_extension_admin_only', true)) echo ' CHECKED'; ?> />
	<strong><?php _e('Admin only?', WPS2_TEXT_DOMAIN); ?></strong><br />
	</p>		

	<p>
	<input type="checkbox" name="wps_extension_always_show" style="float:right; width:10px" <?php if (get_post_meta($post->ID, 'wps_extension_always_show', true)) echo ' CHECKED'; ?> />
	<strong><?php _e('Always show?', WPS2_TEXT_DOMAIN); ?></strong><br />
	</p>		

	<?php if (function_exists('wps_directory_init')): ?>
		<p>
		<input type="checkbox" name="wps_extension_include" style="float:right; width:10px" <?php if (get_post_meta($post->ID, 'wps_extension_include', true)) echo ' CHECKED'; ?> />
		<strong><?php _e('Include in Directory Search?', WPS2_TEXT_DOMAIN); ?></strong><br />
		</p>		
	<?php endif;

	if (function_exists('wps_login_form')): ?>
        <div id="wps_login_form_options">
		<p id="wps_extension_register">
		<input type="checkbox" name="wps_extension_register" style="float:right; width:10px" <?php if (get_post_meta($post->ID, 'wps_extension_register', true)) echo ' CHECKED'; ?> />
		<strong><?php _e('Include on [wps-login-form]?', WPS2_TEXT_DOMAIN); ?></strong><br />
		</p>		
		<p id="wps_extension_register_mandatory">
		<input type="checkbox" name="wps_extension_register_mandatory" style="float:right; width:10px" <?php if (get_post_meta($post->ID, 'wps_extension_register_mandatory', true)) echo ' CHECKED'; ?> />
		<strong><?php _e('Mandatory on [wps-login-form]?', WPS2_TEXT_DOMAIN); ?></strong><br />
		</p>	
        </div>
	<?php endif;

}

add_action( 'save_post', 'extension_info_box_save' );
function extension_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['extension_info_box_content_nonce']) || !wp_verify_nonce( $_POST['extension_info_box_content_nonce'], 'extension_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	// Common fields
	if (isset($_POST['wps_extension_type'])) update_post_meta( $post_id, 'wps_extension_type', $_POST['wps_extension_type'] );
	$order = $_POST['wps_extension_order'] ? $_POST['wps_extension_order'] : 0;
	update_post_meta( $post_id, 'wps_extension_order', $order );

	if (isset($_POST['wps_extension_target'])):
		update_post_meta( $post_id, 'wps_extension_target', true );
	else:
		delete_post_meta( $post_id, 'wps_extension_target' );
	endif;

	if (isset($_POST['wps_extension_admin_only'])):
		update_post_meta( $post_id, 'wps_extension_admin_only', true );
	else:
		delete_post_meta( $post_id, 'wps_extension_admin_only' );
	endif;

	if (isset($_POST['wps_extension_always_show'])):
		update_post_meta( $post_id, 'wps_extension_always_show', true );
	else:
		delete_post_meta( $post_id, 'wps_extension_always_show' );
	endif;

	// List
	if (isset($_POST['wps_extension_default'])) update_post_meta( $post_id, 'wps_extension_default', $_POST['wps_extension_default'] );

	// Image
	if (isset($_POST['wps_extension_image_url'])):
		update_post_meta( $post_id, 'wps_extension_image_url', true );
	else:
		delete_post_meta( $post_id, 'wps_extension_image_url' );
	endif;
	if (isset($_POST['wps_extension_image_width'])) update_post_meta( $post_id, 'wps_extension_image_width', $_POST['wps_extension_image_width'] );
	if (isset($_POST['wps_extension_image_height'])) update_post_meta( $post_id, 'wps_extension_image_height', $_POST['wps_extension_image_height'] );

	// YouTube
	if (isset($_POST['wps_extension_youtube_width'])) update_post_meta( $post_id, 'wps_extension_youtube_width', $_POST['wps_extension_youtube_width'] );
	if (isset($_POST['wps_extension_youtube_height'])) update_post_meta( $post_id, 'wps_extension_youtube_height', $_POST['wps_extension_youtube_height'] );

	// Include in directory
	if (function_exists('wps_directory_init')):
		$include = (isset($_POST['wps_extension_include'])) ? 1 : 0;
		update_post_meta( $post_id, 'wps_extension_include', $include );
	endif;

    // Registration form [wps-login-form]
	if (function_exists('wps_login_form')):
		$register = (isset($_POST['wps_extension_register'])) ? 1 : 0;
		update_post_meta( $post_id, 'wps_extension_register', $register );
		$register_mandatory = (isset($_POST['wps_extension_register_mandatory'])) ? 1 : 0;
		update_post_meta( $post_id, 'wps_extension_register_mandatory', $register_mandatory );
	endif;

}

add_action( 'add_meta_boxes', 'extension_roles_box' );
function extension_roles_box() {
    add_meta_box( 
        'extension_roles_box',
        __( 'Visibility', WPS2_TEXT_DOMAIN ),
        'extension_roles_box_content',
        'wps_extension',
        'side',
        'high'
    );
}

function extension_roles_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'extension_roles_box_content', 'extension_roles_box_content_nonce' );
	?>
	<p><?php _e('Which roles will see this extension in addition to administrators? Select all apart from "Everyone" for members only.', WPS2_TEXT_DOMAIN); ?></p>
    <?php
    $saved_roles = get_option( 'wps_extension_roles_'.$post->post_name);
    
    global $wp_roles;
    $roles = $wp_roles->get_names();
    echo '<input type="checkbox" style="width:10px" CHECKED DISABLED id="wps_extension_role_admins" > '.__('Administrators', WPS2_TEXT_DOMAIN).'<br />';
    echo '<input type="checkbox" id="wps_extension_role_everyone" style="width:10px" name="wps_extension_role[]" ';
    if ($saved_roles && in_array('everyone', $saved_roles)) echo 'CHECKED ';
    echo 'value="everyone"> <label for="wps_extension_role_everyone">'.__('Everyone', WPS2_TEXT_DOMAIN).'</label><br />';

    foreach ( $wp_roles->roles as $key=>$value ):
        if ($key != 'administrator'):
            echo '<input type="checkbox" id="wps_extension_role_'.$key.'" style="width:10px" name="wps_extension_role[]" ';
            if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
            echo 'value="'.$key.'"> <label for="wps_extension_role_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
        endif;
    endforeach;   
    
}

add_action( 'save_post', 'extension_roles_box_save' );
function extension_roles_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['extension_roles_box_content_nonce']) || !wp_verify_nonce( $_POST['extension_roles_box_content_nonce'], 'extension_roles_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;
    
    $the_post = get_post($post_id);

	$roles = array();
	if(!empty($_POST['wps_extension_role'])):
	    
        $roles[] = 'administrator';
	    foreach($_POST['wps_extension_role'] as $check):
			$roles[] = $check;
	    endforeach;
	    update_option( 'wps_extension_roles_'.$the_post->post_name, $roles );

	else:

		delete_option( 'wps_extension_roles_'.$the_post->post_name );

	endif;
    

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'extension_columns_head');
add_action('manage_posts_custom_column', 'extension_columns_content', 10, 2);

// ADD NEW COLUMN
function extension_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_extension') {
		$defaults['col_extension_type'] = __('Type', WPS2_TEXT_DOMAIN);
		$defaults['col_extension_order'] = __('Order', WPS2_TEXT_DOMAIN);
		$defaults['col_extension_slug'] = __('Slug', WPS2_TEXT_DOMAIN);
		$defaults['col_extension_default'] = __('Default', WPS2_TEXT_DOMAIN);
        if (function_exists('wps_login_form'))
            $defaults['col_extension_register'] = __('Registration', WPS2_TEXT_DOMAIN);        
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function extension_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_extension_slug') {
		if ( wps_using_permalinks() ):    	
			$bits = explode('/', get_post_permalink($post_ID));
			echo $bits[count($bits)-2];
    	else:
    		$slug = explode('=', get_post_permalink($post_ID));
    		echo $slug[1];
		endif;
    }
    if ($column_name == 'col_extension_type') {
    	echo get_post_meta($post_ID, 'wps_extension_type', true);
    }
    if ($column_name == 'col_extension_order') {
    	echo get_post_meta($post_ID, 'wps_extension_order', true);
    }
    if ($column_name == 'col_extension_default') {
    	echo get_post_meta($post_ID, 'wps_extension_default', true);
    }
    if (function_exists('wps_login_form')) {
        if ($column_name == 'col_extension_register') {
            if (get_post_meta( $post_ID, 'wps_extension_register', true )):
                if (get_post_meta( $post_ID, 'wps_extension_register_mandatory', true )):
                    echo __('Required', WPS2_TEXT_DOMAIN);
                else:
                    echo __('Yes', WPS2_TEXT_DOMAIN);
                endif;
            endif;
        }  
    }

}

// ADD TO USERS SCREEN
add_filter('manage_users_columns', 'wps_add_user_edit_link_column');
function wps_add_user_edit_link_column($columns) {
    $columns['wps_user_edit'] = __('WPS', WPS2_TEXT_DOMAIN);
    return $columns;
}
 
add_action('manage_users_custom_column',  'wps_add_user_edit_link_column_content', 10, 3);
function wps_add_user_edit_link_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
	if ( 'wps_user_edit' == $column_name )
		return wps_usermeta_change_link(array('user_id'=>$user_id, 'text'=>__('<div id="wps_user_edit_column"></div>', WPS2_TEXT_DOMAIN)));
    return $value;
}

?>