<?php

/* Create Groups custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_group() {
	$labels = array(
		'name'               => __( 'Groups', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Groups', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New', WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Group', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Group', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Group', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Groups', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Group', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Groups', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No group found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No group found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Groups', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our group specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title', 'thumbnail', 'editor' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_group', $args );
}
add_action( 'init', 'wps_custom_post_group' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_groups_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_group'] = array(
		0 => '', 
		1 => __('Group updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Group updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Group restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Group published.'),
		7 => __('Group saved.'),
		8 => __('Group submitted.'),
		9 => sprintf( __('Group scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Group draft updated.'),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_groups_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'group_info_box' );
function group_info_box() {
    add_meta_box( 
        'group_info_box',
        __( 'Group Details', WPS2_TEXT_DOMAIN ),
        'group_info_box_content',
        'wps_group',
        'side',
        'high'
    );
}

function group_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'group_info_box_content', 'group_info_box_content_nonce' );

	echo '<div style="margin-top:10px;font-weight:bold">'.__('Group Admin', WPS2_TEXT_DOMAIN).'</div>';
	$author = get_user_by( 'id', $post->post_author );
	echo '<input type="text" id="wps_admin" name="wps_admin" placeholder="Select author..." value="'.$author->user_login.'" /><br /><br />';

	echo '<input type="checkbox" ';
		if (get_post_meta($post->ID, 'wps_group_privacy', true)) echo 'CHECKED ';
		echo 'name="wps_group_privacy" /> '.__('Join requests need approving', WPS2_TEXT_DOMAIN);

}

add_action( 'save_post', 'group_info_box_save' );
function group_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['group_info_box_content_nonce']) || !wp_verify_nonce( $_POST['group_info_box_content_nonce'], 'group_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	if (isset($_POST['wps_group_privacy'])):
		update_post_meta($post_id, 'wps_group_privacy', $_POST['wps_group_privacy']);
	else:
		delete_post_meta($post_id, 'wps_group_privacy');
	endif;

	$author = get_user_by( 'login', $_POST['wps_admin'] );
	remove_action( 'save_post', 'group_info_box_save' );
	$my_post = array(
	      'ID'         	=> $post_id,
	      'post_author' => $author->ID,
	);
	wp_update_post( $my_post );			
	add_action( 'save_post', 'group_info_box_save' );	

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'group_columns_head');
add_action('manage_posts_custom_column', 'group_columns_content', 10, 2);

// ADD NEW COLUMN
function group_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_group') {
		$defaults['col_group_ID'] = 'ID';
		$defaults['col_group_page'] = __('Page link', WPS2_TEXT_DOMAIN);
		$defaults['col_group_admin'] = __('Admin', WPS2_TEXT_DOMAIN);
		$defaults['col_group_privacy'] = __('Private', WPS2_TEXT_DOMAIN);
    	unset($defaults['date']);
    }
    return $defaults;
}

// SHOW THE COLUMN CONTENT
function group_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_group_ID') {
    	echo $post_ID;
    }
    if ($column_name == 'col_group_page') {
    	echo wps_get_group_name($post_ID, $link=true);
    }
    if ($column_name == 'col_group_admin') {
    	$post = get_post($post_ID);
    	$user = get_user_by ('id', $post->post_author );
    	echo $user->user_login.' ';
    	echo '('.$user->display_name.')';
    }
    if ($column_name == 'col_group_privacy') {
    	if (get_post_meta($post_ID, 'wps_group_privacy', true)) echo __('Yes', WPS2_TEXT_DOMAIN);
    }
}

/* =========================== ALTER VIEW POST LINKS =========================== */

function wps_change_group_link( $permalink, $post ) {

	if ($post->post_type == 'wps_group'):

		$group_page = get_option('wpspro_group_page');
		if ($group_page):
			$url = get_permalink($group_page);
			$permalink = $url.wps_query_mark($url).'group_id='.$post->ID;
		endif;

	endif;

    return $permalink;

}
add_filter('post_type_link',"wps_change_group_link",10,2);
?>