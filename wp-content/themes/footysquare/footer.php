                </div>
<!--Footer widget start-->
<?php $advertisingwidgets = wp_get_sidebars_widgets();?>
    <?php  if((isset($advertisingwidgets['footer-advertisement-widget']) && count($advertisingwidgets['footer-advertisement-widget'])>0) || (isset($advertisingwidgets['footer-widget']) && count($advertisingwidgets['footer-widget'])>0)){?>

	<?php  if(isset($advertisingwidgets['footer-advertisement-widget']) && count($advertisingwidgets['footer-advertisement-widget'])>0){?>
                     <div class="footer-advertising-area">
                        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-advertisement-widget')) : ?><?php endif; ?>
                     </div>
             <?php }?>	
<!--Footer widget ends-->

            </div> 
             <?php 
			 	global $px_theme_option;
				echo px_show_partner();
				?>
        </div>

    	<!-- Inner Main -->
    </div>
    <div class="footer-widget">

    <div class="container">
        	
            <div class="footer-icons">
                <?php
                    if(isset($px_theme_option['footer_social_icons']) && $px_theme_option['footer_social_icons'] == 'on'){
                        px_social_network();
                    }
                ?>
            </div>
        </div>
        <?php }?>
        <!-- Container Start -->
        <?php if(isset($advertisingwidgets['footer-widget']) && count($advertisingwidgets['footer-widget'])>0){?>
        <div class="container">
            <!-- Footer Widgets Start -->
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-widget')) : ?><?php endif; ?>
            <!-- Footer Widgets End -->
        </div>
        <?php }?>
        <!-- Container End -->
    	<footer id="footer">
            <div class="container">
                <p class="coptyright">
                    <?php 
					if(!isset($px_theme_option['copyright'])){
						echo '&copy;'.gmdate("Y")." ".get_option("blogname")." Wordpress All rights reserved.";
					} else {
						if(isset($px_theme_option['copyright'])) echo do_shortcode(htmlspecialchars_decode($px_theme_option['copyright']));
						if(isset($px_theme_option['powered_by'])) echo do_shortcode(htmlspecialchars_decode($px_theme_option['powered_by']));
					}
					?>
                </p>
                <!--<a href="" class="btn btngotop"><i class="fa fa-arrow-circle-o-up"></i></a>-->
            </div>
        </footer>
    </div>
</div>
<!-- Wrapper End -->

<?php 
px_footer_settings();
wp_footer();?>


<link rel="stylesheet" href="<?php echo get_site_url(); ?>/wp-content/themes/footysquare/user_autocomplete/css/jquery.atwho.css" />
<script type="text/javascript" src="<?php echo get_site_url(); ?>/wp-content/themes/footysquare/user_autocomplete/js/jquery.caret.js"></script>
<script type="text/javascript" src="<?php echo get_site_url(); ?>/wp-content/themes/footysquare/user_autocomplete/js/jquery.atwho.js"></script>	



<script>
	/* @mention autocomplete*/
	
	$(document).ready(function() {	
		 
		 $(function(){
			var jeremy = decodeURI("J%C3%A9r%C3%A9my")
			var names = [<?php do_shortcode('[auto-complete-titles]');?>];

			var names = $.map(names,function(value,i) {
			  return {'id':i,'name':value,'email':value+"@email.com"};
			});

			var at_config = {
			  at: "@",
			  data: names,
			  tpl: "<li data-value='@${name}'>${name}</li>",
			  limit: 200,
			  show_the_at: true
			}

			$inputor = $('#add_new_post_shoutbox').atwho(at_config).atwho(emoji_config);
			$inputor.caret('pos', 47);
			$inputor.focus().atwho('run');
			$('#add_new_post_shoutbox').atwho(at_config).atwho(emoji_config);	
		  });
		
		//register bio field limit to 160 char and counter of characters
		
		$(".wps_registration_textarea").keyup(function(){
			el = $(this);
			if(el.val().length >= 160){
				el.val( el.val().substr(0, 160) );
			} else {
				$("#reg-chars").text(160-el.val().length);
			}
		});
		
		$('#filter-post').on('change', function() {
			$('#shoutbox-content').html('<div class="loader-shoutbox"></div>');
		  //if value is not filter by then
		  if( this.value!=0 ){
			var current_post_id = $('#current_post_id').val();
		   //$('#filter-form').submit();
		   //var filter_data = $('#filter-form').serialize();
		   //alert(this.value);
		    var filter_opt = this.value;
		    var page = 1;
			var loading = true;
			//alert(filter_opt);
			var $window = $(window);
			var $content = $('#shoutbox-content');
		    //var load_posts = function(){
				$.ajax({
					type       : "POST",
					data       : {numPosts : 5, pageNumber: page},
					dataType   : "html",
					url        : "/fs/wp-content/themes/footysquare/loopHandler.php?pid="+current_post_id+"&filter-post="+filter_opt,
					beforeSend : function(){
						if(page != 1){
							$content.append('<div id="temp_load'+post_type+'" style="text-align:center">\
								<div class="loader-shoutbox "></div>\
								</div>');
						}
					},
					success    : function(data){
						$data = $(data);
						if($data.length){
							$data.hide();
							$content.html($data);
							$data.fadeIn(500, function(){
								$("#temp_load"+post_type).remove();
								loading = false;
							});
						} else {
							$("#temp_load"+post_type).remove();
						}
						$(".loader-shoutbox").fadeOut("slow");
					},
					error     : function(jqXHR, textStatus, errorThrown) {
						//alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
					}
				});
			//}
		  }
		});
		
		$('#filter-post-clubs').on('change', function() {
		  //if value is not filter by then
		  if( this.value!=0 )
		   $('#filter-form-clubs').submit();
		});
		
		$('#filter-post-players').on('change', function() {
		  //if value is not filter by then
		  if( this.value!=0 )
		   $('#filter-form-players').submit();
		});
		
	}); 
	
	//window loader
		$(window).load(function() {
			$(".loader").fadeOut("fast");
		})
		
	//Notification loader
		/*$('#header-activity').load(function() {
			$(".loader-noti").fadeOut("slow");
		})*/

