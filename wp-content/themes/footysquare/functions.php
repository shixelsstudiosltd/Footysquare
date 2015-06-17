<?php

    /* add action filter and theme support on theme setup */

	add_action( 'after_setup_theme', 'px_theme_setup' );

	function px_theme_setup() {

		/* Add theme-supported features. */		// This theme styles the visual editor with editor-style.css to match the theme style.

		add_editor_style();

		// Make theme available for translation

		// Translations can be filed in the /languages/ directory

		load_theme_textdomain('Kings Club', get_template_directory() . '/languages');

		

		if (!isset($content_width)){

			$content_width = 1160;

		}



		$args = array('default-color' => '','default-image' => '',);

		add_theme_support('custom-background', $args);

		add_theme_support('custom-header', $args);

		// This theme uses post thumbnails

		add_theme_support('post-thumbnails');

		// Add default posts and comments RSS feed links to head

		add_theme_support('automatic-feed-links');

		// Post Formats

		add_theme_support( 'post-formats', array(

		'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',

	) );

		/* Add custom actions. */

		global $pagenow;

		

		if (is_admin() && isset($_GET['activated']) && $pagenow == 'themes.php'){

			

			

			if(!get_option('px_theme_option')){

				add_action('admin_head', 'px_activate_widget');

				add_action('init', 'px_activation_data');

				wp_redirect( admin_url( 'admin.php?page=px_demo_importer' ) );

			}

		}



		if (!session_id()){

			add_action('init', 'session_start');

		}

		

		add_action('init', 'px_register_my_menus' );

		add_action('admin_enqueue_scripts', 'px_admin_scripts_enqueue');

		add_action('wp_enqueue_scripts', 'px_front_scripts_enqueue');

 		add_action('pre_get_posts', 'px_get_search_results');

		add_action('widgets_init', create_function('', 'return register_widget("px_widget_facebook");') );

		add_action('widgets_init', create_function('', 'return register_widget("px_gallery");'));

		add_action('widgets_init', create_function('', 'return register_widget("recentposts");') );

		add_action('widgets_init', create_function('', 'return register_widget("px_fixture_countdown");') );

		add_action('widgets_init', create_function('', 'return register_widget("px_twitter_widget");'));

		add_action('widgets_init', create_function('', 'return register_widget("px_pointstable");'));

		add_action('widgets_init', create_function('', 'return register_widget("px_MailChimp_Widget");') );

		/* Add custom filters. */

		add_filter('widget_text', 'do_shortcode');

		add_filter('the_password_form', 'px_password_form' );

		add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

		add_filter('wp_page_menu','px_add_menuid');

		add_filter('wp_page_menu', 'px_remove_div' );

		add_filter('nav_menu_css_class', 'px_add_parent_css', 10, 2);

		add_filter('pre_get_posts', 'px_change_query_vars');

		add_filter('user_contactmethods','px_contact_options',10,1);

		$home = get_page_by_title( 'Home' );

		if($home <> '' && get_option( 'page_on_front' ) == "0"){

			update_option( 'page_on_front', $home->ID );

			update_option( 'show_on_front', 'page' );

		}

	}

	

	if ( ! function_exists( 'px_register_required_plugins' ) ) { 

	// tgm class for (internal and WordPress repository) plugin activation start

	require_once dirname( __FILE__ ) . '/include/class-tgm-plugin-activation.php';

	add_action( 'tgmpa_register', 'px_register_required_plugins' );

	function px_register_required_plugins() {

		/**

		 * Array of plugin arrays. Required keys are name and slug.

		 * If the source is NOT from the .org repo, then source is also required.

		 */

		$plugins = array(

			// This is an example of how to include a plugin from the WordPress Plugin Repository

			

			array(

				'name'     				=> 'Revolution Slider',

				'slug'     				=> 'revslider',

				'source'   				=> get_template_directory_uri() . '/include/plugins/revslider.zip', 

				'required' 				=> false, 

				'version' 				=> '',

				'force_activation' 		=> false,

				'force_deactivation' 	=> false,

				'external_url' 			=> '',

			),

			array(

				'name' 		=> 'Contact Form 7',

				'slug' 		=> 'contact-form-7',

				'required' 	=> false,

			),

			array(

				'name' 		=> 'Woocommerce',

				'slug' 		=> 'woocommerce',

				'required' 	=> false,

			),

			

	

		);

		// Change this to your theme text domain, used for internationalising strings

		$theme_text_domain = 'Kings Club';

		/**

		 * Array of configuration settings. Amend each line as needed.

		 * If you want the default strings to be available under your own theme domain,

		 * leave the strings uncommented.

		 * Some of the strings are added into a sprintf, so see the comments at the

		 * end of each line for what each argument will be.

		 */

		$config = array(

			'domain'       		=> 'Kings Club',         	// Text domain - likely want to be the same as your theme.

			'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins

			'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug

			'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug

			'menu'         		=> 'install-required-plugins', 	// Menu slug

			'has_notices'      	=> true,                       	// Show admin notices or not

			'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not

			'message' 			=> '',							// Message to output right before the plugins table

			'strings'      		=> array(

				'page_title'                       			=> __( 'Install Required Plugins', 'Kings Club' ),

				'menu_title'                       			=> __( 'Install Plugins', 'Kings Club' ),

				'installing'                       			=> __( 'Installing Plugin: %s', 'Kings Club' ), // %1$s = plugin name

				'oops'                             			=> __( 'Something went wrong with the plugin API.', 'Kings Club' ),

				'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)

				'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)

				'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)

				'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)

				'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)

				'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)

				'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)

				'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)

				'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),

				'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),

				'return'                           			=> __( 'Return to Required Plugins Installer', 'Kings Club' ),

				'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'Kings Club' ),

				'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'Kings Club' ), // %1$s = dashboard link

				'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'

			)

		);

		tgmpa( $plugins, $config );

	}

	// tgm class for (internal and WordPress repository) plugin activation end

	}



	/* adding custom images while uploading media start */

	

	// Banner, Blog Large

	add_image_size('px_media_1', 768, 403, true);

	// Spot Light, Gallery

	add_image_size('px_media_2', 470, 353, true);

	// Popular Players

	add_image_size('px_media_3', 390, 390, true);

	// Blog Medium, News

	add_image_size('px_media_4', 325, 244, true);

	// Admin scripts enqueue

	function px_admin_scripts_enqueue() {

		$template_path = get_template_directory_uri() . '/scripts/admin/media_upload.js';

		wp_enqueue_script('my-upload', $template_path, 

		array('jquery', 'media-upload', 'thickbox', 'jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wp-color-picker'));

		wp_enqueue_script('custom_wp_admin_script', get_template_directory_uri() . '/scripts/admin/px_functions.js');

		wp_enqueue_style('custom_wp_admin_style', get_template_directory_uri() . '/css/admin/admin-style.css', array('thickbox'));

		wp_enqueue_style('custom_wp_admin_fontawesome_style', get_template_directory_uri() . '/css/admin/font-awesome.css', array('thickbox'));

		wp_enqueue_style('wp-color-picker');



	}



	// Backend functionality files

	require_once (TEMPLATEPATH . '/include/theme_activation.php');

	require_once (TEMPLATEPATH . '/include/admin_functions.php');

	require_once (TEMPLATEPATH . '/include/theme_colors.php');

 	require_once (TEMPLATEPATH . '/include/player.php');

	require_once (TEMPLATEPATH . '/include/pointtable.php');

	require_once (TEMPLATEPATH . '/include/event.php');

	require_once (TEMPLATEPATH . '/include/gallery.php');

	require_once (TEMPLATEPATH . '/include/page_builder.php');

	require_once (TEMPLATEPATH . '/include/post_meta.php');

	require_once (TEMPLATEPATH . '/include/widgets.php');

	require_once (TEMPLATEPATH . '/include/ical/iCalcreator.class.php');

	require_once (TEMPLATEPATH . '/include/mailchimpapi/mailchimpapi.class.php');

	require_once (TEMPLATEPATH . '/include/mailchimpapi/chimp_mc_plugin.class.php');



	

	/* Require Woocommerce */

	require_once (TEMPLATEPATH . '/include/config_woocommerce/config.php');

	require_once (TEMPLATEPATH . '/include/config_woocommerce/product_meta.php');

	/* Addmin Menu PX Theme Option */

	

	if (current_user_can('administrator')) {

		require_once (TEMPLATEPATH . '/include/theme_option.php');

		add_action('admin_menu', 'px_theme');

		function px_theme() {

			add_theme_page('PX Theme Option', 'PX Theme Option', 'read', 'px_theme_options', 'theme_option');

			add_theme_page( "PX Import Demo Data" , "Import Demo Data" ,'read', 'px_demo_importer' , 'px_demo_importer');

		}



	}

	$image_url = apply_filters( 'taxonomy-images-queried-term-image-url', '', array(

    'image_size' => 'medium'

    ) );



	// Template redirect in single Gallery and Slider page

	function px_slider_gallery_template_redirect(){

		

		if ( get_post_type() == "px_gallery" ) {

			global $wp_query;

			$wp_query->set_404();

			status_header( 404 );

			get_template_part( 404 );

			exit();

		}

	}



	// enque style and scripts

	function px_front_scripts_enqueue() {

		global $px_theme_option;

		

		if (!is_admin()) {

			//wp_enqueue_style('style_css', get_template_directory_uri() . '/style.css');

			wp_enqueue_style('style_css', get_stylesheet_uri());

			if ( isset($px_theme_option['color_switcher']) && $px_theme_option['color_switcher'] == "on" ) {

				wp_enqueue_style('color-switcher_css', get_template_directory_uri() . '/css/color-switcher.css');

			}

			wp_enqueue_style('prettyPhoto_css', get_template_directory_uri() . '/css/prettyphoto.css');

			wp_enqueue_style('bootstrap_css', get_template_directory_uri() . '/css/bootstrap.css');

			wp_enqueue_style('font-awesome_css', get_template_directory_uri() . '/css/font-awesome.css');



			// Enqueue stylesheet

			wp_enqueue_style( 'wp-mediaelement' );

			wp_enqueue_script('jquery');

			wp_enqueue_script( 'wp-mediaelement' );

			wp_enqueue_script('bootstrap_js', get_template_directory_uri() . '/scripts/frontend/bootstrap.min.js', '', '', true);

			wp_enqueue_script('modernizr_js', get_template_directory_uri() . '/scripts/frontend/modernizr.js', '', '', true);

			wp_enqueue_script('prettyPhoto_js', get_template_directory_uri() . '/scripts/frontend/jquery.prettyphoto.js', '', '', true);

			wp_enqueue_script('functions_js', get_template_directory_uri() . '/scripts/frontend/functions.js', '0', '', false);

			

			

			if ( isset($px_theme_option['rtl_switcher']) && $px_theme_option['rtl_switcher'] == "on"){

				wp_enqueue_style('rtl_css', get_template_directory_uri() . '/css/rtl.css');

			}



			if ( isset($px_theme_option['responsive']) && $px_theme_option['responsive'] == "on") {

				echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">';

				wp_enqueue_style('responsive_css', get_template_directory_uri() . '/css/responsive.css');

			}

		}

	}

	function px_enqueue_flexslider_script(){

		wp_enqueue_style('flexslider_css', get_template_directory_uri() . '/css/flexslider.css');

		wp_enqueue_script('flexslider_js', get_template_directory_uri() . '/scripts/frontend/jquery.flexslider-min.js', '', '', true);

	}

	// cycle Script Enqueue

	function px_enqueue_cycle_script(){

		wp_enqueue_script('jquery.cycle2_js', get_template_directory_uri() . '/scripts/frontend/cycle2.js', '', '', true);

	}

	

	// rating script

	function px_enqueue_rating_style_script(){

		wp_enqueue_style('jRating_css', get_template_directory_uri() . '/css/jRating.jquery.css');

		wp_enqueue_script('jquery_rating_js', get_template_directory_uri() . '/scripts/frontend/jRating.jquery.js', '', '', true);

	}

	// Validation Script Enqueue

	function px_enqueue_validation_script(){

		wp_enqueue_script('jquery.validate.metadata_js', get_template_directory_uri() . '/scripts/admin/jquery.validate.metadata.js', '', '', true);

		wp_enqueue_script('jquery.validate_js', get_template_directory_uri() . '/scripts/admin/jquery.validate.js', '', '', true);

	}

	/* countdown enqueue */	

	function px_enqueue_countdown_script(){

		wp_enqueue_script('jquery.countdown_js', get_template_directory_uri() . '/scripts/frontend/jquery.countdown.js', '', '', true);

	}

		

	// add this share enqueue

	function px_addthis_script_init_method(){

		

		if( is_single()){

			wp_enqueue_script( 'px_addthis', 'http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e4412d954dccc64', '', '', true);

		}



	}

	// content class

	  

	  if ( ! function_exists( 'px_meta_content_class' ) ) {

		  function px_meta_content_class(){

			  global $px_meta_page;

			  

			  if ( $px_meta_page->sidebar_layout->px_layout == '' or $px_meta_page->sidebar_layout->px_layout == 'none' ) {

				  $content_class = "col-md-12";

				  

			  } else

			  if ( $px_meta_page->sidebar_layout->px_layout <> '' and $px_meta_page->sidebar_layout->px_layout == 'right' ) {

				  $content_class = "col-md-9";

				  

			  } else

			  if ( $px_meta_page->sidebar_layout->px_layout <> '' and $px_meta_page->sidebar_layout->px_layout == 'left' ) {

				  $content_class = "col-md-9";

				  

			  } else

			  if ( $px_meta_page->sidebar_layout->px_layout <> '' and ($px_meta_page->sidebar_layout->px_layout == 'both' or $px_meta_page->sidebar_layout->px_layout == 'both_left' or $px_meta_page->sidebar_layout->px_layout == 'both_right')) {

				  $content_class = "col-md-6";

				 

			  } else {

				  $content_class = "col-md-12";

			  }



			  return $content_class;

		  }



	  }

	  

	  // Content pages Meta Class



if ( ! function_exists( 'px_default_pages_meta_content_class' ) ) { 



	function px_default_pages_meta_content_class($layout){



			if ( $layout == '' or $layout == 'none' ) {

	

				echo "col-md-12";

	

			}

	

			else if ( $layout <> '' and $layout == 'right' ) {

	

				echo "content-left col-md-9";

	

			}

	

			else if ( $layout <> '' and $layout == 'left' ) {

	

				echo "content-right col-md-9";

	

			}

	

			else if ( $layout <> '' and $layout == 'both' ) {

	

				echo "content-right col-md-6";

	

			}

	

		}	

	

	}

	  

	  

	  

	  

	  

	// Favicon and header code in head tag//

	function px_footer_settings() {

		global $px_theme_option;

		if(isset($px_theme_option['analytics']))

			echo htmlspecialchars_decode($px_theme_option['analytics']);

	}



	/* Page Sub header title and subtitle */	

	function get_subheader_title(){

		global $post, $wp_query;

		$show_title=true;

  		$get_title = '';

		if (is_page() || is_single()) {

			

			if (is_page() ){

				$px_xmlObject = px_meta_page('px_page_builder');

				if (isset($px_xmlObject)) {

					if($px_xmlObject->page_title == "on"){

						echo '<h1 class="pix-page-title">' . get_the_title(). '</h1>';

					}

				}else{

					echo '<h1 class="pix-page-title">' . get_the_title(). '</h1>';

				}

			}elseif (is_single()) {

				

				$post_type = get_post_type($post->ID);

				if ($post_type == "events") {

					$post_type = "px_event_meta";

				} else if($post_type == "player"){

					$post_type = "player";

				} else {

					$post_type = "post";

				}

				$post_xml = get_post_meta($post->ID, $post_type, true);

				

				if ($post_xml <> "") {

					$px_xmlObject = new SimpleXMLElement($post_xml);

				}

				if (isset($px_xmlObject)) {

 					echo '<h1 class="pix-page-title px-single-page-title">' . get_the_title(). '</h1>';

				}else{

					echo '<h1 class="pix-page-title px-single-page-title">' . get_the_title(). '</h1>';

 				}

			}

			

		} else {

		?>

 			<h1 class="pix-page-title"><?php px_post_page_title(); ?></h1>

 		 <?php 

		}



	}







	// search varibales start

	function px_get_search_results($query) {

		

		if ( !is_admin() and (is_search())) {

			$query->set( 'post_type', array('post', 'events', 'player') );

			remove_action( 'pre_get_posts', 'px_get_search_results' );

		}



	}



	// Filter shortcode in text areas

	

	if ( ! function_exists( 'px_textarea_filter' ) ) {

		

		function px_textarea_filter($content=''){

			return do_shortcode($content);

		}



	}



	// woocommerce ajax add to Cart 

	function woocommerce_header_add_to_cart_fragment( $fragments ) {

		

		if ( class_exists( 'woocommerce' ) ){

			global $woocommerce;

			ob_start();

			?>

            <div class="cart-sec">

                <a href="<?php  echo $woocommerce->cart->get_cart_url(); ?>">

                    <i class="fa fa-shopping-cart"></i><span><?php  echo $woocommerce->cart->cart_contents_count; ?></span>

                </a>

            </div>

			<?php

			$fragments['div.cart-sec'] = ob_get_clean();

			return $fragments;

		}



	}

	// woocommerce default cart

	function px_woocommerce_header_cart() {

		

		if ( class_exists( 'woocommerce' ) ){

			global $woocommerce;

			?>

		<div class="cart-sec">

			<a href="<?php  echo $woocommerce->cart->get_cart_url(); ?>">

            	<i class="fa fa-shopping-cart"></i><span><?php  echo $woocommerce->cart->cart_contents_count; ?></span>

            </a>

		</div>

		<?php

		}



	}



	// Display navigation to next/previous for single posts

	

	if ( ! function_exists( 'px_next_prev_post' ) ) {

		

		function px_next_prev_post(){

 			global $post;

			posts_nav_link();

			// Don't print empty markup if there's nowhere to navigate.

			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) :

			get_adjacent_post( false, '', true );

			$next     = get_adjacent_post( false, '', false );

			echo '<div class="prev-nex-btn">';

				previous_post_link( '%link', '<i class="fa fa-angle-double-left"></i>' );

				next_post_link( '%link','<i class="fa fa-angle-double-right"></i>' );

			echo '</div>';

      		}



	}

	function px_posts_link_next_class($format){

		 $format = str_replace('href=', 'class="post-next" href=', $format);

		 return $format;

	}

	add_filter('next_post_link', 'px_posts_link_next_class');

	

	function px_posts_link_prev_class($format) {

		 $format = str_replace('href=', 'class="post-prev" href=', $format);

		 return $format;

	}

	add_filter('previous_post_link', 'px_posts_link_prev_class');

 	//	Add Featured/sticky text/icon for sticky posts.

 	if ( ! function_exists( 'px_featured()' ) ) {

		function px_featured(){

			global $px_transwitch,$px_theme_option;

		

			if ( is_sticky() ){

				?>

                <li class="featured">

                    <?php 

                        if(!isset($px_theme_option) || (!isset($px_theme_option['lotrans_featuredgo']))){

                                _e('Featured','Kings Club');

                        } else {

                            if(isset($px_theme_option['trans_switcher']) && $px_theme_option['trans_switcher'] == "on"){

                                _e('Featured','Kings Club');

                            } else {

                                if(isset($px_theme_option['trans_featured']))

                                    echo $px_theme_option['trans_featured'];

                            }

                        }

                    ?>		         

                 </li>

		<?php

			}



		}



	}



	/* display post page title */	

	function px_post_page_title(){

		

		if ( is_author() ) {

			global $author;

			$userdata = get_userdata($author);

			echo __('Author', 'Kings Club') . " " . __('Archives', 'Kings Club') . ": ".$userdata->display_name;

		}

 		elseif ( is_tag() || is_tax('event-tag') || is_tax('portfolio-tag') || is_tax('sermon-tag') ) {

			echo __('Tags', 'Kings Club') . " " . __('Archives', 'Kings Club') . ": " . single_cat_title( '', false );

		}

 		elseif ( is_category() || is_tax('event-category') || is_tax('portfolio-category')  || is_tax('season-category')  || 

		is_tax('sermon-series')  || is_tax('sermon-pastors') ) {

			echo __('Categories', 'Kings Club') . " " . __('Archives', 'Kings Club') . ": " . single_cat_title( '', false );

		}

 		elseif( is_search()){

			printf( __( 'Search Results %1$s %2$s', 'Kings Club' ), ': ','<span>' . get_search_query() . '</span>' );

		}

 		elseif ( is_day() ) {

			printf( __( 'Daily Archives: %s', 'Kings Club' ), '<span>' . get_the_date() . '</span>' );

		}

 		elseif ( is_month() ) {

			printf( __( 'Monthly Archives: %s', 'Kings Club' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'Kings Club' ) ) . '</span>' );

		}

 		elseif ( is_year() ) {

			printf( __( 'Yearly Archives: %s', 'Kings Club' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'Kings Club' ) ) . '</span>' );

		}

 		elseif ( is_404()){

			_e( 'Error 404', 'Kings Club' );

		}

 		



	}



	// Custom excerpt function 

	function px_get_the_excerpt($limit,$readmore = '', $dottedline = '') {

		global $px_theme_option;

		$readmore = '';

		if(isset($px_theme_option['trans_switcher']) && $px_theme_option['trans_switcher'] == "on"){

			$readmore = __('Read More','Kings Club');

		} else {

			if(isset($px_theme_option['trans_read_more']))

				$readmore = $px_theme_option['trans_read_more'];

		}

		if(!isset($limit) || $limit == ''){ $limit = '255';}

		$get_the_excerpt = trim(preg_replace('/<a[^>]*>(.*)<\/a>/iU', '', get_the_excerpt()));

		

		if(isset($dottedline) && $dottedline <> ''){

			echo '<p>'.substr($get_the_excerpt, 0, "$limit");

			echo $dottedline;	

			echo '</p>';

		} else {

			echo '<p>'.substr($get_the_excerpt, 0, "$limit").'</p>';

			if (strlen($get_the_excerpt) > "$limit") {

				

				if($readmore == "true"){

					echo '... <a href="' . get_permalink() . '" class="colr">' . $readmore . '</a>';

				}

				

	

			}

		}



	}



	// change the default query variable start

	function px_change_query_vars($query) {

		

		if (is_search() || is_home()) {

			

			if (empty($_GET['page_id_all']))$_GET['page_id_all'] = 1;

			$query->query_vars['paged'] = $_GET['page_id_all'];

		}

 		return $query;

		// Return modified query variables

	}



	/* custom pagination start */

	

	if ( ! function_exists( 'px_pagination' ) ) {

		function px_pagination($total_records, $per_page, $qrystr = '') {

			$html = '';

			$dot_pre = '';

			$dot_more = '';

			$previous = __("Previous",'Kings Club');

			if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") { $previous = __("Previous",'Kings Club'); }elseif(isset($px_theme_option["trans_previous"]) && $px_theme_option["trans_previous"] <> ''){  $previous = $px_theme_option["trans_previous"];}

			$total_page = ceil($total_records / $per_page);

			$loop_start = $_GET['page_id_all'] - 2;

			$loop_end = $_GET['page_id_all'] + 2;

			

			if ($_GET['page_id_all'] < 3) {

				$loop_start = 1;

				

				if ($total_page < 5)$loop_end = $total_page; else $loop_end = 5;

			} else

			if ($_GET['page_id_all'] >= $total_page - 1) {

				

				if ($total_page < 5)$loop_start = 1; else $loop_start = $total_page - 4;

				$loop_end = $total_page;

			}



			

			if ($_GET['page_id_all'] > 1)$html .= "<li  class='prev'>

			<a href='?page_id_all=" . ($_GET['page_id_all'] - 1) . "$qrystr' ><i class='fa fa-long-arrow-left'></i>".__('Previous','Kings Club')."</a></li>";

			

			if ($_GET['page_id_all'] > 3 and $total_page > 5)$html .= "<li><a href='?page_id_all=1$qrystr'>1</a></li>";

			

			if ($_GET['page_id_all'] > 4 and $total_page > 6)$html .= "<li> <a>. . .</a> </li>";

			

			if ($total_page > 1) {

				for ($i = $loop_start; $i <= $loop_end; $i++) {

					

					if ($i <> $_GET['page_id_all'])$html .= "<li><a href='?page_id_all=$i$qrystr'>" . $i . "</a></li>"; else $html .= "<li>

					<span class='active'>" . $i . "</span></li>";

				}



			}

 			

			if ($loop_end <> $total_page and $loop_end <> $total_page - 1)$html .= "<li> <a>. . .</a> </li>";

			

			if ($loop_end <> $total_page)$html .= "<li><a href='?page_id_all=$total_page$qrystr'>$total_page</a></li>";

			

			if ($_GET['page_id_all'] < $total_records / $per_page)$html .= "<li class='next'><a href='?page_id_all=" . ($_GET['page_id_all'] + 1) . "$qrystr' >".__('Next','Kings Club')."<i class='fa fa-long-arrow-right'></i></a></li>";

			return $html;

		}



	}

	// pagination end

	// Social Share Function

	

	if ( ! function_exists( 'px_social_share' ) ) {

		function px_social_share($icon_type = '', $title='true') {

			global $px_theme_option;

			px_addthis_script_init_method();

			if (isset($px_theme_option['social_share']) && $px_theme_option['social_share'] == "on"){

				if(isset($px_theme_option['trans_switcher']) && $px_theme_option["trans_switcher"] == "on") { $html1= __("Share this post",'Kings Club'); }else{  $html1 =  $px_theme_option["trans_share_this_post"];}

				$html = '';

					$html .='<ul class="social-network">';

					$html .='<a class="addthis_button_compact btn share-now pix-bgcolr"><i class="fa fa-share-square-o"></i>'.$html1.'</a>';

					$html .='</ul>';

					

					echo $html;

				

				 

			}

		}



	}



	// Social network

	

	if ( ! function_exists( 'px_social_network' ) ) {

		function px_social_network($icon_type='',$tooltip = ''){

			global $px_theme_option;

			$tooltip_data='';

			if($icon_type=='large'){

				$icon = '2x';

			} else {

				$icon = 'icon';

			}

			echo '<div class="followus">';

			if(isset($tooltip) && $tooltip <> ''){

				$tooltip_data='data-placement-tooltip="tooltip"';

			}

  			if ( isset($px_theme_option['social_net_url']) and count($px_theme_option['social_net_url']) > 0 ) {

				$i = 0;

				foreach ( $px_theme_option['social_net_url'] as $val ){

					if($val != ''){ ?>

                    	<a title="" href="<?php  echo $val; ?>" data-original-title="<?php  echo $px_theme_option['social_net_tooltip'][$i]; ?>" data-placement="top" <?php  echo $tooltip_data; ?> class="colrhover"  target="_blank">

						<?php  if($px_theme_option['social_net_awesome'][$i] <> '' && isset($px_theme_option['social_net_awesome'][$i])){ ?> 

                    <i class="fa <?php  echo $px_theme_option['social_net_awesome'][$i]; ?> <?php  echo $icon; ?>"></i><?php  } else { ?>

                    <img src="<?php  echo $px_theme_option['social_net_icon_path'][$i]; ?>" alt="<?php  echo $px_theme_option['social_net_tooltip'][$i]; ?>" /><?php  } ?></a>

					<?php 

					}

					$i++;

				}

			}

 			echo '</div>';

		}

	}



	// Post image attachment function

	function px_attachment_image_src($attachment_id, $width, $height) {

		$image_url = wp_get_attachment_image_src($attachment_id, array($width, $height), true);

		

		if ($image_url[1] == $width and $image_url[2] == $height); else        

		$image_url = wp_get_attachment_image_src($attachment_id, "full", true);

		$parts = explode('/uploads/',$image_url[0]);

		

		if ( count($parts) > 1 ) return $image_url[0];

	}



	// Post image attachment source function

	function px_get_post_img_src($post_id, $width, $height) {

		

		if(has_post_thumbnail()){

			$image_id = get_post_thumbnail_id($post_id);

			$image_url = wp_get_attachment_image_src($image_id, array($width, $height), true);

			

			if ($image_url[1] == $width and $image_url[2] == $height) {

				return $image_url[0];

			} else {

				$image_url = wp_get_attachment_image_src($image_id, "full", true);

				return $image_url[0];

			}



		}



	}



	// Get Post image attachment

	function px_get_post_img($post_id, $width, $height) {

		$image_id = get_post_thumbnail_id($post_id);

		$image_url = wp_get_attachment_image_src($image_id, array($width, $height), true);

		if ($image_url[1] == $width and $image_url[2] == $height) {

			return get_the_post_thumbnail($post_id, array($width, $height));

		} else {

			return get_the_post_thumbnail($post_id, "full");

		}

	}

	// custom sidebar start

	$px_theme_option = get_option('px_theme_option');

	

	if ( isset($px_theme_option['sidebar']) and !empty($px_theme_option['sidebar'])) {

		foreach ( $px_theme_option['sidebar'] as $sidebar ){

			register_sidebar(

				array(

					'name' => $sidebar,

					'id' => $sidebar,

					'description' => 'This widget will be displayed on right side of the page.',

					'before_widget' => '<div class="widget %2$s">',

					'after_widget' => '</div>',

					'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title heading-color">',

					'after_title' => '</h2></header>'

				)

			);

		}



	}

	register_sidebar( 

		array(

			'name' => 'Sidebar Widget',

			'id' => 'sidebar-1',

			'description' => 'This Widget Show the Content in Blog Listing page.',

			'before_widget' => '<div class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',

			'after_title' => '</h2></header>'

		) 

	);

	// Home top widget area

	register_sidebar( 

		array(

			'name' => 'Home Top Widget',

			'id' => 'home-top-widget',

			'description' => 'This Widget Show the Content in Hom Page',

			'before_widget' => '<div class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',

			'after_title' => '</h2></header>'

		) 

	);

	//footer widget



	register_sidebar( array(

	

		'name' => 'Footer Widget',

	

		'id' => 'footer-widget',

	

		'description' => 'This Widget Show the Content in Footer Area.',

	

		'before_widget' => '<div class="widget %2$s">',

	

		'after_widget' => '</div>',

	

		'before_title' => '<header class="px-heading-title"><h2 class="px-section-title">',

	

		'after_title' => '</h2></header>'

	

	) );

	

	register_sidebar( array(

	

		'name' => 'Header Advertisement Widget',

	

		'id' => 'header-advertisement-widget',

	

		'description' => 'This Widget Show the Content in Header Area.',

	

		'before_widget' => '<div class="widget %2$s">',

	

		'after_widget' => '</div>',

	

		'before_title' => '<header class="px-heading-title"><h2 class="px-section-title">',

	

		'after_title' => '</h2></header>'

	

	) );

	register_sidebar( array(

	

		'name' => 'Footer Advertisement Widget',

	

		'id' => 'footer-advertisement-widget',

	

		'description' => 'This Widget Show the Content in Footer Area.',

	

		'before_widget' => '<div class="widget %2$s">',

	

		'after_widget' => '</div>',

	

		'before_title' => '<header class="px-heading-title"><h2 class="px-section-title">',

	

		'after_title' => '</h2></header>'

	

	) );

