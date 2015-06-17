<?php
/**
Plugin Name: WP Slick Slider
Plugin URI: http://www.tigerstrikemedia.com/
Description: Utilising the power of custom post types and custom meta boxes, adds a powerful and customisable slider to your wordpress site.
Version: 1.8
Author: Tiger Strike Media
Author URI: http://www.tigerstrikemedia.com/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 */

class WP_Slick_Slider{
    
    //The Options Array Form The Database.
    var $options;
    
    /**
     * Plugin Class Constructor
     */
    function __construct() {
        $this->plugin_defines();
        $this->plugin_includes();
        $this->setup_globals();
        $this->setup_actions();
        $this->plugin_filters();
    }
    
    /**
     * Defines To Be Used Anywhere
     */
    function plugin_defines(){
        
        /*
         * Define Base Paths to the plugin file
         */
        define( 'WPSS_HTTP_PATH' , WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__) , "" , plugin_basename(__FILE__) ) );
        define( 'WPSS_ABSPATH' , WP_PLUGIN_DIR . '/' . str_replace(basename( __FILE__) , "" , plugin_basename(__FILE__) ) );
        
    }
    
    /**
     * Includes
     */
    function plugin_includes(){
        
        //@FIXME - Refactor All These Files.
        include 'includes/wpss-functions.php';
        include 'includes/wpss-options-class.php';
        include 'includes/output.php';
        
    }
    
    /*
     * Global Variables
     */
    function setup_globals(){
        
        $this->options = get_option( 'wpss_options' );
        
    }
    
    /**
     * Actions To Hook Into Wordpress
     */
    function setup_actions(){
        
        //Activation Function
        register_activation_hook(__FILE__, array( $this, 'install' ) );
        
        //Deactivate Function
        register_deactivation_hook(__FILE__ , array( $this, 'uninstall' ) );
        
        /*
         * Load The I18n Files
         */
        load_plugin_textdomain( 'wp_slick_slider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        
        if( isset( $wpss_options['wpss_footer_credits'] ) ){
            
            add_action( 'wp_footer' , array( $this, 'footer_credits' ) );
            
        }
        
        //Init Hook
        add_action( 'init', array( $this, 'init' ) );
        
        //Options Pages
        add_action('admin_init', array('WPSS_OPTIONS', 'Init') );
        add_action('admin_menu', array('WPSS_OPTIONS', 'Add_Admin_Menus') );
        
        //Save Post
        add_action( 'save_post' , array( $this, 'save_post' ) );
        
    }
    
    /**
     * Script And Style Enqueues
     */
    function plugin_enqueues(){
        
        if( isset( $this->options['wpss_use_js'] ) && ! is_admin() ){
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-effects' , WPSS_HTTP_PATH . 'js/jquery-ui-effects.js', array( 'jquery' , 'jquery-ui-core' ) , false, true );
            wp_enqueue_script( 'jquery-cycle' , WPSS_HTTP_PATH . 'js/jquery-cycle.min.js', array( 'jquery' ) , false, true );
        }
        
    }
    
    /*
     * Install Function
     */
    function install(){
        
        if( get_option( 'wpss_options' ) )
        return;
    
        $default_options = array(
           'wpss_use_css'         => 'on',
           'wpss_use_js'          => 'on'
        );
        update_option( 'wpss_options' ,  $default_options );
        
    }
    
    /**
     * Uninstall Function
     */
    function uninstall(){
        
        delete_option( 'wpss_options' );

        $terms = get_terms( 'wpss_sliders' );
        foreach( $terms as $term ){
            delete_option( 'wpss_' . $term->slug . '_options' );
        }
        
    }
    
    /**
     * Init Function
     */
    function init(){
        
        //Setup Required Theme Supports
        $this->theme_supports();
        
        //Setup The Post Types
        $this->register_post_types();
        
        //Enqueue Scripts
        $this->plugin_enqueues();
        
    }
    
    /**
     * Footer Credits
     */
    function footer_credits(){
        echo '<p id="wpss_credit_link" style="text-align:center" class="small aligncenter">Slick Slider <a target="_blank" href="http://www.tigerstrikemedia.com">Plugin By Tiger Strike Media</a></p>';
    }
    
    /**
     * Theme Supports
     */
    function theme_supports(){
        
        /*
         * Create Image Sizes.
         */
        if( ! current_theme_supports( 'post-thumbnails' ) ){
            add_theme_support( 'post-thumbnails' );
        }
        
    }
    
    /*
     * Register The Post Types
     */
    function register_post_types(){
        $slidersargs = array(
            'labels' => array(
                'name' => __( 'Sliders' ),
                'singular_name' => __( 'Slider' ),
                'search_items' =>  __( 'Search Sliders' ),
                'all_items' => __( 'All Sliders' ),
                'parent_item' => __( 'Parent Slider' ),
                'parent_item_colon' => __( 'Parent Slider:' ),
                'edit_item' => __( 'Edit Slider' ),
                'update_item' => __( 'Update Slider' ),
                'add_new_item' => __( 'Add New Slider' ),
                'new_item_name' => __( 'New Slider Name' ),
                'menu_name' => __( 'Sliders' )
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => false
        );
        register_taxonomy( 'wpss_sliders' , 'wpss_sliders' , $slidersargs);

        $slideargs = array(
                'labels' => array(
                        'name' => 'Slick Slides',
                        'singular_name' => 'Slide',
                        'add_new' => 'New Slide',
                        'add_new_item' => 'Add New Slide',
                        'edit_item' => 'Edit Slide',
                        'new_item' => 'New Slide',
                        'view_item' => 'View Slide',
                        'search_items' => 'Search Slides',
                        'not_found' => 'No Slides Found',
                        'not_found_in_trash' => 'No Slides In The Trash'
                ),
                'description' => 'Slides For The WP Slick Slider',
                'public' => false,
                'show_ui' => true,
                'capability_type' => 'post',
                'hierarchical' => true,
                'taxonomies' => array( 'wpss_sliders' ),
                'supports' => array(
                        'title',
                        'editor',
                        'thumbnail',
                        'custom-fields'
                ),
                'rewrite' => false,
                'menu_position' => 5,
                'register_meta_box_cb' => array( $this, 'add_meta_box' ),
                'menu_icon' => WPSS_HTTP_PATH . 'images/icon.png'
        );
        register_post_type( 'wpss_slides', $slideargs );
    }
    
    /*
     * Add The Meta Box
     */
    function add_meta_box(){
        add_meta_box( 'extra_data', 'Slide Information', array( $this, 'create_meta_box' ), 'wpss_slides', 'normal', 'high' );
    }
    
    /**
     * Generate The Post Meta Box
     */
    function create_meta_box(){
        global $post;
        
        $metadata = get_post_custom( $post->ID );
        $type = ( isset( $metadata['_slide_type'][0] ) ) ? $metadata['_slide_type'][0]  : '' ;
        $slide_order = ( isset( $metadata['_slide_order'][0] ) ) ? $metadata['_slide_order'][0]  : '' ;
        
	//Create a select box of slide types -- //Left, Right, Full Width Image , Full Width Text
	?>
	<p>
		<b>Select The Slide Type</b><br />
		<select name="slide-type">
                    <?php 
                    $opts = array( 'Left Half Image' , 'Right Half Image' , 'Full Width Image' , 'Full Width Text' );
                    $out = '';
                    foreach( $opts as $opt ){
                        $selected = ( $type == $opt ) ? 'selected="selected"' : '' ;
                        $out .= '<option ' . $selected . ' value="' . $opt . '">' . __( $opt ) . '</option>';
                    }
                    echo $out;
                    ?>
		</select>
	</p>
	<p>
	    <b>Slide Number. Use This Option To Reorganize The Order Of The Slides. Use 0 Or Leave Blank To Disable.</b><br />
	    <input size="20" type="text" name="slide-order" value="<?php echo $slide_order; ?>" />
	</p>
        <?php
        
    }
    
    /*
     * Save The Post Meta For The Slides
     */
    function save_post( $post_id ){
        
        //Dont save on autosave:
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
	    return $post_id;
	}
              
	//Ensure We Are Saving The Custom Post Type:
	if(isset($_POST['post_type']) && $_POST['post_type'] == 'wpss_slides'):

            $slide_type = wp_kses( $_POST['slide-type'], array() );
	    $slide_order = wp_kses( $_POST['slide-order'], array() );
	    $slide_order = ( empty( $slide_order ) || $slide_order == 0 ) ? 0 : $slide_order ;

            update_post_meta($post_id, '_slide_type', $slide_type );
	    update_post_meta($post_id, '_slide_order', $slide_order );

	endif;
        
    }
    
    function plugin_filters(){
        
        //wpss_the_content
        //Shortcodes
        add_filter( 'wpss_the_content', 'do_shortcode' );
        add_filter( 'wpss_the_content', 'wptexturize'        );
        add_filter( 'wpss_the_content', 'convert_smilies'    );
        add_filter( 'wpss_the_content', 'convert_chars'      );
        add_filter( 'wpss_the_content', 'wpautop'            );
        add_filter( 'wpss_the_content', 'shortcode_unautop'  );
        add_filter( 'wpss_the_content', 'prepend_attachment' );
        
    }
    
}

$WP_Slick_Slider = new WP_Slick_Slider();

?>