</script>

<script>


$( document ).ready(function() {
  // show comment box
  
  $('body').on('click', '.shout-comment-icon', function() {
	  post_id_value = $(this).attr('id');
	  post_id = '#postid-'+post_id_value;
		
		$(post_id).toggle("fade", function() {
				//$(post_id).css("display","block");
		  });
	});
	
	
	//on click of edit post
	
	$('body').on('click', '.update-front-post', function() {
		post_id_value = $(this).attr('id');
		//$( this ).toggle(function() {
		  post_id_value = $(this).attr('id');
		  post_content = $( "#"+post_id_value ).text();
		  
		  //remove extra char from post
		  $( "#"+post_id_value ).html('');
		  //update post form
		  //$( "#"+post_id_value ).append( "<form method='post' name='front_end_custom_post_update'><textarea class='dynamic-update-post' name='post_content'>"+post_content+"</textarea><input type='hidden' name='postid' value="+post_id_value+" /><input type='hidden' name='action' value='frontend_post_add' /><input type='submit' value='update' class='footy-btn' name='post_update'/><div class='close-update-form' onclick=remove_form("+post_id_value+","+fun_post_content+")>Cancel</button></div></form>");
		  $( "#"+post_id_value ).append( "<form method='post' name='front_end_custom_post_update'><textarea class='dynamic-update-post' name='post_content'>"+post_content+"</textarea><input type='hidden' name='postid' value="+post_id_value+" /><input type='hidden' name='action' value='frontend_post_add' /><input type='submit' value='update' class='footy-btn' name='post_update'/><div class='close-update-form' onclick=remove_form("+post_id_value+")>Cancel</button></div></form>");
		  //});
	  //hide edit button
		$(this).hide();
	});
	
	$("#shout-post-submit").click(function(){
		var serialze = $("#front_end_custom_post").serialize();
		$.ajax({
		  url: "<?php echo get_site_url();?>/wp-content/themes/footysquare/shoutbox.php",
		  type:"POST",
		  data: serialze
		}).done(function(data) {
			  //alert(data);
			  var new_div = $(data).hide();
			  $('#shoutbox-inner').prepend(new_div);
			  $('#add_new_post_shoutbox').val('');
			  
			  //remove upload image file
				//jQueryspan.replaceWith(jQueryfileInput);
				//jQuery("#uploads").remove();
				
				  $("#uploads").remove();
				  $( ".demo1" ).replaceWith("<div id='uploads'></div><input type='file' name='file' id='demo1'>");

			  //ends
			  
			  new_div.slideDown();
			});
	});
		
	});
	
	function remove_form(str){
		//remove edit form
		$("#"+str+" form").remove();
		//show edit button
		$("#post-"+str+" .update-front-post").show();
		$('#'+str).append(post_content);
	}

	
</script>

<div id="login-model">
	<div class="div-section login-section">
		<div class="section-title">
			<i class="fa fa-user"></i><p>signin:</p>
		</div>
	<div class="notification-alert-brown">You must signin to like the post</div>
	<div class="footy-login">
		<?php 
			$args = array(
			'echo'           => true,
			'redirect'       => $_SERVER['REQUEST_URI'], 
			'form_id'        => 'loginform',
			'label_username' => __( 'Username' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in'   => __( 'Log In' ),
			'id_username'    => 'user_login',
			'id_password'    => 'user_pass',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'remember'       => true,
			'value_username' => NULL,
			'value_remember' => false
			);
			wp_login_form( $args );
		?>
	<p>Not on Footysquare? <a href="/fs/login">Register now</a></p>
	</div>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo get_site_url(); ?>/wp-content/themes/footysquare/css/jquery.fancybox.css" media="screen" />
<script type="text/javascript" src="<?php echo get_site_url(); ?>/wp-content/themes/footysquare/scripts/frontend/jquery.fancybox.js"></script>

<script>
	jQuery(document).ready(function() {
		jQuery('.fancybox').fancybox();
	});
</script>

</body>
</html>