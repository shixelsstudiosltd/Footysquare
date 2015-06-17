<?php
/*
Template Name: About us
*/
 	global $px_theme_option;
	$px_node = new stdClass();
  	get_header();
	$px_layout = '';
	if (have_posts()):
		while (have_posts()) : the_post();
		$post_xml = get_post_meta($post->ID, "post", true);	
		if ( $post_xml <> "" ) {
			$px_xmlObject = new SimpleXMLElement($post_xml);
			$px_layout = $px_xmlObject->sidebar_layout->px_layout;
			if ( $px_layout == "left") {
				$px_layout = "col-md-9";
			}
			else if ( $px_layout == "right" ) {
				$px_layout = "col-md-9";
			}
			else {
				$px_layout = "col-md-12";
			}
		}else{
			$px_layout = "col-md-12";
			$image_url = '';
			$px_xmlObject = new stdClass();
			$px_xmlObject->var_pb_post_social_sharing = '';
			$px_xmlObject->var_pb_post_featured = '';
			$px_xmlObject->var_pb_post_attachment = '';
			$px_xmlObject->var_pb_post_author = '';
		}
		$width = 768;
		$height = 403;
		$image_url = px_get_post_img_src($post->ID, $width, $height);	
							
		?>
            <!--Left Sidebar Starts-->

	<?php if ($px_layout == 'col-md-9' and $px_xmlObject->sidebar_layout->px_layout == 'left'){ ?>

    <aside class="sidebar-left col-md-3"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_xmlObject->sidebar_layout->px_sidebar_left) ) : ?><?php endif; ?></aside>
		
		
		
    <?php wp_reset_query();} ?>
	<!--Left Sidebar End-->
    <div class="<?php echo $px_layout; ?>" >
    	<?php px_page_title();?>
		<div class="blog blog_detail">
            <article>
                <div class="pix-content-wrap">
					<div class="col-lg-12 about-us">
						<div class="about-sections">
							<div class="about-sec-title bg-line">
								<p id="football-icon"></p>
								<span>ABOUT US</span>
							</div>
							<div class="t-content">
								<?php the_field('about_us');?>
							</div>
						</div>
						<div class="about-sections">
							<div class="about-sec-title  bg-line">
								<p id="football-icon"></p>
								<span>OUR MISSION</span>
							</div>
							<div class="t-content">
								<?php the_field('our_mission');?>
							</div>
						</div>
						
						<!--contact info-->
						<div class="bg-line">
							<div class="about-sec-title contact-sec-title">
								<p>CONTACT US</p>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-xs-12 no-pad-left">
							<div class="contact-icon advertisement-icon"></div>
							<div class="contact-sec advertisement">
								<p class="contact-title">ADVERTISMENT</p>
								<?php the_field('advertisment');?>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-xs-12">
							<div class="contact-icon career-icon"></div>
							<div class="contact-sec career">
								<p class="contact-title">CAREER</p>
								<?php the_field('carrer');?>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-xs-12 no-pad-right">
							<div class="contact-icon partnership-icon"></div>
							<div class="contact-sec partnership">
								<p class="contact-title">PARTNERSHIP</p>
								<?php the_field('partnerships');?>
							</div>
						</div>
						<div class="col-lg-12 no-padding"><hr/></div>
						
						<!--contact us form-->
						<div class="col-lg-12 no-padding">
							<?php the_content();?>
						</div>
					</div>
				</div>
            </article>
        </div>
        
	</div>
     <?php
	endwhile;   
	endif;
 	if ( $px_layout == 'col-md-9' and $px_xmlObject->sidebar_layout->px_layout == 'right'){ ?>
 		<aside class="sidebar-right col-md-3">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_xmlObject->sidebar_layout->px_sidebar_right) ) : ?><?php endif; ?>
        </aside>
 	<?php }
	get_footer();
 ?>
 
 <style>
 .bottom_banner img{
	display:none;
}
.footer-widget{
	top:100px;
}
 </style>