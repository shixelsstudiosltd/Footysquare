<?php
	global $px_theme_option,$counter_node,$video_width;
  	get_header();
	if (have_posts()):
	$media_per_page = 30;
	while (have_posts()) : the_post();
 	$count_post =0;

	// galery slug to id start

	// galery slug to id end
	$px_meta_gallery_options = get_post_meta($post->ID, "px_meta_gallery_options", true);
	if ( empty($_GET['page_id_all']) ) $_GET['page_id_all'] = 1;
	// pagination start
	if ( $px_meta_gallery_options <> "" ) {
		$px_xmlObject = new SimpleXMLElement($px_meta_gallery_options);
		if ($media_per_page > 0 ) {
			$limit_start = $media_per_page * ($_GET['page_id_all']-1);
			$limit_end = $limit_start + $media_per_page;
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
       <div class="element_size_100 page_listing">

   
   
   
  <div class="col-sm-12 col-md-12">
 <div class="gallerysec gallery">
        <ul class="col-md-12 lightbox clearfix">
         <?php
            if ( $px_meta_gallery_options <> "" ) {
                for ( $i = $limit_start; $i < $limit_end; $i++ ) {
                    $path = $px_xmlObject->gallery[$i]->path;
                    $title = $px_xmlObject->gallery[$i]->title;
                    $use_image_as = $px_xmlObject->gallery[$i]->use_image_as;
                    $video_code = $px_xmlObject->gallery[$i]->video_code;
                    $link_url = $px_xmlObject->gallery[$i]->link_url;
 					$image_url = px_attachment_image_src($path, 470, 353);
                    $image_url_full = px_attachment_image_src($path, 0, 0);
 					?>
            <li <?php if($use_image_as==1){ echo 'class="video-gallery-img"'; }?>>
                <figure>
                    <img src="<?php echo $image_url;?>" alt="">
                    <figcaption>
                            <a  data-rel="<?php if($use_image_as==1)echo "prettyPhoto";  elseif($use_image_as==2) echo ""; else echo "prettyPhoto[gallery1]"?>" href="<?php if($use_image_as==1)echo $video_code; elseif($use_image_as==2) echo $link_url; else echo $image_url_full;?>"  data-title="<?php if ( $title <> "" ) { echo $title; }?>" >
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
						$categories_list = get_the_term_list ( get_the_id(), 'px_gallery-category', $before_cat, ', ', '</p>' );
						if ( $categories_list ){
							printf( __( '%1$s', 'Kings Club'),$categories_list );
						}
					
					
					 ?>
                </div>
            </li>
   <?php }}?>
   		</ul>
   </div>
</div> 
</div>     
<?php endwhile;   endif;?>
<!--Footer-->
<?php get_footer(); ?>