register_sidebar( 

		array(

			'name' => 'Forum page',

			'id' => 'forum_page',

			'description' => 'This Widget displays custom post type in forums page.',

			'before_widget' => '<div class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',

			'after_title' => '</h2></header>'

		) 

	);
register_sidebar( 

		array(

			'name' => 'Forum page 2',

			'id' => 'forum_page2',

			'description' => 'This Widget displays custom post type in forums page.',

			'before_widget' => '<div class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',

			'after_title' => '</h2></header>'

		) 

	);
register_sidebar( 

		array(

			'name' => 'Forum page 3',

			'id' => 'forum_page3',

			'description' => 'This Widget displays custom post type in forums page.',

			'before_widget' => '<div class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',

			'after_title' => '</h2></header>'

		) 

	);
register_sidebar( 

		array(

			'name' => 'Forum page 4',

			'id' => 'forum_page4',

			'description' => 'This Widget displays custom post type in forums page.',

			'before_widget' => '<div class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',

			'after_title' => '</h2></header>'

		) 

	);
	register_sidebar( 

		array(

			'name' => 'Forum page 5',

			'id' => 'forum_page5',

			'description' => 'This Widget displays custom post type in forums page.',

			'before_widget' => '<div class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',

			'after_title' => '</h2></header>'

		) 

	);
	register_sidebar( 

		array(

			'name' => 'Forum page 6',

			'id' => 'forum_page6',

			'description' => 'This Widget displays custom post type in forums page.',

			'before_widget' => '<div class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',

			'after_title' => '</h2></header>'

		) 

	);
	register_sidebar( 

		array(

			'name' => 'Forum page 7',

			'id' => 'forum_page7',

			'description' => 'This Widget displays custom post type in forums page.',

			'before_widget' => '<div class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',

			'after_title' => '</h2></header>'

		) 

	);
	register_sidebar( 

		array(

			'name' => 'Forum page 8',

			'id' => 'forum_page8',

			'description' => 'This Widget displays custom post type in forums page.',

			'before_widget' => '<div class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',

			'after_title' => '</h2></header>'

		) 

	);


	

	

	function px_add_menuid($ulid) {

		return preg_replace('/<ul>/', '<ul id="menus">', $ulid, 1);

	}

	function px_remove_div ( $menu ){

		return preg_replace( array( '#^<div[^>]*>#', '#</div>$#' ), '', $menu );

	}



	

	function px_register_my_menus() {

		register_nav_menus(array('main-menu'  => __('Main Menu','Kings Club') )  );

	}



	

	function px_add_parent_css($classes, $item) {

		global $px_menu_children;

		

		if ($px_menu_children)        $classes[] = 'parent';

		return $classes;

	}

	

	// map shortcode with various options

		if ( ! function_exists( 'px_map_page' ) ) {

			function px_map_page(){

				global $px_node, $px_counter_node;

  				if ( !isset($px_node->map_lat) or $px_node->map_lat == "" ) { $px_node->map_lat = 0; }

				if ( !isset($px_node->map_lon) or $px_node->map_lon == "" ) { $px_node->map_lon = 0; }

				if ( !isset($px_node->map_zoom) or $px_node->map_zoom == "" ) { $px_node->map_zoom = 11; }

				if ( !isset($px_node->map_info_width) or $px_node->map_info_width == "" ) { $px_node->map_info_width = 200; }

				if ( !isset($px_node->map_info_height) or $px_node->map_info_height == "" ) { $px_node->map_info_height = 100; }

				if ( !isset($px_node->map_show_marker) or $px_node->map_show_marker == "" ) { $px_node->map_show_marker = 'true'; }

				if ( !isset($px_node->map_controls) or $px_node->map_controls == "" ) { $px_node->map_controls = 'false'; }

				if ( !isset($px_node->map_scrollwheel) or $px_node->map_scrollwheel == "" ) { $px_node->map_scrollwheel = 'true'; }

				if ( !isset($px_node->map_draggable) or $px_node->map_draggable == "" )  { $px_node->map_draggable = 'true'; }

				if ( !isset($px_node->map_type) or $px_node->map_type == "" ) { $px_node->map_type = 'ROADMAP'; }

				if ( !isset($px_node->map_info)) { $px_node->map_info = ''; }

				if( !isset($px_node->map_marker_icon)){ $px_node->map_marker_icon = ''; }

				if( !isset($px_node->map_title)){ $px_node->map_title ='';}

				if( !isset($px_node->map_element_size) or $px_node->map_element_size == ""){ $px_node->map_element_size ='default';}

				if( !isset($px_node->map_height) || empty($px_node->map_height)){ $px_node->map_height ='360';}

 				$map_show_marker = '';

				if ( $px_node->map_show_marker == "true" ) { 

					$map_show_marker = " var marker = new google.maps.Marker({

								position: myLatlng,

								map: map,

								title: '',

								icon: '".$px_node->map_marker_icon."',

								shadow:''

							});

					";

				}

				$html = '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=true"></script>';

				

				$html .= '<div class="element_size_'.$px_node->map_element_size.' px-map">';

					$html .= '<div class="contact-us rich_editor_text"><div class="map-sec">';

					

					$html .= '<div class="mapcode iframe mapsection gmapwrapp" id="map_canvas'.$px_counter_node.'" style="height:'.$px_node->map_height.'px;"> </div>';

				$html .= '</div>';

				

				if($px_node->map_title <> ''){$html .= '<h2 class="pix-post-title">'.$px_node->map_title.'</h2>'; }



                   $html .= '<p>'.$px_node->map_text.'</p>';

				   $html .= '</div>';

				$html .= '</div>';   

				//mapTypeId: google.maps.MapTypeId.".$px_node->map_type." ,

				if($px_node->map_type == "STYLED"){

					$px_node->map_type = 'ROADMAP';

					$html .= "<script type='text/javascript'>

							function initialize() {

								var styles = [

									{

									  stylers: [

										{ hue: '#000000' },

										{ saturation: -100 }

									  ]

									},{

									  featureType: 'road',

									  elementType: 'geometry',

									  stylers: [

										{ lightness: -40 },

										{ visibility: 'simplified' }

									  ]

									},{

									  featureType: 'road',

									  elementType: 'labels',

									  stylers: [

										{ visibility: 'on' }

									  ]

									}

								  ];

								var styledMap = new google.maps.StyledMapType(styles,

								{name: 'Styled Map'});

								var myLatlng = new google.maps.LatLng(".$px_node->map_lat.", ".$px_node->map_lon.");

								var mapOptions = {

									zoom: ".$px_node->map_zoom.",

									panControl: false,

									scrollwheel: ".$px_node->map_scrollwheel.",

									draggable: ".$px_node->map_draggable.",

									center: myLatlng,

									disableDefaultUI: true,

									disableDefaultUI: ".$px_node->map_controls.",

									mapTypeControlOptions: {

									  mapTypeIds: [google.maps.MapTypeId.ROADMAP.".$px_node->map_type.", 'map_style']

									}

								}

								var map = new google.maps.Map(document.getElementById('map_canvas".$px_counter_node."'), mapOptions);

								map.mapTypes.set('map_style', styledMap);

								map.setMapTypeId('map_style');

								var infowindow = new google.maps.InfoWindow({

									content: '".$px_node->map_info."',

									maxWidth: ".$px_node->map_info_width.",

									maxHeight:".$px_node->map_info_height.",

								});

								".$map_show_marker."

								//google.maps.event.addListener(marker, 'click', function() {

			

									if (infowindow.content != ''){

									  infowindow.open(map, marker);

									   map.panBy(1,-60);

									   google.maps.event.addListener(marker, 'click', function(event) {

										infowindow.open(map, marker);

			

									   });

									}

								//});

							}

						

						google.maps.event.addDomListener(window, 'load', initialize);

						</script>";

				}else{

					$html .= "<script type='text/javascript'>

						function initialize() {

							var myLatlng = new google.maps.LatLng(".$px_node->map_lat.", ".$px_node->map_lon.");

							var mapOptions = {

								zoom: ".$px_node->map_zoom.",

								scrollwheel: ".$px_node->map_scrollwheel.",

								draggable: ".$px_node->map_draggable.",

								center: myLatlng,

								mapTypeId: google.maps.MapTypeId.".$px_node->map_type." ,

								disableDefaultUI: ".$px_node->map_controls.",

							}

							var map = new google.maps.Map(document.getElementById('map_canvas".$px_counter_node."'), mapOptions);

							var infowindow = new google.maps.InfoWindow({

								content: '".$px_node->map_info."',

								maxWidth: ".$px_node->map_info_width.",

								maxHeight:".$px_node->map_info_height.",

							});

							".$map_show_marker."

							//google.maps.event.addListener(marker, 'click', function() {

		

								if (infowindow.content != ''){

								  infowindow.open(map, marker);

								   map.panBy(1,-60);

								   google.maps.event.addListener(marker, 'click', function(event) {

									infowindow.open(map, marker);

		

								   });

								}

							//});

						}

 						google.maps.event.addDomListener(window, 'load', initialize);

						</script>";

				}

				return $html;

			}

		}

	

	if (!function_exists('pixFill_comment')) :

	/**

     * Template for comments and pingbacks.

     *

     * To override this walker in a child theme without modifying the comments template

     * simply create your own pixFill_comment(), and that function will be used instead.

     *

     * Used as a callback by wp_list_comments() for displaying the comments.

     *

     */

	function pixFill_comment( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;

		$args['reply_text'] = '<i class="fa fa-share"></i> Reply';

		switch ( $comment->comment_type ) :

		case '' :

			?>

        <li  <?php  comment_class(); ?> id="li-comment-<?php  comment_ID(); ?>">

            <div class="thumblist" id="comment-<?php  comment_ID(); ?>">

                <ul>

                    <li>

                        <figure>

                            <a href="#"><?php  echo get_avatar( $comment, 65 ); ?></a>

                        </figure>

                         <div class="text">

                          <header>

                                <?php  printf( __( '%s', 'Kings Club' ), sprintf( '<h5><a class="colrhover">%s</a></h5><br>', get_comment_author_link() ) ); 						/* translators: 1: date, 2: time */								printf( __( '<span>%1$s</span><br/>', 'Kings Club' ), get_comment_date());

	 							?>

                          </header>

                          <div class="bottom-comment">

							  <?php  comment_text(); ?>

                              <?php  edit_comment_link( __( '(Edit)', 'GreenPeace' ), ' ' ); ?>

                                    <?php  comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );                                	if ( $comment->comment_approved == '0' ) : ?>

                                    <div class="comment-awaiting-moderation colr">

                                        <?php  _e( 'Your comment is awaiting moderation.', 'GreenPeace' ); ?>

                                    </div>

                            <?php  endif; ?>

                           </div>

                        </div>

                    </li>

                </ul>

            </div>

         </li>

	<?php

    	break;

			case 'pingback'  :

			case 'trackback' :

	?>

	<li class="post pingback">

		<p><?php  comment_author_link(); ?><?php  edit_comment_link( __( '(Edit)', 'Kings Club' ), ' ' ); ?></p>

		<?php

		break;

		endswitch;

		}

		endif;

			// password protect post/page

			

			if ( ! function_exists( 'px_password_form' ) ) {

				function px_password_form() {

					global $post,$px_theme_option;

					$label = 'pwbox-'.( empty( $post->ID ) ? rand() :

					$post->ID );

					$o = '<div class="password_protected single-password pix-content-wrap">

									<h5>' . __( "This post is password protected. To view it please enter your password below:",'Kings Club' ) . '</h5>';

									$o .= '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">

												<label><input name="post_password" id="' . $label . '" type="password" size="20" /></label>

												<input class="backcolr" type="submit" name="Submit" value="'.__("Submit", "Kings Club").'" />

											</form></div>';

					return $o;

			}



		}



		// breadcrumb function

		

		if ( ! function_exists( 'px_breadcrumbs' ) ) {

			

			function px_breadcrumbs() {

				global $wp_query;

				/* === OPTIONS === */

				$text['home']     = 'Home';

				// text for the 'Home' link

				$text['category'] = '%s';

				// text for a category page

				$text['search']   = '%s';

				// text for a search results page

				$text['tag']      = '%s';

				// text for a tag page

				$text['author']   = '%s';

				// text for an author page

				$text['404']      = 'Error 404';

				// text for the 404 page

				$showCurrent = 1;

				// 1 - show current post/page title in breadcrumbs, 0 - don't show

				$showOnHome  = 1;

				// 1 - show breadcrumbs on the homepage, 0 - don't show

				$delimiter   = '';

				// delimiter between crumbs

				$before      = '<li class="pix-active">';

				// tag before the current crumb

				$after       = '</li>';

				// tag after the current crumb

				/* === END OF OPTIONS === */

				global $post,$px_theme_option;

				$current_page = __("Current Page",'Kings Club');;

				if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") {  $current_page = __("Current Page",'Kings Club'); }else if(isset($px_theme_option["trans_currentpage"])){  $current_page = $px_theme_option["trans_currentpage"];}

				$homeLink = home_url() . '/';

				$linkBefore = '<li>';

				$linkAfter = '</li>';

				$linkAttr = '';

				$link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

				$linkhome = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

				

				if (is_home() || is_front_page()) {

					

					if ($showOnHome == "1") echo '<div class="breadcrumbs"><ul>'.$before.'<a href="' . $homeLink . '">' . $text['home'] . '</a>'.$after.'</ul></div>';

				} else {

					echo '<div class="breadcrumbs"><ul>' . sprintf($linkhome, $homeLink, $text['home']) . $delimiter;

					

					if ( is_category() ) {

						$thisCat = get_category(get_query_var('cat'), false);

						

						if ($thisCat->parent != 0) {

							$cats = get_category_parents($thisCat->parent, TRUE, $delimiter);

							$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);

							$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);

							echo $cats;

						}



						echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

					}



					elseif ( is_search() ) {

						echo $before . sprintf($text['search'], get_search_query()) . $after;

					}



					elseif ( is_day() ) {

						echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;

						echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;

						echo $before . get_the_time('d') . $after;

					}



					elseif ( is_month() ) {

						echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;

						echo $before . get_the_time('F') . $after;

					}



					elseif ( is_year() ) {

						echo $before . get_the_time('Y') . $after;

					}



					elseif ( is_single() && !is_attachment() ) {

						

						if ( get_post_type() != 'post' ) {

							$post_type = get_post_type_object(get_post_type());

							$slug = $post_type->rewrite;

							printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);

							

							if ($showCurrent == 1) echo $delimiter . $before . 'Current Page' . $after;

						} else {

							$cat = get_the_category();

							$cat = $cat[0];

							$cats = get_category_parents($cat, TRUE, $delimiter);

							

							if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);

							$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);

							$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);

							echo $cats;

							

							if ($showCurrent == 1) echo $before .'Current Page' . $after;

						}



					}



					elseif ( !is_single() && !is_page() && get_post_type() <> '' && get_post_type() != 'post' && get_post_type() <> 'events' && get_post_type() <> 'player' && get_post_type() <> 'pointtable' && !is_404() ) {

						$post_type = get_post_type_object(get_post_type());

						echo $before . $post_type->labels->singular_name . $after;

					}



					elseif (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])){

						$taxonomy = $taxonomy_category = '';

						$taxonomy = $wp_query->query_vars['taxonomy'];

						echo $before . $wp_query->query_vars[$taxonomy] . $after;

					}



					elseif ( is_page() && !$post->post_parent ) {

						

						if ($showCurrent == 1) echo $before . get_the_title() . $after;

					}



					elseif ( is_page() && $post->post_parent ) {

						$parent_id  = $post->post_parent;

						$breadcrumbs = array();

						while ($parent_id) {

							$page = get_page($parent_id);

							$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));

							$parent_id  = $page->post_parent;

						}



						$breadcrumbs = array_reverse($breadcrumbs);

						for ($i = 0; $i < count($breadcrumbs); $i++) {

							echo $breadcrumbs[$i];

							

							if ($i != count($breadcrumbs)-1) echo $delimiter;

						}



						

						if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

					}



					elseif ( is_tag() ) {

						echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

					}



					elseif ( is_author() ) {

						global $author;

						$userdata = get_userdata($author);

						echo $before . sprintf($text['author'], $userdata->display_name) . $after;

					}



					elseif ( is_404() ) {

						echo $before . $text['404'] . $after;

					}



					//echo "<pre>"; print_r($wp_query->query_vars); echo "</pre>";

					

					if ( get_query_var('paged') ) {

						// if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';

						// echo __('Page') . ' ' . get_query_var('paged');

						// if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';

					}



					echo '</ul></div>';

				}



			}



		}

 		

		if ( ! function_exists( 'px_logo' ) ) {

			function px_logo($logo_url, $log_width, $logo_height){

			?>

				<a href="<?php  echo home_url(); ?>">

                	<img src="<?php  echo $logo_url; ?>"  style="width:<?php  echo $log_width; ?>px; height:<?php  echo $logo_height; ?>px" 

                    alt="<?php  echo bloginfo('name'); ?>" />

                </a>

	 		<?php

			}



		}

		/*Top and Main Navigation*/

		if ( ! function_exists( 'px_navigation' ) ) {

			function px_navigation($nav='', $menus = 'menus'){

				//sf_mega_menu_walker

				//new px_mega_menu_walker()

				global $px_theme_option;

				// Menu parameters	

				if ( has_nav_menu( $nav ) ) {

				$defaults = array('theme_location' => "$nav",'menu' => '','container' => '','container_class' => '','container_id' => '','menu_class' => '','menu_id' => "$menus",'echo' => false,'fallback_cb' => 'wp_page_menu','before' => '','after' => '','link_before' => '','link_after' => '','items_wrap' => '<ul id="%1$s">%3$s</ul>','depth' => 0,'walker' => '');

				} else {

					$defaults = array('theme_location' => "primary",'menu' => '','container' => '','container_class' => '','container_id' => '','menu_class' => '','menu_id' => "$menus",'echo' => false,'fallback_cb' => 'wp_page_menu','before' => '','after' => '','link_before' => '','link_after' => '','items_wrap' => '<ul id="%1$s">%3$s</ul>','depth' => 0,'walker' => '');

				}

				

				echo do_shortcode(wp_nav_menu($defaults));

			}



		}

	  // Column shortcode with 2/3/4 column option even you can use shortcode in column shortcode

	  

	  if ( ! function_exists( 'px_column_page' ) ) {

		  function px_column_page(){

			  global $px_node;

			  $html = '<div class="element_size_'.$px_node->column_element_size.' column">';

			  $html .= do_shortcode($px_node->column_text);

			  $html .= '</div>';

			  echo $html;

		  }



	  }



  // Get post meta in xml form

  function px_meta_page($meta) {

	  global $px_meta_page;

	  $meta = get_post_meta(get_the_ID(), $meta, true);

	  if ($meta <> '') {

		  $px_meta_page = new SimpleXMLElement($meta);

		  return $px_meta_page;

	  }

	  

  }

  // woocommerce shop meta

  function px_meta_shop_page($meta, $id) {

	  global $px_meta_page;

	  $meta = get_post_meta($id, $meta, true);

		  if ($meta <> '') {

			  $px_meta_page = new SimpleXMLElement($meta);

			  return $px_meta_page;

		  }

	  }









function px_author_description(){

	if (get_the_author_meta('description')){ ?>

    	<!-- About Author -->

        <div class="pix-content-wrap">   

        	<div class="about-author">

                <!-- Thumbnail List Start -->

                <!-- Thumbnail List Item Start -->

                 <figure><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="float-left"><?php echo get_avatar(get_the_author_meta('user_email'), apply_filters('PixFill_author_bio_avatar_size', 90)); ?></a></figure>

                 <div class="text">

                    <h2><a class="colrhover" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author_meta('nicename'); ?></a></h2>

                    <span></span>

                    <p><?php the_author_meta('description'); ?></p>

                    <div class="followus">

                        <?php if(get_the_author_meta('flicker') <> ''){?><a href="<?php the_author_meta('flicker'); ?>"><i class="fa fa-flickr"></i></a><?php }?>

                        <?php if(get_the_author_meta('twitter') <> ''){?><a href="<?php the_author_meta('twitter'); ?>"><i class="fa fa-twitter"></i></a><?php }?>

                        <?php if(get_the_author_meta('facebook') <> ''){?><a href="<?php the_author_meta('facebook'); ?>"><i class="fa fa-facebook"></i></a><?php }?>

                        <?php if(get_the_author_meta('googleplus') <> ''){?><a href="<?php the_author_meta('googleplus'); ?>"><i class="fa fa-google-plus"></i></a><?php }?>

                        <?php if(get_the_author_meta('linkedin') <> ''){?><a href="<?php the_author_meta('linkedin'); ?>"><i class="fa fa-linkedin"></i></a><?php }?>

            		</div>

                </div>

            </div>

        </div>    

       <!-- About Author End -->

    <?php	 

	} 

}



//

 function px_next_prev_custom_links($post_type = 'pointable'){

	 	global $post;

		$previd = $nextid = '';

		$post_categoryy = '';	

		if($post_type == 'events'){

			$post_categoryy = 'event-category';	

		} else if($post_type == 'events'){

			$post_categoryy = 'season-category';	

		} else if($post_type == 'player'){

			$post_categoryy = 'team-category';	

		} else {

			$post_categoryy = 'category';	

		}

		

		$count_posts = wp_count_posts( "$post_type" )->publish;

		$px_postlist_args = array(

		   'posts_per_page'  => -1,

		   'order'           => 'ASC',

		   'post_type'       => "$post_type",

		); 

		$px_postlist = get_posts( $px_postlist_args );



		$ids = array();

		foreach ($px_postlist as $px_thepost) {

		   $ids[] = $px_thepost->ID;

		}

		$thisindex = array_search($post->ID, $ids);

		if(isset($ids[$thisindex-1])){

			$previd = $ids[$thisindex-1];

		} 

		if(isset($ids[$thisindex+1])){

			$nextid = $ids[$thisindex+1];

		} 

		?>

        <div class="single-paginate">

			<?php 

            if (isset($previd) &&  !empty($previd) && $previd >=0 ) {

               ?>

               <div class="next-post-paginate">

                <a href="<?php echo get_permalink($previd); ?>" class="pix-colr"><i class="fa fa-arrow-left"></i>

                    <?php echo __('Previous Post','Kings Club');?>

               </a>

               <h2 class="px-single-page-title">

                   <?php echo get_the_title($previd);?>

               </h2>

               <ul>

               <?php  $before_cat = "<li>";

				$categories_list = get_the_term_list ( $previd, $post_categoryy, $before_cat, ' ', '</li>' );

				if ( $categories_list ){

					printf( __( '%1$s', 'Kings Club'),$categories_list );

				}

				?>

                <li><?php echo date_i18n(get_option('date_format'),strtotime(get_the_date()));?></li>

               </ul>

               </div>

                <?php

            }

            

            if (isset($nextid) &&   !empty($nextid) ) {

                ?>

                <div class="next-post-paginate">

                <a href="<?php echo get_permalink($nextid); ?>" class="pix-colr"><i class="fa fa-arrow-right"></i>

                    <?php echo __('Next Post','Kings Club');?>

                </a>

                 <h2 class="px-single-page-title"><?php echo get_the_title($nextid);?></h2>

               <ul>

               <?php  $before_cat = "<li>";

				$categories_list = get_the_term_list ( $nextid, $post_categoryy, $before_cat, ' ', '</li>' );

				if ( $categories_list ){

					printf( __( '%1$s', 'Kings Club'),$categories_list );

				}

				?>

                <li><?php echo date_i18n(get_option('date_format'),strtotime(get_the_date()));?></li>

               </ul>

                </div>

                <?php	

            }

            ?>

        </div>

        <?php

	 wp_reset_query();

 }

 

// news announcement 

