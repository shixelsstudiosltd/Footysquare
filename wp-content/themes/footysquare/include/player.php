<?php
//add extra fields to team category edit form hook
add_action ( 'team-category_edit_form_fields', 'cs_extra_category_fields');
add_action ( 'team-category_add_form_fields', 'cs_extra_category_fields');
// Add Category Fields
function cs_extra_category_fields( $tag ) {    //check for existing featured ID
	if ( isset($tag->term_id) ) {$t_id = $tag->term_id; }
	else { $t_id = ""; }
	$cat_meta = get_option( "team_$t_id");
	?>
    
	<tr class="form-field">
	<th scope="row" valign="top"><label for="cat_Icon_url">Image Url</label></th>
	<td>
    <input id="Cat_metai" name="Cat_meta[icon]" value="<?php echo $cat_meta['icon'] ? stripslashes(htmlspecialchars($cat_meta['icon'])) : ''; ?>" type="text" 
                class="small {validate:{accept:'jpg|jpeg|gif|png|bmp'}}"  />
                <input id="icon" name="Cat_meta[icon]"   type="button" class="uploadfilee left button" value="Browse"/>
	
	</td>
	</tr>
	<script>
		jQuery(document).ready(function($) {
			px_team_insert_img();
		});
		
		function px_team_insert_img(){
			var ww = jQuery('#post_id_reference').text();
			window.original_send_to_editor = window.send_to_editor;
			window.send_to_editor_clone = function(html){
				imgurl = jQuery('a','<p>'+html+'</p>').attr('href');
				jQuery('#Cat_metai').val(imgurl);
				tb_remove();
			}
			jQuery('input.uploadfilee').click(function() {
				window.send_to_editor=window.send_to_editor_clone;
				formfield = jQuery(this).attr('name');
				tb_show('', 'media-upload.php?post_id=' + ww + '&type=image&TB_iframe=true');
				return false;
			});	
		}
	</script>
	
	<?php
}

// save team category extra fields hook
add_action ( 'create_team-category', 'px_save_extra_category_fileds');
add_action ( 'edited_team-category', 'px_save_extra_category_fileds');
   // save extra category extra fields callback function
function px_save_extra_category_fileds( $term_id ) {
	if ( isset( $_POST['Cat_meta'] ) ) {
		$t_id = $term_id;
		$cat_meta = get_option( "team_$t_id");
		$cat_keys = array_keys($_POST['Cat_meta']);
			foreach ($cat_keys as $key){
			if (isset($_POST['Cat_meta'][$key])){
				$cat_meta[$key] = ($_POST['Cat_meta'][$key]);
			}
		}
		//save the option array
		update_option( "team_$t_id", $cat_meta );
	}
}
// get team category Data

add_filter('manage_player_posts_columns', 'player_columns_add');
	function player_columns_add($columns) {
			$columns['category'] = 'Teams';
 			$columns['department'] = 'Departments';
			$columns['author'] = 'Author';
			return $columns;
    }
	add_action('manage_player_posts_custom_column', 'player_columns');
	function player_columns($name) {
			global $post;
			switch ($name) {
				case 'category':
					$categories = get_the_terms( $post->ID, 'team-category' );
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
				case 'department':
					$categories = get_the_terms( $post->ID, 'player-department' );
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
 	function register_post_type_player(){
		$post_type_name = 'Player';
		// adding post type start
			$labels = array(
				'name' => 'Players',
				'add_new_item' => 'Add New '.$post_type_name,
				'edit_item' => 'Edit '.$post_type_name,
				'new_item' => 'New '.$post_type_name.' Item',
				'add_new' => 'Add New '.$post_type_name,
				'view_item' => 'View '.$post_type_name.' Item',
				'search_items' => 'Search '.$post_type_name,
				'not_found' =>  'No '.$post_type_name.' Found',
				'not_found_in_trash' => 'No '.$post_type_name.' Found in Trash'
			);
			$args = array(
				'labels' => $labels,
				'public' => true,
				'menu_icon' => 'dashicons-groups',
				'supports' => array('title','editor','thumbnail', 'comments')
			); 
			register_post_type('player',$args );
		// adding post type end
	}
	add_action('init', 'register_post_type_player');
	// adding category start
		  $labels = array(
			'name' => 'Teams',
			'search_items' => 'Search Team',
			'edit_item' => 'Edit Team',
			'update_item' => 'Update Team',
			'add_new_item' => 'Add New Team',
			'menu_name' => 'Teams',
		  );
		  register_taxonomy('team-category',array('player'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'team-category' ),
		  ));
		// adding category end
		// adding department start
			$labels = array(
				'name' => 'Departments',
				'search_items' =>  'Search Department',
				'edit_item' => 'Edit Department', 
				'update_item' => 'Update Department',
				'add_new_item' => 'Add Department',
				'menu_name' => 'Departments'
 			); 	
			register_taxonomy('player-department',array('player'), array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'show_admin_column' => false,
				'show_in_nav_menus' => false,
				'public' => false,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'player-department' ),
			));
		// adding series end
