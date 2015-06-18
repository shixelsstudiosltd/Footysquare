<?php
	global $px_node,$post, $px_event_meta,$px_theme_option,$px_counter_node,$wpdb;
	
	date_default_timezone_set('UTC');
	$current_time = strtotime(current_time('m/d/Y H:i', $gmt = 0));
	if($px_theme_option['trans_days'] and $px_theme_option['trans_days'] <> ''){
		$trans_days=$px_theme_option['trans_days'];
	}else{
		$trans_days = __('Days','Kingsclub');
	}
	if($px_theme_option['trans_hours'] and $px_theme_option['trans_hours'] <> ''){
		$trans_hours=$px_theme_option['trans_hours'];
	}else{
		$trans_hours = __('Hours','Kingsclub');
	}
	if($px_theme_option['trans_minutes'] and $px_theme_option['trans_minutes'] <> ''){
		$trans_minutes=$px_theme_option['trans_minutes'];
	}else{
		$trans_minutes = __('Minutes','Kingsclub');
	}
	if($px_theme_option['trans_seconds'] and $px_theme_option['trans_seconds'] <> ''){
		$trans_seconds=$px_theme_option['trans_seconds'];
	}else{
		$trans_seconds = __('Seconds','Kingsclub');
	} 
	if ( !isset($px_node->var_pb_event_per_page) || empty($px_node->var_pb_event_per_page) ) { $px_node->var_pb_event_per_page = -1; }
	if ( $px_node->var_pb_event_pagination == "Single Page") { $px_node->var_pb_event_per_page = $px_node->var_pb_event_per_page; }
	if($px_theme_option["trans_switcher"] == "on") {  
		$start_fixtures = __("Kick-off",'Kings Club'); }else{ $start_fixtures = $px_theme_option["trans_event_start"];
	}
	
	$meta_compare = '';
    $filter_category = '';
 	if ( $px_node->var_pb_event_type == "Fixtures" ) $meta_compare = ">";
    else if ( $px_node->var_pb_event_type == "Results" ) $meta_compare = "<";
	$row_cat = px_get_term_object($px_node->var_pb_event_category);
    $row_cat = $wpdb->get_row("SELECT * from ".$wpdb->prefix."terms WHERE slug = '" . $px_node->var_pb_event_category ."'" );
    if (isset($_GET['filter_category']) ) {
			$filter_category = $_GET['filter_category'];
	}else {
    	if(isset($row_cat->slug)){
        	$filter_category = $row_cat->slug;
    	}
	}
	$px_counter_events = 0;
	if ( empty($_GET['page_id_all']) ){ $_GET['page_id_all'] = 1;}
    if ( $px_node->var_pb_event_type == "All" ) {
		$args = array(
			'posts_per_page'			=> "-1",
			'paged'						=> $_GET['page_id_all'],
			'post_type'					=> 'events',
			'post_status'				=> 'publish',
			'orderby'					=> 'meta_value',
			'order'						=> 'ASC',
		);
		}
		else {
		$args = array(
			'posts_per_page'			=> "-1",
			'paged'						=> $_GET['page_id_all'],
			'post_type'					=> 'events',
			'post_status'				=> 'publish',
			'meta_key'					=> 'px_event_from_date_time',
			'meta_value'				=> $current_time,
			'meta_compare'				=> $meta_compare,
			'orderby'					=> 'meta_value',
			'order'						=> 'ASC',
		);
	}
	if(isset($filter_category) && $filter_category <> '' && $filter_category <> '0' && $filter_category <> 'All' ){
		$event_category_array = array('event-category' => "$filter_category");
		$args = array_merge($args, $event_category_array);
	}
	$custom_query = new WP_Query($args);
	$count_post = 0;
	$count_post = $custom_query->post_count;
	?>
   	<div class=" element_size_<?php echo $px_node->event_element_size; ?>"> 
 		<div class="tabs horizontal">
				<?php 
				if($px_node->var_pb_event_filterable == 'Yes'){
				 $qrystr= "";
 					?>
					   <div class="fluid-tab-horizontal">
						<ul id="myTab" class="nav nav-tabs">
							<?php
							if ( isset($_GET['page_id']) ) $qrystr = "page_id=".$_GET['page_id'];
								if( isset($px_node->var_pb_event_category) && $px_node->var_pb_event_category <> "" && $px_node->var_pb_event_category <> "0" && $px_node->var_pb_event_category <> "All"){
										$categories = get_categories( array('child_of' => "$row_cat->term_id", 'taxonomy' => 'event-category', 'hide_empty' => 1) );
									?>
									<li <?php if($row_cat->slug==$filter_category){echo 'class="active"';}?>>
                                <a href="?<?php echo $qrystr."&filter_category=".$row_cat->slug?>"><?php echo $row_cat->name?></a>
                                </li>
									<?php
									}else{
 										$categories = get_categories( array('taxonomy' => 'event-category', 'hide_empty' => 1) );
									}
										foreach ($categories as $category) {
 							?>
								<li <?php if($category->slug==$filter_category){echo 'class="active"';}?>>
                                <a href="?<?php echo $qrystr."&filter_category=".$category->slug?>"><?php echo $category->cat_name?></a>
                                </li>
						   <?php }?>
						</ul>
					   </div> 
					   <?php 
					   
					   print_r($categories);
					   
					}?>
                    <div class="tab-content">
                        <div id="tab11" class="tab-pane fade in  active">
                                <?php 
                                if($px_node->var_pb_featured_post <> '' && $px_node->var_pb_featured_post <> '0'){ 
								$hours = '00';
								$mints = '00';
                                    $featured_args = array(
                                            'posts_per_page'			=> "1",
                                            'paged'						=> $_GET['page_id_all'],
                                            'post_type'					=> 'events',
                                            'event-category' 			=> "$px_node->var_pb_featured_post",
                                            'meta_key'					=> 'px_event_from_date_time',
                                            'meta_value'				=> $current_time,
                                            'meta_compare'				=> ">",
                                            'orderby'					=> 'meta_value',
                                            'post_status'				=> 'publish',
                                            'order'						=> 'ASC',
                                         );
										 
                                $px_featured_post= new WP_Query($featured_args);
                                while ($px_featured_post->have_posts()) : $px_featured_post->the_post();	
                                    $event_from_date = get_post_meta($post->ID, "px_event_from_date", true);
                                    $px_featured_meta = get_post_meta($post->ID, "px_event_meta", true);
   									$px_event_from_date_time = get_post_meta($post->ID, "px_event_from_date_time", true);

									$year_event = date("Y", strtotime($event_from_date));
                                    $month_event = date("m", strtotime($event_from_date));
                                    $date_event = date("d", strtotime($event_from_date));
                                    if ( $px_featured_meta <> "" ) {
                                    	$px_featured_event_meta = new SimpleXMLElement($px_featured_meta);
										
										if ( $px_featured_event_meta->event_all_day != "on" ) {
											$hours = date("H",$px_event_from_date_time);
											$mints = date("i",$px_event_from_date_time);										
										} else {
											$hours = '00';
											$mints = '00';
										}
                                    }
									$width = 768;
									$height = 403;
									$image_url = px_get_post_img_src($post->ID, $width, $height);
                                    px_enqueue_countdown_script();
                                ?>
                                <div class="featured-event"  <?php if($image_url <> ''){?>style="background-image: url(<?php echo $image_url;?>)"<?php }?>>
                                    <div class="featured-inn">
                                    	<span class="featured-title"><?php if(isset($px_node->var_pb_featuredevent_title) && $px_node->var_pb_featuredevent_title <> '')echo $px_node->var_pb_featuredevent_title;?></span>
                                        <div class="pix-sc-team">
                                            <ul>
                                                <?php if(isset($px_featured_event_meta->var_pb_event_team1)and $px_featured_event_meta->var_pb_event_team1 <> '0' and $px_featured_event_meta->var_pb_event_team1 <> ''){?>
                                                <li>
                                                    <figure>
                                                        <?php
                                                        
                                                        $team1_row = px_get_term_object($px_featured_event_meta->var_pb_event_team1);
                                                         $team_img1 = px_team_data_front($team1_row->term_id);
                                                        
                                                        if($team_img1[0] <> ''){
                                                        ?>
                                                            <img alt="" src="<?php echo $team_img1[0];?>">
                                                        <?php }?>
                                                        
                                                    </figure>
                                                </li>
                                                <?php }?>
                                                <li class="sec-section">
                                                    <h2><?php the_title();?></h2>
                                                    <div class="time-sec">
                                                            <time><?php if(isset($event_from_date) && $event_from_date <> '')echo date_i18n(get_option('date_format'), strtotime($event_from_date)).',';?></time>
                                                            ,
                                                            <?php 
                                                                if ( $px_featured_event_meta->event_all_day != "on" ) {
                                                                   // echo $px_featured_event_meta->event_time;
                                                                }else{
                                                                   // _e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');
                                                                }
                                                            ?>
                                                            <?php //echo $px_featured_event_meta->event_address;?>
                                                       
                                                    </div>
                                                </li>
                                                <?php if(isset($px_featured_event_meta->var_pb_event_team2) and $px_featured_event_meta->var_pb_event_team2 <> '0' and $px_featured_event_meta->var_pb_event_team2 <> ''){?>
                                                <li>
                                                    <figure>
                                                        <?php
                                                            $team2_row = px_get_term_object($px_featured_event_meta->var_pb_event_team2);
                                                            $team_img2 = px_team_data_front($team2_row->term_id);
                                                            if($team_img2[0] <> ''){
                                                        ?>
                                                            <img alt="" src="<?php echo $team_img2[0];?>">
                                                        <?php }?>
                                                    </figure>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <header class="pix-cont-title">
                                            <h2 class="pix-section-title"><span>
											<?php if(isset($px_featured_event_meta->event_time_title) && $px_featured_event_meta->event_time_title <> ''){echo $px_featured_event_meta->event_time_title.' ';}
												if ( isset($px_featured_event_meta->event_all_day) && $px_featured_event_meta->event_all_day != "on" ) {
													echo $px_featured_event_meta->event_time;
												}else{
													_e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');
												}
											 ?>
                                             </span></h2>
                                        </header>
                                     <div class="bottom-event-fixture-panel">
                                     	<?php $random_id = px_generate_random_string();?>
                                        <div class="defaultCountdown" id="defaultCountdown<?php echo $random_id;?>"></div>
                                            <script>
                                                jQuery(document).ready(function($) {
                                                   px_event_countdown('<?php echo $year_event;?>','<?php echo $month_event;?>','<?php echo $date_event;?>',<?php echo $hours;?>,<?php echo $mints;?>,'<?php echo $random_id;?>','<?php echo $trans_days; ?>','<?php echo $trans_hours; ?>','<?php echo $trans_minutes; ?>','<?php echo $trans_seconds; ?>');
                                                });
                                            </script>
                                    </div>
                                </div>
                            </div>
                            <?php
                            endwhile; 
                            wp_reset_query();
                            }
                            if(isset($filter_category) && $filter_category <> '0'){
                                 if ( $px_node->var_pb_event_type == "Fixtures") {
                                     $args = array(
                                        'posts_per_page'			=> "$px_node->var_pb_event_per_page",
                                        'paged'						=> $_GET['page_id_all'],
                                        'post_type'					=> 'events',
                                        'post_status'				=> 'publish',
                                        'meta_key'					=> 'px_event_from_date_time',
                                        'meta_value'				=> $current_time,
                                        'meta_compare'				=> ">",
                                        'orderby'					=> 'meta_value',
                                        'order'						=> "$px_node->var_pb_event_order",
                                     );
                                }else if ( $px_node->var_pb_event_type == "All" ) {
                                    $args = array(
                                        'posts_per_page'			=> "$px_node->var_pb_event_per_page",
                                        'paged'						=> $_GET['page_id_all'],
                                        'post_type'					=> 'events',
										'meta_key'					=> 'px_event_from_date_time',
										//'meta_value'				=> '',
										'orderby'					=> 'meta_value',
										'post_status'				=> 'publish',
                                        'order'						=> "$px_node->var_pb_event_order",
                                    );
                                }
                                else {
                                     $args = array(
                                        'posts_per_page'			=> "$px_node->var_pb_event_per_page",
                                        'paged'						=> $_GET['page_id_all'],
                                        'post_type'					=> 'events',
                                        'post_status'				=> 'publish',
                                        'meta_key'					=> 'px_event_from_date_time',
                                        'meta_value'				=> $current_time,
                                        'meta_compare'				=> $meta_compare,
                                        'orderby'					=> 'meta_value',
                                        'order'						=> "$px_node->var_pb_event_order",
                                     );
                                }
                                if(isset($filter_category) && $filter_category <> '' && $filter_category <> '0' && $filter_category <> 'All'){
                                    $event_category_array = array('event-category' => "$filter_category");
                                    $args = array_merge($args, $event_category_array);
                                }
                                $custom_query = new WP_Query($args);
	                                if ( $custom_query->have_posts() <> "" ) {
                                ?>
                            
                            <div class="event event-listing">
                                 <?php
								 $cs_counter_year = $cs_counter_month = $previous_counter_year = $previous_counter_month = 0; $count_event=1;
                                while ( $custom_query->have_posts() ): $custom_query->the_post();
                                $event_from_date = get_post_meta($post->ID, "px_event_from_date", true);
								
								if ( isset($px_node->var_pb_event_monthly) && $px_node->var_pb_event_monthly == 'Yes' ) {
									$cs_counter_month = date('m',strtotime($event_from_date));
									$cs_counter_year = date('Y',strtotime($event_from_date));
									if(($cs_counter_year <> $previous_counter_year) || ($cs_counter_month <> $previous_counter_month) || ($count_event == 1)){
										$previous_counter_year = $cs_counter_year;
										$previous_counter_month = $cs_counter_month;
									?>
										<h1 class="event-heading"><span class="backcolr uppercase"><?php echo date('F',strtotime($event_from_date));?></span><?php echo date('Y',strtotime($event_from_date));?></h1>
									<?php 
									}
									$count_event++;
								}
                                $post_xml = get_post_meta($post->ID, "px_event_meta", true);	
                                if ( $post_xml <> "" ) {
                                    $px_event_meta = new SimpleXMLElement($post_xml);
                                    $team1_row = px_get_term_object($px_event_meta->var_pb_event_team1);
                                    $team2_row = px_get_term_object($px_event_meta->var_pb_event_team2);
                                }
                                ?>
                                <article>
                                    <div class="calendar-date">
                                        <time datetime="<?php echo date_i18n('d-m-Y', strtotime($event_from_date));?>"><?php echo date_i18n(get_option('date_format'), strtotime($event_from_date));?>
                                            	 <?php 
												if ( $px_event_meta->event_all_day != "on" ) {
														echo $px_event_meta->event_time;
												}else{
														_e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');
												}
                                        		?>
                                        </time>
                                        <?php if($px_event_meta->event_address <> ''){?>@<?php echo $px_event_meta->event_address;?><?php }?>
                                    </div>
                                    <div class="text">
                                        <div class="top-event">
                                            <h2 class="pix-post-title">
                                                <a href="<?php the_permalink();?>" class="pix-hover"><?php the_title(); ?> </a>
                                            </h2>
                                            <?php 
												$before_cat = "<ul><li> ";
												$categories_list = get_the_term_list ( get_the_id(), 'event-category', $before_cat, ', ', '</li></ul>' );
												if ( $categories_list ){
													printf( __( '%1$s', 'Kings Club'),$categories_list );
												}
											?>
                                        </div>
                                    </div>
                                    <?php if( $px_node->var_pb_event_type == "Results"){?>
                                       		<div class="match-result">
                                                <?php if($px_event_meta->event_score <> ''){?>
                                                <span>
                                                    <big><?php echo $px_event_meta->event_score;?></big>
                                                 </span>
                                                 <?php } ?>
                                            </div>
                                        
                                        <?php }elseif ( $px_node->var_pb_event_type == "fixtures"){ ?>
											<?php if($px_event_meta->event_ticket_options <> ''){?> 
                                               <a <?php if(isset($px_event_meta->event_ticket_color) && $px_event_meta->event_ticket_color <> ''){?>style="color: <?php echo $px_event_meta->event_ticket_color;?>"<?php }?> class="btn pix-btn-open" href="<?php echo $px_event_meta->event_buy_now;?>"> <?php if(isset($px_event_meta->event_ticket_options) && $px_event_meta->event_ticket_options <> ''){echo $px_event_meta->event_ticket_options;}?></a>
                                            <?php 
											
												}
											} else {
												
													$dateAfter = date('m/d/Y');
                                                    if(strtotime($event_from_date) > strtotime($dateAfter)){
														if($px_event_meta->event_ticket_options <> ''){?> 
                                                           <a <?php if(isset($px_event_meta->event_ticket_color) && $px_event_meta->event_ticket_color <> ''){?>style="color: <?php echo $px_event_meta->event_ticket_color;?>"<?php }?> class="btn pix-btn-open" href="<?php echo $px_event_meta->event_buy_now;?>"> <?php if(isset($px_event_meta->event_ticket_options) && $px_event_meta->event_ticket_options <> ''){echo $px_event_meta->event_ticket_options;}?></a>
                                                        <?php 
														}
													} else {
														if($px_event_meta->event_score <> ''){?>
                                                        	<div class="match-result">
                                                                 <span>
                                                                    <big><?php echo $px_event_meta->event_score;?></big>
                                                                 </span>
                                                        	 </div>
                                                         <?php
												} 
											}
										}?>
                                </article>
                               <?php endwhile;?> 
                            </div>
                            <?php 
                               $qrystr = '';
                                  if ( $px_node->var_pb_event_pagination == "Show Pagination" and $count_post > $px_node->var_pb_event_per_page and $px_node->var_pb_event_per_page > 0 and $px_node->var_pb_event_filterables != "On" ) {
                                    echo "<nav class='pagination'><ul>";
                                        if ( isset($_GET['page_id']) ) $qrystr = "&amp;page_id=".$_GET['page_id'];
                                            echo px_pagination($count_post, $px_node->var_pb_event_per_page,$qrystr);
                                    echo "</ul></nav>";
                                }
                                  }
                            }
                         ?>
                        </div>
                    </div>
                </div>
       </div>