if ( ! function_exists( 'fnc_announcement' ) ) {

	function fnc_announcement(){

		global $post,$px_theme_option;

		date_default_timezone_set('UTC');

		$current_time = strtotime(current_time('m/d/Y H:i', $gmt = 0));

		$image_url = '';

		if (isset($px_theme_option['fixture_type']) and $px_theme_option['fixture_type']=='Fixtures'){

			$meta_compare = ">";

		}elseif(isset($px_theme_option['fixture_type']) and $px_theme_option['fixture_type']=='Results'){

			$meta_compare = "<";

		}else{

			$meta_compare = ">";

		}

		if (isset($px_theme_option['fixture_order']) and $px_theme_option['fixture_order']<>'' and $px_theme_option['fixture_order']<>'0'){

			$fixture_order = $px_theme_option['fixture_order'];

		}else{

			$fixture_order = 'ASC';

		}

			 

			 

         	if(isset($px_theme_option['announcement_fixtures_category']) && $px_theme_option['announcement_fixtures_category'] <> '0'){

				$fixture_category = $px_theme_option['announcement_fixtures_category'];

        		$announcement_no_posts = $px_theme_option['announcement_no_posts'];

				if (empty($announcement_no_posts)){ $announcement_no_posts  = 10;}

				if(isset($px_theme_option['fixture_type']) and $px_theme_option['fixture_type']=='All'){

					$args = array(

							'posts_per_page'			=> "$announcement_no_posts",

							'paged'						=> '1',

							'post_type'					=> 'events',

							'post_status'				=> 'publish',

							'meta_key'                  => 'px_event_from_date_time',

							//'meta_value'				=> $current_time,

							'orderby'					=> 'meta_value',

							'order'						=> "$fixture_order",

						);

				}else{

					$args = array(

                    'posts_per_page'			=> "$announcement_no_posts",

					'paged'						=> '1',

                    'post_type'					=> 'events',

                    'post_status'				=> 'publish',

					'meta_key'                  => 'px_event_from_date_time',

					'meta_value'				=> $current_time,

					'meta_compare'				=> $meta_compare,

                    'orderby'					=> 'meta_value',

                    'order'						=> "$fixture_order",

                	);

				}

				if(isset($fixture_category) && $fixture_category <> '' && $fixture_category <> '0' && $fixture_category <> 'All' ){

					$event_category_array = array('event-category' => "$fixture_category");

					$args = array_merge($args, $event_category_array);

				}

				$custom_query = new WP_Query($args);

				$count_post = $custom_query->post_count;

				if($announcement_no_posts=='-1'){

					$announcement_no_posts = $count_post;

				}

				if($custom_query->have_posts()):

			    px_enqueue_cycle_script();

			//	echo '<pre>';

				//print_r($custom_query );

			//	echo '</per>';

	?>

     <div id="carouselarea">

     	<div class="container">

    		<div class="news-carousel">

            	

                    <div class="center">

                        <span class="cycle-prev" id="cycle-next"><i class="fa fa-arrow-left"></i></span>

                        <span class="cycle-next" id="cycle-prev"><i class="fa fa-arrow-right"></i></span>

                    </div>

                    

                     <div class="cycle-slideshow news-section"

                    data-cycle-fx=carousel 

                    data-cycle-carousel-visible=<?php echo $announcement_no_posts ?>

                    data-cycle-next="#cycle-next"

                    data-cycle-prev="#cycle-prev"

                    data-cycle-slides=">article"

                    data-cycle-timeout=0>

					<?php 

                        while ($custom_query->have_posts()) : $custom_query->the_post();

		

						$event_from_date = get_post_meta($post->ID, "px_event_from_date", true); 

						$post_xml = get_post_meta($post->ID, "px_event_meta", true);	

						if ( $post_xml <> "" ) {

							$px_event_meta = new SimpleXMLElement($post_xml);

						}

						$dateAfter = date('m/d/Y');

						$var_pb_event_team2 = $var_pb_event_team1 = '';

						if(isset($px_event_meta->var_pb_event_team1)and $px_event_meta->var_pb_event_team1 <> '0' and $px_event_meta->var_pb_event_team1 <> ''){

							$var_pb_event_team1 = px_get_term_object($px_event_meta->var_pb_event_team1);

							

						

						}

						if(isset($px_event_meta->var_pb_event_team2) and $px_event_meta->var_pb_event_team2 <> '0' and $px_event_meta->var_pb_event_team2 <> ''){

							 $var_pb_event_team2 = px_get_term_object($px_event_meta->var_pb_event_team2);

						}

                        ?>

                        <article>

                        	<time datetime="<?php echo date_i18n('d-m-Y', strtotime($event_from_date));?>"><?php echo date_i18n(get_option('date_format'), strtotime($event_from_date));?></time>

                            <div class="text">

                                

                                 <?php if(isset($px_event_meta->event_score) && $px_event_meta->event_score <> '' && strtotime($event_from_date) < strtotime($dateAfter)){

					

								 	$event_score = explode('-',$px_event_meta->event_score);

								?>

                                       		

                                            <div class="match-result">

                                            <a href="<?php the_permalink();?>">

                                            	<?php if(isset($var_pb_event_team1->name) && $var_pb_event_team1->name <> ''){?>

                                                <span>

                                                	<?php echo substr($var_pb_event_team1->name, 0, 3);?>

                                                    <big><?php echo $event_score['0'];?></big>

                                                    <br/>

                                                 </span>

                                                 <?php }?>

                                                 <?php if(isset($var_pb_event_team2->name) && $var_pb_event_team2->name <> ''){?>

                                                 <span>

                                                	<?php echo substr($var_pb_event_team2->name, 0, 3);?>

                                                    <big><?php echo $event_score['1'];?></big>

                                                 </span>

                                                 <?php }?>

                                                 </a>

                                            </div>

                                            

                                        <?php }else{ ?>

                                        

                                        	<div class="match-info">

                                            	<a href="<?php the_permalink();?>">

                                            	<?php if(isset($var_pb_event_team1->name) && $var_pb_event_team1->name <> ''){?>

                                                <span>

                                                	<?php echo substr($var_pb_event_team1->name, 0, 3);?>

                                                    

                                                 </span>

                                                 <?php }?>

                                                 <?php if(isset($var_pb_event_team2->name) && $var_pb_event_team2->name <> ''){?>

                                                                                                  <?php if(isset($px_theme_option['trans_switcher']) && $px_theme_option['trans_switcher'] == "on"){ _e('Vs','Kings Club');}else{ echo $px_theme_option['trans_event_vs']; } ?>



                                                 <span>

                                                	<?php echo substr($var_pb_event_team2->name, 0, 3);?>

                                                 </span>

                                                 <?php }?>

                                                 </a>

                                            </div>

                                            

											<?php if(isset($px_event_meta->event_ticket_options) && $px_event_meta->event_ticket_options <> ''){?> 

                                               <a class="pix-btn-open" href="<?php echo $px_event_meta->event_buy_now;?>"> <?php if(isset($px_event_meta->event_ticket_options) && $px_event_meta->event_ticket_options <> ''){echo $px_event_meta->event_ticket_options;}?></a>

                                            <?php }

										}?>

                            </div>

                        </article>

                    <?php endwhile;?>

         		</div>

          	</div>

    	</div>

    </div>

    <?php endif; wp_reset_query(); 

	}

	}

}

// posts/pages title lenght limit

function px_title_lenght($str ='',$start =0,$length =30){

	return substr($str,$start,$length);

}

// Default pages listing article

function px_defautlt_artilce(){

	global $post,$px_theme_option;

	$img_class = '';

	$image_url = px_attachment_image_src(get_post_thumbnail_id($post->ID), 325, 244);

	if($image_url == ""){

		$img_class = 'no-image';

	}

	?>

         <article id="post-<?php the_ID(); ?>" <?php post_class($img_class); ?> >

          <?php if($image_url <> ""){?>

                <figure><a href="<?php the_permalink(); ?>"><img src="<?php echo $image_url;?>" alt=""></a></figure>

            <?php }?>

            <div class="text">

                <h2 class="pix-post-title"><a href="<?php the_permalink(); ?>" class="pix-colrhvr"><?php the_title(); ?></a></h2>

                <p><?php echo px_get_the_excerpt(255,false); ?></p>

               <div class="blog-bottom">

			   <?php px_posted_on(true,false,false,false,true,false);?>

               <a href="<?php the_permalink(); ?>" class="btnreadmore btn pix-bgcolrhvr"><i class="fa fa-plus"></i><?php if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") {  _e("READ MORE",'Kings Club'); }elseif(isset($px_theme_option["trans_read_more"])){  echo $px_theme_option["trans_read_more"];}?></a>

               </div>

            </div>

        </article>



    <?php

	

}

// header search function

function px_search(){

	?>

	<form id="searchform" method="get" action="<?php echo home_url()?>"  role="search">

		<button> <i class="fa fa-search"></i></button>

        <input name="s" id="searchinput" value="<?php _e('', 'Kings Club'); ?>" type="text" />

    </form>

<?php



}

// post date/categories/tags

if ( ! function_exists( 'px_posted_on' ) ) {

	function px_posted_on($cat=true,$tag=true,$comment=true,$date=true,$author=true,$icon=true,$date=true){

		global $px_theme_option;

		?>

 		<ul class="post-options">

        	<?php px_featured();?>

        	<?php if($date==true){?>

                 <li>

                 	<?php if($icon==true){ echo '<i class="fa fa-calendar"></i>'; } ?>

                    <time datetime="<?php echo date('d-m-y',strtotime(get_the_date()));?>"><?php echo get_the_date();?></time>

                </li>

				<?php

				}

				/* translators: used between list items, there is a space after the comma */

				$trans_in = "";

				if($cat==true){

					if(isset($px_theme_option['trans_switcher']) && $px_theme_option['trans_switcher'] == "on"){ $trans_in =__('in','Kings Club');}else{ if(isset($px_theme_option['trans_listed_in'])) $trans_in = $px_theme_option['trans_listed_in']; }

					  $before_cat = "<li><span>".$trans_in."</span> ";

					$categories_list = get_the_term_list ( get_the_id(), 'category', $before_cat, ', ', '</li>' );

					if ( $categories_list ){

						printf( __( '%1$s', 'Kings Club'),$categories_list );

					}

				}

				/* translators: used between list items, there is a space after the comma */

				if($tag == true){

					$before_tag = "<li>".__( 'tags ','Kings Club')."";

					$tags_list = get_the_term_list ( get_the_id(), 'post_tag', $before_tag, ', ', '</li>' );

					if ( $tags_list ){

						printf( __( '%1$s', 'Kings Club'),$tags_list );

					} // End if categories 

				}

				if($comment == true){

					if ( comments_open() ) {  

						echo "<li>"; comments_popup_link( __( '0 Comment', 'Kings Club' ) , __( '1 Comment', 'Kings Club' ), __( '% Comments', 'Kings Club' ) ); 

					}

				}

				

				

				

				edit_post_link( __( 'Edit', 'Kings Club'), '<li>', '</li>' ); 

			?>

		</ul>

	<?php

	}

}

// footer show partner

function px_show_partner(){

		global $px_theme_option;

		$gal_album_db = '0';

		if(isset($px_theme_option['partners_gallery']))

			$gal_album_db =$px_theme_option['partners_gallery'];

		?>

        <?php if($gal_album_db <> "0" and $gal_album_db <> ''){?>

        <div class="our-sponcers">

        	<?php  

				if($px_theme_option['partners_title'] <> ''){ ?>

            		<header class="sponcer-title">

                        <h3><?php  echo $px_theme_option['partners_title']; ?></h3>

                    </header>

            <?php  } 

				if($gal_album_db <> "0" and $gal_album_db <> ''){

			?>

        	<div class="container">

            

            <div class="center">

                <span class="cycle-prev" id="cycle-nexto"><i class="fa fa-angle-left"></i></span>

                <span class="cycle-next" id="cycle-prevt"><i class="fa fa-angle-right"></i></span>

            </div>

           	<div class="cycle-slideshow"

                    data-cycle-fx=carousel

                    data-cycle-next="#cycle-nexto"

                    data-cycle-prev="#cycle-prevt"

                    data-cycle-slides=">article"

                    data-cycle-timeout=0>

            	

                <?php

                    // galery slug to id start

                    $args=array(

                    'name' => (string)$gal_album_db,

                    'post_type' => 'px_gallery',

                    'post_status' => 'publish',

                    'showposts' => 2,

                    );

                    $get_posts = get_posts($args);

                    if($get_posts){

                    $gal_album_db = (int)$get_posts[0]->ID;

                    }

                    // galery slug to id end	

                    $px_meta_gallery_options = get_post_meta($gal_album_db, "px_meta_gallery_options", true);

                    // pagination start

                    if ( $px_meta_gallery_options <> "" ) {

						px_enqueue_cycle_script();

                    $xmlObject = new SimpleXMLElement($px_meta_gallery_options);

                    $limit_start = 0;

                    $limit_end = count($xmlObject);

                        for ( $i = $limit_start; $i < $limit_end; $i++ ) {

                            $path = $xmlObject->gallery[$i]->path;

                            $title = $xmlObject->gallery[$i]->title;

                            $description = $xmlObject->gallery[$i]->description;

                            $use_image_as = $xmlObject->gallery[$i]->use_image_as;

                            $video_code = $xmlObject->gallery[$i]->video_code;

                            $link_url = $xmlObject->gallery[$i]->link_url;

                            $image_url = px_attachment_image_src($path, 150, 150);

                            $image_url_full = px_attachment_image_src($path, 0, 0);

                            ?>

                            <article>

                                <a <?php if($use_image_as==2){?>href="<?php echo $link_url;?>" 

                                target="<?php if($use_image_as==2) { echo '_blank'; } else {echo '_self'; }?>" <?php }?>>

                                <?php  echo "<img src='".$image_url."' alt='".$title."' />"; ?>

                                </a>

                            </article>

                            <?php

                        }

                    } else {

                      echo '<h4 class="pix-heading-color">'.__( 'No results found.', 'Kings Club' ).'</h4>';

                    }

                ?>

               	

        	</div>

         	

                

           <?php } ?>     

        </div>

    </div>

  <?php }  

	}

//

function px_footer_tweets($username = '', $numoftweets = ''){

	global $px_theme_option;

	if($numoftweets == '' or !is_numeric($numoftweets)){$numoftweets = 1;}

		

		echo "<div class='twitter_sign'>";

			if(strlen($username) > 1){

				echo "<figure><i class='fa fa-twitter'></i></figure>";

				$text ='';

				$return = '';

				require_once "include/twitteroauth/twitteroauth.php"; //Path to twitteroauth library

				$consumerkey = $px_theme_option['consumer_key'];

				$consumersecret = $px_theme_option['consumer_secret'];

				$accesstoken = $px_theme_option['access_token'];

				$accesstokensecret = $px_theme_option['access_token_secret'];

				$connection = new TwitterOAuth($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

				$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$numoftweets);

 				?>

                <?php  px_enqueue_flexslider_script(); ?>

				<script type="text/javascript">

					jQuery(document).ready(function() {

						jQuery(".twitter_sign .flexslider").flexslider({

							animation: "fade",

							prevText: "",

							nextText: "",

							slideshowSpeed: 3000

						});

					});

				</script>

                <?php

					if(!is_wp_error($tweets) and is_array($tweets)){

 						$return .= "<div class='flexslider'><ul class='slides'>";

						foreach($tweets as $tweet) {

							$text = $tweet->{'text'};

							foreach($tweet->{'user'} as $type => $userentity) {

							if($type == 'profile_image_url') {	

								$profile_image_url = $userentity;

							} else if($type == 'screen_name'){

								$screen_name = '<a href="https://twitter.com/' . $userentity . '" target="_blank" class="cs-colrhvr" title="' . $userentity . '">@' . $userentity . '</a>';

							}

						}

						foreach($tweet->{'entities'} as $type => $entity) {

						if($type == 'urls') {						

							foreach($entity as $j => $url) {

								$display_url = '<a href="' . $url->{'url'} . '" target="_blank" title="' . $url->{'expanded_url'} . '">' . $url->{'display_url'} . '</a>';

								$update_with = 'Read more at '.$display_url;

								$text = str_replace('Read more at '.$url->{'url'}, '', $text);

								$text = str_replace($url->{'url'}, '', $text);

							}

						} else if($type == 'hashtags') {

							foreach($entity as $j => $hashtag) {

								$update_with = '<a href="https://twitter.com/search?q=%23' . $hashtag->{'text'} . '&src=hash" target="_blank" title="' . $hashtag->{'text'} . '">#' . $hashtag->{'text'} . '</a>';

								$text = str_replace('#'.$hashtag->{'text'}, $update_with, $text);

							}

						} else if($type == 'user_mentions') {

							foreach($entity as $j => $user) {

								  $update_with = '<a href="https://twitter.com/' . $user->{'screen_name'} . '" target="_blank" title="' . $user->{'name'} . '">@' . $user->{'screen_name'} . '</a>';

								  $text = str_replace('@'.$user->{'screen_name'}, $update_with, $text);

							}

						}

					} 

					$large_ts = time();

					$n = $large_ts - strtotime($tweet->{'created_at'});

					if($n < (60)){ $posted = sprintf(__('%d seconds ago','Kings Club'),$n); }

					elseif($n < (60*60)) { $minutes = round($n/60); $posted = sprintf(_n('About a Minute Ago','%d Minutes Ago',$minutes,'Kings Club'),$minutes); }

					elseif($n < (60*60*16)) { $hours = round($n/(60*60)); $posted = sprintf(_n('About an Hour Ago','%d Hours Ago',$hours,'Kings Club'),$hours); }

					elseif($n < (60*60*24)) { $hours = round($n/(60*60)); $posted = sprintf(_n('About an Hour Ago','%d Hours Ago',$hours,'Kings Club'),$hours); }

					elseif($n < (60*60*24*6.5)) { $days = round($n/(60*60*24)); $posted = sprintf(_n('About a Day Ago','%d Days Ago',$days,'Kings Club'),$days); }

					elseif($n < (60*60*24*7*3.5)) { $weeks = round($n/(60*60*24*7)); $posted = sprintf(_n('About a Week Ago','%d Weeks Ago',$weeks,'Kings Club'),$weeks); } 

					elseif($n < (60*60*24*7*4*11.5)) { $months = round($n/(60*60*24*7*4)) ; $posted = sprintf(_n('About a Month Ago','%d Months Ago',$months,'Kings Club'),$months);}

					elseif($n >= (60*60*24*7*4*12)){$years=round($n/(60*60*24*7*52)) ; $posted = sprintf(_n('About a year Ago','%d years Ago',$years,'Kings Club'),$years);} 

					$user = $tweet->{'user'};

					$return .="<li><article><div class='text'>";

					$return .= " <h2 class='cs-post-title'>" . $text . "<time datetime='2011-01-12'> (" . $posted. ")</time></h2>";

					$return .="</div>";

  					$return .= " </article></li>";

					}

					echo $return;

					echo '</ul></div>';

 					

		}else{

			if(isset($tweets->errors[0]) && $tweets->errors[0] <> ""){

				echo '<div class="flexslider"><div class="messagebox alert alert-info align-left">'.$tweets->errors[0]->message.".Please enter valid Twitter API Keys".'</div></div><div class="clear"></div>';

			}else{

				px_no_result_found(false);

			}

		}

	}

	echo '</div>';

}	



// Player Detail Gallery



function px_single_gallery($px_gallery_id=''){

 	$args=array(

		'name' => (string)$px_gallery_id,

		'post_type' => 'px_gallery',

		'post_status' => 'publish',

		'showposts' => 1,

	);

	$get_posts = get_posts($args);

	

	if($get_posts){

	

		

		$gal_album_db = $get_posts[0]->ID;

		

		

	}

	if(isset($gal_album_db) && $gal_album_db <> '')

	{

		$px_cause_gallery = get_post_meta((int)$gal_album_db, "px_meta_gallery_options", true);

	

		if ( $px_cause_gallery <> "" ) {

			$px_image_per_gallery = '';

			$px_xmlObject_gallery = new SimpleXMLElement($px_cause_gallery);

				$limit_start = 0;

				$limit_end = $limit_start+$px_image_per_gallery;

				if($limit_end < 1){

					$limit_end = count($px_xmlObject_gallery);

			}

				$count_post = count($px_xmlObject_gallery);

	?>

	 <header class="pix-heading-title">

        <h2 class="pix-section-title"><?php echo get_the_title((int)$gal_album_db);?></h2>

     </header>

	<div class="gallery ">

      <ul class="lightbox gallery-four-col">

		<?php for ( $i = 0; $i < $limit_end; $i++ ) {



                $path = $px_xmlObject_gallery->gallery[$i]->path;



                $title = $px_xmlObject_gallery->gallery[$i]->title;



                $social_network = $px_xmlObject_gallery->gallery[$i]->social_network;



                $use_image_as = $px_xmlObject_gallery->gallery[$i]->use_image_as;



                $video_code = $px_xmlObject_gallery->gallery[$i]->video_code;



                $link_url = $px_xmlObject_gallery->gallery[$i]->link_url;



                $gallery_image_url = px_attachment_image_src($path, 470, 353);



                if($gallery_image_url <> ''){



					$image_url_full = px_attachment_image_src($path, 0, 0);

	

					?>

					<li>

						<figure>

							<img src="<?php echo $gallery_image_url;?>" alt="#">

							<figcaption>

                            	  <a  data-rel="<?php if($use_image_as==1)echo "prettyPhoto";  elseif($use_image_as==2) echo ""; else echo "prettyPhoto[gallery1]"?>" href="<?php if($use_image_as==1)echo $video_code; elseif($use_image_as==2) echo $link_url; else echo $image_url_full;?>" data-title="<?php if ( $title <> "" ) { echo $title; }?>" >

                            <?php 

							  if($use_image_as==1){

								  echo '<i class="fa fa-video-camera"></i>';

							  }elseif($use_image_as==2){

								  echo '<i class="fa fa-link"></i>';	

							  }else{

								  echo '<i class="fa fa-plus"></i>';

							  }

							?>

                            </a>

							

							</figcaption>

						</figure>

					</li>



        <?php 	}

		}?>

            

      </ul>

   </div>	

<?php   



	}

  }

}

// Get object by slug

function px_get_term_object($var_pb_event_category = ''){

		global $wpdb;

		return $row_cat = $wpdb->get_row("SELECT * from ".$wpdb->prefix."terms WHERE slug = '" . $var_pb_event_category ."'" );	

	}

function px_fixtures_page($page_section_title = ''){

	global $px_node,$post, $px_theme_option,$px_counter_node;

	

	if($px_node->var_pb_fixtures_cat <> '' && $px_node->var_pb_fixtures_cat <> '0'){ 

	if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") {  $start_fixtures = __("Kick-off",'Kings Club'); }else{  if(isset($px_theme_option["trans_event_start"]))$start_fixtures = $px_theme_option["trans_event_start"];}

	?>

    		<div class="element_size_<?php echo $px_node->fixtures_element_size;?>">

                        

                        <?php if($px_node->var_pb_fixtures_view == 'countdown'){

								$hours = '00';

								$mints = '00';

								$featured_args = array(

                                            'posts_per_page'			=> "1",

                                       //     'paged'						=> $_GET['page_id_all'],

                                            'post_type'					=> 'events',

                                            'event-category' 			=> "$px_node->var_pb_fixtures_cat",

                                            'meta_key'					=> 'px_event_from_date',

                                            'meta_value'				=> date('m/d/Y'),

                                            'meta_compare'				=> ">=",

                                            'orderby'					=> 'meta_value',

                                            'post_status'				=> 'publish',

                                            'order'						=> 'ASC',

                                         );

                                $px_featured_post= new WP_Query($featured_args);

							while ($px_featured_post->have_posts()) : $px_featured_post->the_post();	

                                    $event_from_date = get_post_meta($post->ID, "px_event_from_date", true);

                                        $year_event = date("Y", strtotime($event_from_date));

                                        $month_event = date("m", strtotime($event_from_date));

                                        $date_event = date("d", strtotime($event_from_date));

									 $px_featured_meta = get_post_meta($post->ID, "px_event_meta", true);	

                                    if ( $px_featured_meta <> "" ) {

                                        $px_featured_event_meta = new SimpleXMLElement($px_featured_meta);

										if ( $px_featured_event_meta->event_all_day != "on" ) {

											$time = $px_featured_event_meta->event_time;

											

											$time_param = str_replace("PM", '', $px_featured_event_meta->event_time);

											$time_param = str_replace("AM", '', $time_param);

											$time_param_array = explode(':', $time_param);

											$pos = strpos($px_featured_event_meta->event_time, 'PM');

											if ($pos === false) {

													$hours = $time_param_array['0'];

													$mints = $time_param_array['1'];

											} else {

												$hours = $time_param_array['0']+12;

												$mints = $time_param_array['1'];

											}

											

										} else {

											$hours = '00';

											$mints = '00';

											

										}

											

                                    }

									$image_url = px_get_post_img_src($post->ID, '530', '398');

                                    px_enqueue_countdown_script();

									

							

							?>

                        <?php if($px_node->var_pb_fixtures_title <> '' && $page_section_title == ''){?>

                                <header class="pix-heading-title">

                                    <h2 class="pix-section-title"><a href="<?php the_permalink();?>"><?php echo $px_node->var_pb_fixtures_title;?></a></h2>

                                </header>

                              <?php }?>

                           			 <div class="widget widget_countdown">

                                <div class="countdown-section">

                                <?php if($image_url <> '' && $page_section_title == ''){?>

                                    <figure>

                                        <img src="<?php echo $image_url;?>" alt="">

                                    </figure>

                                <?php }?>

                                <!-- Pix Label Strat -->

                                <div class="pix-label">

                                	<span class="pix-tittle"><?php echo $px_node->var_pb_fixtures_title;?></span>

                                	<time>

									<?php echo date_i18n(get_option('date_format'), strtotime($event_from_date));?>

                                    <?php 

										if ( $px_featured_event_meta->event_all_day != "on" ) {

											echo $px_featured_event_meta->event_time;

										}else{

											_e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');

										}

									?>

                                    </time>

                                </div>

                                <!-- Pix Label Strat -->

                                <div class="text">

                                    <div class="pix-sc-team">

                                        <ul>

                                        	<?php if(isset($px_featured_event_meta->var_pb_event_team1) && $px_featured_event_meta->var_pb_event_team1 <> '' && $px_featured_event_meta->var_pb_event_team1 <> '0'){?>

                                            <li>

                                                <figure>

                                                    <?php

                                                    $team1_row = px_get_term_object($px_featured_event_meta->var_pb_event_team1);

													

                                                      $team_img1 = px_team_data_front($team1_row->term_id);

                                                    if($team_img1[0] <> ''){

                                                    ?>

                                                        <img alt="" src="<?php echo $team_img1[0];?>">

                                                    <?php }?>

                                                </figure>

                                            </li>

                                            <?php }?>

                                            <?php if(isset($px_featured_event_meta->var_pb_event_team2) && $px_featured_event_meta->var_pb_event_team2 <> '' && $px_featured_event_meta->var_pb_event_team2 <> '0'){?>

                                            <li>

                                                <figure>

                                                    <?php

													 $px_featured_event_meta->var_pb_event_team2;

                                                    $team2_row = px_get_term_object($px_featured_event_meta->var_pb_event_team2);

                                                    $team_img2 = px_team_data_front($team2_row->term_id);

													

                                                    if($team_img2[0] <> ''){

                                                    ?>

                                                        <img alt="" src="<?php echo $team_img2[0];?>">

                                                    <?php }?>

                                                </figure>

                                            </li>

                                            <?php }?>

                                        </ul>

                                        <div class="pix-sc-team-info">

                                        	<p>

                                            	<?php 

													if(isset($team1_row->name)){echo $team1_row->name;}

											   ?>

                                               <span class="vs"><?php if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") {  _e("VS",'Kings Club'); }else{  echo $px_theme_option["trans_event_vs"];}?></span>

                                            	<?php 

														if(isset($team2_row->name)){echo $team2_row->name;}

												   ?>

                                                   <span class="time-sec"><?php echo ''.$px_featured_event_meta->event_address;?></span>

                                            </p>

                                        </div>

                                    </div>

                                    <header class="pix-cont-title">

                                        <h2 class="pix-section-title"><span>

										<?php if(isset($px_featured_event_meta->event_time_title) && $px_featured_event_meta->event_time_title <> ''){echo $px_featured_event_meta->event_time_title.' ';}

												if ( isset($px_featured_event_meta->event_all_day) && $px_featured_event_meta->event_all_day != "on" ) {

													echo $px_featured_event_meta->event_time;

												}else{

													_e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');

												}

										?>

										</span></h2>

                                    </header>

                                    <?php $random_id = px_generate_random_string();?>

                                    <div class="defaultCountdown" id="defaultCountdown<?php echo $random_id;?>"></div>

                                   	<script>

										jQuery(document).ready(function($) {

										   px_event_countdown('<?php echo $year_event;?>','<?php echo $month_event;?>','<?php echo $date_event;?>',<?php echo $hours;?>,<?php echo $mints;?>,'<?php echo $random_id;?>');

										});

									</script>

                                    <div class="countdown-buttons">

                                        <?php

											add_to_calender(); 

											if($px_featured_event_meta->event_ticket_options <> ''){?> 

                                            <div class="buy-ticket-button">

                                               <a class="btn pix-btn-open" href="<?php echo $px_featured_event_meta->event_buy_now;?>"> <?php if(isset($px_featured_event_meta->event_ticket_options) && $px_featured_event_meta->event_ticket_options <> ''){echo $px_featured_event_meta->event_ticket_options;}?></a>

                                             </div>

                                        <?php }?>

                                        

                                     </div>

                                    

                                  

                                </div>

                                </div>

                            </div>

                            

                            <?php 

								 endwhile; 

							 

							} else {

								$featured_args = array(

                                            'posts_per_page'			=> "$px_node->var_pb_fixtures_per_page",

                                       //     'paged'						=> $_GET['page_id_all'],

                                            'post_type'					=> 'events',

                                            'event-category' 			=> "$px_node->var_pb_fixtures_cat",

                                            'meta_key'					=> 'px_event_from_date',

                                            'meta_value'				=> date('m/d/Y'),

                                            'meta_compare'				=> ">=",

                                            'orderby'					=> 'meta_value',

                                            'post_status'				=> 'publish',

                                            'order'						=> 'ASC',

                                         );

                                $px_featured_post= new WP_Query($featured_args);

								

								?>

                            	<?php if($px_node->var_pb_fixtures_title <> ''){?> 

                                    <header class="pix-heading-title">

                                        <h2 class="pix-section-title">

                                            <?php echo $px_node->var_pb_fixtures_title;?>

                                        </h2>

                                    </header>

                                    

                                    <?php if ( $px_featured_post->have_posts() <> "" ) {?>

                                        <div class="event event-listing event-listing-v2">

                                        <?php

                                                while ( $px_featured_post->have_posts() ): $px_featured_post->the_post();

                                                $event_from_date = get_post_meta($post->ID, "px_event_from_date", true);

                                                

                                                $post_xml = get_post_meta($post->ID, "px_event_meta", true);	

                                                if ( $post_xml <> "" ) {

                                                    $px_event_meta = new SimpleXMLElement($post_xml);

                                                    $team1_row = px_get_term_object($px_event_meta->var_pb_event_team1);

                                                    $team2_row = px_get_term_object($px_event_meta->var_pb_event_team2);

                                                }

                                                ?>

                                        

                                            <article>

                                                <div class="text">

                                                    <div class="top-event">

                                                        <h2 class="pix-post-title">

                                                            <a href="<?php the_permalink();?>"><?php the_title(); ?></a>

                                                        </h2>

                                                    </div>

                                                     <?php 

														 if($px_event_meta->event_venue <> '' and $px_event_meta->event_venue  <> '0'){

														 echo '<span class="match-category cat-'.$px_event_meta->event_venue.'">'.substr($px_event_meta->event_venue,0,1).'</span>';

										  				 } ?>

													

                                                    

                                                    <ul class="post-options">

                                                        <li> <i class="fa fa-calendar"></i>

                                                            <?php echo date_i18n(get_option('date_format'), strtotime($event_from_date));?>

                                                        </li>

                                                        <li><i class="fa fa-clock-o"></i>

                                                         <?php 

                                                            if ( $px_event_meta->event_all_day != "on" ) {

                                                                echo $px_event_meta->event_time;

                                                            }else{

                                                                _e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');

                                                            }

                                                        ?>

                                                            </li>

                                                        <?php if($px_event_meta->event_ticket_options <> ''){?> <li><i class="fa fa-map-marker"></i><?php echo $px_event_meta->event_address;?></li><?php }?>

                                                    </ul>

                                                </div>

                                            </article>

                                            <?php endwhile;?>

                                            <?php if($px_node->var_pb_fixtures_viewall_title <> ''){?> <a href="<?php echo $px_node->var_pb_fixtures_viewall_link;?>" class="btn btn-viewall pix-bgcolrhvr"><i class="fa fa-calendar"></i><?php echo $px_node->var_pb_fixtures_viewall_title;?>l</a><?php }?>

                                        </div>

                        <?php }?>

                        	

                        

                        <?php }?>

                            <?php }?>

                        

                    </div>

    

    <?php

	

	wp_reset_query();

	}

}





