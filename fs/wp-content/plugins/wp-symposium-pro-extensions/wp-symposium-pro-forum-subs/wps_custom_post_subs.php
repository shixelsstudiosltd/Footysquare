<?php

/* Create Subscriptions custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_subs() {
	$labels = array(
		'name'               => __( 'Post Subscriptions', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Post Subscription', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New' , WPS2_TEXT_DOMAIN),
		'add_new_item'       => __( 'Add New Subscription', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Subscription', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Subscription', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Subscriptions', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Subscription', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Subscriptions', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No subscription found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No subscription found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Post Subscriptions', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our subscription specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_subs', $args );
}
add_action( 'init', 'wps_custom_post_subs' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_subs_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_subs'] = array(
		0 => '', 
		1 => __('Subscription updated.', WPS2_TEXT_DOMAIN),
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Subscription updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('Subscription restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Subscription published.', WPS2_TEXT_DOMAIN),
		7 => __('Subscription saved.', WPS2_TEXT_DOMAIN),
		8 => __('Subscription submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('Subscription scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Subscription draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_subs_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'subs_info_box' );
function subs_info_box() {
    add_meta_box( 
        'subs_info_box',
        __( 'Subscription Details', WPS2_TEXT_DOMAIN ),
        'subs_info_box_content',
        'wps_subs',
        'normal',
        'high'
    );
}

function subs_info_box_content( $post ) {

	wp_nonce_field( 'subs_info_box_content', 'subs_info_box_content_nonce' );

	echo '<div style="margin-top:10px;font-weight:bold">'.__('User', WPS2_TEXT_DOMAIN).'</div>';
	$author = get_user_by( 'id', $post->post_author );
	echo '<input type="text" id="wps_user" name="wps_user" placeholder="Select user..." value="'.$author->user_login.'" />';

	echo '<div style="margin-top:10px;font-weight:bold">'.__('Post ID', WPS2_TEXT_DOMAIN).'</div>';
	echo '<input type="text" id="wps_post_id" name="wps_post_id" style="width:60px" placeholder="" value="'.$post->wps_post_id.'" />';

	if ($post->wps_post_id):
		$the_post = get_post($post->wps_post_id);
		echo '<p><strong>'.__('Title:', WPS2_TEXT_DOMAIN).'</strong> '.$the_post->post_title.'</p>';
		echo '<p><strong>'.__('Author:', WPS2_TEXT_DOMAIN).'</strong> '.wps_display_name(array('user_id'=>$the_post->post_author, 'link'=>'1')).'</p>';
	endif;

}

add_action( 'save_post', 'subs_info_box_save' );
function subs_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['subs_info_box_content_nonce']) || !wp_verify_nonce( $_POST['subs_info_box_content_nonce'], 'subs_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	update_post_meta( $post_id, 'wps_post_id', $_POST['wps_post_id'] );

	$user = get_user_by( 'login', $_POST['wps_user'] );

	remove_action( 'save_post', 'subs_info_box_save' );
	$my_post = array(
	      'ID'         	=> $post_id,
	      'post_title' 	=> $user->user_login,
	      'post_name'	=> sanitize_title_with_dashes($user->user_login),
	      'post_type'	=> 'wps_subs',
	      'post_author'	=> $user->ID,
	      'post_status'	=> 'publish'
	);
	wp_update_post( $my_post );			
	add_action( 'save_post', 'subs_info_box_save' );

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'subs_columns_head');
add_action('manage_posts_custom_column', 'subs_columns_content', 10, 2);

// ADD NEW COLUMN
function subs_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_subs') {
		$defaults['col_user'] = 'User';
		$defaults['col_post_id'] = 'Post';
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function subs_columns_content($column_name, $post_ID) {

    if ($column_name == 'col_user') {
    	$post = get_post($post_ID);
    	$user = get_user_by ('id', $post->post_author );
    	echo $user->display_name;
    }

    if ($column_name == 'col_post_id') {
    	$the_post_id = get_post_meta( $post_ID, 'wps_post_id', true );
    	$the_post = get_post($the_post_id);
		$content = preg_replace('#<[^>]+>#', ' ', $the_post->post_title);
		$max_len = 100;
		
		$post_terms = wp_get_object_terms( $the_post_id, 'wps_forum' );
		$term = $post_terms[0];
		$forum_page = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
		$forum_url = get_permalink($forum_page);
		$url = $forum_url.$the_post->post_name;
		if (strlen($content) > $max_len) $content = substr($content, 0, $max_len).'...';
		echo '<a href="'.$url.'">';
		echo $content;
		echo '</a>';
	}

}

?>