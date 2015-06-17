<?php
	// Corlor Styles for front end
	function px_custom_styles() {
		global $px_theme_option;
		$header_bg_color = $nav_bg_color = $nav_color = '';
		if ( isset($_POST['style_sheet']) ) {
			$_SESSION['kcsess_style_sheet'] = $_POST['style_sheet'];
			$px_color_scheme = $_SESSION['kcsess_style_sheet'];
		}
		
		elseif (isset($_SESSION['kcsess_style_sheet']) and $_SESSION['kcsess_style_sheet'] <> '') {
			$px_color_scheme = $_SESSION['kcsess_style_sheet'];
		} else {
			$px_color_scheme = $px_theme_option['custom_color_scheme'];
		}
		if(isset($px_theme_option['header_bg_color']) && $px_theme_option['header_bg_color'] <> ''){
			$header_bg_color = $px_theme_option['header_bg_color'];
		}
		if(isset($px_theme_option['nav_bg_color']) && $px_theme_option['nav_bg_color'] <> ''){
			$nav_bg_color = $px_theme_option['nav_bg_color'];
		}
		if(isset($px_theme_option['nav_color']) && $px_theme_option['nav_color'] <> ''){
			$nav_color = $px_theme_option['nav_color'];
		}
		
		?>
		<style type="text/css">
		.pix-colr, .pix-colrhvr:hover,.price-table article:hover h3,.breadcrumbs ul li.pix-active,#footer p a:hover,.is-countdown span:before,.pagination .active,
/* New Clases Add*/.event-listing article:hover .text .pix-post-title a,.cs-post-title a,.pagination .active,.blog-medium-options li a,.is-countdown span,.widget ul li:hover a { color:<?php  echo $px_color_scheme; ?> !important;
		}
		.pix-bgcolr,.pix-bgcolrhvr:hover,nav.navigation > ul > li > a:before,.cart-sec span,.navigation ul ul li:hover > a,.navigation ul > li.current-menu-item > a,
		.navigation ul ul li.current-menu-item > a,.price-table article:hover .pix-price-box, .event.evevt-listing article:hover .text .btn-boobked, .match-result.match-lost p,.event.event-listing.event-listing-v2 article:hover,.cycle-pager-active,.widget .tagcloud a:hover, .event.event-listing article:hover .text .btn-boobked, .flex-direction-nav li a:hover /**/, .our-team-sec article:hover figure figcaption .pix-post-title a,.footer-widget .widget_newsletter .error,.news-section article:hover .text,.password_protected form input[type="submit"],.team-vertical article figcaption .caption h2,
#respond form input[type="submit"],#wp-calendar caption,.gallery ul li figure figcaption a,.woocommerce-pagination ul li a:hover,.woocommerce-pagination ul li span,.woocommerce-tabs .tabs .active a, span.match-category.cat-neutral, .event.event-listing article:hover .text .btn,.widget_search form input[type="submit"], .woocommerce .button,.onsale,.gallery ul li:hover .text,.footer-icons .followus a:hover,
/* New Clases Add*/.searchform button,.tabs.horizontal .nav-tabs li.active a,p.stars span a.active,.event.event-listing.event-listing-v2 .btn-viewall,.featured-title,
.pix-feature article .blog-bottom .btn,.pix-feature .featured,.blog-vertical .tab-content header.pix-heading-title h2,header #lang_sel a:hover, header #lang_sel ul ul a:hover,
.post-tags a:hover,.blog-vertical header.pix-heading-title h2,.pix-tittle,nav.navigation > ul > li:hover > a, nav.navigation > ul > li.current-menu-ancestor > a,.table tbody tr:hover,.widget_newsletter label .btn{
			background-color:<?php  echo $px_color_scheme; ?> !important;
		}
		.pix-bdrcolr ,.tabs.horizontal .nav-tabs li.active,.address-info .text,.subtitle h1,.about-us article .text,blockquote,
		.pagination > ul > li > a:before,.pagination > ul > li > a:before,.pagination > ul > li > span.active:before,.footer-icons .followus a:hover{
			border-color:<?php  echo $px_color_scheme; ?> !important;
		}
		#banner .flexslider figcaption .pix-desc h3 span {
		   box-shadow: -10px  0 0 <?php  echo $px_color_scheme; ?>,10px  0 0 <?php  echo $px_color_scheme; ?> !important; 
 		}
		.our-team-sec article:hover figure figcaption .pix-post-title a{
			 box-shadow: -10px  0 0 <?php  echo $px_color_scheme; ?>,10px  0 0 <?php  echo $px_color_scheme; ?> !important;   
		}
		header#header .top-head{
			background-color:<?php  echo $header_bg_color; ?> !important;
		}
		nav.navigation > ul > li > a
		{
			color:<?php  echo $nav_color; ?> !important;
		}
		header #mainheader{
			background-color:<?php  echo $nav_bg_color; ?> !important;
		}
		.sliderpagination ul li:before{
			border-color: transparent <?php  echo $px_color_scheme; ?> !important;
		}
		.footer-widget .widget_newsletter .error:before{
			border-top-color: <?php  echo $px_color_scheme; ?> !important;
		}
		</style>
		<?php 
	}
	
	
