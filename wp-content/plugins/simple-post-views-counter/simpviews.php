<?php
/*
  Plugin Name: Simple Post Views Counter
  Plugin URI: http://yooplugins.com/
  Description: This plugin will enable you to display how many times a post has been viewed. The hits/views are displayed in the posts entry meta. Please refer to the included readme file for proper install instructions and use.
  Version: 1.3
  Author: RSPublishing
  Author URI: http://yooplugins.com/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/*
  Copyright 2012-2014  Rynaldo Stoltz  (email : support@yooplugins.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

register_activation_hook(__FILE__, spvco_install());
Register_uninstall_hook(__FILE__, spvco_drop());

function spvco_install() {
    global $wpdb;
    $table = $wpdb->prefix . "simpleviews";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE " . $table .
                " ( UNIQUE KEY id (post_id), post_id int(10) NOT NULL,
             view int(10),
            view_datetime datetime NOT NULL default '0000-00-00 00:00:00');";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

function spvco_drop() {
    global $wpdb;
    $table = $wpdb->prefix . "simpleviews";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "DROP TABLE " . $table;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

if (!function_exists('echo_views')) {
    function echo_views($post_id) {
        if (update_views($post_id) == 1) {
            $views = get_views($post_id);
            echo number_format_i18n($views);
        } else {
            echo 0;
        }
    }
}

function insert_views($views, $post_id) {
    global $wpdb;
    $table = $wpdb->prefix . "simpleviews";
    $result = $wpdb->query("INSERT INTO $table VALUES($post_id,$views,NOW())");
    return ($result);
}

function update_views($post_id) {
    global $wpdb;
    $table = $wpdb->prefix . "simpleviews";
    $views = get_views($post_id) + 1;
    if ($wpdb->query("SELECT view FROM $table WHERE post_id = '$post_id'") != 1)
        insert_views($views, $post_id);
    $result = $wpdb->query("UPDATE $table SET view = $views WHERE post_id = '$post_id'");
    return ($result);
}

function get_views($post_id) {
    global $wpdb;
    $table = $wpdb->prefix . "simpleviews";
    $result = $wpdb->get_results("SELECT view FROM $table WHERE post_id = '$post_id'", ARRAY_A);
    if (!is_array($result) || empty($result)) {
        return "0";
    } else {
        return $result[0]['view'];
    }
}

?>