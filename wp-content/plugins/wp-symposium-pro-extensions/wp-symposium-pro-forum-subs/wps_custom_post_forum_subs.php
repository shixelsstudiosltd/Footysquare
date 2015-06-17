<?php

/* Create forum_subscriptions custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_forum_subs() {
	$labels = array(
		'name'               => __( 'Forum Subcriptions', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Forum Subscription', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New' , WPS2_TEXT_DOMAIN),
		'add_new_item'       => __( 'Add New Forum Subscription', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Forum Subscription', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Forum Subscription', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Forum Subscriptions', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View  Forum Subscription', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Forum Subscriptions', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No Forum Subscription found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No Forum Subscription found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Forum Subscriptions', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our forum subscription specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_forum_subs', $args );
}
add_action( 'init', 'wps_custom_post_forum_subs' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_forum_subs_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_forum_subs'] = array(
		0 => '', 
		1 => __('Forum Subscription updated.', WPS2_TEXT_DOMAIN),
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Forum Subscription updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('Forum Subscription restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Forum Subscription published.', WPS2_TEXT_DOMAIN),
		7 => __('Forum Subscription saved.', WPS2_TEXT_DOMAIN),
		8 => __('Forum Subscription submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('Forum Subscription scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Forum Subscription draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_forum_subs_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'forum_subs_info_box' );
function forum_subs_info_box() {
    add_meta_box( 
        'forum_subs_info_box',
        __( 'Forum Subscription Details', WPS2_TEXT_DOMAIN ),
        'forum_subs_info_box_content',
        'wps_forum_subs',
        'normal',
        'high'
    );
}

function forum_subs_info_box_content( $post ) {

	wp_nonce_field( 'forum_subs_info_box_content', 'forum_subs_info_box_content_nonce' );

	echo '<div style="margin-top:10px;font-weight:bold">'.__('User', WPS2_TEXT_DOMAIN).'</div>';
	$author = get_user_by( 'id', $post->post_author );
	echo '<input type="text" id="wps_user" name="wps_user" placeholder="Select user..." value="'.$author->user_login.'" />';

	echo '<div style="margin-top:10px;font-weight:bold">'.__('Forum', WPS2_TEXT_DOMAIN).'</div>';

	$terms = get_terms( "wps_forum", array( ) );

	if ( count($terms) > 0 ):

		echo '<select name="wps_forum_id">';

			foreach ( $terms as $term ):

				echo '<option value="'.$term->term_id.'"';
					if ($term->term_id == $post->wps_forum_id) echo ' SELECTED';
					echo'>'.$term->name.'</option>';

			endforeach;

		echo '</select>';
	else:
		echo '<a href="edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post">'.__('Add at least one forum').'</a>';
	endif;

}

add_action( 'save_post', 'forum_subs_info_box_save' );
function forum_subs_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['forum_subs_info_box_content_nonce']) || !wp_verify_nonce( $_POST['forum_subs_info_box_content_nonce'], 'forum_subs_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	update_post_meta( $post_id, 'wps_forum_id', $_POST['wps_forum_id'] );

	$user = get_user_by( 'login', $_POST['wps_user'] );

	remove_action( 'save_post', 'forum_subs_info_box_save' );
	$my_post = array(
	      'ID'         	=> $post_id,
	      'post_title' 	=> $user->user_login,
	      'post_name'	=> sanitize_title_with_dashes($user->user_login),
	      'post_type'	=> 'wps_forum_subs',
	      'post_author'	=> $user->ID,
	      'post_status'	=> 'publish'
	);
	wp_update_post( $my_post );			
	add_action( 'save_post', 'forum_subs_info_box_save' );

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'forum_subs_columns_head');
add_action('manage_posts_custom_column', 'forum_subs_columns_content', 10, 2);

// ADD NEW COLUMN
function forum_subs_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_forum_subs') {
		$defaults['col_display_name'] = 'User';
		$defaults['col_forum'] = 'Forum';
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function forum_subs_columns_content($column_name, $post_ID) {

    if ($column_name == 'col_display_name') {
    	$post = get_post($post_ID);
    	$user = get_user_by ('id', $post->post_author );
    	echo $user->display_name;
    }

    if ($column_name == 'col_forum') {
		$wps_forum_id = get_post_meta ($post_ID, 'wps_forum_id', true);
		$term = get_term($wps_forum_id, 'wps_forum');
		echo $term->name;
	}

}

?>