// Corlor Switcher for front end

function px_color_switcher(){

	global $px_theme_option;

 	if ( isset($px_theme_option['color_switcher']) && $px_theme_option['color_switcher'] == "on" ) {

		if ( empty($_POST['patter_or_bg']) ){

			$_POST['patter_or_bg'] = '';

		}

		if ( empty($_POST['reset_color_txt']) ) { 

			$_POST['reset_color_txt'] = "";

		}

		else if ( $_POST['reset_color_txt'] == "1" ) {

			$_POST['layout_option'] = 'wrapper_boxed';

			$_POST['custome_pattern'] = "";

			$_POST['bg_img'] = "";

			$_POST['style_sheet'] = $px_theme_option['custom_color_scheme'];

 		}

		

		if ( $_POST['patter_or_bg'] == 0 ){

			$_SESSION['kcsess_bg_img'] = '';

		}

		else if ( $_POST['patter_or_bg'] == 1 ){

			$_SESSION['kcsess_custome_pattern'] = '';

		}

		

		if ( isset($_POST['layout_option']) ) {

			$_SESSION['kcsess_layout_option'] = 'wrapper_boxed';

		}

		if ( isset($_POST['style_sheet']) ) {

			$_SESSION['kcsess_style_sheet'] = $_POST['style_sheet'];

		}

		

		if ( isset($_POST['custome_pattern']) ) {

			$_SESSION['kcsess_custome_pattern'] = $_POST['custome_pattern'];

		}

		if ( isset($_POST['bg_img']) ) {

			$_SESSION['kcsess_bg_img'] = $_POST['bg_img'];

		}



		if ( empty($_SESSION['kcsess_layout_option']) or $_POST['reset_color_txt'] == "1" ) { $_SESSION['kcsess_layout_option'] = ""; }

		if ( empty($_SESSION['kcsess_header_styles']) or $_POST['reset_color_txt'] == "1" ) { $_SESSION['kcsess_header_styles'] = ""; }

		if ( empty($_SESSION['kcsess_style_sheet']) or $_POST['reset_color_txt'] == "1" ) { $_SESSION['kcsess_style_sheet'] = ''; }

		if ( empty($_SESSION['kcsess_custome_pattern']) or $_POST['reset_color_txt'] == "1" ) { $_SESSION['kcsess_custome_pattern'] = ""; }

		if ( empty($_SESSION['kcsess_bg_img']) or $_POST['reset_color_txt'] == "1" ) { $_SESSION['kcsess_bg_img'] = ""; }



		$theme_path = get_template_directory_uri();	

		wp_enqueue_style( 'wp-color-picker' );

		

		wp_enqueue_script('iris',admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),false, 1  );

		wp_enqueue_script('wp-color-picker',admin_url( 'js/color-picker.min.js' ),array( 'iris' ),false,1);

		$colorpicker_l10n = array(

			'clear' => 'Clear',

			'defaultString' => 'Default',

			'pick' => 'Select Color'

		);

		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );

?>



		<script type="text/javascript">

        jQuery(document) .ready(function($){

   			jQuery("#togglebutton").click(function(){

				jQuery("#sidebarmain").trigger('click')

				jQuery(this).toggleClass('btnclose');

				jQuery("#sidebarmain") .toggleClass('sidebarmain');

				return false; 

		   });

           jQuery("#pattstyles li label") .click(function(){
			   
			   var classname=jQuery("#wrappermain-pix") .hasClass("wrapper_boxed"); 
			   
			

				if(classname == false) { 

					alert("Please select Boxed View")

					return false; 

					

				} else {

					jQuery("#backgroundimages li label") .removeClass("active");	

					jQuery("#patter_or_bg") .attr("value","0");

					var ah = jQuery(this) .find('input[type="radio"]') .val();

					jQuery('body') .css({"background":"url(<?php echo $theme_path?>/images/pattern/pattern"+ah+".jpg)"});

				}

      });

      jQuery("#backgroundimages li label") .click(function(){
		  
		   var classname=jQuery("#wrappermain-pix") .hasClass("wrapper_boxed"); 
			   

		// var classname=$(".layoutoption li:first-child label") .hasClass("active"); 

			if(classname == false) { 

				alert("Please select Boxed View")

				return false; 

				

			} else {

				$("#patter_or_bg") .attr("value","1");

				$("#pattstyles li label") .removeClass("active");	
				
				$(this) .parents(".selectradio") .find("label") .removeClass("active");

		 		$(this) .addClass("active");

				var ah = $(this) .find('input[type="radio"]') .val();

				$('body') .css({"background":"url(<?php echo $theme_path?>/images/background/bg"+ah+".png) no-repeat center / cover fixed"});

			}

	  

     });

   $("#backgroundimages li label, #pattstyles li label") .click(function($){
	   
		
		var classname=$(".layoutoption li:first-child label") .hasClass("active"); 

		if(classname) {
			
			alert("Please select Boxed View")

			return false; 

		}else {
	
		  $(this) .parents(".selectradio") .find("label") .removeClass("active");

		  $(this) .addClass("active");


	

		 }

    });

                jQuery(".layoutoption li label") .click(function(){

					//jQuery("#header").scrollToFixed();

    var th = $(this).find('input') .val();

    $("#wrappermain-pix") .attr('class','');

    $('#wrappermain-pix') .addClass(th);

                $(this) .parents(".selectradio") .find("label") .removeClass("active");

                $(this) .addClass("active");


                });

    

    $(".accordion-sidepanel .innertext") .hide();

    $(".accordion-sidepanel header") .click(function(){

     if ($(this) .next() .is(":visible")){

       $(".accordion-sidepanel .innertext") .slideUp(300);

       $(".accordion-sidepanel header") .removeClass("active");

       return false;

      }

    $(".accordion-sidepanel .innertext") .slideUp(300);

    $(".accordion-sidepanel header") .removeClass("active");

    $(this) .addClass("active");

    $(this).next() .slideDown(300);

     

    

    });

    

        });



	jQuery(document).ready(function($){

		jQuery(".colorpicker-main").click(function(){

		jQuery(this).find('.wp-color-result').trigger('click'); 

    });

	<!-- Color-->

	var cf = '.pix-colr,.pix-colrhvr:hover,.price-table article:hover h3,.breadcrumbs ul li.pix-active,#footer p a:hover,.event-listing article:hover .text .pix-post-title a,.cs-post-title a,.pagination .active,.blog-medium-options li a,.is-countdown span,.widget ul li:hover a,.is-countdown span:before,.pagination .active'; 

	<!-- Background Color-->

var bc ='.pix-bgcolr,.pix-bgcolrhvr:hover,nav.navigation > ul > li > a:before,.cart-sec span,.navigation ul ul li:hover > a,.navigation ul ul li.current-menu-item > a,.price-table article:hover .pix-price-box,.event.evevt-listing article:hover .text .btn-boobked,.match-result.match-lost p,.event.event-listing.event-listing-v2 article:hover,.cycle-pager-active,.widget .tagcloud a:hover,.event.event-listing article:hover .text .btn-boobked,.flex-direction-nav li a:hover,.our-team-sec article:hover figure figcaption .pix-post-title a,#respond form input[type="submit"],#wp-calendar caption,.gallery ul li figure figcaption a,.woocommerce-pagination ul li a:hover,.woocommerce-pagination ul li span,.woocommerce-tabs .tabs .active a,span.match-category.cat-neutral,.event.event-listing article:hover .text .btn,.widget_search form input[type="submit"],.woocommerce .button,.onsale,.gallery ul li:hover .text,.searchform button,.tabs.horizontal .nav-tabs li.active a,p.stars span a.active,.event.event-listing.event-listing-v2 .btn-viewall,.featured-title,.pix-feature article .blog-bottom .btn,.pix-feature .featured,.blog-vertical .tab-content header.pix-heading-title h2,header #lang_sel a:hover,header #lang_sel ul ul a:hover,.post-tags a:hover,.blog-vertical header.pix-heading-title h2,.pix-tittle,nav.navigation > ul > li:hover > a, nav.navigation > ul > li.current-menu-ancestor > a,.table tbody tr:hover,.widget_newsletter label .btn,.footer-widget .widget_newsletter .error,.news-section article:hover .text,.navigation ul > li.current-menu-item > a,.password_protected form input[type="submit"],.team-vertical article figcaption .caption h2,.footer-icons .followus a:hover';

	<!-- Border Color-->

	var boc ='.pix-bdrcolr,.tabs.horizontal .nav-tabs li.active,.address-info .text,.subtitle h1,.about-us article .text,blockquote,.pagination > ul > li > a:before,.pagination > ul > li > a:before,.pagination > ul > li > span.active:before,.footer-icons .followus a:hover';

	<!-- Border Transparent Color-->

	var boc2 =".sliderpagination ul li:before";
	
	var bck = ".datepicker thead tr:first-child th";

 	jQuery("#colorpickerwrapp span.col-box") .live("click",function(event) {
			//alert('test');
			var a = jQuery(this).data('color');
			//alert(a);
			jQuery("#bgcolor").val(a);
			jQuery('.wp-color-result').css('background-color', a);
			$("#color_switcher_stylecss") .remove();
			$("<style type='text/css' id='color_switcher_stylecss'>"+cf+"{color:"+a+" !important}"+bc+"{background-color:"+a+" !important}"+bck+"{background:"+a+" !important}"+boc+"{border-color:"+a+" !important}"+boc2+"{border-color:transparent "+a+" !important}</style>").insertAfter("#wrappermain-pix");
			
			jQuery("#colorpickerwrapp span.col-box") .removeClass('active');
			jQuery(this).addClass("active");
		});

	jQuery('#themecolor .bg_color').wpColorPicker({

		change:function(event,ui){
		

			var a = ui.color.toString();
			
			$("#color_switcher_stylecss") .remove();

			$("<style type='text/css' id='color_switcher_stylecss'>"+cf+"{color:"+a+" !important}"+bc+"{background-color:"+a+" !important}"+boc+"{border-color:"+a+" !important}"+boc2+"{border-color:transparent "+a+" !important}</style>").insertAfter("#wrappermain-cs");

			} 

    	}); 

 	});
	
	

	function reset_color(){

		jQuery("#reset_color_txt").attr('value',"1")

		jQuery("#bgcolor").attr('value',"<?php echo $px_theme_option['custom_color_scheme'];?>")

		jQuery("#color_switcher").submit();

	}

        </script>

        <div id="sidebarmain">

            <span id="togglebutton">&nbsp;</span>

            <div id="sidebar">

                <form method="post" id="color_switcher" action="">

                	<aside class="rowside">

      					<header><h4>Layout options</h4></header>
                        <div class="switcher-inn">
                            <h5>Select Color Scheme</h5>
                            <div id="colorpickerwrapp">
                                <?php $px_color_array= array('#45b363','#339a74', '#1d7f5b', '#3fb0c3', '#2293a6', '#137d8f', '#9374ae', '#775b8f', '#dca13a', '#c46d32', '#c44732', '#c44d55', '#425660', '#292f32');
                                foreach($px_color_array as $colors){
                                    $active = '';
                                    if($colors == $px_theme_option['custom_color_scheme']){$active = 'active';}
                                    echo '<span class="col-box '.$active.'" data-color="'.$colors.'" style="background: '.$colors.'"></span>';
                                }
                                ?>
                            </div>
                            <input id="bgcolor" name="style_sheet" type="hidden" class="bg_color" value="<?php echo $_SESSION['kcsess_style_sheet'];?>" />
                        
                            <h5>Choose Your Layout Style</h5>
    
                            <ul class="layoutoption selectradio">
                                <li><label class="label_radio <?php if($_SESSION['kcsess_layout_option']=="wrapper_boxed")echo "active";?> ">
                                <span>Boxed</span>
                                <i class="fa fa-columns"></i><input type="radio" name="layout_option" value="wrapper_boxed" ></label></li>
    							
                                <li><label class="full-view <?php if($_SESSION['kcsess_layout_option']=="wrapper")echo "active";?> ">
                                <span>Full</span>
                                <i class="fa fa-arrows-h"></i><input type="radio" name="layout_option" value="wrapper" ></label></li>
                            </ul>
						</div>
                    </aside>

                    <div class="accordion-sidepanel">

                    <aside class="rowside">

                      <header>  <h4>Pattren Styles</h4></header>

                      <div class="innertext">

                      

                        <div id="pattstyles" class="itemstyles selectradio">
							<span>Patterns are available in boxed mode</span>
                            <ul>

                                <li><label <?php if($_SESSION['kcsess_custome_pattern']=="1")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern1.jpg" alt=""><input type="radio" name="custome_pattern" value="1"></label></li>

                                <li><label <?php if($_SESSION['kcsess_custome_pattern']=="2")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern2.jpg" alt=""><input type="radio" name="custome_pattern" value="2"></label></li>

                                <li><label <?php if($_SESSION['kcsess_custome_pattern']=="3")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern3.jpg" alt=""><input type="radio" name="custome_pattern" value="3"></label></li>

                                <li><label <?php if($_SESSION['kcsess_custome_pattern']=="4")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern4.jpg" alt=""><input type="radio" name="custome_pattern" value="4"></label></li>

                                <li><label <?php if($_SESSION['kcsess_custome_pattern']=="5")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern5.jpg" alt=""><input type="radio" name="custome_pattern" value="5"></label></li>

                                <li><label <?php if($_SESSION['kcsess_custome_pattern']=="6")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern6.jpg" alt=""><input type="radio" name="custome_pattern" value="6"></label></li>

                                <li><label <?php if($_SESSION['kcsess_custome_pattern']=="7")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern7.jpg" alt=""><input type="radio" name="custome_pattern" value="7"></label></li>

                                <li><label <?php if($_SESSION['kcsess_custome_pattern']=="8")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern8.jpg" alt=""><input type="radio" name="custome_pattern" value="8"></label></li>

                                <li><label <?php if($_SESSION['kcsess_custome_pattern']=="9")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern9.jpg" alt=""><input type="radio" name="custome_pattern" value="9"></label></li>

                                <li><label <?php if($_SESSION['kcsess_custome_pattern']=="10")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern10.jpg" alt=""><input type="radio" name="custome_pattern" value="10"></label></li>
                                 <li><label <?php if($_SESSION['kcsess_custome_pattern']=="11")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern11.jpg" alt=""><input type="radio" name="custome_pattern" value="11"></label></li>
                                  <li><label <?php if($_SESSION['kcsess_custome_pattern']=="12")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern12.jpg" alt=""><input type="radio" name="custome_pattern" value="12"></label></li>
                                    <li><label <?php if($_SESSION['kcsess_custome_pattern']=="13")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern13.jpg" alt=""><input type="radio" name="custome_pattern" value="13"></label></li>
                                      <li><label <?php if($_SESSION['kcsess_custome_pattern']=="14")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern14.jpg" alt=""><input type="radio" name="custome_pattern" value="14"></label></li>
                                        <li><label <?php if($_SESSION['kcsess_custome_pattern']=="15")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/pattern/pattern15.jpg" alt=""><input type="radio" name="custome_pattern" value="15"></label></li>

                               

                            </ul>

                        </div>

                        </div>

                    </aside>

                    <aside class="rowside">

                        <header><h4>Background Images</h4></header>

                        <div class="innertext">

                      

                        <div id="backgroundimages" class="selectradio">

                            <ul>

                            	<li><label <?php if($_SESSION['kcsess_bg_img']=="1")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/background/bg1.png" alt=""><input type="radio" name="bg_img" value="1"></label></li>

                                <li><label <?php if($_SESSION['kcsess_bg_img']=="2")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/background/bg2.png" alt=""><input type="radio" name="bg_img" value="2"></label></li>

                                <li><label <?php if($_SESSION['kcsess_bg_img']=="3")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/background/bg3.png" alt=""><input type="radio" name="bg_img" value="3"></label></li>

                                <li><label <?php if($_SESSION['kcsess_bg_img']=="4")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/background/bg4.png" alt=""><input type="radio" name="bg_img" value="4"></label></li>

                                <li><label <?php if($_SESSION['kcsess_bg_img']=="5")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/background/bg5.png" alt=""><input type="radio" name="bg_img" value="5"></label></li>

                                <li><label <?php if($_SESSION['kcsess_bg_img']=="6")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/background/bg6.png" alt=""><input type="radio" name="bg_img" value="6"></label></li>

                                <li><label <?php if($_SESSION['kcsess_bg_img']=="7")echo "class='active'";?> ><img src="<?php echo $theme_path?>/images/background/bg7.png" alt=""><input type="radio" name="bg_img" value="7"></label></li>


                            </ul>

                        </div>

                        </div>

                    </aside>

                    </div>

                	<div class="buttonarea">

                    	<input type="submit" value="Apply" class="btn" />

                        <input type="hidden" name="patter_or_bg" id="patter_or_bg" value="1" />

                        <input type="hidden" name="reset_color_txt" id="reset_color_txt" value="" />

                    	<input type="reset" value="Reset" class="btn" onclick="javascript:reset_color()" />

                    </div>

            </form>

            </div>

        </div>

<?php

	}

}



