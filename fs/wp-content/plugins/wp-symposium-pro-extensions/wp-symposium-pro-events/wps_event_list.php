<?php

if (AUTH_KEY):

	$event_items = array();

	// If friends with this user, or the user, get activity
	if ($current_user->ID == $user_id):

		$args = array(
		   	'post_type' => 'wps_event',
		   	'orderby' => 'ID',
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
			$event_item['post_end'] = get_post_meta( $post->ID, 'wps_event_end', true );
			$event_item['post_end_time'] = get_post_meta( $post->ID, 'wps_event_end_time', true );

			$event_items[$post->ID] = $event_item;

		endwhile;

		wp_reset_query();		

		if ( !empty( $event_items ) ):

			if ($format == 'list'):

				$table_html = '<div class="wps_mail_posts">';

			    foreach ($event_items as $event_item):
					
					$sql = "SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date ASC";
					$comments = $wpdb->get_results($wpdb->prepare($sql, $event_item['ID']));
					
					if ($comments):

						$created = sprintf($date_format, human_time_diff(strtotime($comments[0]->comment_date_gmt), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
						$comment_count = count($comments);

					else:

						$created = sprintf($date_format, human_time_diff(strtotime($event_item['post_date_gmt']), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
						$comment_count = 0;

					endif;

					$table_html .= '<div class="wps_event_post">';

						$table_html .= '<div class="wps_event_title">';
							$url = wps_curPageURL();
							$table_html .= '<a href="'.$url.wps_query_mark($url).'event='.$event_item['ID'].'">'.esc_attr($event_item['post_title']).'</a>';
						$table_html .= '</div>';
						$table_html .= '<div class="wps_event_count">'.$comment_count.'</div>';
						$table_html .= '<div class="wps_event_freshness">'.$created.'</div>';

					$table_html .= '</div>';

					$table_html = apply_filters( 'wps_event_post_item', $table_html );

				endforeach;

				$table_html .= '</div>';

				$html .= $table_html;

			else:

				$monthNames = array(
					__("January", WPS2_TEXT_DOMAIN),
					__("February", WPS2_TEXT_DOMAIN),
					__("March", WPS2_TEXT_DOMAIN),
					__("April", WPS2_TEXT_DOMAIN),
					__("May", WPS2_TEXT_DOMAIN),
					__("June", WPS2_TEXT_DOMAIN),
					__("July", WPS2_TEXT_DOMAIN),
					__("August", WPS2_TEXT_DOMAIN),
					__("September", WPS2_TEXT_DOMAIN),
					__("October", WPS2_TEXT_DOMAIN),
					__("November", WPS2_TEXT_DOMAIN),
					__("December", WPS2_TEXT_DOMAIN),
					);

				$this_day = date("d");
				$cMonth = (isset($_GET['cm'])) ? $_GET['cm'] : date("m");
				$cYear = date("Y");
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
				$url = preg_replace("/[&?]cm=[0-9]&cy=[0-9]+/","",$url);
				$url = $url.wps_query_mark($url);

				
				$table_html = '<table id="wps_events_table">';
				$table_html .= '<tr align="center">';
				$table_html .= '<td id="wps_events_table_header_prev"><a href="'.$url.'cm='.$prev_month.'&cy='.$prev_year.'">&laquo;</a></td>';
				$table_html .= '<td  id="wps_events_table_header_month" colspan="5">';
					$table_html .= $monthNames[$cMonth-1].' '.$cYear;
					if ($cMonth != (int)date('m')):
						$table_html .= '&nbsp;&nbsp;&nbsp;[<a href="'.$url.'">'.$monthNames[(int)date('m')-1].'</a>]';
					endif;
				$table_html .= '</td>';
				$table_html .= '<td id="wps_events_table_header_next"><a href="'.$url.'cm='.$next_month.'&cy='.$next_year.'">&raquo;</a></td>';
				$table_html .= '</tr>';
				$table_html .= '<tr id="wps_events_table_header">';
				$table_html .= '<td class="wps_event_week_title">S</td>';
				$table_html .= '<td class="wps_event_week_title">M</td>';
				$table_html .= '<td class="wps_event_week_title">T</td>';
				$table_html .= '<td class="wps_event_week_title">W</td>';
				$table_html .= '<td class="wps_event_week_title">T</td>';
				$table_html .= '<td class="wps_event_week_title">F</td>';
				$table_html .= '<td class="wps_event_week_title">S</td>';
				$table_html .= '</tr>';

				$timestamp = mktime(0,0,0,$cMonth,1,$cYear);
				$maxday = date("t",$timestamp);
				$thismonth = getdate ($timestamp);
				$startday = $thismonth['wday'];
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
				    		if ($suffix):
					    		$end = 'th';
					    		if ($day == 1 || $day == 21 || $day == 31) $end = 'st';
					    		if ($day == 2 || $day == 22) $end = 'nd';
					    		if ($day == 3 || $day == 2) $end = 'rd';
								$table_html .= $end;
							endif;
						$table_html .= '</div>';
						$table_html .= '<div class="wps_event_content">';

						$event_count = 0;
						foreach ($event_items as $event) {

							$cmp_date = date("Y-m-d", mktime(0, 0, 0, $cMonth, $day, $cYear));

							if ( (strtotime($cmp_date) >= strtotime($event['post_start'])) && (strtotime($cmp_date) <= strtotime($event['post_end'])) ):

								$table_html .= '<div class="wps_event_item_title"><a href="'.$url.'event='.$event['ID'].'">'.$event['post_title'].'</a></div>';

							endif;
							
						}

						$table_html .= '</div>';

				    	$table_html .= "</td>";
				    }
				    if(($i % 7) == 6 ) $table_html .= "</tr>";
				}

				$table_html .= '</table>';

				$html .= $table_html;

			endif;

		else:

			$html .= $label_noevents;

		endif;

	endif;

endif;

?>