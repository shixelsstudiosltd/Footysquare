<?php

																	/* **** */
																	/* INIT */
																	/* **** */


function wps_gallery_init() {

	// JS and CSS
	wp_enqueue_script('wps-gallery-js', plugins_url('wps_gallery.js', __FILE__), array('jquery'));	
    $url = get_page_link(get_option('wpspro_gallery_page'));
    $url_with_query = $url.wps_query_mark($url);
	wp_localize_script('wps-gallery-js', 'wpspro_gallery', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ), 
		'plugins_url' => plugins_url( '', __FILE__ ), 
		'gallery_page_url' => $url,
        'gallery_page_url_with_query' => $url_with_query,
        'slideshow_page' => plugins_url('images/page.png', __FILE__),
        'slideshow_page_current' => plugins_url('images/page_current.png', __FILE__),
	));	
	wp_enqueue_style('wps-gallery-css', plugins_url('wps_gallery.css', __FILE__), 'css');	
    
	// Anything else?
	do_action('wps_gallery_init_hook');

}


																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_gallery_grid($atts) {

    // Init
    add_action('wp_footer', 'wps_gallery_init');

    // Shortcode parameters
    extract( shortcode_atts( array(
        'user_id' => false,
        'show_all' => false,
        'featured_image_size' => 75,
        'no_permission_text' => '',
        'no_albums' => __('No albums to view.', WPS2_TEXT_DOMAIN),
        'scrolling' => true,
        'count' => 30,
        'more' => '...', // set to false to hide
        'more_top_adjustment' => 0,
        'more_left_adjustment' => 10,
        'orderby' => 'updated', // can by created, title or updated
        'padding' => 3,
        'before' => '',
        'after' => '',
    ), $atts, 'wps_gallery_grid' ) );

    // Check if showing all user's galleries
    if ($user_id == 'all'):
        $user_id = false;
        $show_all = true;
    endif;
        
    $html = '';
    global $current_user;
    
    if ( is_user_logged_in() ):    

        if (!$user_id && !$show_all) $user_id = wps_get_user_id();
    
        if (!$show_all):
            $galleries = get_posts(array(
                    'posts_per_page'    => 1000,
                    'post_type'         => 'wps_gallery',
                    'author'			=> $user_id,
                    'orderby'           => 'post_date',
                    'order'             => 'DESC',
                    'post_status'       => 'publish',            
            ));
        else:
            $galleries = get_posts(array(
                    'posts_per_page'    => 1000,
                    'post_type'         => 'wps_gallery',
                    'orderby'           => 'post_date',
                    'order'             => 'DESC',
                    'post_status'       => 'publish',            
            ));
        endif;

        if ($galleries):      

            $items = array();
            foreach ($galleries as $gallery):

                array_push($items, array(
                    'ID' => $gallery->ID, 
                    'post_author' => $gallery->post_author, 
                    'post_title' => $gallery->post_title, 
                    'post_content' => $gallery->post_content,
                    'last_updated' => get_post_meta($gallery->ID, 'wps_gallery_updated', true),
                ));

            endforeach;

            // Sort the array
            $sort = array();
            foreach($items as $k=>$v) {
                $sort['ID'][$k] = $v['ID'];
                $sort['post_title'][$k] = $v['post_title'];
                $sort['last_updated'][$k] = $v['last_updated'];
                $sort['post_author'][$k] = $v['post_author'];
            }
            if ($orderby == 'created')  array_multisort($sort['ID'], SORT_DESC, $sort['post_title'], SORT_ASC, $items);
            if ($orderby == 'title')    array_multisort($sort['post_title'], SORT_ASC, $sort['ID'], SORT_ASC, $items);
            if ($orderby == 'updated')  array_multisort($sort['last_updated'], SORT_DESC, $sort['post_title'], SORT_ASC, $items);

            $wrapper_div = !$scrolling ? 'wps_gallery_items' : 'wps_gallery_items_scrolling';
            $inline_div = !$scrolling ? 'wps_gallery_item_grid' : 'wps_gallery_item_grid_scrolling';
            $html .= '<div id="wps_gallery_grid"><div id="'.$wrapper_div.'">';

            $c=0;

            $wpspro_gallery_page = get_page_link(get_option('wpspro_gallery_page'));
            if (!$wpspro_gallery_page)
                $html .= '<div class="wps_error">'.__('You must set the Gallery page in WPS Setup->Galleries.', WPS2_TEXT_DOMAIN).'</div>';

            $wpspro_gallery_page = $wpspro_gallery_page.wps_query_mark($wpspro_gallery_page);

            $gallery_count = 0;
            foreach ($items as $gallery):

                // Permission to view?
                $friend_status = wps_are_friends($gallery['post_author'], $current_user->ID);
                $is_friend = $friend_status['status'] == 'publish' ? true : false;  

                if ($is_friend || current_user_can('manage_options')):
                
                    $featured_image = get_the_post_thumbnail($gallery['ID'], array($featured_image_size, $featured_image_size));

                    if ($featured_image):

                        $c++;
                        if ($c > $count) break;

                        $url = $wpspro_gallery_page.'user_id='.$gallery['post_author'].'&';
                        $html .= '<div style="padding:'.$padding.'px;" class="'.$inline_div.'">';
                        $html .= '<a title="'.$gallery['post_title'].'" href="'.$url.'gallery_id='.$gallery['ID'].'">'.$featured_image.'</a>';
                        $html .= '</div>';
    
                        $gallery_count++;

                    endif;

                else:

                    if (!$show_all) $html .= $no_permission_text;

                endif;

            endforeach;

            if ($more && !$show_all && $gallery_count):

                $url = $wpspro_gallery_page.'user_id='.$gallery['post_author'].'&';

                $html .= '<div class="'.$inline_div.' wps_gallery_item_grid_scrolling_more" style="margin-left:'.$more_left_adjustment.'px;top:'.(-($featured_image_size/2)+$more_top_adjustment).'px">';
                $html .= '<a href="'.$url.'">'.$more.'</a>';
                $html .= '</div>';

            endif;

            $html .= '</div></div>';

        else:

            $html .= $no_albums;

        endif;
        
    endif;
    
    return $html;
    
}

