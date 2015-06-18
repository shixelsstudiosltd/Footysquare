<?php
	global $px_node,$post,$px_theme_option,$counter_node,$wpdb;
	if ( !isset($px_node->px_gal_album_media_per_page) || empty($px_node->px_gal_album_media_per_page) ) { $px_node->px_gal_album_media_per_page = -1; }
	if (empty($_GET['page_id_all'])) $_GET['page_id_all'] = 1;
	$args = array('posts_per_page' => "-1", 'paged' => $_GET['page_id_all'], 'post_type' => 'px_gallery', 'post_status' => 'publish');
	if(isset($px_node->px_gal_album_cat) && $px_node->px_gal_album_cat <> '' && $px_node->px_gal_album_cat <> '0' && $px_node->px_gal_album_cat <> 'All' ){
		$gallery_category_array = array('px_gallery-category' => "$px_node->px_gal_album_cat");
		$args = array_merge($args, $gallery_category_array);
	}
	$custom_query = new WP_Query($args);
	$post_count = $custom_query->post_count;
	$args = array('posts_per_page' => "$px_node->px_gal_album_media_per_page", 'post_type' => 'px_gallery', 'paged' => $_GET['page_id_all']);
	if(isset($px_node->px_gal_album_cat) && $px_node->px_gal_album_cat <> '' && $px_node->px_gal_album_cat <> '0' && $px_node->px_gal_album_cat <> 'All' ){
		$gallery_category_array = array('px_gallery-category' => "$px_node->px_gal_album_cat");
		$args = array_merge($args, $gallery_category_array);
	}
	$custom_query = new WP_Query($args);
	 ?>
	 <div class="element_size_<?php echo $px_node->gallery_albums_element_size;?>">
        <?php if($px_node->px_gal_album_header_title <> ''){?>
         <header class="px-heading-title">
            <h2 class="px-section-title px-heading-color"><?php echo $px_node->px_gal_album_header_title;?></h2>
          </header>
      <?php }?>
       <!-- Latest Video Start -->
        <div class="latest-video">

            <!-- Minus Column Start -->
            <div class="minus-column">
            <?php if($custom_query->have_posts()):
				$qrystr= "";
				$width 	=325;
				$height	=244;
				
                if ( isset($_GET['page_id']) ) $qrystr = "&page_id=".$_GET['page_id'];
				while ($custom_query->have_posts()) : $custom_query->the_post();
				$pos_class = '';
				$count_post_gallery = '';
				$image_url = px_attachment_image_src(get_post_thumbnail_id($post->ID),$width,$height);
				if($image_url == ''){$pos_class = 'no-image';}
				$px_meta_gallery_options = get_post_meta((int)$post->ID, "px_meta_gallery_options", true);
				if ( $px_meta_gallery_options <> "" ) {
					$px_xmlObject = new SimpleXMLElement($px_meta_gallery_options);
					$count_post_gallery = count($px_xmlObject->gallery);
				}
				?>
                <article <?php post_class($pos_class); ?>>
                    <figure >
                    	<a href="<?php the_permalink();?>">
                    		<?php if($image_url <> ''){?><img src="<?php echo $image_url;?>" alt=""><?php }?>
	                    </a>
                    </figure>
                    <div class="text">
                    	<h2 class="pix-post-title"><a href="<?php the_permalink(); ?>" class="pix-colrhvr"><?php the_title(); ?></a></h2>
                    	<?php if($count_post_gallery <> ''){?><a class="uppercase btn px_bgcolor" href="<?php the_permalink();?>"><?php echo $count_post_gallery;?> <?php if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") { _e("Photos",'Kings Club'); }else{  echo $px_theme_option["trans_photo"];} ?></a><?php }?>
                        <time datetime="<?php echo date_i18n('d-m-Y', strtotime(get_the_date()));?>"><?php echo date_i18n(get_option('date_format'), strtotime(get_the_date()));?></time>
                        
                    </div>
                </article>
                <?php endwhile; endif;?>
            </div>
            <!-- Minus Column End -->
        </div>
         <?php
		// pagination start
		$qrystr = '';
		 if ( $px_node->px_gal_album_pagination == "Show Pagination" and $post_count > $px_node->px_gal_album_media_per_page and $px_node->px_gal_album_media_per_page > 0 ) {
				$qrystr = '';
			echo "<nav class='pagination'><ul>";
			if ( isset($_GET['page_id']) ) $qrystr = "&page_id=".$_GET['page_id'];						
			echo px_pagination( $post_count, $px_node->px_gal_album_media_per_page,$qrystr );
			echo "</ul></nav>";
		}
		// pagination end
		?>
      <!-- Latest Video End -->
      <div class="clear"></div>
	</div>      
                  