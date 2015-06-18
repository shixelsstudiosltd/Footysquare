<?php


// New blog post
add_action('draft_to_publish', 'wps_blogpost_add_reward');
add_action('pending_to_publish', 'wps_blogpost_add_reward');
add_action('auto-draft_to_publish', 'wps_blogpost_add_reward');
add_action('draft_to_new', 'wps_blogpost_add_reward');
add_action('pending_to_new', 'wps_blogpost_add_reward');
add_action('auto-draft_to_new', 'wps_blogpost_add_reward');
function wps_blogpost_add_reward($post) {

	if ($post->post_type == 'post' && wps_reward_type_exists('post')):

		$author = get_user_by('id', $post->post_author);
		$post_author = $author ? $author->display_name : '?';
		$url = get_permalink($post->ID);
		$reward = array(
			'type' => 'post',
			'title' => $post->post_title,
			'content' => sprintf('%s added by %s.', '<a href="'.$url.'">'.$post->post_title.'</a>', $post_author),
			'post_author' => $post->post_author
		);
		wps_insert_reward($reward);

	endif;

}

// New accepted friendship
add_action( 'wps_friends_accept_hook', 'wps_friendship_add_reward', 10, 2 );
function wps_friendship_add_reward($sent_id, $accepted_id) {

	if (wps_reward_type_exists('friendship')):

		$sent = get_user_by('id', $sent_id);
		$accepted = get_user_by('id', $accepted_id);

		$reward = array(
			'type' => 'friendship',
			'title' => $sent->display_name.' &rarr; '.$accepted->display_name,
			'content' => $sent->display_name.' ('.$sent->ID.') &rarr; '.$accepted->display_name.' ('.$sent->ID.')',
			'post_author' => $sent_id
		);
		wps_insert_reward($reward);

	endif;

}


// New forum post
add_action( 'wps_forum_post_add_hook', 'wps_activity_forum_add_reward', 10, 3 );
function wps_activity_forum_add_reward($the_post, $the_files, $new_id) {

	global $wpdb;
	$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_rewards' AND post_status='publish'";
	$rewards = $wpdb->get_results($sql);

	if ($rewards):

		global $current_user;

		foreach ($rewards as $reward):

			$wps_reward_type = get_post_meta($reward->ID, 'wps_rewards_type', true);
			$wps_reward_value = get_post_meta($reward->ID, 'wps_rewards_value', true);

			if ($wps_reward_value):

				if ($wps_reward_type == 'forum_new'):

					$post = array(
					  'post_title'     	=> $current_user->display_name,
					  'post_status'    	=> 'publish',
					  'post_content'	=> $current_user->display_name.'<br />'.serialize($the_post),
					  'post_type'      	=> 'wps_reward',
					  'post_author'    	=> $current_user->ID,
					  'ping_status'    	=> 'closed',
					  'comment_status' 	=> 'closed',
					);  
					$reward_id = wp_insert_post( $post );

					update_post_meta( $reward_id, 'wps_reward_type', $reward->post_name );
					update_post_meta( $reward_id, 'wps_reward_value', $wps_reward_value );

				endif;		

			endif;

		endforeach;

	endif;

}

// New forum reply
add_action( 'wps_forum_comment_add_hook', 'wps_activity_forum_comment_reward', 10, 4 );
function wps_activity_forum_comment_reward($the_post, $the_files, $post_id, $new_id) {

	global $wpdb;
	$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_rewards' AND post_status='publish'";
	$rewards = $wpdb->get_results($sql);

	if ($rewards):

		global $current_user;

		foreach ($rewards as $reward):

			$wps_reward_type = get_post_meta($reward->ID, 'wps_rewards_type', true);
			$wps_reward_value = get_post_meta($reward->ID, 'wps_rewards_value', true);

			if ($wps_reward_type == 'forum_reply' && $wps_reward_value):

				$post = array(
				  'post_title'     	=> $current_user->display_name,
				  'post_status'    	=> 'publish',
				  'post_content'	=> $current_user->display_name.'<br />'.serialize($the_post),
				  'post_type'      	=> 'wps_reward',
				  'post_author'    	=> $current_user->ID,
				  'ping_status'    	=> 'closed',
				  'comment_status' 	=> 'closed',
				);  
				$reward_id = wp_insert_post( $post );

				update_post_meta( $reward_id, 'wps_reward_type', $reward->post_name );
				update_post_meta( $reward_id, 'wps_reward_value', $wps_reward_value );

			endif;		

		endforeach;

	endif;

}

