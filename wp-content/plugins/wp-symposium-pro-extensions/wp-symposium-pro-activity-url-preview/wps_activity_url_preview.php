<?php
// AJAX
require_once('ajax_activity_url_preview.php');

add_action('wps_activity_init_hook', 'wps_url_preview_init');
function wps_url_preview_init() {
        
    wp_enqueue_script('wps-preview-init-js', plugins_url('wps_activity_url_preview.js', __FILE__), array('jquery'));    
    wp_localize_script( 'wps-preview-init-js', 'wps_activity_url_ajax', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ));   
    wp_enqueue_style('wps-preview-init-css', plugins_url('wps_activity_url_preview.css', __FILE__), 'css');

}

/**
 * Filter to parse item activity (post_title)  and return remote URLs as a preview
 **/
add_filter('wps_activity_item_filter', 'wps_parse_remote_links',10,7);
function wps_parse_remote_links($item_html, $atts, $item_id, $item_title, $user_id, $current_user_id, $shown_count) {
        
    // Shortcode parameters
    extract( shortcode_atts( array(
        'dynamic_previews' => 0, // fetch preview every time, set to 0 to fetch once and cache
        'max_previews' => 10, // maximum to attempt, reduce to improve page load if dynamic_previews=1
        'url_preview_image' => 100, // set to 0 to disable
        'url_preview_fail' => 1,
        'min_width' => 200, // set to 0 to get first image from site
        'max_width' => 0, // set to 0 to ignore, or maximum width of image
        'fail_text' => __('Unable to connect to site', WPS2_TEXT_DOMAIN),
        'fail_image' => plugins_url('broken.png', __FILE__),
        'ignore_images' => 'adserver,ads,affiliate', // list of words which will cause URL to be ignored (images)
        'ignore_links' => '', // list of words which will cause URL to be ignored (link)
    ), $atts, 'wps_activity' ) );

    if (!strpos($item_html, 'wps_p_content')): // checking for existence of this class in activity post content
    
        require_once('simple_html_dom.php');
        require_once('url_to_absolute.php');
    
        if ($shown_count <= ($max_previews-1)):

            $cached_preview = get_post_meta( $item_id, 'wps_url_preview_cache', true );    

            if (!$cached_preview || $dynamic_previews) {

                // add current domain to ignore_links
                $ignore_links = parse_url(get_bloginfo('url'), PHP_URL_HOST).','.$ignore_links;
                $ignore_links = rtrim($ignore_links, ',');
                $ignore = explode(',', $ignore_links);

                $ids = wps_get_remote_urls($item_title);

                if (!empty($ids)) {
                    foreach ($ids as $id) {

                        // Check for ignore words in URL
                        $ignore_url = false;
                        if ($ignore_links):
                            foreach ($ignore as $word):
                                if (strpos($id, $word) !== false) $ignore_url = true;
                            endforeach;
                        endif;

                        if (!$ignore_url):

                            // Get HTML for title and description
                            $html = wps_file_get_contents_curl($id);

                            $doc = new DOMDocument();
                            @$doc->loadHTML($html);
                            $nodes = $doc->getElementsByTagName('title');
                            @$title = $nodes->item(0)->nodeValue;

                            $img_id = 0; // Default attachment ID if unable to retrieve image to media library

                            if ($title): // if can retrieve

                                $metas = $doc->getElementsByTagName('meta');
                                $description = $id;
                                for ($i = 0; $i < $metas->length; $i++)
                                {
                                    $meta = $metas->item($i);
                                    if($meta->getAttribute('name') == 'description')
                                        $description = $meta->getAttribute('content');
                                }

                                // Get image to show
                                if ($url_preview_image):
                                    @$html = file_get_html($id);
                                    $cnt = 0;
                                    if ($html):
                                        foreach($html->find('img') as $element):
                                            $use_img = false;
                                            $img_src = strtolower(url_to_absolute($id, $element->src));
                                            $ignore_img = false;
                                            if ($ignore_images):
                                                $ignore_list = explode(',', strtolower($ignore_images));
                                                foreach ($ignore_list as $ignore):
                                                    if (strpos($img_src, $ignore)) $ignore_img = true;
                                                endforeach;
                                            endif;
                                            if (!$ignore_img):
                                                if ($min_width) {
                                                    $the_img = @getimagesize($img_src);
                                                    if ($the_img):
                                                        list($width, $height, $type, $attr) = $the_img;
                                                        if ($width >= $min_width && (!$max_width || $width <= $max_width)) {
                                                            $use_img = true;
                                                            // Put into media library
                                                            if ( !function_exists('media_handle_upload') ) {
                                                                require_once(ABSPATH . "wp-includes" . '/pluggable.php');
                                                                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                                                                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                                                                require_once(ABSPATH . "wp-admin" . '/includes/media.php');
                                                            }
                                                            $tmp = download_url( $img_src );
                                                            // Set variables for storage
                                                            // fix file filename for query strings
                                                            preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $img_src, $matches);
                                                            $file_array['name'] = basename($matches[0]);
                                                            $file_array['tmp_name'] = $tmp;

                                                            // If error storing temporarily, unlink
                                                            if ( is_wp_error( $tmp ) ) {
                                                                @unlink($file_array['tmp_name']);
                                                                $file_array['tmp_name'] ='';
                                                            }

                                                            @$img_id = media_handle_sideload( $file_array, 0, '' );

                                                            // check for error in $id
                                                            if ( is_wp_error($img_id) ) {
                                                                @unlink($file_array['tmp_name']);
                                                            }                                                    
                                                        }
                                                    endif;
                                                } else {
                                                     $use_img = true;
                                                }
                                                if ($use_img) break;
                                            endif;
                                        endforeach;
                                    else:
                                        // Couldn't get HTML, secure?
                                        $use_img = false;
                                    endif;
                                    if (!$use_img) $img_src = $fail_image;
                                endif;

                            else:

                                $title = $id;
                                $description = $fail_text;
                                $img_src = $fail_image;

                            endif;

                            if ($doc->documentElement || $url_preview_fail):

                                $padding = $url_preview_image ? $url_preview_image : 6;
                                $margin = $url_preview_image ? $url_preview_image-6 : 6;
                                $img_margin = $url_preview_image ? 20 : 0;

                                $item = get_post($item_id);

                                $cached_preview_html = '<div id="wps_remote_'.$item_id.'" class="wps_remote_container" style="padding-left:'.$padding.'px">';
                                    if ($item->post_author == $current_user_id || current_user_can('manage_options'))
                                        $cached_preview_html .= '<img class="wps_remote_remove_preview" data-item="'.$item_id.'" style="margin-left: -'.$margin.'px;" title="'.__('Remove preview', WPS2_TEXT_DOMAIN).'"  src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                                    $cached_preview_html .= '<div class="wps_remote_container_img_div">';
                                        if ($url_preview_image)
                                            $cached_preview_html .= '<img id="wps_remote_image_'.$item_id.'" class="wps_remote_container_img" style="margin-left: -'.$margin.'px; max-width:'.$url_preview_image.'px; width:'.$url_preview_image.'px;" src="'.$img_src.'" />';
                                        if ( ($url_preview_image) && ($item->post_author == $current_user_id || current_user_can('manage_options')) )
                                            $cached_preview_html .= '<img class="wps_remote_remove_image" data-item="'.$item_id.'" style="margin-left: -'.(($url_preview_image/2)+8).'px; margin-top: 3px;" title="'.__('Remove image', WPS2_TEXT_DOMAIN).'"  src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                                    $cached_preview_html .= '</div>';
                                    $cached_preview_html .= '<div id="wps_remote_container_meta_'.$item_id.'" class="wps_remote_container_meta" style="margin-left:'.$img_margin.'px;">';
                                        $cached_preview_html .= '<div class="wps_remote_container_title"><a target="_blank" href="'.$id.'">'.$title.'</a></div>';
                                        $cached_preview_html .= '<div class="wps_remote_container_desc">'.$description.'</div>';
                                    $cached_preview_html .= '</div>';
                                $cached_preview_html .= '</div>';
                                $item_html .= $cached_preview_html;

                                // Store cached
                                $meta = array('attachment_id' => $img_id, 'title' => $title, 'description' => $description, 'url' => $id);
                                update_post_meta($item_id, 'wps_url_preview_cache', $meta);

                            endif;

                        endif;

                    } // end foreach
                }

            } else {
                
                // Get cached version
                if ($cached_preview != 'removed'):

                    if (isset($cached_preview['attachment_id'])):

                        $attachment_id = $cached_preview['attachment_id'];
                        if ($attachment_id != 0 && $attachment_id != 'removed'):
                            $the_img_src = wp_get_attachment_image_src($attachment_id, $url_preview_image);
                            $img_src = $the_img_src[0];
                        else:
                            if ($attachment_id):
                                $url_preview_image = false;
                            else:
                                $img_src = $fail_image;
                            endif;
                        endif;

                        $padding = $url_preview_image ? $url_preview_image : 6;
                        $margin = $url_preview_image ? $url_preview_image-6 : 6;
                        $img_margin = $url_preview_image ? 20 : 0;

                        $item = get_post($item_id);

                        $cached_preview_html = '<div id="wps_remote_'.$item_id.'" class="wps_remote_container" style="padding-left:'.$padding.'px">';
                            if ($item->post_author == $current_user_id || current_user_can('manage_options'))
                                $cached_preview_html .= '<img class="wps_remote_remove_preview" data-item="'.$item_id.'" style="margin-left: -'.$margin.'px;" title="'.__('Remove preview', WPS2_TEXT_DOMAIN).'"  src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                            $cached_preview_html .= '<div class="wps_remote_container_img_div">';
                                if ($url_preview_image)
                                    $cached_preview_html .= '<img id="wps_remote_image_'.$item_id.'" class="wps_remote_container_img" style="margin-left: -'.$margin.'px; max-width:'.$url_preview_image.'px; width:'.$url_preview_image.'px;" src="'.$img_src.'" />';
                                if ( ($url_preview_image) && ($item->post_author == $current_user_id || current_user_can('manage_options')) )
                                    $cached_preview_html .= '<img class="wps_remote_remove_image" data-item="'.$item_id.'" style="margin-left: -'.(($url_preview_image/2)+8).'px; margin-top: 3px;" title="'.__('Remove image', WPS2_TEXT_DOMAIN).'"  src="'.plugins_url('../../wp-symposium-pro/forums/images/trash.png', __FILE__).'" />';
                            $cached_preview_html .= '</div>';
                            $cached_preview_html .= '<div id="wps_remote_container_meta_'.$item_id.'" class="wps_remote_container_meta" style="margin-left:'.$img_margin.'px;">';
                                $cached_preview_html .= '<div class="wps_remote_container_title"><a target="_blank" href="'.$cached_preview['url'].'">'.$cached_preview['title'].'</a></div>';
                                $cached_preview_html .= '<div class="wps_remote_container_desc">'.$cached_preview['description'].'</div>';
                            $cached_preview_html .= '</div>';
                        $cached_preview_html .= '</div>';

                        $item_html .= $cached_preview_html;

                    endif;

                endif;

            }

        endif;
        
    endif;

	return $item_html;

}

function wps_get_remote_urls($string) {

    $ids = array();

    // find all urls
    preg_match_all('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', strtolower($string), $links);
    
    foreach ($links[0] as $link) {
        $link = strtolower($link);
        if ( (!strpos($link, 'youtube.com')) && (!strpos($link, 'soundcloud.com')) ) {
            if (strpos($link, 'http') === false) $link = 'http://'.$link;
        	if (!in_array($link, $ids)) $ids[] = $link;
        }
    }

    return $ids;
}
function wps_file_get_contents_curl($url)
{
    $curl_err = false;
    $disabled_functions=explode(',', ini_get('disable_functions'));
    if (!is_callable('curl_init')):
        $curl_err = __('CURL PHP extension is not installed, please contact your hosting company.', WPS2_TEXT_DOMAIN);
    else:
        if (in_array('curl_init', $disabled_functions))
            $curl_err = __('CURL PHP extension is disabled in php.ini, please contact your hosting company.', WPS2_TEXT_DOMAIN);
    endif;
    
    if (!$curl_err):
    
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $data = curl_exec($ch);
        curl_close($ch);
    
    else:
    
        echo '<div class="wps_error">'.$curl_err.'</div>';
        $data = false;
    
    endif;
    
    return $data;
}
?>