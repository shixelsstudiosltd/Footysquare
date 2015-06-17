<?php
	// Gallery start
		//adding columns start
		add_filter('manage_px_gallery_posts_columns', 'gallery_columns_add');
			function gallery_columns_add($columns) {
				$columns['author'] = 'Author';
				return $columns;
		}
		add_action('manage_px_gallery_posts_custom_column', 'gallery_columns');
			function gallery_columns($name) {
				global $post;
				switch ($name) {
					case 'author':
						echo get_the_author();
						break;
				}
			}
		//adding columns end
	function px_gallery_register() {  
		$labels = array(
			'name' => 'Galleries/Sliders',
			'add_new_item' => 'Add New Gallery/Slider',
			'edit_item' => 'Edit Gallery/Slider',
			'new_item' => 'New Gallery/Slider Item',
			'add_new' => 'Add New Gallery/Slider',
			'view_item' => 'View Gallery/Slider Item',
			'search_items' => 'Search Gallery/Slider',
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
			'menu_icon' => 'dashicons-format-gallery',
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title', 'thumbnail')
		); 
        register_post_type( 'px_gallery' , $args );
	}
	add_action('init', 'px_gallery_register');
	function cs_gallery_categories() 
	{
		  $labels = array(
			'name' => 'Gallery Albums',
			'search_items' => 'Search Gallery Albums',
			'edit_item' => 'Edit Gallery Album',
			'update_item' => 'Update Gallery Album',
			'add_new_item' => 'Add New Album',
			'menu_name' => 'Gallery Albums',
		  ); 	
		  register_taxonomy('px_gallery-category',array('px_gallery'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'px_gallery-category' ),
		  ));
	}
	add_action( 'init', 'cs_gallery_categories');

		// adding Gallery meta info start
			add_action( 'add_meta_boxes', 'px_meta_gallery_add' );
			function px_meta_gallery_add()
			{  
				add_meta_box( 'px_meta_gallery', 'Gallery Options', 'px_meta_gallery', 'px_gallery', 'normal', 'high' );  
			}
			function px_meta_gallery( $post ) {
				?>
					<div class="page-wrap" style="overflow:hidden;">
					<div class="option-sec">
                            <div class="opt-conts-in">
                                <div class="to-social-network">
                                    <div class="gal-active">
                                        <div class="clear"></div>
                                        <div class="dragareamain">
                                        <div class="placehoder">Gallery is Empty. Please Select Media <img src="<?php echo get_template_directory_uri()?>/images/admin/bg-arrowdown.png" alt="" /></div>
										<ul id="gal-sortable">
											<?php 
												global $px_node, $px_counter;
												$px_counter_gal = 0;
                                                $px_meta_gallery_options = get_post_meta($post->ID, "px_meta_gallery_options", true);
                                                if ( $px_meta_gallery_options <> "" ) {
                                                    $px_xmlObject = new SimpleXMLElement($px_meta_gallery_options);
                                                        foreach ( $px_xmlObject->children() as $px_node ){
                                                            $px_counter_gal++;
                                                            $px_counter = $post->ID.$px_counter_gal;
															px_gallery_caption();
                                                        }
                                                }
                                            ?>
                                        </ul>
                                        </div>
                                    </div>
                                    <div class="to-social-list">
                                        <div class="soc-head">
                                            <h5>Select Media</h5>
                                            <div class="right">
                                                <label><input type="button" class="button reload" value="Reload" onclick="refresh_media()" /></label>
                                                <input id="px_log" name="px_logo" type="button" class="uploadfile button" value="Upload Media" />
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                        <script type="text/javascript">
											function show_next(page_id, total_pages){
												var dataString = 'action=media_pagination&page_id='+page_id+'&total_pages='+total_pages;
												jQuery("#pagination").html("<img src='<?php echo get_template_directory_uri()?>/images/admin/ajax_loading.gif' />");
												jQuery.ajax({
													type:'POST', 
													url: "<?php echo admin_url('admin-ajax.php');?>",
													data: dataString,
													success: function(response) {
														jQuery("#pagination").html(response);
													}
												});
											}
											function refresh_media(){
												var dataString = 'action=media_pagination';
												jQuery("#pagination").html("<img src='<?php echo get_template_directory_uri()?>/images/admin/ajax_loading.gif' />");
												jQuery.ajax({
													type:'POST', 
													url: "<?php echo admin_url('admin-ajax.php');?>",
													data: dataString,
													success: function(response) {
														jQuery("#pagination").html(response);
													}
												});
											}
										</script>
										<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/jquery.scrollTo-min.js"></script>
                                        <script>
                                            jQuery(document).ready(function($) {
                                                $("#gal-sortable").sortable({
                                                    cancel:'li div.poped-up',
                                                });
                                               // $(this).append("#gal-sortable").clone() ;
                                                });
                                                var counter = 0;
                                                var count_items = <?php echo $px_counter_gal?>;
                                                if ( count_items > 0 ) {
                                                    jQuery(".dragareamain") .addClass("noborder");	
                                                }
                                                function clone(path){
                                                    counter = counter + 1;
                                                    var dataString = 'path='+path+'&counter='+counter+'&action=px_gallery_caption';
                                                    
                                                    jQuery("#gal-sortable").append("<li id='loading'><img src='<?php echo get_template_directory_uri()?>/images/admin/ajax_loading.gif' /></li>");
                                                    jQuery.ajax({
                                                        type:'POST', 
                                                        url: "<?php echo admin_url('admin-ajax.php');?>",
                                                        data: dataString,
                                                        success: function(response) {
                                                            jQuery("#loading").remove();
                                                            jQuery("#gal-sortable").append(response);
                                                            count_items = jQuery("#gal-sortable li") .length;
                                                                if ( count_items > 0 ) {
                                                                    jQuery(".dragareamain") .addClass("noborder");	
                                                                }
                                                        }
                                                    });
                                                }
                                                function del_this(id){
                                                    jQuery("#"+id).remove();
                                                    count_items = jQuery("#gal-sortable li") .length;
                                                        if ( count_items == 0 ) {
                                                            jQuery(".dragareamain") .removeClass("noborder");	
                                                        }
                                                }
                                        </script>
                                        <script type="text/javascript">
                                         var contheight;
                                              function galedit(id){
                                          var $ = jQuery;
                                          $(".to-social-list,.gal-active h4.left,#gal-sortable li,#gal-sortable .thumb-secs") .not("#"+id) .fadeOut(200);
                                          $.scrollTo( '.page-wrap', 400, {easing:'swing'} );
                                                $('.poped-up').animate({
                                                 top: 0,
                                                }, 300, function() {
                                          $("#edit_" + id+" li")  .show(); 
                                                $("#edit_" + id)   .slideDown(300); 
                                                });
                                               };
                                               function galclose(id){
                                          var $ = jQuery;
                                          $("#edit_" + id) .slideUp(300);
                                          $(".to-social-list,.gal-active h4.left,#gal-sortable li,#gal-sortable .thumb-secs")  .fadeIn(300);
                                          };
                                        
                                        </script>                    
										<div id="pagination"><?php media_pagination();?></div>
	                                    <input type="hidden" name="gallery_meta_form" value="1" />
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php
			}
			// adding Gallery meta info end
			// saving Gallery meta start
			if ( isset($_POST['gallery_meta_form']) and $_POST['gallery_meta_form'] == 1 ) {
				add_action( 'save_post', 'px_meta_gallery_options' );
				function px_meta_gallery_options( $post_id )
				{
					$px_counter = 0;
					$sxe = new SimpleXMLElement("<gallery_options></gallery_options>");
						if ( isset($_POST['path']) ) {
							foreach ( $_POST['path'] as $count ) {
								$gallery = $sxe->addChild('gallery');
									$gallery->addChild('path', $_POST['path'][$px_counter] );
									$gallery->addChild('title', htmlspecialchars($_POST['title'][$px_counter]) );
									$gallery->addChild('use_image_as', $_POST['use_image_as'][$px_counter] );
									$gallery->addChild('video_code', htmlspecialchars($_POST['video_code'][$px_counter]) );
									$gallery->addChild('link_url', htmlspecialchars($_POST['link_url'][$px_counter]) );
									$px_counter++;
							}
						}
					update_post_meta( $post_id, 'px_meta_gallery_options', $sxe->asXML() );
				}
			}
			// saving Gallery meta end
	// Gallery end
?>