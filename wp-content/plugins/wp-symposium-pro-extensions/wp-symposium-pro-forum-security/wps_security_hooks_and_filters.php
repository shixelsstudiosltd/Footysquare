<?php

// Check timedout settings
add_filter('wps_forum_post_timed_out_filter', 'wps_user_has_timed_out',10,5);
function wps_user_has_timed_out($timed_out, $user_id, $age, $timeout, $term_id) {

	global $wpdb;
	$user = get_userdata($user_id);
	if ($saved_roles = wps_get_term_meta( $term_id, 'wps_forum_roles_timeout' , true)):
		if ($user):
			$capabilities = $user->{$wpdb->prefix . 'capabilities'};		

			$found = false;
			if ($saved_roles && $capabilities):
				foreach ($capabilities as $key => $value):
					if (in_array($key, $saved_roles)) {
						$found = true;
					}
				endforeach;
			endif;
			if ($found):
				return false; // not timed out, as one of selected roles
			else:
				return $timed_out; // role not found, so just return passed value
			endif;
		else:
			return true; // no user, so timed out by default
		endif;
	else:
		return $timed_out; // no timed out settings, so just return passed value
	endif;

}

// Add roles to forum setup edit form
add_action('wpspro_forum_setup_after', 'wps_forum_taxonomy_metadata_edit_roles', 10, 1);
add_action('wps_forum_taxonomy_metadata_edit_hook', 'wps_forum_taxonomy_metadata_edit_roles', 10, 1);
function wps_forum_taxonomy_metadata_edit_roles($tag) {

	if (!isset($_GET['page'])): ?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<h2><?php _e('Security', WPS2_TEXT_DOMAIN); ?></h2>
			</th>
			<td colspan="4"></td>
		</tr> 
	<?php 
		$display = '';
	else:
		$display = 'style="display:none"';
		echo '<tr><td colspan="5">';
			echo '<a id="wps_show_forum_security_'.$tag->term_id.'" class="wps_show_forum_security" rel="'.$tag->term_id.'" href="javascript:void(0)">'.__('Show Security', WPS2_TEXT_DOMAIN).'</a>';
			echo '<a id="wps_hide_forum_security_'.$tag->term_id.'" class="wps_hide_forum_security" rel="'.$tag->term_id.'" href="javascript:void(0)" style="display:none">'.__('Hide Security', WPS2_TEXT_DOMAIN).'</a>';
		echo '</td></tr>';
	endif; 

	?>
	<tr class="form-field wps_forum_security_<?php echo $tag->term_id; ?>" <?php echo $display; ?>>
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Viewing', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td colspan="4">
			<?php

			$saved_roles = wps_get_term_meta( $tag->term_id, 'wps_forum_roles' , true);

		    global $wp_roles;
			$roles = $wp_roles->get_names();
			foreach ( $wp_roles->roles as $key=>$value ):
				if ($key != 'administrator'):
					echo '<input type="checkbox" id="wps_taxonomy_metadata_edit_role_'.$key.'" style="width:10px" name="wps_taxonomy_metadata_edit_role_'.$tag->term_id.'[]" ';
					if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
					echo 'value="'.$key.'"> <label for="wps_taxonomy_metadata_edit_role_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
				endif;
			endforeach;
			?>
			<span class="description"><?php _e('Roles that are allowed to use the forum when logged in. No selection means all users. Administrators can always view.', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 
	<tr class="form-field wps_forum_security_<?php echo $tag->term_id; ?>" <?php echo $display; ?>>
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Creating', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td colspan="4">
			<?php

			$saved_roles = wps_get_term_meta( $tag->term_id, 'wps_forum_roles_create' , true);

		    global $wp_roles;
			$roles = $wp_roles->get_names();
			foreach ( $wp_roles->roles as $key=>$value ):
				if ($key != 'administrator'):
					echo '<input type="checkbox" id="wps_taxonomy_metadata_edit_role_create_'.$key.'" style="width:10px" name="wps_taxonomy_metadata_edit_role_create_'.$tag->term_id.'[]" ';
					if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
					echo 'value="'.$key.'"> <label for="wps_taxonomy_metadata_edit_role_create_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
				endif;
			endforeach;
			?>
			<span class="description"><?php _e('Roles that are allowed to create a new post on the forum when logged in. No selection means all users. Administrators can always create.', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 	
	<tr class="form-field wps_forum_security_<?php echo $tag->term_id; ?>" <?php echo $display; ?>>
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Editing', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td colspan="4">
			<?php

			$saved_roles = wps_get_term_meta( $tag->term_id, 'wps_forum_roles_edit' , true);

		    global $wp_roles;
			$roles = $wp_roles->get_names();
			foreach ( $wp_roles->roles as $key=>$value ):
				if ($key != 'administrator'):
					echo '<input type="checkbox" id="wps_taxonomy_metadata_edit_role_edit_'.$key.'" style="width:10px" name="wps_taxonomy_metadata_edit_role_edit_'.$tag->term_id.'[]" ';
					if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
					echo 'value="'.$key.'"> <label for="wps_taxonomy_metadata_edit_role_edit_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
				endif;
			endforeach;
			?>
			<span class="description"><?php _e('Roles that are allowed to edit posts/replies on the forum when logged in. No selection means post/reply owner or administrators. Administrators can always edit.', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 	
	<tr class="form-field wps_forum_security_<?php echo $tag->term_id; ?>" <?php echo $display; ?>>
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Replying and<br />Commenting', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td colspan="4">
			<?php

			$saved_roles = wps_get_term_meta( $tag->term_id, 'wps_forum_roles_comment' , true);

		    global $wp_roles;
			$roles = $wp_roles->get_names();
			foreach ( $wp_roles->roles as $key=>$value ):
				if ($key != 'administrator'):
					echo '<input type="checkbox" id="wps_taxonomy_metadata_edit_role_comment_'.$key.'" style="width:10px" name="wps_taxonomy_metadata_edit_role_comment_'.$tag->term_id.'[]" ';
					if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
					echo 'value="'.$key.'"> <label for="wps_taxonomy_metadata_edit_role_comment_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
				endif;
			endforeach;
			?>
			<span class="description"><?php _e('Roles that are allowed to reply/comment on the forum when logged in. No selection means all logged in users. Administrators can always edit.', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 	
    <tr class="form-field wps_forum_security_<?php echo $tag->term_id; ?>" <?php echo $display; ?>>
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Deleting', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td colspan="4">
			<?php

			$saved_roles = wps_get_term_meta( $tag->term_id, 'wps_forum_roles_delete' , true);

		    global $wp_roles;
			$roles = $wp_roles->get_names();
			foreach ( $wp_roles->roles as $key=>$value ):
				if ($key != 'administrator'):
					echo '<input type="checkbox" id="wps_taxonomy_metadata_edit_role_delete_'.$key.'" style="width:10px" name="wps_taxonomy_metadata_edit_role_delete_'.$tag->term_id.'[]" ';
					if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
					echo 'value="'.$key.'"> <label for="wps_taxonomy_metadata_edit_role_delete_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
				endif;
			endforeach;
			?>
			<span class="description"><?php _e('Roles that are allowed to delete posts/replies on the forum when logged in. No selection means post/reply owner or administrators. Administrators can always delete.', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 	
	<tr class="form-field wps_forum_security_<?php echo $tag->term_id; ?>" <?php echo $display; ?>>
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Moving', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td colspan="4">
			<?php

			$saved_roles = wps_get_term_meta( $tag->term_id, 'wps_forum_roles_move' , true);

		    global $wp_roles;
			$roles = $wp_roles->get_names();
			foreach ( $wp_roles->roles as $key=>$value ):
				if ($key != 'administrator'):
					echo '<input type="checkbox" id="wps_taxonomy_metadata_edit_role_move_'.$key.'" style="width:10px" name="wps_taxonomy_metadata_edit_role_move_'.$tag->term_id.'[]" ';
					if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
					echo 'value="'.$key.'"> <label for="wps_taxonomy_metadata_edit_role_move_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
				endif;
			endforeach;
			?>
			<span class="description"><?php _e('Roles that are allowed to move posts on the forum when logged in. No selection means post owner or administrators. Administrators can always move.', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 		
	<tr class="form-field wps_forum_security_<?php echo $tag->term_id; ?>" <?php echo $display; ?>>
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Timeout', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td colspan="4">
			<?php

			$saved_roles = wps_get_term_meta( $tag->term_id, 'wps_forum_roles_timeout' , true);

		    global $wp_roles;
			$roles = $wp_roles->get_names();
			foreach ( $wp_roles->roles as $key=>$value ):
				if ($key != 'administrator'):
					echo '<input type="checkbox" id="wps_taxonomy_metadata_edit_role_timeout_'.$key.'" style="width:10px" name="wps_taxonomy_metadata_edit_role_timeout_'.$tag->term_id.'[]" ';
					if ($saved_roles && in_array($key, $saved_roles)) echo 'CHECKED ';
					echo 'value="'.$key.'"> <label for="wps_taxonomy_metadata_edit_role_timeout_'.$key.'">'.$value['name'].' ('.$key.')</label><br />';
				endif;
			endforeach;
			?>
			<span class="description"><?php _e('Roles that ignore the timeout value. No selection means all apart from administrators. Administrators always ignore timeouts.', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 
	<?php
}

add_action('wps_forum_taxonomy_metadata_edit_roles_save_hook', 'wps_forum_taxonomy_metadata_edit_roles_save_roles', 10, 2);
function wps_forum_taxonomy_metadata_edit_roles_save_roles($term_id, $the_post) {

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_'.$term_id])):
	    
	    foreach($the_post['wps_taxonomy_metadata_edit_role_'.$term_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $term_id, 'wps_forum_roles', $roles );

	else:

		wps_delete_term_meta( $term_id, 'wps_forum_roles' );

	endif;

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_create_'.$term_id])):
	    
	    foreach($the_post['wps_taxonomy_metadata_edit_role_create_'.$term_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $term_id, 'wps_forum_roles_create', $roles );

	else:

		wps_delete_term_meta( $term_id, 'wps_forum_roles_create' );

	endif;

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_edit_'.$term_id])):
	    
	    foreach($the_post['wps_taxonomy_metadata_edit_role_edit_'.$term_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $term_id, 'wps_forum_roles_edit', $roles );

	else:

		wps_delete_term_meta( $term_id, 'wps_forum_roles_edit' );

	endif;

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_comment_'.$term_id])):
	    
	    foreach($the_post['wps_taxonomy_metadata_edit_role_comment_'.$term_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $term_id, 'wps_forum_roles_comment', $roles );

	else:

		wps_delete_term_meta( $term_id, 'wps_forum_roles_comment' );

	endif;

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_delete_'.$term_id])):
	    
	    foreach($the_post['wps_taxonomy_metadata_edit_role_delete_'.$term_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $term_id, 'wps_forum_roles_delete', $roles );

	else:

		wps_delete_term_meta( $term_id, 'wps_forum_roles_delete' );

	endif;

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_move_'.$term_id])):
	    
	    foreach($the_post['wps_taxonomy_metadata_edit_role_move_'.$term_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $term_id, 'wps_forum_roles_move', $roles );

	else:

		wps_delete_term_meta( $term_id, 'wps_forum_roles_move' );

	endif;

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_timeout_'.$term_id])):
	    
	    foreach($the_post['wps_taxonomy_metadata_edit_role_timeout_'.$term_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $term_id, 'wps_forum_roles_timeout', $roles );

	else:

		wps_delete_term_meta( $term_id, 'wps_forum_roles_timeout' );

	endif;


}

// Save from Forum Administration screen
add_action('wpspro_forum_setup_save', 'wpspro_forum_setup_save_roles', 10, 2);
function wpspro_forum_setup_save_roles($wps_forum_id, $the_post) {

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_'.$wps_forum_id])):
	    foreach($the_post['wps_taxonomy_metadata_edit_role_'.$wps_forum_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $wps_forum_id, 'wps_forum_roles', $roles );
	else:
		wps_delete_term_meta( $wps_forum_id, 'wps_forum_roles' );
	endif;

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_create_'.$wps_forum_id])):
	    foreach($the_post['wps_taxonomy_metadata_edit_role_create_'.$wps_forum_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $wps_forum_id, 'wps_forum_roles_create', $roles );
	else:
		wps_delete_term_meta( $wps_forum_id, 'wps_forum_roles_create' );
	endif;

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_comment_'.$wps_forum_id])):
	    foreach($the_post['wps_taxonomy_metadata_edit_role_comment_'.$wps_forum_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $wps_forum_id, 'wps_forum_roles_comment', $roles );
	else:
		wps_delete_term_meta( $wps_forum_id, 'wps_forum_roles_comment' );
	endif;

	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_delete_'.$wps_forum_id])):
	    foreach($the_post['wps_taxonomy_metadata_edit_role_delete_'.$wps_forum_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $wps_forum_id, 'wps_forum_roles_delete', $roles );
	else:
		wps_delete_term_meta( $wps_forum_id, 'wps_forum_roles_delete' );
	endif;
    
	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_move_'.$wps_forum_id])):
	    foreach($the_post['wps_taxonomy_metadata_edit_role_move_'.$wps_forum_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $wps_forum_id, 'wps_forum_roles_move', $roles );
	else:
		wps_delete_term_meta( $wps_forum_id, 'wps_forum_roles_move' );
	endif;
    
	$roles = array();
	if(!empty($the_post['wps_taxonomy_metadata_edit_role_timeout_'.$wps_forum_id])):
	    foreach($the_post['wps_taxonomy_metadata_edit_role_timeout_'.$wps_forum_id] as $check):
			$roles[] = $check;
	    endforeach;
	    wps_update_term_meta( $wps_forum_id, 'wps_forum_roles_timeout', $roles );
	else:
		wps_delete_term_meta( $wps_forum_id, 'wps_forum_roles_timeout' );
	endif;    
}


add_filter('user_can_see_forum_filter', 'user_can_see_forum_filter_roles',10,3);
function user_can_see_forum_filter_roles($see, $user_id, $term_id) {

	global $wpdb;
	$user = get_userdata($user_id);
	if ($saved_roles = wps_get_term_meta( $term_id, 'wps_forum_roles' , true)):
		if ($user):
			$capabilities = $user->{$wpdb->prefix . 'capabilities'};		

			$found = false;
			if ($saved_roles && $capabilities):
				foreach ($capabilities as $key => $value):
					if (in_array($key, $saved_roles)) {
						$found = true;
					}
				endforeach;
			endif;
			if ($found):
				return true;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	else:
		return true;
	endif;

}

add_filter('wps_forum_post_user_can_post_filter', 'user_can_post_forum_filter_roles',10,3);
function user_can_post_forum_filter_roles($see, $user_id, $term_id) {

	global $wpdb;
	$user = get_userdata($user_id);
	if ($saved_roles = wps_get_term_meta( $term_id, 'wps_forum_roles_create' , true)):
		if ($user):
			$capabilities = $user->{$wpdb->prefix . 'capabilities'};		

			$found = false;
			if ($saved_roles && $capabilities):
				foreach ($capabilities as $key => $value):
					if (in_array($key, $saved_roles)) {
						$found = true;
					}
				endforeach;
			endif;
			if ($found):
				return true;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	else:
		// No specific setting to create, therefore assuming all, so check if this user's role can see the forum
		if ($saved_roles = wps_get_term_meta( $term_id, 'wps_forum_roles' , true)):
			if ($user):
				$capabilities = $user->{$wpdb->prefix . 'capabilities'};		

				$found = false;
				if ($saved_roles && $capabilities):
					foreach ($capabilities as $key => $value):
						if (in_array($key, $saved_roles)) {
							$found = true;
						}
					endforeach;
				endif;
				if ($found):
					return true;
				else:
					return false;
				endif;
			else:
				return false;
			endif;
		else:
			return true;
		endif;		
	endif;

}

add_filter('wps_forum_post_user_can_edit_filter', 'user_can_edit_forum_filter_roles',10,4);
function user_can_edit_forum_filter_roles($user_can_edit_forum, $the_post, $user_id, $term_id) {

	if ($the_post->post_author == $user_id)
		return true;

	global $wpdb;
	$user = get_userdata($user_id);
	if ($saved_roles = wps_get_term_meta( $term_id, 'wps_forum_roles_edit' , true)):
		if ($user):
			$capabilities = $user->{$wpdb->prefix . 'capabilities'};		

			$found = false;
			if ($saved_roles && $capabilities):
				foreach ($capabilities as $key => $value):
					if (in_array($key, $saved_roles)) {
						$found = true;
					}
				endforeach;
			endif;
			if ($found):
				return true;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	else:
		// No specific setting to edit, therefore check if post owner
		return $the_post->post_author == $user_id;
	endif;

}

add_filter('wps_forum_post_user_can_comment_filter', 'user_can_comment_forum_filter_roles',10,3);
function user_can_comment_forum_filter_roles($see, $user_id, $term_id) {

	global $wpdb;
	$user = get_userdata($user_id);
	if ($saved_roles = wps_get_term_meta( $term_id, 'wps_forum_roles_comment' , true)):
		if ($user):
			$capabilities = $user->{$wpdb->prefix . 'capabilities'};		

			$found = false;
			if ($saved_roles && $capabilities):
				foreach ($capabilities as $key => $value):
					if (in_array($key, $saved_roles)) {
						$found = true;
					}
				endforeach;
			endif;
			if ($found):
				return true;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	else:
		// No specific setting to comment, therefore assuming all
        return true;
	endif;

}

add_filter('wps_forum_post_user_can_edit_comment_filter', 'user_can_edit_comment_forum_filter_roles',10,4);
function user_can_edit_comment_forum_filter_roles($user_can_edit_comment, $the_comment, $user_id, $term_id) {

	if ($the_comment->user_id == $user_id)
		return true;

	global $wpdb;
	$user = get_userdata($user_id);
	if ($saved_roles = wps_get_term_meta( $term_id, 'wps_forum_roles_edit' , true)):
		if ($user):
			$capabilities = $user->{$wpdb->prefix . 'capabilities'};		

			$found = false;
			if ($saved_roles && $capabilities):
				foreach ($capabilities as $key => $value):
					if (in_array($key, $saved_roles)) {
						$found = true;
					}
				endforeach;
			endif;
			if ($found):
				return true;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	else:
		// No specific setting to edit, therefore check if post owner
		return $the_comment->user_id == $user_id;
	endif;

}

add_filter('wps_forum_post_user_can_delete_comment_filter', 'user_can_delete_comment_forum_filter_roles',10,4);
function user_can_delete_comment_forum_filter_roles($user_can_edit_comment, $the_comment, $user_id, $term_id) {

	global $wpdb;
	$user = get_userdata($user_id);
	if ($saved_roles = wps_get_term_meta( $term_id, 'wps_forum_roles_delete' , true)):
		if ($user):
			$capabilities = $user->{$wpdb->prefix . 'capabilities'};		

			$found = false;
			if ($saved_roles && $capabilities):
				foreach ($capabilities as $key => $value):
					if (in_array($key, $saved_roles)) {
						$found = true;
					}
				endforeach;
			endif;
			if ($found):
				return true;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	else:
		// No specific setting to edit, therefore check if post owner
		return $the_comment->user_id == $user_id;
	endif;
}

add_filter('wps_forum_post_user_can_delete_filter', 'user_can_delete_forum_filter_roles',10,4);
function user_can_delete_forum_filter_roles($user_can_delete_forum, $the_post, $user_id, $term_id) {

	global $wpdb;
	$user = get_userdata($user_id);
	if ($saved_roles = wps_get_term_meta( $term_id, 'wps_forum_roles_delete' , true)):
		if ($user):
			$capabilities = $user->{$wpdb->prefix . 'capabilities'};		

			$found = false;
			if ($saved_roles && $capabilities):
				foreach ($capabilities as $key => $value):
					if (in_array($key, $saved_roles)) {
						$found = true;
					}
				endforeach;
			endif;
			if ($found):
				return true;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	else:
		// No specific setting to delete, therefore check if post owner
		return $the_post->post_author == $user_id;
	endif;

}

add_filter('wps_forum_post_user_can_move_post_filter', 'user_can_move_post_filter_roles',10,4);
function user_can_move_post_filter_roles($user_can_delete_forum, $the_post, $user_id, $term_id) {

	global $wpdb;
	$user = get_userdata($user_id);
	if ($saved_roles = wps_get_term_meta( $term_id, 'wps_forum_roles_move' , true)):
		if ($user):
			$capabilities = $user->{$wpdb->prefix . 'capabilities'};		

			$found = false;
			if ($saved_roles && $capabilities):
				foreach ($capabilities as $key => $value):
					if (in_array($key, $saved_roles)) {
						$found = true;
					}
				endforeach;
			endif;
			if ($found):
				return true;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	else:
		// No specific setting to move, therefore check if post owner
		return $the_post->post_author == $user_id;
	endif;
}


?>