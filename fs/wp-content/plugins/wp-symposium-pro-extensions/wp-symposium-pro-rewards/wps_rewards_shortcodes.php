<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_rewards_init() {
	// CSS
	wp_enqueue_style('wps-rewards-css', plugins_url('wps_rewards.css', __FILE__), 'css');
}
																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_badge($atts) {

	// Init
	add_action('wp_footer', 'wps_rewards_init');

	$html = '';
	global $wpdb;

	$user_id = wps_get_user_id();

	// Shortcode parameters
	extract( shortcode_atts( array(
		'slug' => '', // badge slug
		'opacity' => 20,
		'size' => 0,
		'before' => '',
		'after' => '',
	), $atts, 'wps_reward' ) );	

	if ($slug == ''):

		$html .= '<div class="wps_error">'.sprintf(__('You must set a slug for %s-badge.', WPS2_TEXT_DOMAIN), WPS_PREFIX).'</div>';

	else:

		// Get all rewards for this user of this reward type
		$sql = "SELECT p.ID, p.post_type, p.post_name, p.post_author, m.meta_value as reward_type
				FROM ".$wpdb->prefix."posts p
				LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_id
				WHERE m.meta_key = 'wps_reward_type'
				  AND m.meta_value = %s
				AND p.post_status = 'publish'
				AND p.post_type = 'wps_reward'
				AND p.post_author = %d";

		$badge = $wpdb->get_row($wpdb->prepare($sql, $slug, $user_id));

		// Get reward definitions
		$rewards = get_posts(array(
		    'post_type'   => 'wps_rewards',
		    'post_status' => 'publish',
		    'posts_per_page' => -1
		    )
		);
		$shown_badge = false;
		foreach ($rewards as $reward):

			if ($reward->wps_rewards_type != 'count'):

				// Reward for individual posts

				if (($badge || $opacity) && ($reward->post_name == $slug)):

					if (has_post_thumbnail($reward->ID)):

						$badge_size = $size ? $size : get_post_meta($reward->ID, 'wps_rewards_size', true);
						$opacity = (!$badge && $opacity) ? 'opacity: '.($opacity/100).'; filter: alpha(opacity='.$opacity.');' : '';
						$image_id = get_post_thumbnail_id($reward->ID);
						$image_attributes = wp_get_attachment_image_src( $image_id, 'full');

						$html .= '<div class="wps_badge" style="position:relative;top:0;left:0;">';
						$html .= '<img src="'.$image_attributes[0].'" style="'.$opacity.'position:relative;top:0;left:0;width:'.$badge_size.'px; height:'.$badge_size.'px;" alt="'.$reward->post_title.'" title="'.$reward->post_title.'" />';
						$html .= '</div>';

					endif;

				endif;

			else:

				// Reward for making a number of posts

				// What are we counting?
				$reward_count_type = get_post_meta($reward->ID, 'wps_rewards_count_type', true);

				// Get reward associated with this type
				$sql = "SELECT p.post_name FROM ".$wpdb->prefix."posts p
						LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_id
						WHERE m.meta_key = 'wps_rewards_type'
						  AND m.meta_value = %s";
				$r = $wpdb->get_var($wpdb->prepare($sql, $reward_count_type));


				// Get all rewards for this user of this reward type
				$sql = "SELECT count(p.ID)
						FROM ".$wpdb->prefix."posts p
						LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_id
						WHERE m.meta_key = 'wps_reward_type'
						  AND m.meta_value = %s
						AND p.post_status = 'publish'
						AND p.post_type = 'wps_reward'
						AND p.post_author = %d";

				$count = $wpdb->get_var($wpdb->prepare($sql, $r, $user_id));	

				if ($reward->post_name == $slug):

					if (has_post_thumbnail($reward->ID)):

						$badge_size = $size ? $size : get_post_meta($reward->ID, 'wps_rewards_size', true);
						$count_required = get_post_meta($reward->ID, 'wps_rewards_count', true);
						$opacity = (!$badge && $opacity && $count < $count_required) ? 'opacity: '.($opacity/100).'; filter: alpha(opacity='.$opacity.');' : '';
						$image_id = get_post_thumbnail_id($reward->ID);
						$image_attributes = wp_get_attachment_image_src( $image_id, 'full');

						$percent = floor(($count/$count_required)*100);
						if ($percent > 100) $percent = 100;
						if ($opacity || $percent == 100):
							$html .= '<div class="wps_badge" style="position:relative;top:0;left:0;">';
							$html .= '<img src="'.$image_attributes[0].'" style="'.$opacity.'position:relative;top:0;left:0;width:'.$badge_size.'px; height:'.$badge_size.'px;" alt="'.$reward->post_title.' ('.$percent.'%)'.'" title="'.$reward->post_title.' ('.$percent.'%)'.'" />';
							$html .= '<img src="'.$image_attributes[0].'" style="clip: rect(0, '.$badge_size.'px, '.($badge_size/100*$percent).'px, 0);position:absolute;top:0;left:0;width:'.$badge_size.'px; height:'.$badge_size.'px;" alt="'.$reward->post_title.' ('.$percent.'%)'.'" title="'.$reward->post_title.' ('.$percent.'%)'.'" />';
							$html .= '</div>';
						endif;							

					endif;

				endif;

			endif;

		endforeach;
		wp_reset_query();

		if ($html):
			$html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);
		endif;

	endif;

	return $html;

}