function remove_player_department()
{
	remove_meta_box('player-departmentdiv', 'player', 'side' );
}
add_action( 'admin_menu', 'remove_player_department' );
	// meta box start
	function meta_box_player(){
		global $post_id,$px_xmlObject;
		$px_player_dept = get_post_meta($post_id, 'px_player_dept', true);
		$post_xml = get_post_meta($post_id, 'px_player', true);
		if ( $post_xml <> "" ) {
			$px_xmlObject = new SimpleXMLElement($post_xml);
			$player_dob = $px_xmlObject->player_dob;
			$player_birthplace = $px_xmlObject->player_birthplace;
			$player_spciality = $px_xmlObject->player_spciality;
 			$player_shirtnumber = $px_xmlObject->player_shirtnumber;
			$player_debut = $px_xmlObject->player_debut;
			$player_twittername = $px_xmlObject->player_twittername;
			$player_gallery_title = $px_xmlObject->player_gallery_title;	
			$player_gallery = $px_xmlObject->player_gallery;	
			$var_pb_player_social_sharing = $px_xmlObject->var_pb_player_social_sharing;
			$var_pb_player_author = $px_xmlObject->var_pb_player_author;
 		}else{
			$player_dob = '';
			$player_birthplace = '';
			$player_spciality = '';
 			$player_shirtnumber = '';
			$player_debut = '';
			$player_twittername = '';
			$player_gallery_title = '';
			$player_gallery = '';
			$var_pb_player_social_sharing = '';
			$var_pb_player_author = '';
			
		}
 		?>
       <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/bootstrap.min.css">
        <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/select.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/bootstrap-3.0.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/bootstrap-datepicker.js"></script>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/datepicker.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/bootstrap-timepicker.min.css">
         <script type="text/javascript">
		
		jQuery(function($) {
					jQuery( "#player_debut" ).click(function(event) {
						jQuery( this ).prev( "div" ).show();
						event.stopPropagation();
					});
					jQuery( "html" ).click(function() {
						jQuery( '.bootstrap-timepicker-widget' ).hide();
					});
					
					    var nowTemp = new Date();
						var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
						 
						var checkin = $('#player_debut').datepicker({
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
						}).data('datepicker');
						var checkout = $('#to_date').datepicker({
						onRender: function(date) {
						return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
						}
						}).on('changeDate', function(ev) {
						checkout.hide();
						}).data('datepicker');
				});
			
        </script>
		
		<div class="page-wrap">
            <div class="option-sec" style="margin-bottom:0;">
                <div class="opt-conts">
                    <div class="opt-head">
                      <h4>Player Profile</h4>
                      <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <ul class="form-elements">
                        <li class="to-field">
                        	<input type="text" name="player_dob" value="<?php echo $player_dob; ?>" />
                            <p>D.O.B/Age</p>
                        </li>
                    </ul>
                    <ul class="form-elements noborder">
                        <li class="to-field">
                        	<input type="text" name="player_birthplace" value="<?php echo $player_birthplace; ?>" />
                            <p>Birth Place</p>
                        </li>
                    </ul>
                    <ul class="form-elements noborder">
                        <li class="to-field">
                        	<input type="text" name="player_spciality" value="<?php echo $player_spciality; ?>" />                             
                            <p>Position</p>
                        </li>
                    </ul>
                    <ul class="form-elements noborder">
                        <li class="to-field">
                        	<input type="text" name="player_shirtnumber" value="<?php echo $player_shirtnumber; ?>" />                             
                            <p>Squad Number/T-Shirt Number</p>
                        </li>
                    </ul>
                    <ul class="form-elements noborder">
                        <li class="to-field">
                        	<div data-date-minviewmode="months" data-date-viewmode="years" data-date-format="mm/yyyy" data-date="102/2012" id="dpMonths" class="input-append date">
                        	<input type="text" id="player_debut" name="player_debut" value="<?php echo $player_debut;  ?>" /> 
                            <span class="add-on"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>                            
                            <p>Debut Date</p>
                        </li>
                    </ul>
                     <ul class="form-elements noborder">
                        <li class="to-field">
                         	<input type="text" id="player_twittername" name="player_twittername" value="<?php echo $player_twittername; ?>" /> 
                             <p>Enter Twitter Name</p>
                        </li>
                    </ul>
                    <ul class="form-elements noborder">
                    <li class="to-field">
                         <select name="px_player_dept">
                        	<option value="">-- Select Department --</option>
                        	 <?php
								$categories = get_categories( array('taxonomy' => 'player-department', 'hide_empty' => 0) );
									foreach ($categories as $category) {
									?>
									<option <?php if ($category->term_id == $px_player_dept){echo 'selected="selected"';} ?> value="<?php echo $category->term_id ?>">
										<?php echo $category->cat_name?>
                                    </option>
									<?php
									}
								?> 
							
                        </select>
                        <p>Choose Department</p>
                    </li>
                </ul>
                
                	<ul class="form-elements noborder">
                        <li class="to-field">
                        	<input type="text" name="player_gallery_title" value="<?php echo $player_gallery_title; ?>" />   
                            <p>Enter Gallery Title</p>                          
                        </li>
                    </ul>
                    <ul class="form-elements noborder">
                       <li class="to-field">
                          <select name="player_gallery" class="dropdown">
                          	<option value="">-- Select Gallery --</option>
                               <?php
                                  global $post;
                                  $query = array( 'posts_per_page' => '-1', 'post_type' => 'px_gallery', 'orderby'=>'ID', 'post_status' => 'publish' );
                                  $wp_query = new WP_Query($query);
                                  while ($wp_query->have_posts()) : $wp_query->the_post();
                              ?>
                              <option <?php if($post->post_name==$player_gallery)echo "selected";?> value="<?php echo $post->post_name; ?>">
                                  <?php the_title()?>
                              </option>
                              <?php
                                  endwhile;
                                  wp_reset_query();
                              ?>
                          </select>
                          <p>Choose Gallery.Create new Gallery from <a style="color:#06F; text-decoration:underline;" href="<?php echo get_site_url(); ?>/wp-admin/post-new.php?post_type=px_gallery">here</a></p>
                      </li>
                    </ul>
                    
                    <ul class="form-elements  on-off-options">
                        <li class="to-label"><label>Social Sharing</label></li>
                        <li class="to-field">
                            <label class="cs-on-off">
                                <input type="checkbox" name="var_pb_player_social_sharing" value="on" class="myClass" <?php if($var_pb_player_social_sharing == 'on') echo "checked"?> />
                                <span></span>
                            </label>    
                        </li>
    
                        <li class="to-label"><label>Author Description</label></li>
                        <li class="to-field">
                            <label class="cs-on-off">
                                <input type="checkbox" name="var_pb_player_author" value="on" class="myClass" <?php if($var_pb_player_author=='on')echo "checked"?> />
                                <span></span>
                            </label>
                        </li>
                    </ul>
                     <?php meta_layout()?>
                </div>
            </div>
            
            <input type="hidden" name="player_meta_form" value="1" />
			<div class="clear"></div>
		</div>
	    <?php
	}
    function add_meta_box_player(){
        add_meta_box( 'meta_box_player', 'Player Options', 'meta_box_player', 'player', 'normal', 'high' );
    }
	add_action( 'add_meta_boxes', 'add_meta_box_player' );
	// meta box end

	// meta box saving start
		if ( isset($_POST['player_meta_form']) and $_POST['player_meta_form'] == 1 ) {
			if ( empty($_POST['px_layout']) ) $_POST['px_layout'] = 'none';
			add_action( 'save_post', 'px_meta_save_player' );  
			function px_meta_save_player($post_id){
				$sxe = new SimpleXMLElement("<player></player>");
					
					if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
					if (empty($_POST["player_dob"])){ $_POST["player_dob"] = "";}
					if ( empty($_POST["player_birthplace"]) ) $_POST["player_birthplace"] = "";
					if ( empty($_POST["player_role"]) ) $_POST["player_role"] = "";
					if ( empty($_POST["player_spciality"]) ) $_POST["player_spciality"] = "";
					if ( empty($_POST["player_shirtnumber"]) ) $_POST["player_shirtnumber"] = "";
					if ( empty($_POST["player_debut"]) ) $_POST["player_debut"] = "";
					if ( empty($_POST["player_twittername"]) ) $_POST["player_twittername"] = "";
					if ( empty($_POST["player_gallery"]) ) $_POST["player_gallery"] = "";
					if ( empty($_POST["player_gallery_title"]) ) $_POST["player_gallery_title"] = "";
					if ( empty($_POST["var_pb_player_social_sharing"]) ) $_POST["var_pb_player_social_sharing"] = "";
					if ( empty($_POST["var_pb_player_author"]) ) $_POST["var_pb_player_author"] = "";
					
					$sxe = save_layout_xml($sxe);
					$sxe->addChild('player_dob', $_POST['player_dob'] );
					$sxe->addChild('player_birthplace', htmlspecialchars($_POST['player_birthplace']) );
					$sxe->addChild('player_role', htmlspecialchars($_POST['player_role']) );
					$sxe->addChild('player_spciality', htmlspecialchars($_POST['player_spciality']) );
					$sxe->addChild('player_shirtnumber', htmlspecialchars($_POST['player_shirtnumber']) );
					$sxe->addChild('player_debut', htmlspecialchars($_POST['player_debut']) );
					$sxe->addChild('player_twittername', htmlspecialchars($_POST['player_twittername']) );
					$sxe->addChild('player_gallery_title', htmlspecialchars($_POST['player_gallery_title']) );
					$sxe->addChild('player_gallery', htmlspecialchars($_POST['player_gallery']) );
					$sxe->addChild('var_pb_player_social_sharing', htmlspecialchars($_POST['var_pb_player_social_sharing']) );
					$sxe->addChild('var_pb_player_author', htmlspecialchars($_POST['var_pb_player_author']) );
					update_post_meta( $post_id, 'px_player', $sxe->asXML() );
					update_post_meta( $post_id, 'px_player_dept', $_POST["px_player_dept"] );
				
				
			}		
 		}
	
	// meta box saving end

?>