<?php
global $px_node, $px_count_node, $px_xmlObject,$px_theme_option;
add_action( 'add_meta_boxes', 'px_page_bulider_add' );
add_action( 'add_meta_boxes', 'px_page_options_add' );
function px_page_options_add() {
	add_meta_box( 'id_page_options', 'Page Options', 'px_page_options', 'page', 'normal', 'high' );  
}
function px_page_bulider_add() {
	add_meta_box( 'id_page_builder', 'Page Builder', 'px_page_bulider', 'page', 'normal', 'high' );  
}  

function px_page_bulider( $post ) {
?>
     
     <div class="page-wrap page-opts event-meta-section" style="overflow:hidden; position:relative; height: 705px;">
    	<div class="add-widget">
            <div class="widgets-list">
                
                <a href="javascript:ajaxSubmit('px_pb_blog')">Blog</a>
                 <a href="javascript:ajaxSubmit('px_pb_column')">Column</a>
                <a href="javascript:ajaxSubmit('px_pb_contact')">Contact</a>
                <a href="javascript:ajaxSubmit('px_pb_event')">Fixtures/Events</a>
                <a href="javascript:ajaxSubmit('px_pb_map')">Map</a>
                <a href="javascript:ajaxSubmit('px_pb_pointtable')">Point Table</a>
                <a href="javascript:ajaxSubmit('px_pb_fixtures')">Upcoming Fixtures</a>
                <a href="javascript:ajaxSubmit('px_pb_gallery')">Gallery</a>
                <a href="javascript:ajaxSubmit('px_pb_slider')">Slider</a>
                <a href="javascript:ajaxSubmit('px_pb_gallery_albums')">Gallery Albums</a>
                 <a href="javascript:ajaxSubmit('px_pb_team')">Team</a>
             </div>
        </div>
        <div class="clear"></div>
        <div id="add_page_builder_item">
          <div class="page-show-items">
            <?php
				global $px_node,$px_xmlObject,$px_theme_option; 
				$px_count_node = 0;
				$count_widget = 0;
				$page_title = 'on';
				$header_styles = '';
				$switch_footer_widgets = '';               
                $px_page_bulider = get_post_meta($post->ID, "px_page_builder", true);
				if ( $px_page_bulider <> "" ) {
                   	$px_xmlObject = new stdClass();
					$px_xmlObject = new SimpleXMLElement($px_page_bulider);
						$count_widget = count($px_xmlObject->children())-10;
                        foreach ( $px_xmlObject->children() as $px_node ){
 							if ( $px_node->getName() == "gallery" ) { px_pb_gallery(1); }
							else if ( $px_node->getName() == "slider" ) { px_pb_slider(1); }
							else if ( $px_node->getName() == "gallery_albums" ) { px_pb_gallery_albums(1); }
 							else if ( $px_node->getName() == "blog" ) { px_pb_blog(1); }
 							else if ( $px_node->getName() == "event" ) { px_pb_event(1); }
							else if ( $px_node->getName() == "fixtures" ) { px_pb_fixtures(1); }
 							else if ( $px_node->getName() == "pointtable" ) { px_pb_pointtable(1); }
							else if ( $px_node->getName() == "map" ) { px_pb_map(1); }
 							else if ( $px_node->getName() == "contact" ) { px_pb_contact(1); }
							else if ( $px_node->getName() == "column" ) { px_pb_column(1); }
							else if ( $px_node->getName() == "team" ) { px_pb_team(1); }
                         }
                }
 				if($count_widget<0){ $count_widget = 0;}
			?>
            </div>
            <div id="no_widget" class="placehoder">Page Builder in Empty, Please Select Page Element. <img src="<?php echo get_template_directory_uri()?>/images/admin/bg-arrowup.png" alt="" /></div>
        </div>
		<div id="loading" class="builderload"></div>
         <div class="clear"></div>

        <div class="clear"></div>
    </div>
<div class="clear"></div>
	
    <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/jquery.scrollTo-min.js"></script>
    <script>
		jQuery(function() {
			jQuery( ".page-show-items" ).sortable({
				cancel : 'div div.poped-up'
			});
			//jQuery( "#add_page_builder_item" ).disableSelection();
		});
    </script>
	<script type="text/javascript">
		var count_widget = <?php echo $count_widget; ?>;
        function ajaxSubmit(action){
  			counter++;
			count_widget++;
            var newCustomerForm = "action=" + action + '&counter=' + counter;
            jQuery.ajax({
                type:"POST",
                url: "<?php echo admin_url('admin-ajax.php');?>",
                data: newCustomerForm,
                success:function(data){
                    jQuery(".page-show-items").append(data);
					if (count_widget > 0) jQuery("#add_page_builder_item").addClass('hasclass');
					//alert(count_widget);
                }
            });
            //return false;
        }
    </script>
<?php  
}
 
