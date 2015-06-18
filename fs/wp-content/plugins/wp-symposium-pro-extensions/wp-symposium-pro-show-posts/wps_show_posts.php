<?php
// Enable shortcodes in text/HTML widgets
add_filter('widget_text', 'do_shortcode');

// Shortcodes
require_once('wps_show_posts_shortcodes.php');

?>