// team images

function px_team_data_front($team_id){

		$team_data = get_option("team_$team_id");

		if (isset($team_data)){

			$data[] = stripslashes($team_data['icon']);

		}

		return $data;

}



// Flexslider function



if ( ! function_exists( 'px_flex_slider' ) ) {



	function px_flex_slider($width,$height,$slider_id, $single_slider = ''){



		global $px_node,$px_theme_option,$px_counter_node;

		



		$px_counter_node++;



		if($slider_id == ''){



			$slider_id = $px_node->slider;



		}





			$px_meta_slider_options = get_post_meta($slider_id, "px_meta_gallery_options", true); 



		?>



		<!-- Flex Slider -->











		  <div class="flexslider">



			  <ul class="slides">



				<?php 



					$px_counter = 1;



					$px_xmlObject_flex = new SimpleXMLElement($px_meta_slider_options);

					echo '';

					$gallery_count = $px_xmlObject_flex->gallery;

					foreach ( $px_xmlObject_flex->children() as $as_node ){

 						$image_url = px_attachment_image_src($as_node->path,$width,$height); 

						if(isset($as_node->link) && $as_node->link <> ''){$link = $as_node->link;} else {$link = '';}

						?>

                        <li>

                            <figure>

                                <img src="<?php echo $image_url ?>" alt="">   

                                    <?php if($as_node->title <> ''){?>

                                    <figcaption>

                                    	<h2 class="cs-bgcolr"><a <?php if(isset($as_node->link) && $as_node->link <> ''){?>href="<?php echo $as_node->link;?>" target="<?php echo $as_node->link_target;?>" <?php }?>><?php echo $as_node->title;?></a></h2>

                                      

                                    </figcaption><?php }?>

                                

                            </figure>

                        </li>

					<?php 

					$px_counter++;

					}

				?>

                

			  </ul>

             

		  </div>

		<?php px_enqueue_flexslider_script(); ?>

		<!-- Slider height and width -->



		<!-- Flex Slider Javascript Files -->



		<script type="text/javascript">

			jQuery(document).ready(function($) {

				<?php if(isset($single_slider) && $single_slider == 'single'){?>

					px_flexsliderGallery();

				<?php } else {?>

					px_flexsliderBannerGallery(); 

				<?php } ?>

			});

		</script>



	<?php



	}



}







if ( ! function_exists( 'px_player_slider' ) ) {



	function px_player_slider($width,$height,$slider_id, $single_slider = ''){



		global $px_node,$px_theme_option,$px_counter_node;

		

		 if($px_theme_option["trans_switcher"] == "on") { $out_of = __("Out of",'Kings Club'); }else{  $out_of = $px_theme_option["trans_out_of"];}

		

		$px_counter_node++;

		if($slider_id == ''){

			$slider_id = $px_node->slider;

		}

		$px_meta_slider_options = get_post_meta($slider_id, "px_meta_gallery_options", true); 

		?>

		<!-- Flex Slider -->

		  <div class="flexslider">

			  <ul class="slides lightbox">

				<?php 

					$px_counter = 1;

					$px_xmlObject_flex = new SimpleXMLElement($px_meta_slider_options);

					$gallery_count = count($px_xmlObject_flex->gallery);

					foreach ( $px_xmlObject_flex->children() as $as_node ){

						$image_url_full = px_attachment_image_src($as_node->path,'',''); 

 						$image_url = px_attachment_image_src($as_node->path,$width,$height); 

						if(isset($as_node->link) && $as_node->link <> ''){$link = $as_node->link;} else {$link = '';}

						$link_target = '';

						if($as_node->use_image_as==2){

							$link_target = 	'target="_blank"';

						}

						?>

                        <li>

                            <figure>

                            	<img src="<?php echo $image_url ?>" alt=""> 

                                <a class="pix-zoom"  data-rel="<?php if($as_node->use_image_as==1)echo "prettyPhoto";  elseif($as_node->use_image_as==2) echo ""; else echo "prettyPhoto[gallery1]"?>" href="<?php if($as_node->use_image_as==1)echo $video_code; elseif($as_node->use_image_as==2) echo $link; else echo $image_url_full;?>" <?php echo $link_target;?> data-title="<?php if ( $as_node->title <> "" ) { echo $as_node->title; }?>" ><i class="fa fa-arrows"></i></a>

                                    <figcaption>

                                    <?php 

									

                                      if($as_node->use_image_as==1){

                                          echo '<i class="fa fa-video-camera"></i>';

                                      }elseif($as_node->use_image_as==2){

                                          echo '<i class="fa fa-link"></i>';

                                      }else{

                                           echo '<i class="fa fa-camera"></i>';

                                      }

                                    ?>

                                    <h2 class="cs-bgcolr"><a <?php if(isset($as_node->link) && $as_node->link <> ''){?>href="<?php echo $as_node->link;?>" target="<?php echo $as_node->link_target;?>" <?php }?>><?php echo $as_node->title;?></a></h2>

									

                                    <span class="px-count">

									<?php if($single_slider == 'player'){

                                            echo $px_counter.' '.$out_of.' '.$gallery_count;

                                        }

                                    ?></span>

                                    </figcaption>

                                

                            </figure>

                        </li>

					<?php 

					$px_counter++;

					}

				?>

                

			  </ul>

             

		  </div>

		<?php px_enqueue_flexslider_script(); ?>

		<!-- Slider height and width -->



		<!-- Flex Slider Javascript Files -->



		<script type="text/javascript">

			jQuery(document).ready(function($) {

					px_flexsliderGallery();

			});

		</script>



	<?php



	}



}





// CycleSlider function



if ( ! function_exists( 'px_cycle_slider' ) ) {



	function px_cycle_slider($width,$height,$slider_id){

		 $px_meta_slider_options = get_post_meta($slider_id, "px_meta_gallery_options", true);

			?>

            <script type="text/javascript">

				jQuery(document).ready(function($) {

					jQuery('#slideshow').cycle({

						fx:       'fade',

						timeout:   2000,

						after:     onAfter

					});

				});

				

				function onAfter(curr,next,opts) {

					var caption = 'Image ' + (opts.currSlide + 1) + ' of ' + opts.slideCount;

					jQuery('#caption').html(caption);

				}

			</script>

		<div class="teamdetail-carousel"> 

         <div class="center">

                <span class="cycle-prev" id="cycle-next<?php echo $slider_id;?>"><i class="fa fa-chevron-left"></i></span>

                <span class="cycle-next" id="cycle-prev<?php echo $slider_id;?>"><i class="fa fa-chevron-right"></i></span>

            </div>

             <div id="slideshow" class="cycle-slideshow"

                data-cycle-fx=carousel

                data-cycle-next="#cycle-next<?php echo $slider_id;?>"

                data-cycle-prev="#cycle-prev<?php echo $slider_id;?>"

                data-cycle-slides=">figure"

                data-cycle-timeout=0>

						<?php 

                        $px_counter = 1;

                        $px_xmlObject_flex = new SimpleXMLElement($px_meta_slider_options);

                        

                        foreach ( $px_xmlObject_flex->children() as $as_node )

                        {

                            $image_url = px_attachment_image_src($as_node->path,$width,$height); 

                            if(isset($as_node->link) && $as_node->link <> ''){$link = $as_node->link;} else {$link = '';}

                       		 ?>

                        <figure>

                        <img src="<?php echo $image_url ?>" alt="">   

                        <?php if($as_node->title <> ''){?>

                            <figcaption>

                                <i class="fa fa-camera"></i><h2 class="cs-bgcolr"><a <?php if(isset($as_node->link) && $as_node->link <> ''){?>href="<?php echo $as_node->link;?>" target="<?php echo $as_node->link_target;?>" <?php }?>><?php echo $as_node->title;?></a></h2>

                            </figcaption><?php }?>

                        </figure>

                                

                        <?php 

                            $px_counter++;

                        }

                        ?>

                </div>

                <p id="caption"></p>

			</div>

		<?php

		}



}













function px_page_title(){

	if(function_exists("is_shop") and is_shop()){

		$px_shop_id = woocommerce_get_page_id( 'shop' );

		echo "<div class=\"subtitle\"><h1 class=\"cs-page-title\">".get_the_title($px_shop_id)."</h1></div>";

	}else if(function_exists("is_shop") and !is_shop()){

		echo '<div class="subtitle">';

			get_subheader_title();

		echo '</div>';

	}else{

		echo '<div class="subtitle">';

			get_subheader_title();

		echo '</div>';

	}                        	

}



// Calendar time

function calender_time($event_time) {



	$mints = $mints = $seconds = '';

	$seconds = '00';

	$time = $event_time;

	$time_param = str_replace("PM", '', $event_time);

	$time_param = str_replace("AM", '', $time_param);

	$time_param_array = explode(':', $time_param);

	$pos = strpos($time, 'PM');

	

	if ($pos === false) {

			$hours = $time_param_array['0'];

			$mints = $time_param_array['1'];

	} else {

		if(isset($time_param_array['0']) && $time_param_array['0'] < 12){

			$hours = $time_param_array['0']+12;

		} else {

			$hours = $time_param_array['0'];

		}

		$mints = $time_param_array['1'];

	}

	

   return $hours.':'.$mints.':'.$seconds;



}



function get_formated_date($date)



{



	return mysql2date(get_option('date_format'), $date);



}



function get_formated_time($time)



{



	return mysql2date(get_option('time_format'), $time, $translate=true);;



}



// Calendar



function add_to_calender()



{	global $post;



	$px_theme_option = get_option('px_theme_option');



	$calendar_args=array('outlook'=>1,'google_calender'=>1,'yahoo_calender'=>1,'ical_cal'=>1);



	if($calendar_args)



	{



		$calendar_url = px_event_calendar($post->ID);



			?>



    <div class="add-calender"><a class="bgcolrhvr btn add_calendar_toggle<?php echo $post->ID;?> btn-toggle_cal" href="#inline-<?php echo $post->ID;?>"><i class="fa fa-plus"></i> <?php if($px_theme_option["trans_switcher"] == "on") { _e("Add to Calendar",'Kings Club'); }else{  echo $px_theme_option["trans_add_calendar"];} ?></a>



      <ul class="add_calendar add_calendar<?php echo $post->ID;?>" id="inline-<?php echo $post->ID;?>" >



        <?php if($calendar_args['outlook']){?>



        <li class="i_calendar">



        <a href="<?php echo $calendar_url['ical']; ?>"> 



          <img src="<?php echo get_template_directory_uri(); ?>/images/calendar-icon.png" alt="" width="24" />



        </a> 



        </li>



        <?php }?>



        <?php if($calendar_args['google_calender']){?>



        <li class="i_google"><a href="<?php echo $calendar_url['google']; ?>" target="_blank"> 



          <img src="<?php echo get_template_directory_uri(); ?>/images/google-icon.png" alt="" width="25" />



        </a> 



        </li>



        <?php }?>



        <?php if($calendar_args['yahoo_calender']){?>



        <li class="i_yahoo"><a href="<?php echo $calendar_url['yahoo']; ?>" target="_blank">



          <img src="<?php echo get_template_directory_uri(); ?>/images/yahoo-icon.png" alt="" width="24" />



        </a> 



        </li>



        <?php }?>



      </ul>



    </div>



<?php



	}



}







/*	Function to get the events info on calander -- START	*/



function px_event_calendar($post_id = '') {



	



	if(!isset($post_id) && $post_id == ''){



		global $post;



		$post_id = $post->ID;



	}



	$cal_post = get_post($post_id);



	if ($cal_post) {



		$event_from_date = get_post_meta($post_id, "px_event_from_date", true);



		$px_event_to_date = '';



		$px_event_meta = get_post_meta($post_id, "px_event_meta", true);



			if ( $px_event_meta <> "" ) {



				$px_event_meta = new SimpleXMLElement($px_event_meta);



				if(isset($px_event_meta->event_address) && $px_event_meta->event_address <> ''){

					$location = (string)$px_event_meta->event_address;	

				}else{

					$location = '';

				}

			}

		$start_year = date('Y',strtotime($event_from_date));



		$start_month = date('m',strtotime($event_from_date));



		$start_day = date('d',strtotime($event_from_date));



		$end_year = '';





		$end_month = '';



		$end_day = '';



		if ( $px_event_meta->event_all_day != "on" ) {



			$start_time = calender_time($px_event_meta->event_time);



		} else {



			$start_time = $end_time = '';



		}



		if (($start_time != '') && ($start_time != ':')) { $event_start_time = explode(":",$start_time); }



		$post_title = get_the_title($post_id);

		

		$post_title = html_entity_decode($post_title);



		$px_vcalendar = new vcalendar();                          



		$px_vevent = new vevent();  



		$site_info = get_bloginfo('name').'Events';



		$px_vevent->setProperty( 'categories' , $site_info );                   



		



		if (isset( $event_start_time)) { @$px_vevent->setProperty( 'dtstart' 	,  @$start_year, @$start_month, @$start_day, @$event_start_time[0], @$event_start_time[1], 00 ); } else { $px_vevent->setProperty( 'dtstart' ,  $start_year, $start_month, $start_day ); } // YY MM dd hh mm ss



		/*if (isset($event_end_time)) { @$px_vevent->setProperty( 'dtend'   	,  $end_year, $end_month, $end_day, $event_end_time[0], $event_end_time[1], 00 );  } else { $px_vevent->setProperty( 'dtend' , $end_year, $end_month, $end_day );  }*/ // YY MM dd hh mm ss



		$px_vevent->setProperty( 'description' 	, strip_tags($cal_post->post_excerpt)); 



		if (isset($location)) { $px_vevent->setProperty( 'location'	, $location ); } 



		$px_vevent->setProperty( 'summary'	, $post_title );                 



		$px_vcalendar->addComponent( $px_vevent );                        



		$templateurl = get_template_directory_uri().'/cache/';



		//makeDir(get_bloginfo('template_directory').'/cache/');



		$home = home_url();



		$dir = str_replace($home,'',$templateurl);



		$dir = str_replace('/wp-content/','wp-content/',$dir);

		

		

		$directory_url =  get_template_directory_uri();

		$directorypath = explode('/', $directory_url);

		$themefolderName = $directorypath[count($directorypath)-1];



		$px_vcalendar->setConfig( 'directory', ABSPATH .'wp-content/themes/'.$themefolderName.'/cache' ); 



		$px_vcalendar->setConfig( 'filename', 'event-'.$post_id.'.ics' ); 



		$px_vcalendar->saveCalendar(); 



		////OUT LOOK & iCAL URL//



		$output_calendar_url['ical'] = $templateurl.'event-'.$post_id.'.ics';



		////GOOGLE URL//



		$google_url = "http://www.google.com/calendar/event?action=TEMPLATE";

		$post_title = strip_tags($post_title);

		$google_url .= "&text=".urlencode($post_title);

	

		if (isset($event_start_time) ) { 

			$Start_time = str_replace('.','',@$event_start_time[0]).str_replace('.','',@$event_start_time[1]).str_replace('.','',@$event_start_time[2]);

			$Start_time = str_replace(' ','',$Start_time);

			$google_url .= "&dates=".@$start_year.@$start_month.@$start_day."T".$Start_time.'/'.@$start_year.@$start_month.@$start_day."T".$Start_time; 



		} else { 

			$google_url .= "&dates=".$start_year.$start_month.$start_day."/".$start_year.$start_month.$start_day; 

		}





		$google_url .= "&sprop=website:".get_permalink($post_id);



		$google_url .= "&details=".strip_tags($cal_post->post_excerpt);

		if (isset($location)) { $google_url .= "&location=".$location; } else { $google_url .= "&location=Unknown"; }



		$google_url .= "&trp=true";



		$output_calendar_url['google'] = $google_url;



		////YAHOO CALENDAR URL///



		$yahoo_url = "http://calendar.yahoo.com/?v=60&view=d&type=20";



		$yahoo_url .= "&title=".str_replace(' ','+',$post_title);



		if (isset($event_start_time)) 



		{ 



			$yahoo_url .= "&st=".@$start_year.@$start_month.@$start_day."T".@$event_start_time[0].@$event_start_time[1]."00"; 



		}



		else



		{ 



			$yahoo_url .= "&st=".$start_year.$start_month.$start_day;



		}



		if(isset($event_end_time))



		{



			//$yahoo_url .= "&dur=".$event_start_time[0].$event_start_time[1];



		}



		$yahoo_url .= "&desc=".str_replace(' ','+',strip_tags($cal_post->post_excerpt)).' -- '.get_permalink($post_id);



		$yahoo_url .= "&in_loc=".str_replace(' ','+',$location);



		$output_calendar_url['yahoo'] = $yahoo_url;



	}



	return $output_calendar_url;



} 



// Get Main background

	

	function px_bg_image(){

	

		global $px_theme_option;

	

		$bg_img = '';

		

		

		if ( isset($_POST['bg_img']) ) {

	

			$_SESSION['kcsess_bg_img'] = $_POST['bg_img'];

	

			echo $bg_img = get_template_directory_uri()."/images/background/bg".$_SESSION['kcsess_bg_img'].".png";

	

		}

	

		else if ( isset($_SESSION['kcsess_bg_img']) and !empty($_SESSION['kcsess_bg_img'])){

	

			$bg_img = get_template_directory_uri()."/images/background/bg".$_SESSION['kcsess_bg_img'].".png";

	

		}

	

		else {

	

			if (isset($px_theme_option['bg_img_custom']) and $px_theme_option['bg_img_custom'] == "" ) {

	

				if (isset($px_theme_option['bg_img']) and $px_theme_option['bg_img'] <> 0 ){

	

					$bg_img = get_template_directory_uri()."/images/background/bg".$px_theme_option['bg_img'].".png";

	

				}

	

			}

	

			else  { 

	

				if(isset($px_theme_option['bg_img_custom']))

					$bg_img = $px_theme_option['bg_img_custom'];

	

			}

	

		}

	

		if ( $bg_img <> "" ) {

	

			echo ' style="background:url('.$bg_img.') ' . $px_theme_option['bg_repeat'] . ' top  ' . $px_theme_option['bg_position'] . ' 		' . $px_theme_option['bg_attach'].'"';

	

		}

	

	}

	

	// Main wrapper class function

	

	function px_wrapper_class(){

	

		global $px_theme_option;

		

		

		if ( isset($_POST['layout_option']) ) {

	

			echo $_SESSION['kcsess_layout_option'] = $_POST['layout_option'];

	

		}

	

		elseif ( isset($_SESSION['kcsess_layout_option']) and !empty($_SESSION['kcsess_layout_option'])){

	

			echo $_SESSION['kcsess_layout_option'];

	

		}

	

		else {

			

			if ( isset($px_theme_option['layout_option']) )

				echo $px_theme_option['layout_option'];

	

			$_SESSION['kcsess_layout_option']='';

	

		}

	

	}

	

	// Get Background color Pattren

	

	function px_bgcolor_pattern(){

	

		global $px_theme_option;

	

		// pattern start

		

		$pattern = '';

	

		$bg_color = '';

	

		if ( isset($_POST['custome_pattern']) ) {

	

			$_SESSION['kcsess_custome_pattern'] = $_POST['custome_pattern'];

	

			$pattern = get_template_directory_uri()."/images/pattern/pattern".$_SESSION['kcsess_custome_pattern'].".png";

	

		}

	

		else if ( isset($_SESSION['kcsess_custome_pattern']) and !empty($_SESSION['kcsess_custome_pattern'])){

	

			$pattern = get_template_directory_uri()."/images/pattern/pattern".$_SESSION['kcsess_custome_pattern'].".png";

	

		}

	

		else {

	

			if (isset($px_theme_option['custome_pattern']) and $px_theme_option['custome_pattern'] == "" ) {

	

				if (isset($px_theme_option['pattern_img']) and $px_theme_option['pattern_img'] <> 0 ){

	

					$pattern = get_template_directory_uri()."/images/pattern/pattern".$px_theme_option['pattern_img'].".png";

	

				}

	

			}

	

			else { 

				if ( isset($px_theme_option['custome_pattern']) )

					$pattern = $px_theme_option['custome_pattern'];

	

			}

	

		}

	

		// pattern end

	

		// bg color start

	

		if ( isset($_POST['bg_color']) ) {

	

			$_SESSION['kcsess_bg_color'] = $_POST['bg_color'];

	

			$bg_color = $_SESSION['kcsess_bg_color'];

	

		}

	

		else if ( isset($_SESSION['kcsess_bg_color']) ){

	

			$bg_color = $_SESSION['kcsess_bg_color'];

	

		}

	

		else {

			if ( isset($px_theme_option['bg_color']) )

				$bg_color = $px_theme_option['bg_color'];

	

		}

	

		// bg color end

		if($bg_color <> '' or $pattern <> ''){

			echo ' style="background:'.$bg_color.' url('.$pattern.')" ';

		}

	

	}



	function px_no_result_found(){

		 _e("No results found.",'KingsClub');

	}

	

	// rating function



	function px_user_rating(){

		global $post;

		$user_rating = 0;

		$rating_vote_counter = get_post_meta($post->ID, "rating_vote_counter", true);

		$rating_value = get_post_meta($post->ID, "rating_value", true);

		if ( $rating_value <> 0 and $rating_vote_counter <> 0 ) {

			$user_rating =  ( $rating_value / $rating_vote_counter  ) ;

		}

		return $user_rating = number_format( $user_rating);

	}

	

	function px_player_pointtable($pointtable){

		global $post,$px_theme_option;

		if(isset($pointtable) && $pointtable <> ''){

			$args=array(

				'name' => (string)$pointtable,

				'post_type' => 'pointtable',

				'post_status' => 'publish',

				'showposts' => 1,

			);

			$get_posts = get_posts($args);

			if($get_posts){

				$gal_pointtable_id = (int)$get_posts[0]->ID;

				$pointtable_counter=1;

				$px_pointtable = get_post_meta($gal_pointtable_id, "px_pointtable", true);

				if ( $px_pointtable <> "" ) {

					$px_xmlObject = new SimpleXMLElement($px_pointtable);

					$var_pb_record_per_post =$px_xmlObject->var_pb_record_per_post;

				}else{

					$var_pb_record_per_post= '';

				}

			?>

            <header class="pix-heading-title">

                <h2 class="pix-section-title"><?php echo get_the_title((int)$gal_pointtable_id);?></h2>

             </header>

             <div class="points-table fullwidth">

             	

                    <table class="table table-condensed table_D3D3D3">

                        <thead>

                            <tr>

                            <th>

                                <span class="box1">

                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e("Pos",'Kings Club'); }else{  echo $px_theme_option["trans_pos"];} ?>

                                </span>

                            </th>

                             <th>

                                <span class="box2">

                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e("Team",'Kings Club'); }else{  echo $px_theme_option["trans_team"];} ?>

                                 </span>

                            </th>

                            <th>

                                <span class="box3">

                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e("Play",'Kings Club'); }else{  echo $px_theme_option["trans_play"];} ?>

                                </span>

                            </th>

                            <th>

                                <span class="box4">

                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e(" +/-",'Kings Club'); }else{  echo $px_theme_option["trans_plusminus"];} ?>

                                </span>

                            </th>

                            <th>

                                <span class="box5">

                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e("Points",'Kings Club'); }else{  echo $px_theme_option["trans_totalpoints"];} ?>  			 	</span>

                            </th>

                            </tr>

                         </thead>

                         <tbody>

                     

                  <?php

                  if($px_xmlObject->var_pb_record_per_post <> '' and $px_xmlObject->var_pb_record_per_post > 0){



                    foreach ( $px_xmlObject->track as $track ){

                        if(($pointtable_counter-1) < $px_xmlObject->var_pb_record_per_post){

                            

                            if(isset($track->var_pb_pointtable_team) && $track->var_pb_pointtable_team <> ''){

                                $row_cat = px_get_term_object($track->var_pb_pointtable_team);

                                $teamname = $row_cat->name;	

                            } else {

                                $teamname = '';	

                            }

                        echo '<tr>

                              <td>'.$pointtable_counter.'</td>

                              <td>'.$teamname.'</td>

                              <td>'.$track->var_pb_match_played.'</td>

                              <td>'.$track->var_pb_pointtable_plusminus_points.'</td>

                              <td>'.$track->var_pb_pointtable_totalpoints.'</td>

                        </tr>';

                       }

                          $pointtable_counter++;

                      }

                  }else{

                      foreach ( $px_xmlObject->track as $track ){

                          if(isset($track->var_pb_pointtable_team) && $track->var_pb_pointtable_team <> ''){

                                $row_cat = px_get_term_object($track->var_pb_pointtable_team);

                                $teamname = $row_cat->name;	

                            } else {

                                $teamname = '';	

                            }

                        echo '<tr>

                              <td>'.$pointtable_counter.'</td>

                              <td>'.$teamname.'</td>

                              <td>'.$track->var_pb_match_played.'</td>

                              <td>'.$track->var_pb_pointtable_plusminus_points.'</td>

                              <td>'.$track->var_pb_pointtable_totalpoints.'</td>

                        </tr>';

                       }

                          $pointtable_counter++;

                  }

                 ?>

                </tbody>

             </table>

           </div>

			<?php

			}

		}

	}

	// review criteria check

	function px_criteria_check($value) {

		global $px_theme_option;

		$html = '';

		for ( $j = 1; $j <= 10; $j++ ) {

			if ( $value >= $px_theme_option['review_criteria_'.$j.'_1'] and $value <= $px_theme_option['review_criteria_'.$j.'_2'] ) {

				$html = $px_theme_option['review_criteria_text_'.$j.''];

			}

		}

		return $html;

	}

	function px_rating_section($px_xmlObject){

		global $post;

		$image_url_small = px_get_post_img_src($post->ID, 470, 353);	

		?>

        	<div class="px-review-section <?php echo $px_xmlObject->var_pb_review_section_position;?>">

                   <?php if($px_xmlObject->var_pb_review_section_title <> '') {?>

                            <header class="pix-heading-title">

                                <h2 class="pix-section-title"><?php echo $px_xmlObject->var_pb_review_section_title; ?></h2>

                            </header>

                	<?php }?>

                    <!-- Blog Rating Section Start -->

                      <div class="blog-rating-sec">

                      	   

                            <figure>

                            	<?php if($image_url_small <> ''){?>

                                	<img src="<?php echo $image_url_small;?>" alt="<?php the_title();?>">

                                <?php }?>

                          <figcaption>

                          	<?php

								$rating_value = get_post_meta($post->ID, "rating_value", true);

								if($rating_value == ''){

								 $rating_value = 0;

								}

							 ?>

							<script type="text/javascript">

								  jQuery(document).ready(function(){

										jQuery(".basic ").jRating({

												bigStarsPath : '<?php echo get_template_directory_uri(); ?>/images/stars.png', // path of the icon stars.png

												smallStarsPath : '<?php echo get_template_directory_uri(); ?>/images/small.png', // path of the icon small.png

												phpPath : '<?php echo get_template_directory_uri()."/include/review_save.php?id=".$post->ID?>', // path of the php file jRating.php

												rateMax : 10,

												length : 5

										});

								  });

							</script>

							<?php px_enqueue_rating_style_script();?>

							<strong>User Rating: </strong>

							<div id="rating_saved">

								<div id="rating_saved">

									<h6 class="heading-color">

										<?php 

										echo px_user_rating();

										if ( get_post_meta(get_the_id(), "rating_vote_counter", true) > 0 ) {

											$rating_vote_counter = get_post_meta(get_the_id(), "rating_vote_counter", true);

										}

										else {

											$rating_vote_counter = 0;

										}

										echo " ( " . $rating_vote_counter . " Votes )";

										?>

									</h6>

								</div>

							</div>

							<div id="rating_loading" style="display:none"><i class='fa fa-spinner fa-spin fa-1x'></i></div>

							<div class="px-star-rating basic <?php if ( isset($_COOKIE["rating_vote_counter".$post->ID ]) ){echo "jDisabled"; }?>" data="<?php px_user_rating()*10;?>"><span style="width:<?php px_user_rating()*10;?>%"></span></div>

                            <?php 

								$rating = px_user_rating();

								echo px_criteria_check($rating*10);

							?>

                          </figcaption>

                        </figure>

                       <?php if(isset($px_xmlObject->reviews)) {?>

                            <ul>

                             <?php foreach($px_xmlObject->reviews as $reviews){?>

                              <li>

                                <?php if($reviews->var_pb_review_title <> ''){?><span><?php echo $reviews->var_pb_review_title;?></span><?php }?>

                                <?php if($reviews->var_pb_review_points <> ''){?><span><?php echo $reviews->var_pb_review_points;?></span><?php }?>

                                  <div class="progress-wrap">

                                    <div data-loadbar-text="<?php echo round($reviews->var_pb_review_points);?>%" data-loadbar="<?php echo round($reviews->var_pb_review_points*10);?>" class="progress-bar-charity">

                                      <div class="px-bgcolr"></div>

                                    </div>

                                  </div>

                              </li>

                              <?php }?>

                            </ul>

                        <?php }?>

                      </div>

                      <!-- Blog Rating Section End -->

                   </div>

        <?php	

	}

	





