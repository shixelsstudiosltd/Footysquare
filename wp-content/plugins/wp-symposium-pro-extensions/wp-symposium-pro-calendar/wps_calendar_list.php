<?php 
if (AUTH_KEY):

    $calendars = get_posts(array(
            'name' => $slug,
            'posts_per_page' => 1,
            'post_type' => 'wps_calendar',
            'post_status' => 'publish'
    ));
	if ($calendars):    

		$calendar = $calendars[0];

		$html .= '<h2 id="calendar_title">'.$calendar->post_title.'</h2>';

		if ($calendar->post_content) $html .= '<p>'.wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($calendar->post_content))))).'</p>';

		$event_items = array();

		$args = array(
		   	'post_type' => 'wps_event',
		   	'orderby' => 'ID',
		   	'meta_key' => 'wps_event_calendar',
			'meta_value' => $calendar->ID,
		   	'order' => 'DESC',
		   	'posts_per_page' => '1000'	
		);
		$loop = new WP_Query($args); 

		while ( $loop->have_posts() ) : $loop->the_post();

			$event_item = array();
			$event_item['ID'] = $post->ID;
			$event_item['post_author'] = $post->post_author;
			$event_item['post_name'] = $post->post_name;
			$event_item['post_title'] = $post->post_title;
			$event_item['post_title_lower'] = strtolower($post->post_title);
			$event_item['post_date'] = $post->post_date;
			$event_item['post_date_gmt'] = $post->post_date_gmt;
			$event_item['post_content'] = $post->post_content;
			$event_item['post_start'] = get_post_meta( $post->ID, 'wps_event_start', true );
			$event_item['post_start_time'] = get_post_meta( $post->ID, 'wps_event_start_time', true );
			$event_item['post_end'] = get_post_meta( $post->ID, 'wps_event_end', true ) ? get_post_meta( $post->ID, 'wps_event_end', true ) : get_post_meta( $post->ID, 'wps_event_start', true );
			$event_item['post_end_time'] = get_post_meta( $post->ID, 'wps_event_end_time', true );

			$event_items[$post->ID] = $event_item;

		endwhile;

		wp_reset_query();		

		if ( !empty( $event_items ) ):


			$monthNames = explode(',', $months);

			$this_day = date("d");
			$cMonth = (isset($_GET['cm'])) ? $_GET['cm'] : date("m");
			$cYear = (isset($_GET['cy'])) ? $_GET['cy'] : date("Y");
			$m_d = 31;
			if ($cMonth == 2) $m_d = 28;
			if ($cMonth == 9 || $cMonth == 4 || $cMonth == 6 || $cMonth == 11 ) $m_d = 30;				

			$prev_year = $cYear;
			$next_year = $cYear;
			$prev_month = ($cMonth-1);
			$next_month = ($cMonth+1);
			 
			if ($prev_month == 0 ) {
			    $prev_month = 12;
			    $prev_year = $cYear - 1;
			}
			if ($next_month == 13 ) {
			    $next_month = 1;
			    $next_year = $cYear + 1;
			}

			$url = wps_curPageURL();
			$url = preg_replace("/[&?]cm=(\d+)/","",$url);
			$url = preg_replace("/[&?]cy=(\d+)/","",$url);
			$url = $url.wps_query_mark($url);

			$table_html = '<table id="wps_events_table">';
			$table_html .= '<tr id="wps_events_table_header_top" align="center">';
			$table_html .= '<td id="wps_events_table_header_prev"><a href="'.$url.'cm='.$prev_month.'&cy='.$prev_year.'">'.$left.'</a></td>';
			$table_html .= '<td  id="wps_events_table_header_month" colspan="5">';
				$table_html .= $monthNames[$cMonth-1].' '.$cYear;
				if ($cMonth != (int)date('m')):
					$table_html .= '&nbsp;&nbsp;&nbsp;[<a href="'.$url.'">'.$today.'</a>]';
				endif;
			$table_html .= '</td>';
			$table_html .= '<td id="wps_events_table_header_next"><a href="'.$url.'cm='.$next_month.'&cy='.$next_year.'">'.$right.'</a></td>';
			$table_html .= '</tr>';
			$table_html .= '<tr id="wps_events_table_header">';
			$day_labels = explode(',', $days);
			$table_html .= '<td class="wps_event_week_title">'.$day_labels[0].'</td>';
			$table_html .= '<td class="wps_event_week_title">'.$day_labels[1].'</td>';
			$table_html .= '<td class="wps_event_week_title">'.$day_labels[2].'</td>';
			$table_html .= '<td class="wps_event_week_title">'.$day_labels[3].'</td>';
			$table_html .= '<td class="wps_event_week_title">'.$day_labels[4].'</td>';
			$table_html .= '<td class="wps_event_week_title">'.$day_labels[5].'</td>';
			$table_html .= '<td class="wps_event_week_title">'.$day_labels[6].'</td>';
			$table_html .= '</tr>';

			$timestamp = mktime(0,0,0,$cMonth,1,$cYear);
			$maxday = date("t",$timestamp);
			$thismonth = getdate ($timestamp);
			$startday = $thismonth['wday'];
			if ($suffix) $suffix = explode(',', $suffix);

			for ($i=0; $i<($maxday+$startday); $i++) {
			    if(($i % 7) == 0 ) $table_html .= "<tr valign='top'>";
			    if($i < $startday) $table_html .= "<td width='14.3%'></td>";
			    else {
			    	$day = ($i - $startday + 1);	    	
			    	$table_html .= "<td width='14.3%'";
			    	if (($i % 7 == 0) || ($i % 7 == 6)) {
			    		$table_html .= " class='wps_event_weekend'";
			    	} else {
			    		$table_html .= " class='wps_event_weekday'";
			    	}
			    	$table_html .= ">";

					$cmp_date = date("Y-m-d", mktime(0, 0, 0, $cMonth, $day, $cYear));

			    	if (strtotime($cmp_date) != strtotime(date('Y-m-d'))) :
				    	$table_html .= '<div class="wps_event_not_today">';
				   	else:
				    	$table_html .= '<div class="wps_event_today">';
				   	endif;
			    		$table_html .= $day;
			    		if ($suffix) $table_html .= $suffix[$day-1];
					$table_html .= '</div>';
					$table_html .= '<div class="wps_event_content">';

					$event_count = 0;
					foreach ($event_items as $event) {

						$cmp_date = date("Y-m-d", mktime(0, 0, 0, $cMonth, $day, $cYear));

						if ( (strtotime($cmp_date) >= strtotime($event['post_start'])) && (strtotime($cmp_date) <= strtotime($event['post_end'])) ):

                            if ($thumbnails && has_post_thumbnail($event['ID'])):
                                $table_html .= '<div class="wps_event_item_thumbnail">';
                                    $attachment_id = get_post_thumbnail_id($event['ID']);
                                    $image_attributes = wp_get_attachment_image_src( $attachment_id, 'thumbnail');
                                    $table_html .= '<a href="'.$url.'event_id='.$event['ID'].'">';
                                    $table_html .= '<img src="'.$image_attributes[0].'" style="width:100%;" alt="'.esc_html($event['post_title']).'" title="'.esc_html($event['post_title']).'" />'; 
                                    $table_html .= '</a>';
                                $table_html .= '</div>';
                            endif;
                            if ($titles):
                                $table_html .= '<div class="wps_event_item_title">';
                                $table_html .= '<a href="'.$url.'event_id='.$event['ID'].'">'.$event['post_title'].'</a>';
                                $table_html .= '</div>';
                            endif;

						endif;
						
					}

					$table_html .= '</div>';

			    	$table_html .= "</td>";
			    }
			    if(($i % 7) == 6 ) $table_html .= "</tr>";
			}

			$table_html .= '</table>';

			$html .= $table_html;

		else:

			$html .= $label_noevents;

		endif;

	endif;

endif;

?>