function wps_reward($atts) {

	// Init
	add_action('wp_footer', 'wps_rewards_init');

	$html = '';
	global $wpdb;

	$user_id = wps_get_user_id();

	// Shortcode parameters
	extract( shortcode_atts( array(
        'slug' => false,
		'before' => '',
		'after' => '',
	), $atts, 'wps_reward' ) );	

    if (!$slug):
    
        $sql = "SELECT p.post_author, SUM( m.meta_value ) AS score
                FROM ".$wpdb->prefix."posts p
                LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_id
                WHERE m.meta_key = 'wps_reward_value'
                AND p.post_status = 'publish'
                AND p.post_author = %d";

        $reward = $wpdb->get_row($wpdb->prepare($sql, $user_id));

        $html .= number_format((int)$reward->score);
    
    else:
        // Specific reward, so...
        // Get the ID of this reward, and reward "points" based on slug value
        $sql = "SELECT m.meta_value AS value FROM ".$wpdb->prefix."posts p
                LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_ID
                WHERE p.post_name = %s 
                  AND p.post_type='wps_rewards' 
                  AND p.post_status='publish'
                  AND m.meta_key = 'wps_rewards_value'";
        $value = $wpdb->get_var($wpdb->prepare($sql, $slug));

		// Get number of rewards for this slug
        $sql = "SELECT COUNT(*) AS cnt FROM ".$wpdb->prefix."postmeta WHERE meta_key='wps_reward_type' AND meta_value=%s";
        $cnt = $wpdb->get_var($wpdb->prepare($sql, $slug));
    
        $total = $cnt * $value;
        
        $html .= number_format((int)$total);

    endif;

	if ($html):
		$html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);
	endif;

	return $html;

}

function wps_rewards($atts) {

	// Init
	add_action('wp_footer', 'wps_rewards_init');

	$html = '';
	global $wpdb;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'count' => 10,
		'days' => 365,
		'avatar_size' => 32,
		'link' => 1,
		'before' => '',
		'after' => '',
	), $atts, 'wps_rewards' ) );

	$sql = "SELECT p.post_author, SUM( m.meta_value ) AS score
			FROM ".$wpdb->prefix."posts p
			LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_id
			WHERE m.meta_key =  'wps_reward_value'
			AND p.post_status =  'publish'
			AND p.post_date_gmt > DATE_SUB(NOW(), INTERVAL %d DAY)
			GROUP BY p.post_author
			ORDER BY score DESC 
			LIMIT 0 , %d";

	$rewards = $wpdb->get_results($wpdb->prepare($sql, $days, $count));

	foreach ($rewards as $reward):

		$avatar_padding = $avatar_size ? '10' : 0;

		$html .= '<div class="wps_rewards_row" style="padding-left: '.($avatar_size).'px;">';

			// Avatar
			if ($avatar_size):
				$html .= '<div class="wps_rewards_avatar" style="margin-left: -'.($avatar_size).'px; margin-right: '.$avatar_padding.'px;">';
					$html .= user_avatar_get_avatar($reward->post_author, $avatar_size);
				$html .= '</div>';
			endif;

			// Name
			$html .= '<div class="wps_rewards_row_user">';
				if ($link):
					$html .= wps_display_name(array('user_id'=>$reward->post_author, 'link'=>1));
				else:
					$html .= $reward->post_author;
				endif;
			$html .= '</div>';

			// Points
			$html .= '<div class="wps_rewards_row_score">'.$reward->score.'</div>';

		$html .= '</div>';
	
	endforeach;

	if ($html):
		$html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);
	endif;

	return $html;
}


if (!is_admin()) {
	add_shortcode(WPS_PREFIX.'-badge', 'wps_badge');
	add_shortcode(WPS_PREFIX.'-reward', 'wps_reward');
	add_shortcode(WPS_PREFIX.'-rewards', 'wps_rewards');
}



?>