function px_generate_random_string($length = 3) {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $randomString = '';

    for ($i = 0; $i < $length; $i++) {

        $randomString .= $characters[rand(0, strlen($characters) - 1)];

    }

    return $randomString;

}



function px_subval_sort_array($a,$subkey) {

	foreach($a as $k=>$v) {

		$b[$k] = strtolower($v[$subkey]);

	}

	arsort($b);

	foreach($b as $key=>$val) {

		$c[] = $a[$key];

	}

	return $c;

}



// review criteria check

function cs_criteria_check($value) {

	global $px_theme_option;

	$html = '';

	for ( $j = 1; $j <= 10; $j++ ) {

		if ( $value >= $px_theme_option['review_criteria_'.$j.'_1'] and $value <= $px_theme_option['review_criteria_'.$j.'_2'] ) {

			$html = $px_theme_option['review_criteria_text_'.$j.''];

		}

	}

	return $html;

}

////////*******custom post type*****\\\\\\\\\\

add_action( 'init', 'register_cpt_league' );

function register_cpt_league() {

    $labels = array( 
        'name' => _x( 'Leagues', 'league' ),
        'singular_name' => _x( 'League', 'league' ),
        'add_new' => _x( 'Add New', 'league' ),
        'add_new_item' => _x( 'Add New League', 'league' ),
        'edit_item' => _x( 'Edit League', 'league' ),
        'new_item' => _x( 'New League', 'league' ),
        'view_item' => _x( 'View League', 'league' ),
        'search_items' => _x( 'Search Leagues', 'league' ),
        'not_found' => _x( 'No leagues found', 'league' ),
        'not_found_in_trash' => _x( 'No leagues found in Trash', 'league' ),
        'parent_item_colon' => _x( 'Parent League:', 'league' ),
        'menu_name' => _x( 'Leagues', 'league' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        
        'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies' => array( 'team-category' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'league', $args );
}

add_action( 'init', 'register_cpt_club' );

function register_cpt_club() {

    $labels = array( 
        'name' => _x( 'Clubs', 'club' ),
        'singular_name' => _x( 'Club', 'club' ),
        'add_new' => _x( 'Add New', 'club' ),
        'add_new_item' => _x( 'Add New Club', 'club' ),
        'edit_item' => _x( 'Edit Club', 'club' ),
        'new_item' => _x( 'New Club', 'club' ),
        'view_item' => _x( 'View Club', 'club' ),
        'search_items' => _x( 'Search Clubs', 'club' ),
        'not_found' => _x( 'No clubs found', 'club' ),
        'not_found_in_trash' => _x( 'No clubs found in Trash', 'club' ),
        'parent_item_colon' => _x( 'Parent Club:', 'club' ),
        'menu_name' => _x( 'Clubs', 'club' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'comments' ),
        'taxonomies' => array( 'team-category' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'club', $args );
}
add_action( 'init', 'register_cpt_country' );

function register_cpt_country() {

    $labels = array( 
        'name' => _x( 'Countries', 'country' ),
        'singular_name' => _x( 'Country', 'country' ),
        'add_new' => _x( 'Add New', 'country' ),
        'add_new_item' => _x( 'Add New Country', 'country' ),
        'edit_item' => _x( 'Edit Country', 'country' ),
        'new_item' => _x( 'New Country', 'country' ),
        'view_item' => _x( 'View Country', 'country' ),
        'search_items' => _x( 'Search Countries', 'country' ),
        'not_found' => _x( 'No countries found', 'country' ),
        'not_found_in_trash' => _x( 'No countries found in Trash', 'country' ),
        'parent_item_colon' => _x( 'Parent Country:', 'country' ),
        'menu_name' => _x( 'Countries', 'country' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies' => array( 'team-category' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'country', $args );
}

// Front End Functions END


// Get Trending Teams

function trending_team( $atts ) {

		$count=1;
		
		$args = array(
			'type'                     => 'player',
			'child_of'                 => 0,
			'parent'                   => '',
			'orderby'                  => 'count',
			'order'                    => 'DESC',
			'hide_empty'               => 1,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => '',
			'taxonomy'                 => 'team-category',
			'pad_counts'               => false 

		); 
		
		$categories = get_categories($args);
		echo '<div class="div-section">
		<div class="section-title"><i class="fa fa-futbol-o"></i><p>trending clubs</p></div>';
		foreach ($categories as $category) :
		   if($count<6){
			echo '<div class="trending_item col-md-2 col-xs-2"><p class="trending_count">'.$count++.'.</p>';
			$cat_meta = get_option( "team_$category->term_id");
			if(empty($cat_meta['icon'])){
				echo '<div class="style-post-img">';
					echo '<a href="'.get_site_url().'/?team-category='.$category->slug.'">';
						echo '<div class="default_ball"></div>';
					echo '</a>';
				echo '</div>';
			}
			else{
				echo '<div class="style-post-img">';
					echo '<a href="'.get_site_url().'/?team-category='.$category->slug.'">';
						echo '<img src="';
							echo $cat_meta['icon'] ? stripslashes(htmlspecialchars($cat_meta['icon'])) : ''; 
						echo '" alt="club">';
					echo '</a>';
				echo '</div>';
			}
			echo "<p class=trending_title><a href='".get_site_url()."/?team-category=".$category->slug."' >";
			echo $category->name;
			echo '</a>';
			echo '</p></div>';
			}
			else{
				if($count>10)
				{
				 $viewmore.= '<div class="trending_item col-md-2 col-xs-2"><span class="trending_count1">'.$count++.'.</span>'; 
				 $viewmore.= '<span class="trending_title1"><a href="';
				 $viewmore.= get_site_url().'/?team-category='.$category->slug;
				 $viewmore.= '">';
				 $viewmore.= $category->name;
				 $viewmore.= '</a></span></div>';
				 }
				else if($count>=6 && $count<=10){ 
				echo '<div class="trending_item col-md-2 col-xs-2"><span class="trending_count1">'.$count++.'.</span>'; 
				 echo '<span class="trending_title1"><a href="';
				 echo get_site_url().'/?team-category='.$category->slug;
				 echo '">';
					echo $category->name;
				 echo '</a></span></div>';
				}
			}
		  
		endforeach;
	echo '<div class="pad" id-"show-club-list">'.$viewmore.'</div>';
	echo '<div class="trending-more tm_club"><i class="fa fa-angle-down fdo_c"></i><i class="fa fa-angle-up fup_c"></i></div>';
	echo '</div>';

}
add_shortcode( 'trendingteam', 'trending_team' );

// Get Trending players

function trending_player( $atts ) {
	$atts = shortcode_atts(
		array(
			'postType' => 'player',
			'taxonomy' => 'team-category',
			'meta_key' => 'views',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
		), $atts, 'trending' );
		$trend_data='';
		$count=1;
		$viewmore='';
		
		$args = array( 'post_type' => 'player', 'posts_per_page' => -1, 'meta_key' => 'views','orderby' => 'meta_value_num');
		$loop = new WP_Query( $args );
		//print_r($loop);
		echo '<div class="col-lg-12 no-padding"><div class="div-section"><div class="section-title"><i class="fa fa-futbol-o"></i><p>trending players</p></div>';
		while ( $loop->have_posts() ) : $loop->the_post();
		  //echo '<div class="trending_item">';
		   if($count<6){
			echo '<div class="trending_item">';
			echo '<p class="trending_count">'.$count++.'.</p>';
			echo '<div class="style-post-img">';
				if(has_post_thumbnail()){
						the_post_thumbnail('thumbnail');
						}
			echo '</div>'; 
			echo '<p class="trending_title"><a href="';
			echo the_permalink();
			echo '">';
			the_title();
			echo '</a></p></div>';
			}
			else{
				if($count>10)
				{
				 $viewmore.= '<div class="trending_item"><span class="trending_count1">'.$count++.'.</span>'; 
				 $viewmore.= '<span class="trending_title1"><a href="';
				 $viewmore.= get_the_permalink();
				 $viewmore.= '">';
				 $viewmore.= get_the_title();
				 $viewmore.= '</a></span></div>';
				 }
				else if($count>=6 && $count<=10){ 
				echo '<div class="trending_item"><span class="trending_count1">'.$count++.'.</span>'; 
				 echo '<span class="trending_title1"><a href="';
				 echo the_permalink();
				 echo '">';
					the_title();
				 echo '</a></span></div>';
				}
			}
		 // echo '</div>';
		endwhile;
	//return $trend_data;
	echo '<div class="pad" id="show-player-list">'.$viewmore.'</div>';
	echo '<div class="trending-more"><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i></div>';
	echo '</div></div>';
	
	echo "
	<script>
		jQuery(document).ready(function() {
		jQuery('.fa-angle-up').hide();
		jQuery('.pad').hide();
    
		jQuery('.trending-more').click(function() {
        jQuery(this).next('.pad').slideToggle(500);
		
        if($(this).find('.fa-angle-down').attr('id') == 'yes') {
			jQuery('.pad').show();
			jQuery('.fa-angle-down').hide();
			jQuery('.fa-angle-up').show();
            $(this).find('.fa-angle-down').attr('id', '');
        } else {
			jQuery('.pad').hide();
			jQuery('.fa-angle-up').hide();
			jQuery('.fa-angle-down').show();
            $(this).find('.fa-angle-down').attr('id', 'yes');
        }
    });
	});
</script>
	";
	
}
add_shortcode( 'trendingplayer', 'trending_player' );

// Get Trending players

function trending_player_by_term( $atts ) {
		
		$post_id=$atts['post_id'];
		//$get_term=get_term_by( 'id',$post_id, 'player' );
		//$all_term = get_the_terms( $post_id, 'team-category' ); 
		$terms = wp_get_post_terms( $post_id, 'team-category');
		
		foreach ($terms as $key => $object) {
			$term_id=$object->term_id;
			//echo $term_id.'<br/>';
		}
		
		$count=1;
		
		$args=array('post_type'=>'player', 'posts_per_page' => 5,
				'tax_query' => array( 
							array(
								'taxonomy' => 'team-category',
								'field'    => 'id',
								'terms'    => $term_id,
								'include_children' => 0,
							),
						), 'meta_key' => 'views','orderby' => 'meta_value_num');
						

						
		
		$loop = new WP_Query( $args );
		echo '<div class="col-lg-12 col-md-12 col-xs-12 no-padding"><div class="div-section"><div class="section-title"><i class="fa fa-futbol-o"></i><p>trending players</p></div>';
		while ( $loop->have_posts() ) : $loop->the_post();
			echo '<div class="trending_item">';
			echo '<p class="trending_count">'.$count++.'.</p>';
			echo '<a href="';
				echo the_permalink();
			echo '">';
				if(has_post_thumbnail()){
					the_post_thumbnail('thumbnail');
				}
				else{
					echo '<div class="default_ball"></div>';
				}
			echo '</a>'; 
			echo '<p class="trending_title"><a href="';
			echo the_permalink();
			echo '">';
			the_title();
			echo '</a></p></div>';
		endwhile;
		if($count<=1){
			echo '<h3>No Player Found...</h3>';
		}
	echo '</div></div>';
	
}
add_shortcode( 'trending-player-by-term', 'trending_player_by_term' );


// Get Trending Teams

function spotlight_team($atts) {
		global $px_counter_node;
		$categories = get_categories('taxonomy=team-category&post_type=player');
		$slider_pagination = array();
		echo '<div class="element_size_50 spotlight-team">';
		echo '<div class="div-section" style="margin-top: 0px;height: 324px;"><div class="section-title"><i class="fa fa-lightbulb-o"></i><p>spotlight teams</p></div>';
		echo '<div class="our-team-sec team-vertical topn15">
			<div class="cycle-slideshow" 
								data-cycle-fx=fade
								data-cycle-timeout=250000
								data-cycle-auto-height=container
								data-cycle-slides="article"
								data-cycle-random=false
								data-cycle-pager="#banner-pager'.$px_counter_node.'"
								data-cycle-pager-template="">';
				$count1=0;
				foreach ($categories as $category) :
					$cat_meta = get_option( "team_$category->term_id");
					if($count1==5) break;
					echo '<article><a href="';
					echo get_site_url().'/?team-category='.$category->slug;
					 echo '"><figure>';
						 if(empty($cat_meta['icon'])){
							echo '<div class="default_ball_slider"></div>';
						}
						else{
							echo '<img src="';
								echo $cat_meta['icon'] ? stripslashes(htmlspecialchars($cat_meta['icon'])) : ''; 
							echo '" alt="club" style="height:285px;">';
						}
						 /*echo '<figcaption>'.$category->name.'</figcaption><h6><a>La Liga</a></h6>*/
						 echo '</figure></a></article>';
						 $slider_pagination[] = $category->name;
						 $count1++;
				endforeach;
		echo '</div></div>';
		//pagination
		$pagination_no = 0;
		echo '<div class="sliderpagination topn15 pxleft-team"><ul id="banner-pager'.$px_counter_node.'" class="banner-pager">';
		$count2=0;
		foreach ($categories as $category1) :
		    if($count2==5) break;
			$image_url_full = $cat_meta['icon'] ? stripslashes(htmlspecialchars($cat_meta['icon'])) : '';
			echo '<li><article><figure><img alt="" src="'.$image_url_full.'"></figure><div class="text"><h2>'.$category1->name.'</h2><h6><a>La Liga</a></h6></div></article></li>';
			$count2++;
		endforeach;	
		echo '</div>';
}
add_shortcode( 'spotteam','spotlight_team');

/* Get Forum Chat Content
Shortcode:
term="name" :
	which term's post need to show i:e club
child=0 :
	Show child posts or not i:e 0 = not include, 1 = include
totalposts=15 :
	show total number of posts per page
order=ASC :
	show posts by ASC or DESC
*/

function forum_chat($atts) {

		$forum_term=$atts['term'];
		$include_child=$atts['child'];
		$post_per_page=$atts['totalposts'];
		$post_order=$atts['order'];
		
		if(!($forum_term)) $forum_term='clubs';
		if(!($include_child)) $include_child=0;
		if(!($post_per_page)) $post_per_page=10;
		if(!($post_order)) $post_order='ASC';
			
		
		echo '<div class="col-md-12 no-padding">
			<div class="div-section">
				<div class="section-title"><i class="fa fa-shield"></i>
					<p>'.$forum_term.' chat</p>';
					
						$get_filter_val = $_POST['filter-post-'.$forum_term];
						//filter form starts
						echo '<form action="" method="post" class="filter-form" id="filter-form-'.$forum_term.'">
							<select id="filter-post-'.$forum_term.'" name="filter-post-'.$forum_term.'">
								';
								
								echo '<option ';
								if ($get_filter_val){
									if($get_filter_val=='1')
										echo "selected";
									else
										echo "";
								}
								echo ' value="1">Latest</option>';
								echo '<option ';
								if ($get_filter_val){
									if($get_filter_val=='2')
										echo "selected";
									else
										echo "";
								}
								echo ' value="2">Best</option>
							</select>
							</form>
							';
					
				echo '</div>';
				
				if (isset($_POST['filter-post'])){
					$filter_type = $_POST['filter-post'];
				
					if($filter_type==1){
						$args=array('post_type'=>'wps_forum_post',
						'tax_query' => array( 
									array(
										'taxonomy' => 'wps_forum',
										'field'    => 'slug',
										'terms'    => $forum_term,
										'include_children' => $include_child,
									),
								), 
							'orderby'=>$post_order,'posts_per_page'=>$post_per_page);
					}
					
					else if($filter_type==2){
						$args=array('post_type'=>'wps_forum_post',
						'tax_query' => array( 
									array(
										'taxonomy' => 'wps_forum',
										'field'    => 'slug',
										'terms'    => $forum_term,
										'include_children' => $include_child,
									),
								), 
								
								'meta_query' => array(
										array(
											'key'     => 'likes',
														'orderby' => 'meta_value_num',
														'order' => DESC
											,
										),
										array(
											'key'     => 'date',
														'orderby' => 'meta_value',
														'order' => DESC
										),
									),
								
							'orderby'=>$post_order,'posts_per_page'=>$post_per_page);
					}
				}
				else{
					$args=array('post_type'=>'wps_forum_post',
						'tax_query' => array( 
									array(
										'taxonomy' => 'wps_forum',
										'field'    => 'slug',
										'terms'    => $forum_term,
										'include_children' => $include_child,
									),
								), 
								
								
							'orderby'=>$post_order,'posts_per_page'=>$post_per_page);
				}
					
				$player=new WP_Query($args);

				while ($player->have_posts()) : $player->the_post();
				
				//get all terms of this post
				$terms = get_the_terms( get_the_ID(), 'wps_forum' );
				//get single term value of post
				foreach($terms as $term_single)
					$get_term_id = $term_single->term_id;
				//get parent post id
				$term__parent_post_id=get_term_meta($get_term_id,'parentpostid', true);
				
				echo '<div class="col-lg-6 col-md-6 club-chat-sec">
				<a href="';
					echo get_site_url().'/?p='.$term__parent_post_id.'&section=post-'.get_the_ID();
				echo '">';
				//if ( has_post_thumbnail($term__parent_post_id) ) {
					echo get_the_post_thumbnail($term__parent_post_id,array(34,40));
				//}
				//else{
					//echo '<div class="default_ball"></div>';
				//}
				echo '</a><a href="';
				//the_permalink();
				
				
				echo get_site_url().'/?p='.$term__parent_post_id.'&section=post-'.get_the_ID();
				
				echo '">';
				/*
				echo '<div class="chat-post-title">';
					echo substr(get_the_title(),0,45);
				echo '</div>';
				*/
				$post_time= get_the_modified_time('Y-m-j g:i:s');  //y-f-j g:i:s
				//echo $post_time;
				
				echo '</a>';
				
				$post_id_val = get_the_ID();
				$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
				$post_large_img = $large_image_url[0];
			
				echo "<div id='image-$post_id_val' class='fancybox image-popup'>
						<div class='col-lg-8 col-md-10 col-xs-12'>
							<img class='popup-post-image' src='$post_large_img' alt='img'/>
						</div>
					</div>
					";
				
				echo "<a href='#image-$post_id_val' class='fancybox'>";
					echo '<div class="post-thumbnail">';
						if ( has_post_thumbnail() ) {
							the_post_thumbnail(array(34,40));
						}
					echo '</div>';
				echo "</a>";
				
				//echo '<p>'.get_the_excerpt().'</p>';
				the_content();
				
				echo '<div class="icons post-time"><i class="fa fa fa-clock-o" style="display:block;"></i>'; echo get_time_difference($post_time); echo '</div>';
				
				echo '<span class="icons"><i class="fa fa-angle-right"></i></span><hr></div>';

				endwhile;
				wp_reset_postdata();
				
				echo '</div>
		</div>';
}
add_shortcode( 'forumchat','forum_chat');

// Get match Chat Content
function match_chat($atts) {
		$count=0;
		echo '<div class="col-md-12 no-padding">
			<div class="div-section match-chat">
				<div class="section-title"><i class="fa fa-shield"></i>
				<p>match chat</p></div>';

				$args=array('post_type'=>'wps_forum_post',
				'tax_query' => array( 
							array(
								'taxonomy' => 'wps_forum',
								'field'    => 'slug',
								'terms'    => 'matches',
								'include_children' => 0,
							),
						), 'orderby'=>'ASC','posts_per_page'=>'10');
						
				$match=new WP_Query($args);
				//get_field();
				while ($match->have_posts()) : $match->the_post();
				
				echo '<div class="col-lg-6 col-md-6 club-chat-sec">
				<div class="row">
				<div class="style-post-img">
					<a href="';
						the_permalink();
					echo '">';
					the_post_thumbnail(array(34,40));
				echo '</div>';
					/*Reteriving second image*/
					$image = get_field('featured_image');
					if( !empty($image) ):
				echo '<div class="style-post-img">';
					echo '<img class="featured_second" src="'.$image['url'].'" alt="'.$image['alt'].'" />';
				echo '</div>';
					endif;
				 
				echo '</a></div><a href="';
				the_permalink();
				echo '" class="match-chat-title">';
				
				$post_title = get_the_title();
				$post_title = substr($post_title,0,30);
				if((strlen($post_title))>28)
					$post_title = $post_title.'...';
				echo $post_title;
				
				if(!(get_field('alternative_title')))
					$league_name='N/A';
				else
					$league_name=get_field('alternative_title');
				echo '</a><div class="match-league">'.$league_name.'</div><div class="match-league">';
				$match_date=get_field('date');
				if ($match_date) { echo $match_date; } else { echo 'N/A'; }
				$post_time= get_the_modified_time('Y-m-j g:i:s');
				echo'</div><div class="icons"><span class="float-r">';echo get_time_difference($post_time);
				echo '</span><i class="fa fa fa-clock-o"></i></div></div>';
				endwhile;
				wp_reset_postdata();
				
				echo '</div>
		</div>';
}
add_shortcode( 'matchchat','match_chat');

// Get leagues list Content
function league_list($atts) {
				$exclude_id=$atts['lid'];
				$args=array('post_type'=>'league', 'orderby'=>'rand','posts_per_page'=>'4','lid' => $exclude_id);
				$league=new WP_Query($args);
				while ($league->have_posts()) : $league->the_post();
				$this_id=get_the_ID();
				
				if($this_id!=$exclude_id){
				echo '<div class="col-lg-5 col-md-5 col-xs-12">
				<a href="';
				the_permalink();
				echo '" class="col-lg-5 col-md-5 col-xs-5">';
				the_post_thumbnail(array(45,45));
				echo '</a><a href="';
				the_permalink();
				echo '"><div class="other-league-meta">';
				echo substr(the_title('', '', FALSE), 0, 15);
				echo '<br/>(';
				the_field('league_country');
				echo ')</div></a></div>';
				}
				endwhile;
				wp_reset_postdata();
}
add_shortcode( 'leagues','league_list');

// Get leagues clubs list
function league_clubs($atts) {
		$largs=array('post_type'=>'player','taxonomy' => 'team-category');
		$lcategories = get_categories($largs);
		$leaguelist=array();
		
		$leagues=get_field('league_name');
		if(empty($leagues))
		{
			echo '<h3>No Club Found!</h3>';
		}
		else{
			foreach($leagues as $leag){
				$leaguelist[]=$leag->term_id;
			}

			echo '<div class="div-section">';
			foreach ($lcategories as $lcategory) :
			if(in_array($lcategory->term_id,$leaguelist))
			{
			  echo '<div class="trending_item" style="width: auto;">';
				$cat_meta = get_option( "team_$lcategory->term_id");
				if(empty($cat_meta['icon'])){
					echo '<div class="default_ball"></div>';
				}
				else{
					echo '<img src="';
						echo $cat_meta['icon'] ? stripslashes(htmlspecialchars($cat_meta['icon'])) : ''; 
					echo '" alt="club">';
				}
				echo '<p class="trending_title"><a href="';
				echo get_site_url().'/?team-category='.$lcategory->slug;
				echo '">';
				echo $lcategory->name;
				echo '</a>';
				echo '</p>';
				
			  echo '</div>';
			 }
			endforeach;
			echo '</div>';
		}
}
add_shortcode( 'leagueclubs','league_clubs');

function custom_taxonomies_terms_links($id=null, $parent_id=null) {
	global $post;
	$postid=$id;//get_the_ID();
	// get post type taxonomies
	$taxonomies = get_object_taxonomies('wps_forum_post');
	
	foreach ($taxonomies as $taxonomy) {        
		// get the terms related to post
		$terms = get_the_terms( $postid, $taxonomy );
		if ( !empty( $terms ) ) {
			foreach ( $terms as $term ){
				if($parent_id)
					$out=$term->parent;
				else
					$out = $term->name;
			}
		}
	}
	return $out;
}

/*** league point table ***/

function league_point_table($atts) {
		$league_id=$atts['lid'];
		$args=array('post_type'=>'league', 'orderby'=>'rand','posts_per_page'=>'-1','lid' => $league_id);
		
		echo '<div id="external_filter_container_wrapper">
				<div id="external_filter_container"></div>
			</div>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	        <thead>
	          <tr>
	            <th>Ranks</th>
	            <!--<th class="club-search">Clubs</th>-->
				<th class="no-sort">P</th>
				<th class="no-sort">W</th>
				<th class="no-sort">D</th>
				<th class="no-sort">L</th>
				<th class="no-sort">F</th>
				<th class="no-sort">A</th>
				<th class="no-sort">&nbsp;</th>
				<th class="no-sort">W</th>
				<th class="no-sort">D</th>
				<th class="no-sort">L</th>
				<th class="no-sort">F</th>
				<th class="no-sort">A</th>
				<th class="no-sort">&nbsp;</th>
				<th class="no-sort">W</th>
				<th class="no-sort">D</th>
				<th class="no-sort">L</th>
				<th class="no-sort">F</th>
				<th class="no-sort">A</th>
	            <th>GD</th>
	            <th>PTS</th>
	          </tr>
	        </thead>
	        <tbody>
	          <tr class="odd gradeX">
	            <td>1</td>
	            <!--<td>1000</td>-->
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>&nbsp;</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>&nbsp;</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
	            <td>Yes</td>
	            <td>15</td>
	          </tr>
	          <tr class="gradeA">
	            <td>2</td>
	            <!--<td>55</td>-->
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>&nbsp;</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>&nbsp;</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
	            <td>Yes</td>
	            <td>21</td>
	          </tr>
	          <tr class="gradeA">
	            <td>3</td>
	            <!--<td>101</td>-->
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>&nbsp;</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>&nbsp;</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
	            <td>No</td>
	            <td>10</td>
	          </tr>
	        </tbody>
	   </table>
	   <div class="point-table-meta">
	   <span><b>P:</b>GAME PLAYED</span><span><b>W:</b>WINS</span><span><b>D:</b>DRAWS</span><span><b>L:</b>LOSSES</span>
	   <span><b>F:</b>GOAL FOR</span><span><b>A:</b>GOAL AGAINST</span><span><b>GD:</b>GOAL DIFFERENCE</span><span><b>PTS:</b>POINTS</span>
	   </div>';
		
		
}
add_shortcode( 'leaguept','league_point_table');

// Get league's most recent threads
function recentthread_chat($atts) {
		
		$league_id=$atts['lid'];
		
		$args=array('post_type'=>'wps_forum_post',
				'tax_query' => array( 
							array(
								'taxonomy' => 'wps_forum',
								'field'    => 'id',
								'terms'    => $league_id,
								'include_children' => 0,
							),
						), 'orderby'=>'ASC','posts_per_page'=>'25');
						
		$player=new WP_Query($args);
		
		/*slider starts*/
		while ($player->have_posts()) : $player->the_post();
			
			/*update views field from advance custom field data*/
			$p_id=get_the_ID();
			$get_val=get_field('views_count',$p_id);
			update_post_meta($p_id, 'views',$get_val);
		
			echo '<div class="col-lg-12 col-md-12 col-xs-12 club-chat-sec">
				<div class="col-lg-2 col-md-2 col-xs-2 no-padding thread-thumbnail"><a href="';
				the_permalink();
				echo '">';
				the_post_thumbnail(array(34,40));
				echo '</a></div><div class="col-lg-9 col-md-9 col-xs-9 thread-content no-padding"><a href="';
				the_permalink();
				echo '" class="thread-title">';
				echo substr(the_title('', '', FALSE), 0, 44);
				echo '</a>';
				$content = get_the_excerpt();
				echo '<p>'.substr($content, 0, 155).'<br/><span class="fs-author">'.get_the_author_meta('first_name').'</span></p></div>';
				echo '<div class="col-lg-12 col-md-12 col-xs-12 thread-bottom">';
				$post_time= get_the_modified_time('Y-m-j g:i:s');
				echo '<div class="col-lg-3 col-md-3 col-xs-3"><i class="fa fa-clock-o"></i>';echo get_time_difference($post_time); echo '</div>';
				echo '<div class="col-lg-3 col-md-2 col-xs-2"><i class="fa fa-eye"></i>';echo views_count(get_the_ID()); echo '</div>';
				echo '<div class="col-lg-3 col-md-3 col-xs-3">'; if(function_exists('wp_ulike')) wp_ulike('get'); echo '</div>';
				echo '<div class="col-lg-3 col-md-2 col-xs-2 shout-comment-icon" id="'.get_the_ID().'"><i class="fa fa-comment-o"></i>';comments_number('0','1','%'); echo '</div>';
		
				echo '<div class="col-lg-12 col-md-12 col-xs-12 shout-comment" id="postid-'.get_the_ID().'">';
					comments_template('comments.php',true);//get_shoutbox_comment(get_the_title());
				echo '</div>';
			echo '</div></div>';
			$slider_count++;
			endwhile;
			wp_reset_postdata();
		/*slider ends*/		
		
		if($slider_count==0){
			echo '<p class="no-match-found">No Post Found...</p>';
		}
		wp_reset_postdata();
}
add_shortcode( 'recentthread','recentthread_chat');

// Get league's most active threads
function activethread_chat($atts) {
		
		$count=0;
		$league_id=$atts['lid'];
		//$args=array('post_type'=>'wps_forum_post','taxonomy' => 'wps_forum', 'orderby' => 'meta_value_num', 'meta_key' => 'views','posts_per_page'=>'-1');
		
		$args=array('post_type'=>'wps_forum_post',
				'tax_query' => array( 
							array(
								'taxonomy' => 'wps_forum',
								'field'    => 'id',
								'terms'    => $league_id,
								'include_children' => 0,
							),
						), 'orderby' => 'meta_value_num', 'meta_key' => 'views','posts_per_page'=>'25');
		
		$player=new WP_Query($args);
		
					/*slider starts*/
					while ($player->have_posts()) : $player->the_post();
						
						/*update views field from advance custom field data*/
						$p_id=get_the_ID();
						$get_val=get_field('views_count',$p_id);
						update_post_meta($p_id, 'views',$get_val);
					
						echo '<div class="col-lg-12 col-md-12 col-xs-12 club-chat-sec">
						<div class="col-lg-2 col-md-2 col-xs-2 no-padding thread-thumbnail"><a href="';
						the_permalink();
						echo '">';
						the_post_thumbnail(array(34,40));
						echo '</a></div><div class="col-lg-9 col-md-9 col-xs-12 thread-content no-padding"><a href="';
						the_permalink();
						echo '" class="thread-title">';
						echo substr(the_title('', '', FALSE), 0, 44);
						echo '</a>';
						$content = get_the_excerpt();
						echo '<p>'.substr($content, 0, 155).'<br/><span class="fs-author">'.get_the_author_meta('first_name').'</span></p></div>';
						echo '<div class="col-lg-12 col-md-12 col-xs-12 thread-bottom">';
						$post_time= get_the_modified_time('Y-m-j g:i:s');
						echo '<div class="col-lg-3 col-md-3 col-xs-3"><i class="fa fa-clock-o"></i>';echo get_time_difference($post_time); echo '</div>';
						echo '<div class="col-lg-3 col-md-2 col-xs-2"><i class="fa fa-eye"></i>';echo views_count(get_the_ID()); echo '</div>';
						echo '<div class="col-lg-3 col-md-3 col-xs-3">'; if(function_exists('wp_ulike')) wp_ulike('get'); echo '</div>';
						echo '<div class="col-lg-3 col-md-2 col-xs-2 shout-comment-icon-active" id="'.get_the_ID().'"><i class="fa fa-comment-o"></i>';comments_number('0','1','%'); echo '</div>';
				
						echo '<div class="col-lg-12 col-md-12 col-xs-12 shout-comment" id="rpostid-'.get_the_ID().'">';
							comments_template('comments.php',true);//get_shoutbox_comment(get_the_title());
						echo '</div>';
						echo '</div></div>';
						
						$slider_count++;
						endwhile;
						wp_reset_postdata();
						
						if($slider_count==0){
							echo '<p class="no-match-found">No Post Found...</p>';
						}
						/*slider ends*/		
}

add_shortcode( 'activethread','activethread_chat');

function match_box($atts){
	$post_id = $atts['pid'];
	$get_term = array();
	$matches_id = array();
	
	//get terms of post
	$all_terms = wp_get_post_terms( $post_id,'team-category');
	//var_dump($all_terms);
	foreach($all_terms as $terms){
		$get_term[] = $terms->term_id;
	}

	global $px_event_meta;
	
	$events_args = array('post_type' => 'events','posts_per_page' => -1);

	$myposts = get_posts( $events_args );

	foreach ( $myposts as $post ) : setup_postdata( $post );
		$post_xml = get_post_meta($post->ID, "px_event_meta", true);	
		if ( $post_xml <> "" )
			$px_event_meta = new SimpleXMLElement($post_xml);
		$team1 = px_get_term_object($px_event_meta->var_pb_event_team1);
		$team2 = px_get_term_object($px_event_meta->var_pb_event_team2);
		$team_1_id = $team1->term_id;
		$team_2_id = $team2->term_id;
		//check if any of team exist store match id in matches_id
		if ( in_array($team_1_id, $get_term) || in_array($team_2_id, $get_term)) {
			$matches_id[] = $post->ID;
		}
	endforeach; 
	wp_reset_postdata();
	
	if(!$matches_id){
		echo '<div class="div-section match-chat fixture-widget">
				<div class="section-title"><i class="fa fa-shield"></i>
				<p>match box</p></div>';
				echo '<div class="no-match-found">Sorry No Match Found...!</div>';
		echo '</div>';
	}
	else{
	
	//show all matches of requested post_id
	$args=array('post_type'=>'events','post__in'=>$matches_id, 'orderby'=>'ASC','posts_per_page'=>'6');
	$match=new WP_Query($args);
		
	echo '<div class="div-section match-chat fixture-widget">
			<div class="section-title"><i class="fa fa-shield"></i>
			<p>match box</p></div>';
				while ($match->have_posts()) : $match->the_post();
				global $px_event_meta;
				
				$post_xml = get_post_meta(get_the_ID(), "px_event_meta", true);	
				if ( $post_xml <> "" )
					$px_event_meta = new SimpleXMLElement($post_xml);
				$team1_row = px_get_term_object($px_event_meta->var_pb_event_team1);
				$team2_row = px_get_term_object($px_event_meta->var_pb_event_team2);

				echo '<div class="col-lg-6 col-md-6 col-xs-12 no-pad-right">
						<div class="match-board-inner">
							<div class="row result-row">
								<div class="style-post-img col-lg-4 col-md-4 col-xs-12">
									<a href="';
										the_permalink();
									echo '">';
									
										$team_img1 = px_team_data_front($team1_row->term_id);
										if($team_img1[0] <> '')
										{
											echo '<img alt="" src="'.$team_img1[0].'">';
										}
										else { echo '<div class="default_ball"></div>'; }
										
										echo '</div>';
										
										echo '<h1 class="match-result col-lg-2 col-md-2 col-xs-12">'.$px_event_meta->event_score.'</h1>';
											/*Reteriving second image*/
										echo '<div class="style-post-img col-lg-4 col-md-4 col-xs-12">';	
												
												$team_img2 = px_team_data_front($team2_row->term_id);

												if($team_img2[0] <> ''){
													echo '<img alt="" src="'.$team_img2[0].'">';
												 }
												else { echo '<div class="default_ball"></div>'; }
										echo '</div>';
									echo '</a>
								</div>';
								
								echo '<a href="';
								the_permalink();
								echo '" class="match-chat-title">';
									$post_title = $team1_row->name.' vs '.$team2_row->name;
									$post_title = substr($post_title,0,30);
									if((strlen($post_title))>28)
										$post_title = $post_title.'...';
										echo $post_title;
								echo '</a>';
								echo '<div class="match-league">';
								$match_type = get_field('match_type');
								if(!$match_type)
									echo '<span class="event-type">N/A</span>';
								else
								echo '<span class="event-type">'.$match_type->name.'</span>';

								$current_date = date('m/d/yy');
								$px_event_from_date = get_post_meta(get_the_ID(), "px_event_from_date", true);
								if($current_date > $px_event_from_date)
									echo '<div class="match-on">match in progress</div>';
								else
									echo '<div class="match-off">match is ended</div>';
									
								echo '</div>
						</div>
					</div>';
				endwhile;
				wp_reset_postdata();
		echo '</div>';
	}
	
}
add_shortcode( 'matchbox','match_box');


// Get All Countries
function get_all_countries($atts) {
		
		$getAlphabets=array();
		
		//c = content
		$country_c1= '<div class="col-md-12 no-padding">
			<div class="div-section countries-list">
				<div class="section-title">';
		$paginate='';
		$country_c2='</div>';
				$args=array('post_type'=>'country', 'orderby'=> 'title', 'order' => 'ASC','posts_per_page'=>'-1');
				$match=new WP_Query($args);
				
				foreach($match as $alphabet){
					
				}
				
				$charMatch='';
				while ($match->have_posts()) : $match->the_post();
				
				$firstChar = substr(get_the_title(),0, 1); 
				if($firstChar!=$charMatch)
				{
					$charMatch=$firstChar;
					//echo '<p id="'.$charMatch.'">&nbsp;</p><div class="clear"></div>';
					$country_c2.= '<div id='.$charMatch.' class="col-lg-12">&nbsp;</div>';
					$country_c2.= '<div class="col-lg-12"><p class="country-separate">'.$charMatch.'</p></div>';
					$paginate.='<a href="#'.$charMatch.'">'.$charMatch.'</a>';
				}
				
				$country_c2.= '<div class="col-lg-3 col-md-3 col-xs-6 country-sec">
					
					<div class="col-lg-4 col-md-4 col-xs-6">
						<a href="';
					$country_c2.=	get_the_permalink();
					$country_c2.= '">';
					$country_c2.=	get_the_post_thumbnail(get_the_ID(),array(34,40));
					$country_c2.= '</a></div>';
				
					//Country Title
					$country_c2.= '<div class="col-lg-8 col-md-8 col-xs-6"><a href="';
					$country_c2.=	get_the_permalink();
						$country_c2.= '">';
						$country_c2.= get_the_title();
						$country_c2.= '</a>';
					$country_c2.= '</div>';
				$country_c2.= '</div>';

				endwhile;
				wp_reset_postdata();
			$country_c2.= '<div class="section-title paginate-bottom">'.$paginate.'</div>';
			$country_c2.= '</div>
		</div>';
		
		$country_content=$country_c1.$paginate.$country_c2;
		
		return $country_content;
}
add_shortcode( 'countries-list','get_all_countries');

///////Get follow activities\\\\\\\	
	
function follow_post($atts){

	global $wpw_fp_model, $wpw_fp_options;
	//model class
	$model = $wpw_fp_model;
	$user_id = get_current_user_id();
	
	$argsdata = array(
					'author' 			=>	$user_id,
					'posts_per_page' 	=>	$perpage,
					'paged'				=>	$_POST['paging']
				);
				
				
	$followposts = $model->wpw_fp_get_follow_post_users_data( $argsdata );
			
	//print_r($followposts);
		foreach ( $followposts as $followpost ) {
			
			$post_parent = isset( $followpost['post_parent'] ) && !empty( $followpost['post_parent'] ) ? $followpost['post_parent'] : '';
			
			if( !empty( $post_parent ) ) { // Check post parent is not empty
				
				$posts = get_post( $post_parent );
				//print_r($posts);
				// Get Follow Post Name
				$post_name = isset( $posts->post_title ) ? $posts->post_title : '';
				
				//Get Post Id
				$page_id=$posts->ID;

				// Get Follow Post Type
				$posttype = $posts->post_type;
				
				// Get Follow Post Url
				$post_url = $posts->guid;
				
				// Get Follow Post Type Name
				$post_type_name = !empty( $posttype ) && isset( $post_types[$posttype]->labels->singular_name ) ? $post_types[$posttype]->labels->singular_name : '';
				
				// Get Follow Date
				$followdate = $model->wpw_fp_get_date_format( $followpost['post_date'] );
				$slider_count=0;
				//check if it's forum
				if(substr($posts->post_content, 0, 10)=='[wps-forum')
				{
					
					//get all leagues
					
					if (preg_match('/"([^"]+)"/', $posts->post_content, $getslug)) {
						$slugvalue=$getslug[1];   
					} 
					
					$count=0;
					$args=array('post_type'=>'wps_forum_post','taxonomy' => 'wps_forum', 'orderby'=>'ASC','posts_per_page'=>'-1');
					$match=new WP_Query($args);
					
					echo '<div class="col-lg-6 col-md-12">';
						echo '<div class="div-section no-padding fs-sec-slider">
								<div class="section-title"><i class="fa fa-group"></i><p>'.ucfirst($slugvalue).'</p></div>';
					/*slider starts*/
					echo '<ul class="threadslider"><li>';
					//$slider_count=0;
					while ($match->have_posts()) : $match->the_post();
					
					//print_r($match);
					$termv=custom_taxonomies_terms_links(get_the_ID());
					//echo $termv.',';
					if($termv==ucfirst($slugvalue)){
						
						echo '<div class="col-lg-12 col-md-12 club-chat-sec">
						<div class="col-lg-2 col-md-1 no-padding thread-thumbnail"><a href="';
						the_permalink();
						echo '">';
						the_post_thumbnail(array(34,40));
						echo '</a></div><div class="col-lg-9 col-md-10 thread-content no-padding"><a href="';
						the_permalink();
						echo '" class="thread-title">';
						echo substr(the_title('', '', FALSE), 0, 44);
						echo '</a>';
						$content = get_the_excerpt();
						echo '<p>'.substr($content, 0, 155).'<br/><span class="fs-author">'.get_the_author_meta('first_name').'</span></p></div>';
						echo '<div class="col-lg-12 thread-bottom">';
						$post_time= get_the_modified_time('Y-m-j g:i:s');
						echo '<div class="col-lg-3 col-md-2 col-xs-2"><i class="fa fa-clock-o"></i>';echo get_time_difference($post_time); echo '</div>';
						echo '<div class="col-lg-3 col-md-2 col-xs-2"><i class="fa fa-eye"></i>';echo views_count(get_the_ID()); echo '</div>';
						echo '<div class="col-lg-3 col-md-2 col-xs-2"><i class="fa fa-thumbs-o-up"></i>';the_modified_time('i'); echo '</div>';
						echo '<div class="col-lg-3 col-md-2 col-xs-2"><i class="fa fa-comment-o"></i>';comments_number('0','1','%'); echo '</div>';
						echo '</div></div>';
						
						$slider_count++;
						if($slider_count%5==0) 	echo '</li><li>'; 
						
					}
						if($slider_count>=25)	return;

						
						endwhile;
						wp_reset_postdata();
						if($slider_count==0){
							echo '<li style="display: block !important;"><h3>No Thread Found.</h3></li>';
						}
						echo '</ul>';
						/*slider ends*/
						echo '</div></div>';
				}
			} 
		}	 
		echo '</tbody>';
	}
	
add_shortcode( 'followpost','follow_post');

function followed_users($atts){
	global $wpw_fp_model, $wpw_fp_options;
	//model class
	$model = $wpw_fp_model;
	$user_id = get_current_user_id();
	
	$argsdata = array(
					'author' 			=>	$user_id,
					'posts_per_page' 	=>	$perpage,
					'paged'				=>	$_POST['paging']
				);
				
				
	$followposts = $model->wpw_fp_get_follow_author_users_data( $argsdata );
			
	//print_r($followposts);
		foreach ( $followposts as $followpost ) {
			//Followers=$post_parent
			$post_parent = isset( $followpost['post_parent'] ) && !empty( $followpost['post_parent'] ) ? $followpost['post_parent'] : '';
			if( !empty( $post_parent ) ) { // Check post parent is not empty
				
				$follow_user = get_userdata($post_parent);
				echo '<p>'.$follow_user->user_login.'</p>';
			} 
		}	 
}
	
add_shortcode( 'follower-users','followed_users');

///Get Post Time\\\\\

function get_time_difference( $time ) {
	
    $current_time = new DateTime( current_time( 'mysql' ) );
    $previous_time = new DateTime( $time );
    $difference = $current_time->diff( $previous_time );
    $timestamp = '';
 
    if ( 0 < $difference->y ) {
        /**
         * If we've passed one year, let's show the full
         * date.
        */
         $timestamp = get_the_date( 'F j, Y' );
    } else if ( 12 >= $difference->m && 1 <= $difference->m ) {
        /**
         * If we've made it here, we know that we have not
         * yet passed one year, but have made it passed one
         * month. As such, let's remove the year from the 
         * output, but keep the date style format.
        */
        $timestamp .= get_the_date( 'F j' );
    } else if ( 0 < $difference->d ) {
        /**
         * If we've made it here, we know that we have not
         * yet passed one month, but have made it passed one
         * day. As such, let's show just the number of days
         * that have passed.
        */
        $timestamp .= sprintf( translate_nooped_plural( _n_noop( '%s d', '%s d' ), $difference->days ), $difference->days );
    } else if ( 0 < $difference->h ) {
        /**
         * If we've made it here, we know that we have not
         * yet passed one day, but have made it passed one
         * hour. As such, let's show just the number of hours
         * that have passed.
        */
        $timestamp .= sprintf( translate_nooped_plural( _n_noop( '%s h', '%s h', 'listed' ), $difference->h, 'listed' ), $difference->h );
    } else if ( 0 < $difference->i ) {
        /**
         * If we've made it here, we know that we have not
         * yet passed one hour, but have made it passed one
         * minute. As such, let's show just the number of
         * minutes that have passed.
        */
        $timestamp .= sprintf( translate_nooped_plural( _n_noop( '%s m', '%s m', 'listed' ), $difference->i, 'listed' ), $difference->i );
    } else {
        /**
         * If we've made it here, that this post is fresh
         * off the press. Let's show how fresh it is.
        */
        $timestamp = __( 'Just Now', 'listed' );
    }
 
    return $timestamp;
}

// function to display number of posts.

function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}

// function to count views.
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
function posts_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
}

function posts_custom_column_views($column_name, $id){
	if($column_name === 'post_views'){
        echo getPostViews(get_the_ID());
    }
}
	                   	
function views_count($id){
	$value=get_field( "views_count",$id);
	if($value==0){
		return 0;
	}
	else{
		return the_field( "views_count",$id);
	}
}

function ad_banner(){
	$advertisingwidgets = wp_get_sidebars_widgets();
			if(isset($advertisingwidgets['header-advertisement-widget']) && count($advertisingwidgets['header-advertisement-widget'])>0)
			{                       
				echo '<div class="col-lg-12 no-padding"><div class="rightheader">';            	
					if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-advertisement-widget')) : endif;                         
				echo '</div></div><div class="clear"></div>';  
			}
}

add_shortcode('adbanner','ad_banner');

function get_country_code($c_name){
	$countries = array( 'AF'=>'AFGHANISTAN', 'AL'=>'ALBANIA', 'DZ'=>'ALGERIA', 'AS'=>'AMERICAN SAMOA', 'AD'=>'ANDORRA', 'AO'=>'ANGOLA', 'AI'=>'ANGUILLA', 'AQ'=>'ANTARCTICA', 'AG'=>'ANTIGUA AND BARBUDA', 'AR'=>'ARGENTINA', 'AM'=>'ARMENIA', 'AW'=>'ARUBA', 'AU'=>'AUSTRALIA', 'AT'=>'AUSTRIA', 'AZ'=>'AZERBAIJAN', 'BS'=>'BAHAMAS', 'BH'=>'BAHRAIN', 'BD'=>'BANGLADESH', 'BB'=>'BARBADOS', 'BY'=>'BELARUS', 'BE'=>'BELGIUM', 'BZ'=>'BELIZE', 'BJ'=>'BENIN', 'BM'=>'BERMUDA', 'BT'=>'BHUTAN', 'BO'=>'BOLIVIA', 'BA'=>'BOSNIA AND HERZEGOVINA', 'BW'=>'BOTSWANA', 'BV'=>'BOUVET ISLAND', 'BR'=>'BRAZIL', 'IO'=>'BRITISH INDIAN OCEAN TERRITORY', 'BN'=>'BRUNEI DARUSSALAM', 'BG'=>'BULGARIA', 'BF'=>'BURKINA FASO', 'BI'=>'BURUNDI', 'KH'=>'CAMBODIA', 'CM'=>'CAMEROON', 'CA'=>'CANADA', 'CV'=>'CAPE VERDE', 'KY'=>'CAYMAN ISLANDS', 'CF'=>'CENTRAL AFRICAN REPUBLIC', 'TD'=>'CHAD', 'CL'=>'CHILE', 'CN'=>'CHINA', 'CX'=>'CHRISTMAS ISLAND', 'CC'=>'COCOS (KEELING) ISLANDS', 'CO'=>'COLOMBIA', 'KM'=>'COMOROS', 'CG'=>'CONGO', 'CD'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'CK'=>'COOK ISLANDS', 'CR'=>'COSTA RICA', 'CI'=>'COTE D IVOIRE', 'HR'=>'CROATIA', 'CU'=>'CUBA', 'CY'=>'CYPRUS', 'CZ'=>'CZECH REPUBLIC', 'DK'=>'DENMARK', 'DJ'=>'DJIBOUTI', 'DM'=>'DOMINICA', 'DO'=>'DOMINICAN REPUBLIC', 'TP'=>'EAST TIMOR', 'EC'=>'ECUADOR', 'EG'=>'EGYPT', 'SV'=>'EL SALVADOR', 'GQ'=>'EQUATORIAL GUINEA', 'ER'=>'ERITREA', 'EE'=>'ESTONIA', 'ET'=>'ETHIOPIA', 'FK'=>'FALKLAND ISLANDS (MALVINAS)', 'FO'=>'FAROE ISLANDS', 'FJ'=>'FIJI', 'FI'=>'FINLAND', 'FR'=>'FRANCE', 'GF'=>'FRENCH GUIANA', 'PF'=>'FRENCH POLYNESIA', 'TF'=>'FRENCH SOUTHERN TERRITORIES', 'GA'=>'GABON', 'GM'=>'GAMBIA', 'GE'=>'GEORGIA', 'DE'=>'GERMANY', 'GH'=>'GHANA', 'GI'=>'GIBRALTAR', 'GR'=>'GREECE', 'GL'=>'GREENLAND', 'GD'=>'GRENADA', 'GP'=>'GUADELOUPE', 'GU'=>'GUAM', 'GT'=>'GUATEMALA', 'GN'=>'GUINEA', 'GW'=>'GUINEA-BISSAU', 'GY'=>'GUYANA', 'HT'=>'HAITI', 'HM'=>'HEARD ISLAND AND MCDONALD ISLANDS', 'VA'=>'HOLY SEE (VATICAN CITY STATE)', 'HN'=>'HONDURAS', 'HK'=>'HONG KONG', 'HU'=>'HUNGARY', 'IS'=>'ICELAND', 'IN'=>'INDIA', 'ID'=>'INDONESIA', 'IR'=>'IRAN, ISLAMIC REPUBLIC OF', 'IQ'=>'IRAQ', 'IE'=>'IRELAND', 'IL'=>'ISRAEL', 'IT'=>'ITALY', 'JM'=>'JAMAICA', 'JP'=>'JAPAN', 'JO'=>'JORDAN', 'KZ'=>'KAZAKSTAN', 'KE'=>'KENYA', 'KI'=>'KIRIBATI', 'KP'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF', 'KR'=>'KOREA REPUBLIC OF', 'KW'=>'KUWAIT', 'KG'=>'KYRGYZSTAN', 'LA'=>'LAO PEOPLES DEMOCRATIC REPUBLIC', 'LV'=>'LATVIA', 'LB'=>'LEBANON', 'LS'=>'LESOTHO', 'LR'=>'LIBERIA', 'LY'=>'LIBYAN ARAB JAMAHIRIYA', 'LI'=>'LIECHTENSTEIN', 'LT'=>'LITHUANIA', 'LU'=>'LUXEMBOURG', 'MO'=>'MACAU', 'MK'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'MG'=>'MADAGASCAR', 'MW'=>'MALAWI', 'MY'=>'MALAYSIA', 'MV'=>'MALDIVES', 'ML'=>'MALI', 'MT'=>'MALTA', 'MH'=>'MARSHALL ISLANDS', 'MQ'=>'MARTINIQUE', 'MR'=>'MAURITANIA', 'MU'=>'MAURITIUS', 'YT'=>'MAYOTTE', 'MX'=>'MEXICO', 'FM'=>'MICRONESIA, FEDERATED STATES OF', 'MD'=>'MOLDOVA, REPUBLIC OF', 'MC'=>'MONACO', 'MN'=>'MONGOLIA', 'MS'=>'MONTSERRAT', 'MA'=>'MOROCCO', 'MZ'=>'MOZAMBIQUE', 'MM'=>'MYANMAR', 'NA'=>'NAMIBIA', 'NR'=>'NAURU', 'NP'=>'NEPAL', 'NL'=>'NETHERLANDS', 'AN'=>'NETHERLANDS ANTILLES', 'NC'=>'NEW CALEDONIA', 'NZ'=>'NEW ZEALAND', 'NI'=>'NICARAGUA', 'NE'=>'NIGER', 'NG'=>'NIGERIA', 'NU'=>'NIUE', 'NF'=>'NORFOLK ISLAND', 'MP'=>'NORTHERN MARIANA ISLANDS', 'NO'=>'NORWAY', 'OM'=>'OMAN', 'PK'=>'PAKISTAN', 'PW'=>'PALAU', 'PS'=>'PALESTINIAN TERRITORY, OCCUPIED', 'PA'=>'PANAMA', 'PG'=>'PAPUA NEW GUINEA', 'PY'=>'PARAGUAY', 'PE'=>'PERU', 'PH'=>'PHILIPPINES', 'PN'=>'PITCAIRN', 'PL'=>'POLAND', 'PT'=>'PORTUGAL', 'PR'=>'PUERTO RICO', 'QA'=>'QATAR', 'RE'=>'REUNION', 'RO'=>'ROMANIA', 'RU'=>'RUSSIAN FEDERATION', 'RW'=>'RWANDA', 'SH'=>'SAINT HELENA', 'KN'=>'SAINT KITTS AND NEVIS', 'LC'=>'SAINT LUCIA', 'PM'=>'SAINT PIERRE AND MIQUELON', 'VC'=>'SAINT VINCENT AND THE GRENADINES', 'WS'=>'SAMOA', 'SM'=>'SAN MARINO', 'ST'=>'SAO TOME AND PRINCIPE', 'SA'=>'SAUDI ARABIA', 'SN'=>'SENEGAL', 'SC'=>'SEYCHELLES', 'SL'=>'SIERRA LEONE', 'SG'=>'SINGAPORE', 'SK'=>'SLOVAKIA', 'SI'=>'SLOVENIA', 'SB'=>'SOLOMON ISLANDS', 'SO'=>'SOMALIA', 'ZA'=>'SOUTH AFRICA', 'GS'=>'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'ES'=>'SPAIN', 'LK'=>'SRI LANKA', 'SD'=>'SUDAN', 'SR'=>'SURINAME', 'SJ'=>'SVALBARD AND JAN MAYEN', 'SZ'=>'SWAZILAND', 'SE'=>'SWEDEN', 'CH'=>'SWITZERLAND', 'SY'=>'SYRIAN ARAB REPUBLIC', 'TW'=>'TAIWAN, PROVINCE OF CHINA', 'TJ'=>'TAJIKISTAN', 'TZ'=>'TANZANIA, UNITED REPUBLIC OF', 'TH'=>'THAILAND', 'TG'=>'TOGO', 'TK'=>'TOKELAU', 'TO'=>'TONGA', 'TT'=>'TRINIDAD AND TOBAGO', 'TN'=>'TUNISIA', 'TR'=>'TURKEY', 'TM'=>'TURKMENISTAN', 'TC'=>'TURKS AND CAICOS ISLANDS', 'TV'=>'TUVALU', 'UG'=>'UGANDA', 'UA'=>'UKRAINE', 'AE'=>'UNITED ARAB EMIRATES', 'GB'=>'UNITED KINGDOM', 'US'=>'UNITED STATES', 'UM'=>'UNITED STATES MINOR OUTLYING ISLANDS', 'UY'=>'URUGUAY', 'UZ'=>'UZBEKISTAN', 'VU'=>'VANUATU', 'VE'=>'VENEZUELA', 'VN'=>'VIET NAM', 'VG'=>'VIRGIN ISLANDS, BRITISH', 'VI'=>'VIRGIN ISLANDS, U.S.', 'WF'=>'WALLIS AND FUTUNA', 'EH'=>'WESTERN SAHARA', 'YE'=>'YEMEN', 'YU'=>'YUGOSLAVIA', 'ZM'=>'ZAMBIA', 'ZW'=>'ZIMBABWE', );

	while ($country = current($countries)){
		if(strtoupper($c_name)==$country)
			echo strtolower(key($countries));
		next($countries);
	}
}

/*Favourite Posts Shortcode*/
//$fav_post_ids is array containg all fav post ids

//shortcode account=1 means template of account else default profile template i:e account=0

function get_fav_posts($atts){
	//fetching from wp-favorite-posts.php
	$user_account=$atts['account'];
	$p_type=$atts['posttype'];
	
	//set default value for template
	if($user_account==null)
		$user_account=0;
	
	$favorite_post_ids = wpfp_get_users_favorites();

	if(empty($favorite_post_ids))
		echo '<p class="not-found">No Favourite Entry Found.</p>';
	else{
		if(!($p_type))
			$p_type="player";
		$args =array( 'post_type' => $p_type,'post__in' => $favorite_post_ids,'posts_per_page' => 10 );
		$the_query = new WP_Query( $args );
		
		if($user_account==0){
			if($p_type == 'player'){
			/*fav players posts starts*/
			echo '<div id="shoutbox-content" class="posttype-'.$p_type.'"></div>';
				echo '<div class="col-lg-12 see-all-style see-all-'.$p_type.'" id="shout-more'.$p_type.'"><i class="fa fa-angle-down view_more"></i></div>';
				//echo '<div class="loader-shoutbox"></div>';
			echo '</div>'; //end inner div
			}
			if($p_type == 'country'){
			/*fav players posts starts*/
			echo '<div id="shoutbox-content-country" class="posttype-'.$p_type.'"></div>';
				echo '<div class="col-lg-12 see-all-style see-all-'.$p_type.'" id="shout-more'.$p_type.'"><i class="fa fa-angle-down view_more"></i></div>';
				//echo '<div class="loader-shoutbox"></div>';
			echo '</div>'; //end inner div
			}
		}
		else{
		
		if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
				echo "<div class='col-lg-2 col-md-3 col-xs-6 fav-post-item col-centered'><div class='fav-head'>";
					echo '<div class="style-post-img">';
						echo "<a href='".get_permalink()."' title='". get_the_title() ."'>";
							the_post_thumbnail( 'thumbnail' );
						echo "</a> ";
					echo '</div>';
					echo "<a class='fav-title' href='".get_permalink()."' title='". get_the_title() ."'>" . get_the_title() . "</a> ";
					echo '<p class="footy-btn"><i class="fa fa-close"></i><span>';
						if (function_exists('wpfp_link')) 
							{ wpfp_link(); }
					echo '</span></p>';
				echo "</div></div>";
				endwhile;
			else :
				echo '<p class="not-found">No Favourite Entry Found.</p>';
			endif;
		
		}
	}
	
}

add_shortcode('getfavposts','get_fav_posts');


/*Post you are following Shortcode*/
//$fav_post_ids is array containg all fav post ids


function get_follow_posts($atts){
	
	$follow_post_ids = array();
	//get follow post ids
	
	global $wpw_fp_model, $wpw_fp_options;
	//model class
	$model = $wpw_fp_model;
	$user_id = get_current_user_id();
	
	$argsdata = array(
					'author' 			=>	$user_id,
					'posts_per_page' 	=>	$perpage,
					'paged'				=>	$_POST['paging']
				);
				
	$followposts = $model->wpw_fp_get_follow_post_users_data( $argsdata );
	//print_r($followposts);
	foreach($followposts as $followpost){
		$post_parent = isset( $followpost['post_parent'] ) && !empty( $followpost['post_parent'] ) ? $followpost['post_parent'] : '';
			
			if( !empty( $post_parent ) ) { // Check post parent is not empty
				
				$posts = get_post( $post_parent );
				//print_r($posts);
				//echo $posts->ID;
				$follow_post_ids[] = $posts->ID;
			}
	}
	
	$p_type=$atts['posttype'];
	
	if(empty($follow_post_ids))
		echo '<p class="not-found">No Favourite Entry Found.</p>';
	else{
		if(!($p_type))
			$p_type="player";
		$args =array( 'post_type' => $p_type,'post__in' => $follow_post_ids,'posts_per_page' => 10 );
		$the_query = new WP_Query( $args );
		
		if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
				echo "<div class='col-lg-2 col-md-3 col-xs-6 follow-post-item col-centered'><div class='fav-head'>";
					echo '<div class="style-post-img">';
						echo "<a href='".get_permalink()."' title='". get_the_title() ."'>";
							the_post_thumbnail( 'thumbnail' );
						echo "</a> ";
					echo '</div>';
					echo "<a class='fav-title' href='".get_permalink()."' title='". get_the_title() ."'>" . get_the_title() . "</a> ";
					//follow button
					echo do_shortcode('[wpw_follow_me disablecount="true" followtext="FOLLOW" followingtext="UNFOLLOW" unfollowtext="UNFOLLOW"][/wpw_follow_me]');
				echo "</div></div>";
				endwhile;
			else :
				echo '<p class="not-found">No Favourite Entry Found.</p>';
			endif;
	}
	
}

add_shortcode('getfollowposts','get_follow_posts');

function getStringBetween($str,$from,$to){
    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    return substr($sub,0,strpos($sub,$to));
}

/*Favourite Custom Posts Shortcode*/

function get_fav_custom_posts($atts){
	//fetching from wp-favorite-posts.php
	//i:e club,player, or country
	$parent_post_type=$atts['post_type'];
	
	if($parent_post_type=='')
		$parent_post_type='club';
	
	$parent_forum_title = $parent_post_type;
	
	$favorite_post_ids =array();
	
	$followposts = wpfp_get_users_favorites();
	
	if(empty($followposts))
		$followposts[] = -1;
	
	//var_dump($favorite_post_ids);
	
	$fav_post_ids=array();
	
		foreach ( $followposts as $followpost ) {
			
				$posts = get_post( $followpost );

				// Get Follow Post Type
				$posttype = $posts->post_type;
				if($posttype==$parent_post_type)
				{
					$fav_post_ids[] = $posts->ID;
					//echo $posts->ID.'<br/>';
				}
		}
		
		//get all posts and terms which user has followed
		$all_followed_terms=array();

		$terms = get_terms( 'wps_forum' );
		 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			 foreach ( $terms as $term ) {
				//term meta
				$term_id_value = $term->term_id;
				
				foreach($fav_post_ids as $fav_id)
				{
					$get_parent_post_id=get_term_meta($term->term_id,'parentpostid', true);
					if($get_parent_post_id==$fav_id)
					{
						$all_followed_terms[]= $term_id_value;
					}
				}
			 }
		 }
		
		echo '<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
				<p id="favorite-'.$parent_forum_title.'"></p>
				<div class="div-section no-padding user-activities">
					<div class="section-title"><i class="fa fa-group"></i>
						<p>new posts in the favourite '.$parent_forum_title.'</p>
					</div>';

					$args=array('post_type'=>'wps_forum_post',
								'tax_query' => array( 
											array(
												'taxonomy' => 'wps_forum',
												'field'    => 'id',
												'terms'    => $all_followed_terms,
												'include_children' => 0,
											),
										), 'orderby'=>'ASC','posts_per_page'=>'10');
					
					$forum_posts=new WP_Query($args);

					while ($forum_posts->have_posts()) : $forum_posts->the_post();

						//show parent post thumbnail start
						$club_terms = wp_get_post_terms( get_the_ID(), 'wps_forum');
							$term_id = $club_terms[0]->term_id;
						$parent_post_id = get_term_meta( $term_id, 'parentpostid', get_the_ID());
						
						$parent_post_thmnail_url = get_the_post_thumbnail( $parent_post_id, array(34,40));
						//thumbnail ends
						
						echo '<div class="col-lg-6 col-md-6 col-xs-12 club-chat-sec">
									
							<div class="col-lg-2 col-md-2 no-padding thread-thumbnail"><a href="';
							the_permalink();
							echo '">';
							if ( $parent_post_thmnail_url ) {
								echo $parent_post_thmnail_url;
							}
							else{
								echo '<div class="default_ball"></div>';
							}
							echo '</a></div><div class="col-lg-10 col-md-10 thread-content no-padding"><a href="';
							
							the_permalink();
							echo '" class="thread-title">';
							//echo substr(the_title('', '', FALSE), 0, 44);
							echo '</a>';
							$content = get_the_excerpt();
							echo '<p>'.substr($content, 0, 155).'<br/><span class="fs-author">'.get_the_author_meta('first_name').'</span></p></div>';
							echo '<div class="col-lg-12 col-md-12 col-xs-12 thread-bottom">';
							$post_time= get_the_modified_time('Y-m-j g:i:s');
							
							//echo '<div class="col-lg-3 col-md-2 col-xs-2"><i class="fa fa-eye"></i>';echo views_count(get_the_ID()); echo '</div>';
							echo '<div class="col-lg-3 col-md-3 col-xs-2">'; if(function_exists('wp_ulike')) wp_ulike('get'); echo '</div>';
							echo '<div class="col-lg-3 col-md-2 col-xs-2 shout-comment-icon" id="'.get_the_ID().'"><i class="fa fa-comment-o"></i>';comments_number('0','1','%'); echo '</div>';
							echo '<div class="col-lg-3 col-md-3 col-xs-2"><i class="fa fa-clock-o"></i>';echo get_time_difference($post_time); echo '</div>';
							
							echo '<div class="col-lg-12 col-md-12 col-xs-12 shout-comment" id="postid-'.get_the_ID().'">';
								comments_template('comments.php',true);//get_shoutbox_comment(get_the_title());
							echo '</div>';

							echo '</div></div>';
							$count++;
						endwhile;
						wp_reset_postdata();
					if($count==0){
						echo '<div class="no-match-found"><h3>No Post Found.</h3></div>';
					}
					echo '</div>
						</div>';
	
	
}

add_shortcode('getfavcustomposts','get_fav_custom_posts');

function get_custom_follow_post($atts){
	
	//i:e club,player, or country
	$parent_post_type=$atts['post_type'];
	
	if($parent_post_type=='')
		$parent_post_type='club';
	
	$parent_forum_title = $parent_post_type;
	
	//global $wpw_fp_model, $wpw_fp_options;
	global $wpdb, $user_ID, $user_email, $wpw_fp_options, $wpw_fp_model;
	$prefix = WPW_FP_META_PREFIX;

	//model class
	$model = $wpw_fp_model;
	$user_id = get_current_user_id();
	
	$argsdata = array(
					'author' 			=>	$user_id,
					'posts_per_page' 	=>	$perpage,
					'paged'				=>	$_POST['paging']
				);
				
	$followposts = $model->wpw_fp_get_follow_post_users_data( $argsdata );
	
	$follow_post_ids=array();
	
		foreach ( $followposts as $followpost ) {
			
			$post_parent = isset( $followpost['post_parent'] ) && !empty( $followpost['post_parent'] ) ? $followpost['post_parent'] : '';
			
			if( !empty( $post_parent ) ) { // Check post parent is not empty
				
				$posts = get_post( $post_parent );
				//print_r($posts);
				
				//Get Post Id
				//$page_id=$posts->ID;

				// Get Follow Post Type
				$posttype = $posts->post_type;
				if($posttype==$parent_post_type)
				{
					$follow_post_ids[] = $posts->ID;
					//echo $posts->ID.'<br/>';
				}
				
			}
		}
		
		//get all posts and terms which user has followed
		$all_followed_terms=array();
		$terms = get_terms( 'wps_forum' );
		 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			 foreach ( $terms as $term ) {
				//term meta
				$term_id_value = $term->term_id;
				
				foreach($follow_post_ids as $follow_id)
				{
					$get_parent_post_id=get_term_meta($term->term_id,'parentpostid', true);
					if($get_parent_post_id==$follow_id)
					{
						//check follow status
						// args to check user is following this post?
						$post_args = array( 
											'post_status'	=>	'publish',
											'post_parent' 	=>	$follow_id,
											'post_type' 	=>	WPW_FP_POST_TYPE
										);
						
						// get results from args		
						$result = get_posts( $post_args );
						$follow_status = get_post_meta( $result[0]->ID, $prefix.'follow_status', true );
						
						if($follow_status==1)
						//if user have followed and status 1 , i:e followed , 0 mean not followed
						$all_followed_terms[]= $term_id_value;
					}
				}
			 }
		 }
		
		echo '<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
				<p id="follow-'.$parent_forum_title.'"></p>
				<div class="div-section no-padding user-activities">
					<div class="section-title"><i class="fa fa-group"></i>
						<p>new posts in the '.$parent_forum_title.' you are following</p>
					</div>';

					$args=array('post_type'=>'wps_forum_post',
								'tax_query' => array( 
											array(
												'taxonomy' => 'wps_forum',
												'field'    => 'id',
												'terms'    => $all_followed_terms,
												'include_children' => 0,
											),
										), 'orderby'=>'ASC','posts_per_page'=>'10');
					
					$forum_posts=new WP_Query($args);

					while ($forum_posts->have_posts()) : $forum_posts->the_post();

						echo '<div class="col-lg-6 col-md-6 col-xs-12 club-chat-sec">
									
							<div class="col-lg-2 col-md-2 no-padding thread-thumbnail"><a href="';
							the_permalink();
							echo '">';
							if ( has_post_thumbnail() ) {
								the_post_thumbnail(array(34,40));
							}
							else{
								echo '<div class="default_ball"></div>';
							}
							echo '</a></div><div class="col-lg-10 col-md-10 thread-content no-padding"><a href="';
							
							the_permalink();
							echo '" class="thread-title">';
							//echo substr(the_title('', '', FALSE), 0, 44);
							echo '</a>';
							$content = get_the_excerpt();
							echo '<p>'.substr($content, 0, 155).'<br/><span class="fs-author">'.get_the_author_meta('first_name').'</span></p></div>';
							echo '<div class="col-lg-12 col-md-12 col-xs-12 thread-bottom">';
							$post_time= get_the_modified_time('Y-m-j g:i:s');
							
							//echo '<div class="col-lg-3 col-md-2 col-xs-2"><i class="fa fa-eye"></i>';echo views_count(get_the_ID()); echo '</div>';
							echo '<div class="col-lg-3 col-md-3 col-xs-2">'; if(function_exists('wp_ulike')) wp_ulike('get'); echo '</div>';
							echo '<div class="col-lg-3 col-md-2 col-xs-2 shout-comment-icon" id="'.get_the_ID().'"><i class="fa fa-comment-o"></i>';comments_number('0','1','%'); echo '</div>';
							echo '<div class="col-lg-3 col-md-3 col-xs-2"><i class="fa fa-clock-o"></i>';echo get_time_difference($post_time); echo '</div>';
							
							echo '<div class="col-lg-12 col-md-12 col-xs-12 shout-comment" id="postid-'.get_the_ID().'">';
								comments_template('comments.php',true);//get_shoutbox_comment(get_the_title());
							echo '</div>';

							echo '</div></div>';
							$count++;
						endwhile;
						wp_reset_postdata();
					if($count==0){
						echo '<div class="no-match-found"><h3>No Post Found.</h3></div>';
					}
					echo '</div>
						</div>';
					
	}
	
add_shortcode( 'getfollowpost','get_custom_follow_post');

function get_autocomplete_titles(){
	$args=array('post_type' => array( 'country', 'player', 'club', 'league' ),
				'posts_per_page'=>'-1');
				
	$titles_query=new WP_Query($args);
	//$get_all_titles=array();
	$get_all_titles='';
	while ($titles_query->have_posts()) : $titles_query->the_post();
		$get_all_titles .= '"'.get_the_title().'",';
	endwhile;

	$blogusers = get_users( array( 'fields' => array( 'user_login' ) ) );
	// Array of stdClass objects.
	foreach ( $blogusers as $user ) {
		$user_name = $user->user_login;
		$get_all_titles .= '"'.$user_name.'",';
	}
	echo $get_all_titles;				
}

add_shortcode('auto-complete-titles','get_autocomplete_titles');

function club_next_match($atts){
	$term=$atts['term'];
	global $px_event_meta;
	
	$args=array('post_type'=>'events','orderby'=>'ASC','posts_per_page'=>'-1');
	$events=new WP_Query($args);
	//print_r($events);
	while ( $events->have_posts() ) : $events->the_post();
			
			$post_xml = get_post_meta(get_the_ID(), "px_event_meta", true);
			if ( $post_xml <> "" ) {
				$px_event_meta = new SimpleXMLElement($post_xml);
				$team1 = px_get_term_object($px_event_meta->var_pb_event_team1);
				$team2 = px_get_term_object($px_event_meta->var_pb_event_team2);
					
					$term_id_team1 = $team1->term_id;
					$term_name_team1 = $team1->name;
					$team_img1 = px_team_data_front($term_id_team1);
					
					$term_id_team2 = $team2->term_id;
					$term_name_team2 = $team2->name;
					$team_img2 = px_team_data_front($term_id_team2);
					
					//date of current match
					$px_event_from_date = get_post_meta(get_the_ID(), "px_event_from_date", true);
					
					if(date('M d,Y') < date('M d,Y', strtotime($px_event_from_date))) //check if match date gone or not
					{
						//check if current match is of current club's match
						if (strpos($term_name_team1,$term) !== false || strpos($term_name_team2,$term) !== false) {
							//team 1 image
							echo  '<img class="club-match-img" alt="'.$term_name_team1.'" src="'.$team_img1[0].'">';
							echo '<p>'.$term_name_team1.'</p><p>v</p><p>'.$term_name_team2.'</p>';
							//team 2 image
							echo  '<img class="club-match-img" alt="'.$term_name_team2.'" src="'.$team_img2[0].'">';
							
							echo '<div class="match-time-date"><span>'.$px_event_meta->event_time.'</span><span>'.date('M d,Y', strtotime($px_event_from_date)).'</span></div>';
							
							$event_league = get_field('league');
							echo '<p>'.$event_league->post_name.'</p>';
							
							$post_data=get_post(get_the_ID());
							echo '<a href="'.get_site_url().'/?p='.get_the_ID().'"><div class="see-all">SEE FULL SCHEDULE</div></a>';
							return;
						}
					}
					else {
						echo '<p class="no-match-found">No upcoming match</p>';
						return;
					}
			}
	endwhile;	
}

add_shortcode('club-next-match','club_next_match');

function get_term_id($atts){
	$term=strtolower($atts['term']);
	//$term='arsenal';
	//$term_data=get_term_by( 'name',$term, 'wps_forum' );
	//print_r( $term_data);
	echo $term;
}

add_shortcode('get-term-id','get_term_id');

/*Add custom taxonomy field*/

add_action('wps_forum_add_form_fields', 'wps_forum_metabox_add', 10, 1);
add_action('wps_forum_edit_form_fields', 'wps_forum_metabox_edit', 10, 1);    

function wps_forum_metabox_add($tag) {
	echo '<h3>Parent Post ID</h3>
		<div class="form-field">
			<label for="parentpostid">Post Type ID</label>
			<input name="parentpostid" id="parentpostid" type="text" value="" size="40" aria-required="true" />
		</div>
	';  
}	

function wps_forum_metabox_edit($tag) {
	echo '<h3>Meta Box Info Title</h3>
		<table class="form-table">
			<tr class="form-field">
			<th scope="row" valign="top">
				<label for="parentpostid">Parent Post ID</label>
			</th>
			<td>
				<input name="parentpostid" id="parentpostid" type="text" value="';
				echo get_term_meta($tag->term_id,'parentpostid', true);
			echo '" size="40" aria-required="true" />
			</td>
			</tr>
		</table>
		';
 }

add_action('created_wps_forum', 'save_wps_forum_metadata', 10, 1);
add_action('edited_wps_forum', 'save_wps_forum_metadata', 10, 1);

function save_wps_forum_metadata($term_id){
	if (isset($_POST['parentpostid']))
		update_term_meta( $term_id, 'parentpostid', $_POST['parentpostid']);
}

function shoutbox($atts){
	$count=0;
	$get_post_id=$atts['postid'];
	
	
	
	$term_arg = array(
		'hide_empty' => false, 
	); 
	
	$terms = get_terms( 'wps_forum' , $term_arg );
	 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		 foreach ( $terms as $term ) {
			//term meta
			$term__parent_post_value=get_term_meta($term->term_id,'parentpostid', true);
			//echo $term->name.'<br/>';
			if($term__parent_post_value==$get_post_id){
					$term_id_value = $term->term_id;
					$term_name = $term->name;
				}
		 }
	 }
	
	$get_filter_val = $_POST['filter-post'];
	echo '<div class="section-title"><i class="fa fa-comments-o"></i>
			<p id="shout-head-title">shoutbox</p>';
		
			//filter form starts
			echo '<form action="" method="post" id="filter-form">
				<select id="filter-post" name="filter-post">
					';
					
					echo '<option ';
					if ($get_filter_val){
						if($get_filter_val=='1')
							echo "selected";
						else
							echo "";
					}
					echo ' value="1">Most Recent</option>';
					echo '<option ';
					if ($get_filter_val){
						if($get_filter_val=='2')
							echo "selected";
						else
							echo "";
					}
					echo ' value="2">Most Likes</option>
				</select>
				<input type="hidden" value="'.$get_post_id.'" name="current_post_id" id="current_post_id">
				</form>
				';
			//filter form ends
		echo '</div>';
	//section head ends
	$upload_dir = wp_upload_dir();
	//$up_url = $upload_dir['url'].'/'.$_SESSION["upload_file_name"];
	$up_url = $upload_dir['url'];
	//$up_url = substr($up_url,0,-1);
	//unset($_SESSION['upload_file_name']);
	
	echo '<div id="upload_path">'.$up_url.'</div>';
	echo '<script type="text/javascript" src="'.get_site_url().'/wp-content/themes/footysquare/scripts/frontend/jquery.ajaxfileupload.js"></script>';
	echo '<script>
	
	jQuery(document).ready(function() {
	
	var interval;
	
	function applyAjaxFileUpload(element) {
		
		var upload_url_path = jQuery("#upload_path").text()+"/";
		jQuery(element).AjaxFileUpload({
			action: "/fs/wp-content/themes/footysquare/upload.php",
			onChange: function(filename) {
				// Create a span element to notify the user of an upload in progress
				var jQueryspan = jQuery("<span />")
					.attr("class", jQuery(this).attr("id"))
					.text("Uploading")
					.insertAfter(jQuery(this));

				jQuery(this).hide();
				

				interval = window.setInterval(function() {
					var text = jQueryspan.text();
					if (text.length < 13) {
						jQueryspan.text(text + ".");
					} else {
						jQueryspan.text("Uploading");
					}
				}, 200);
				
			},
			onSubmit: function(filename) {
				return true;
			},
			onComplete: function(filename, response) {
				
				window.clearInterval(interval);
				var jQueryspan = jQuery("span." + jQuery(this).attr("id")).text(filename + " "),
					jQueryfileInput = jQuery("<input />")
						.attr({
							type: "file",
							name: jQuery(this).attr("name"),
							id: jQuery(this).attr("id")
						});
					
					//jQueryfilevalue = jQuery("<img />").attr("src", upload_url_path+response.name).attr("width", 200);
					//jQuery("#uploads").append(jQueryfilevalue);
				
				if (typeof(response.error) === "string") {
					jQueryspan.replaceWith(jQueryfileInput);

					applyAjaxFileUpload(jQueryfileInput);

					alert(response.error);

					return;
				}
				
				jQuery("#uploads").append(
					jQuery("<img />").attr("src", upload_url_path+response.name).attr("width", 200),
					jQuery("<a />").attr("href", "#").text("x").bind("click", function(e) {
						jQuery("#uploads").text("");
						jQuery(".demo1").remove();
						jQuery("#demo1").show();
					})
					.appendTo(jQueryspan)
				);
				
				jQuery("#uploads_field").append(
					jQuery("<input type=\"hidden\" name=\"featured_image\"/>").attr("value", filename)
				);
			}
		});
	}

	applyAjaxFileUpload("#demo1");
});
	
	</script>';
	if ( is_user_logged_in()) {
	
	
	//echo '<div id="uploads"></div><input type="file" name="file" id="demo1" />';
	echo '<form method="post" name="front_end_custom_post" id="front_end_custom_post" action="" enctype="multipart/form-data">
			  <div class="add_post_form">
				<!--<input type="text" name="title" placeholder="Add Title"/>-->
				<textarea id="add_new_post_shoutbox" type="text" name="content" placeholder="WRITE YOUR POST..."></textarea>
				<div id="uploads_field"></div>
				
			';
			echo '<input type="hidden" name="term" value="'.$term_name.'"/>
			 <div class="col-lg-12 shout-textarea-bottom">WHATEVER YOU WRITE, THE FIRST LINE WILL BE THE TITLE OF YOUR POST, CHOOSE YOUR WORDS WISELY! ';
				echo '<i class="fa fa-image">';
					echo '<div id="uploads"></div><input type="file" name="file" id="demo1" />';
				echo '</i>';
			 echo '</div>	
			  </div>
			  <div class="shoutbox-footer">
			    <!--<div class="shoutbox-user-info">Signed In as <a href="'.get_site_url().'/?page_id=1770&user_id='.$user_id.'">'.$username.'</a> | <a href="'. wp_logout_url(get_permalink()).' ">sign out</a></div>-->
				<input type="hidden" name="action" value="frontend_post_add" />
				<input type="hidden" name="term" value="'.$term_name.'" />
				<input type="hidden" name="term_id" value="'.$term_id_value.'" />
				<input type="button" id="shout-post-submit" class="btn btn-primary" value="POST"/>
			  </div>
		  </form>';
	}	
	
	echo '<div id="shoutbox-inner">';
	
	/*shoutbox posts starts*/
		echo '<div id="shoutbox-content" class="post_content-'.$get_post_id.'">
				<div class="loader-shoutbox"></div>
		</div>';
		echo '<div class="col-lg-12 see-all" id="shout-more"><i class="fa fa-angle-down view_more"></i></div>';
		//echo '<div class="loader-shoutbox"></div>';
	echo '</div>'; //end inner shoutbox
	
}
add_shortcode('shoutbox','shoutbox');