function wps_gallery_list($atts) {

    // Init
    add_action('wp_footer', 'wps_gallery_init');

    // Shortcode parameters
    extract( shortcode_atts( array(
        'user_id' => 'all',
        'show_all' => true,
        'featured_image_size' => 75, // set to 0 to hide
        'show_description' => true,
        'no_permission_text' => '',
        'no_albums' => __('No albums to view.', WPS2_TEXT_DOMAIN),
        'edit_prompt_text' => __('Click the title to add a description and/or add items...', WPS2_TEXT_DOMAIN),
        'show_count' => false,
        'count' => 30,
        'more' => __('more...', WPS2_TEXT_DOMAIN), // set to false to hide
        'more_top_adjustment' => 0,
        'more_left_adjustment' => 0,
        'orderby' => 'updated', // can by created, title or updated
        'before' => '',
        'after' => '',
    ), $atts, 'wps_gallery_list' ) );
    if (!$featured_image_size) $featured_image_size = -15; // To take into account the padding

    // Check if showing all user's galleries
    if ($user_id == 'all'):
        $user_id = false;
        $show_all = true;
    endif;

    $html = '';
    global $current_user, $wpdb;
    
    if ( is_user_logged_in() ):    

        if (!$user_id) $user_id = wps_get_user_id();
    
        if (!$show_all):
            $galleries = get_posts(array(
                    'posts_per_page'    => 1000,
                    'post_type'         => 'wps_gallery',
                    'author'			=> $user_id,
                    'orderby'           => 'post_date',
                    'order'             => 'DESC',
                    'post_status'       => 'publish',            
            ));    
        else:
            $galleries = get_posts(array(
                    'posts_per_page'    => 1000,
                    'post_type'         => 'wps_gallery',
                    'orderby'           => 'post_date',
                    'order'             => 'DESC',
                    'post_status'       => 'publish',            
            ));    
        endif;

        if ($galleries):      

            $items = array();
            foreach ($galleries as $gallery):

                array_push($items, array(
                    'ID' => $gallery->ID, 
                    'post_author' => $gallery->post_author, 
                    'post_title' => $gallery->post_title, 
                    'post_content' => $gallery->post_content,
                    'last_updated' => get_post_meta($gallery->ID, 'wps_gallery_updated', true),
                ));

            endforeach;

            // Sort the array
            $sort = array();
            foreach($items as $k=>$v) {
                $sort['ID'][$k] = $v['ID'];
                $sort['post_title'][$k] = $v['post_title'];
                $sort['last_updated'][$k] = $v['last_updated'];
                $sort['post_author'][$k] = $v['post_author'];
            }

            if ($orderby == 'created')  array_multisort($sort['ID'], SORT_DESC, $sort['post_title'], SORT_ASC, $items);
            if ($orderby == 'title')    array_multisort($sort['post_title'], SORT_ASC, $sort['ID'], SORT_ASC, $items);
            if ($orderby == 'updated')  array_multisort($sort['last_updated'], SORT_DESC, $sort['post_title'], SORT_ASC, $items);

            $wpspro_gallery_page = get_page_link(get_option('wpspro_gallery_page'));
            if (!$wpspro_gallery_page)
                $html .= '<div class="wps_error">'.__('You must set the Gallery page in WPS Setup->Galleries.', WPS2_TEXT_DOMAIN).'</div>';

            $wpspro_gallery_page = $wpspro_gallery_page.wps_query_mark($wpspro_gallery_page);



            $c = 0;
            foreach ($items as $gallery):

                // Permission to view?
                $friend_status = wps_are_friends($gallery['post_author'], $current_user->ID);
                $is_friend = $friend_status['status'] == 'publish' ? true : false;  

                if ($is_friend || current_user_can('manage_options')):

                    $c++;
                    if ($c > $count) break;

                    $featured_image = get_the_post_thumbnail($gallery['ID'], array($featured_image_size, $featured_image_size));
                    $attachment_count = count(get_children(array('post_parent' => $gallery['ID'])));
                    $attachment_count = ($attachment_count && $show_count) ? ' ('.$attachment_count.')' : '';

                    $url = $wpspro_gallery_page.'user_id='.$gallery['post_author'].'&';
    
                    $html .= '<div class="wps_gallery_item wps_gallery_item_list" style="padding-left:'.($featured_image_size+15).'px">';
                        if ($featured_image_size > 0) $html .= '<div class="wps_gallery_featured_image" style="margin-left:-'.($featured_image_size+15).'px"><a href="'.$url.'gallery_id='.$gallery['ID'].'">'.$featured_image.'</a></div>';
                        $html .= '<div class="wps_gallery_meta">';
                            $html .= '<div class="wps_gallery_list_title"><a href="'.$url.'gallery_id='.$gallery['ID'].'">'.$gallery['post_title'].'</a>'.$attachment_count.'</div>';
                            if ($show_description):
                                if ($gallery['post_content'] != ''):
                                    $html .= '<p class="wps_gallery_description">'.convert_smilies(make_clickable(esc_html($gallery['post_content']))).'</p>';    
                                else:
                                    if ($user_id != $current_user->ID) $edit_prompt_text = '';
                                    $html .= '<p class="wps_gallery_description">'.$edit_prompt_text.'</p>';    
                                endif;
                            endif;
                        $html .= '</div>';
                    $html .= '</div>';

                else:

                    if (!$show_all) $html .= $no_permission_text;

                endif;

            endforeach;

            if ($more && !$show_all):
                $html .= '<p style="margin-left:'.$more_left_adjustment.'px;margin-top:'.$more_top_adjustment.'px"><a href="'.$url.'">'.$more.'</a></p>';
            endif;

        else:

            $html .= $no_albums;

        endif;
        
    endif;
    
    return $html;
    
}

