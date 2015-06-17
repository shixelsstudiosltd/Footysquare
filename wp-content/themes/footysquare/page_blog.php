<?php
 	global $px_node,$post,$px_theme_option,$px_counter_node,$px_meta_page; 
	$image_url = $post_order ='';
   	if(isset($px_node->var_pb_blog_order)){
		$post_order = $px_node->var_pb_blog_order;
	}else{
		$post_order ='DESC';
	}
	if ( !isset($px_node->var_pb_blog_num_post) || empty($px_node->var_pb_blog_num_post) ) { $px_node->var_pb_blog_num_post = -1; }
		if($px_node->var_pb_blog_view =="blog-carousel"){
			$clses= 'blog-vertical';
			
			$divend= '';
		}else{
			$clses ='';
			$divend = '';
		}
	?>
	<div class="element_size_<?php echo $px_node->blog_element_size; ?>">
    	<?php
		if($px_node->var_pb_featured_cat <> '' && $px_node->var_pb_blog_view <> 'blog-home' && $px_node->var_pb_blog_view <> 'blog-carousel'){
			$args = array('posts_per_page' => "3",  'category_name' => "$px_node->var_pb_featured_cat",'order'=>"$post_order");
            $custom_query = new WP_Query($args);
			if($custom_query->have_posts()):
		?>
            <div class="pix-blog blog-carousel-view">
                <div class="cycle-slideshow"
                    data-cycle-fx=scrollHorz
                    pagination=".cycle-pager"
                    data-cycle-slides=">article"
                    data-cycle-timeout=3000>
                    <div class="cycle-pager"></div>
                    <?php 
                        px_enqueue_cycle_script();
                        while ($custom_query->have_posts()) : $custom_query->the_post();
                        $image_url = px_get_post_img_src($post->ID,768,403); 
                       	if($image_url <> ""){ ?>
                        	<article>
                                <figure><a href="<?php the_permalink(); ?>"><img src="<?php echo $image_url;?>" alt=""></a></figure>
                                <div class="text">
                                    <h2 class="pix-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <?php px_get_the_excerpt(165,false, ' ...'); ?>
                                </div>
                      		</article>
                      <?php }?>
                       <?php endwhile; ?>
                   </div>
             </div>
        <?php endif; 
		}
		?> 
    	<div class="pix-blog <?php echo $px_node->var_pb_blog_view; ?> <?php echo $clses; ?>">
     	<!-- Blog Start -->
        <?php 
			$blog_category_name = '';
			if (empty($_GET['page_id_all'])) $_GET['page_id_all'] = 1;
            $args = array('posts_per_page' => "-1", 'paged' => $_GET['page_id_all'], 'post_status' => 'publish','order'=>"$post_order");
			if(isset($px_node->var_pb_blog_cat) && $px_node->var_pb_blog_cat <> ''){
				
				$blog_category_array = array('category_name' => "$px_node->var_pb_blog_cat");
				$args = array_merge($args, $blog_category_array);
			}
            $custom_query = new WP_Query($args);
            $post_count = $custom_query->post_count;
            $count_post = 0;
            $args = array('posts_per_page' => "$px_node->var_pb_blog_num_post", 'paged' => $_GET['page_id_all'],'order'=>"$post_order");
			if(isset($px_node->var_pb_blog_cat) && $px_node->var_pb_blog_cat <> ''){
				$blog_category_array = array('category_name' => "$px_node->var_pb_blog_cat");
				$args = array_merge($args, $blog_category_array);
			}
            $custom_query = new WP_Query($args);
            $px_counter = 0;
			if($px_node->var_pb_blog_view =="blog-large"){
				$width = 768;
				$height = 403;
			}else{
				$width = 325;
				$height = 244;
			}
			if ($px_node->var_pb_blog_view =="blog-home"){ $blog_category_name = $category_link = '';
				?>
                       	 <div class="tabs horizontal">
                        	  	<header class="pix-heading-title">
								<?php	if ($px_node->var_pb_blog_title <> '') { ?>
                                <h2 class="pix-heading-color pix-section-title"><?php echo $px_node->var_pb_blog_title; ?></h2>
                                <?php  } ?>
                                <ul id="myTab" class="nav nav-tabs">
                                    <li class=" active"><a href="#blog-headlines<?php echo $px_counter_node; ?>" data-toggle="tab"><?php if($px_theme_option["trans_switcher"] == "on") { _e("Headlines",'Kings Club'); }else{  echo $px_theme_option["trans_headlines"];} ?></a></li>
                                    <li class=""><a href="#blog-recent<?php echo $px_counter_node; ?>" data-toggle="tab"><?php if($px_theme_option["trans_switcher"] == "on") { _e("Recent Posts",'Kings Club'); }else{  echo $px_theme_option["trans_recent"];} ?></a></li>
                                    <li class=""><a href="#blog-popular<?php echo $px_counter_node; ?>" data-toggle="tab"><?php if($px_theme_option["trans_switcher"] == "on") { _e("Popular Posts",'Kings Club'); }else{  echo $px_theme_option["trans_popular"];} ?></a></li>
                            	</ul>
                            	</header>
                            <div class="tab-content">
                             <div id="blog-headlines<?php echo $px_counter_node; ?>" class="blog-headlines tab-pane fade in active">
                             <div class="pix-feature">
							<?php
							$counter_blog = 0;
							$sticky = get_option( 'sticky_posts' );
							$args = array('posts_per_page' => "$px_node->var_pb_blog_num_post", 'post__in' => $sticky, 'paged' => $_GET['page_id_all'],  'order' => "DESC");
							if(isset($px_node->var_pb_blog_cat) && $px_node->var_pb_blog_cat <> ''){
								$row_cat = $wpdb->get_row("SELECT * from ".$wpdb->prefix."terms WHERE slug = '" . $px_node->var_pb_blog_cat ."'" );
								if(isset($row_cat)){
									$blog_category_name = $row_cat->name;
									$category_link = get_category_link( $row_cat->term_id );
								}
								$blog_category_array = array('category_name' => "$px_node->var_pb_blog_cat");
								$args = array_merge($args, $blog_category_array);
							}
							$custom_query = new WP_Query($args);
                            while ($custom_query->have_posts()) : $custom_query->the_post();
							if(is_sticky()){
								$counter_blog++;
								$post_xml = get_post_meta($post->ID, "post", true);	
								$blog_classes = array();
								if ( $post_xml <> "" ) {
									$px_xmlObject = new SimpleXMLElement($post_xml);
									$no_image = '';
									$image_url = px_get_post_img_src($post->ID, $width, $height);
									if($image_url == ""){
										$blog_classes[] = 'no-image';
									}
									}else{
										
										$post_view = '';
										$no_image = '';	
										$image_url_full = '';
									}	
								$format = get_post_format( $post->ID );
								if($px_node->var_pb_blog_featured_post == 'No' && $counter_blog == 1){
									$counter_blog = 2;
									$blog_classes[] = 'full-width-post';
								}
								if($counter_blog == 1){
									$blog_classes[] = 'featured-post';
								?>
								<article <?php post_class($blog_classes); ?>>
									<?php if($image_url <> ""){?>
										<figure>
                                        	<a href="<?php the_permalink(); ?>"><img src="<?php echo $image_url;?>" alt=""></a>
                                        	<?php px_featured();?>
                                            <figcaption>
                                            	<h2 class="pix-post-title"><a href="<?php the_permalink(); ?>" ><?php if ( strlen(get_the_title()) > 45){echo substr(get_the_title(),0,45);} else { the_title();} if ( strlen(get_the_title()) > 45) echo  "...";?></a></h2>
                                            </figcaption>
                                        </figure>
									<?php }?>
									<div class="text">
                                    		<ul class="post-options">
													<?php 
                                                        if ( isset($blog_category_name) && $blog_category_name <> '' ){
															echo "<li>";
															echo '<a rel="tag" href="'.$category_link.'">'.$blog_category_name.'</a>';
															echo "</li>";
                                                        }
                                                    ?>
                                                     <li>
                                                        <time datetime="<?php echo date('d-m-y',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option('date_format'),strtotime(get_the_date()));?></time>
                                                    </li>
                                            </ul>
										 <?php 
											px_get_the_excerpt($px_node->var_pb_blog_excerpt,false);
											wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'Kings Club' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
										   ?>
										<div class="blog-bottom">
												<a href="<?php the_permalink(); ?>" class="btnreadmore btn pix-bgcolrhvr"><?php echo $px_theme_option['trans_read_more']; ?></a>
                                                 <?php
													if ( comments_open() ) {  
														echo "<span class='px-comments'><i class='comments'></i> "; comments_popup_link( __( '0', 'Kings Club' ) , __( '1', 'Kings Club' ), __( '%', 'Kings Club' ) ); 
													}
                                                ?>
										</div>
									</div>
								</article></div>
								<?php if($post_count>1){?><div class="blog-listing-text"><?php }?>
								<?php 
								} else {
								?>
									<article <?php post_class($blog_classes); ?>>
										<div class="text">
											<h2 class="pix-post-title"><a href="<?php the_permalink(); ?>" ><?php the_title();?></a></h2>
											<ul class="post-options">
												
													<?php 
                                                        if ( isset($blog_category_name) && $blog_category_name <> '' ){
															echo "<li>";
															echo '<a rel="tag" href="'.$category_link.'">'.$blog_category_name.'</a>';
															echo "</li>";
                                                        }
                                                    ?>
                                                     <li>
                                                        <time datetime="<?php echo date('d-m-y',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option('date_format'),strtotime(get_the_date()));?></time>
                                                    </li>
                                                    <?php
                                                        if ( comments_open() ) {  
                                                            echo "<li class='px-comments'>"; comments_popup_link( __( '0', 'Kings Club' ) , __( '1', 'Kings Club' ), __( '%', 'Kings Club' ) ); 
                                                        }
                                                ?>
                                            </ul>
										</div>
								</article>
								<?php
								}
							}
                            endwhile; 
                            ?>
                            <?php if($post_count>1){?></div><?php }?>
                                    </div>
                                    <div id="blog-recent<?php echo $px_counter_node; ?>" class="blog-headlines tab-pane fade in "> 
                                    	<div class="pix-feature">
										<?php
										
           								 $custom_query = new WP_Query($args);
                                        $counter_blog = 0;
										if(isset($px_node->var_pb_blog_cat) && $px_node->var_pb_blog_cat <> ''){
											$row_cat = $wpdb->get_row("SELECT * from ".$wpdb->prefix."terms WHERE slug = '" . $px_node->var_pb_blog_cat ."'" );
											if(isset($row_cat)){
												$blog_category_name = $row_cat->name;
												$category_link = get_category_link( $row_cat->term_id );
											}
										}
                                        while ($custom_query->have_posts()) : $custom_query->the_post();
                                        $counter_blog++;
                                        $post_xml = get_post_meta($post->ID, "post", true);	
                                        $blog_classes = array();
                                        if ( $post_xml <> "" ) {
                                            $px_xmlObject = new SimpleXMLElement($post_xml);
                                            $no_image = '';
                                            $image_url = px_get_post_img_src($post->ID, $width, $height);
                                            if($image_url == ""){
                                                $blog_classes[] = 'no-image';
                                            }
										}else{
											$post_view = '';
											$no_image = '';	
											$image_url_full = '';
										}	
                                        $format = get_post_format( $post->ID );
										if($px_node->var_pb_blog_featured_post == 'No' && $counter_blog == 1){
											$counter_blog = 2;
											$blog_classes[] = 'full-width-post';
										}
                                        if($counter_blog == 1){
                                            $blog_classes[] = 'featured-post';
                                        ?>
                                        <article <?php post_class($blog_classes); ?>>
									<?php if($image_url <> ""){?>
										<figure>
                                        	<a href="<?php the_permalink(); ?>"><img src="<?php echo $image_url;?>" alt=""></a>
                                            <?php px_featured();?>
                                        	<figcaption>
                                            	<h2 class="pix-post-title"><a href="<?php the_permalink(); ?>" ><?php if ( strlen(get_the_title()) > 45){echo substr(get_the_title(),0,45);} else { the_title();} if ( strlen(get_the_title()) > 45) echo  "...";?></a></h2>
                                            </figcaption>
                                        </figure>
									<?php }?>
									<div class="text">
                                    		<ul class="post-options">
													<?php 
                                                        if ( isset($blog_category_name) && $blog_category_name <> '' ){
															echo "<li>";
																echo '<a rel="tag" href="'.$category_link.'">'.$blog_category_name.'</a>';
															echo "</li>";
                                                        }
                                                    ?>
                                                     <li>
                                                        <time datetime="<?php echo date('d-m-y',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option('date_format'),strtotime(get_the_date()));?></time>
                                                    </li>
                                            </ul>
										 <?php 
											px_get_the_excerpt($px_node->var_pb_blog_excerpt,false);
											wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'Kings Club' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
										   ?>
										  
										<div class="blog-bottom">
												<a href="<?php the_permalink(); ?>" class="btnreadmore btn pix-bgcolrhvr"><i class="fa fa-plus"></i>READ MORE</a>
                                                 <?php
													if ( comments_open() ) {  
														echo "<span class='px-comments'><i class='fa fa-comments'></i> "; comments_popup_link( __( '0', 'Kings Club' ) , __( '1', 'Kings Club' ), __( '%', 'Kings Club' ) ); 
													}
                                                ?>
										</div>
									</div>
								</article></div>
                                        <?php if($post_count>1){?><div class="blog-listing-text"><?php }?>
                                        <?php 
                                        } else {
                                        ?>
                                            <article <?php post_class($blog_classes); ?>>
                                                <div class="text">
                                                    <h2 class="pix-post-title"><a href="<?php the_permalink(); ?>" ><?php the_title();?></a></h2>
                                                    <ul class="post-options">
												
															<?php 
																if ( isset($blog_category_name) && $blog_category_name <> '' ){
																	echo "<li>";
																		echo '<a rel="tag" href="'.$category_link.'">'.$blog_category_name.'</a>';
																	echo "</li>";
																}
															?>
                                                            
                                                             <li>
                                                                <time datetime="<?php echo date('d-m-y',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option('date_format'),strtotime(get_the_date()));?></time>
                                                            </li>
                                                            <?php
                                                           
                                               
                                                                if ( comments_open() ) {  
                                                                    echo "<li class='px-comments'>"; comments_popup_link( __( '0', 'Kings Club' ) , __( '1', 'Kings Club' ), __( '%', 'Kings Club' ) ); 
                                                                }
                                                       
                                                        ?>
                                                    </ul>
                                                </div>
                                        </article>
                                        <?php
                                        }
                                        endwhile; 
                                        ?>
                                        <?php if($post_count>1){?></div><?php }?>
                     
                                    </div>
                                    <div id="blog-popular<?php echo $px_counter_node; ?>" class="blog-headlines tab-pane fade in ">
                                    	<div class="pix-feature">
							<?php
                            $counter_blog = 0;
							$args = array('posts_per_page' => "$px_node->var_pb_blog_num_post", 'paged' => $_GET['page_id_all'], 'orderby' => "comment_count",  'order' => "DESC");
							if(isset($px_node->var_pb_blog_cat) && $px_node->var_pb_blog_cat <> ''){

								$row_cat = $wpdb->get_row("SELECT * from ".$wpdb->prefix."terms WHERE slug = '" . $px_node->var_pb_blog_cat ."'" );
								if(isset($row_cat)){
									$blog_category_name = $row_cat->name;
									$category_link = get_category_link( $row_cat->term_id );
								}
								
								$blog_category_array = array('category_name' => "$px_node->var_pb_blog_cat");
								$args = array_merge($args, $blog_category_array);
							}
							
							$custom_query = new WP_Query($args);
                            while ($custom_query->have_posts()) : $custom_query->the_post();
							$counter_blog++;
                            $post_xml = get_post_meta($post->ID, "post", true);	
                            $blog_classes = array();
                            if ( $post_xml <> "" ) {
                                $px_xmlObject = new SimpleXMLElement($post_xml);
                                $no_image = '';
								$format = get_post_format( $post->ID );
                                $image_url = px_get_post_img_src($post->ID, $width, $height);
                                if($image_url == ""){
                                    $blog_classes[] = 'no-image';
                                }
							}else{
								$post_view = '';
								$no_image = '';	
								$image_url_full = '';
							}	
                            //$format = get_post_format( $post->ID );
                            if($px_node->var_pb_blog_featured_post == 'No' && $counter_blog == 1){
								$counter_blog = 2;
								$blog_classes[] = 'full-width-post';
							}
							if($counter_blog == 1){
								$blog_classes[] = 'featured-post';
                            ?>
                            <article <?php post_class($blog_classes); ?>>
									<?php if($image_url <> ""){?>
										<figure>
                                        	<a href="<?php the_permalink(); ?>"><img src="<?php echo $image_url;?>" alt=""></a>
                                            <?php px_featured();?>
                                        	<figcaption>
                                            	
                                            	<h2 class="pix-post-title"><a href="<?php the_permalink(); ?>" ><?php if ( strlen(get_the_title()) > 45){echo substr(get_the_title(),0,45);} else { the_title();} if ( strlen(get_the_title()) > 45) echo  "...";?></a></h2>
                                            </figcaption>
                                        </figure>
									<?php }?>
									<div class="text">
                                    		<ul class="post-options">
													<?php 
                                                        if ( isset($blog_category_name) && $blog_category_name <> '' ){
															echo "<li>";
															echo '<a rel="tag" href="'.$category_link.'">'.$blog_category_name.'</a>';
															echo "</li>";
                                                        }
                                                    ?>
                                                     <li>
                                                        <time datetime="<?php echo date('d-m-y',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option('date_format'),strtotime(get_the_date()));?></time>
                                                    </li>
                                            </ul>
										 <?php 
											px_get_the_excerpt($px_node->var_pb_blog_excerpt,false);
											wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'Kings Club' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
										   ?>
										  
										<div class="blog-bottom">
												<a href="<?php the_permalink(); ?>" class="btnreadmore btn pix-bgcolrhvr"><i class="fa fa-plus"></i>READ MORE</a>
                                                 <?php
													if ( comments_open() ) {  
														echo "<span class='px-comments'><i class='fa fa-comments'></i> "; comments_popup_link( __( '0', 'Kings Club' ) , __( '1', 'Kings Club' ), __( '%', 'Kings Club' ) ); 
													}
                                                ?>
										</div>
									</div>
								</article></div>
                            <?php if($post_count>1){?><div class="blog-listing-text"><?php }?>
							<?php 
							} else {
							?>
								<article <?php post_class($blog_classes); ?>>
                                    <div class="text">
                                      	<h2 class="pix-post-title"><a href="<?php the_permalink(); ?>" ><?php the_title();?></a></h2>
                                        <ul class="post-options">
												
                                                <?php 
													if ( isset($blog_category_name) && $blog_category_name <> '' ){
														echo "<li>";
															echo '<a rel="tag" href="'.$category_link.'">'.$blog_category_name.'</a>';
														echo "</li>";
													}
												?>
                                                 <li>
                                                 	<time datetime="<?php echo date('d-m-y',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option('date_format'),strtotime(get_the_date()));?></time>
                                                </li>
                                                <?php
                                                    if ( comments_open() ) {  
                                                        echo "<li class='px-comments'>"; comments_popup_link( __( '0', 'Kings Club' ) , __( '1', 'Kings Club' ), __( '%', 'Kings Club' ) ); 
                                                    }
                                            ?>
                                        </ul>
                                    </div>
                           	 </article>
							<?php
							}
                            endwhile; 
                            ?>
                           			<?php if($post_count>1){?></div><?php }?>
                                   </div>
                             </div>
                       </div>
                      
                    <?php
		
			} else if($px_node->var_pb_blog_view =="blog-carousel"){

		
		if($custom_query->have_posts()):
		?>
                        
                        <?php if ($px_node->var_pb_blog_title <> '') { ?>
                            <header class="pix-heading-title">
                                <h2 class="pix-heading-color pix-section-title"><?php echo $px_node->var_pb_blog_title; ?></h2>
                            </header>
                        <?php }?>
                        
						<?php
						$slider_pagination = array();
							echo '<div class="cycle-slideshow" 
								data-cycle-fx=fade
								data-cycle-timeout=3000
								data-cycle-auto-height=container
								data-cycle-slides="article"
								
								data-cycle-random=false
								data-cycle-pager="#banner-pager'.$px_counter_node.'"
								data-cycle-pager-template="">';
							while ($custom_query->have_posts()) : $custom_query->the_post();
								$image_url_full = px_get_post_img_src($post->ID, '470' ,'353');
								if($image_url_full <> ''){
								$slider_pagination[] = get_the_title();
								?>
									<article class="<?php echo $post->ID; ?>">
                                        <?php if($image_url_full <> ''){?><a href="<?php the_permalink(); ?>"><img src="<?php echo $image_url_full;?>" alt=""></a><?php }?>
                                               <div class="caption">
                                                	<h2><a href="<?php the_permalink(); ?>"><?php if ( strlen(get_the_title()) > 50){echo substr(get_the_title(),0,50);} else { the_title();} if ( strlen(get_the_title()) > 50) echo  "...";?></a></h2>
                                               </div> 
                                    </article>
                                <?php
								}
							endwhile;
				echo '</div>';

				if(is_array($slider_pagination) && count($slider_pagination)>0){
					$pagination_no = 0;
					echo '<div class="sliderpagination">
					
						<ul id="banner-pager'.$px_counter_node.'" class="banner-pager">';
						
								
									foreach($slider_pagination as $slider){
										$pagination_no++;
										$slider_title = substr($slider,0,50); if ( strlen($slider) > 50) $slider_title .= "...";
										echo '<li>
												<div class="pager-desc">
													
													<span class="cs-desc">'.$slider_title.'</span>
												</div>
											</li>';
									}
									
								
						echo '</ul></div>';
						
				
				}
		
			px_enqueue_cycle_script();
		endif;
		wp_reset_query();
		
            } else { 	
					
			if ($px_node->var_pb_blog_title <> '') { ?>
                <header class="pix-heading-title">
                    <?php	if ($px_node->var_pb_blog_title <> '') { ?>
                    <h2 class="pix-heading-color pix-section-title"><?php echo $px_node->var_pb_blog_title; ?></h2>
					<?php  } ?>
                </header>
        <?php  }  
            while ($custom_query->have_posts()) : $custom_query->the_post();
				$post_xml = get_post_meta($post->ID, "post", true);	
				$blog_classes = array();
				if ( $post_xml <> "" ) {
					$px_xmlObject = new SimpleXMLElement($post_xml);
					$no_image = '';
 					$image_url = px_get_post_img_src($post->ID,$width,$height);
					$image_url_full = px_get_post_img_src($post->ID, '' ,'');
					if($image_url == ""){
						$blog_classes[] = 'no-image';
					}
				}else{
					
					$post_view = '';
					$no_image = '';	
					$image_url_full = '';
				}	
				//$format = get_post_format( $post->ID );
				$format = get_post_format( $post->ID );
				?>
				<!-- Blog Post Start -->
                <article <?php post_class($blog_classes); ?>>
                    <?php if($image_url <> ""){?>
                        <figure><a href="<?php the_permalink(); ?>"><img src="<?php echo $image_url;?>" alt=""></a></figure>
                    <?php }?>
                    <div class="text">
                    <?php 
						if($px_node->var_pb_blog_view == 'blog-medium'){
							  $before_cat = "<ul class='post-options blog-medium-options'><li>";
								$categories_list = get_the_term_list ( get_the_id(), 'category', $before_cat, ', ', '</li></ul>' );
								if ( $categories_list ){
									printf( __( '%1$s', 'Kings Club'),$categories_list );
								}
						}
					?>
                      <h2 class="pix-post-title"><a href="<?php the_permalink(); ?>" ><?php the_title(); ?>.</a></h2>
                         <?php 
 							px_get_the_excerpt($px_node->var_pb_blog_excerpt,false);
							wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'Kings Club' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
                           ?>
                        <div class="blog-bottom">
                     		<?php 
							if($px_node->var_pb_blog_view <> 'blog-medium'){
								px_posted_on(true,false,false,false,true,false);
							}
							?>
                         	<?php if($px_node->var_pb_blog_view =="blog-large"){?>
                            	<a href="<?php the_permalink(); ?>" class="btnreadmore btn pix-bgcolrhvr"><i class="fa fa-plus"></i><?php if($px_theme_option["trans_switcher"] == "on") {  _e("READ MORE",'Kings Club'); }else{  echo $px_theme_option["trans_read_more"];}?></a>
                            <?php } ?>
                     	</div>
                    </div>
                </article>
				<!-- Blog Post End -->
               	<?php endwhile;  ?>
                 	<!-- Blog End -->
                    <?php 
					
					} ?>
     
    			</div>   
                <?php
                $qrystr = '';
               if ( $px_node->var_pb_blog_pagination == "Show Pagination" and $post_count > $px_node->var_pb_blog_num_post and $px_node->var_pb_blog_num_post > 0 ) {
                	echo "<nav class='pagination'><ul>";
                    	if ( isset($_GET['page_id']) ) $qrystr = "&amp;page_id=".$_GET['page_id'];
                        	echo px_pagination($post_count, $px_node->var_pb_blog_num_post,$qrystr);
                    echo "</ul></nav>";
                }
                 // pagination end
             ?>
           </div>