// New post on activity
add_action( 'wps_activity_post_add_hook', 'wps_activity_post_add_reward', 10, 3 );
function wps_activity_post_add_reward($the_post, $the_files, $new_id) {

	global $wpdb;
	$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_rewards' AND post_status='publish'";
	$rewards = $wpdb->get_results($sql);

	if ($rewards):

		$post_author = $the_post['wps_activity_post_author'];
		$post_author_user = get_user_by ('id', $post_author );
        if ($post_author_user):
            $the_target = $the_post['wps_activity_post_target'];
            $the_target_user = get_user_by ('id', $the_target );

            foreach ($rewards as $reward):

                $wps_reward_type = get_post_meta($reward->ID, 'wps_rewards_type', true);
                $wps_reward_value = get_post_meta($reward->ID, 'wps_rewards_value', true);

                // Reward for posting to friends
                if ($wps_reward_type == 'activity_new' && $wps_reward_value && ($post_author == $the_target && !is_array($the_post['wps_activity_recipients']))):

                    $post = array(
                      'post_title'     	=> $post_author_user->display_name,
                      'post_status'    	=> 'publish',
                      'post_content'	=> $post_author.' -> '.$the_target.'<br />'.serialize($the_post),
                      'post_type'      	=> 'wps_reward',
                      'post_author'    	=> $post_author,
                      'ping_status'    	=> 'closed',
                      'comment_status' 	=> 'closed',
                    );  
                    $reward_id = wp_insert_post( $post );

                    update_post_meta( $reward_id, 'wps_reward_type', $reward->post_name );
                    update_post_meta( $reward_id, 'wps_reward_value', $wps_reward_value );

                endif;

                // Reward for posting to another
                if ($wps_reward_type == 'activity_new_other' && $wps_reward_value && ($post_author != $the_target || is_array($the_post['wps_activity_recipients']))):

                    $post = array(
                      'post_title'     	=> $post_author_user->display_name,
                      'post_status'    	=> 'publish',
                      'post_content'	=> $post_author.' -> '.$the_target.'<br /><br />'.serialize($the_post),
                      'post_type'      	=> 'wps_reward',
                      'post_author'    	=> $post_author,
                      'ping_status'    	=> 'closed',
                      'comment_status' 	=> 'closed',
                    );  
                    $reward_id = wp_insert_post( $post );

                    update_post_meta( $reward_id, 'wps_reward_type', $reward->post_name );
                    update_post_meta( $reward_id, 'wps_reward_value', $wps_reward_value );

                endif;			

            endforeach;
    
        endif;

	endif;

}

// Reply to activity
add_action( 'wps_activity_comment_add_hook', 'wps_activity_comment_add_reward', 10, 2 );
function wps_activity_comment_add_reward($the_post, $new_id) {

	global $wpdb;
	$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='wps_rewards' AND post_status='publish'";
	$rewards = $wpdb->get_results($sql);

	if ($rewards):

		global $current_user;

		foreach ($rewards as $reward):

			$wps_reward_type = get_post_meta($reward->ID, 'wps_rewards_type', true);
			$wps_reward_value = get_post_meta($reward->ID, 'wps_rewards_value', true);

			if ($wps_reward_type == 'activity_reply' && $wps_reward_value):

				$post = array(
				  'post_title'     	=> $current_user->display_name,
				  'post_status'    	=> 'publish',
				  'post_content'	=> $current_user->ID.'<br />'.serialize($the_post),
				  'post_type'      	=> 'wps_reward',
				  'post_author'    	=> $current_user->ID,
				  'ping_status'    	=> 'closed',
				  'comment_status' 	=> 'closed',
				);  
				$reward_id = wp_insert_post( $post );

				update_post_meta( $reward_id, 'wps_reward_type', $reward->post_name );
				update_post_meta( $reward_id, 'wps_reward_value', $wps_reward_value );

			endif;	

		endforeach;

	endif;

}



?>