function wps_gallery($atts) {

	$html = '';

    // Shortcode parameters
    extract( shortcode_atts( array(
        'user_id' => false, // set to 'all' for all owners
        'no_permission_text' => '',
        'edit_prompt_text' => __('Click the title to add a description and/or add items...', WPS2_TEXT_DOMAIN),
        'before' => '',
        'after' => '',
    ), $atts, 'wps_gallery' ) );
    
    global $current_user;
    
    if ( is_user_logged_in() ):    

        if (!$user_id) $user_id = wps_get_user_id();

        // Permission to view?
        $friend_status = wps_are_friends($user_id, $current_user->ID);
        $is_friend = $friend_status['status'] == 'publish' ? true : false;  
    
        if ($is_friend || current_user_can('manage_options')):

            if (!isset($_GET['gallery_id'])):

                // Show all albums
                $html .= wps_gallery_all($atts, $user_id);

            else:

                // Show single album
                $html .= wps_gallery_single($_GET['gallery_id'], $atts, $user_id);

            endif;
    
        else:
    
            $html .= $no_permission_text;
    
        endif;

        if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);
        
    endif;

	return $html;
}

function wps_gallery_single($gallery_id, $atts, $user_id) {

    // Init
    add_action('wp_footer', 'wps_gallery_init');

    // Shortcode parameters
    extract( shortcode_atts( array(
        'class' => false,
        'edit_and_cancel_class' => '',
        'show_slideshow' => 0,
        'slideshow_link' => __('View slideshow', WPS2_TEXT_DOMAIN),
        'slideshow_hide_link' => __('Close slideshow', WPS2_TEXT_DOMAIN),
        'no_permission_text' => '',
        'edit_text' => __('Edit description', WPS2_TEXT_DOMAIN),
        'delete_text' => __('Delete', WPS2_TEXT_DOMAIN),
        'item_size' => 150, // size of items in gallery
        'show_owner' => __('Gallery owner: %s', WPS2_TEXT_DOMAIN), // set to false to hide
        'back_to' => __('Back to Albums', WPS2_TEXT_DOMAIN),
        'add_text' => __('Click here to select files to add, then click on Upload below...', WPS2_TEXT_DOMAIN),
        'button_label' => __('Upload', WPS2_TEXT_DOMAIN),
        'cancel_button_label' => __('Cancel', WPS2_TEXT_DOMAIN),
        'update_button_label' => __('Update', WPS2_TEXT_DOMAIN),
        'edit_prompt_content' => __('Enter a description...', WPS2_TEXT_DOMAIN),
        'comment_avatar_size' => 64,
        'comment_label' => __('Add Comment', WPS2_TEXT_DOMAIN),
        'link' => true, // link to comment author?
        'date_format' => __('%s ago', WPS2_TEXT_DOMAIN),
        'show_allowed_types' => __('You can upload the following file types: %s,%s.', WPS2_TEXT_DOMAIN),
        'allowed_image_types' => 'jpg,png,gif,jpeg,svg',
        'allowed_document_types' => 'txt,rtf,pdf',
    ), $atts, 'wps_gallery_single' ) );

    global $current_user;
    if (!$user_id) $user_id = wps_get_user_id();

    $html = '';

    $url = get_page_link(get_option('wpspro_gallery_page'));
    if (!$url)
        $html .= '<div class="wps_error">'.__('You must set the Gallery page in WPS Setup->Galleries.', WPS2_TEXT_DOMAIN).'</div>';
    
    $url = $url.wps_query_mark($url);
    $url .= 'user_id='.$user_id;

    $gallery = get_post($gallery_id);

    $html .= '<a href="'.$url.'">'.$back_to.'</a>';

    if ($gallery):      

        // Permission to view?
        $friend_status = wps_are_friends($gallery->post_author, $current_user->ID);
        $is_friend = $friend_status['status'] == 'publish' ? true : false;  

        if ($is_friend || current_user_can('manage_options')):
    
            // Get allowed file extensions
            $image_types = explode(',', $allowed_image_types);
            $document_types = explode(',', $allowed_document_types);

            // admin options
            if ($gallery->post_author == $current_user->ID || current_user_can('manage_options')):
                $html .= ' | <a id="wps_gallery_edit" href="javascript:void(0)">'.$edit_text.'</a>';
                $html .= ' | <a id="wps_gallery_delete" rel="'.$gallery->ID.'" href="javascript:void(0)">'.$delete_text.'</a>';

                // Do update from edit
                if (isset($_POST['wps_gallery_edit_title']) && $_POST['wps_gallery_edit_title'] != ''):

                    $post = array(
                        'ID' => $gallery->ID,
                        'post_content'   => $_POST['wps_gallery_edit_content'],
                        'post_name'      => sanitize_title_with_dashes($_POST['wps_gallery_edit_title']),
                        'post_title'     => $_POST['wps_gallery_edit_title'],
                    );  
                    wp_update_post( $post ); 
                    $gallery = get_post($gallery->ID);

                endif;

                $the_form = '<div id="wps_gallery_edit_div">';
                $the_form .= '<form id="wps_gallery_edit_form" action="" method="POST" style="display:none">';
                $the_form .= '<input type="text" placeholder="'.$gallery->post_title.'" id="wps_gallery_edit_title" name="wps_gallery_edit_title" value="'.$gallery->post_title.'" /><br />';
                $content = $gallery->post_content != '' ? $gallery->post_content : '';
                $the_form .= '<textarea placeholder="'.$edit_prompt_content.'" id="wps_gallery_edit_content" name="wps_gallery_edit_content">'.$gallery->post_content.'</textarea>';
                $the_form .= '<input style="float:left" class="wps_submit '.$edit_and_cancel_class.'" type="submit" id="wps_gallery_edit_button" value="'.$update_button_label.'" />';
                $the_form .= '</form><input style="display:none" class="wps_submit '.$edit_and_cancel_class.'" type="submit" id="wps_gallery_edit_cancel_button" value="'.$cancel_button_label.'" />';
                $the_form .= '</div>';
                $html .= $the_form;

            endif;

            if ($show_owner):
                $html .= '<h2 class="wps_gallery_owner">'.sprintf($show_owner, wps_display_name(array('user_id'=>$user_id, 'link'=>1))).'</h2>';
            endif;

            $html .= '<div id="wps_gallery_album">';

                $html .= '<h2 class="wps_gallery_title">'.$gallery->post_title.'</h2>';
                $html .= '<p class="wps_gallery_description">'.convert_smilies(make_clickable(esc_html($gallery->post_content))).'</p>';
    
                // Show attachments

                $attachments = get_posts( array(
                    'post_type' => 'attachment',
                    'posts_per_page' => -1,
                    'post_parent' => $gallery_id,
                ) );

                // Slideshow
                $wps_slideshow_display = '';
                $wps_slideshow_hide_display = 'style="display:none"';
                if ($slideshow_link):
                    if ($show_slideshow):
                        $wps_slideshow_display = 'style="display:none"';
                        $wps_slideshow_hide_display = '';
                    endif;
    
                    $html .= '<a href="javascript:void(0)" '.$wps_slideshow_display.' id="wps_slideshow">'.$slideshow_link.'</a>';
                    $html .= '<a href="javascript:void(0)" '.$wps_slideshow_hide_display.' id="wps_slideshow_hide">'.$slideshow_hide_link.'</a>';
                    $html .= '<div id="wps_slideshow_div" '.$wps_slideshow_hide_display.'>';
                        $html .= '<input id="wps_slideshow_ptr" style="display:none" value="0" />';
                        $html .= '<div id="wps_slideshow_navigation">';
                            $html .= '<a href="javascript:void(0)" id="wps_slideshow_previous"><img src="'.plugins_url('images/previous'.get_option('wpspro_icon_colors').'.png', __FILE__).'" /></a>';
                            $html .= '<a href="javascript:void(0)" id="wps_slideshow_zoom"><img src="'.plugins_url('images/zoom'.get_option('wpspro_icon_colors').'.png', __FILE__).'" /></a>';
                            $html .= '<a href="javascript:void(0)" id="wps_slideshow_next"><img src="'.plugins_url('images/next'.get_option('wpspro_icon_colors').'.png', __FILE__).'" /></a>';
                            $html .= '<div id="wps_slideshow_pagination">';
                            $i=0;
                            $images_html = '';
                            foreach ( $attachments as $attachment ) {
                                $image_src = wp_get_attachment_image_src( $attachment->ID, 'full' );
                                $display = ($i==0 && $show_slideshow) ? '' : 'style="display:none"';
                                $images_html .= '<div data-width="'.$image_src[1].'" data-height="'.$image_src[2].'" id="wps_slideshow_'.$i.'_zoom" style="display:none">'.$image_src[0].'</div>';
                                $images_html .= '<img id="wps_slideshow_'.$i.'" class="wps_slideshow_image" '.$display.' src="'.$image_src[0].'" data-url="'.$image_src[0].'" />';
                                $i++;
                            }
                            $p=0;
                            while ($p<$i):
                                $image = ($p == 0) ? 'page_current' : 'page';
                                $html .= '<a href="javascript:void(0)" class="wps_slideshow_pick" rel="'.$p.'"><img id="wps_slideshow_pick_'.$p.'" src="'.plugins_url('images/'.$image.'.png', __FILE__).'" /></a>';
                                $p++;
                            endwhile;
                            $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div id="wps_slideshow_images">';
                            $html .= $images_html;
                        $html .= '</div>';
                        $html .= '<input id="wps_slideshow_max" style="display:none" value="'.($i-1).'" />';
                        $html .= '<input id="wps_slideshow_tmp" style="display:none" />';
                    $html .= '</div>';
                endif;

    
                $item_html = '';
                if ( $attachments ) {

                    $item_html .= '<div id="wps_gallery_attachment_dialog"></div>';            
                    $item_html .= '<input type="hidden" id="wps_gallery_attachment_ptr" value="1">';            
                    $item_html .= '<input type="hidden" id="wps_post_id" value="'.$gallery_id.'">';            
                    $item_html .= '<div id="wps_gallery_items" '.$wps_slideshow_display.'>';

                    // ptr counter
                    $c=0;

                    // featured image
                    $featured_image = get_post_thumbnail_id($gallery_id);

                    foreach ( $attachments as $attachment ) {

                        // Get extensions and file type
                        $file_ext = strtolower(substr(strrchr(get_attached_file($attachment->ID),'.'),1));

                        // Images, setting defaults (use filters to change)
                        $valid_image_exts = $image_types;
                        $valid_image_exts = apply_filters( 'wps_gallery_attachments_valid_image_extensions_filter', $valid_image_exts, $atts );

                        if (in_array($file_ext, $valid_image_exts)):
                            // Increase ptr count for images
                            $c++;
                            $featured_class = ($user_id == $current_user->ID && $featured_image == $attachment->ID) ? 'wps_gallery_featured' : 'wps_gallery_not_featured';
                            $item_html .= '<div id="wps_gallery_item_attachment_by_id_'.$attachment->ID.'" class="wps_gallery_item_div '.$featured_class.'" style="width:'.$item_size.'px; height: '.$item_size.'px;">';
                                $item_html .= '<div rel="'.$c.'" id="wps_gallery_item_attachment_'.$c.'" class="wps_gallery_item_attachment">';
                                    $item_html .= wp_get_attachment_image($attachment->ID, array($item_size, $item_size));            
                                    $image_src = wp_get_attachment_image_src( $attachment->ID, 'full' );
                                    $item_html .= '<div data-width="'.$image_src[1].'" data-height="'.$image_src[2].'" class="wps_gallery_item_attachment_full">'.$image_src[0].'</div>';
                                $item_html .= '</div>';
                                $gallery = get_post($gallery_id);
                                if ($gallery->post_author == $current_user->ID || current_user_can('manage_options')) $item_html .= '<img class="wps_gallery_delete_item_icon wps_gallery_delete_attachment" title="'.__('Delete', WPS2_TEXT_DOMAIN).'" rel="'.$attachment->ID.'" style="cursor:pointer;position:absolute;height:15px;width:15px;right:5px;top:5px;" src="'.plugins_url('../../wp-symposium-pro-extensions/wp-symposium-pro-gallery/images/trash.png', __FILE__).'" />';                
                                if ( ($gallery->post_author == $current_user->ID || current_user_can('manage_options')) && ($featured_image != $attachment->ID) ) $item_html .= '<img class="wps_gallery_featured_item_icon wps_gallery_feature_attachment" title="'.__('Set as Featured Image', WPS2_TEXT_DOMAIN).'" rel="'.$attachment->ID.'" style="cursor:pointer;position:absolute;height:15px;width:15px;left:5px;top:5px;" src="'.plugins_url('../../wp-symposium-pro-extensions/wp-symposium-pro-gallery/images/star.png', __FILE__).'" />';                
                            $item_html .= '</div>';
                        endif;

                        // Other (documents), setting defaults (use filters to change)
                        $valid_document_exts = $document_types;
                        $valid_document_exts = apply_filters( 'wps_gallery_attachments_valid_document_extensions_filter', $valid_document_exts, $atts );

                        if (in_array($file_ext, $valid_document_exts)):
                            $item_html .= '<div id="wps_gallery_item_attachment_by_id_'.$attachment->ID.'" class="wps_gallery_item_doc_div wps_gallery_not_featured" style="width:'.$item_size.'px; height: '.$item_size.'px;overflow:hidden;">';
                                    $item_html .= '<a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">'.basename(get_attached_file($attachment->ID)).'</a>';
                                    if ($gallery->post_author == $current_user->ID || current_user_can('manage_options')) $item_html .= '<img title="'.__('Delete', WPS2_TEXT_DOMAIN).'" class="wps_gallery_delete_attachment" rel="'.$attachment->ID.'" style="cursor:pointer;position:absolute;height:15px;width:15px;right:4px;top:5px;" src="'.plugins_url('../../wp-symposium-pro-extensions/wp-symposium-pro-gallery/images/trash.png', __FILE__).'" />';                
                            $item_html .= '</div>';
                        endif;

                    }

                    $item_html .= '</div>';

                    $item_html .= '<input type="hidden" id="wps_gallery_attachment_ptr_max" value="'.$c.'">';            

                }

                $html .= $item_html;

                // Form to add new files

                if ($user_id == $current_user->ID):
                    $form_html = '<div id="wps_gallery_upload_form" '.$wps_slideshow_display.'>';
                    $form_html .= '<form enctype="multipart/form-data" id="wps_gallery_theuploadform">';

                    $form_html .= '<input type="hidden" name="action" value="wps_gallery_add" />';
                    $form_html .= '<input type="hidden" name="post_id" value="'.$gallery_id.'" />';

                    $form_html .= '<input type="hidden" name="wps_image_types" value="'.$allowed_image_types.'" />';                
                    $form_html .= '<input type="hidden" name="wps_document_types" value="'.$allowed_document_types.'" />';                

                    $form_html .= '<div class="wps_gallery_upload_prompt"><input title="'.$add_text.'" id="wps_gallery_upload" name="wps_gallery_upload[]" size="50" multiple type="file" /></div>';

                    $form_html .= '<input id="wps_gallery_attachment_button" type="submit" class="wps_submit '.$class.'" value="'.$button_label.'" />';

                    if ($show_allowed_types) $form_html .= '<p class="wps_allowed_types">'.sprintf($show_allowed_types, $allowed_image_types, $allowed_document_types).'</p>';

                    $form_html .= '</form></div>';

                    $html .= $form_html;
                endif;

                // Comments

                $args = array(
                    'post_id' => $gallery_id,
                    'orderby' => 'ID',
                    'order' => 'ASC',
                );
                $comments = get_comments($args);
                $item_html = '<div id="wps_gallery_comments">';

                if ($comments) {
                    foreach($comments as $comment) :
                        $item_html .= '<div id="wps_comment_'.$comment->comment_ID.'" class="wps_activity_comment" style="position:relative;padding-left: '.($comment_avatar_size+10).'px">';

                            // Settings
                            if ($comment->user_id == $current_user->ID || current_user_can('manage_options')):
                                $item_html .= '<div class="wps_gallery_comment_settings">';
                                    $item_html .= '<img style="height:15px;width:15px;" src="'.plugins_url('images/wrench'.get_option('wpspro_icon_colors').'.png', __FILE__).'" />';
                                $item_html .= '</div>';
                                $item_html .= '<div class="wps_gallery_comment_settings_delete_option" style="display:none">';
                                    $item_html .= '<a class="wps_gallery_comment_settings_delete" rel="'.$comment->comment_ID.'" href="javascript:void(0);">'.__('Delete comment', WPS2_TEXT_DOMAIN).'</a>';
                                $item_html .= '</div>';
                            endif;

                            // Avatar
                            $item_html .= '<div class="wps_gallery_activity_post_comment_avatar" style="float:left; margin-left: -'.($comment_avatar_size+10).'px">';
                                $item_html .= user_avatar_get_avatar($comment->user_id, $comment_avatar_size);
                            $item_html .= '</div>';

                            // Name and date
                            $item_html .= wps_display_name(array('user_id'=>$comment->user_id, 'link'=>$link));
                            $item_html .= '<br />';
                            $item_html .= '<div class="wps_ago">'.sprintf($date_format, human_time_diff(strtotime($comment->comment_date), current_time('timestamp', 0)), WPS2_TEXT_DOMAIN).'</div>';

                            // Any other meta
                            $item_html = apply_filters( 'wps_gallery_comment_meta_filter', $item_html, $atts, $gallery_id, $comment->comment_ID, $user_id, $current_user->ID );                                    

                            // The Comment
                            $item_html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($comment->comment_content)))));

                        $item_html .= '</div>';
                    endforeach;
                }

                $item_html .= '</div>';

                // Add new comment	
                $friend_status = wps_are_friends($user_id, $current_user->ID);
                $is_friend = $friend_status['status'] == 'publish' ? true : false;  
                if (is_user_logged_in() && $is_friend ):						
                    $item_html .= '<div class="wps_gallery_activity_post_comment_div" '.$wps_slideshow_display.'>';
                        $item_html .= '<input type="hidden" id="wps_activity_plugins_url" value="'.plugins_url( '', __FILE__ ).'" />';
                        $item_html .= '<textarea class="wps_gallery_activity_post_comment" id="post_comment_'.$gallery_id.'"></textarea>';
                        $item_html .= '<input class="wps_submit wps_gallery_activity_post_comment_button '.$class.'" rel="'.$gallery_id.'" type="submit" value="'.$comment_label.'" />';
                    $item_html .= '</div>';
                endif;

                $html .= $item_html;

            $html .= '</div>';

        else:
    
            $html .= '<p>'.$no_permission_text.'</p>';
                
        endif;

    endif;
    
    return $html;
    
}

