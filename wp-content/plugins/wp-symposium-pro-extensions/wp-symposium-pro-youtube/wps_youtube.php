<?php
/**
 * Filter to parse item activity (post_title) and return YouTube as attachment
 **/
add_filter('wps_activity_item_filter', 'wps_parse_youtube_links',10,7);
function wps_parse_youtube_links($item_html, $atts, $item_id, $item_title, $user_id, $current_user_id, $shown_count) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'youtube_width' => '100%',
        'youtube_height' => '100%',
    ), $atts, 'wps_activity' ) );

	$ids = get_youtube_videos($item_title);
	if (!empty($ids)) {
		foreach ($ids as $id) {
            $item_html .= '<div class="wps_video_container">';
			$item_html .= '<iframe style="max-width:'.$youtube_width.';max-height:'.$youtube_height.';" src="//www.youtube.com/embed/'.strip_tags($id).'" frameborder="0" allowfullscreen></iframe>';
            $item_html .= '</div>';
		}
	}

	return $item_html;

}


function get_youtube_videos($string) {

    $ids = array();

    // find all urls
    preg_match_all('/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $string, $links);

    foreach ($links[0] as $link) {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $id)) {
        	$ids[] = $id[1];
        }

    }

    return $ids;
}

?>