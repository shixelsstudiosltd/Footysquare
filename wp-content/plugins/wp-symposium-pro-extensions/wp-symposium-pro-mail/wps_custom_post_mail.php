<?php

/* Create Mail custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_mail() {
	$labels = array(
		'name'               => __( 'Messages', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Message', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New',  WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Message', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Message', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Message', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Messages', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Message', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Messages', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No messages found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No messages found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Message', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our messages specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title', 'thumbnail', 'editor', 'comments' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_mail', $args );
}
add_action( 'init', 'wps_custom_post_mail' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_mail_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_mail'] = array(
		0 => '', 
		1 => __('Mail updated.', WPS2_TEXT_DOMAIN),
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Mail updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('Mail restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Mail published.', WPS2_TEXT_DOMAIN),
		7 => __('Mail saved.', WPS2_TEXT_DOMAIN),
		8 => __('Mail submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('Mail scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Mail draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_mail_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'mail_info_box' );
function mail_info_box() {
    add_meta_box( 
        'mail_info_box',
        __( 'Mail Details', WPS2_TEXT_DOMAIN ),
        'mail_info_box_content',
        'wps_mail',
        'side',
        'high'
    );
}

function mail_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'mail_info_box_content', 'mail_info_box_content_nonce' );

	$author = get_user_by('id', $post->post_author);
	echo '<p>'.__('From:', WPS2_TEXT_DOMAIN).' '.$author->display_name.' ('.$author->user_login.')</p>';

	$recipients = get_post_meta( $post->ID, 'wps_mail_recipients', true );
	if (!empty($recipients)):
		$recipients_list = implode(",", $recipients);
	else:
		$recipients_list = '';
	endif;
	echo '<div style="margin-top:10px;font-weight:bold">'.__('Recipients (user logins)', WPS2_TEXT_DOMAIN).'</div>';
	echo '<textarea style="width:100%; height:200px;" id="wps_mail_recipients" name="wps_mail_recipients">'.$recipients_list.'</textarea>';

	$unread = get_post_meta( $post->ID, 'wps_mail_unread', true );
	if (!empty($unread)):
		$unread_list = implode(",", $unread);
	else:
		$unread_list = '';
	endif;
	echo '<div style="margin-top:10px;font-weight:bold">'.__('Unread (user logins)', WPS2_TEXT_DOMAIN).'</div>';
	echo '<textarea style="width:100%; height:200px;" id="wps_mail_unread" name="wps_mail_unread">'.$unread_list.'</textarea>';

}

add_action( 'save_post', 'mail_info_box_save' );
function mail_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['mail_info_box_content_nonce']) || !wp_verify_nonce( $_POST['mail_info_box_content_nonce'], 'mail_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	if ($_POST['wps_mail_recipients']):
		$recipients_list = explode(",", $_POST['wps_mail_recipients']);
		update_post_meta( $post_id, 'wps_mail_recipients', $recipients_list );
	else:
		delete_post_meta( $post_id, 'wps_mail_recipients' );
	endif;

	if ($_POST['wps_mail_unread']):
		$unread_list = explode(",", $_POST['wps_mail_unread']);
		update_post_meta( $post_id, 'wps_mail_unread', $unread_list );
	else:
		delete_post_meta( $post_id, 'wps_mail_unread' );
	endif;

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'mail_columns_head');
add_action('manage_posts_custom_column', 'mail_columns_content', 10, 2);

// ADD NEW COLUMN
function mail_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_mail') {
		$defaults['col_mail_author'] = 'Author';
		$defaults['col_mail_recipients'] = 'Recipients';
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function mail_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_mail_author') {
    	$post = get_post($post_ID);
    	$author = get_user_by('id', $post->post_author);
    	echo $author->display_name.' ('.$author->user_login.')';
    }
    if ($column_name == 'col_mail_recipients') {
		$recipients = get_post_meta( $post_ID, 'wps_mail_recipients', true );
		if (!empty($recipients)):
			echo implode(",", $recipients);
		endif;    	
    }
}

/* =========================== ALTER VIEW POST LINKS =========================== */

function wps_change_mail_link( $permalink, $post ) {

	if ($post->post_type == 'wps_mail'):

		global $wpdb;
		$sql = "SELECT ID FROM ".$wpdb->prefix."posts WHERE (post_content LIKE '%%[wps-mail %%' OR post_content LIKE '%%[wps-mail]%%') AND post_type = 'page' AND post_status = %s;";
		if ($pages = $wpdb->get_results($wpdb->prepare($sql, 'publish'))):
			$page = $pages[0];
			$url = get_permalink($page->ID);
			$permalink = $url.wps_query_mark($url).'mail='.$post->ID;
		endif;

	endif;

    return $permalink;

}
add_filter('post_type_link',"wps_change_mail_link",10,2);

?>