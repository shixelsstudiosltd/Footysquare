<?php
	get_header();
 global  $px_theme_option;
 global $withcomments;
	$withcomments = true;
 if(isset($px_theme_option['px_layout'])){ $px_layout = $px_theme_option['px_layout']; }else{ $px_layout = '';} 
 
 if ( $px_layout <> '' and $px_layout  <> "none" and $px_layout  == 'left') :  ?>
        <aside class="left-content col-md-3">
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_theme_option['px_sidebar_left']) ) : endif; ?>
        </aside>
<?php endif;?>
        <div class="<?php px_default_pages_meta_content_class( $px_layout ); ?>">
        <?php px_page_title();?>
	       	<div class="pix-blog blog-medium">
				<div class="div-section login-section">
					<div class="section-title">
						<i class="fa fa-search"></i><p>search result for "<?php if ( isset($_GET['s']) ) echo $_GET['s'];?>"</p>
					</div>
                 <!-- Blog Post Start -->
				 <?php
					
					 //update front end post
				if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == "frontend_post_add") 
				{
					
					$content   = $_POST['post_content'];
					$pid   = $_POST['postid'];
					$title = substr($content, 0, 50);
					$post_type = 'wps_forum_post';
					
					if($title==null && $content==null)
						return false;
						
					//add_filter('wp_insert_post_data', 'update_post_slug');
					//the array of arguements to be inserted with wp_insert_post
					$update_post = array(
					'ID'           => $pid,
					'post_title'    => $title,
					'post_content'  => $content 
					);
					
					//print_r($update_post);
					wp_update_post($update_post);
					//remove_filter('wp_insert_post_data', array(&$this, 'update_post_slug'));
				}

				if ( isset($_GET['s']) ){
					$search_word = $_GET['s'];
					$args=array('post_type'=>'wps_forum_post','taxonomy' => 'wps_forum','s'=>$search_word, 'orderby'=>'ASC','posts_per_page'=>'10');
				}
				
				//f = filter
				if ( isset($_GET['f']) ){
					$filter_term_id = $_GET['f'];
					$args=array('post_type'=>'wps_forum_post','s'=>$search_word,
									'tax_query' => array( 
												array(
													'taxonomy' => 'wps_forum',
													'field'    => 'id',
													'terms'    => $filter_term_id,
													'include_children' => 0,
												),
											), 'orderby'=>'ASC','posts_per_page'=>'50');
				}
					$search_q=new WP_Query($args);
				 ?>
				 <div class="col-lg-3 col-md-4 col-xs-12 no-padding search-list">
					<!--search result-->
					
					<!--club result-->
					
					<?php
						
						$terms_arg = array(
							'orderby'           => 'name', 
							'order'             => 'ASC',
							'hide_empty'        => false, 
							'fields'            => 'all', 
							'slug'              => '',
							'parent'            => '',
							'child_of'          => 0
						); 
						$terms = get_terms( 'wps_forum',$terms_arg);
						
						$clubs = array();
						$countries = array();
						$players = array();
						
						while ( $search_q->have_posts() ) : $search_q->the_post();
						
							$post_terms = wp_get_post_terms( get_the_ID(),'wps_forum');
							
							$keyword_str = strtolower(get_the_title());
							//$keyword_str = str_replace(' ', '-', $keyword_slug);
							
							//step-3
							/*foreach($post_terms as $post_id_val){
								//echo $post_id_val->term_id.',';
								$parent_id = $post_id_val->parent;
								//echo $post_id_val->term_id.' parent = '.$parent_id;
								if($parent_id==61){ //club
									$clubs[] =  $post_id_val->term_id;
								}
								if($parent_id==63){ //player
									$players[] =  $post_id_val->term_id;
								}
								if($parent_id==95){ //country
									$countries[] =  $post_id_val->term_id;
								}
							}*/
							
							//var_dump($post_terms);
							foreach($post_terms as $post_id_val){
								
								//var_dump($post_id_val->parent);
								$parent_id = $post_id_val->parent;
								$parent_name = $post_id_val->name;
								
								
								//echo $keyword_str.'->'.$search_word.'<br/>';
								//echo $parent_name.'->'.$search_word.'<br/><br/>';
								//echo $parent_id;
								
								if (strpos(strtolower($keyword_str),strtolower($search_word)) !== false){ //club
								//echo $parent_id;
								//echo $parent_name."->".$search_word;
									if(strpos(strtolower($parent_name),strtolower($search_word)) !== false){
										if($parent_id==61)
											$clubs[] =  $post_id_val->term_id;
									}
									if(strpos(strtolower($parent_name),strtolower($search_word)) !== false){
										if($parent_id==63)
											$players[] =  $post_id_val->term_id;
									}
									if(strpos(strtolower($parent_name),strtolower($search_word)) !== false){
										if($parent_id==95){//country
											$countries[] =  $post_id_val->term_id;
										}
									}
								}
								
							}
							
							//var_dump($post_terms);
						
						endwhile;						
						?>
						
						
						<?php
							//clubs
							echo '<div class="search-list-items">
									<div class="notification-alert-brown"><i class="fa fa-shield"></i>clubs</div>
									<div class="search-result-lists">';
										$clubs = array_count_values($clubs);
										while ($fruit_name = current($clubs)) {
											echo '<div class="search-result-list">';
													$term__parent_post_id=get_term_meta(key($clubs),'parentpostid', true);
													echo '<a href="?p='.$term__parent_post_id.'">';
														if(!$club) 
															echo '<div class="default_ball_search"></div>';
														//echo get_term(key($clubs),'wps_forum')->name." ($fruit_name).";
														echo get_term(key($clubs),'wps_forum')->name;
													echo '</a>';
											echo '</div>';
											next($clubs);
										}
										if(!$clubs){
											echo '<div class="search-result-list">
													no result found!
												</div>';
										}
								echo '</div>
								</div>';
								
							//countries
							echo '<div class="search-list-items">
									<div class="notification-alert-brown"><i class="fa fa-globe"></i>countries</div>
									<div class="search-result-lists">';
										$countries = array_count_values($countries);
										while ($fruit_name = current($countries)) {
											$get_entry_name = get_term(key($countries),'wps_forum')->name;
											echo '<div class="search-result-list">';
												$term__parent_post_id=get_term_meta(key($countries),'parentpostid', true);
												echo '<a href="?p='.$term__parent_post_id.'">';
													echo '<span class="flag flag-';
														echo get_country_code($get_entry_name);
													echo '"></span>';
													//echo $get_entry_name." ($fruit_name).";
													echo $get_entry_name;
												echo '</a>';
											echo '</div>';
											next($countries);
										}
										if(!$countries){
											echo '<div class="search-result-list">
													no result found!
												</div>';
										}
								echo '</div>
								</div>';
							
							//players
							echo '<div class="search-list-items">
									<div class="notification-alert-brown"><i class="fa fa-users"></i>players</div>
									<div class="search-result-lists">';
										$players = array_count_values($players);
										while ($fruit_name = current($players)) {
											echo '<div class="search-result-list">';
												$term__parent_post_id=get_term_meta(key($players),'parentpostid', true);
												echo '<a href="?p='.$term__parent_post_id.'">';
													if(!$club) 
														echo '<div class="default_ball_search"></div>';
													//echo get_term(key($players),'wps_forum')->name." ($fruit_name).";
													echo get_term(key($players),'wps_forum')->name;
												echo '</a>';
											echo '</div>';
											next($players);
										}
										if(!$players){
											echo '<div class="search-result-list">
													no result found!
												</div>';
										}
								echo '</div>
								</div>';
						?>
					
					<!--user result-->
					<div class="search-list-items">
						<div class="notification-alert-brown"><i class="fa fa-user"></i>users</div>
						<div class="search-result-lists">
							<div class="search-result-list">
								<?php if(!$club) echo '<span class="flag flag-ng"></span>';?>nigeria (2).
							</div>
							<div class="search-result-list">
								<?php if(!$club) echo '<span class="flag flag-za"></span>';?>south africa (5).
							</div>
							<div class="search-result-list">
								<?php if(!$club) echo '<span class="flag flag-bd"></span>';?>bangladesh (12).
							</div>
							<div class="search-result-list">
								<?php if(!$club) echo '<span class="flag flag-no"></span>';?>norway (10).
							</div>
						</div>
					</div>
					
				 </div>
				 
				 <div class="col-lg-9 col-md-8 col-xs-12 search-result-content">
				 <?php
					echo '<div id="shoutbox-content" class="'.$search_word.'"></div>';
						echo '<div class="col-lg-12 see-all-style see-all" id="shout-more"><i class="fa fa-angle-down view_more"></i></div>';
					echo '</div>'; //end inner div
				?>
				 </div>
				<?php
                	if ( isset($_GET['f']) )
						echo '<a class="footy-btn back-search-btn" href="?s='.$search_word.'">back</a>';
             	?> 
				</div>
			</div>
                                   
         </div>
	  <?php
		if ( $px_layout <> '' and $px_layout  <> "none" and $px_layout  == 'right') :  ?>
		<aside class="left-content col-md-3">
			<?php 
            if(isset($px_theme_option['px_sidebar_right'])){
                if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_theme_option['px_sidebar_right']) ) : endif;
            }else{
                if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-1') ) : endif;
            }
      		?>
		</aside>
	<?php endif; ?>

<?php get_footer();?>
<!-- Columns End -->