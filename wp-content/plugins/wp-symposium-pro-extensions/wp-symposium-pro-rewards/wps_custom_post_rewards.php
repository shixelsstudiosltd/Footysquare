<?php

/* Create Rewards custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_rewards() {
	$labels = array(
		'name'               => __( 'Rewards', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Reward', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New', WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Reward', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Rewards', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Reward', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Rewards', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Rewards', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Rewards', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No reward found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No reward found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Rewards', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our reward specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title', 'editor', 'thumbnail' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_rewards', $args );
}
add_action( 'init', 'wps_custom_post_rewards' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_rewards_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_reward'] = array(
		0 => '', 
		1 => __('Reward updated.', WPS2_TEXT_DOMAIN),
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Reward updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('Reward restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Reward published.', WPS2_TEXT_DOMAIN),
		7 => __('Reward saved.', WPS2_TEXT_DOMAIN),
		8 => __('Reward submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('Reward scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Reward draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_rewards_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'rewards_info_box' );
function rewards_info_box() {
    add_meta_box( 
        'rewards_info_box',
        __( 'Reward Details', WPS2_TEXT_DOMAIN ),
        'rewards_info_box_content',
        'wps_rewards',
        'side',
        'high'
    );
}

function rewards_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'rewards_info_box_content', 'rewards_info_box_content_nonce' );
	?>
	<p><?php _e('Use the editor to enter a description for users.', WPS2_TEXT_DOMAIN); ?></p>
	<p>
	<strong><?php _e('Type', WPS2_TEXT_DOMAIN); ?></strong><br />
	<select name="wps_rewards_type" id="wps_rewards_type">
		<option value="activity_new"<?php if (get_post_meta($post->ID, 'wps_rewards_type', true) == 'activity_new') echo ' SELECTED'; ?>><?php _e('New activity post to all friends', WPS2_TEXT_DOMAIN); ?></option>
		<option value="activity_new_other"<?php if (get_post_meta($post->ID, 'wps_rewards_type', true) == 'activity_new_other') echo ' SELECTED'; ?>><?php _e('New activity to other member(s)', WPS2_TEXT_DOMAIN); ?></option>
		<option value="activity_reply"<?php if (get_post_meta($post->ID, 'wps_rewards_type', true) == 'activity_reply') echo ' SELECTED'; ?>><?php _e('Reply to activity', WPS2_TEXT_DOMAIN); ?></option>
		<option value="forum_new"<?php if (get_post_meta($post->ID, 'wps_rewards_type', true) == 'forum_new') echo ' SELECTED'; ?>><?php _e('Post to forum', WPS2_TEXT_DOMAIN); ?></option>
		<option value="forum_reply"<?php if (get_post_meta($post->ID, 'wps_rewards_type', true) == 'forum_reply') echo ' SELECTED'; ?>><?php _e('Reply to a forum post', WPS2_TEXT_DOMAIN); ?></option>
		<option value="friendship"<?php if (get_post_meta($post->ID, 'wps_rewards_type', true) == 'friendship') echo ' SELECTED'; ?>><?php _e('Have a friendship request accepted', WPS2_TEXT_DOMAIN); ?></option>
		<option value="post"<?php if (get_post_meta($post->ID, 'wps_rewards_type', true) == 'post') echo ' SELECTED'; ?>><?php _e('Add a new blog post', WPS2_TEXT_DOMAIN); ?></option>
		<?php
		// Any more options
   		do_action( 'rewards_info_box_content_options_filter', $post->ID );
   		?>
		<option value="count"<?php if (get_post_meta($post->ID, 'wps_rewards_type', true) == 'count') echo ' SELECTED'; ?>><?php _e('Badge for post count', WPS2_TEXT_DOMAIN); ?></option>
	</select>
	</p>

	<div id="wps_type_action"<?php if (get_post_meta($post->ID, 'wps_rewards_type', true) == 'count') echo ' style="display:none"'; ?>>
		<p>
		<strong><?php _e('Default Value', WPS2_TEXT_DOMAIN); ?></strong><br />
		<input type="text" name="wps_rewards_value" style="width:50px" value="<?php echo get_post_meta($post->ID, 'wps_rewards_value', true); ?>" />
		</p>
	</div>

	<div id="wps_type_count"<?php if (get_post_meta($post->ID, 'wps_rewards_type', true) != 'count') echo ' style="display:none"'; ?>>
		<p>
		<strong><?php _e('Type of reward to count', WPS2_TEXT_DOMAIN); ?></strong><br />
		<select name="wps_rewards_count_type" id="wps_rewards_count_type">
			<option value="activity_new"<?php if (get_post_meta($post->ID, 'wps_rewards_count_type', true) == 'activity_new') echo ' SELECTED'; ?>><?php _e('New activity post to all friends', WPS2_TEXT_DOMAIN); ?></option>
			<option value="activity_new_other"<?php if (get_post_meta($post->ID, 'wps_rewards_count_type', true) == 'activity_new_other') echo ' SELECTED'; ?>><?php _e('New activity to other member(s)', WPS2_TEXT_DOMAIN); ?></option>
			<option value="activity_reply"<?php if (get_post_meta($post->ID, 'wps_rewards_count_type', true) == 'activity_reply') echo ' SELECTED'; ?>><?php _e('Reply to activity', WPS2_TEXT_DOMAIN); ?></option>
			<option value="forum_new"<?php if (get_post_meta($post->ID, 'wps_rewards_count_type', true) == 'forum_new') echo ' SELECTED'; ?>><?php _e('Post to forum', WPS2_TEXT_DOMAIN); ?></option>
			<option value="forum_reply"<?php if (get_post_meta($post->ID, 'wps_rewards_count_type', true) == 'forum_reply') echo ' SELECTED'; ?>><?php _e('Reply to a forum post', WPS2_TEXT_DOMAIN); ?></option>
			<option value="friendship"<?php if (get_post_meta($post->ID, 'wps_rewards_count_type', true) == 'friendship') echo ' SELECTED'; ?>><?php _e('Accepted friendship requests', WPS2_TEXT_DOMAIN); ?></option>
			<option value="post"<?php if (get_post_meta($post->ID, 'wps_rewards_count_type', true) == 'post') echo ' SELECTED'; ?>><?php _e('Add a new blog post', WPS2_TEXT_DOMAIN); ?></option>
			<?php
			// Any more options
	   		do_action( 'rewards_info_box_content_options_count_filter', $post->ID );
	   		?>
		</select>
		</p>
		<p>
		<strong><?php _e('Post count', WPS2_TEXT_DOMAIN); ?></strong><br />
		<?php _e('The minimum number of times this type of reward has been awarded.', WPS2_TEXT_DOMAIN); ?><br />
		<input type="text" name="wps_rewards_count" style="width:50px" value="<?php echo get_post_meta($post->ID, 'wps_rewards_count', true); ?>" />
		</p>
	</div>

	<p>
	<?php _e('To use a badge, upload as a featured image, and set a pixel size (square).', WPS2_TEXT_DOMAIN); ?><br />
	<input type="text" name="wps_rewards_size" style="width:50px" value="<?php echo get_post_meta($post->ID, 'wps_rewards_size', true); ?>" />px
	</p>
	
	<?php
}

add_action( 'save_post', 'rewards_info_box_save' );
function rewards_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['rewards_info_box_content_nonce']) || !wp_verify_nonce( $_POST['rewards_info_box_content_nonce'], 'rewards_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	update_post_meta( $post_id, 'wps_rewards_type', $_POST['wps_rewards_type'] );
	update_post_meta( $post_id, 'wps_rewards_value', $_POST['wps_rewards_value'] );
	update_post_meta( $post_id, 'wps_rewards_size', $_POST['wps_rewards_size'] );
	update_post_meta( $post_id, 'wps_rewards_count_type', $_POST['wps_rewards_count_type'] );
	update_post_meta( $post_id, 'wps_rewards_count', $_POST['wps_rewards_count'] );
}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'rewards_columns_head');
add_action('manage_posts_custom_column', 'rewards_columns_content', 10, 2);

// ADD NEW COLUMN
function rewards_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_rewards') {
		$defaults['col_rewards_slug'] = __('Slug', WPS2_TEXT_DOMAIN);
		$defaults['col_rewards_type'] = __('Type', WPS2_TEXT_DOMAIN);
		$defaults['col_rewards_value'] = __('Value', WPS2_TEXT_DOMAIN);
		$defaults['col_rewards_badge'] = __('Badge', WPS2_TEXT_DOMAIN);
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function rewards_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_rewards_slug') {
    	$the_post = get_post($post_ID);
    	echo $the_post->post_name;
    }
    if ($column_name == 'col_rewards_type') {
    	if (get_post_meta($post_ID, 'wps_rewards_type', true) != 'count'):
	    	echo get_post_meta($post_ID, 'wps_rewards_type', true);
    	else:
	    	echo sprintf(__('Count of %s reward type', WPS2_TEXT_DOMAIN), '<strong>'.get_post_meta($post_ID, 'wps_rewards_count_type', true).'</strong>');
    	endif;
    }
    if ($column_name == 'col_rewards_value') {
    	if (get_post_meta($post_ID, 'wps_rewards_type', true) != 'count'):
	    	echo get_post_meta($post_ID, 'wps_rewards_value', true);
    	else:
	    	echo get_post_meta($post_ID, 'wps_rewards_count', true).' ('.__('count', WPS2_TEXT_DOMAIN).')';
    	endif;
    }
    if ($column_name == 'col_rewards_badge') {
		if (has_post_thumbnail($post_ID)):
			$image_id = get_post_thumbnail_id($post_ID);
			$image_attributes = wp_get_attachment_image_src( $image_id, 'thumbnail');
			echo '<img src="'.$image_attributes[0].'" style="width:32px; height:32px;" />'; 
		endif;
    }


					   
}

?>