<?php
// AJAX functions for crowds
add_action( 'wp_ajax_wps_crowds_get_ajax', 'wps_crowds_get_ajax' ); 
add_action( 'wp_ajax_wps_crowds_get_friends', 'wps_crowds_get_friends' ); 
add_action( 'wp_ajax_wps_crowds_create', 'wps_crowds_create' ); 
add_action( 'wp_ajax_wps_crowds_update', 'wps_crowds_update' ); 
add_action( 'wp_ajax_wps_crowds_delete', 'wps_crowds_delete' ); 

/* DELETE CROWD */
function wps_crowds_delete() {

	$the_post = $_POST;
	if (isset($the_post['id']) && $the_post['id'] != ''):

		$my_post = array(
		      'ID'         	=> $the_post['id'],
		      'post_status' => 'pending'
		);
		wp_update_post( $my_post );	

	else:
		echo __('Error: no ID passed', WPS2_TEXT_DOMAIN);
	endif;
	exit();
}

/* UPDATE CROWD */
function wps_crowds_update() {

	$the_post = $_POST;
	if (isset($the_post['id']) && $the_post['id'] != ''):
		if (isset($the_post['title']) && $the_post['title'] != ''):
			if (isset($the_post['recipients']) && $the_post['recipients'] != ''):

				$my_post = array(
				      'ID'         	=> $the_post['id'],
				      'post_title' 	=> $the_post['title'],
				      'post_name'	=> sanitize_title_with_dashes($the_post['title']),
				);
				wp_update_post( $my_post );	

				update_post_meta( $the_post['id'], 'wps_crowd_recipients', $the_post['recipients'] );

				echo $the_post['id'].' '.$the_post['title'].' '.$the_post['recipients'];

			else:
				echo __('Select one or more recipients!', WPS2_TEXT_DOMAIN);
			endif;
		else:
			echo __('Please enter a title!', WPS2_TEXT_DOMAIN);
		endif;
	else:
		echo __('Error: no ID passed', WPS2_TEXT_DOMAIN);
	endif;
	exit();
}

/* CREATE NEW CROWD */
function wps_crowds_create() {

	$the_post = $_POST;
	if (isset($the_post['title']) && $the_post['title'] != ''):
		if (isset($the_post['recipients']) && $the_post['recipients'] != ''):

			global $current_user;
			$post = array(
			  'post_title'     => $the_post['title'],
			  'post_content'   => '',
			  'post_status'    => 'publish',
			  'author'		   => $current_user->ID,
			  'post_type'      => 'wps_crowd',
			  'post_author'    => $current_user->ID,
			  'ping_status'    => 'closed',
			  'comment_status' => 'closed',
			);  
			$new_id = wp_insert_post( $post );

			update_post_meta( $new_id, 'wps_crowd_recipients', $the_post['recipients'] );

		endif;
	endif;
	exit();
}

/* FORM TO CREATE OR EDIT CROWD */
function wps_crowds_get_friends() {
	global $current_user;
	$friends = wps_get_friends($current_user->ID);
	$form_html = '';
	if ($friends):
		if ($_POST['populate']):
			$crowd = get_post($_POST['populate']);
			$title = $crowd->post_title;
			$populate = get_post_meta($_POST['populate'], 'wps_crowd_recipients', true);
		else:
			$title = '';
			$populate = array();
		endif;
		$form_html .= '<div class="wps_crowds_item">'.__('Enter a name for your list:', WPS2_TEXT_DOMAIN).'</div>';
		$form_html .= '<input type="text" id="wps_crowd_title" value="'.$title.'">';
		$form_html .= '<div class="wps_crowds_item">'.__('Select from your friends...', WPS2_TEXT_DOMAIN).'</div>';
		$form_html .= '<select multiple="multiple" id="wps_crowd_recipients" name="wps_crowd_recipients[]" style="width:100%">';
		foreach ($friends as $friend):
			$user = get_user_by('id', $friend['ID']);
			$selected = in_array($user->user_login, $populate) ? 'selected' : '';
			$form_html .= sprintf('<option %s value="%s">%s</option>', $selected, $user->user_login, $user->display_name);
		endforeach;
		$form_html .= '</select>';
	else:
		$form_html .= __('Make some friends first...', WPS2_TEXT_DOMAIN);
	endif;
	$form_html .= '<input id="wps_crowd_cancel" type="submit" class="wps_submit" value="'.__('Cancel', WPS2_TEXT_DOMAIN).'" />';
	if ($_POST['populate']):
		$form_html .= '<input id="wps_crowd_update" rel="'.$_POST['populate'].'" type="submit" class="wps_submit" value="'.__('Update', WPS2_TEXT_DOMAIN).'" />';
	else:
		$form_html .= '<input id="wps_crowd_create" type="submit" class="wps_submit" value="'.__('Create', WPS2_TEXT_DOMAIN).'" />';
	endif;

	echo $form_html;
	exit();
}

/* GET CROWDS CREATED BY USER */
function wps_crowds_get_ajax() {
	
	$current_user_id = $_POST['user_id'];

	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'post_title',
		'order'            => 'ASC',
		'post_type'        => 'wps_crowd',
		'post_status'      => 'publish',
		'author'		 	=> $current_user_id,
	);
	$crowds = get_posts($args);

	$html = '<input id="wps_crowd_close" style="margin-bottom:10px;float:right" type="submit" value="'.__('Close', WPS2_TEXT_DOMAIN).'" />';
	$html .= '<div class="wps_crowds_item"><a id="manage_crowds_create" href="javascript:void(0);">'.__('Create new list...', WPS2_TEXT_DOMAIN).'</a></div>';
	if ($crowds):
		foreach ($crowds as $crowd):
			$html .= '<div class="wps_crowds_item wps_crowds_item_row">';
				$html .= '<div class="wps_crowds_item_title">'.$crowd->post_title.'</div>';
				$html .= '<div class="wps_crowds_item_delete" rel="'.$crowd->ID.'"><a href="javascript:void(0)">'.__('Delete', WPS2_TEXT_DOMAIN).'</a></div>';
				$html .= '<div class="wps_crowds_item_edit" rel="'.$crowd->ID.'"><a href="javascript:void(0)">'.__('Edit', WPS2_TEXT_DOMAIN).'</a></div>';
			$html .= '</div>';
		endforeach;
	endif;

	echo $html;
	exit();

}


?>
