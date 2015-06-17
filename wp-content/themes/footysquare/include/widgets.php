<?php
/* widget_facebook start */
class px_widget_facebook extends WP_Widget{
	function px_widget_facebook()  {
		$widget_ops = array('classname' => 'facebok_widget', 'description' => 'Facebook widget like box total customized with theme.' );
		$this->WP_Widget('px_widget_facebook', 'PX: Facebook', $widget_ops);
  	}
  	function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
		$pageurl = isset( $instance['pageurl'] ) ? esc_attr( $instance['pageurl'] ) : '';
		$showfaces = isset( $instance['showfaces'] ) ? esc_attr( $instance['showfaces'] ) : '';
		$showstream = isset( $instance['showstream'] ) ? esc_attr( $instance['showstream'] ) : '';
		$showheader = isset( $instance['showheader'] ) ? esc_attr( $instance['showheader'] ) : '';
		$fb_bg_color = isset( $instance['fb_bg_color'] ) ? esc_attr( $instance['fb_bg_color'] ) : '';
		$likebox_height = isset( $instance['likebox_height'] ) ? esc_attr( $instance['likebox_height'] ) : '';						
	?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"> Title:
                <input class="upcoming" id="<?php echo $this->get_field_id('title'); ?>" size='40' 
                name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('pageurl'); ?>"> Page URL:
                <input class="upcoming" id="<?php echo $this->get_field_id('pageurl'); ?>" size='40' 
                name="<?php echo $this->get_field_name('pageurl'); ?>" type="text" value="<?php echo esc_attr($pageurl); ?>" />
                <br />
                <small>Please enter your page or User profile url example: http://www.facebook.com/profilename OR <br />
                https://www.facebook.com/pages/wxyz/123456789101112 </small><br />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('showfaces'); ?>"> Show Faces:
                <input class="upcoming" id="<?php echo $this->get_field_id('showfaces'); ?>" 
                name="<?php echo $this->get_field_name('showfaces'); ?>" type="checkbox" <?php if(esc_attr($showfaces) != '' ){echo 'checked';}?> />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('showstream'); ?>"> Show Stream:
                <input class="upcoming" id="<?php echo $this->get_field_id('showstream'); ?>" 
                name="<?php echo $this->get_field_name('showstream'); ?>" type="checkbox" <?php if(esc_attr($showstream) != '' ){echo 'checked';}?> />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('likebox_height'); ?>"> Like Box Height:
                <input class="upcoming" id="<?php echo $this->get_field_id('likebox_height'); ?>" size='2' 
                name="<?php echo $this->get_field_name('likebox_height'); ?>" type="text" value="<?php echo esc_attr($likebox_height); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('fb_bg_color'); ?>"> Background Color:
                <input type="text" size='4' id="<?php echo $this->get_field_id('fb_bg_color'); ?>" 
                name="<?php echo $this->get_field_name('fb_bg_color'); ?>" value="<?php if(!empty($fb_bg_color)){ echo $fb_bg_color;}else{ echo "#fff";}; ?>" class="fb_bg_color upcoming"  />
            </label>
        </p>
	<?php
	}
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['pageurl'] = $new_instance['pageurl'];
		$instance['showfaces'] = $new_instance['showfaces'];	
		$instance['showstream'] = $new_instance['showstream'];
		$instance['showheader'] = $new_instance['showheader'];
		$instance['fb_bg_color'] = $new_instance['fb_bg_color'];		
		//$instance['likebox_width'] = $new_instance['likebox_width'];
		$instance['likebox_height'] = $new_instance['likebox_height'];			
		return $instance;
	}
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$pageurl = empty($instance['pageurl']) ? ' ' : apply_filters('widget_title', $instance['pageurl']);
		$showfaces = empty($instance['showfaces']) ? ' ' : apply_filters('widget_title', $instance['showfaces']);
		$showstream = empty($instance['showstream']) ? ' ' : apply_filters('widget_title', $instance['showstream']);
		$showheader = empty($instance['showheader']) ? ' ' : apply_filters('widget_title', $instance['showheader']);
		$fb_bg_color = empty($instance['fb_bg_color']) ? ' ' : apply_filters('widget_title', $instance['fb_bg_color']);								
		//$likebox_width = empty($instance['likebox_width']) ? ' ' : apply_filters('widget_title', $instance['likebox_width']);								
		$likebox_height = empty($instance['likebox_height']) ? ' ' : apply_filters('widget_title', $instance['likebox_height']);													
		if(isset($showfaces) AND $showfaces == 'on'){$showfaces ='true';}else{$showfaces = 'false';}
		if(isset($showstream) AND $showstream == 'on'){$showstream ='true';}else{$showstream ='false';}
		echo $before_widget;	
		// WIDGET display CODE Start
		if (!empty($title) && $title <> ' '){
			echo $before_title;
			echo $title;
			echo $after_title;
		}
		global $wpdb, $post;
	?>
	<style type="text/css" >
	.facebookOuter {
	 	background-color:<?php echo $fb_bg_color ?>; 
	 	width:100%; 
	 	padding:0;
	  	float:left;
	}
	.facebookInner {
		float: left;
	  	width: 100%;
	}
	.facebook_module, .fb_iframe_widget > span, .fb_iframe_widget > span > iframe {
		width: 100% !important;
	}
	.fb_iframe_widget, .fb-like-box div span iframe {
		width: 100% !important;
		float: left;
	}
	</style>
    <div class="facebook">
        <div class="facebookOuter">
            <div class="facebookInner">
                <div class="fb-like-box" 
                          colorscheme="light" data-height="<?php echo $likebox_height;?>"  data-width="190" 
                          data-href="<?php echo $pageurl;?>" 
                          data-border-color="#fff" data-show-faces="<?php echo $showfaces;?>"  data-show-border="false"
                          data-stream="<?php echo $showstream;?>" data-header="false"> </div>
            </div>
        </div>
    </div>
	<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script> 
	<?php 
	echo $after_widget;
	}
}
// widget_gallery start
class px_gallery extends WP_Widget {
	function px_gallery() {
		$widget_ops = array('classname' => 'widget-gallery', 'description' => 'Select any gallery to show in widget.');
		$this->WP_Widget('px_gallery', 'PX : Gallery Widget', $widget_ops);
	}
	function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title' => '', 'get_names_gallery' => 'new'));
		$title = $instance['title'];
		$get_names_gallery = isset($instance['get_names_gallery']) ? esc_attr($instance['get_names_gallery']) : '';
		$showcount = isset($instance['showcount']) ? esc_attr($instance['showcount']) : '';
		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"> Title:
                <input class="upcoming" id="<?php echo $this->get_field_id('title'); ?>" size="40" 
                name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('get_names_gallery'); ?>"> Select Gallery:
            	<select id="<?php echo $this->get_field_id('get_names_gallery'); ?>" 
                name="<?php echo $this->get_field_name('get_names_gallery'); ?>" style="width:225px;">
			<?php
				global $wpdb, $post;
				$newpost = 'posts_per_page=-1&post_type=px_gallery&order=ASC&post_status=publish';
				$newquery = new WP_Query($newpost);
				while ($newquery->have_posts()): $newquery->the_post();
				?>
                    <option <?php
                        if (esc_attr($get_names_gallery) == $post->post_name) {
                            echo 'selected';
                        }
                        ?> value="<?php echo $post->post_name; ?>" > <?php echo substr(get_the_title($post->ID), 0, 20);
                                if (strlen(get_the_title($post->ID)) > 20)
                                    echo "...";
                                ?> 
                   </option>
				<?php endwhile; ?>
			</select>
		</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('showcount'); ?>"> Number of Images:
			<input class="upcoming" id="<?php echo $this->get_field_id('showcount'); ?>" size="2" 
            name="<?php echo $this->get_field_name('showcount'); ?>" type="text" value="<?php echo esc_attr($showcount); ?>" />
		</label>
	</p>
	<?php
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['get_names_gallery'] = $new_instance['get_names_gallery'];
		$instance['showcount'] = $new_instance['showcount'];
  		return $instance;
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		global $wpdb, $post;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$get_names_gallery = isset($instance['get_names_gallery']) ? esc_attr($instance['get_names_gallery']) : '';
		$showcount = isset($instance['showcount']) ? esc_attr($instance['showcount']) : '';
		if (empty($showcount)) {
			 $showcount = '12';
		}
		echo $before_widget;
		if (strlen($get_names_gallery) <> 1 || strlen($get_names_gallery) <> 0) {
			echo $before_title . $title . $after_title;
		}
 		if ($get_names_gallery <> '') {
 			// galery slug to id start
			$get_gallery_id = '';
			$args=array(
				'name' => $get_names_gallery,
				'post_type' => 'px_gallery',
				'post_status' => 'publish',
				'showposts' => 1,
			);
			$get_posts = get_posts($args);
 			if($get_posts){
				$get_gallery_id = $get_posts[0]->ID;
			}
			// galery slug to id end
			if($get_gallery_id <> ''){
				$px_meta_gallery_options = get_post_meta($get_gallery_id, "px_meta_gallery_options", true);
				if ($px_meta_gallery_options <> "") {
					$cs_xmlObject = new SimpleXMLElement($px_meta_gallery_options);
					if ($showcount > count($cs_xmlObject)) {
						$showcount = count($cs_xmlObject);
					}
				?>
                <div  class="gallery lightbox">
					<ul>
					<?php
                         for ($i = 0; $i < $showcount; $i++) {
                            $path = $cs_xmlObject->gallery[$i]->path;
                            $title = $cs_xmlObject->gallery[$i]->title;
                            $description = $cs_xmlObject->gallery[$i]->description;
                            $social_network = $cs_xmlObject->gallery[$i]->social_network;
                            $use_image_as = $cs_xmlObject->gallery[$i]->use_image_as;
                            $video_code = $cs_xmlObject->gallery[$i]->video_code;
                            $link_url = $cs_xmlObject->gallery[$i]->link_url;
                            $image_url = px_attachment_image_src($path, 64, 64);
                            $image_url_full = px_attachment_image_src($path, 0, 0);
                    ?>
                    <li> 
                    	<figure>
                        <?php echo "<img  src='" . $image_url . "' data-alt='" . $title . "' alt='' />" ?>
                       <figcaption>
                        <a href="<?php if ($use_image_as == 1)echo $video_code;  elseif($use_image_as==2) 
							echo $link_url; else echo $image_url_full;?>"	        
                            target="<?php if($use_image_as==2){ echo '_blank'; }else{ echo '_self'; }; ?>" data-rel="<?php if ($use_image_as == 1) 
                            echo "prettyPhoto"; elseif($use_image_as==2) echo ""; else echo "prettyPhoto[gallery1]"?>">
                            <i class="fa fa-plus"></i>	
                        </a> 
                          </figcaption>
                        </figure>
                    </li>
            <?php } ?>
			</ul>
        </div>
	<?php }
	}else{
			echo '<h4>'.__( 'No results found.', 'Kings Club' ).'</h4>';
		}
	} 
	echo $after_widget; // WIDGET display CODE End
	}
}
// widget_recent_post start
class recentposts extends WP_Widget{
	function recentposts()	{
		$widget_ops = array('classname' => 'widget-recent-blog', 'description' => 'Recent Posts from category.' );
		$this->WP_Widget('recentposts', 'PX : Recent Posts', $widget_ops);
	}
	function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
		$select_category = isset( $instance['select_category'] ) ? esc_attr( $instance['select_category'] ) : '';
		$showcount = isset( $instance['showcount'] ) ? esc_attr( $instance['showcount'] ) : '';	
 	?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"> Title:
            <input class="upcoming" id="<?php echo $this->get_field_id('title'); ?>" size="40" 
            name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </label>
    </p>
	<p>
		<label for="<?php echo $this->get_field_id('select_category'); ?>"> Select Category:
			<select id="<?php echo $this->get_field_id('select_category'); ?>" 
            	name="<?php echo $this->get_field_name('select_category'); ?>" style="width:225px">
				<?php
				$categories = get_categories();
					if($categories <> ""){
						foreach ( $categories as $category ) {
						?>
							<option <?php if($select_category == $category->slug){echo 'selected';}?> 
                            	value="<?php echo $category->slug;?>" ><?php echo $category->name;?>
                            </option>
                    	<?php } 
					}
				?>
			</select>
		</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('showcount'); ?>"> Number of Posts To Display:
			<input class="upcoming" id="<?php echo $this->get_field_id('showcount'); ?>" size='2' 
            name="<?php echo $this->get_field_name('showcount'); ?>" type="text" value="<?php echo esc_attr($showcount); ?>" />
		</label>
     </p>
	 
