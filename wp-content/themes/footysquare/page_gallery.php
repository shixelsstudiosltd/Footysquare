<?php
	global $px_node,$counter_node, $px_theme_option;
 	$count_post =0;
 	// galery slug to id start
		$args=array(
			'name' => (string)$px_node->album,
			'post_type' => 'px_gallery',
			'post_status' => 'publish',
			'showposts' => 1,
		);
 		$get_posts = get_posts($args);
		if($get_posts){
			$gal_album_db = (int)$get_posts[0]->ID;
			$px_meta_gallery_options = get_post_meta((int)$gal_album_db, "px_meta_gallery_options", true);
		}
	// galery slug to id end
	
	if ( empty($_GET['page_id_all']) ) $_GET['page_id_all'] = 1;
	// pagination start
	if ( isset($px_meta_gallery_options) &&  $px_meta_gallery_options <> "" ) {
		$px_xmlObject = new SimpleXMLElement($px_meta_gallery_options);
		if ($px_node->media_per_page > 0 ) {
			$limit_start = $px_node->media_per_page * ($_GET['page_id_all']-1);
			$limit_end = $limit_start + $px_node->media_per_page;
			$count_post = count($px_xmlObject->gallery);
				if ( $limit_end > count($px_xmlObject->gallery) ) 
					$limit_end = count($px_xmlObject->gallery);
		}
		else {
			$limit_start = 0;
			$limit_end = count($px_xmlObject->gallery);
			$count_post = count($px_xmlObject->gallery);
		}
	}
	?>
    
    <div class="element_size_<?php echo $px_node->gallery_element_size; ?> page_listing">
    <?php
		if ($px_node->header_title <> '') { ?>
                <header class="pix-heading-title">
                    <h2 class="pix-section-title pix-heading-color"><?php echo $px_node->header_title; ?></h2>
                </header>
        <?php  } ?>
     <div class="gallerysec gallery">
        <ul class="<?php echo $px_node->layout;?> lightbox clearfix">
         <?php
            if ( isset($px_meta_gallery_options) &&  $px_meta_gallery_options <> "" ) {
                for ( $i = $limit_start; $i < $limit_end; $i++ ) {
                    $path = $px_xmlObject->gallery[$i]->path;
                    $title = $px_xmlObject->gallery[$i]->title;
                    $social_network = $px_xmlObject->gallery[$i]->social_network;
                    $use_image_as = $px_xmlObject->gallery[$i]->use_image_as;
                    $video_code = $px_xmlObject->gallery[$i]->video_code;
                    $link_url = $px_xmlObject->gallery[$i]->link_url;
 					$image_url = px_attachment_image_src($path, 470, 353);
                    $image_url_full = px_attachment_image_src($path, 0, 0);
					$link_target = '';
					if($use_image_as==2){
						$link_target = 	'target="_blank"';
					}
 					?>
            <li <?php if($use_image_as==1){ echo 'class="video-gallery-img"'; }?>>
                <figure>
                    <img src="<?php echo $image_url;?>" alt="">
                    <figcaption>
                            <a  data-rel="<?php if($use_image_as==1)echo "prettyPhoto";  elseif($use_image_as==2) echo ""; else echo "prettyPhoto[gallery1]"?>" href="<?php if($use_image_as==1)echo $video_code; elseif($use_image_as==2) echo $link_url; else echo $image_url_full;?>" <?php echo $link_target;?> data-title="<?php if ( $title <> "" ) { echo $title; }?>" >
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
                <div class="text">
                <?php if(isset($title) && $title <> '') {?>
                		<h2><?php echo $title; ?></h2>
                    <?php }?>
                    <?php
					 $before_cat = "<p> ";
	
						$categories_list = get_the_term_list ( $gal_album_db, 'px_gallery-category', $before_cat, ', ', '</p>' );
						if ( $categories_list ){
							printf( __( '%1$s', 'Kings Club'),$categories_list );
						}
					
					
					 ?>
                </div>
            </li>
   <?php }}?>
   		</ul>
   </div>
   <?php
	// pagination start
	 $qrystr = '';
	   if ( $px_node->pagination == "Show Pagination" and $count_post > $px_node->media_per_page and $px_node->media_per_page > 0 ) {
			if ( isset($_GET['page_id']) ) $qrystr = "&amp;page_id=".$_GET['page_id'];
			
			echo "<nav class='pagination'><ul>";
				echo px_pagination($count_post, $px_node->media_per_page,$qrystr);
			echo "</ul></nav>";
		}
	
	// pagination end
	?>
</div>