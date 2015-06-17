<?php

/* Create Crowd custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_crowd() {
	$labels = array(
		'name'               => __( 'User Lists', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'User List', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New',  WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New List', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit List', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New List', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'User Lists', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Lists', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Lists', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No lists found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No lists found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('User Lists', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our list specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_crowd', $args );
}
add_action( 'init', 'wps_custom_post_crowd' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_crowd_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_crowd'] = array(
		0 => '', 
		1 => __('User List updated.', WPS2_TEXT_DOMAIN),
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('User List updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('User list restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('User List published.', WPS2_TEXT_DOMAIN),
		7 => __('User List saved.', WPS2_TEXT_DOMAIN),
		8 => __('User List submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('User List scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('User List draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_crowd_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'crowd_info_box' );
function crowd_info_box() {
    add_meta_box( 
        'crowd_info_box',
        __( 'Crowd Details', WPS2_TEXT_DOMAIN ),
        'crowd_info_box_content',
        'wps_crowd',
        'side',
        'high'
    );
}

function crowd_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'crowd_info_box_content', 'crowd_info_box_content_nonce' );

	$author = get_user_by('id', $post->post_author);
	echo '<p>'.__('Owner:', WPS2_TEXT_DOMAIN).' '.$author->display_name.' ('.$author->user_login.')</p>';

	$recipients = get_post_meta( $post->ID, 'wps_crowd_recipients', true );
	if (!empty($recipients)):
		$recipients_list = implode(",", $recipients);
	else:
		$recipients_list = '';
	endif;
	echo '<div style="margin-top:10px;font-weight:bold">'.__('Recipients (user logins)', WPS2_TEXT_DOMAIN).'</div>';
	echo '<textarea style="width:100%; height:200px;" id="wps_crowd_recipients" name="wps_crowd_recipients">'.$recipients_list.'</textarea>';

}

add_action( 'save_post', 'crowd_info_box_save' );
function crowd_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['crowd_info_box_content_nonce']) || !wp_verify_nonce( $_POST['crowd_info_box_content_nonce'], 'crowd_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	if ($_POST['wps_crowd_recipients']):
		$recipients_list = explode(",", $_POST['wps_crowd_recipients']);
		update_post_meta( $post_id, 'wps_crowd_recipients', $recipients_list );
	else:
		delete_post_meta( $post_id, 'wps_crowd_recipients' );
	endif;

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'crowd_columns_head');
add_action('manage_posts_custom_column', 'crowd_columns_content', 10, 2);

// ADD NEW COLUMN
function crowd_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_crowd') {
		$defaults['col_crowd_author'] = 'Author';
		$defaults['col_crowd_recipients'] = 'Recipients';
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function crowd_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_crowd_author') {
    	$post = get_post($post_ID);
    	$author = get_user_by('id', $post->post_author);
    	echo $author->display_name.' ('.$author->user_login.')';
    }
    if ($column_name == 'col_crowd_recipients') {
		$recipients = get_post_meta( $post_ID, 'wps_crowd_recipients', true );
		if (!empty($recipients)):
			echo implode(",", $recipients);
		endif;    	
    }
}


?>