	<?php
	}
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['select_category'] = $new_instance['select_category'];
		$instance['showcount'] = $new_instance['showcount'];
 		return $instance;
	}
	function widget($args, $instance){
		global $px_node;
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$select_category = empty($instance['select_category']) ? ' ' : apply_filters('widget_title', $instance['select_category']);		
		$showcount = empty($instance['showcount']) ? ' ' : apply_filters('widget_title', $instance['showcount']);	
 		
		if($instance['showcount'] == ""){$instance['showcount'] = '-1';}
		echo $before_widget;	
		// WIDGET display CODE Start
		if (!empty($title) && $title <> ' '){
			echo $before_title;
			echo $title;
			echo $after_title;
		}
		global $wpdb, $post;
		wp_reset_query();
		$args = array( 'posts_per_page' => "$showcount",'post_type' => 'post','category_name' => "$select_category",'order'=>'DESC'); 
		$custom_query = new WP_Query($args);
		if ( $custom_query->have_posts() <> "" ) {
			while ( $custom_query->have_posts()) : $custom_query->the_post();
			$post_xml = get_post_meta($post->ID, "post", true);	
			$cs_xmlObject = new stdClass();
			if ( $post_xml <> "" ) {
				$cs_xmlObject = new SimpleXMLElement($post_xml);
				$width 	= 240;
				$height = 180;
				$image_url = px_get_post_img_src($post->ID, $width, $height);
				}
			?>
			<article>
			<?php 
				if($image_url <> ''){
  					echo " <figure><a class='pix-colrhvr' href='".get_permalink()."' ><img src='".$image_url."' alt=''></a></figure>";					
				} ?>
                <div class="text">
                    <h6>
                        <a href="<?php the_permalink();?>"  class='pix-colrhvr'>
                            <?php echo substr(get_the_title(),0,50); if ( strlen(get_the_title()) > 50) echo ".."; ?>
                        </a>
                    </h6>
                     <?php px_posted_on(false,false,false,true,false,false);?>
				</div>
    		</article>
	    <?php endwhile; 
	}else {
		echo '<h4>'.__( 'No results found.', 'Kings Club' ).'</h4>';
	}
	echo $after_widget;
	}
}

