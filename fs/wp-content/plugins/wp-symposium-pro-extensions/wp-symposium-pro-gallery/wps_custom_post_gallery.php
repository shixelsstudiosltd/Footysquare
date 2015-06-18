<?php

/* Create Gallery custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_gallery() {
	$labels = array(
		'name'               => __( 'Galleries', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Gallery', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New',  WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Gallery', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Gallery', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Gallery', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Galleries', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Galleries', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Galleries', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No galleries found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No galleries found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Galleries', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our gallery specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title', 'thumbnail', 'editor', 'comments' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_gallery', $args );
}
add_action( 'init', 'wps_custom_post_gallery' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_gallery_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_Gallery'] = array(
		0 => '', 
		1 => __('Gallery updated.', WPS2_TEXT_DOMAIN),
		2 => __('Gallery  updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Gallery updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('Gallery restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Gallery published.', WPS2_TEXT_DOMAIN),
		7 => __('Gallery saved.', WPS2_TEXT_DOMAIN),
		8 => __('Gallery submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('Gallery scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Gallery draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_gallery_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'gallery_info_box' );
function gallery_info_box() {
    add_meta_box( 
        'gallery_info_box',
        __( 'Gallery Images', WPS2_TEXT_DOMAIN ),
        'gallery_info_box_content',
        'wps_gallery',
        'side',
        'high'
    );
}

function gallery_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'gallery_info_box_content', 'gallery_info_box_content_nonce' );

	$author = get_user_by('id', $post->post_author);
	echo '<p>'.__('Owner:', WPS2_TEXT_DOMAIN).' '.$author->display_name.' ('.$author->user_login.')</p>';
}

add_action( 'save_post', 'gallery_info_box_save' );
function gallery_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['gallery_info_box_content_nonce']) || !wp_verify_nonce( $_POST['gallery_info_box_content_nonce'], 'gallery_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'gallery_columns_head');
add_action('manage_posts_custom_column', 'gallery_columns_content', 10, 2);

// ADD NEW COLUMN
function gallery_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_gallery') {
		$defaults['col_gallery_author'] = 'Author';
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function gallery_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_gallery_author') {
    	$post = get_post($post_ID);
    	$author = get_user_by('id', $post->post_author);
    	echo $author->display_name.' ('.$author->user_login.')';
    }
}


?>