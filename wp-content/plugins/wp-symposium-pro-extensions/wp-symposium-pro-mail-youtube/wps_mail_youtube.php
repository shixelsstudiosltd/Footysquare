<?php
/**
 * Filter to parse item message and return YouTube as attachment
 **/
add_filter('wps_mail_item_filter', 'wps_parse_mail_youtube_links',10,4);
add_filter('wps_mail_item_comment_filter', 'wps_parse_mail_youtube_links',10,4);
function wps_parse_mail_youtube_links($item_html, $atts, $mail, $message) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'youtube_width' => '100%',
        'youtube_height' => '100%',
    ), $atts, 'wps_mail' ) );

	$ids = get_mail_youtube_videos($message);
	if (!empty($ids)) {
		foreach ($ids as $id) {
            $item_html .= '<div class="wps_video_container">';
            $item_html .= '<iframe style="max-width:'.$youtube_width.';max-height:'.$youtube_height.';" src="//www.youtube.com/embed/'.strip_tags($id).'" frameborder="0" allowfullscreen></iframe>';
            $item_html .= '</div>';
		}
	}

	return $item_html;

}


function get_mail_youtube_videos($string) {

    $ids = array();

    // find all urls
    preg_match_all('/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $string, $links);

    foreach ($links[0] as $link) {
        if (false && preg_match('~youtube\.com~', $link)) {
            if (preg_match('/[^=]+=([^?]+)/', $link, $id)) {
                $ids[] = $id[1];
            }
        }
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $id)) {
        	$ids[] = $id[1];
        }

    }

    return $ids;
}

?>