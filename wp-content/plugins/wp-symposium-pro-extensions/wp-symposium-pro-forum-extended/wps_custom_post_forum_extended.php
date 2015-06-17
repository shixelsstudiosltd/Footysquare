<?php

/* Create Extended (Forum meta) custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_forum_extension() {
	$labels = array(
		'name'               => __( 'Forum Extensions', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Forum Extension', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New', WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Forum Extension', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Forum Extensions', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Forum Extension', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Forum Extensions', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Forum Extension', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Forum Extensions', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No forum extension found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No forum extension found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Forum Extensions', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our forum extension specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title', 'editor' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_forum_extension', $args );
}
add_action( 'init', 'wps_custom_post_forum_extension' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_forum_extension_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_forum_extension'] = array(
		0 => '', 
		1 => __('Forum Extension updated.', WPS2_TEXT_DOMAIN),
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Forum Extension updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('Forum Extension restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Forum Extension published.', WPS2_TEXT_DOMAIN),
		7 => __('Forum Extension saved.', WPS2_TEXT_DOMAIN),
		8 => __('Forum Extension submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('Forum Extension scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Forum Extension draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_forum_extension_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'forum_extension_info_box' );
function forum_extension_info_box() {
    add_meta_box( 
        'forum_extension_info_box',
        __( 'Forum Extension Details', WPS2_TEXT_DOMAIN ),
        'forum_extension_info_box_content',
        'wps_forum_extension',
        'side',
        'high'
    );
}

function forum_extension_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'forum_extension_info_box_content', 'forum_extension_info_box_content_nonce' );
	?>
	<p>Use the editor to enter an optional description for users.</p>
	<p>
	<strong><?php _e('Type', WPS2_TEXT_DOMAIN); ?></strong><br />
	<select name="wps_forum_extension_type" id="wps_forum_extension_type">
		<option value="text"<?php if (get_post_meta($post->ID, 'wps_forum_extension_type', true) == 'text') echo ' SELECTED'; ?>><?php _e('Text', WPS2_TEXT_DOMAIN); ?></option>
		<option value="textarea"<?php if (get_post_meta($post->ID, 'wps_forum_extension_type', true) == 'textarea') echo ' SELECTED'; ?>><?php _e('Textarea', WPS2_TEXT_DOMAIN); ?></option>
		<option value="list"<?php if (get_post_meta($post->ID, 'wps_forum_extension_type', true) == 'list') echo ' SELECTED'; ?>><?php _e('List', WPS2_TEXT_DOMAIN); ?></option>
		<option value="url"<?php if (get_post_meta($post->ID, 'wps_forum_extension_type', true) == 'url') echo ' SELECTED'; ?>><?php _e('URL', WPS2_TEXT_DOMAIN); ?></option>
	</select>
	</p>
	
	<p>
	<input type="checkbox" name="wps_forum_extension_required" style="width:10px"
	<?php if (get_post_meta($post->ID, 'wps_forum_extension_required', true)) echo ' CHECKED'; ?> />
	<strong><?php _e('Required (text, textarea or URL)?', WPS2_TEXT_DOMAIN); ?></strong><br />
	</p>

	<p>
	<input type="checkbox" name="wps_forum_extension_resave" style="width:10px"
	<?php if (get_post_meta($post->ID, 'wps_forum_extension_resave', true)) echo ' CHECKED'; ?> />
	<strong><?php _e('Repeat as editable under replies?', WPS2_TEXT_DOMAIN); ?></strong><br />
	</p>

	<p>
	<strong><?php _e('Values (for list type only, values separated by commas)', WPS2_TEXT_DOMAIN); ?></strong><br />
	<input type="text" name="wps_forum_extension_default" style="width:100%" value="<?php echo get_post_meta($post->ID, 'wps_forum_extension_default', true); ?>" />
	</p>

	<p>
	<strong><?php _e('Order', WPS2_TEXT_DOMAIN); ?></strong><br />
	<input type="text" name="wps_forum_extension_order" style="width:50px" value="<?php echo get_post_meta($post->ID, 'wps_forum_extension_order', true); ?>" />
	</p>

	<?php
}

add_action( 'save_post', 'forum_extension_info_box_save' );
function forum_extension_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['forum_extension_info_box_content_nonce']) || !wp_verify_nonce( $_POST['forum_extension_info_box_content_nonce'], 'forum_extension_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	update_post_meta( $post_id, 'wps_forum_extension_default', $_POST['wps_forum_extension_default'] );
	update_post_meta( $post_id, 'wps_forum_extension_type', $_POST['wps_forum_extension_type'] );
	$order = $_POST['wps_forum_extension_order'] ? $_POST['wps_forum_extension_order'] : 0;
	update_post_meta( $post_id, 'wps_forum_extension_order', $order );

	if ( isset($_POST['wps_forum_extension_resave'])):
		update_post_meta( $post_id, 'wps_forum_extension_resave', true );
	else:
		delete_post_meta( $post_id, 'wps_forum_extension_resave');
	endif;

	if ( isset($_POST['wps_forum_extension_required'])):
		update_post_meta( $post_id, 'wps_forum_extension_required', true );
	else:
		delete_post_meta( $post_id, 'wps_forum_extension_required');
	endif;

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'forum_extension_columns_head');
add_action('manage_posts_custom_column', 'forum_extension_columns_content', 10, 2);

// ADD NEW COLUMN
function forum_extension_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_forum_extension') {
		$defaults['col_forum_extension_type'] = __('Type', WPS2_TEXT_DOMAIN);
		$defaults['col_forum_extension_order'] = __('Order', WPS2_TEXT_DOMAIN);
		$defaults['col_forum_extension_slug'] = __('Slug', WPS2_TEXT_DOMAIN);
		$defaults['col_forum_extension_default'] = __('Default', WPS2_TEXT_DOMAIN);
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function forum_extension_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_forum_extension_slug') {
        if (!is_multisite()):
            if ( wps_using_permalinks() ):    	
                $bits = explode('/', get_post_permalink($post_ID));
                echo $bits[count($bits)-2];
            else:
                $slug = explode('=', get_post_permalink($post_ID));
                echo $slug[1];
            endif;
        else:
            $bits = explode('/', get_post_permalink($post_ID));
            echo $bits[count($bits)-2];
        endif;
    }
    if ($column_name == 'col_forum_extension_type') {
    	echo get_post_meta($post_ID, 'wps_forum_extension_type', true);
    }
    if ($column_name == 'col_forum_extension_order') {
    	echo get_post_meta($post_ID, 'wps_forum_extension_order', true);
    }
    if ($column_name == 'col_forum_extension_default') {
    	echo get_post_meta($post_ID, 'wps_forum_extension_default', true);
    }
}

?>