/**** ajax shout post loader starts ****/

//setp-1
function register_jquery() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}     
//add_action('wp_enqueue_scripts', 'register_jquery');

//step-2
function register_ajaxLoop_script() {
    wp_register_script(
      'ajaxLoop',
       get_stylesheet_directory_uri() . '/scripts/frontend/post_loader/ajaxLoop.js',
       array('jquery')
    );
    wp_enqueue_script('ajaxLoop');
	
	wp_register_script(
      'ajaxloadfavcountry',
       get_stylesheet_directory_uri() . '/scripts/frontend/post_loader/ajaxloadfavcountry.js',
       array('jquery')
    );
    wp_enqueue_script('ajaxloadfavcountry');
	
	wp_register_script(
      'searchresult',
       get_stylesheet_directory_uri() . '/scripts/frontend/post_loader/searchresult.js',
       array('jquery')
    );
    wp_enqueue_script('searchresult');
	/*
	wp_register_script(
      'socialshare',
       get_stylesheet_directory_uri() . '/scripts/frontend/socialshare.js',
       array('jquery')
    );
    wp_enqueue_script('socialshare');	*/
}
add_action('wp_enqueue_scripts', 'register_ajaxLoop_script');

//step-3
 //create loopHandler.php file and paste shoutbox content in it.