function px_page_options( $post ) {
	global $px_xmlObject,$px_theme_option;
	$page_title = 'on';
	$page_content = 'on';
 	$px_page_bulider = get_post_meta($post->ID, "px_page_builder", true);
	if ( $px_page_bulider <> "" ) {
		$px_xmlObject = new stdClass();
		$px_xmlObject = new SimpleXMLElement($px_page_bulider);
		if ( isset($px_xmlObject->page_title) ) $page_title = $px_xmlObject->page_title;
		if ( isset($px_xmlObject->page_content) ) $page_content = $px_xmlObject->page_content;
	
	}
	?>
    <div class="page-wrap page-opts event-meta-section" style="overflow:hidden; position:relative; height: 705px;">
    <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/bootstrap-3.0.js"></script>
	
         <div class="clear"></div>
           <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/select.js"></script>
           <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/bootstrap.min.css">
           <div class="option-sec" style="margin-bottom:0;">
             <div class="opt-conts">
            <div class="elementhidden">
            	<ul class="form-elements  on-off-options noborder">
                	<li class="to-label"><label>Page Title</label></li>
                    <li class="to-field">
                    	<label class="cs-on-off">
                            <input type="checkbox" name="page_title" value="on" class="myClass" <?php if($page_title=='on')echo "checked"?> />
                            <span></span>
                        </label>
                    </li>
                    <li class="to-label"><label>Rich editor description</label></li>
                    <li class="to-field">
                    	<label class="cs-on-off">
                            <input type="checkbox" name="page_content" value="on" class="myClass" <?php if($page_content=='on')echo "checked"?> />
                            <span></span>
                        </label>
                    </li>
                </ul>
                
            </div>
		<?php meta_layout() ?>
        <input type="hidden" name="page_builder_form" value="1" />
        <div class="clear"></div>
    </div>
<div class="clear"></div>
</div>
</div>
<?php  
}
	if ( isset($_POST['page_builder_form']) and $_POST['page_builder_form'] == 1 ) {
		add_action( 'save_post', 'save_page_builder' );
		function save_page_builder( $post_id ) {
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
				if ( isset($_POST['px_orderby']) ) {
					if ( empty($_POST["page_title"]) ) $_POST["page_title"] = "";
     				if ( empty($_POST["page_content"]) ) $_POST["page_content"] = "";
					//creating xml page builder start
					$sxe = new SimpleXMLElement("<pagebuilder></pagebuilder>");
						$sxe->addChild('page_title', $_POST['page_title']);
						$sxe->addChild('page_content', $_POST['page_content']);
						$sxe = save_layout_xml($sxe);
							if ( isset($_POST['px_orderby']) ) {
								$px_counter = 0;
								$px_counter_gal = 0;
								$px_counter_slider = 0;
								$px_counter_event = 0;
								$px_counter_fixtures = 0;
 								$px_counter_blog = 0;
								$px_counter_contact = 0;
 								$px_counter_column = 0;
								$px_counter_map = 0;
								$px_counter_team = 0;
 								$px_counter_pointtable = 0;
								$counter_gal_album = 0;
 								foreach ( $_POST['px_orderby'] as $count ){
									if ( $_POST['px_orderby'][$px_counter] == "gallery" ) {
										$gallery = $sxe->addChild('gallery');
										$gallery->addChild('header_title', $_POST['px_gal_header_title'][$px_counter_gal] );
										$gallery->addChild('layout', $_POST['px_gal_layout'][$px_counter_gal] );
										$gallery->addChild('album', $_POST['px_gal_album'][$px_counter_gal] );
										$gallery->addChild('pagination', $_POST['px_gal_pagination'][$px_counter_gal] );
										$gallery->addChild('media_per_page', $_POST['px_gal_media_per_page'][$px_counter_gal] );
										$gallery->addChild('gallery_element_size', $_POST['gallery_element_size'][$px_counter_gal] );
										$px_counter_gal++;
									}else if ( $_POST['px_orderby'][$px_counter] == "slider" ) {
										$slider = $sxe->addChild('slider');
										$slider->addChild('slider_header_title', $_POST['px_slider_header_title'][$px_counter_slider] );
										$slider->addChild('slider', $_POST['px_slider'][$px_counter_slider] );
										$slider->addChild('slider_element_size', $_POST['slider_element_size'][$px_counter_slider] );
										$px_counter_slider++;
									}else if ( $_POST['px_orderby'][$px_counter] == "gallery_albums" ) {
										$gallery_album = $sxe->addChild('gallery_albums');
											$gallery_album->addChild('px_gal_album_header_title', $_POST['px_gal_album_header_title'][$counter_gal_album] );
											$gallery_album->addChild('px_gal_album_cat', $_POST['px_gal_album_cat'][$counter_gal_album] );
											$gallery_album->addChild('px_gal_album_pagination', $_POST['px_gal_album_pagination'][$counter_gal_album] );
											$gallery_album->addChild('px_gal_album_media_per_page', $_POST['px_gal_album_media_per_page'][$counter_gal_album] );
											$gallery_album->addChild('gallery_albums_element_size', $_POST['gallery_albums_element_size'][$counter_gal_album] );
										$counter_gal_album++;
									}else if ( $_POST['px_orderby'][$px_counter] == "pointtable" ) {
										$pointtable = $sxe->addChild('pointtable');
										$pointtable->addChild('var_pb_pointtable_title', htmlspecialchars($_POST['var_pb_pointtable_title'][$px_counter_pointtable]) );
										$pointtable->addChild('var_pb_pointtable_cat', $_POST['var_pb_pointtable_cat'][$px_counter_pointtable] );
 										$pointtable->addChild('var_pb_pointtable_filterable', $_POST['var_pb_pointtable_filterable'][$px_counter_pointtable] );
 										$pointtable->addChild('var_pb_pointtable_per_page', $_POST['var_pb_pointtable_per_page'][$px_counter_pointtable] );
										$pointtable->addChild('pointtable_element_size', $_POST['pointtable_element_size'][$px_counter_pointtable] );
 										$px_counter_pointtable++;
									
									}else if ( $_POST['px_orderby'][$px_counter] == "blog" ) {
										$blog = $sxe->addChild('blog');
										$blog->addChild('var_pb_blog_title', htmlspecialchars($_POST['var_pb_blog_title'][$px_counter_blog]) );
 										$blog->addChild('var_pb_featured_cat', $_POST['var_pb_featured_cat'][$px_counter_blog]);
										$blog->addChild('var_pb_blog_view', $_POST['var_pb_blog_view'][$px_counter_blog]);
 										$blog->addChild('var_pb_blog_cat', $_POST['var_pb_blog_cat'][$px_counter_blog] );
										$blog->addChild('var_pb_blog_order', $_POST['var_pb_blog_order'][$px_counter_blog] );
										$blog->addChild('var_pb_blog_featured_post', $_POST['var_pb_blog_featured_post'][$px_counter_blog] );
										$blog->addChild('var_pb_blog_excerpt', $_POST['var_pb_blog_excerpt'][$px_counter_blog] );
 										$blog->addChild('var_pb_blog_num_post', $_POST['var_pb_blog_num_post'][$px_counter_blog] );
 										$blog->addChild('var_pb_blog_pagination', $_POST['var_pb_blog_pagination'][$px_counter_blog] );
 										$blog->addChild('blog_element_size', $_POST['blog_element_size'][$px_counter_blog] );
										$px_counter_blog++;
									} else if ( $_POST['px_orderby'][$px_counter] == "map" ) {
										$divider = $sxe->addChild('map');
											$divider->addChild('map_element_size', htmlspecialchars($_POST['map_element_size'][$px_counter_map]) );
											$divider->addChild('map_title', htmlspecialchars($_POST['map_title'][$px_counter_map]) );
											$divider->addChild('map_height', htmlspecialchars($_POST['map_height'][$px_counter_map]) );
											$divider->addChild('map_lat', htmlspecialchars($_POST['map_lat'][$px_counter_map]) );
											$divider->addChild('map_lon', htmlspecialchars($_POST['map_lon'][$px_counter_map]) );
											$divider->addChild('map_zoom', htmlspecialchars($_POST['map_zoom'][$px_counter_map]) );
											$divider->addChild('map_type', htmlspecialchars($_POST['map_type'][$px_counter_map]) );
											$divider->addChild('map_info', $_POST['map_info'][$px_counter_map] );
											$divider->addChild('map_info_width', $_POST['map_info_width'][$px_counter_map] );
											$divider->addChild('map_info_height', $_POST['map_info_height'][$px_counter_map] );
											$divider->addChild('map_marker_icon', $_POST['map_marker_icon'][$px_counter_map] );
											$divider->addChild('map_show_marker', $_POST['map_show_marker'][$px_counter_map] );
											$divider->addChild('map_controls', $_POST['map_controls'][$px_counter_map] );
											$divider->addChild('map_draggable', htmlspecialchars($_POST['map_draggable'][$px_counter_map]) );
											$divider->addChild('map_scrollwheel', htmlspecialchars($_POST['map_scrollwheel'][$px_counter_map]) );
											$divider->addChild('map_text', htmlspecialchars($_POST['map_text'][$px_counter_map]) );
										$px_counter_map++;
									}else if ( $_POST['px_orderby'][$px_counter] == "event" ) {
										$event = $sxe->addChild('event');
										$event->addChild('var_pb_event_title', htmlspecialchars($_POST['var_pb_event_title'][$px_counter_event]) );
										$event->addChild('var_pb_featured_post', $_POST['var_pb_featured_post'][$px_counter_event] );
										$event->addChild('var_pb_featuredevent_title', $_POST['var_pb_featuredevent_title'][$px_counter_event] );
										$event->addChild('var_pb_event_type', $_POST['var_pb_event_type'][$px_counter_event] );
										$event->addChild('var_pb_event_order', $_POST['var_pb_event_order'][$px_counter_event] );
										$event->addChild('var_pb_event_category', $_POST['var_pb_event_category'][$px_counter_event] );
										$event->addChild('var_pb_event_pagination', $_POST['var_pb_event_pagination'][$px_counter_event] );
										$event->addChild('event_element_size', $_POST['event_element_size'][$px_counter_event] );
										$event->addChild('var_pb_event_filterable', $_POST['var_pb_event_filterable'][$px_counter_event] );
										$event->addChild('var_pb_event_monthly', $_POST['var_pb_event_monthly'][$px_counter_event] );
										$event->addChild('var_pb_event_per_page', $_POST['var_pb_event_per_page'][$px_counter_event] );
										$px_counter_event++;
									}else if ( $_POST['px_orderby'][$px_counter] == "fixtures" ) {
										$fixtures = $sxe->addChild('fixtures');
										$fixtures->addChild('fixtures_element_size', htmlspecialchars($_POST['fixtures_element_size'][$px_counter_fixtures]) );
										$fixtures->addChild('var_pb_fixtures_title', htmlspecialchars($_POST['var_pb_fixtures_title'][$px_counter_fixtures]) );
										$fixtures->addChild('var_pb_fixtures_cat', $_POST['var_pb_fixtures_cat'][$px_counter_fixtures] );
										$fixtures->addChild('var_pb_fixtures_view', $_POST['var_pb_fixtures_view'][$px_counter_fixtures] );
										$fixtures->addChild('var_pb_fixtures_per_page', $_POST['var_pb_fixtures_per_page'][$px_counter_fixtures] );
 										$fixtures->addChild('var_pb_fixtures_viewall_title', $_POST['var_pb_fixtures_viewall_title'][$px_counter_fixtures] );
										$fixtures->addChild('var_pb_fixtures_viewall_link', $_POST['var_pb_fixtures_viewall_link'][$px_counter_fixtures] );
										$px_counter_fixtures++;
									}else if ( $_POST['px_orderby'][$px_counter] == "contact" ) {
										$contact = $sxe->addChild('contact');
										$contact->addChild('px_contact_form_title', $_POST['px_contact_form_title'][$px_counter_contact] );
										$contact->addChild('px_contact_email', $_POST['px_contact_email'][$px_counter_contact] );
										$contact->addChild('px_contact_succ_msg', $_POST['px_contact_succ_msg'][$px_counter_contact] );
										$contact->addChild('contact_element_size', $_POST['contact_element_size'][$px_counter_contact] );
										$px_counter_contact++;
									
									}else if ( $_POST['px_orderby'][$px_counter] == "column" ) {
										$column = $sxe->addChild('column');
										$column->addChild('column_element_size', htmlspecialchars($_POST['column_element_size'][$px_counter_column]) );
										$column->addChild('column_text', htmlspecialchars($_POST['column_text'][$px_counter_column]) );
										$px_counter_column++;
									
									}else if ( $_POST['px_orderby'][$px_counter] == "team" ) {
										$team = $sxe->addChild('team');
 											$team->addChild('team_title', htmlspecialchars($_POST['team_title'][$px_counter_team]) );
											$team->addChild('team_pagination', $_POST['team_pagination'][$px_counter_team] );
 											$team->addChild('var_pb_team_cat', $_POST["var_pb_team_multicat"][$px_counter_team]);
 											$team->addChild('team_page_num', $_POST['team_page_num'][$px_counter_team] );
											$team->addChild('team_view', $_POST['team_view'][$px_counter_team] );
											$team->addChild('team_orderby', $_POST['team_orderby'][$px_counter_team] );
											$team->addChild('team_expertise', $_POST['team_expertise'][$px_counter_team] );
											$team->addChild('team_element_size', $_POST['team_element_size'][$px_counter_team] );
 										$px_counter_team++;
										
  									}
									$px_counter++;
								}
							}
 							update_post_meta( $post_id, 'px_page_builder', $sxe->asXML() );
					//creating xml page builder end
				}
		}
	}
?>