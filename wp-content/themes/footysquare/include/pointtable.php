<?php
	//adding columns start
    add_filter('manage_pointtables_posts_columns', 'pointtable_columns_add');
		function pointtable_columns_add($columns) {
			$columns['category'] = 'Category';
			$columns['author'] = 'Author';
			return $columns;
    }
    add_action('manage_pointtables_posts_custom_column', 'pointtables_columns');
		function pointtables_columns($name) {
			global $post;
			switch ($name) {
				case 'category':
					$categories = get_the_terms( $post->ID, 'season-category' );
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
			}
		}
	//adding columns end
	function px_pointtable_register() {
		$labels = array(
			'name' => 'Point Tables',
			'add_new_item' => 'Add New Table',
			'edit_item' => 'Edit Table',
			'new_item' => 'New Table Item',
			'add_new' => 'Add New Table',
			'view_item' => 'View Table Item',
			'search_items' => 'Search Table',
			'not_found' =>  'Nothing found',
			'not_found_in_trash' => 'Nothing found in Trash',
			'parent_item_colon' => ''
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-admin-media',
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title')
		); 
        register_post_type( 'pointtable' , $args );
	}
	add_action('init', 'px_pointtable_register');

		// adding cat start
		  $labels = array(
			'name' => 'Season Categories',
			'search_items' => 'Search Season Categories',
			'edit_item' => 'Edit Season Category',
			'update_item' => 'Update Season Category',
			'add_new_item' => 'Add New Category',
			'menu_name' => 'Season Categories',
		  ); 	
		  register_taxonomy('season-category',array('pointtable'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'season-category' ),
		  ));
		// adding cat end
		
	// adding point table meta info start
		add_action( 'add_meta_boxes', 'px_meta_pointtable_add' );
		function px_meta_pointtable_add()
		{  
			add_meta_box( 'px_meta_pointtable', 'Point Tables Options', 'px_meta_pointtable', 'pointtable', 'normal', 'high' );  
		}
		function px_meta_pointtable( $post ) {
			$px_pointtable = get_post_meta($post->ID, "px_pointtable", true);
			global $px_xmlObject;
			if ( $px_pointtable <> "" ) {
				$px_xmlObject = new SimpleXMLElement($px_pointtable);
				
				$var_pb_record_per_post=$px_xmlObject->var_pb_record_per_post;
				$var_pb_pointtable_viewall = $px_xmlObject->var_pb_pointtable_viewall;
				$var_pb_pointtable_tableheads = $px_xmlObject->var_pb_pointtable_tableheads;
				$px_table_sort_column = $px_xmlObject->px_table_sort_column;
				$point_slug = $post->post_name;
				
    		}else {
   				$var_pb_record_per_post ='';
				$var_pb_pointtable_viewall ='';
				$var_pb_pointtable_tableheads ='';
				$px_table_sort_column = '';
				$point_slug = '';
 			}
?>	
			 <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/jquery.scrollTo-min.js"></script>
             <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/bootstrap.min.css">
             <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/bootstrap-3.0.js"></script>
			<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/select.js"></script>
            <div class="page-wrap page-opts left event-meta-section" style="overflow:hidden; position:relative;">
                  <div class="option-sec" style="margin-bottom:0;">
                    <div class="opt-conts">
                        <ul class="form-elements">
                             <li class="to-field">
                            	<input type="text" id="var_pb_record_per_post" name="var_pb_record_per_post" value="<?php echo $var_pb_record_per_post;?>" />
                                <p>Record Per Post</p>
                            </li>
                            <li class="to-field">
                            	<input type="text" id="var_pb_pointtable_viewall" name="var_pb_pointtable_viewall" value="<?php echo $var_pb_pointtable_viewall;?>" />
                                <p><label>View All URL</label></p>
                            </li>
                           <?php 
						   
						   $px_theme_option = get_option('px_theme_option');
						   if(isset($var_pb_pointtable_tableheads) && $var_pb_pointtable_tableheads == '0'){
								$var_pb_pointtable_tableheads = '0';
							} else if(isset($var_pb_pointtable_tableheads) && $var_pb_pointtable_tableheads <> '0' && $var_pb_pointtable_tableheads <> '')
								$var_pb_pointtable_tableheads = $var_pb_pointtable_tableheads;
							else 
								$var_pb_pointtable_tableheads = '';
						   if(isset($px_xmlObject->track) && count($px_xmlObject->track) > 0 && $var_pb_pointtable_tableheads <> ''){
							   
							   echo '<li class="to-field">
							   <p></p>
							   <p></p>
							   <p></p>
							   <input type="hidden" name="var_pb_pointtable_tableheads" value="'.(int)$var_pb_pointtable_tableheads.'">
							  
							   <input type="text" name="sssnn" readonly="readonly" value="'.$px_theme_option['points_table_title'][(int)$var_pb_pointtable_tableheads].'">
							   <p>Table Heading name. If you want to change table columns heading, Then you have to delete the points.</p>
							   
							   </li>';
						   } else {
                           ?>
                            <li class="to-field">
                            	<select name="var_pb_pointtable_tableheads" id="var_pb_pointtable_tableheads" class="dropdown"  onchange="px_pointtable_heads(this.value,'<?php echo admin_url('admin-ajax.php');?>')">
                                	<option value="">--Select Table heading Name--</option>
                                    <?php 
									
									if ( isset($px_theme_option['points_table_title']) and is_array($px_theme_option['points_table_title']) and count($px_theme_option['points_table_title']) > 0 ) {
										$counter_heading = 0;
										
										
										foreach ( $px_theme_option['points_table_title'] as $val ){
											?>
                                            <option value="<?php echo $counter_heading;?>" <?php if(isset($var_pb_pointtable_tableheads) && $var_pb_pointtable_tableheads <> '' && $var_pb_pointtable_tableheads == $counter_heading){echo 'selected="selected"';}?>><?php echo $val;?></option>
											<?php
											$counter_heading++;
										}
									}
									?>
                                </select>
                                <p><label>Select Name for Table headings</label></p>
                            </li>
                            <?php }?>
                            <?php 
							if(isset($point_slug) && $point_slug <> ''){
								echo ' <li class="to-field"><p></p>
							   <p></p>
							   <p></p>[pointstable table_slug="'.$point_slug.'" ]
								<p>Copy this shortcode to display point table.</p>
								
								</li>';
							}
							
							?>
                        </ul>
                     </div>
					<div class="clear"></div>
                </div>
                  <div class="opt-head">
                        <h4 style="padding-top:12px;">Point Tables</h4>
                        <a href="javascript:openpopedup('add_track')" class="button add-points-table" style="display:<?php if($var_pb_pointtable_tableheads == ''){echo 'none;';} else {echo 'block;';}?>">Add points to table</a>
                        <div class="clear"></div>
                    </div>
                <div class="boxes tracklists">
                	<div id="add_track" class="poped-up">
                        <div class="opt-head">
                            <h5>Point Table Settings</h5>
                            <a href="javascript:closepopedup('add_track')" class="closeit">&nbsp;</a>
                            <div class="clear"></div>
                        </div>
                        <ul class="form-elements" id="pointtbale-points-section">
                        	<?php 
							$px_theme_option = get_option('px_theme_option');
							if(isset($var_pb_pointtable_tableheads) && ($var_pb_pointtable_tableheads <> '' || $var_pb_pointtable_tableheads == 0))
							{
								$count_columns_heading = 0;
								$i = (int)$var_pb_pointtable_tableheads;
								if(isset($px_theme_option['points_table_coloumn_field_1'][$i]) && $px_theme_option['points_table_coloumn_field_1'][$i] <> ''){
									$count_columns_heading++;
								?>
									<li class="to-label"><label><?php echo $px_theme_option['points_table_coloumn_field_1'][$i];?></label></li>
									<li class="to-field">
										<input type="text" id="var_pb_points_table_value1" name="var_pb_points_table_value1" value="" />
									</li>
								<?php }?>
								<?php if(isset($px_theme_option['points_table_coloumn_field_2'][$i]) && $px_theme_option['points_table_coloumn_field_2'][$i] <> ''){
									$count_columns_heading++;
									?>
									<li class="to-label"><label><?php echo $px_theme_option['points_table_coloumn_field_2'][$i];?></label></li>
									<li class="to-field">
										<input type="text" id="var_pb_points_table_value2" name="var_pb_points_table_value2" value="" />
									</li>
								<?php }?>
								<?php if(isset($px_theme_option['points_table_coloumn_field_3'][$i]) && $px_theme_option['points_table_coloumn_field_3'][$i] <> ''){
									$count_columns_heading++;
									?>
									<li class="to-label"><label><?php echo $px_theme_option['points_table_coloumn_field_3'][$i];?></label></li>
									<li class="to-field">
										<input type="text" id="var_pb_points_table_value3" name="var_pb_points_table_value3" value="" />
									</li>
								<?php }?>
								<?php if(isset($px_theme_option['points_table_coloumn_field_4'][$i]) && $px_theme_option['points_table_coloumn_field_4'][$i] <> ''){
									$count_columns_heading++;
									?>
									<li class="to-label"><label><?php echo $px_theme_option['points_table_coloumn_field_4'][$i];?></label></li>
									<li class="to-field">
										<input type="text" id="var_pb_points_table_value4" name="var_pb_points_table_value4" value="" />
									</li>
								<?php }?>
								<?php if(isset($px_theme_option['points_table_coloumn_field_5'][$i]) && $px_theme_option['points_table_coloumn_field_5'][$i] <> ''){
									$count_columns_heading++;
									?>
									<li class="to-label"><label><?php echo $px_theme_option['points_table_coloumn_field_5'][$i];?></label></li>
									<li class="to-field">
										<input type="text" id="var_pb_points_table_value5" name="var_pb_points_table_value5" value="" />
									</li>
								<?php }?>
								<?php if(isset($px_theme_option['points_table_coloumn_field_6'][$i]) && $px_theme_option['points_table_coloumn_field_6'][$i] <> ''){
									$count_columns_heading++;
									?>
									<li class="to-label"><label><?php echo $px_theme_option['points_table_coloumn_field_6'][$i];?></label></li>
									<li class="to-field">
										<input type="text" id="var_pb_points_table_value6" name="var_pb_points_table_value6" value="" />
									</li>
								<?php }?>
								<?php if(isset($px_theme_option['points_table_coloumn_field_7'][$i]) && $px_theme_option['points_table_coloumn_field_7'][$i] <> ''){
									$count_columns_heading++;
									?>
									<li class="to-label"><label><?php echo $px_theme_option['points_table_coloumn_field_7'][$i];?></label></li>
									<li class="to-field">
										<input type="text" id="var_pb_points_table_value7" name="var_pb_points_table_value7" value="" />
									</li>
								<?php }?>
								<?php if(isset($px_theme_option['points_table_coloumn_field_8'][$i]) && $px_theme_option['points_table_coloumn_field_8'][$i] <> ''){
									$count_columns_heading++;
									?>
									<li class="to-label"><label><?php echo $px_theme_option['points_table_coloumn_field_8'][$i];?></label></li>
									<li class="to-field">
										<input type="text" id="var_pb_points_table_value8" name="var_pb_points_table_value8" value="" />
									</li>
								<?php }?>
								<?php if(isset($px_theme_option['points_table_coloumn_field_9'][$i]) && $px_theme_option['points_table_coloumn_field_9'][$i] <> ''){
									$count_columns_heading++;
									?>
									<li class="to-label"><label><?php echo $px_theme_option['points_table_coloumn_field_9'][$i];?></label></li>
									<li class="to-field">
										<input type="text" id="var_pb_points_table_value9" name="var_pb_points_table_value9" value="" />
									</li>
								<?php }?>
                                <li class="to-label"><label>Featured Points Row</label></li>
                                <li class="to-field">
                                	<select name="var_pb_points_table_featured" id="var_pb_points_table_featured">
                                    	<option value="no" <?php if(isset($var_pb_points_table_featured) && $var_pb_points_table_featured == 'no'){echo 'selected="selected"';}?>>No</option>
                                        <option value="yes" <?php if(isset($var_pb_points_table_featured) && $var_pb_points_table_featured == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                                    
                                    </select>
                                </li>
							<?php
							}
							?>
                        </ul>
                        <ul class="form-elements noborder">
                            <li class="to-label"></li>
                            <li class="to-field"><input type="button" value="Add Points to List" onclick="add_track_to_list('<?php echo admin_url('admin-ajax.php');?>', '<?php echo get_template_directory_uri()?>')" /></li>
                        </ul>
                    </div>
                    <script>
						jQuery(document).ready(function($) {
							$("#total_tracks").sortable({
								cancel : 'td div.poped-up',
							});
						});
					</script>
                    <ul class="form-elements noborder">
                        <li class="to-label"></li>
                        <li class="to-field" id="sortablecoloumn">
                        <?php 
							if(isset($var_pb_pointtable_tableheads) && ($var_pb_pointtable_tableheads <> '' || $var_pb_pointtable_tableheads == '0'))
							{
								if(isset($px_table_sort_column) && $px_table_sort_column == '0'){
									$px_table_sort_column = '0';
								} else if(isset($px_table_sort_column) && $px_table_sort_column <> '0' && $px_table_sort_column <> '')
									$px_table_sort_column = $px_table_sort_column;
								else 
									$px_table_sort_column = '';
									
								$i = (int)$var_pb_pointtable_tableheads;
									?>
								   <select name="px_table_sort_column" >
										<option value="">-- Select Coloumn--</option>
										<?php if(isset($px_theme_option['points_table_coloumn_field_1'][$i]) && $px_theme_option['points_table_coloumn_field_1'][$i] <> ''){?>
												<option value="0"  <?php if(isset($px_table_sort_column) && $px_table_sort_column == '0'){echo 'selected="selected"';}?> ><?php echo $px_theme_option['points_table_coloumn_field_1'][$i];?></option>
										<?php }?>
										<?php if(isset($px_theme_option['points_table_coloumn_field_2'][$i]) && $px_theme_option['points_table_coloumn_field_2'][$i] <> ''){?>
												<option value="1"   <?php if(isset($px_table_sort_column) && $px_table_sort_column <> '' && $px_table_sort_column == '1'){echo 'selected="selected"';}?>><?php echo $px_theme_option['points_table_coloumn_field_2'][$i];?></option>
										<?php }?>
										<?php if(isset($px_theme_option['points_table_coloumn_field_3'][$i]) && $px_theme_option['points_table_coloumn_field_3'][$i] <> ''){?>
												<option value="2"   <?php if(isset($px_table_sort_column) && $px_table_sort_column <> '' && $px_table_sort_column == '2'){echo 'selected="selected"';}?>><?php echo $px_theme_option['points_table_coloumn_field_3'][$i];?></option>
										<?php }?>
										<?php if(isset($px_theme_option['points_table_coloumn_field_4'][$i]) && $px_theme_option['points_table_coloumn_field_4'][$i] <> ''){?>
												<option value="3"   <?php if(isset($px_table_sort_column) && $px_table_sort_column <> '' && $px_table_sort_column == '3'){echo 'selected="selected"';}?>><?php echo $px_theme_option['points_table_coloumn_field_4'][$i];?></option>
										<?php }?>
										<?php if(isset($px_theme_option['points_table_coloumn_field_5'][$i]) && $px_theme_option['points_table_coloumn_field_5'][$i] <> ''){?>
												<option value="4"  <?php if(isset($px_table_sort_column) && $px_table_sort_column <> '' && $px_table_sort_column == '4'){echo 'selected="selected"';}?>><?php echo $px_theme_option['points_table_coloumn_field_5'][$i];?></option>
										<?php }?>
										<?php if(isset($px_theme_option['points_table_coloumn_field_6'][$i]) && $px_theme_option['points_table_coloumn_field_6'][$i] <> ''){?>
												<option value="5"   <?php if(isset($px_table_sort_column) && $px_table_sort_column <> '' && $px_table_sort_column == '5'){echo 'selected="selected"';}?>><?php echo $px_theme_option['points_table_coloumn_field_6'][$i];?></option>
										<?php }?>
										<?php if(isset($px_theme_option['points_table_coloumn_field_7'][$i]) && $px_theme_option['points_table_coloumn_field_7'][$i] <> ''){?>
												<option value="6"  <?php if(isset($px_table_sort_column) && $px_table_sort_column <> '' && $px_table_sort_column == '6'){echo 'selected="selected"';}?>><?php echo $px_theme_option['points_table_coloumn_field_7'][$i];?></option>
										<?php }?>
										<?php if(isset($px_theme_option['points_table_coloumn_field_8'][$i]) && $px_theme_option['points_table_coloumn_field_8'][$i] <> ''){?>
												<option value="7"   <?php if(isset($px_table_sort_column) && $px_table_sort_column <> '' && $px_table_sort_column == '7'){echo 'selected="selected"';}?>><?php echo $px_theme_option['points_table_coloumn_field_8'][$i];?></option>
										<?php }?>
										<?php if(isset($px_theme_option['points_table_coloumn_field_9'][$i]) && $px_theme_option['points_table_coloumn_field_9'][$i] <> ''){?>
												<option value="8" <?php if(isset($px_table_sort_column) && $px_table_sort_column <> '' && $px_table_sort_column == '8'){echo 'selected="selected"';}?>><?php echo $px_theme_option['points_table_coloumn_field_9'][$i];?></option>
										<?php }?>
								   </select>
                                   <p> </p>
								<?php
							}
						?>
                        </li>
                    </ul>
                    <table class="to-table px-sermon-table px-pointable" border="0" cellspacing="0" <?php if($px_pointtable <> "" && !isset($px_xmlObject) && count($px_xmlObject->track)<1){?>style="<?php echo 'display: none';?>" <?php }?>>
                        <thead>
                            <tr>
                                <th style="width:80%;">Points Title</th>
                                <th style="width:80%;" class="centr">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="total_tracks">
                            <?php
								global $counter_track, $var_pb_pointtable_tableheadsss, $var_pb_points_table_value1, $var_pb_points_table_value2,$var_pb_points_table_value3,$var_pb_points_table_value4,$var_pb_points_table_value5,$var_pb_points_table_value6,$var_pb_points_table_value7,$var_pb_points_table_value8,$var_pb_points_table_value9,$var_pb_points_table_featured;
								$counter_track = $post->ID;
								$var_pb_pointtable_tableheadsss=(int)$var_pb_pointtable_tableheads;
								if ( $px_pointtable <> "" ) {
									foreach ( $px_xmlObject as $track ){
										if ( $track->getName() == "track" ) {
											$var_pb_points_table_value1 = $track->var_pb_points_table_value1;
											$var_pb_points_table_value2 = $track->var_pb_points_table_value2;
											$var_pb_points_table_value3 = $track->var_pb_points_table_value3;
											$var_pb_points_table_value4 = $track->var_pb_points_table_value4;
											$var_pb_points_table_value5 = $track->var_pb_points_table_value5;
											$var_pb_points_table_value6 = $track->var_pb_points_table_value6;
											$var_pb_points_table_value7 = $track->var_pb_points_table_value7;
											$var_pb_points_table_value8 = $track->var_pb_points_table_value8;
											$var_pb_points_table_value9 = $track->var_pb_points_table_value9;
											$var_pb_points_table_featured = $track->var_pb_points_table_featured;
											$counter_track++;
 											px_add_pointtable_to_list();
										}
									}
								}
							?>
                        </tbody>
                    </table>
                </div>
                <div class="option-sec" style="margin-bottom:0;">
                    <div class="opt-conts">
                        <?php meta_layout()?> 
                    </div>
                    
					<div class="clear"></div>
                    
                </div>
                <input type="hidden" name="var_pb_pointtable_meta_form" value="1" />
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
<?php
		}

		if ( isset($_POST['var_pb_pointtable_meta_form']) and $_POST['var_pb_pointtable_meta_form'] == 1 ) {
			if ( empty($_POST['px_layout']) ) $_POST['px_layout'] = 'none';
			add_action( 'save_post', 'px_meta_pointtable_save' );  
			function px_meta_pointtable_save( $px_post_id )
			{  
				$sxe = new SimpleXMLElement("<pointtable></pointtable>");
					if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
 						if ( empty($_POST["var_pb_record_per_post"]) ) $_POST["var_pb_record_per_post"] = "";
						if ( empty($_POST["var_pb_pointtable_viewall"]) ) $_POST["var_pb_pointtable_viewall"] = "";
						if($_POST["var_pb_pointtable_tableheads"] == 0){
							$_POST["var_pb_pointtable_tableheads"] = 0;
						} else if ( empty($_POST["var_pb_pointtable_tableheads"]) &&  $_POST["var_pb_pointtable_tableheads"] == '' ){
							$_POST["var_pb_pointtable_tableheads"] = "";
						}
						if(isset($_POST["px_table_sort_column"] )){
							if($_POST["px_table_sort_column"] == 0){
								$_POST["px_table_sort_column"] = 0;
							} else if ( empty($_POST["px_table_sort_column"]) &&  $_POST["px_table_sort_column"] == '' ){
								$_POST["px_table_sort_column"] = "";
							}
						} else {
							$_POST["px_table_sort_column"] = "";
						}
					
						$sxe = save_layout_xml($sxe);
							$sxe->addChild('var_pb_record_per_post', $_POST['var_pb_record_per_post'] );
							$sxe->addChild('var_pb_pointtable_viewall', $_POST['var_pb_pointtable_viewall'] );
							$sxe->addChild('var_pb_pointtable_tableheads', $_POST['var_pb_pointtable_tableheads'] );
							$sxe->addChild('px_table_sort_column', $_POST['px_table_sort_column'] );
 							$counter = 0;
							
							if ( isset($_POST['var_pb_points_table_value1']) && is_array($_POST['var_pb_points_table_value1']) ) {
									foreach ( $_POST['var_pb_points_table_value1'] as $count ){
										$track = $sxe->addChild('track');
											if ( empty($_POST['var_pb_points_table_value1'][$counter]) and $_POST['var_pb_points_table_value1'][$counter] <> '0') {$_POST['var_pb_points_table_value1'][$counter] = "-";}
											if ( empty($_POST['var_pb_points_table_value2'][$counter]) and $_POST['var_pb_points_table_value2'][$counter] <> '0' ) { $_POST['var_pb_points_table_value2'][$counter] = "-";}
											if ( empty($_POST['var_pb_points_table_value3'][$counter]) and $_POST['var_pb_points_table_value3'][$counter] <> '0' ) $_POST['var_pb_points_table_value3'][$counter] = "-";
											if ( empty($_POST['var_pb_points_table_value4'][$counter]) and $_POST['var_pb_points_table_value4'][$counter] <> '0' ) $_POST['var_pb_points_table_value4'][$counter] = "-";
											if ( empty($_POST['var_pb_points_table_value5'][$counter]) and $_POST['var_pb_points_table_value5'][$counter] <> '0' ) $_POST['var_pb_points_table_value5'][$counter] = "-";
											if ( empty($_POST['var_pb_points_table_value6'][$counter]) and $_POST['var_pb_points_table_value6'][$counter] <> '0' ) $_POST['var_pb_points_table_value6'][$counter] = "-";
											if ( empty($_POST['var_pb_points_table_value7'][$counter]) and $_POST['var_pb_points_table_value7'][$counter] <> '0' ) $_POST['var_pb_points_table_value7'][$counter] = "-";
											if ( empty($_POST['var_pb_points_table_value8'][$counter]) and $_POST['var_pb_points_table_value8'][$counter] <> '0' ) $_POST['var_pb_points_table_value8'][$counter] = "-";
											if ( empty($_POST['var_pb_points_table_value9'][$counter]) and $_POST['var_pb_points_table_value9'][$counter] <> '0' ) $_POST['var_pb_points_table_value9'][$counter] = "-";
											if ( empty($_POST['var_pb_points_table_featured'][$counter]) ) $_POST['var_pb_points_table_featured'][$counter] = "";
											
											$track->addChild('var_pb_points_table_value1', htmlspecialchars($_POST['var_pb_points_table_value1'][$counter]));
											$track->addChild('var_pb_points_table_value2', htmlspecialchars($_POST['var_pb_points_table_value2'][$counter]));
											$track->addChild('var_pb_points_table_value3', htmlspecialchars($_POST['var_pb_points_table_value3'][$counter]) );
											$track->addChild('var_pb_points_table_value4', htmlspecialchars($_POST['var_pb_points_table_value4'][$counter]) );
											$track->addChild('var_pb_points_table_value5', htmlspecialchars($_POST['var_pb_points_table_value5'][$counter]) );
											$track->addChild('var_pb_points_table_value6', htmlspecialchars($_POST['var_pb_points_table_value6'][$counter]) );
											$track->addChild('var_pb_points_table_value7', htmlspecialchars($_POST['var_pb_points_table_value7'][$counter]) );
											$track->addChild('var_pb_points_table_value8', htmlspecialchars($_POST['var_pb_points_table_value8'][$counter]) );
											$track->addChild('var_pb_points_table_value9', htmlspecialchars($_POST['var_pb_points_table_value9'][$counter]) );
											$track->addChild('var_pb_points_table_featured', htmlspecialchars($_POST['var_pb_points_table_featured'][$counter]) );
											
											$counter++;
									}
							}
						
				update_post_meta( $px_post_id, 'px_pointtable', $sxe->asXML() );
			}
		}
		// adding poin table meta info end
?>