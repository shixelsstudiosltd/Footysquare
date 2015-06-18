<?php 
/* Template Name:My Footysquare */
get_header();  
					
if ( is_user_logged_in() ) { 
	?>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<div class="col-lg-12 no-padding">
		
		<!--show my footysquare header meta-->
		<div class="col-lg-12 no-padding fs-head">
			<div class="col-lg-6 col-md-12 fs-meta">
				<div class="fs-meta-title">new thread in your favourite:</div>
				<div class="col-lg-4 col-md-4">
					<span class="fs-fav-label">league</span><span class="fs-fav-count">5</span>
				</div>
				<div class="col-lg-4 col-md-4">
					<span class="fs-fav-label">clubs</span><span class="fs-fav-count">9</span>
				</div>
				<div class="col-lg-4 col-md-4">
					<span class="fs-fav-label">players</span><span class="fs-fav-count">16</span>
				</div>
			</div>
			<div class="col-lg-1 col-md-2 fs-meta">
				<div class="fs-meta-title">responses</div>
				<span class="fs-fav-label">to your threads</span><span class="fs-fav-count">10</span>
			</div>
			<div class="col-lg-1 col-md-2 fs-meta">
				<div class="fs-meta-title">responses</div>
				<span class="fs-fav-label">to your posts</span><span class="fs-fav-count">-</span>
			</div>
			<div class="col-lg-1 col-md-2 fs-meta">
				<div class="fs-meta-title">new likes</div>
				<span class="fs-fav-label">&nbsp;</span><span class="fs-fav-count">15</span>
			</div>
			<div class="col-lg-1 col-md-2 fs-meta">
				<div class="fs-meta-title">watching</div>
				<span class="fs-fav-label">&nbsp;</span><span class="fs-fav-count">19</span>
			</div>
		</div>
		<?php do_shortcode('[adbanner]'); ?>
		<p class="section-second-title">what's new in my favourite forums :</p>
		<?php the_content();?>
		<div class="clear"></div>
		 <!--show league recent thread-->
		 <div class="col-lg-6 no-pad-left">
		  <div class="div-section no-padding fs-sec-slider">
		 
				<div class="section-title"><i class="fa fa-group"></i>
				<p>most recent thread in <?php echo $fetch_country;?></p></div>
		  
		  </div>
		 </div> 
		
		 <!--show league active thread-->
		  <div class="col-lg-6 no-pad-right">
			<div class="div-section no-padding fs-sec-slider">
				<div class="section-title"><i class="fa fa-group"></i>
				<p>most active thread in <?php echo $fetch_country;?></p></div>
		   
		   </div>
		  </div>
		   
		<p class="section-second-title">my watched threads and activities :</p>

	  <script src="<?php get_site_url(); ?>/fs/wp-content/themes/footysquare/scripts/frontend/jquery.bxslider.js"></script>
		<script>
		$('.threadslider').bxSlider({
		  mode: 'fade',
		  captions: true
		});
		</script>
	<style>
	
	</style>
	</div> <!--close content wrap-->
<?php } 
else{
	echo '<h1>404 error!</h1>';
}
					
?>

<?php get_footer();?>
<!-- Columns End -->

<style>
.footer-widget{
	top:100px;
}
</style>