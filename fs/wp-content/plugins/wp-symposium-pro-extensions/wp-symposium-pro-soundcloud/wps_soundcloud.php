<?php
// Init
add_action('wps_activity_init_hook', 'wps_soundcloud_init');
function wps_soundcloud_init() {

    // JS and CSS files
    wp_enqueue_script('wps-soundcloud-js', plugins_url('wps_soundcloud.js', __FILE__), array('jquery'));      
    wp_enqueue_style('wps-soundcloud-css', plugins_url('wps_soundcloud.css', __FILE__), 'css');

    // SoundCloud SDK
    wp_register_script('wps-soundcloud-sdk-js', 'http://connect.soundcloud.com/sdk.js', array('jquery'));
    wp_enqueue_script('wps-soundcloud-sdk-js');

    // Setup variables to pass to JS
    wp_localize_script( 'wps-soundcloud-js', 'wps_soundcloud_var', array(
        'client_id'      => get_option('wps_soundcloud_client_id')
    ));

}

// Admin
if (is_admin())
    require_once('wps_soundcloud_admin.php');


/**
 * Filter to parse item post_title and return SoundCloud as attachment
 **/
add_filter('wps_activity_item_filter', 'wps_parse_soundcloud_links',10,7);
function wps_parse_soundcloud_links($item_html, $atts, $item_id, $item_title, $user_id, $current_user_id, $shown_count) {

	$ids = get_soundcloud_links($item_title);
	if (!empty($ids)) {
        $c=0;
		foreach ($ids as $id) {
            $item_html .= '<div class="wps_soundcloud_container" style="display:none">';
            $item_html .= '<div id="wps_soundcloud_embed_'.$item_id.'_'.$c.'" class="wps_soundcloud_embed">';
            $item_html .= $id;
            $item_html .= '</div>';
            $item_html .= '</div>';
            $c++;
		}
	}

	return $item_html;

}


function get_soundcloud_links($string) {

    $ids = array();

    // Get all URLs
    preg_match_all('/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $string, $links);

    foreach ($links[0] as $link) {
        // If a URL is soundcloud, add to array
        if (strpos($link, 'soundcloud.com')) {
        	$ids[] = $link;
        }

    }

    return $ids;
}

?>