/**** ajax shout post loader ends ****/

function replace_content($content){	
	//get all words that start @
	preg_match_all("/@(\w+)/", $content, $out, PREG_SET_ORDER);
	//var_dump($out);

	foreach($out as $tag_word)
	{	
		//$tag_word[1] = Orignal Word, $tag_word[0] = word start with @ tag
		//echo $tag_word[1].'<br/>';
		global $wpdb;
		
		//all posts
		$sql = "SELECT * FROM `wp_posts` WHERE `post_name` like '".$tag_word[1]."' AND (`post_type`='club' OR `post_type`='player' OR `post_type`='country') limit 1";
		$mentions = $wpdb->get_results($sql);

		//get user
		$sql_user = "SELECT * FROM `wp_users` WHERE `user_login` like '".$tag_word[1]."' limit 1";
		$mentions_user = $wpdb->get_results($sql_user);
		
		if ($mentions) {
			foreach ($mentions AS $mention) {
				$post_id = $mention->ID;
				
				$post_url = "?p=$post_id";
				$mentioned_string = "<a class='mention_tag' href='$post_url'>$tag_word[0]</a>";
			}
		}
		
		if ($mentions_user) {
			foreach ($mentions_user AS $mention_user) {
				$user_id = $mention_user->ID;
				
				$user_url = "?page_id=1770&user_id=$user_id";
				$mentioned_string = "<a class='mention_tag' href='$user_url'>$tag_word[0]</a>";
			}
		}
		
		if($mentioned_string)
			$content = str_replace($tag_word[0],$mentioned_string,$content);
		//$mentioned_string= null;
	}
	
	$content = preg_replace_callback("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i",
			function($matches) {
				$url = $matches[1];
			    $short_url = substr($matches[1],0,25);
				if(strlen($url)>25)
					$short_url .= '...';
				
				//work only when internet is available
				$url_str = file_get_contents($url);
				if(strlen($url_str)>0){
					preg_match("/\<title\>(.*)\<\/title\>/",$url_str,$title);
					$link_title = $title[1];
				}
				
			   //return "<a target=\"_blank\" href=\"$url\">$short_url</a>";	
			   return "<a target=\"_blank\" href=\"$url\">$link_title</a>";
			}, $content);
		
	return substr($content,0,250);
	
}
add_filter('the_content','replace_content');

