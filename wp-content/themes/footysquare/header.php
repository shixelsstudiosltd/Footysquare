<?php
	global $px_theme_option, $px_page_builder, $px_meta_page, $px_node;
	$px_theme_option = get_option('px_theme_option');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
					
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' /> 
    <title>
	<?php
	    bloginfo('name'); ?> | 
    <?php 
		if ( is_home() or is_front_page() ) { bloginfo('description'); }
		else { wp_title(''); }
    ?>
    </title>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="shortcut icon" href="<?php echo $px_theme_option['fav_icon'] ?>" />
	
    <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<?php 
	if(isset($px_theme_option['header_code']))
    	echo  htmlspecialchars_decode($px_theme_option['header_code']); 
	    if ( is_singular() && get_option( 'thread_comments' ) )
        	wp_enqueue_script( 'comment-reply' );  
         	wp_head(); 
    ?>
	<?php
	if ( is_user_logged_in() ) {
	?>
	<style>
	@media screen and (max-width: 782px){
		html {
			margin-top: 0px !important;
		}
	}
	@media only screen and (max-width: 546px){
		.mobile-search {
			margin-left: 85px;
		}
		.header-user-profile .btn-group button{
			top:0px;
		}
		.btn-default:hover{
			background:none !important;
		}
	}
	@media only screen and (max-width: 455px){
		.header-user-profile .btn-group button{
			top: -22px;
		}
	}
	@media only screen and (max-width: 341px){
		.header-user-profile .btn-group button{
			top: -2px;
		}
		.header-notif{
			padding: 0px;
			margin: 17px 36px 0px 0px;
		}
	}
	@media only screen and (max-width: 341px){
		.mobile-search {
			margin-left: 74px;
			position: absolute;
		}
	}
	@media only screen and (max-width: 273px){
		.header-notif {
			padding: 0px;
			margin: 0px;
			width: 55px !important;
			top: 17px;
			right: 48px;
		}
		.header-searchform{
			right: 30px;
		}
	}
	</style>
	<?php } ?>
    </head>
	<body <?php body_class(); px_bg_image(); px_bgcolor_pattern();  ?> >
 		<?php  
			px_custom_styles();
			px_color_switcher();
		?>
	
		
		<div id="wrappermain-pix" class="wrapper <?php echo px_wrapper_class();?>">
		<!-- Header Start -->
        <header id="header">
            <!-- Top Head Start -->
            <div class="top-head">
            	<div class="container">
					<div class="width11">
						<!-- Logo -->
						<div class="logo col-lg-6 col-md-3 col-xs-3">
							<?php
								 if(isset($px_theme_option['logo']) && $px_theme_option['logo'] <> ''){
									if(is_front_page()){
									  px_logo($px_theme_option['logo'], $px_theme_option['logo_width'], $px_theme_option['logo_height']);
									  }
									else {
									
									echo '<div id="half-logo">';
									px_logo($px_theme_option['logo'], $px_theme_option['logo_width'], $px_theme_option['logo_height']);
									echo '</div>';
									}
								} else {
									echo '<a href="'.home_url().'">';
										bloginfo('name');
									echo '</a>';
								}
							 ?>
						</div>
						<!-- Logo Close -->
						<!-- Add Right side header content here -->					
						<!---header right start-->
						<?php
						if ( is_user_logged_in() ) {
						?>
						<div class="col-lg-2 col-md-2 col-xs-1 header-notif">							
							<a href="<?php echo get_site_url().'/?page_id=1300'; ?>"> <!--1474-->
								<i class="fa fa-envelope-o">
									<span class="custom-badge" id="message-noti-badge">
										<?php 
											//echo do_shortcode('[unreadmsgs]'); 
											
											// Unread Mail
											if (function_exists('__wps__mail')) {
												echo "<div id='__wps__email_box' class=''></div>";
											}
											//echo "</div>";
											
										?>
									</span>
								</i>
							</a>
							<i class="fa fa-futbol-o open-activity"></i>
								<span class="custom-badge" id="header-noti-count"></span>
								<div id="header-activity">
									<!--<div class="loader-noti"></div>	-->
									<div id="header-activity-content"></div>
									<a href="?p=2370"><div id="notify-see-all" class="col-lg-12 col-md-12 col-xs-12 text-center see-all">Show All</div></a>
								</div>
								
								
						</div>
						
						<!--Autocomplete-->
						<span id="notify_sound"></span>
						
						<?php } ?>
						
						<?php
							global $current_user;
							$current_user_id = $current_user->ID;
						?>
						
						<script>
							
							
							
						/* header activity */
							jQuery(document).ready(function() {

							jQuery( ".mobile-search" ).click(function() {
								  jQuery( ".mobile-search-sec" ).toggle( "fade", function() {
									// Animation complete.
								  });
								});
								
								noti_counter();
								
								var counter=0,newnotifi='';
								
								function noti_counter(){
								
								$.post( "<?php echo get_site_url();?>/wp-content/themes/footysquare/set_notifications.php?uid=<?php echo $current_user_id;?>", function( data ) {
										  $( "#header-noti-count" ).html( data );
										  
										  noti_intval = parseInt(data);
										  //alert(data);

										  if(noti_intval==0)
											$("#header-noti-count").hide();
										  else
											$("#header-noti-count").show();
										  
										  
										  //if new notification comes
										  if(newnotifi != data && data != 0){
											audioElement.play();
											setTimeout(function () {audioElement.pause()}, 3000);
										  }
										  newnotifi = data;

										  if(data != 0 && counter == 0){
											counter++;
											audioElement.play();
											setTimeout(function () {audioElement.pause()}, 3000);
										  }
										  //audioElement.pause();
										});
								}
								//refresh notification after every 5 sec
								setInterval(function () {noti_counter()}, 5000);
								
								
								
								//play notification sound
								//soundfile = "<?php echo get_template_directory_uri().'/images/notify.mp3'; ?>";
								
								var audioElement = document.createElement('audio');
								audioElement.setAttribute('src', '<?php echo get_template_directory_uri()."/images/notify.mp3";?>');
								audioElement.load()
								$.get();
								
								
								//end play sound
								 
								$(".open-activity").click(function(){
								  $("#header-activity").toggle( "fast", function() {
										$.post( "<?php echo get_site_url();?>/wp-content/themes/footysquare/get_notifications.php", function( data ) {
										  $( "#header-activity-content" ).html( data );
										  noti_counter();
										});
									});
								});
								
								//check if notification count is zero
								msg_htmlval=$('#message-noti-badge').html();
								msg_intval = parseInt(msg_htmlval);
								
								if(msg_intval==0)
									$('#message-noti-badge').hide();
								
							});
							
							jQuery(document).ready(function() {
							  $('a[href*=#]:not([href=#])').click(function() {
								if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
								  var target = $(this.hash);
								  target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
								  if (target.length) {
									$('html,body').animate({
									  scrollTop: target.offset().top
									}, 1000);
									return false;
								  }
								}
							  });
							  
							  //hide div on click
								$('.profile-head-bottom').click(function () {
									$(this).hide('fade',500);
								});
								
							});
							
						</script>
						
						<div class="mobile-search"><i class="fa fa-search"></i></div>
						
						<div class="header-searchform mobile-search-sec col-lg-2 col-md-2 col-sm-1 col-xs-7">							
							<?php echo px_search(); ?>					    
						</div>		
						<?php
						if ( is_user_logged_in() ) {
						?>
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 header-user-profile">							
							<div class="btn-group">							  
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">								
									<?php 
										echo strtolower(wp_get_current_user()->user_login); 
									?> <span class="caret"></span>							  
								</button>							  
								<ul class="dropdown-menu" role="menu">								
									<li><a href="<?php echo get_site_url().'/?page_id=1812'; ?> ">My Footysquare</a></li>
									<li><a href="<?php echo get_site_url().'/?page_id=1770'; ?> ">User Profile</a></li>							  
									<li><a href="<?php echo get_site_url().'/?page_id=2037'; ?> ">Account</a></li>							  
									<li class="divider"></li>
									<li><?php wp_nav_menu( array('menu' => 'user' )); ?></li>
									<li><a href="<?php echo wp_logout_url($_SERVER['REQUEST_URI']); ?> ">Logout</a></li>							  
								</ul>	
								
							</div>					   
						</div>	
						<?php } else {?>
							<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 header-user-profile">							
							<div class="btn-group">							  
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">								
									<i class="fa fa-user"></i><span class="caret"></span>							  
								</button>							  
								<ul class="dropdown-menu" role="menu">								
									<li><a href="<?php echo get_site_url().'/?page_id=2034'?>">login/register</a></li>			
								</ul>							
							</div>					   
						</div>	
						<?php } ?>
					</div>
					<div class="header-menu">
						 <?php echo do_shortcode('[responsive-menu]'); ?>
					</div>					
					<!--header right ends-->
                </div>
            </div>
            <!-- Top Head End -->
        </header>
    <!-- Header Close -->
    <div class="clear"></div>
    <div id="main">
        <!-- Inner Main -->
        <div id="innermain">
             <?php
				/*if(isset($px_theme_option['announcement_fixtures_category']) && $px_theme_option['announcement_fixtures_category'] <> ''){ 
					$announcement_category =$px_theme_option['announcement_fixtures_category']; 
					fnc_announcement();
				}else{
					$announcement_category ='';
				}*/
			 ?>
              <?php 
				if(is_home() || is_front_page()){
					if(isset($advertisingwidgets['home-top-widget']) && count($advertisingwidgets['home-top-widget'])>0){?>
                        <div class="home-top-widget">
                        	<div class="container">
                            	
                        		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('home-top-widget')) : ?><?php endif; ?>
                            </div>
                        </div>
                 <?php }
			 }
			 if(!(is_home() || is_front_page())){?>
			 <style>
				.footer-widget{
					top: 100px;
				}
			 </style>
			 <?php } ?>
           <div class="container">	
			
			<?php //do_shortcode('[adbanner]'); ?>
		   <!--header banner starts-->					
		   <?php 					//$advertisingwidgets = wp_get_sidebars_widgets();					if(isset($advertisingwidgets['header-advertisement-widget']) && count($advertisingwidgets['header-advertisement-widget'])>0){?>                        
		   <!--<div class="rightheader">                        	-->
		   <?php //if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('header-advertisement-widget')) : ?><?php //endif; ?>                        
		  <!-- </div>  -->                  <?php //}?>			    
		   <!--header banner ends-->
		   
		   <!--<div class="loader"></div>-->
                <div id="main-inner-content" class="row">
					 <?php if(!is_home() and !is_front_page()) {
						 if(isset($px_theme_option['header_breadcrumbs']) && $px_theme_option['header_breadcrumbs'] <> ''){ 
						  ?>
                            <div class="breadcrumb"> 
                                <?php //px_breadcrumbs(); ?>
                            </div>
                    	<?php }
					
					 }
					 ?>
                      
                    
                  