<?php
// event start
	//adding columns start
    add_filter('manage_events_posts_columns', 'event_columns_add' );
		function event_columns_add($columns) {
			$columns['category'] = 'Categories';
			$columns['author'] = 'Author';
			$columns['tag'] = 'Tags';
			return $columns;
	    }
    add_action('manage_events_posts_custom_column', 'event_columns');
		function event_columns($name) {
			global $post;
			switch ($name) {
				case 'category':
					$categories = get_the_terms( $post->ID, 'event-category' );
						if($categories <> ""){
							$couter_comma = 0;
							foreach ( $categories as $category ) {
								echo $category->name;
								$couter_comma++;
								if ( $couter_comma < count($categories) ) {
									echo ", ";
								}
							}
						}
					break;
				case 'author':
					echo get_the_author();
					break;
				case 'tag':
					$categories = get_the_terms( $post->ID, 'event-tag' );
						if($categories <> ""){
							$couter_comma = 0;
							foreach ( $categories as $category ) {
								echo $category->name;
								$couter_comma++;
								if ( $couter_comma < count($categories) ) {
									echo ", ";
								}
							}
						}
					break;
			}
		}
	//adding columns end
	
	function px_event_register() {  
		$labels = array(
			'name' => 'Fixtures',
			'add_new_item' => 'Add New Fixture',
			'edit_item' => 'Edit Fixture',
			'new_item' => 'New Fixture Item',
			'add_new' => 'Add New Fixture',
			'view_item' => 'View Fixture Item',
			'search_items' => 'Search Fixture',
			'not_found' => 'Nothing found',
			'not_found_in_trash' => 'Nothing found in Trash',
			'parent_item_colon' => ''
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-calendar',
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'has_archive' => true,
			'supports' => array('title','editor','thumbnail', 'excerpt', 'comments')
		); 
        register_post_type( 'events' , $args );  

			// adding Manage Location start
				$labels = array(
					'name' => 'Locations',
					'add_new_item' => 'Add New Location (Venue Title)',
					'edit_item' => 'Edit Location',
					'new_item' => 'New Location Item',
					'add_new' => 'Add New Location',
					'view_item' => 'View Location Item',
					'search_items' => 'Search Location',
					'not_found' => 'Nothing found',
					'not_found_in_trash' => 'Nothing found in Trash',
					'parent_item_colon' => ''
				);
				$args = array(
					'labels' => $labels,
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'query_var' => true,
					'menu_icon' => get_template_directory_uri() . '/images/calendar.png',
					'show_in_menu' => 'edit.php?post_type=events',
					'show_in_nav_menus'=>true,
					'rewrite' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'menu_position' => null,
					'supports' => array('title')
				); 
				register_post_type( 'event-location' , $args );  
			// adding Manage Location end
    }
	add_action('init', 'px_event_register');

	function px_event_categories() 
	{
		  $labels = array(
			'name' => 'Fixture Categories',
			'search_items' => 'Search Fixture Categories',
			'edit_item' => 'Edit Fixture Category',
			'update_item' => 'Update Fixture Category',
			'add_new_item' => 'Add New Category',
			'menu_name' => 'Fixture Categories',
		  ); 	
		  register_taxonomy('event-category',array('events'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'event-category' ),
		  ));
	}
	add_action( 'init', 'px_event_categories');

	function px_event_tag() {
		  $labels = array(
			'name' => 'Event Tags',
			'singular_name' => 'event-tag',
			'search_items' => 'Search Tags',
			'popular_items' => 'Popular Tags',
			'all_items' => 'All Tags',
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => 'Edit Tag',
			'update_item' => 'Update Tag',
			'add_new_item' => 'Add New Tag',
			'new_item_name' => 'New Tag Name',
			'separate_items_with_commas' => 'Separate writers with commas',
			'add_or_remove_items' => 'Add or remove tags',
			'choose_from_most_used' => 'Choose from the most used tags',
			'menu_name' => 'Event Tags',
		  ); 
		  register_taxonomy('event-tag','events',array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'event-tag' ),
		  ));
	}
	add_action( 'init', 'px_event_tag');
 
 	// event-location custom fields end

	// event custom fields start
	add_action( 'add_meta_boxes', 'px_event_meta' );  
    function px_event_meta()
    {
        add_meta_box( 'event_meta', 'Event Options', 'px_event_meta_data', 'events', 'normal', 'high' );
    }
	function px_event_meta_data($post) {
		$px_event_meta = get_post_meta($post->ID, "px_event_meta", true);
		global $px_xmlObject;
		if ( $px_event_meta <> "" ) {
			$px_xmlObject = new SimpleXMLElement($px_event_meta);
 				$event_social_sharing = $px_xmlObject->event_social_sharing;
				$event_time = $px_xmlObject->event_time;
 				$event_all_day = $px_xmlObject->event_all_day;
				$event_address = $px_xmlObject->event_address;
				$event_gallery = $px_xmlObject->event_gallery;
 				$event_buy_now = $px_xmlObject->event_buy_now;
				$event_ticket_price = $px_xmlObject->event_ticket_price;
 				$event_ticket_options = $px_xmlObject->event_ticket_options;
				$var_pb_event_author = $px_xmlObject->var_pb_event_author;
				$var_pb_event_team1 = $px_xmlObject->var_pb_event_team1;
				$var_pb_event_team2 = $px_xmlObject->var_pb_event_team2;
 				$event_ticket_color = $px_xmlObject->event_ticket_color;
				$event_summary =$px_xmlObject->event_summary;
				$event_venue =$px_xmlObject->event_venue;
				$var_pb_event_team1=$px_xmlObject->var_pb_event_team1;
				$var_pb_event_team2=$px_xmlObject->var_pb_event_team2;
				$event_score=$px_xmlObject->event_score;
				$event_time_title = $px_xmlObject->event_time_title;
 				
		}
		else {
 			$event_social_sharing = '';
			$event_related = '';
			$event_time = '';
 			$event_all_day = '';
			$event_address = '';
			$event_loc_lat = '';
			$event_loc_long = '';
			$event_loc_zoom = '';
 			$event_ticket_price = '';
			$event_buy_now = '';
			$event_ticket_options = '';
			$var_pb_event_author = '';
			$event_ticket_color = '';
			$event_summary ='';
			$event_venue ='';
			$var_pb_event_team1 ='';
			$var_pb_event_team2 ='';
			$event_score ='';
			$event_gallery = '';
			$event_time_title = 'KICK-OFF';
			
 		}
		$px_event_from_date = get_post_meta($post->ID, "px_event_from_date", true);
	
	?>
    	
    	<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/bootstrap.min.css">
		<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/select.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/bootstrap-3.0.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/bootstrap-datepicker.js"></script>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/datepicker.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/bootstrap-timepicker.min.css">
        <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/bootstrap-timepicker.js"></script>
        <script>
		
		jQuery(function($) {
					jQuery( "#event_start_time, #event_end_time" ).click(function(event) {
						jQuery( this ).prev( "div" ).show();
						event.stopPropagation();
					});
					jQuery( "html" ).click(function() {
						jQuery( '.bootstrap-timepicker-widget' ).hide();
					});
					
					jQuery('#event_start_time').timepicker({showInputs: false,disableFocus: true});
					jQuery('#event_end_time').timepicker({
									showInputs: false,
									disableFocus: true,
									//modalBackdrop: true,
								//	showMeridian: false
					});
					    var nowTemp = new Date();
						var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
						 $('#from_date').datepicker();
						/*var checkin = $('#from_date').datepicker({
						onRender: function(date) {
						return date.valueOf() < now.valueOf() ? 'disabled' : '';
						}
						}).on('changeDate', function(ev) {
						if (ev.date.valueOf() > checkout.date.valueOf()) {
						var newDate = new Date(ev.date)
						newDate.setDate(newDate.getDate());
						checkout.setValue(newDate);
						}
						checkin.hide();
						$('#to_date')[0].focus();
						}).data('datepicker');*/
						var checkout = $('#to_date').datepicker({
						onRender: function(date) {
						return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
						}
						}).on('changeDate', function(ev) {
						checkout.hide();
						}).data('datepicker');
				});
        </script>
    	<div class="page-wrap  event-meta-section">
            <div class="option-sec" style="margin-bottom:0;">
                <div class="opt-conts">
               		<div class="opt-head">
                      <h4></h4>
                      <div class="clear"></div>
                    </div>
                    <ul class="form-elements noborder">
                         <li class="to-field">
                           <select name="var_pb_event_team1" class="dropdown">
                        		<option value="0">-- Select Team--</option>
                            	<?php show_all_cats('', '', $var_pb_event_team1, "team-category");?>
                        	</select>
                           <p>Team 1</p>
                        </li>
                    </ul>    
                    <ul class="form-elements noborder">    
                         <li class="to-field">
                          <select name="var_pb_event_team2" class="dropdown">
                        	<option value="0">-- Select Team--</option>
                            <?php show_all_cats('', '', $var_pb_event_team2, "team-category");?>
                        </select>
                         <p>Team 2</p>
                        </li>
                    </ul>
                    <ul class="form-elements  time-page-options noborder">
                            <li class="to-field">
                            	<div data-date-minviewmode="months" data-date-viewmode="years" data-date-format="mm/yyyy" data-date="102/2012" id="dpMonths" class="input-append date">
                                <input type="text" id="from_date"  class="span2 icon-calendar" name="event_from_date" value="<?php if($px_event_from_date=='') echo gmdate("Y-m-d"); else echo $px_event_from_date?>" /><span class="add-on"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                                <p>Date</p>
                            </li>
                        </ul>
                        <ul class="form-elements  time-page-options noborder">
                        	 <li class="to-field">
                                <input type="text" id="event_time_title" name="event_time_title" value="<?php echo $event_time_title;?>" />
                                <p>Start Time Title (KICK-OFF)</p>
                            </li>
                            <li class="to-field add-on">
                            	
                                <div class="input-append bootstrap-timepicker">
                                    <input id="event_start_time" name="event_time"  data-format="hh:mm:ss" value="<?php echo $event_time?>" type="text" class="vsmall glyphicon glyphicon-time" />
                                    <span class="add-on"><i class="glyphicon glyphicon-time"></i></span>
                                </div>
                                <p>Start Time (KICK-OFF)</p>
                            </li>
                            <li  class="event-or label-to">Or</li>
                            <li class="event-all to-field">
                            	<div class="checkbox-list">
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="event_all_day" value="on" <?php if($event_all_day=='on')echo "checked"?> onclick="px_toggle('event_time')" class="styled" />
                                        <label>All Day</label>
                                    </div>
                                </div>
                            </li>
                            
                    </ul>
                    <div class="opt-head">
                      <h4>Results and Summary</h4>
                      <div class="clear"></div>
                    </div>
                    <ul class="form-elements noborder">
                        <li class="to-field">
                            <input type="text" id="event_score" name="event_score" value="<?php echo $event_score;?>" />
                            <p>Fixture Scores.</p>
                        </li>
                    </ul>
                    <ul class="form-elements noborder">
                        <li class="to-field">
                           <?php 
							wp_editor( $event_summary, 'event_summary', array('textarea_name' => 'event_summary',
							'editor_class' => 'cs-fixture-summary',
							'media_buttons' => true,
							'teeny' => false,
							'textarea_rows' => 20) ); 
							?>
                             <p>Fixture Summary.</p>
                        </li>
                    </ul>
                    <div class="opt-head">
                      <h4>Ticket / Store Options</h4>
                      <div class="clear"></div>
                    </div>
                    <ul class="form-elements">
                        
                        <li class="to-field">
                        	<input type="text" id="event_ticket_options" name="event_ticket_options" value="<?php echo htmlspecialchars($event_ticket_options);?>" />
                            <p>Please enter ticket button text. e.g: Buy Now,Free,Cancelled,Full Booked</p>
                        </li>
                   </ul>
                    <ul class="form-elements noborder">
                        <li class="to-field">
                            <input type="text" id="event_buy_now" name="event_buy_now" value="<?php echo $event_buy_now;?>" />
                            <p>Buy Now URL.</p>
                        </li>
                    </ul>
                    <ul class="form-elements noborder">
                      <li class="to-field">
                        <input type="text" name="event_ticket_color" value="<?php echo $event_ticket_color?>" class="bg_color" />
                        <p>Tickets Button color</p>
                      </li>
                    </ul>
                     <div class="opt-head">
                      <h4>Other options</h4>
                      <div class="clear"></div>
                    </div>
                     <ul class="form-elements noborder">
                     	<li class="to-field">
                         	<select name="event_venue" class="dropdown" >
	                          	<option value="0">Select Venue</option>
 	                            <option <?php if($event_venue=='home')echo "selected"?> value="home">Home</option>
                                <option <?php if($event_venue=='away')echo "selected"?> value="away">Away</option>
                                <option <?php if($event_venue=='neutral')echo "selected"?> value="neutral">Neutral</option>
                        	</select>
                        </li>
                    </ul>
                    <ul class="form-elements noborder">
                     	<li class="to-field">
                        	
                        	<select name="event_address" class="dropdown" >
                            	<option value="0">Select Location</option>
                                <?php
									query_posts( array('posts_per_page' => "-1", 'post_status' => 'publish', 'post_type' => 'event-location') );
										while ( have_posts()) : the_post();
										?>
	                                        <option <?php if($event_address==get_the_title())echo "selected"?> value="<?php echo get_the_title();?>"><?php the_title()?></option>
                                        <?php
										endwhile;
                                ?>
                            </select>
                        </li>
                    </ul>
                    <ul class="form-elements noborder">
                    	<li class="to-field">
                                    <select name="event_gallery" class="dropdown">
                                    	<option value="">Select Gallery</option>
                                         <?php
                                            global $post;
                                            $query = array( 'posts_per_page' => '-1', 'post_type' => 'px_gallery', 'orderby'=>'ID', 'post_status' => 'publish' );
                                            $wp_query = new WP_Query($query);
                                            while ($wp_query->have_posts()) : $wp_query->the_post();
                                        ?>
                                        <option <?php if($post->post_name==$event_gallery)echo "selected";?> value="<?php echo $post->post_name; ?>">
										<?php the_title()?></option>
                                        <?php
                                            endwhile;
                                            wp_reset_query();
                                        ?>
                                    </select>
                                <p>Choose Gallery.Create new Gallery from <a style="color:#06F; text-decoration:underline;" href="<?php echo get_site_url(); ?>/wp-admin/post-new.php?post_type=px_gallery">here</a></p>
                            </li>
                    </ul>
                    <ul class="form-elements on-off-options noborder">
                        <li class="to-label"><label>Social Sharing</label></li>
                        <li class="to-field">
                        	<label class="cs-on-off">
                                <input type="checkbox" name="event_social_sharing" value="on" class="myClass" <?php if($event_social_sharing=='on')echo "checked"?> />
                                <span></span>
                            </label>
                        </li>
                        
                        <li class="to-label"><label>Author Description</label></li>
                            <li class="to-field">
                                <label class="cs-on-off">
                                	<input type="checkbox" name="var_pb_event_author" value="on" class="myClass" <?php if($var_pb_event_author=='on')echo "checked"?> />
                                	<span></span>
                                </label>
                            </li>
                    </ul>
                      <?php meta_layout()?>
                      
                </div>
                
            </div>
            
            <input type="hidden" name="event_meta_form" value="1" />
			<div class="clear"></div>
		</div>
    
    <?php
	}
	// event custom fields end
	// event-location custom fields save start
		if ( isset($_POST['event_loc_meta_form']) and $_POST['event_loc_meta_form'] == 1 ) {
			add_action( 'save_post', 'event_loc_meta_save' );
			function event_loc_meta_save( $post_id ) {
 				
				$sxe = new SimpleXMLElement("<event_loc></event_loc>");
 						update_post_meta( $post_id, 'px_event_loc_meta', $sxe->asXML() );
			}
		}
	// event-location custom fields save end
	// event custom fields save start
		if ( isset($_POST['event_meta_form']) and $_POST['event_meta_form'] == 1 ) {
			add_action( 'save_post', 'px_event_meta_save' );
			function px_event_meta_save( $post_id ) {
					date_default_timezone_set('UTC');				

					events_meta_save($post_id);
					update_post_meta( $post_id, 'px_event_from_date', $_POST["event_from_date"] );
					$px_event_datetime = strtotime($_POST["event_from_date"].''.$_POST["event_time"]);
					update_post_meta( $post_id, 'px_event_from_date_time', $px_event_datetime);
				
			}
		}
	// event custom fields save end
	
// event end
?>