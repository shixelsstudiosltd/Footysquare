<?php
	// Install data on theme activation
	function px_activation_data() {
		global $wpdb;
		$args = array(
		'style_sheet' => 'custom',
		'custom_color_scheme' => '#e95842',
		'header_bg_color' => '#FFFFFF',
		'nav_bg_color' => '#212121',
		'nav_color' => '#959595',
		'header_languages' => '',
		'header_cart' => '',
		'header_search' => 'on',
		'header_breadcrumbs' => 'on',
		
		'layout_option' => 'wrapper_boxed',
		'bg_img' => '2',
		'bg_img_custom' => '',
		'bg_position' => 'center',
		'bg_repeat' => 'no-repeat',
		'bg_attach' => 'fixed',
		'pattern_img' => '0',
		'custome_pattern' => '',
		'bg_color' => '#e95842',
		
		// home page announcements
		'announcement_title' => '',
		'announcement_fixtures_category' => '',
		'announcement_no_posts' => '',
		'fixture_type' => 'All',
		'fixture_order' => 'ASC',
		// end home page announcements

		
		'logo' => get_template_directory_uri().'/images/logo.png',
		'logo_width' => '250',
		'logo_height' => '67',
		
		'fav_icon' => get_template_directory_uri() . '/images/favicon.ico',
		'advertisement_banner' => get_template_directory_uri() . '/images/head-add.png',
		'advertisement_banner_url' => '#',
		'header_code' => '',
		 'analytics' => '',
		 'responsive' => 'on',
		 'style_rtl' => '',
		 'rtl_switcher' => '',
		 // fotter setting 
		 'footer_social_icons' => 'on',	
		 'partners_title' => '',
		 'partners_gallery' => '',
		 'twitter_name' => '',
		 'tweets_number' =>'',	
		 'trans_switcher' => '',
		 'sidebar' => array( 'sidebar-1','sidebar-home','contact-sidebar'),
		 'social_share' => 'on',
		 // Advertisement Banner
		 'banner_title_input' => array( 'Header Banner', 'Footer Banner', 'Sidebar Banner' ),
		 'banner_type_input' => array( 'top_banner', 'bottom_banner', 'sidebar_banner'),
		 'banner_image_url' => array( get_template_directory_uri() . '/images/px-image1.png', get_template_directory_uri() . '/images/px-image2.png', get_template_directory_uri() . '/images/px-image3.jpg'),
		 'banner_url_input' => array( '#', '#', '#'),
		 'adsense_input' => array( '', '', ''),
		 
		 //points_table_title
		 'points_table_title' => array( 'Table Heading Set 1', 'Table Heading Set 2'),
		 'points_table_coloumn_field_1' => array( 'heading 1', 'heading 1'),
		 'points_table_coloumn_field_2' => array( 'heading 2', 'heading 2'),
		 'points_table_coloumn_field_3' => array( 'heading 3', 'heading 3'),
		 'points_table_coloumn_field_4' => array( '', 'heading 4'),
		 'points_table_coloumn_field_5' => array( '', 'heading 5'),
		 'points_table_coloumn_field_6' => array( '', ''),
		 'points_table_coloumn_field_7' => array( '', ''),
		 'points_table_coloumn_field_8' => array( '', ''),
		 'points_table_coloumn_field_9' => array( '', ''),
		 
		 
		 // Social Share
		'social_net_icon_path' => array( '', '', '', '', '', '', '', '', '' ),
		'social_net_awesome' => array( 'fa-facebook-square', 'fa-google-plus-square', 'fa-linkedin-square', 'fa-pinterest-square', 'fa-twitter-square', 'fa-tumblr-square', 'fa-instagram', 'fa-flickr' ),'social_net_url' => array( 'Facebook URL', 'Google-plus URL', 'Linked-in URL', 'Pinterest URL', 'Twitter URL', 'Tumblr URL', 'Instagram URL', 'Flickr URL' ),'social_net_tooltip' => array( 'Facebook', 'Google-plus', 'Linked-in', 'Pinterest', 'Twitter', 'Tumblr', 'Instagram', 'Flickr' ),'facebook_share' => 'on','twitter_share' => 'on','linkedin_share' => 'on','pinterest_share' => 'on','tumblr_share' => 'on','google_plus_share' => 'on','px_other_share' => 'on',
		
		'trans_event_start' => 'Kick-of',
		'trans_event_vs' => 'VS',
		'trans_from' => 'From the',
		'trans_event_goals' => 'Match Goals',
		'trans_player_born' => 'Born (age)',
		'trans_player_location' => 'Location',
		'trans_player_postion' => 'Position',
		'trans_player_squad' => 'Squad Number',
		'trans_player_debut_date' => 'Debut date',
		'trans_player_location' => 'Location',
		'trans_viewall' => 'View All',
		'trans_pos' => 'Pos',
		'trans_team' => 'Team',
		'trans_play' => 'Play',
		'trans_plusminus' => '+/-',
		'trans_totalpoints' => 'Points',
		'trans_currentpage' => 'Current Page',
		'trans_photo' => 'Photos',
		'trans_add_calendar' => 'Add to Calender',
		'trans_previous' => 'Previous',
		
		'trans_headlines' => 'Headlines',
		'trans_recent' => 'Recent Posts',
		'trans_popular' => 'Popular Posts',
		
		
		'trans_out_of' => 'Out of',
		
		'trans_days' => 'Days',
		
		'trans_hours' => 'Hours',
		
		'trans_minutes' => 'Mins',
		
		'trans_seconds' => 'Secs',
		
		'trans_firstname' => 'First Name','trans_subject' => 'Subject','trans_subject' => 'Subject','trans_message' => 'Message', 'trans_share_this_post' => 'Share Now','trans_featured' => 'Featured','trans_listed_in' => 'in','trans_posted_on' => 'Posted on','trans_read_more' => 'read more','trans_other_phone' => 'Phone:','trans_other_fax' => 'Fax:','trans_special_request' => 'Special Request','trans_email_published' => '*Your Email will never published.',
		'pagination' => 'Show Pagination',
		'record_per_page' => '5',
		'px_layout' => 'none',
		'px_sidebar_left' => '',
		'px_sidebar_right' => '',
		'showlogo' => 'on',
		'socialnetwork' => 'on',
		'launch_date' => '2015-10-24',
		'copyright' =>  '&copy;'.gmdate("Y")." ".get_option("blogname")." Wordpress All rights reserved.", 
		'powered_by' => '<a href="#">Design by Pixfill</a>',
		'mailchimp_key' => '90f86a57314446ddbe87c57acc930ce8-us2',
		'consumer_key' => 'BUVzW5ThLW8Nbmk9rSFag',
		'consumer_secret' => 'J8LDM3SOSNuP2JrESm8ZE82dv9NtZzer091ZjlWI',
		'access_token' => '1584785251-sTO1qbjZFwicbIe04fIByGifvfKIeewfOpSVsJq',
		'access_token_secret' => 'FpHZH50brTiiztx0G0LNp37c1rUjjwQ4rNHbEWjABw',
		
	);
		/* Merge Heaser styles	*/
		update_option("px_theme_option", $args );
		update_option("px_theme_option_restore", $args );
 	}
	function px_activate_widget(){

		$sidebars_widgets = get_option('sidebars_widgets');  //collect widget informations

		// ---- calendar widget setting---

		$calendar = array();

		$calendar[1] = array(

		"title"		=>	'Calendar'

		);

						

		$calendar['_multiwidget'] = '1';

		update_option('widget_calendar',$calendar);

		$calendar = get_option('widget_calendar');

		krsort($calendar);

		foreach($calendar as $key1=>$val1)

		{

			$calendar_key = $key1;

			if(is_int($calendar_key))

			{

				break;

			}

		}

		//---Blog Categories

		$categories = array();

		$categories[1] = array(

		"title"		=>	'Categories',

		"count" => 'checked'

		);

						

		$calendar['_multiwidget'] = '1';

		update_option('widget_categories',$categories);

		$categories = get_option('widget_categories');

		krsort($categories);

		foreach($categories as $key1=>$val1)

		{

			$categories_key = $key1;

			if(is_int($categories_key))

			{

				break;

			}

		}

	
	// Default Recent Post
	
	
		
		$default_recent_post_widget = array();

		$default_recent_post_widget[1] = array(

		"title"		=>	'Latest Blogs',

		"select_category" 	=> 'boxing',

		"showcount" => '4',

		 );						

		$default_recent_post_widget['_multiwidget'] = '1';

		update_option('widget_recent-posts',$default_recent_post_widget);

		$default_recent_post_widget = get_option('widget_recent-posts');

		krsort($default_recent_post_widget);

		foreach($default_recent_post_widget as $key1=>$val1)

		{

			$default_recent_post_widget_key = $key1;

			if(is_int($default_recent_post_widget_key))

			{

				break;

			}

		}
	
	
	

		// ----   recent post with thumbnail widget setting---

		$recent_post_widget = array();

		$recent_post_widget[1] = array(

		"title"		=>	'Latest Blogs',

		"select_category" 	=> 'boxing',

		"showcount" => '2',

		 );						

		$recent_post_widget['_multiwidget'] = '1';

		update_option('widget_recentposts',$recent_post_widget);

		$recent_post_widget = get_option('widget_recentposts');

		krsort($recent_post_widget);

		foreach($recent_post_widget as $key1=>$val1)

		{

			$recent_post_widget_key = $key1;

			if(is_int($recent_post_widget_key))

			{

				break;

			}

		}

		// ----   recent post without thumbnail widget setting---

		$recent_post_widget2 = array();

		$recent_post_widget2 = get_option('widget_recentposts');

		$recent_post_widget2[2] = array(

		"title"		=>	'Hospitality',

		"select_category" 	=> 'football',

		"showcount" => '2',

		"thumb" => ''

		 );						

		$recent_post_widget2['_multiwidget'] = '1';

		update_option('widget_recentposts',$recent_post_widget2);

		$recent_post_widget2 = get_option('widget_recentposts');

		krsort($recent_post_widget2);

		foreach($recent_post_widget2 as $key1=>$val1)

		{

			$recent_post_widget_key2 = $key1;

			if(is_int($recent_post_widget_key2))

			{

				break;

			}

		}

 		// ----   recent event widget setting---

		$upcoming_events_widget = array();

		$upcoming_events_widget[1] = array(

		"title"		=>	'Upcoming Events',

		"get_post_slug" 	=> 'event',

		"showcount" => '4',

 		 );						

		$upcoming_events_widget['_multiwidget'] = '1';

		update_option('widget_upcoming_events',$upcoming_events_widget);

		$upcoming_events_widget = get_option('widget_upcoming_events');

		krsort($upcoming_events_widget);

		foreach($upcoming_events_widget as $key1=>$val1)

		{

			$upcoming_events_widget_key = $key1;

			if(is_int($upcoming_events_widget_key))

			{

				break;

			}

		}

		// ----   recent event countdown widget setting---

		$upcoming_events_countdown_widget = array();

		$upcoming_events_countdown_widget[1] = array(

		"title"		=>	'Upcoming Events',

		"get_post_slug" 	=> 'event',

		"showcount" => '1',

 		 );						

		$upcoming_events_countdown_widget['_multiwidget'] = '1';

		update_option('widget_cs_upcomingevents_count',$upcoming_events_countdown_widget);

		$upcoming_events_countdown_widget = get_option('widget_cs_upcomingevents_count');

		krsort($upcoming_events_countdown_widget);

		foreach($upcoming_events_countdown_widget as $key1=>$val1)

		{

			$upcoming_events_countdown_widget = $key1;

			if(is_int($upcoming_events_countdown_widget))

			{

				break;

			}

		}
  
		
		

		// --- gallery widget setting ---

		$px_gallery = array();

		$px_gallery[1] = array(

			'title' => 'Latest Photos',

			'get_names_gallery' => 'our-default-image-gallery',

			'showcount' => '20'

		);						

		$px_gallery['_multiwidget'] = '1';

		update_option('widget_px_gallery',$px_gallery);

		$px_gallery = get_option('widget_px_gallery');

		krsort($px_gallery);

		foreach($px_gallery as $key1=>$val1)

		{

			$px_gallery_key = $key1;

			if(is_int($px_gallery_key))

			{

				break;

			}

		}

		 

		// ---- search widget setting---		

		$search = array();

		$search[1] = array(

			"title"		=>	'',

		);	

		$search['_multiwidget'] = '1';

		update_option('widget_search',$search);

		$search = get_option('widget_search');

		krsort($search);

		foreach($search as $key1=>$val1)

		{

			$search_key = $key1;

			if(is_int($search_key))

			{

				break;

			}

		}
		
		// ---- Custom Menu widget setting---		

		$nav_menu = array();

		$nav_menu[1] = array(

			"title"		=>	'',
			"nav_menu"		=>	'shortcodes',
			

		);	

		$nav_menu['_multiwidget'] = '1';

		update_option('widget_nav_menu',$nav_menu);

		$nav_menu = get_option('widget_nav_menu');

		krsort($nav_menu);

		foreach($nav_menu as $key1=>$val1)

		{

			$nav_menu_key = $key1;

			if(is_int($nav_menu_key))

			{

				break;

			}

		}
		
		
		// --- facebook widget setting-----

		$px_widget_facebook = array();

		$px_widget_facebook[1] = array(

		"title"		=>	'Follow on Facebook',

		"pageurl" 	=>	"https://www.facebook.com/envato",

		"showfaces" => "on",

		"likebox_height" => "385",

		"fb_bg_color" =>"#fff",

		);						

		$px_widget_facebook['_multiwidget'] = '1';

		update_option('widget_px_widget_facebook',$px_widget_facebook);

		$px_widget_facebook = get_option('widget_px_widget_facebook');

		krsort($px_widget_facebook);

		foreach($px_widget_facebook as $key1=>$val1)

		{

			$px_widget_facebook_key = $key1;

			if(is_int($px_widget_facebook_key))

			{

				break;

			}

		}
	
		
		// --- Points Table widget setting-----
		
		
		/*$px_pointstable = array();

		$px_pointstable [1] = array(

			"title"		=>	'Points Table',
			"select_category"		=>	"2013-14",
			"showcount"		=>	'2',
		);						

		$px_pointstable['_multiwidget'] = '1';

		update_option('widget_px_pointstable',$px_pointstable);

		$px_pointstable = get_option('widget_px_pointstable');

		krsort($px_pointstable);

		foreach($px_pointstable as $key1=>$val1)

		{

			$px_pointstable_key = $key1;

			if(is_int($px_pointstable_key))

			{

				break;

			}

		}*/
		
		// --- Twitter widget setting-----
		
		
		$px_twitter_widget = array();

		$px_twitter_widget [1] = array(

			"title"		=>	'Twitter',
			"username"		=>	"envato",
			"numoftweets"		=>	'3',
		);						

		$px_twitter_widget['_multiwidget'] = '1';

		update_option('widget_px_twitter_widget',$px_twitter_widget);

		$px_twitter_widget = get_option('widget_px_twitter_widget');

		krsort($px_twitter_widget);

		foreach($px_twitter_widget as $key1=>$val1)

		{

			$px_twitter_widget_key = $key1;

			if(is_int($px_twitter_widget_key))

			{

				break;

			}

		}
		$px_twitter_widget2 = array();

		$px_twitter_widget2 [1] = array(

			"title"		=>	'Twitter',
			"username"		=>	"envato",
			"numoftweets"		=>	'3',
		);						

		$px_twitter_widget2['_multiwidget'] = '1';

		update_option('widget_px_twitter_widget',$px_twitter_widget2);

		$px_twitter_widget2 = get_option('widget_px_twitter_widget');

		krsort($px_twitter_widget2);

		foreach($px_twitter_widget2 as $key1=>$val1)

		{

			$px_twitter_widget_key2 = $key1;

			if(is_int($px_twitter_widget_key2))

			{

				break;

			}

		}
		
		// --- Mail chimp widget setting-----

		$px_MailChimp_Widget = array();

		$px_MailChimp_Widget [1] = array(

		"title"		=>	'Newsletter',
		"description"		=>	'New Enterprise Commercial <br/>A Funny Disclaimer ',
		"email_text"		=>	'Enter Your Email',

		);						

		$px_MailChimp_Widget['_multiwidget'] = '1';

		update_option('widget_px_MailChimp_Widget',$px_MailChimp_Widget);

		$px_MailChimp_Widget = get_option('widget_px_MailChimp_Widget');

		krsort($px_MailChimp_Widget);

		foreach($px_MailChimp_Widget as $key1=>$val1)

		{

			$px_MailChimp_Widget_key = $key1;

			if(is_int($px_MailChimp_Widget_key))

			{

				break;

			}

		}

		
		
		// --- text widget setting ---

		$text = array();

		$text[1] = array(

			'title' => 'Hospitality',

			'text' => '',

		);						

		$text['_multiwidget'] = '1';

		update_option('widget_text',$text);

		$text = get_option('widget_text');

		krsort($text);

		foreach($text as $key1=>$val1)

		{

			$text_key = $key1;

			if(is_int($text_key))

			{

				break;

			}

		}

	 	//----text widget for contact info----------

		$text2 = array();

		$text2 = get_option('widget_text');

		$text2[2] = array(
			'title' => ' Contact Info',
			'text' => ' <div class="home-info">

                	<h5>0044 (800) 123 4567 891
					info@kingsclub.com</h5>
									<p>
					Street 2, ABC The Top Tower<br>
					Rapson Place via London Avenue<br>
					London UK, ABC, 1234
					</p>
					<a href="#" class="pix-bgcolr">View Map <i class="fa fa-arrow-right"></i></a>
					</div>',
		);						

		$text2['_multiwidget'] = '1';

		update_option('widget_text',$text2);

		$text2 = get_option('widget_text');

		krsort($text2);

		foreach($text2 as $key1=>$val1)

		{

			$text_key2 = $key1;

			if(is_int($text_key2))

			{

				break;

			}

		}
		
		
		$header_adstext = array();

		$header_adstext = get_option('widget_text');

		$header_adstext[3] = array(
			'title' => '',
			'text' => '[ads no="0"]',
		);						

		$header_adstext['_multiwidget'] = '1';

		update_option('widget_text',$header_adstext);

		$header_adstext = get_option('widget_text');

		krsort($header_adstext);

		foreach($header_adstext as $key1=>$val1)

		{

			$header_adstext_key = $key1;

			if(is_int($header_adstext_key))

			{

				break;

			}

		}
		
		$footer_adstexttt = array();

		$footer_adstexttt = get_option('widget_text');

		$footer_adstexttt[4] = array(
			'title' => '',
			'text' => '[ads no="1"]',
		);						

		$footer_adstexttt['_multiwidget'] = '1';

		update_option('widget_text',$footer_adstexttt);

		$footer_adstexttt = get_option('widget_text');

		krsort($footer_adstexttt);

		foreach($footer_adstexttt as $key1=>$val1)

		{

			$footer_adstexttt_key = $key1;

			if(is_int($footer_adstexttt_key))

			{

				break;

			}

		}
		
		$sidebar_adstext = array();

		$sidebar_adstext = get_option('widget_text');

		$sidebar_adstext[5] = array(
			'title' => '',
			'text' => '[ads no="2"]',
		);						

		$sidebar_adstext['_multiwidget'] = '1';

		update_option('widget_text',$sidebar_adstext);

		$sidebar_adstext = get_option('widget_text');

		krsort($sidebar_adstext);

		foreach($sidebar_adstext as $key1=>$val1)

		{

			$sidebar_adstext_key = $key1;

			if(is_int($sidebar_adstext_key))

			{

				break;

			}

		}
		
		//// --- Fixture Countdown widget setting-----
		
		
		$px_fixture_countdown = array();

		$px_fixture_countdown[1] = array(

			"title"		=>	'Next Match',
			"upcoming_fixtures_cat"		=>	"football",
		);						

		$px_fixture_countdown['_multiwidget'] = '1';

		update_option('widget_px_fixture_countdown',$px_fixture_countdown);

		$px_fixture_countdown = get_option('widget_px_fixture_countdown');

		krsort($px_fixture_countdown);

		foreach($px_fixture_countdown as $key1=>$val1)

		{

			$px_fixture_countdown_key = $key1;

			if(is_int($px_fixture_countdown_key))

			{

				break;

			}

		}
		
		
		
		
		
		
		
		
// 
	// Add widgets in sidebars
	$sidebars_widgets['sidebar-1'] = array("text-$sidebar_adstext_key", "px_gallery-$px_gallery_key", "calendar-$calendar_key", "px_twitter_widget-$px_twitter_widget_key", "px_widget_facebook-$px_widget_facebook_key");
	
	$sidebars_widgets['sidebar-home'] = array("px_fixture_countdown-$px_fixture_countdown_key", "recentposts-$recent_post_widget_key", "text-$sidebar_adstext_key", "px_widget_facebook-$px_widget_facebook_key", "px_gallery-$px_gallery_key");
	
	$sidebars_widgets['footer-widget'] = array("text-$text_key2", "recent-posts-$default_recent_post_widget_key", "categories-$categories_key", "px_mailchimp_widget-$px_MailChimp_Widget_key");
	
	$sidebars_widgets['contact-sidebar'] = array("calendar-$calendar_key", "px_twitter_widget-$px_twitter_widget_key2", "px_widget_facebook-$px_widget_facebook_key", );
	$sidebars_widgets['header-advertisement-widget'] = array("text-$header_adstext_key");
	$sidebars_widgets['footer-advertisement-widget'] = array("text-$footer_adstexttt_key");
	
	/*$sidebars_widgets['sidebar-home'] = array("px_fixture_countdown-$px_fixture_countdown_key", "px_pointstable-$px_pointstable_key", "recentposts-$recent_post_widget_key", "text-$sidebar_adstext_key", "px_widget_facebook-$px_widget_facebook_key", "px_gallery-$px_gallery_key");*/
	
	

	update_option('sidebars_widgets',$sidebars_widgets);  //save widget informations

	}