<?php
add_action( 'add_meta_boxes', 'px_meta_post_add' );
function px_meta_post_add()
{  
	add_meta_box( 'px_meta_post', 'Post Options', 'px_meta_post', 'post', 'normal', 'high' );  
}
function px_meta_post( $post ) {
	$post_xml = get_post_meta($post->ID, "post", true);
	global $px_xmlObject;
	if ( $post_xml <> "" ) {
		$px_xmlObject = new SimpleXMLElement($post_xml);
			$var_pb_post_author = $px_xmlObject->var_pb_post_author;
 			$var_pb_post_social_sharing = $px_xmlObject->var_pb_post_social_sharing;
			$var_pb_post_attachment = $px_xmlObject->var_pb_post_attachment;
			$var_pb_post_attachment_title = $px_xmlObject->var_pb_post_attachment_title;
			$var_pb_post_featured = $px_xmlObject->var_pb_post_featured;
	} else {
		
 		$var_pb_post_social_sharing = '';
		$var_pb_post_author = 'on';
		
		
		$var_pb_post_attachment = 'on';
		$var_pb_post_attachment_title = 'Attachment';
		$var_pb_post_featured = '';

	}
?>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/select.js"></script>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/admin/bootstrap.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/bootstrap-3.0.js"></script>
	<div class="page-wrap event-meta-section">
        <div class="option-sec row">
            <div class="opt-conts">
            	
  				
                <ul class="form-elements  on-off-options">
                	<li class="to-label"><label>Featured Image</label></li>
                    <li class="to-field">
                        <label class="cs-on-off">
                            <input type="checkbox" name="var_pb_post_featured" value="on" class="myClass" <?php if($var_pb_post_featured == 'on') echo "checked"?> />
                            <span></span>
                        </label>    
                    </li>
                    <li class="to-label"><label>Social Sharing</label></li>
                    <li class="to-field">
                        <label class="cs-on-off">
                            <input type="checkbox" name="var_pb_post_social_sharing" value="on" class="myClass" <?php if($var_pb_post_social_sharing == 'on') echo "checked"?> />
                            <span></span>
                        </label>    
                    </li>

                    <li class="to-label"><label>Author Description</label></li>
                    <li class="to-field">
                        <label class="cs-on-off">
                            <input type="checkbox" name="var_pb_post_author" value="on" class="myClass" <?php if($var_pb_post_author=='on')echo "checked"?> />
                            <span></span>
                        </label>
                    </li>
                    </ul>
                    <ul class="form-elements">
                     <li class="to-label"><label>Attachment</label></li>
                    <li class="to-field">
                        <label class="cs-on-off">
                            <input type="checkbox" name="var_pb_post_attachment" value="on" class="myClass" <?php if($var_pb_post_attachment=='on')echo "checked"?> />
                            <span></span>
                        </label>
                    </li>
                    <li class="to-label"><label>Attachment title</label></li>
                    <li class="to-field">
                       
                            <input type="text" name="var_pb_post_attachment_title" value="<?php echo $var_pb_post_attachment_title;?>" />
                        
                    </li>
                </ul>
                <?php meta_layout()?>
			</div>
            
		</div>
        
		<div class="clear"></div>
       
        <input type="hidden" name="post_meta_form" value="1" />
    </div>
<?php
}

		if ( isset($_POST['post_meta_form']) and $_POST['post_meta_form'] == 1 ) {
			add_action( 'save_post', 'px_meta_post_save' );
			function px_meta_post_save( $post_id ) {
				if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
					if (empty($_POST["var_pb_post_author"])){ $_POST["var_pb_post_author"] = "";}
 					if (empty($_POST["var_pb_post_social_sharing"])){ $_POST["var_pb_post_social_sharing"] = "";}
					if (empty($_POST["var_pb_post_attachment_title"])){ $_POST["var_pb_post_attachment_title"] = "";}
					if (empty($_POST["var_pb_post_attachment"])){ $_POST["var_pb_post_attachment"] = "";}
					if (empty($_POST["var_pb_post_featured"])){ $_POST["var_pb_post_featured"] = "";}
					
						$sxe = new SimpleXMLElement("<px_meta_post></px_meta_post>");
						
							$sxe->addChild('var_pb_post_attachment_title', $_POST['var_pb_post_attachment_title'] );
							$sxe->addChild('var_pb_post_attachment', $_POST['var_pb_post_attachment'] );
							$sxe->addChild('var_pb_post_featured', $_POST['var_pb_post_featured'] );
							$sxe->addChild('var_pb_post_author', $_POST['var_pb_post_author'] );
 							$sxe->addChild('var_pb_post_social_sharing', $_POST['var_pb_post_social_sharing'] );
 							$sxe = save_layout_xml($sxe);
							$counter = 0;
						
						
						
				update_post_meta( $post_id, 'post', $sxe->asXML() );
				
			}
		}

//add extra fields to team category edit form hook
add_action ( 'category_edit_form_fields', 'px_extra_category_fields');
add_action ( 'category_add_form_fields', 'px_extra_category_fields');
// Add Category Fields
function px_extra_category_fields( $tag ) {    //check for existing featured ID
	if ( isset($tag->term_id) ) {$t_id = $tag->term_id; }
	else { $t_id = ""; }
	$cat_meta = get_option( "cat_$t_id");
	?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="cat_Icon_url">Mega Menu</label></th>
	<td>
    	 <ul class="form-elements  on-off-options">
           
            <li class="to-field">
                <label class="cs-on-off">
                    <input type="checkbox" name="cat_meta[menu]" value="on" class="myClass" <?php if($cat_meta['menu'] == 'on') echo "checked"?> />
                    <span></span>
                </label>    
                
            </li>
         </ul>
        
	</td>
	</tr>
    <tr class="form-field">
	<th scope="row" valign="top"><label for="cat_Icon_url">Mega Menu Style</label></th>
	<td>
        <select  name="cat_meta[menu_style]" class="dropdown">
           <option value="2 Level Links" <?php if(isset($cat_meta['menu_style']) && $cat_meta['menu_style'] == '2 Level Links') echo 'selected="selected"';?>>2 Level Links</option>
            <option value="Category Post" <?php if(isset($cat_meta['menu_style']) && $cat_meta['menu_style'] == 'Category Post') echo 'selected="selected"';?>>Category Post</option>
        </select>
	</td>
	</tr>
	<?php
}

// save team category extra fields hook
add_action ( 'create_category', 'px_save_extra_post_category_fileds');
add_action ( 'edited_category', 'px_save_extra_post_category_fileds');
   // save extra category extra fields callback function
function px_save_extra_post_category_fileds( $term_id ) {
	if ( isset( $_POST['cat_meta'] ) ) {
		$t_id = $term_id;
		$cat_meta = get_option( "cat_$t_id");
		$cat_keys = array_keys($_POST['cat_meta']);
			foreach ($cat_keys as $key){
			if (isset($_POST['cat_meta'][$key])){
				$cat_meta[$key] = ($_POST['cat_meta'][$key]);
			}
		}
		//print_r($cat_meta);
		//save the option array
		update_option( "cat_$t_id", $cat_meta );
		//exit;
	}
}
	
?>