function callback($matches)
{
   global $one,$two;
   $url = $matches[1];
   $short_url = substr($matches[1],0,15);
   return "<a target=\"_blank\" href=\"$url\">$short_url</a>";
   //return $matches[1].$matches[0];
}

function mention_notify($post_id,$content){
	//get all words that start @
	preg_match_all("/@(\w+)/", $content, $out, PREG_SET_ORDER);
	foreach($out as $tag_word)
	{
		if($tag_word[1]){
			//get user
			global $wpdb;
			$sql_user = "SELECT * FROM `wp_users` WHERE `user_login` like '".$tag_word[1]."' limit 1";
			$mentions_user = $wpdb->get_results($sql_user);
			
			if ($mentions_user) {
				foreach ($mentions_user AS $mention_user) {
					$user_id = $mention_user->ID;
				}
			}
			
			//get settings
			$get_notify_settings = $wpdb->get_results("SELECT * FROM `wp_notification_settings` WHERE `user_id` = $user_id");
		
			foreach($get_notify_settings as $notif_set){
				$get_mention_setting = $notif_set->noti_setting_mention;
			}
			
			//if user mention notify on footy
			if($get_mention_setting >=1100){
				set_notification($post_id,4,$user_id);
				
				//if user allow email and user mentioned
				if($get_mention_setting >=1110){
					set_notify_email($post_id,4,$user_id);
				}
			}
			
			//alert notification
			$upload_dir = wp_upload_dir();
			//$post_author_id = get_post_field( 'post_author', $comment_made_by_id);
			
			$file_url = $upload_dir['path'].'/notify/'.$user_id;
			
			$myfile = fopen("$file_url.txt", "w") or die("Unable to open file!");
			$txt = "true \n";
			fwrite($myfile, $txt);
			fclose($myfile);
			
		}
	}
}

function update_model_panel($get_postid=null){
		
}

add_filter('comment_post_redirect', 'redirect_after_comment');
function redirect_after_comment($location)
{
	global $wpdb;
	return $_SERVER["HTTP_REFERER"]."#shoutbox";
}


function logout_custom(){
	wp_logout_url(get_permalink());
}

//custom excerpt length 20 words

function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


/*Add hook if post added, add taxonomy*/

/******************* Add Forum Starts ************************/

//club forum
function add_new_forum_club( $post_ID ) {

	$parent_term = term_exists( 'clubs', 'wps_forum' ); // array is returned if taxonomy is given
	$parent_term_id = $parent_term['term_id']; // get numeric term id
	
	$post_meta=get_post($post_ID); 
	$title = $post_meta->post_title;
	$slug = $post_meta->post_name;
	
	//add team taxonomy
	wp_insert_term(
	  $title, // the term 
	  'team-category', // the taxonomy
	  array(
		'slug' => $title,
		'parent'=> 61
	  )
	);
	//set post term
	wp_set_object_terms( $post_ID, $title, 'team-category');
	
	wp_insert_term(
	  $title, // the term 
	  'wps_forum', // the taxonomy
	  array(
		'slug' => $title,
		'parent'=> $parent_term_id
	  )
	);
	
	$last_term=get_term_by('slug', $slug, 'wps_forum');
	//add value in custom meta field : parent post type
	update_term_meta( $last_term->term_id, 'parentpostid', $post_ID);
   //return $post_ID;

}

add_action( 'publish_club', 'add_new_forum_club' );

//countries
function add_new_forum_country( $post_ID ) {

	$parent_term = term_exists( 'countries', 'wps_forum' ); // array is returned if taxonomy is given
	$parent_term_id = $parent_term['term_id']; // get numeric term id
	
	$post_meta=get_post($post_ID); 
	$title = $post_meta->post_title;
	$slug = $post_meta->post_name;
	
	//add team taxonomy
	wp_insert_term(
	  $title, // the term 
	  'team-category', // the taxonomy
	  array(
		'slug' => $title,
		'parent'=> 130
	  )
	);
	//set post term
	wp_set_object_terms( $post_ID, $title, 'team-category');
	
	wp_insert_term(
	  $title, // the term 
	  'wps_forum', // the taxonomy
	  array(
		'slug' => $title,
		'parent'=> $parent_term_id
	  )
	);
	
	$last_term=get_term_by('slug', $slug, 'wps_forum');
	//add value in custom meta field : parent post type
	update_term_meta( $last_term->term_id, 'parentpostid', $post_ID);
   //return $post_ID;

}

add_action( 'publish_country', 'add_new_forum_country' );

//players
function add_new_forum_player( $post_ID ) {

	$parent_term = term_exists( 'players', 'wps_forum' ); // array is returned if taxonomy is given
	$parent_term_id = $parent_term['term_id']; // get numeric term id
	
	$post_meta=get_post($post_ID); 
	$title = $post_meta->post_title;
	$slug = $post_meta->post_name;
	
	wp_insert_term(
	  $title, // the term 
	  'wps_forum', // the taxonomy
	  array(
		'slug' => $title,
		'parent'=> $parent_term_id
	  )
	);
	
	$last_term=get_term_by('slug', $slug, 'wps_forum');
	//add value in custom meta field : parent post type
	update_term_meta( $last_term->term_id, 'parentpostid', $post_ID);
   //return $post_ID;

}

add_action( 'publish_player', 'add_new_forum_player' );

//club forum
function add_new_forum_events( $post_ID ) {

	$parent_term = term_exists( 'matches', 'wps_forum' ); // array is returned if taxonomy is given
	$parent_term_id = $parent_term['term_id']; // get numeric term id
	
	$post_meta=get_post($post_ID); 
	$title = $post_meta->post_title;
	$slug = $post_meta->post_name;
	
	//add team taxonomy
	wp_insert_term(
	  $title, // the term 
	  'team-category', // the taxonomy
	  array(
		'slug' => $title,
		'parent'=> 70
	  )
	);
	//set post term
	wp_set_object_terms( $post_ID, $title, 'team-category');
	
	wp_insert_term(
	  $title, // the term 
	  'wps_forum', // the taxonomy
	  array(
		'slug' => $title,
		'parent'=> $parent_term_id
	  )
	);
	
	$last_term=get_term_by('slug', $slug, 'wps_forum');
	//add value in custom meta field : parent post type
	update_term_meta( $last_term->term_id, 'parentpostid', $post_ID);
   //return $post_ID;

}

add_action( 'publish_events', 'add_new_forum_events' );


/******************* Add Forum Ends ************************/

function save_existant_forum($post_ID){
	
	$post_meta=get_post($post_ID); 
	$title = $post_meta->post_title;
	$slug = $post_meta->post_name;
	
	$current_term = get_term_by( 'slug', $slug, 'wps_forum' );

	//get all term and filter which parent post id is equal to $post_ID
	// or match slugs of both posts
		wp_update_term($current_term->term_id, 'wps_forum', array(
		  'name' => $title,
		  'slug' => $title
		));
	
}

add_action('save_club', 'save_existant_forum');	

function delete_forum_after_post($post_ID){
	
	$post_meta=get_post($post_ID); 
	$slug = $post_meta->post_name;
	
	$current_term = get_term_by( 'slug', $slug, 'wps_forum' );
	wp_delete_term($current_term->term_id, 'wps_forum' );
	// $current_term->term_id
}

add_action('delete_club', 'delete_forum_after_post');		

/* Notification Module */

function get_alert_notification($atts){
	global $wpdb;
	//get all notifications which are unread
	$user_id = get_current_user_id();
	$get_notifications = $wpdb->get_results("SELECT * FROM `wp_notification` WHERE `status` = '0' AND `user_id` = $user_id");
	echo count($get_notifications);
}
add_shortcode('alert_notification_count','get_alert_notification');

function set_notification($get_postid=null,$get_noti_type,$get_follow_userid=null,$get_comment_id=null){
	global $wpdb;
	
	if($get_postid != null && $get_follow_userid != null){
		$user_id = get_current_user_id();
		$post_id = $get_postid;
		$recipient_id = $get_follow_userid;
		$notification_type = $get_noti_type;
	}
	else if($get_postid){
		$user_id = get_current_user_id();
		$post_id = $get_postid;
		$get_recip_id = get_post_field( 'post_author', $post_id );
		$recipient_id = $get_recip_id;
		$notification_type = $get_noti_type;
	}
	else if($get_follow_userid){
		$user_id = get_current_user_id();
		$post_id = 0;
		$recipient_id = $get_follow_userid;
		$notification_type = $get_noti_type;
	}
	
	else if($get_comment_id){
		$user_id = get_current_user_id();
		$post_id = 0;
		$comment_id = $get_comment_id;
		$comment_meta = get_comment( $comment_id );
		$recipient_id = $comment_meta->user_id;
		$notification_type = $get_noti_type;
	}
	
	//$show = "INSERT INTO ".$wpdb->prefix."notification (`user_id`, `recipient_id`, `postid`, `notification_type`) VALUES ('$user_id', '$recipient_id', '$post_id', '$notification_type')";
	//echo $show;
	
	//Note:::
	//if there is no postid that means user has followed some user.
	$current_time = current_time( 'mysql' ); 
	
	$wpdb->insert($wpdb->prefix."notification", array(
	   "user_id" => $user_id,
	   "recipient_id" => $recipient_id,
	   "postid" => $post_id,
	   "comment_id" => $comment_id,
	   "notification_type" => $notification_type,
	   "time" => $current_time
	));
}

function set_notify_email($post_id=null,$get_noti_type,$notify_to_user_id=null){
	
	$current_user_ID = get_current_user_id();
	
	$post_time = date('m/d/Y h:i:s a', time());
	
	//send email notification to user
	if($post_id){
		$post_meta = get_post($post_id); 
		$post_title = $post_meta->post_title;
		$post_author_id = $post_meta->post_author;
		//$post_time  = get_time_difference($post_meta->post_date_gmt);
		
		//email
		$notify_to_user = get_userdata($post_author_id);
		$notify_to_email = $notify_to_user->user_email;
	}
	
	//notify to user meta
	if($notify_to_user_id){
		$post_author_id = $notify_to_user_id;
		$notify_to_user = get_userdata($post_author_id);
		$notify_to_email = $notify_to_user->user_email;
	}
	
	//current user
	global $current_user;
	$current_user_name = $current_user->user_login;
	
	//mail credentials
	$to = $notify_to_email;
	
	//for like and like comment ,Code 1 and 2 for it
	if($get_noti_type==1){
		$subject = "$current_user_name like's your post";
		$message = "$current_user_name likes you post $post_title $post_time.";
	}
	//for making comment
	if($get_noti_type==3){
		$subject = "$current_user_name comment on your post";
		$message = "$current_user_name on your post $post_title $post_time.";
	}
	//for user follow
	if($get_noti_type==5){
		$subject = "$current_user_name follows you";
		$message = "$current_user_name is now following you, $post_time.";
	}
	
	if($post_author_id != $current_user_ID)
		wp_mail($to, $subject, $message);
}

//notify if user like
//make comment code = 1
	$notify=false;
	if(isset($_POST['action'])){
		if($_POST['action']=='ulikeprocess'){
			$postid=$_POST['id'];
			if($postid){
				global $wpdb;
				
				$get_likes = $wpdb->get_results("SELECT * FROM `wp_ulike` WHERE `post_id` = $postid");
				if($get_likes){
					foreach($get_likes as $get_like){
						$get_like_status = $get_like->status;
					}
					if (($get_like_status=="unlike")){
						$notify = true;
						$post_id = $postid;
					}
				}
				else{
					$notify = true;
					$post_id = $postid;
				}
			}
		}
	
		if($notify==true){
			
			global $wpdb;
			$show_notify_to_user_id = get_post_field( 'post_author', $post_id );
			$get_notify_settings = $wpdb->get_results("SELECT * FROM `wp_notification_settings` WHERE `user_id` = $show_notify_to_user_id");
			foreach($get_notify_settings as $notif_set){
				$get_like_setting = $notif_set->noti_setting_footy;
			}
			
			//if user allow like
			if($get_like_setting >=1100){
				set_notification($post_id,1);
				
				//if user allow email and like both
				if($get_like_setting >=1110){
					set_notify_email($post_id,1);
				}
				
				$user_id_val = get_current_user_id();
				$upload_dir = wp_upload_dir();

				if (!file_exists($upload_dir['path']."/notify")) {
					mkdir($upload_dir['path']."/notify", 0777, true);
				}
				
				$post_author_id = get_post_field( 'post_author', $post_id );
				$file_url = $upload_dir['path'].'/notify/'.$post_author_id;
				
				$myfile = fopen("$file_url.txt", "w") or die("Unable to open file!");
				$txt = "true \n";
				fwrite($myfile, $txt);
				fclose($myfile);
			}
			
			//if user allow email only
			if($get_like_setting >=1010 && $get_like_setting <=1013){
				set_notify_email($post_id,1);
			}
		}
	}


//notify if user like comment
//make comment code = 2
	$notify=false;
	$comment_made_by_id=0;
	if(isset($_POST['action'])){
		if($_POST['action']=='ulikecommentprocess'){
			$comment_id=$_POST['id'];
			//$notify = true;
			
			if($comment_id){
				global $wpdb;
				
				$get_likes = $wpdb->get_results("SELECT * FROM `wp_ulike_comments` WHERE `comment_id` = $comment_id");
				if($get_likes){
					foreach($get_likes as $get_like){
						$get_like_status = $get_like->status;
						$comment_made_by_id = $get_like->user_id;
					}
					if (($get_like_status=="unlike")){
						$notify = true;
					}
				}
				else{
					$notify = true;
				}
			}
			
		}
	
		if($notify==true){
	
		global $wpdb;
		$comment_post_id = get_comment($comment_id);
		$show_notify_to_user_id = $comment_post_id->user_id;
		$get_notify_settings = $wpdb->get_results("SELECT * FROM `wp_notification_settings` WHERE `user_id` = $show_notify_to_user_id");
		
		foreach($get_notify_settings as $notif_set){
			$get_like_setting = $notif_set->noti_setting_footy;
		}
		
		if($get_like_setting >=1100){
			set_notification($post_id,2,$author,$comment_id);
			
			//if user allow email
			if($get_like_setting >=1110){
				set_notify_email($post_id,1,$author,$comment_id);
			}
			
			$user_id_val = get_current_user_id();
			$upload_dir = wp_upload_dir();
			//$post_author_id = get_post_field( 'post_author', $comment_made_by_id);
			if (!file_exists($upload_dir['path']."/notify")) {
				mkdir($upload_dir['path']."/notify", 0777, true);
			}
			
			$file_url = $upload_dir['path'].'/notify/'.$comment_post_id->user_id;
			
			$myfile = fopen("$file_url.txt", "w") or die("Unable to open file!");
			$txt = "true \n";
			fwrite($myfile, $txt);
			fclose($myfile);
		}
		
	}
	}

//add notification, once user make comment
//make comment code = 3
function comment_inserted($comment_id, $comment_object) {
	
	$comment_data = get_comment( $comment_id ); 
	$post_id = $comment_data->comment_post_ID ;
	$post_author_id = get_post_field( 'post_author', $post_id );
	
	//apply settings
	global $wpdb;
	$get_notify_settings = $wpdb->get_results("SELECT * FROM `wp_notification_settings` WHERE `user_id` = $post_author_id");
	
	foreach($get_notify_settings as $notif_set){
		$get_comment_setting = $notif_set->noti_setting_comment;
	}
	
	if($get_comment_setting >=1100){
		set_notification($post_id,3);
		
		//if user allow email
		if($get_comment_setting >=1110){
			set_notify_email($post_id,3);
		}
		
		$user_id_val = get_current_user_id();
		$upload_dir = wp_upload_dir();
		
		if (!file_exists($upload_dir['path']."/notify")) {
			mkdir($upload_dir['path']."/notify", 0777, true);
		}
		
		$file_url = $upload_dir['path'].'/notify/'.$post_author_id;
		
		$myfile = fopen("$file_url.txt", "w") or die("Unable to open file!");
		$txt = "true \n";
		fwrite($myfile, $txt);
		fclose($myfile);
	}
	
	//if user allow email only
	if($get_comment_setting >=1010 && $get_comment_setting <=1013){
		set_notify_email($post_id,3);
	}
	
}
add_action('wp_insert_comment','comment_inserted',99,2);

//notify if user follow
//follow user code = 5
	$notify=false;
	if(isset($_POST['action'])){
		if($_POST['action']=='wpw_fp_follow_author'){
			if($_POST['status']==1)
				$notify = true;
		}
	
			if($notify==true){
			
			$post_author_id = $_POST['authorid'];
			//apply settings
			global $wpdb;
			$get_notify_settings = $wpdb->get_results("SELECT * FROM `wp_notification_settings` WHERE `user_id` = $post_author_id");
			
			foreach($get_notify_settings as $notif_set){
				$get_follow_setting = $notif_set->noti_setting_follow;
			}
			
			if($get_follow_setting >=1100){
				set_notification($post_id,5,$post_author_id);
				
				//if user allow email
				if($get_follow_setting >=1110){
					set_notify_email($post_id,5,$post_author_id);
				}
				
				$user_id_val = get_current_user_id();
				$upload_dir = wp_upload_dir();
				
				if (!file_exists($upload_dir['path']."/notify")) {
					mkdir($upload_dir['path']."/notify", 0777, true);
				}
				
				$file_url = $upload_dir['path'].'/notify/'.$post_author_id;
				
				$myfile = fopen("$file_url.txt", "w") or die("Unable to open file!");
				$txt = "true \n";
				fwrite($myfile, $txt);
				fclose($myfile);
			}
			
			//if user allow email only
			if($get_follow_setting >=1010 && $get_follow_setting <=1013){
				set_notify_email($post_id,5,$post_author_id);
			}
			
		}
	}
	

function append_query_string( $url, $post, $leavename ) {
	if ( $post->post_type == 'club' ) {
		$url = add_query_arg( 'section', 'bar', $url );
	}
	return $url;
}
add_filter( 'club_link', 'append_query_string', 10, 3 );

/*function parameter_queryvars( $qvars )
{
	$qvars[] = 'section';
	return $qvars;
}
add_filter('query_vars', 'parameter_queryvars' );	
*/

function social_media_login($atts){
//social login
		echo '
			<div class="col-lg-12 col-md-12 no-padding">
				<p class="section-second-title">sign in to your account :</p>
				<div class="social_connect_ui ">
					<p class="comment-form-social-connect">
						<div class="social_connect_form">
							<label>Sign in using</label>
							<a href="javascript:void(0);" title="Facebook" class="social_connect_login_facebook">
								<img alt="Facebook" src="'.get_site_url().'/wp-content/plugins/social-connect/media/img/fb-cust.png" />
							</a>
							<label>or</label>
							<a href="javascript:void(0);" title="Twitter" class="social_connect_login_twitter">
								<img alt="Twitter" src="'.get_site_url().'/wp-content/plugins/social-connect/media/img/tw-cust.png" />
							</a>
						</div>
					</p>
		
				<div id="social_connect_facebook_auth">
					<input type="hidden" name="client_id" value="377783302381480" />
					<input type="hidden" name="redirect_uri" value="'.get_site_url().'/index.php?social-connect=facebook-callback" />
				</div>
				<div id="social_connect_twitter_auth"><input type="hidden" name="redirect_uri" value="'.get_site_url().'/index.php?social-connect=twitter" /></div>
			</div>';
}

add_shortcode('social-login','social_media_login');


function footy_login(){
	echo '<div class="col-md-12 no-padding">
			<div class="div-section login-section">
				<div class="section-title">
					<i class="fa fa-user"></i><p>signin or create an account :</p>
				</div>';
				echo '<div class="col-lg-6 col-md-6 col-xs-12 footy-login">';
					echo do_shortcode('[wps-login-form mode="login" captcha="0" ]');
				echo '</div>';
				echo '<div class="col-lg-6 col-md-6 col-xs-12 footy-register">';
					echo do_shortcode('[wps-login-form mode="register" captcha="0" password="1"]');
				echo '</div>';
	echo '<div>
		</div>';
	echo "<style>
	#innermain{
		background: none !important;
	}
	body{
		background: url(".get_site_url()."/wp-content/themes/footysquare/images/signup-bg.png) right 655px no-repeat !important;
	}
	
	</style>";
	
}

add_shortcode('footy-login','footy_login');
	
function social_media($link) {
 
        echo '<div class="social-post">';
			//echo '<div class="counter-twitter"><a data-related="DIY_WP_Blog" href="http://twitter.com/share" class="twitter-share-button" data-text="' . get_the_title() . ' —" data-url="' . get_permalink() . '" data-count="vertical">Tweet</a></div>' . "\n";
			//echo '<a class="twitter-share-button" href="'.get_the_title().'" data-related="twitterdev" data-size="large" data-count="none">Tweet</a>';
			//echo '<a href="'.get_the_title().'" class="tweetpopup">Tweet</a>';
			echo '<a class="tweetpopup" href="http://twitter.com/share?text='.get_the_title().'-&url='.$link.'&via=Footysquare" alt="Tweet This Post" title="Tweet This Post" target="_blank"></a>';
			//echo '<div class="fb-share-button" data-href="'.get_the_title().'" data-layout="button"></div><div id="fb-root"></div>';
			echo '<a class="fb-share" href="http://www.facebook.com/sharer.php?u='.$link.'&t='.get_the_title().'" alt="Share on Facebook" title="Share on Facebook" target="_blank"></a>';
		echo '</div>';

}


function show_all_notifications(){
	
	global $current_user,$wpdb;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	
	$get_notify_settings = $wpdb->get_results("SELECT * FROM `wp_notification_settings` WHERE `user_id` = $user_id");
	foreach($get_notify_settings as $notif_set){
		$get_like_setting = $notif_set->noti_setting_footy;
		$get_comment_setting = $notif_set->noti_setting_comment;
		$get_follow_setting = $notif_set->noti_setting_follow;
	}

	$get_notifications = $wpdb->get_results("SELECT * FROM `wp_notification` WHERE `recipient_id` = $user_id AND `user_id` <> $user_id ORDER BY `wp_notification`.`time` DESC limit 50");

	foreach($get_notifications as $notif){

		$noti_type = $notif->notification_type;
		$notify_user_id = $notif->user_id;
		$recipient_id = $notif->recipient_id;
		$post_id = $notif->postid;
		$comment_id = $notif->comment_id;
		$post_time = $notif->time;
		$status_val = $notif->status;
		
		//status
		if($status_val==1)
			$status='st-checked';
		
		$current_user = get_userdata($current_user_id);
		
		//notify by user
		$notify_by_user = get_userdata($notify_user_id);
		$by_user_name = $notify_by_user->user_login;
		
		//notify to the user
		$recipient_user = get_userdata($recipient_id);
		$recipient_user_name = $recipient_user->user_login;
		
		//comment content
		$comment_data = get_comment( $comment_id ); 
		$comment_content = $comment_data->comment_content;
		$comment_content = substr($comment_content,0,20);
		if(strlen($comment_content)>20)
			$comment_content = $comment_content.'...';
		
		//post title
		$post_data = get_post($post_id); 
		$post_title = $post_data->post_title;
		$post_title = substr($post_title,0,20);
		if(strlen($post_title)>20)
			$post_title = $post_title.'...';
		
		//user avatar
		$avatar = "[wps-avatar user_id=$notify_user_id size=32]";
		
		//get permalink
		////get post taxonomy
		
		$terms = get_the_terms( $post_id, 'wps_forum' );
		if ( $terms && ! is_wp_error( $terms ) ) : 
			foreach ( $terms as $term ) {
				$post_term_id = $term->term_id;
			}
		endif;
		
		$terms = get_terms( 'wps_forum' );
		 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			 foreach ( $terms as $term ) {
				//term meta
				if($term->term_id==$post_term_id){
					$term_id_value = $term->term_id;
					}
			 }
		 }
		$term__parent_post_id=get_term_meta($term_id_value,'parentpostid', true);
		
		//like
		//echo $get_like_setting;
		if($get_like_setting >= 1100){
			if($noti_type == 1){
				echo '<div class="notification-item '.$status.'">
						<a href="'.get_site_url().'/?p='.$term__parent_post_id.'&section='.$post_id.'">
							<div class="col-lg-2">
								'.do_shortcode($avatar).'
							</div>
							<div class="col-lg-10">
								<strong>'.$by_user_name.'</strong> likes your post <strong>'.$post_title.'</strong>
								<p><i class="fa fa-clock-o"></i>'.get_time_difference($post_time).'</p>
							</div>
						</a>
					 </div>';
			}
		
			//like comment
			if($noti_type == 2){
				echo '<div class="notification-item '.$status.'">
						<a href="#">
							<div class="col-lg-2">
								'.do_shortcode($avatar).'
							</div>
							<div class="col-lg-10">
								<strong>'.$by_user_name.'</strong> likes your comment <strong>'.$comment_content.'</strong>
								<p><i class="fa fa-clock-o"></i>'.get_time_difference($post_time).'</p>
							</div>
						</a>
					 </div>';
			}
		}
		
		//make comment
		if($noti_type == 3){
			echo '<div class="notification-item '.$status.'">
					<a href="#">
						<div class="col-lg-2">
							'.do_shortcode($avatar).'
						</div>
						<div class="col-lg-10">
							<strong>'.$by_user_name.'</strong> comment on your post <strong>'.$post_title.'</strong>
							<p><i class="fa fa-clock-o"></i>'.get_time_difference($post_time).'</p>
						</div>
					</a>
				 </div>';
		}
		
		//mention user
		if($noti_type == 4){
				echo '<div class="notification-item '.$status.'">
						<a href="'.get_site_url().'/?p='.$term__parent_post_id.'&section='.$post_id.'">
							<div class="col-lg-2">
								'.do_shortcode($avatar).'
							</div>
							<div class="col-lg-10">
								<strong>'.$by_user_name.'</strong> mentioned you in his post <strong>'.$post_title.'</strong>
								<p><i class="fa fa-clock-o"></i>'.get_time_difference($post_time).'</p>
							</div>
						</a>
					 </div>';
		}
		
		//follow user
		if($noti_type == 5){
			echo '<div class="notification-item '.$status.'">
					<a href="#">
						<div class="col-lg-2">
							'.do_shortcode($avatar).'
						</div>
						<div class="col-lg-10">
							<strong>'.$by_user_name.'</strong> follows you
							<p><i class="fa fa-clock-o"></i>'.get_time_difference($post_time).'</p>
						</div>
					</a>
				 </div>';
		}
		$status='';
	}
										
}