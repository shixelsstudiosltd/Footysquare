<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_extended_init() {
	// CSS
	wp_enqueue_style('wps-extended-css', plugins_url('wps_extended.css', __FILE__), 'css');
	// Hooks and Filters
	require_once('wps_extended_hooks_and_filters.php');
}
																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_extended($atts) {

	// Init
	add_action('wp_footer', 'wps_extended_init');

	$html = '';
	global $current_user;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'slug' => '', // Mandatory
		'user_id' => '',
		'show_if_empty' => 1,
		'empty_text' => '', 
		'value_translations' => '',
		'label_prefix' => 0,
        'age' => 0,
		'before' => '',
		'after' => '',
	), $atts, 'wps_extended' ) );


	if (!$user_id) $user_id = wps_get_user_id();

	if ($slug == ''):

		$html .= __('Please add slug="xxx" to the shortcode, where xxx is the slug of the extension.', WPS2_TEXT_DOMAIN);

	else:

		$friends = wps_are_friends($current_user->ID, $user_id);
		// By default same user, and friends of user, can see profile
		$user_can_see_profile = ($current_user->ID == $user_id || $friends['status'] == 'publish') ? true : false;
		$user_can_see_profile = apply_filters( 'wps_check_profile_security_filter', $user_can_see_profile, $user_id, $current_user->ID );

        if (wps_can_see_extension($slug)):

            // First check if user_meta == $slug exists (without the wps_ prefix), if not then assume WPS Pro extension, with wps_ prefix
            if ($value = get_user_meta($user_id, $slug, true)):

                $html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($value)))));

            else:

                $value_translations_array = str_replace(',', '&', $value_translations);
                parse_str($value_translations_array, $value_translations_array);		    

                $args = array (
                    'post_type'              => 'wps_extension',
                    'posts_per_page'         => -1,
                    'meta_key'				 => 'wps_extension_order',
                    'orderby'				 => 'wps_extension_order',
                    'order'					 => 'ASC',
                    'suppress_filters'		 => true,
                );

                $extensions = query_posts( $args );

                if ($extensions):
                    foreach ($extensions as $extension):

                        if ($user_can_see_profile || $extension->wps_extension_always_show):

                            if ( wps_using_permalinks() ):    	
                                $post_slug = explode('/', get_post_permalink($extension->ID));
                                $key = $post_slug[count($post_slug)-2];
                            else:
                                $permalink = get_post_permalink($extension->ID).'<br>';
                                if (strpos($permalink, '=')):
                                    $post_slug = explode('=', get_post_permalink($extension->ID));
                                    $key = $post_slug[1];
                                else:
                                    if (strpos($permalink, 'blog/')) $permalink = str_replace('blog/', '', $permalink);
                                    $post_slug = explode('/', $permalink);
                                    $key = $post_slug[count($post_slug)-2];
                                endif;
                            endif;
                            if ($key == $slug):			    				
                                $key = 'wps_'.$key;
                                if ($extension->wps_extension_type == 'text'):
                                    $value = get_user_meta($user_id, $key, true);
                                    $html .= wps_bbcode_replace(convert_smilies(make_clickable(esc_html($value))));
                                    if ($label_prefix && $value) $html = $extension->post_title.': '.$html;
                                endif;
                                if ($extension->wps_extension_type == 'date'):
                                    if (get_user_meta($user_id, $key, true)):
                                        if (!$age):
                                            $value = date('F j, Y', strtotime(get_user_meta($user_id, $key, true)));
                                        else:
                                            $dob = getdate(strtotime(get_user_meta($user_id, $key, true))); 
                                            $now = getdate(time()); 
                                            $years = $now['year'] - $dob['year']; 
                                            $years-= (int)($now['mon'] < $dob['mon']); 
                                            $years-= (int)(($now['mon'] == $dob['mon']) && ($now['mday'] < $dob['mday'])); 
                                            $value = sprintf($age, $years);
                                        endif;
                                    else:
                                        $value = '';
                                    endif;
                                    $html .= $value;
                                    if ($label_prefix && $value) $html = $extension->post_title.': '.$html;
                                endif;
                                if ($extension->wps_extension_type == 'textarea'):
                                    $value = get_user_meta($user_id, $key, true);
                                    $value = rtrim(wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($value))))));
                                    $value = str_replace('</p>', '<br /><br />', str_replace('<p>', '', $value));
                                    while ( (strlen($value) > 7) && (substr($value, strlen($value)-6, 6) == '<br />') ) $value = substr($value, 0, strlen($value)-6);
                                    $html .= $value;
                                    if ($label_prefix && $value) $html = $extension->post_title.'<br />'.$html;
                                endif;
                                if ($extension->wps_extension_type == 'youtube'):
                                    $value = get_user_meta($user_id, $key, true);
                                    if (strpos($value, 'http') === false) $value = 'https://'.$value;
                                    $ids = get_extensions_youtube_videos($value);
                                    foreach ($ids as $id):
                                        $width = $extension->wps_extension_youtube_width ? 'width:'.$extension->wps_extension_youtube_width.';' : '';
                                        if ($extension->wps_extension_youtube_height == 'auto'):
                                            $html = '<div class="wps_video_container">';
                                            $html .= '<iframe style="'.$width.';max-height:100%;" src="//www.youtube.com/embed/'.strip_tags($id).'" frameborder="0" allowfullscreen></iframe>';
                                            $html .= '</div>';
                                        else:
                                            $height = $extension->wps_extension_youtube_height ? 'height:'.$extension->wps_extension_youtube_height.';' : '';
                                            $html .= '<iframe style="'.$width.$height.'" src="//www.youtube.com/embed/'.strip_tags($id).'" frameborder="0" allowfullscreen></iframe>';
                                        endif;
                                        if ($label_prefix && $value) $html = $extension->post_title.'<br />'.$html;
                                    endforeach;
                                endif;
                                if ($extension->wps_extension_type == 'list'):
                                    $value = get_user_meta($user_id, $key, true);
                                    if (isset($value_translations_array[str_replace(' ', '_', $value)])) $value = $value_translations_array[str_replace(' ', '_', $value)];
                                    $html .= wps_bbcode_replace(convert_smilies(make_clickable(esc_html($value))));
                                    if ($label_prefix && $value) $html = $extension->post_title.': '.$html;
                                endif;
                                if ($extension->wps_extension_type == 'image'):
                                    $image = get_user_meta($user_id, $key, true) ? get_user_meta($user_id, $key, true) : false;
                                    if ($image):
                                        $image_url = get_user_meta($user_id, $key.'_url', true) ? get_user_meta($user_id, $key.'_url', true) : false;
                                        $width = $extension->wps_extension_image_width ? 'width:'.$extension->wps_extension_image_width.';' : '';
                                        $height = $extension->wps_extension_image_height ? 'height:'.$extension->wps_extension_image_height.';' : '';
                                        $target = $extension->wps_extension_target ? '_blank' : '_self';
                                        if ($image_url) $html .= '<a target="'.$target.'" href="'.$image_url.'">';
                                        $html .= '<img style="'.$width.$height.'" src="'.$image.'" />';
                                        if ($image_url) $html .= '</a>';
                                        if ($label_prefix && $value) $html = $extension->post_title.'<br />'.$html;
                                    endif;
                                endif;
                                if ($extension->wps_extension_type == 'url'):
                                    if ($value = get_user_meta($user_id, $key, true)):
                                        $target = get_post_meta($extension->ID, 'wps_extension_target', true) ? '_blank' : '_self';
                                        $value = strtolower($value);
                                        if (strpos($value, 'http') === false) $value = 'http://'.$value;
                                        if (!has_post_thumbnail($extension->ID)):
                                            $html .= '<a href="'.$value.'" target="'.$target.'">'.wps_bbcode_replace(convert_smilies(esc_html($value))).'</a>';
                                        else:
                                            $html .= '<a href="'.$value.'" target="'.$target.'">'.get_the_post_thumbnail($extension->ID).'</a>';
                                        endif;
                                        if ($label_prefix && $value) $html = $extension->post_title.': '.$html;
                                    endif;
                                endif;
                            endif;

                        endif;

                    endforeach;
                endif;
                wp_reset_query();

            endif;

            if ($html || $show_if_empty):
                if (!$html) $html = $empty_text;
                $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);
            endif;

        endif;

	endif;

	return $html;
}

function get_extensions_youtube_videos($string) {

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

if (!is_admin()) {
	add_shortcode(WPS_PREFIX.'-extended', 'wps_extended');
}



?>
