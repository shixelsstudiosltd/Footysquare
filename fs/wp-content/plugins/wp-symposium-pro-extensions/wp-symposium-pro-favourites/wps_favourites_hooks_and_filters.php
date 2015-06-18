<?php
// Add Favourites icon to activity
add_filter('wps_activity_item_settings_filter', 'wps_favourites_activity_icon', 10, 5); 
function wps_favourites_activity_icon($settings, $atts, $item, $user_id, $this_user) {

    if ( is_user_logged_in() ) {

        $favs = get_user_meta($this_user, 'wps_favourites', true);

        $settings .= '<div class="wps_activity_favourite_icon">';
            $state = ($favs && in_array($item->ID, $favs)) ? '' : '_empty';
            $settings .= '<img style="height:15px;width:15px;" class="wps_favourite" data-state="'.$state.'" rel="'.$item->ID.'" src="'.plugins_url('images/star'.$state.'.png', __FILE__).'" />';
        $settings .= '</div>';
        
    }

	return $settings;

}


?>