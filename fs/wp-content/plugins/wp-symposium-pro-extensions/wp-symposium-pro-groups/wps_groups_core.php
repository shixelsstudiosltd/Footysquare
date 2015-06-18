<?php
																	/* ********* */
																	/* FUNCTIONS */
																	/* ********* */

function wps_get_groups() {

	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'post_title',
		'order'            => 'ASC',
		'post_type'        => 'wps_group',
		'post_status'      => 'publish',
	);
	$groups = get_posts($args);
	return $groups;

}

function wps_get_group_name($group_id, $link=true) {
	$group = get_post($group_id);
	if ($group):
		if ($link):
			$group_page = get_option('wpspro_group_page');
			if ($group_page):
				$url = get_permalink($group_page);
				return sprintf('<a href="%s">', $url.wps_query_mark($url).'group_id='.$group_id).$group->post_title.'</a>';
			else:
				return $group->post_title;
			endif;
		else:
			return $group->post_title;
		endif;
	else:
		return false;
	endif;
}

function wps_get_user_groups($user_id) {

	global $wpdb;

	$sql = "SELECT p.ID, p.post_status, m1.meta_value as wps_member, m2.meta_value as wps_group FROM ".$wpdb->prefix."posts p 
	LEFT JOIN ".$wpdb->prefix."postmeta m1 ON m1.post_id = p.ID
	LEFT JOIN ".$wpdb->prefix."postmeta m2 ON m2.post_id = p.ID
	WHERE p.post_type = 'wps_group_members'
	  AND (p.post_status = 'pending' OR p.post_status = 'publish')
	  AND (m1.meta_key = 'wps_member' AND m2.meta_key = 'wps_group')
      AND (m1.meta_value = %d)";
              
	$groups = $wpdb->get_results($wpdb->prepare($sql, $user_id));

	$user_groups = array();
	if ($groups):
		foreach ($groups as $group):
			array_push($user_groups, $group->wps_group);
		endforeach;
	endif;

	return $user_groups;

}

function wps_get_group_members($group_id, $status) {

	global $wpdb;

	$status = (isset($status)) ? $status : 'publish';

	if ($status != 'all'):

		$sql = "SELECT p.ID, p.post_status, m1.meta_value as wps_member, m1.meta_value as wps_member FROM ".$wpdb->prefix."posts p 
		LEFT JOIN ".$wpdb->prefix."postmeta m1 ON m1.post_id = p.ID
		LEFT JOIN ".$wpdb->prefix."postmeta m2 ON m2.post_id = p.ID
		WHERE p.post_type = 'wps_group_members'
		  AND (p.post_status = %s)
		  AND (m1.meta_key = 'wps_member' AND m2.meta_key = 'wps_group')
	      AND (m2.meta_value = %d)";
	              
		$members = $wpdb->get_results($wpdb->prepare($sql, $status, $group_id));

	else:

		$sql = "SELECT p.ID, p.post_status, m1.meta_value as wps_member, m1.meta_value as wps_member FROM ".$wpdb->prefix."posts p 
		LEFT JOIN ".$wpdb->prefix."postmeta m1 ON m1.post_id = p.ID
		LEFT JOIN ".$wpdb->prefix."postmeta m2 ON m2.post_id = p.ID
		WHERE p.post_type = 'wps_group_members'
		  AND (m1.meta_key = 'wps_member' AND m2.meta_key = 'wps_group')
	      AND (m2.meta_value = %d)";
	              
		$members = $wpdb->get_results($wpdb->prepare($sql, $group_id));

	endif;

	$group_members = array();
	if ($members):
		foreach ($members as $member):
			array_push($group_members, $member->wps_member);
		endforeach;
	endif;

	return $group_members;

}

function wps_is_group_member($user_id, $group_id_to_check) {

	global $wpdb;

	$sql = "SELECT p.ID, p.post_status, m1.meta_value as wps_member, m2.meta_value as wps_group FROM ".$wpdb->prefix."posts p 
	LEFT JOIN ".$wpdb->prefix."postmeta m1 ON m1.post_id = p.ID
	LEFT JOIN ".$wpdb->prefix."postmeta m2 ON m2.post_id = p.ID
	WHERE p.post_type = 'wps_group_members'
	  AND (p.post_status = 'pending' OR p.post_status = 'publish')
	  AND (m1.meta_key = 'wps_member' AND m2.meta_key = 'wps_group')
      AND (m1.meta_value = %d AND m2.meta_value = %d)";
              
	$group = $wpdb->get_row($wpdb->prepare($sql, $user_id, $group_id_to_check));

	if ($group):
		return array("ID"=>$group->ID, "status"=>$group->post_status);
		break;
	endif;

	return array("ID"=>0, "status"=>false);

}

function wps_group_administrator($group_id) {

	global $wpdb;

	$group = get_post($group_id);
	if ($group):
		$admin = get_user_by('id', $group->post_author);
		return $admin;
	else:
		return false;
	endif;

}

?>