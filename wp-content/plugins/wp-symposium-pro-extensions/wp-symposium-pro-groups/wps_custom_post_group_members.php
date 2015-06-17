<?php

/* Create Group Members custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_group_members() {
	$labels = array(
		'name'               => __( 'Group Members', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Group Member', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New', WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Group Member', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Group Member', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Group Member', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Group Members', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Group Members', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Group Members', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No group members found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No group members found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __( 'Group Members', WPS2_TEXT_DOMAIN ),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our group members specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_group_members', $args );
}
add_action( 'init', 'wps_custom_post_group_members' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_group_membership_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_group_members'] = array(
		0 => '', 
		1 => __('Group membership updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Group membership updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Group membership restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Group membership published.'),
		7 => __('Group membership saved.'),
		8 => __('Group membership submitted.'),
		9 => sprintf( __('Group membership scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Group membership draft updated.'),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_group_membership_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */

add_action( 'add_meta_boxes', 'group_members_info_box' );
function group_members_info_box() {
    add_meta_box( 
        'group_members_info_box',
        __( 'Membership Info', WPS2_TEXT_DOMAIN ),
        'group_members_info_box_content',
        'wps_group_members',
        'normal',
        'high'
    );
}

function group_members_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'group_members_info_box_content', 'group_members_info_box_content_nonce' );

	echo '<div style="margin-top:10px;font-weight:bold">'.__('User', WPS2_TEXT_DOMAIN).'</div>';
	$member = get_user_by( 'id', get_post_meta( $post->ID, 'wps_member', true ) );
	$member_text = ($member) ? $member->user_login : '';
	echo '<input type="text" id="wps_member" style="width:300px" name="wps_member" placeholder="Select user..." value="'.$member_text.'" />';

	echo '<div style="margin-top:10px;font-style:italic;">'.__('is a member of...', WPS2_TEXT_DOMAIN).'</div>';

	echo '<div style="margin-top:10px;font-weight:bold">'.__('Group', WPS2_TEXT_DOMAIN).'</div>';
	$group = get_post( get_post_meta( $post->ID, 'wps_group', true ) );
	$title = ($group) ? $group->post_title : '';
	echo '<input type="text" id="wps_group" style="width:400px" name="wps_group" placeholder="Select group..." value="'.$title.'" />';

}

add_action( 'save_post', 'group_members_info_box_save' );
function group_members_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['group_members_info_box_content_nonce']) || !wp_verify_nonce( $_POST['group_members_info_box_content_nonce'], 'group_members_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	$group_title = isset($_POST['wps_group']) ? stripslashes($_POST['wps_group']) : false;
	$member_login = isset($_POST['wps_member']) ? $_POST['wps_member'] : false;

	if ($group_title && $member_login) {

		$member = get_user_by('login', $member_login);

		$group = get_page_by_title($group_title, OBJECT, 'wps_group');

		if ($member && $group):

			global $wpdb;

			// New member
			update_post_meta( $post_id, 'wps_member', $member->ID );
			update_post_meta( $post_id, 'wps_group', $group->ID );
			update_post_meta( $post_id, 'wps_group_member_since', current_time('mysql', 1) );

			remove_action( 'save_post', 'group_members_info_box_save' );
			$my_post = array(
			      'ID'         	=> $post_id,
			      'post_title' 	=> $member->user_login.' - '.$group->post_title,
			      'post_name'	=> sanitize_title_with_dashes($member->user_login.' '.$group->post_title),
			      'post_type'	=> 'wps_group_members',
			      'post_status'	=> 'publish'
			);
			wp_update_post( $my_post );			
			add_action( 'save_post', 'group_members_info_box_save' );

		endif;

	} else {

		die(__('Need member and group.', WPS2_TEXT_DOMAIN));

	}

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'group_membership_columns_head');
add_action('manage_posts_custom_column', 'group_membership_columns_content', 10, 2);

// ADD NEW COLUMN
function group_membership_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_group_members') {
		$defaults['col_group_member'] = __('User display name', WPS2_TEXT_DOMAIN);
    	$defaults['col_group_title'] = __('Group', WPS2_TEXT_DOMAIN);
    	$defaults['col_membership_status'] = __('Status', WPS2_TEXT_DOMAIN);
    	$defaults['wps_group_member_since'] = __('Member since', WPS2_TEXT_DOMAIN);
    	unset($defaults['date']);
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function group_membership_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_group_member') {
    	$post = get_post($post_ID); 
    	$user = get_user_by('id', $post->wps_member);
    	echo $user->display_name;
    }
    if ($column_name == 'col_group_title') {
    	$group_id = get_post_meta($post_ID, 'wps_group', true);
    	echo wps_get_group_name($group_id, true);
    }
    if ($column_name == 'col_membership_status') {
    	$post = get_post($post_ID); 
    	if ($post->post_status == 'publish'):
    		echo __('Member', WPS2_TEXT_DOMAIN);
    	else:
    		echo __('Pending', WPS2_TEXT_DOMAIN);
    	endif;
    }
    if ($column_name == 'wps_group_member_since') {
    	$post = get_post($post_ID); 
    	echo date("F j, Y h:m:s a", strtotime($post->wps_group_member_since));
    }
}




?>