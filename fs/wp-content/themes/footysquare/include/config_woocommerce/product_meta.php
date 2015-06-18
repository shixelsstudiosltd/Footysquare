<?php

add_action( 'add_meta_boxes', 'px_meta_woo_prod_add' );
function px_meta_woo_prod_add()
{  
	add_meta_box( 'px_meta_woo_prod', 'Product Layout Options', 'px_meta_woo_prod', 'product', 'normal', 'low' );  
}
function px_meta_woo_prod( $post ) {
	$post_xml = get_post_meta($post->ID, "product", true);
	global $px_xmlObject;
	if ( $post_xml <> "" ) {
		$px_xmlObject = new SimpleXMLElement($post_xml);
		$sub_title = $px_xmlObject->sub_title;
	}else{
		$sub_title = "";
	}
	?>
    <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/select.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/prettyCheckable.js"></script>
	<div class="page-wrap">
        <div class="option-sec row">
            <div class="opt-conts">
            	<ul class="form-elements" style="display:none;">
                    <li class="to-label"><label>Sub Title</label></li>
                    <li class="to-field">
                    	<input type="text" name="sub_title" value="<?php echo $sub_title ?>" />
                        <p>Put the sub title.</p>
                    </li>
                </ul>
			</div>
		</div>
		<div class="clear"></div>
		<?php meta_layout()?>
        <input type="hidden" name="post_woo_meta_form" value="1" />
    </div>
    <?php
}

	if ( isset($_POST['post_woo_meta_form']) and $_POST['post_woo_meta_form'] == 1 ) {
		add_action( 'save_post', 'px_meta_woo_post_save' );
		function px_meta_woo_post_save( $post_id ) {
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
				if (empty($_POST["sub_title"])){ $_POST["sub_title"] = "";}
				
				if (empty($_POST["switch_footer_widgets"])){ $_POST["switch_footer_widgets"] = "";}
					$sxe = new SimpleXMLElement("<px_meta_post></px_meta_post>");
						$sxe->addChild('sub_title', $_POST['sub_title'] );
						$sxe = save_layout_xml($sxe);
						
			update_post_meta( $post_id, 'product', $sxe->asXML() );
		}
	}


?>