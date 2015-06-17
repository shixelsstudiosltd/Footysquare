<?php
	global $px_node, $px_theme_option, $px_counter_node;
	if ( !isset($px_node->var_pb_pointtable_per_page) || empty($px_node->var_pb_pointtable_per_page) ) { $px_node->var_pb_pointtable_per_page = -1; }
	$filter_category = '';
	$row_cat = $wpdb->get_row("SELECT * from ".$wpdb->prefix."terms WHERE slug = '" . $px_node->var_pb_pointtable_cat ."'" );
	        if ( isset($_GET['filter_category']) ) {$filter_category = $_GET['filter_category'];}
        else {
            if(isset($row_cat->slug)){
            $filter_category = $row_cat->slug;
            }
        }
		 if ( empty($_GET['page_id_all']) ) $_GET['page_id_all'] = 1;
				$args = array(
					'posts_per_page'			=> "-1",
					'post_type'					=> 'pointtable',
					'post_status'				=> 'publish',
					'order'						=> 'ASC',
				);
			if(isset($filter_category) && $filter_category <> '' && $filter_category <> '0'){
				$season_category_array = array('season-category' => "$filter_category");
				$args = array_merge($args, $season_category_array);
			}
			$custom_query = new WP_Query($args);
 		?>
        <div class="element_size_<?php echo $px_node->pointtable_element_size;?>">
            <div class="pix-content-wrap">
                <?php	
					if ($px_node->var_pb_pointtable_title <> '') { ?>
                        <header class="pix-heading-title">
                            <?php	if ($px_node->var_pb_pointtable_title <> '') { ?>
                            <h2 class="pix-section-title"><?php echo $px_node->var_pb_pointtable_title; ?></h2>
                            <?php  } ?>
        
                        </header>
                <?php  } ?>
                <?php if ($px_node->var_pb_pointtable_filterable == "On") {
                    $qrystr= "";
                    if ( isset($_GET['page_id']) ) $qrystr = "page_id=".$_GET['page_id'];
                ?>
                <div class="tabs horizontal">
                     <div class="fluid-tab-horizontal">
						<ul id="myTab" class="nav nav-tabs">
                             <?php  
                                if((isset($px_node->var_pb_pointtable_cat) &&  $px_node->var_pb_pointtable_cat <> ''  && $px_node->var_pb_pointtable_cat <> '0') &&  isset( $row_cat->term_id )){
                                    $categories = get_categories( array('child_of' => "$row_cat->term_id", 'taxonomy' => 'season-category', 'hide_empty' => 0) );
                                    ?>
                                    <li class="<?php if(($px_node->var_pb_pointtable_cat==$filter_category)){echo 'pix-active';}?>">
                                        <a href="?<?php echo $qrystr."&amp;filter_category=".$row_cat->slug?>"><?php _e("All",'Kings Club');?></a>
                                    </li>
                                    <?php
                                } else {
                                    $categories = get_categories( array('taxonomy' => 'season-category', 'hide_empty' => 0) );
                                }
                                foreach ($categories as $category) {?>
                                    <li <?php if($category->slug==$filter_category){echo 'class="pix-active"';}?>>
                                        <a href="?<?php echo $qrystr."&amp;filter_category=".$category->slug?>"><?php echo $category->cat_name?></a>
                                    </li>
                            <?php }?>
                        </ul>
                    </div>
                    </div>
                  <?php }?>
                  <?php
                        $args = array(
                            'posts_per_page'			=> "$px_node->var_pb_pointtable_per_page",
                            'paged'						=> $_GET['page_id_all'],
                            'post_type'					=> 'pointtable',
                            'post_status'				=> 'publish',
                            'order'						=> 'ASC',
                         );
                        if(isset($filter_category) && $filter_category <> '' && $filter_category <> '0'){
                            $season_category_array = array('season-category' => "$filter_category");
                            $args = array_merge($args, $season_category_array);
                        }
                        $custom_query = new WP_Query($args);
                        if ( $custom_query->have_posts() <> "" ):
                        while ( $custom_query->have_posts() ): $custom_query->the_post();
                            $pointtable_counter=1;
							$px_pointtable = get_post_meta($post->ID, "px_pointtable", true);
                            if ( $px_pointtable <> "" ) {
                                $px_xmlObject = new SimpleXMLElement($px_pointtable);
								$var_pb_record_per_post =$px_xmlObject->var_pb_record_per_post;
								$var_pb_pointtable_tableheads = $px_xmlObject->var_pb_pointtable_tableheads;
								$px_table_sort_column = $px_xmlObject->px_table_sort_column;
                            }else{
								$var_pb_record_per_post ='';
								$var_pb_pointtable_viewall ='';
								$var_pb_pointtable_tableheads ='';
								$px_table_sort_column = '';
							}
                         ?> 
                        <div class="points-table fullwidth">
                        	<table class="table table-condensed table_D3D3D3">
                            	<thead>
                                    <tr>
                                    <?php if(isset($var_pb_pointtable_tableheads) && ($var_pb_pointtable_tableheads == 0 || $var_pb_pointtable_tableheads <> '')){
										echo '<th>
												<span class="box1">
												   #
												</span>
											</th>';
										$i = (int)$var_pb_pointtable_tableheads;
										$count_columns = 0;
										for($j = 1; $j<=9; $j++){
											$table_heads='';
											if(isset($px_theme_option['points_table_coloumn_field_'.$j][$i]) && $px_theme_option['points_table_coloumn_field_'.$j][$i] <> '')
											$table_heads = $px_theme_option['points_table_coloumn_field_'.$j][$i];
											if(isset($table_heads) && $table_heads <> ''){
												$count_columns++;
												?>
                                                	  <th>
                                                        <span class="box1">
                                                            <?php echo $table_heads;?>
                                                        </span>
                                                    </th>
                                                <?php
											}
										}
									}?>
                                    </tr>
                                 </thead>
                                 <tbody>
								  <?php
                                 if(empty($px_xmlObject->var_pb_record_per_post) and $px_xmlObject->var_pb_record_per_post == ''){$px_xmlObject->var_pb_record_per_post = count($px_xmlObject->track);}
                                  if($px_xmlObject->var_pb_record_per_post <> '' and $px_xmlObject->var_pb_record_per_post > 0){
                                       $xml_temp = array();
                                       $m = 0;
                                       if(isset($px_xmlObject->track) && count($px_xmlObject->track) > 0){
											foreach ($px_xmlObject->track as $aTask) {
												if(($pointtable_counter-1) < $px_xmlObject->var_pb_record_per_post){
													$xml_temp[$m][] = (string)$aTask->var_pb_points_table_value1;
													$xml_temp[$m][] = (string)$aTask->var_pb_points_table_value2;
													$xml_temp[$m][] = (string)$aTask->var_pb_points_table_value3;
													$xml_temp[$m][] = (string)$aTask->var_pb_points_table_value4;
													$xml_temp[$m][] = (string)$aTask->var_pb_points_table_value5;
													$xml_temp[$m][] = (string)$aTask->var_pb_points_table_value6;
													$xml_temp[$m][] = (string)$aTask->var_pb_points_table_value7;
													$xml_temp[$m][] = (string)$aTask->var_pb_points_table_value8;
													$xml_temp[$m][] = (string)$aTask->var_pb_points_table_value9;
													$xml_temp[$m][] = (string)$aTask->var_pb_points_table_featured;
													$m++;
													$pointtable_counter++;
												}
											}
                                       }
										if(isset($px_table_sort_column) && $px_table_sort_column <> ''){
                                        	$points_table_data = px_subval_sort_array($xml_temp,(int)$px_table_sort_column);
										} else {
											$points_table_data = $xml_temp;
										}
                                        $pointtable_counter_abc = 1;
										
                                        foreach($points_table_data as $points_table_data_value1){
											$count_value_aray = count($points_table_data_value1);
											$count_columns_data = 0;
											$featured_class = '';
											if(isset($points_table_data_value1[$count_value_aray-1]) && $points_table_data_value1[$count_value_aray-1] == 'yes'){
												$featured_class = 'class="featured-points-row"';
											}
                                            echo '<tr '.$featured_class.'><td>'.$pointtable_counter_abc.'</td>';
                                                foreach($points_table_data_value1 as $points_table_data_value){
													if($count_columns_data >= $count_columns)
														break;
                                                    if(isset($points_table_data_value) && $points_table_data_value <> ''){
                                                        echo '<td>'.$points_table_data_value.'</td>';
													} else {
														echo '<td>-</td>';
													}
														
												$count_columns_data++;
                                                }
                                            echo '</tr>';
                                            $pointtable_counter_abc++;
                                        }
                                  }
                                 ?>
                  		</tbody>
                         <tfoot>
                        	 <tr>
                                <td colspan="<?php echo $count_columns+1;?>"> <?php if($px_xmlObject->var_pb_pointtable_viewall <> ''){?>
                                <a href="<?php  echo $px_xmlObject->var_pb_pointtable_viewall; ?>" class="btn">
                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e("View All",'Kings Club'); }else{  echo $px_theme_option["trans_viewall"];} ?>
                                </a>
                                <?php } ?>
                                </td>
                        	</tr>
                         </tfoot>
                     </table>
                      </div>
                    <?php endwhile; endif;?>  
				 </div>
          </div>