function wps_gallery_all($atts, $user_id) {

    // Init
    add_action('wp_footer', 'wps_gallery_init');

    // Shortcode parameters
    extract( shortcode_atts( array(
        'user_id' => false, // set to all for all owners
        'edit_and_cancel_class' => '',
        'empty_gallery' => __('No albums created yet...', WPS2_TEXT_DOMAIN),
        'create_placeholder' => __('Enter a title for your album...', WPS2_TEXT_DOMAIN),
        'create_label' => __('Create a new album...', WPS2_TEXT_DOMAIN),
        'create_button_label' => __('Create', WPS2_TEXT_DOMAIN),
        'cancel_button_label' => __('Cancel', WPS2_TEXT_DOMAIN),
        'edit_prompt_text' => __('Click the title to add a description and/or add items...', WPS2_TEXT_DOMAIN),
        'featured_image_size' => 150, // set to 0 to hide
        'show_owner' => __('Gallery owner: %s', WPS2_TEXT_DOMAIN), // set to false to hide
        'show_description' => true,
        'show_count' => true,
        'count' => 100,
        'orderby' => 'updated', // can by created, title or updated
    ), $atts, 'wps_gallery_all' ) );
    if (!$featured_image_size) $featured_image_size = -15; // To take into account the padding

    $html = '';
    
    global $current_user, $wpdb;
    if (!$user_id && $user_id != 'all') $user_id = wps_get_user_id();

    // Create new album
    if ($user_id == $current_user->ID):
        $the_form = '<input type="submit" id="wps_gallery_create_gallery_button" class="wps_submit" value="'.$create_label.'" />';
        $the_form .= '<div id="wps_gallery_create_div" style="display:none">';
        $the_form .= '<input type="hidden" name="action" value="wps_gallery_create" />';
        $the_form .= '<input type="text" placeholder="'.$create_placeholder.'" id="wps_gallery_create_title" /><br />';
        $the_form .= '<input style="float:left" class="wps_submit '.$edit_and_cancel_class.'" type="submit" id="wps_gallery_create_button" value="'.$create_button_label.'" />';
        $the_form .= '<input style="display:none" class="wps_submit '.$edit_and_cancel_class.'" type="submit" id="wps_gallery_cancel_button" value="'.$cancel_button_label.'" /></div>';
    
        $html .= $the_form;
    endif;
    
    if ($show_owner):
        $html .= '<h2 class="wps_gallery_owner">'.sprintf($show_owner, wps_display_name(array('user_id'=>$user_id, 'link'=>1))).'</h2>';
    endif;
    
    if ($user_id != 'all'):
        $galleries = get_posts(array(
                'posts_per_page'    => -1,
                'post_type'         => 'wps_gallery',
                'author'			=> $user_id,
                'post_status'       => 'publish',            
        ));
    else:
        $galleries = get_posts(array(
                'posts_per_page'    => -1,
                'post_type'         => 'wps_gallery',
                'post_status'       => 'publish',            
        ));
    endif;
    
    if ($galleries):      

        $items = array();
        foreach ($galleries as $gallery):

            array_push($items, array(
                'ID' => $gallery->ID, 
                'post_author' => $gallery->post_author, 
                'post_title' => $gallery->post_title, 
                'post_content' => $gallery->post_content,
                'last_updated' => get_post_meta($gallery->ID, 'wps_gallery_updated', true),
            ));

        endforeach;

        // Sort the array
        $sort = array();
        foreach($items as $k=>$v) {
            $sort['ID'][$k] = $v['ID'];
            $sort['post_title'][$k] = $v['post_title'];
            $sort['last_updated'][$k] = $v['last_updated'];
        }
    
        if ($orderby == 'created')  array_multisort($sort['ID'], SORT_DESC, $sort['post_title'], SORT_ASC, $items);
        if ($orderby == 'title')    array_multisort($sort['post_title'], SORT_ASC, $sort['ID'], SORT_ASC, $items);
        if ($orderby == 'updated')  array_multisort($sort['last_updated'], SORT_DESC, $sort['post_title'], SORT_ASC, $items);

        $url = get_page_link(get_option('wpspro_gallery_page'));
        if (!$url)
            $html .= '<div class="wps_error">'.__('You must set the Gallery page in WPS Setup->Galleries.', WPS2_TEXT_DOMAIN).'</div>';

        $url = $url.wps_query_mark($url);
        $url .= 'user_id='.$user_id.'&';
    
        $c=0;
    
        foreach ($items as $gallery):
    
            $c++;
            if ($c > $count) break;
    
            $featured_image = get_the_post_thumbnail($gallery['ID'], array($featured_image_size, $featured_image_size));
            $attachment_count = count(get_children(array('post_parent' => $gallery['ID'])));
            $attachment_count = ($attachment_count && $show_count) ? ' ('.$attachment_count.')' : '';

            $html .= '<div class="wps_gallery_item" style="padding-left:'.($featured_image_size+15).'px">';
                if ($featured_image_size > 0) $html .= '<div class="wps_gallery_featured_image" style="margin-left:-'.($featured_image_size+15).'px"><a href="'.$url.'gallery_id='.$gallery['ID'].'">'.$featured_image.'</a></div>';
                $html .= '<div class="wps_gallery_meta">';
                    $html .= '<div class="wps_gallery_title"><a href="'.$url.'gallery_id='.$gallery['ID'].'">'.$gallery['post_title'].'</a>'.$attachment_count.'</div>';
                    if ($show_description):
                        if ($gallery['post_content'] != ''):
                            $html .= '<p class="wps_gallery_description">'.convert_smilies(make_clickable(esc_html($gallery['post_content']))).'</p>';    
                        else:
                            if ($gallery['post_author'] == $current_user->ID) {
                                $html .= '<p class="wps_gallery_description">'.$edit_prompt_text.'</p>';    
                            }
                        endif;
                    endif;
                $html .= '</div>';
            $html .= '</div>';

        endforeach;
    
    else:
    
        $html .= '<p>'.$empty_gallery.'</p>';
    
    endif;
    
    return $html;
    
}

if (!is_admin()) {
	add_shortcode(WPS_PREFIX.'-gallery', 'wps_gallery');
	add_shortcode(WPS_PREFIX.'-gallery-list', 'wps_gallery_list');
	add_shortcode(WPS_PREFIX.'-gallery-grid', 'wps_gallery_grid');
}

?>
