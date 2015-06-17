<?php

/* Create User Reward custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_reward() {
	$labels = array(
		'name'               => __( 'User Rewards', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'User Reward', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New', WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New User Reward', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit User Rewards', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New User Reward', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'User Rewards', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View User Rewards', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search User Rewards', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No user reward found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No user reward found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('User Reward', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our user reward specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title', 'editor' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_reward', $args );
}
add_action( 'init', 'wps_custom_post_reward' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_reward_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_reward'] = array(
		0 => '', 
		1 => __('User Reward updated.', WPS2_TEXT_DOMAIN),
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Reward updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('User Reward restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('User Reward published.', WPS2_TEXT_DOMAIN),
		7 => __('User Reward saved.', WPS2_TEXT_DOMAIN),
		8 => __('User Reward submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('User Reward scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('User Reward draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_reward_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'reward_info_box' );
function reward_info_box() {
    add_meta_box( 
        'reward_info_box',
        __( 'User Reward Details', WPS2_TEXT_DOMAIN ),
        'reward_info_box_content',
        'wps_reward',
        'side',
        'high'
    );
}

function reward_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'reward_info_box_content', 'reward_info_box_content_nonce' );
	?>
	<p><?php _e('The editor records why the user reward was given.', WPS2_TEXT_DOMAIN); ?></p>

	<p>
	<strong><?php _e('Value', WPS2_TEXT_DOMAIN); ?></strong><br />
	<input type="text" name="wps_reward_value" style="width:50px" value="<?php echo get_post_meta($post->ID, 'wps_reward_value', true); ?>" />
	</p>
	<?php
}

add_action( 'save_post', 'reward_info_box_save' );
function reward_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['reward_info_box_content_nonce']) || !wp_verify_nonce( $_POST['reward_info_box_content_nonce'], 'reward_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	update_post_meta( $post_id, 'wps_reward_value', $_POST['wps_reward_value'] );

}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'reward_columns_head');
add_action('manage_posts_custom_column', 'reward_columns_content', 10, 2);

// ADD NEW COLUMN
function reward_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_reward') {
		$defaults['col_reward_user'] = __('User', WPS2_TEXT_DOMAIN);
		$defaults['col_reward_type'] = __('Type', WPS2_TEXT_DOMAIN);
		$defaults['col_reward_value'] = __('Value', WPS2_TEXT_DOMAIN);
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function reward_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_reward_user') {
    	$the_post = get_post($post_ID);
    	$user = get_user_by ('id', $the_post->post_author );
    	echo $user->display_name;
    }
    if ($column_name == 'col_reward_type') {
    	echo get_post_meta($post_ID, 'wps_reward_type', true);
    }
    if ($column_name == 'col_reward_value') {
    	echo get_post_meta($post_ID, 'wps_reward_value', true);
    }
}

?>