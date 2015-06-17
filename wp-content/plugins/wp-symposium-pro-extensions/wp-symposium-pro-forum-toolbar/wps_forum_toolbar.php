<?php
// Admin
if (is_admin())
    require_once('wps_toolbar_admin.php');

// Init
add_action('wps_forum_init_hook', 'wps_forum_toolbar_init');
function wps_forum_toolbar_init() {

    wp_enqueue_script('wps-forum-toolbar-js', plugins_url('wps_forum_toolbar.js', __FILE__), array('jquery'));    

    if ( get_option( 'wps_pro_toolbar' ) == 'bbcode' ):          
        wp_enqueue_style('wps-forum-toolbar-css', plugins_url('wps_forum_toolbar.css', __FILE__), 'css');
        wp_localize_script( 'wps-forum-toolbar-js', 'wps_forum_toolbar', array( 
            'mode' => 'bbcode',
            'bbcode_url' => __('Enter a URL...', WPS2_TEXT_DOMAIN),
            'bbcode_label' => __('Enter a label...', WPS2_TEXT_DOMAIN)
        ));     
    else:
        wp_enqueue_style('wps-forum-toolbar-css', plugins_url('wps_forum_toolbar.css', __FILE__), 'css');
        wp_enqueue_script('wps-redactor-js', plugins_url('redactor/redactor.min.js', __FILE__), array('jquery'));   
        wp_enqueue_script('wps-redactor-fontcolor-js', plugins_url('redactor/fontcolor.js', __FILE__), array('jquery'));    
        wp_enqueue_style('wps-redactor-css', plugins_url('redactor/redactor.css', __FILE__), 'css');        
        $wps_pro_toolbar_icons = ($value = get_option('wps_pro_toolbar_icons')) ? $value : "bold,italic,deleted,unorderedlist,orderedlist,link";
        wp_localize_script( 'wps-redactor-js', 'wps_forum_toolbar', array( 
            'iframe_css' => plugins_url('redactor/redactor-iframe.css', __FILE__),
            'mode' => 'wysiwyg',
            'icons' => stripslashes($wps_pro_toolbar_icons),
        ));     
    endif;

}

/**
 * Filter to add toolbar for new post
 **/
if ( get_option( 'wps_pro_toolbar' ) != 'bbcode' ):

    /**
     * WYSIWYG
     **/
    add_filter('wps_forum_item_content_filter', 'wps_forum_item_content_filter_wysiwyg',10,2);
    function wps_forum_item_content_filter_wysiwyg($the_post_content, $atts) {
        return wps_bbcode_replace(convert_smilies(make_clickable(wpautop(wps_strip_tags($the_post_content)))));
    }

else:

    /**
     * BB Codes
     **/
    add_filter('wps_forum_item_content_filter', 'wps_forum_item_content_filter_wysiwyg',10,2);
    function wps_forum_item_content_filter_wysiwyg($the_post_content, $atts) {
        return wps_bbcode_replace(convert_smilies(make_clickable(wpautop(wps_strip_tags($the_post_content)))));
    }

    /**
     * Filter to add toolbar for new post
     **/
    add_filter('wps_forum_post_pre_form_filter', 'wps_forum_post_pre_form_filter_add_toolbar',10,4);
    function wps_forum_post_pre_form_filter_add_toolbar($form_html, $atts, $current_user_id, $term) {
        return $form_html.wps_add_toolbar_icons('wps_forum_post_textarea');
    }

    /**
     * Filter to add toolbar for new comment
     **/
    add_filter('wps_forum_comment_pre_form_filter', 'wps_forum_post_pre_form_filter_add_comment_toolbar',10,3);
    function wps_forum_post_pre_form_filter_add_comment_toolbar($form_html, $atts, $current_user_id) {
        return $form_html.wps_add_toolbar_icons('wps_forum_comment');
    }

    /**
     * Filter to add toolbar for edit post
     **/
    add_filter('wps_forum_post_edit_pre_form_filter', 'wps_forum_post_pre_form_filter_add_toolbar_edit',10,4);
    function wps_forum_post_pre_form_filter_add_toolbar_edit($form_html, $atts, $current_user_id, $post_id) {
        return $form_html.wps_add_toolbar_icons('wps_forum_post_edit_textarea');
    }

    /**
     * Filter to add toolbar for edit comment
     **/
    add_filter('wps_forum_comment_edit_pre_form_filter', 'wps_forum_post_pre_form_filter_add_comment_toolbar_edit',10,3);
    function wps_forum_post_pre_form_filter_add_comment_toolbar_edit($form_html, $atts, $current_user_id) {
        return $form_html.wps_add_toolbar_icons('wps_forum_comment_edit_textarea');
    }

endif;

function wps_add_toolbar_icons($rel) {
    $form_html = '<div class="__wps__toolbar">';
    $form_html .= '<div class="__wps__toolbar_code" rel="'.$rel.'"><img src="'.plugins_url('code'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" style="width:15px; height:15px;" /></div>';
    $form_html .= '<div class="__wps__toolbar_quote" rel="'.$rel.'"><img src="'.plugins_url('quote'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" style="width:15px; height:15px;" /></div>';
    $form_html .= '<div class="__wps__toolbar_ul_li" rel="'.$rel.'"><img src="'.plugins_url('ul_li'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" style="width:15px; height:15px;" /></div>';
    $form_html .= '<div class="__wps__toolbar_url" rel="'.$rel.'"><img src="'.plugins_url('url'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" style="width:15px; height:15px;" /></div>';
    $form_html .= '<div class="__wps__toolbar_center" rel="'.$rel.'"><img src="'.plugins_url('center'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" style="width:15px; height:15px;" /></div>';
    $form_html .= '<div class="__wps__toolbar_underline" rel="'.$rel.'"><img src="'.plugins_url('underline'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" style="width:15px; height:15px;" /></div>';
    $form_html .= '<div class="__wps__toolbar_italic" rel="'.$rel.'"><img src="'.plugins_url('italic'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" style="width:15px; height:15px;" /></div>';
    $form_html .= '<div class="__wps__toolbar_bold" rel="'.$rel.'"><img src="'.plugins_url('bold'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" style="width:15px; height:15px;" /></div>';
    $form_html .= '</div>';     
    return $form_html;
}

?>