// widget px_fixture_countdown start
class px_fixture_countdown extends WP_Widget{
	function px_fixture_countdown()	{
		$widget_ops = array('classname' => 'widget_countdown', 'description' => 'Upcoming Fixture Time Countdown.' );
		$this->WP_Widget('px_fixture_countdown', 'PX : Fixture Countdown', $widget_ops);
	}
	function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
		$upcoming_fixtures_cat = isset( $instance['upcoming_fixtures_cat'] ) ? esc_attr( $instance['upcoming_fixtures_cat'] ) : '';
 	?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"> Title:
            <input class="upcoming" id="<?php echo $this->get_field_id('title'); ?>" size="40" 
            name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </label>
    </p>
	<p>
		<label for="<?php echo $this->get_field_id('upcoming_fixtures_cat'); ?>"> Select Category:
        	<select id="<?php echo $this->get_field_id('upcoming_fixtures_cat'); ?>" name="<?php echo $this->get_field_name('upcoming_fixtures_cat'); ?>" class="dropdown">
                  <option value="">-- Select Category --</option>
				  <?php show_all_cats('', '', $upcoming_fixtures_cat, "event-category");?>
                </select>
        	
		</label>
	</p>
	
	<?php
	}
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['upcoming_fixtures_cat'] = $new_instance['upcoming_fixtures_cat'];
 		return $instance;
	}
	function widget($args, $instance){
		global $wpdb, $px_node, $post, $px_theme_option,$px_counter_node;
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$upcoming_fixtures_cat = empty($instance['upcoming_fixtures_cat']) ? ' ' : $instance['upcoming_fixtures_cat'];		
		echo $before_widget;	
		// WIDGET display CODE Start
		date_default_timezone_set('UTC');
		$current_time = strtotime(current_time('m/d/Y H:i', $gmt = 0 )); 
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
		if(isset($upcoming_fixtures_cat) && $upcoming_fixtures_cat <> ''){
			$hours = '00';
			$mints = '00';
			$featured_args = array(
				'posts_per_page'			=> "1",
				'post_type'					=> 'events',
				'event-category' 			=> "$upcoming_fixtures_cat",
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
				$px_event_from_date_time = get_post_meta($post->ID, "px_event_from_date_time", true);
				$year_event = date("Y", strtotime($event_from_date));
				$month_event = date("m", strtotime($event_from_date));
				$date_event = date("d", strtotime($event_from_date));
				$px_featured_meta = get_post_meta($post->ID, "px_event_meta", true);	
				if ( $px_featured_meta <> "" ) {
					$px_featured_event_meta = new SimpleXMLElement($px_featured_meta);
					if ( $px_featured_event_meta->event_all_day != "on" ) {
						$hours = date("H",$px_event_from_date_time);
						$mints = date("i", $px_event_from_date_time);										
					
					} else {
						$hours = '00';
						$mints = '00';
					}
				}
				px_enqueue_countdown_script();
				$random_id = px_generate_random_string('3');
		
		?>

			<div class="countdown-section">
			
			<!-- Pix Label Strat -->
			<div class="pix-label">
				<span class="pix-tittle"><?php echo $title;?></span>
				<time>
				<?php echo date_i18n(get_option('date_format'), strtotime($event_from_date));?>
				<?php 
					if ( $px_featured_event_meta->event_all_day != "on" ) {
						echo $px_featured_event_meta->event_time;
					}else{
						_e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');
					}
				?>
				</time>
			</div>
			<!-- Pix Label Strat -->
			<div class="text">
				<div class="pix-sc-team">
					<ul>
						<?php if(isset($px_featured_event_meta->var_pb_event_team1) && $px_featured_event_meta->var_pb_event_team1 <> '' && $px_featured_event_meta->var_pb_event_team1 <> '0'){?>
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
						<?php if(isset($px_featured_event_meta->var_pb_event_team2) && $px_featured_event_meta->var_pb_event_team2 <> '' && $px_featured_event_meta->var_pb_event_team2 <> '0'){?>
						<li>
							<figure>
								<?php
								 $px_featured_event_meta->var_pb_event_team2;
								$team2_row = px_get_term_object($px_featured_event_meta->var_pb_event_team2);
								$team_img2 = px_team_data_front($team2_row->term_id);
								
								if($team_img2[0] <> ''){
								?>
									<img alt="" src="<?php echo $team_img2[0];?>">
								<?php }?>
							</figure>
						</li>
						<?php }?>
					</ul>
					<div class="pix-sc-team-info">
						<p>
							<?php 
								if(isset($team1_row->name)){echo $team1_row->name;}
						   ?>
						   <span class="vs"><?php if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") {  _e("VS",'Kings Club'); }else{  echo $px_theme_option["trans_event_vs"];}?></span>
							<?php 
								if(isset($team2_row->name)){echo $team2_row->name;}
							   ?>
							   <span class="time-sec"><?php echo ''.$px_featured_event_meta->event_address;?></span>
						</p>
					</div>
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
				<div class="defaultCountdown" id="defaultCountdown<?php echo $random_id;?>"></div>
				<script>
					jQuery(document).ready(function($) {
					   px_event_countdown('<?php echo $year_event;?>','<?php echo $month_event;?>','<?php echo $date_event;?>',<?php echo $hours;?>,<?php echo $mints;?>,'<?php echo $random_id;?>','<?php echo $trans_days; ?>','<?php echo $trans_hours; ?>','<?php echo $trans_minutes; ?>','<?php echo $trans_seconds; ?>');
					});
				</script>
				<div class="countdown-buttons">
					<?php
						add_to_calender(); 
						if($px_featured_event_meta->event_ticket_options <> ''){?> 
						<div class="buy-ticket-button">
						   <a class="btn pix-btn-open" href="<?php echo $px_featured_event_meta->event_buy_now;?>"> <?php if(isset($px_featured_event_meta->event_ticket_options) && $px_featured_event_meta->event_ticket_options <> ''){echo $px_featured_event_meta->event_ticket_options;}?></a>
						 </div>
					<?php }?>
				 </div>
			</div>
			</div>
		<?php 
		endwhile; 
		wp_reset_query();
		}else {
			
			echo '<h4>'.__( 'No results found.', 'Kings Club' ).'</h4>';
			
		}
		echo $after_widget;
	}
}

// widget_twitter start
class px_twitter_widget extends WP_Widget {
		function px_twitter_widget() {
			$widget_ops = array('classname' => 'widget-twitter', 'description' => 'twitter widget');
			$this->WP_Widget('px_twitter_widget', 'PX : Twitter Widget', $widget_ops);
		}
		function form($instance) {
			$instance = wp_parse_args((array) $instance, array('title' => ''));
			$title = $instance['title'];
			$username = isset($instance['username']) ? esc_attr($instance['username']) : '';
			$numoftweets = isset($instance['numoftweets']) ? esc_attr($instance['numoftweets']) : '';
 		?>
        	<p>
          	<label for="<?php echo $this->get_field_id('title'); ?>">
				<span>Title: </span>
				<input class="upcoming" id="<?php echo $this->get_field_id('title'); ?>" size="40" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
            </p>
            <p>
			<label for="screen_name">User Name<span class="required">(*)</span>: </label>
				<input class="upcoming" id="<?php echo $this->get_field_id('username'); ?>" size="40" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo esc_attr($username); ?>" />
            </p>
            <p>
            <label for="tweet_count">
			<span>Num of Tweets: </span>
			<input class="upcoming" id="<?php echo $this->get_field_id('numoftweets'); ?>" size="2" name="<?php echo $this->get_field_name('numoftweets'); ?>" type="text" value="<?php echo esc_attr($numoftweets); ?>" />
			</label>
            </p>
            <div class="clear"></div>
  		<?php
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = $new_instance['title'];
			$instance['username'] = $new_instance['username'];
			$instance['numoftweets'] = $new_instance['numoftweets'];
			
 			return $instance;
		}
  		function widget($args, $instance) {
			global $px_theme_option;
			extract($args, EXTR_SKIP);
			$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
			$username = $instance['username'];
 			$numoftweets = $instance['numoftweets'];		
	 		if($numoftweets == ''){$numoftweets = 2;}
			echo $before_widget;
  			// WIDGET display CODE Start
			if (!empty($title) && $title <> ' '){
				echo $before_title . $title . $after_title;
			}
				if(strlen($username) > 1){
						$text ='';
						$return = '';
						$cacheTime =10000;
						$transName = 'latest-tweets';
						require_once "twitteroauth/twitteroauth.php"; //Path to twitteroauth library
						$consumerkey = $px_theme_option['consumer_key'];
						$consumersecret = $px_theme_option['consumer_secret'];
						$accesstoken = $px_theme_option['access_token'];
						$accesstokensecret = $px_theme_option['access_token_secret'];
						$connection = new TwitterOAuth($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
						$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$numoftweets);
						if(!is_wp_error($tweets) and is_array($tweets)){
							set_transient($transName, $tweets, 60 * $cacheTime);
						}else{
							$tweets= get_transient('latest-tweets');
						}
 						if(!is_wp_error($tweets) and is_array($tweets)){
							$return .= "<div class='twitter_sign webkit'>
								<div class='tweets-wrapper article'>";
									foreach($tweets as $tweet) {
										$text = $tweet->{'text'}; 
										foreach($tweet->{'entities'} as $type => $entity) {
												if($type == 'urls') {						
													foreach($entity as $j => $url) {
														$display_url = '<a href="' . $url->{'url'} . '" target="_blank" title="' . $url->{'expanded_url'} . '">' . $url->{'display_url'} . '</a>';
														$update_with = 'Read more at '.$display_url;
														$text = str_replace('Read more at '.$url->{'url'}, '', $text);
														$text = str_replace($url->{'url'}, '', $text);
													}
												} else if($type == 'hashtags') {
													foreach($entity as $j => $hashtag) {
														$update_with = '<a href="https://twitter.com/search?q=%23' . $hashtag->{'text'} . '&src=hash" target="_blank" title="' . $hashtag->{'text'} . '">#' . $hashtag->{'text'} . '</a>';
														$text = str_replace('#'.$hashtag->{'text'}, $update_with, $text);
													}
												} else if($type == 'user_mentions') {
														foreach($entity as $j => $user) {
															  $update_with = '<a href="https://twitter.com/' . $user->{'screen_name'} . '" target="_blank" title="' . $user->{'name'} . '">@' . $user->{'screen_name'} . '</a>';
															  $text = str_replace('@'.$user->{'screen_name'}, $update_with, $text);
														}
													}
												}
										$large_ts = time();
										$n = $large_ts - strtotime($tweet->{'created_at'});
										if($n < (60)){ $posted = sprintf(__('%d seconds ago','Kings Club'),$n); }
										elseif($n < (60*60)) { $minutes = round($n/60); $posted = sprintf(_n('About a Minute Ago','%d Minutes Ago',$minutes,'Kings Club'),$minutes); }
										elseif($n < (60*60*16)) { $hours = round($n/(60*60)); $posted = sprintf(_n('About an Hour Ago','%d Hours Ago',$hours,'Kings Club'),$hours); }
										elseif($n < (60*60*24)) { $hours = round($n/(60*60)); $posted = sprintf(_n('About an Hour Ago','%d Hours Ago',$hours,'Kings Club'),$hours); }
										elseif($n < (60*60*24*6.5)) { $days = round($n/(60*60*24)); $posted = sprintf(_n('About a Day Ago','%d Days Ago',$days,'Kings Club'),$days); }
										elseif($n < (60*60*24*7*3.5)) { $weeks = round($n/(60*60*24*7)); $posted = sprintf(_n('About a Week Ago','%d Weeks Ago',$weeks,'Kings Club'),$weeks); } 
										elseif($n < (60*60*24*7*4*11.5)) { $months = round($n/(60*60*24*7*4)) ; $posted = sprintf(_n('About a Month Ago','%d Months Ago',$months,'Kings Club'),$months);}
										elseif($n >= (60*60*24*7*4*12)){$years=round($n/(60*60*24*7*52)) ; $posted = sprintf(_n('About a year Ago','%d years Ago',$years,'Kings Club'),$years);} 
										$user = $tweet->{'user'};
										$return .="<article><div class='text webkit'><i class='fa fa-twitter'></i>";
										$return .= "<p class='cs-post-title'>" . $text . "</p>";
										$return .= "<p>" . $posted. "</p>";
										$return .="</div></article>";
									}
							$return .="</div></div>";
							echo $return;
			}else{
			if(isset($tweets->errors[0]) && $tweets->errors[0] <> ""){
				echo $tweets->errors[0]->message.".<br> Please enter valid Twitter API Keys";
			}else{
				_e( 'No results found.', 'Kings Club' );	
			}
		}
	}else{ 				
			//echo '<h4>No User information given.</h4>';
		}
		echo $after_widget;
		// WIDGET display CODE End
		}
 	}
 	
// widget_twitter end
// widget pointstable start
class px_pointstable extends WP_Widget{
		function px_pointstable()	{
			$widget_ops = array('classname' => 'widget-point-table', 'description' => 'Point Table Listing.' );
			$this->WP_Widget('px_pointstable', 'PX : Point Table', $widget_ops);
		}
		function form($instance){

			global $px_theme_option;
			$defaults = array();
			$instance = wp_parse_args( (array) $instance, array( 'title' => '','select_category' => '','px_points_widget_checkbox1' => '1','px_points_widget_checkbox2' => '2','px_points_widget_checkbox3' => '3','px_points_widget_checkbox4' => '4','px_points_widget_checkbox5' => '5','px_points_widget_checkbox6' => '6','px_points_widget_checkbox7' => '7','px_points_widget_checkbox8' => '8','px_points_widget_checkbox9' => '9' ) );
			$title = $instance['title'];
			$select_category = isset( $instance['select_category'] ) ? esc_attr( $instance['select_category'] ) : '';
			$widgetid = px_generate_random_string(4);
		?>
       
        <script>
			function px_pointtable_widget_heads(var_pb_pointtable_name, elem, admin_url){
				if ( var_pb_pointtable_name != "" || var_pb_pointtable_name != "" ) {
					
                                        console.log('THIS ONE: '+jQuery(elem).attr('name'));
                                        var elemId = jQuery(elem).attr('id');
                                        var form = jQuery('#'+elemId).parent().parent().parent().parent();
                                        var foundElm = jQuery(form).find('input[type="hidden"].widget-id');
                                        var required_value = foundElm.val();
										
                                        window.oiewurioer = form;
                                        console.log('form: '+form);
                                        console.log('foundElm: '+foundElm);
                                        console.log('required value: '+required_value);
                        
					
									var dataString = 'var_pb_pointtable_name=' + var_pb_pointtable_name + '&fieldname='+required_value+ 
									'&action=px_pointtable_widget_coloumns';
									jQuery.ajax({
										type:"POST",
										url: admin_url,
										data: dataString,
										success:function(response){
											jQuery('.'+elemId).html(response);
											//jQuery(".sortableWidgetcoloumn-1<?php echo $widgetid; ?>").html(response);
										}
									});
					
					return false; 
				}
			}
		
		</script>
        
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"> Title:
				<input class="upcoming" id="<?php echo $this->get_field_id('title'); ?>" size="40" 
				name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('select_category'); ?>"> Select Category:
            
            
            <select id="<?php echo $this->get_field_id('select_category'); ?>" name="<?php echo $this->get_field_name('select_category'); ?>" style="width:225px" onchange="px_pointtable_widget_heads(this.value, this,'<?php echo admin_url('admin-ajax.php');?>')">
                <option value=""> Select Point Table</option>
        		<?php
				global $wpdb, $post;
				$newpost = 'posts_per_page=-1&post_type=pointtable&order=ASC&post_status=publish';
				$newquery = new WP_Query($newpost);
				while ($newquery->have_posts()): $newquery->the_post();
				?>
                    <option <?php
                        if (esc_attr($select_category) == $post->post_name) {
                            echo 'selected';
                        }
                        ?> value="<?php echo $post->post_name; ?>" > <?php echo substr(get_the_title($post->ID), 0, 20);
                                if (strlen(get_the_title($post->ID)) > 20)
                                    echo "...";
                                ?> 
                   </option>
				<?php endwhile; ?>
              </select>
			</label>
		</p>
        
        
        <p>
			<div class="sortableWidgetcoloumn-1<?php echo $widgetid; ?>">
        		<?php 
					if(isset($select_category) && $select_category <> ''){
						$args=array(
						  'name' => $select_category,
						  'post_type' => 'pointtable',
						  'post_status' => 'publish',
						  'showposts' => 1,
						);
						$get_posts = get_posts($args);
						if($get_posts){
							$pointtable_id = $get_posts[0]->ID;
						}
						$px_pointtable = get_post_meta($pointtable_id, "px_pointtable", true);
							if ( $px_pointtable <> "" ) {
								$px_xmlObject = new SimpleXMLElement($px_pointtable);
								$var_pb_pointtable_tableheads = $px_xmlObject->var_pb_pointtable_tableheads;
							}else {
								$var_pb_pointtable_tableheads ='';
							}
						$i = (int)$var_pb_pointtable_tableheads;
						
						if(isset($px_theme_option['points_table_coloumn_field_1'][$i]) && $px_theme_option['points_table_coloumn_field_1'][$i] <> ''){?>
								 <input class="checkbox" type="checkbox" value="1" name="<?php echo $this->get_field_name('px_points_widget_checkbox1'); ?>" <?php checked($instance['px_points_widget_checkbox1'], 1); ?> /> 
								 <label for="<?php echo $px_theme_option['points_table_coloumn_field_1'][$i];?>"><?php echo $px_theme_option['points_table_coloumn_field_1'][$i];?></label>
						<?php }?>
						<?php if(isset($px_theme_option['points_table_coloumn_field_2'][$i]) && $px_theme_option['points_table_coloumn_field_2'][$i] <> ''){?>
								<input class="checkbox" type="checkbox" value="2" name="<?php echo $this->get_field_name('px_points_widget_checkbox2'); ?>"<?php checked($instance['px_points_widget_checkbox2'], 2); ?> /> 
							 
								 <label for="<?php echo $px_theme_option['points_table_coloumn_field_2'][$i];?>"><?php echo $px_theme_option['points_table_coloumn_field_2'][$i];?></label>
						<?php }?>
						<?php if(isset($px_theme_option['points_table_coloumn_field_3'][$i]) && $px_theme_option['points_table_coloumn_field_3'][$i] <> ''){?>
							 <input class="checkbox" type="checkbox" value="3" name="<?php echo $this->get_field_name('px_points_widget_checkbox3'); ?>" <?php checked($instance['px_points_widget_checkbox3'], 3); ?> />
                              <?php echo $px_theme_option['points_table_coloumn_field_3'][$i];?>
						<?php }?>
						<?php if(isset($px_theme_option['points_table_coloumn_field_4'][$i]) && $px_theme_option['points_table_coloumn_field_4'][$i] <> ''){?>
								<input type="checkbox" class="checkbox" name="<?php echo $this->get_field_name('px_points_widget_checkbox4'); ?>"value="4" <?php checked($instance['px_points_widget_checkbox4'], 4); ?>/>								   								<label><?php echo $px_theme_option['points_table_coloumn_field_4'][$i];?></label>
						<?php }?>
						<?php if(isset($px_theme_option['points_table_coloumn_field_5'][$i]) && $px_theme_option['points_table_coloumn_field_5'][$i] <> ''){?>
								<input type="checkbox" class="checkbox"  name="<?php echo $this->get_field_name('px_points_widget_checkbox5'); ?>" value="5" <?php checked($instance['px_points_widget_checkbox5'], 5); ?> />								 								<label><?php echo $px_theme_option['points_table_coloumn_field_5'][$i];?></label>
						<?php }?>
						<?php if(isset($px_theme_option['points_table_coloumn_field_6'][$i]) && $px_theme_option['points_table_coloumn_field_6'][$i] <> ''){?>
								<input type="checkbox" class="checkbox"  name="<?php echo $this->get_field_name('px_points_widget_checkbox6'); ?>" value="6" <?php checked($instance['px_points_widget_checkbox6'], 6); ?> />
                                <label><?php echo $px_theme_option['points_table_coloumn_field_6'][$i];?></label>
						<?php }?>
						<?php if(isset($px_theme_option['points_table_coloumn_field_7'][$i]) && $px_theme_option['points_table_coloumn_field_7'][$i] <> ''){?>
								<input type="checkbox" class="checkbox"  name="<?php echo $this->get_field_name('px_points_widget_checkbox7'); ?>" value="7" <?php checked($instance['px_points_widget_checkbox7'], 7); ?> /><label><?php echo $px_theme_option['points_table_coloumn_field_7'][$i];?></label>
						<?php }?>
						<?php if(isset($px_theme_option['points_table_coloumn_field_8'][$i]) && $px_theme_option['points_table_coloumn_field_8'][$i] <> ''){?>
								<input type="checkbox" class="checkbox" name="<?php echo $this->get_field_name('px_points_widget_checkbox8'); ?>" value="8" <?php checked($instance['px_points_widget_checkbox8'], 8); ?> /><label><?php echo $px_theme_option['points_table_coloumn_field_8'][$i];?></label>
						<?php }?>
						<?php if(isset($px_theme_option['points_table_coloumn_field_9'][$i]) && $px_theme_option['points_table_coloumn_field_9'][$i] <> ''){?>
								<input type="checkbox" class="checkbox"  name="<?php echo $this->get_field_name('px_points_widget_checkbox9'); ?>" value="9" <?php checked($instance['px_points_widget_checkbox9'], 9); ?> /><label><?php echo $px_theme_option['points_table_coloumn_field_9'][$i];?></label>
						<?php }
						
					}
				?>
        	</div>
        </p>
		<?php
		}
		function update($new_instance, $old_instance){
			$instance = $old_instance;
			$instance['title'] = $new_instance['title'];
			$instance['select_category'] = $new_instance['select_category'];
			
			
			if(isset($new_instance['px_points_widget_checkbox1'])){
				$instance['px_points_widget_checkbox1'] = $new_instance['px_points_widget_checkbox1'];
			} else {
				$instance['px_points_widget_checkbox1'] = '';
			}
			if(isset($new_instance['px_points_widget_checkbox2'])){
				$instance['px_points_widget_checkbox2'] = $new_instance['px_points_widget_checkbox2'];
			} else {
				$instance['px_points_widget_checkbox2'] = '';
			}
			if(isset($new_instance['px_points_widget_checkbox3'])){
				$instance['px_points_widget_checkbox3'] = $new_instance['px_points_widget_checkbox3'];
			} else {
				$instance['px_points_widget_checkbox3'] = '';
			}
			if(isset($new_instance['px_points_widget_checkbox4'])){
				$instance['px_points_widget_checkbox4'] = $new_instance['px_points_widget_checkbox4'];
			} else {
				$instance['px_points_widget_checkbox4'] = '';
			}
			
			if(isset($new_instance['px_points_widget_checkbox5'])){
				$instance['px_points_widget_checkbox5'] = $new_instance['px_points_widget_checkbox5'];
			} else {
				$instance['px_points_widget_checkbox5'] = '';
			}
			
			if(isset($new_instance['px_points_widget_checkbox6'])){
				$instance['px_points_widget_checkbox6'] = $new_instance['px_points_widget_checkbox6'];
			} else {
				$instance['px_points_widget_checkbox6'] = '';
			}
			
			
			if(isset($new_instance['px_points_widget_checkbox7'])){
				$instance['px_points_widget_checkbox7'] = $new_instance['px_points_widget_checkbox7'];
			} else {
				$instance['px_points_widget_checkbox7'] = '';
			}
			
			if(isset($new_instance['px_points_widget_checkbox8'])){
				$instance['px_points_widget_checkbox8'] = $new_instance['px_points_widget_checkbox8'];
			} else {
				$instance['px_points_widget_checkbox8'] = '';
			}
			if(isset($new_instance['px_points_widget_checkbox9']) && $new_instance['px_points_widget_checkbox9']){
				$instance['px_points_widget_checkbox9'] = $new_instance['px_points_widget_checkbox9'];
			} else {
				$instance['px_points_widget_checkbox9'] = '';
			}
		
			
			
			return $instance;
		}
		function widget($args, $instance){
			global $px_node, $px_theme_option;
			extract($args, EXTR_SKIP);
			$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
			$select_category = empty($instance['select_category']) ? ' ' : apply_filters('widget_title', $instance['select_category']);		

			echo $before_widget;	
			// WIDGET display CODE Start
			if (!empty($title) && $title <> ' '){
				echo $before_title;
				echo $title;
				echo $after_title;
			}
			global $wpdb, $post;
			$pointtable_id  = '';
			
			$args=array(
                  'name' => $select_category,
                  'post_type' => 'pointtable',
                  'post_status' => 'publish',
                  'showposts' => 1,
                );
                $get_posts = get_posts($args);
                if(isset($get_posts[0]->ID)){
                    $pointtable_id = $get_posts[0]->ID;
				}

			if ( isset($pointtable_id) && $pointtable_id <> "" ) {
				 	$pointtable_counter=1;
					$px_pointtable = get_post_meta((int)$pointtable_id, "px_pointtable", true);
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
											$table_check_coloumns = '';
											if(isset($px_theme_option['points_table_coloumn_field_'.$j][$i]) && $px_theme_option['points_table_coloumn_field_'.$j][$i] <> '')
											$table_heads = $px_theme_option['points_table_coloumn_field_'.$j][$i];
											if(isset($table_heads) && $table_heads <> ''){
												$table_check_coloumnss = 'px_points_widget_checkbox'.$j;
												$table_check_coloumns = $instance[$table_check_coloumnss];
												if(isset($table_check_coloumns) && $table_check_coloumns <> ''){
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
                                        $pointtable_counter = 1;
                                        foreach($points_table_data as $points_table_data_value1){
											$count_columns_data = 0;
											$count_value_aray = count($points_table_data_value1);
											$count_columns_data = 0;
											$featured_class = '';
											if(isset($points_table_data_value1[$count_value_aray-1]) && $points_table_data_value1[$count_value_aray-1] == 'yes'){
												$featured_class = 'class="featured-points-row"';
											}
                                            echo '<tr '.$featured_class.'><td>'.$pointtable_counter.'</td>';
											
											
											for($j = 1; $j<=9; $j++){
											$table_heads='';
											$table_check_coloumns = '';
											if(isset($px_theme_option['points_table_coloumn_field_'.$j][$i]) && $px_theme_option['points_table_coloumn_field_'.$j][$i] <> '')
											$table_heads = $px_theme_option['points_table_coloumn_field_'.$j][$i];
											if(isset($table_heads) && $table_heads <> ''){
												$table_check_coloumnss = 'px_points_widget_checkbox'.$j;
												$table_check_coloumns = $instance[$table_check_coloumnss];
												if(isset($table_check_coloumns) && $table_check_coloumns <> ''){
												$count_columns++;
													$valu_index = $j-1;
													if(isset($points_table_data_value1[$valu_index]) && $points_table_data_value1[$valu_index] <> ''){
                                                        echo '<td>'.$points_table_data_value1[$valu_index].'</td>';
													} else {
														echo '<td>-</td>';
													}

												}
											}
										}
										
                                        echo '</tr>';
                                            $pointtable_counter++;
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
			<?php 
			wp_reset_query();
		}else {
			echo '<h4>'.__( 'No results found.', 'Kings Club' ).'</h4>';
		}
		echo $after_widget;
		}
	}
	
// MailChimp Widget
class px_MailChimp_Widget extends WP_Widget {

	private $default_failure_message;

	public $default_loader_graphic;

	private $default_signup_text;

	private $default_success_message;

	private $default_title;

	private $successful_signup = false;

	private $subscribe_errors;

	private $ns_mc_plugin;


	public function px_MailChimp_Widget () {

		$this->default_failure_message = __('There was a problem processing your submission.', 'Kings Club');

		$this->default_signup_text = __('Join now!', 'Kings Club');
		
		$this->default_email_field_text = __('Enter Your Email', 'Kings Club');

		$this->default_success_message = __('Thank you for joining our mailing list. Please check your email for a confirmation link.', 'Kings Club');

		$this->default_title = __('Sign up for our mailing list.', 'Kings Club');

		$widget_options = array('classname' => 'widget_newsletter', 'description' => __( "Displays a sign-up form for a MailChimp mailing list.", 'Kings Club'));

		$this->WP_Widget('px_MailChimp_Widget', __('PX: MailChimp List Signup', 'Kings Club'), $widget_options);

		$this->ns_mc_plugin = CHIMP_MC_Plugin::get_instance();

		$default_loader_graphic = get_template_directory_uri()."/images/admin/ajax-loader.gif";

		$this->default_loader_graphic = get_template_directory_uri()."/images/ajax-loader.gif";

		add_action('parse_request', array(&$this, 'process_submission'));

	}

	/**

	 * @author James Lafferty

	 * @since 0.1

	 */

	public function form ($instance) {

		$mcapi = $this->ns_mc_plugin->get_mcapi();

		if (false == $mcapi) {

			echo $this->ns_mc_plugin->get_admin_notices();

		} else {

			$this->lists = $mcapi->lists();

			$defaults = array(

				'failure_message' => $this->default_failure_message,

				'title' => $this->default_title,
				
				'email_text' => $this->default_email_field_text,
				
				
				'description' => 'Enter Your Email',

				'signup_text' => $this->default_signup_text,

				'success_message' => $this->default_success_message,

				'collect_first' => false,

				'collect_last' => false,

				'old_markup' => false

			);

			$vars = wp_parse_args($instance, $defaults);

			extract($vars);

			?>

					<h3><?php echo  __('General Settings', 'Kings Club'); ?></h3>

					<p>

						<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo  __('Title :', 'Kings Club'); ?></label>

						<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

					</p>

					<p>

						<label for="<?php echo $this->get_field_id('current_mailing_list'); ?>"><?php echo __('Select a Mailing List :', 'Kings Club'); ?></label>

						<select class="widefat" id="<?php echo $this->get_field_id('current_mailing_list');?>" name="<?php echo $this->get_field_name('current_mailing_list'); ?>">

			<?php	

			foreach ($this->lists['data'] as $key => $value) {

				$selected = (isset($current_mailing_list) && $current_mailing_list == $value['id']) ? ' selected="selected" ' : '';

				?>	

						<option <?php echo $selected; ?>value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>

				<?php

			}

			?>

						</select>

					</p>

                    <p>

						<label ><?php echo  __('Description :', 'Kings Club'); ?></label>

                        <textarea  class="widefat" name="<?php echo $this->get_field_name('description'); ?>"  rows="4" cols="8"><?php if(isset($description)){echo $description;} else { 'New Enterprise Commercial <br/> A Funny Disclaimer';} ?></textarea>

					</p>
					<p>

						<label ><?php echo  __('Email Text :', 'Kings Club'); ?></label>

                        <textarea  class="widefat" name="<?php echo $this->get_field_name('email_text'); ?>"  rows="4" cols="8"><?php if(isset($email_text)){echo $email_text;} else { 'Enter Your Email';} ?></textarea>

					</p>
					

					<p>

						<label for="<?php echo $this->get_field_id('signup_text'); ?>"><?php echo __('Sign Up Button Text :', 'Kings Club'); ?></label>

						<input class="widefat" id="<?php echo $this->get_field_id('signup_text'); ?>" name="<?php echo $this->get_field_name('signup_text'); ?>" value="<?php echo $signup_text; ?>" />

					</p>

					<p>

						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('collect_first'); ?>" name="<?php echo $this->get_field_name('collect_first'); ?>" <?php echo  checked($collect_first, true, false); ?> />

						<label for="<?php echo $this->get_field_id('collect_first'); ?>"><?php echo  __('Collect first name.', 'Kings Club'); ?></label>

						<br />

						<input type="checkbox" class="checkbox" id="<?php echo  $this->get_field_id('collect_last'); ?>" name="<?php echo $this->get_field_name('collect_last'); ?>" <?php echo checked($collect_last, true, false); ?> />

						<label><?php echo __('Collect last name.', 'Kings Club'); ?></label>

					</p>

					<h3><?php echo __('Notifications', 'Kings Club'); ?></h3>

					<p><?php echo  __('Use these fields to customize what your visitors see after they submit the form', 'Kings Club'); ?></p>

					<p>

						<label for="<?php echo $this->get_field_id('success_message'); ?>"><?php echo __('Success :', 'Kings Club'); ?></label>

						<textarea class="widefat" id="<?php echo $this->get_field_id('success_message'); ?>" name="<?php echo $this->get_field_name('success_message'); ?>"><?php echo $success_message; ?></textarea>

					</p>

					<p>

						<label for="<?php echo $this->get_field_id('failure_message'); ?>"><?php echo __('Failure :', 'Kings Club'); ?></label>

						<textarea class="widefat" id="<?php echo $this->get_field_id('failure_message'); ?>" name="<?php echo $this->get_field_name('failure_message'); ?>"><?php echo $failure_message; ?></textarea>

					</p>

			<?php

		}

	}

	

	/**

	 * @author James Lafferty

	 * @since 0.1

	 */

	

	public function process_submission () {

		global $px_theme_option;
		
		if(isset($px_theme_option['mailchimp_key']) && isset($_REQUEST[$this->id_base . '_email']) && $px_theme_option['mailchimp_key'] <> ''){
				
				
		

			if (isset($_GET[$this->id_base . '_email'])) {
				
				
				$mcapi = $this->ns_mc_plugin->get_mcapi();
				
	
				header("Content-Type: application/json");
	
				
	
				//Assume the worst.
	
				$response = '';
	
				$result = array('success' => false, 'error' => $this->get_failure_message($_GET['ns_mc_number']));
	
				
	
				$merge_vars = array();
	
				
	
				if (! is_email($_GET[$this->id_base . '_email'])) { //Use WordPress's built-in is_email function to validate input.
	
					
	
					$response = json_encode($result); //If it's not a valid email address, just encode the defaults.
	
					
	
				} else {
	
					
	
					$mcapi = $this->ns_mc_plugin->get_mcapi();
					
					if (false == $mcapi) {
	
					
	
					return false;
	
					
	
				}
					
	
					if (false == $this->ns_mc_plugin) {
	
						
	
						$response = json_encode($result);
	
						
	
					} else {
	
						
	
						if (isset($_GET[$this->id_base . '_first_name']) && is_string($_GET[$this->id_base . '_first_name'])) {
	
							
	
							$merge_vars['FNAME'] = $_GET[$this->id_base . '_first_name'];
	
							
	
						}
	
						
	
						if (isset($_GET[$this->id_base . '_last_name']) && is_string($_GET[$this->id_base . '_last_name'])) {
	
							
	
							$merge_vars['LNAME'] = $_GET[$this->id_base . '_last_name'];
	
							
	
						}
	
						
	
						$subscribed = $mcapi->listSubscribe($this->get_current_mailing_list_id($_GET['ns_mc_number']), $_GET[$this->id_base . '_email'], $merge_vars);
	
					
	
						if (false == $subscribed) {
	
							
	
							$response = json_encode($result);
	
							
	
						} else {
	
						
	
							$result['success'] = true;
	
							$result['error'] = '';
	
							$result['success_message'] =  $this->get_success_message($_GET['ns_mc_number']);
	
							$response = json_encode($result);
	
							
	
						}
	
						
	
					}
	
					
	
				}
	
				
	
				exit($response);
	
				
	
				} elseif (isset($_POST[$this->id_base . '_email'])) {
	
				
	
				$this->subscribe_errors = '<div class="error">'  . $this->get_failure_message($_POST['ns_mc_number']) .  '</div>';
	
				
	
				if (! is_email($_POST[$this->id_base . '_email'])) {
	
					
	
					return false;
	
					
	
				}
	
				
	
				$mcapi = $this->ns_mc_plugin->get_mcapi();
	
				
	
				if (false == $mcapi) {
	
					
	
					return false;
	
					
	
				}
				$merge_vars = array();
				if (!isset($_POST[$this->id_base . '_first_name']) && empty($_POST[$this->id_base . '_first_name'])){$_POST[$this->id_base . '_first_name'] = '';}
				if (!isset($_POST[$this->id_base . '_last_name']) && empty($_POST[$this->id_base . '_last_name'])){$_POST[$this->id_base . '_last_name'] = '';}
				if (!isset($_POST[$this->id_base . '_email']) && empty($_POST[$this->id_base . '_email'])){$_POST[$this->id_base . '_email'] = '';}
				
	
				if (isset($_POST[$this->id_base . '_first_name']) && is_string($_POST[$this->id_base . '_first_name'])  && '' != $_POST[$this->id_base . '_first_name']) {
	
					
	
					$merge_vars['FNAME'] = strip_tags($_POST[$this->id_base . '_first_name']);
	
					
	
				}
	
				
	
				if (isset($_POST[$this->id_base . '_last_name']) && is_string($_POST[$this->id_base . '_last_name']) && '' != $_POST[$this->id_base . '_last_name']) {
	
					
	
					$merge_vars['LNAME'] = strip_tags($_POST[$this->id_base . '_last_name']);
	
					
	
				}
				
				
	
				$subscribed = $mcapi->listSubscribe($this->get_current_mailing_list_id($_POST['ns_mc_number']), $_POST[$this->id_base . '_email'], $merge_vars);
	
				
	
				if (false == $subscribed) {
	
	
	
					return false;
	
					
	
				} else {
	
					
	
					$this->subscribe_errors = '';
	
					
	
					setcookie($this->id_base . '-' . $this->number, $this->hash_mailing_list_id(), time() + 31556926);
	
					
	
					$this->successful_signup = true;
	
					
	
					$this->signup_success_message = '<p>' . $this->get_success_message($_POST['ns_mc_number']) . '</p>';
	
					
	
					return true;
	
					
	
				}	
	
				
	
			}
			
			
		} else if(!isset($px_theme_option['mailchimp_key']) && isset($_REQUEST[$this->id_base . '_email']) && $px_theme_option['mailchimp_key'] == ''){
			
			echo '<div class="error">Invalid API key.</div>';	
			
			return false;
			//echo '<div class="error">'  . $this->get_failure_message($_POST['ns_mc_number']) .  '</div>';	
		}

		

	}

	

	/**

	 * @author James Lafferty

	 * @since 0.1

	 */

	

	public function update ($new_instance, $old_instance) {

		

		$instance = $old_instance;

		

		$instance['collect_first'] = ! empty($new_instance['collect_first']);

		

		$instance['collect_last'] = ! empty($new_instance['collect_last']);

		

		$instance['current_mailing_list'] = esc_attr($new_instance['current_mailing_list']);

		

		$instance['failure_message'] = esc_attr($new_instance['failure_message']);

		

		$instance['signup_text'] = esc_attr($new_instance['signup_text']);
		
		$instance['email_text'] = esc_attr($new_instance['email_text']);

		

		$instance['success_message'] = esc_attr($new_instance['success_message']);

		

		$instance['title'] = esc_attr($new_instance['title']);

		$instance['description'] = esc_attr($new_instance['description']);

		

		return $instance;

		

	}

	

	/**

	 * @author James Lafferty

	 * @since 0.1

	 */

	

	public function widget ($args, $instance) {

		

		extract($args);

		

	

			

			echo $before_widget . $before_title . $instance['title'] . $after_title;


			
			
			if ($this->successful_signup) {

				echo '<p class="bad_authentication">'.$this->signup_success_message.'</span>';

			}

				//cs_mailchimp_add_scripts ();

				global $px_theme_option;

				?>	

                

               

                <?php echo $this->subscribe_errors; ?>

				

                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="<?php echo $this->id_base . '_form-' . $this->number; ?>" method="post">

					

					<?php	
					$mailchimpkey  = '';
					if($px_theme_option['mailchimp_key'] == ''){
						$mailchimpkey = 'invalid Mailchimp API Key';
						
					}
					echo '<input type="hidden" name="mailchimp_key" id="mailchimp_key_validation" value="'.$mailchimpkey.'">';

						if (isset($instance['collect_first']) && $instance['collect_first'] <> '') {

					?>	
					<label>
					<input type="text" name="<?php echo $this->id_base . '_first_name'; ?>" value="<?php if($px_theme_option['trans_switcher'] == "on"){ _e('First Name :','Kings Club');}else{ echo $px_theme_option['trans_firstname']; }?>" />
					</label>

					<?php

						}
						if (isset($instance['collect_last']) && $instance['collect_last'] <> '') {

					?>	
					<label>	
					<input type="text" name="<?php echo $this->id_base . '_last_name'; ?>" value="<?php if($px_theme_option['trans_switcher'] == "on"){ _e('Last Name :','Kings Club');}else{ echo $px_theme_option['trans_lastname']; }?>" />

					</label>

					<?php	

						}
					
					?>

						<input type="hidden" name="ns_mc_number" value="<?php echo $this->number; ?>" />
						<label>
                            <input id="<?php echo $this->id_base; ?>-email-<?php echo $this->number; ?>" type="text" name="<?php echo $this->id_base; ?>_email" value="<?php if(isset($instance['email_text']) && $instance['email_text'] <> ''){echo html_entity_decode($instance['email_text']);} else {_e('Enter Your Email','');};?>"/>
                        </label>
                        <label>
							<?php if(!isset($instance['signup_text'])){ $instance['signup_text'] = 'Submit';}?>
							<input type="submit" name="<?php echo $instance['signup_text']; ?>" class="btn cs-bgcolr" value="<?php echo $instance['signup_text']; ?>">
                        </label>
                        <label>
                        	<?php if(isset($instance['description']) && $instance['description'] <> ''){echo html_entity_decode($instance['description']);}?>
                        </label>
                        <!--<button class="btn cs-bgcolr" name="<?php echo $instance['signup_text']; ?>"><?php echo $instance['signup_text']; ?></button>-->

					</form>


						<script type="text/javascript">

							jQuery(document).ready(function(){

								px_mailchimp_add_scripts ();

								jQuery('#<?php echo $this->id_base; ?>_form-<?php echo $this->number; ?>').ns_mc_widget({"url" : "<?php echo $_SERVER['PHP_SELF']; ?>", "cookie_id" : "<?php echo $this->id_base; ?>-<?php echo $this->number; ?>", "cookie_value" : "<?php //echo $this->hash_mailing_list_id(); ?>", "loader_graphic" : "<?php //echo $this->default_loader_graphic; ?>"});

							});

						 </script>

				<?php

			

			echo $after_widget;

		

		

	}

	

	/**

	 * @author James Lafferty

	 * @since 0.1

	 */

	

	private function hash_mailing_list_id () {

		

		$options = get_option($this->option_name);

		

		$hash = md5($options[$this->number]['current_mailing_list']);

		

		return $hash;

		

	}

	

	/**

	 * @author James Lafferty

	 * @since 0.1

	 */

	

	private function get_current_mailing_list_id ($number = null) {

		

		$options = get_option($this->option_name);

		if(isset($options[$number]['current_mailing_list'])){

			return $options[$number]['current_mailing_list'];
		}

		

	}

	

	/**

	 * @author James Lafferty

	 * @since 0.5

	 */

	

	private function get_failure_message ($number = null) {

		

		$options = get_option($this->option_name);

		return __('There was a problem processing your submission.', 'Kings Club');

		//return $options[$number]['failure_message'];

		

	}

	

	/**

	 * @author James Lafferty

	 * @since 0.5

	 */

	

	private function get_success_message ($number = null) {

		

		$options = get_option($this->option_name);

		

		return $options[$number]['success_message'];

		

	}

}

?>