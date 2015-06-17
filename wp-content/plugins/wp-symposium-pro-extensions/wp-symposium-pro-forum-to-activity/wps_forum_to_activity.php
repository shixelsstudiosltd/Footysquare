<?php
/**
 * Add field to new forum post form to pass new shortcode option (post_to_activity_text)
 **/
add_filter('wps_forum_post_pre_form_filter', 'wps_add_new_forum_post_to_activity_field',10,4);
function wps_add_new_forum_post_to_activity_field($form_html, $atts, $current_user_id, $term) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'post_to_activity_text' => "Started %s: %s",
    ), $atts, 'wps_forum_post' ) );    

    return $form_html.'<input type="hidden" name="post_to_activity_text" value="'.$post_to_activity_text.'" />';
}

/**
 * Add field to new forum comment form to pass new shortcode option (post_to_activity_text)
 **/
add_filter('wps_forum_comment_pre_form_filter', 'wps_add_new_forum_comment_to_activity_field',10,3);
function wps_add_new_forum_comment_to_activity_field($form_html, $atts, $current_user_id) {

    // Shortcode parameters
    extract( shortcode_atts( array(
        'post_to_activity_text' => "Replied to %s: %s",
    ), $atts, 'wps_forum_comment' ) );    

    return $form_html.'<input type="hidden" name="post_to_activity_text" value="'.$post_to_activity_text.'" />';
}

/**
 * Adds activity entry when a user adds a new forum post
 **/
add_action( 'wps_forum_post_add_hook', 'wps_add_new_forum_post_to_activity', 10, 3 );
function wps_add_new_forum_post_to_activity($the_post, $the_files, $new_id) {

    $post_terms = wp_get_object_terms( $new_id, 'wps_forum' );
    $term = $post_terms[0];
    if (!wps_get_term_meta( $term->term_id, 'wps_exclude_from_activity', true)):
    
      $new_post = get_post($new_id);
      if ( wps_using_permalinks() ):
          $url = '/'.$term->slug.'/'.$new_post->post_name;
      else:
          $forum_page_id = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
          $url = "/?page_id=".$forum_page_id."&topic=".$new_post->post_name;
      endif;

      $new_activity_title = sprintf($the_post['post_to_activity_text'], $new_post->post_title, get_bloginfo('url').$url);

      $post = array(
        'post_title'     => $new_activity_title,
        'post_status'    => 'publish',
        'author'         => $new_post->post_author,
        'post_type'      => 'wps_activity',
        'post_author'    => $new_post->post_author,
        'ping_status'    => 'closed',
        'comment_status' => 'open',
      );  
      $new_id = wp_insert_post( $post );

      if ($new_id):

          update_post_meta( $new_id, 'wps_target', $new_post->post_author );

      endif;

    endif;

}

/**
 * Adds activity entry when a user adds a new forum comment
 **/
add_action('wps_forum_comment_add_hook', 'wps_add_new_forum_comment_to_activity',10,4);
function wps_add_new_forum_comment_to_activity($the_comment, $the_files, $post_id, $new_id) {

    global $current_user;

    if (isset($the_comment['post_to_activity_text'])):

      $post_id = $the_comment['post_id'];
      $post_terms = wp_get_object_terms( $post_id, 'wps_forum' );
      $term = $post_terms[0];
      if (!wps_get_term_meta( $term->term_id, 'wps_exclude_from_activity', true)):

        $original_post = get_post($post_id);
        if ( wps_using_permalinks() ):
            $url = '/'.$term->slug.'/'.$original_post->post_name;
        else:
            $forum_page_id = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
            $url = "/?page_id=".$forum_page_id."&topic=".$original_post->post_name;
        endif;

        $new_activity_title = sprintf($the_comment['post_to_activity_text'], $original_post->post_title, get_bloginfo('url').$url);

        $post = array(
          'post_title'     => $new_activity_title,
          'post_status'    => 'publish',
          'author'         => $current_user->ID,
          'post_type'      => 'wps_activity',
          'post_author'    => $current_user->ID,
          'ping_status'    => 'closed',
          'comment_status' => 'open',
        );  
        $new_id = wp_insert_post( $post );

        if ($new_id):

            update_post_meta( $new_id, 'wps_target', $current_user->ID );

        endif;

      endif;

    endif;

}

// Add exclusion to forum setup edit form
add_action('wps_forum_taxonomy_metadata_edit_hook', 'wps_forum_taxonomy_metadata_exclude_from_activity', 10, 1);
function wps_forum_taxonomy_metadata_exclude_from_activity($tag) {

  ?>
  <tr class="form-field">
    <th scope="row" valign="top">
      <h2><?php _e('Forum To Activity', WPS2_TEXT_DOMAIN); ?></h2>
    </th>
    <td></td>
  </tr>   <tr class="form-field">
    <th scope="row" valign="top">
      <label for="wps_forum_order"><?php _e('Activity', WPS2_TEXT_DOMAIN); ?></label>
    </th>
    <td>
      <?php

      $exclude = wps_get_term_meta( $tag->term_id, 'wps_exclude_from_activity', true);
      echo '<input type="checkbox" id="wps_exclude_from_activity" style="width:10px" name="wps_exclude_from_activity" ';
      if ($exclude) echo 'CHECKED ';
      echo ' /> ';
      ?>
      <span class="description"><?php _e('Do not post to profile activity.', WPS2_TEXT_DOMAIN); ?></span>
    </td>
  </tr> 
  <?php
}

add_action('wps_forum_taxonomy_metadata_edit_roles_save_hook', 'wps_forum_taxonomy_metadata_save_exclude_from_activity', 10, 2);
function wps_forum_taxonomy_metadata_save_exclude_from_activity($term_id, $the_post) {

  if (isset($the_post['wps_exclude_from_activity'])):
    wps_update_term_meta( $term_id, 'wps_exclude_from_activity', true );
  else:
    wps_delete_term_meta( $term_id, 'wps_exclude_from_activity' );
  endif;

}

?>