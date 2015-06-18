var contheight;
function px_amimate(id){
	var $ = jQuery;
	$("#"+id).animate({
		height: 'toggle'
		}, 1000, function() {
		// Animation complete.
	});
}

	function hide_all(id){
		var $ = jQuery;
		var itemmain=$("#"+id);
		$("#add_page_builder_item > div") .css({"transition":"none","-moz-transition":"none","-webkit-transition":"none","-o-transition":"none","-ms-transition":"none"});
		itemmain.css({"padding":0});
		itemmain.parent('.column') .css({"width":"100%"});
		var showdiv =itemmain.parents(".column"); 
		$(".column,.column-in,.page-builder,.elementhidden") .not(showdiv) .hide();
		itemmain.slideDown(600);
			$('html, body').animate({ scrollTop: itemmain.offset().top - 50}, 600);
		
		

 };
  function addtrack(id){
  var $ = jQuery;
  contheight = $('.page-opts').height();
  //var widthvr = $('.page-opts').outerWidth(true);
  var popd = $("#"+id).height();
  $("#"+id).css("top", popd);
  $("#"+id).css("display", "block");
  $(".poped-up").css("height", popd);
  $(".page-opts").css("height", popd);
  $("#"+id).animate({
   top: 0,
  }, 500, function() {
   // Animation complete.
  });
  //$.scrollTo( '#normal-sortables', 800, {easing:'swing'} );
 };
  function closetrack(id){
  var $ = jQuery;
  $(".page-opts").css("height", "auto");
  //var widthvr = $('.page-opts').outerHeight();
  $("#"+id).animate({
   top: contheight + 100,
  }, 500, function() {
  // Animation complete.
  });
  $("#"+id).hide(500).delay(500);
	//$.scrollTo( '#normal-sortables', 800, {easing:'swing'} );
 };
 
 var counter_track = 0;

 function show_all(id){
   var $ = jQuery;
  var itemmain=$("#"+id);
    itemmain.slideUp(800);
	 setTimeout( function(){
	itemmain.parent('.column').css({"width":""});
	itemmain.css({"padding":""}); 
    },800);
		$(".column-in,.column,.page-builder,.elementhidden") .delay(800) .fadeIn(400,function(){
		
		$("#add_page_builder_item > div") .css({"transition":"width 500ms ease","-moz-transition":"width 500ms ease","-webkit-transition":"width 500ms ease","-o-transition":"width 500ms ease","-ms-transition":"width 500ms ease"}); 
	 });
	


 };
 
 function openpopedup(id){
  var $ = jQuery;
	$(".elementhidden,.opt-head,.option-sec,.to-table thead,.to-table tr")  .hide();
	$("#"+id) .parents("tr") .show();
	$("#"+id) .parents("td") .css("width","100%");
	$("#"+id) .parents("td") .prev() .hide();
	$("#"+id) .parents("td") .find("a.actions") .hide();
	$("#"+id).children(".opt-head") .show();
  $("#"+id).slideDown();
   
  $("#"+id).animate({
   top: 0,
  }, 400, function() {
   // Animation complete.
  });
 // $.scrollTo( '#normal-sortables', 800, {easing:'swing'} );
 };
  function openpopedup_social(id){
  var $ = jQuery;
	jQuery(".add_social_link").hide();
	jQuery(".close_social_link").show();

  $("#"+id).slideDown();
 
 };
 
 function closepopedup(id){
  var $ = jQuery;
  $("#"+id).slideUp(800);

	$(".to-table tr") .css("width","");
	$(".elementhidden,.opt-head,.option-sec,.to-table thead,.to-table tr,a.actions,.to-table tr td").delay(600)  .fadeIn(200);
	
	$.scrollTo( '.elementhidden', 800, {easing:'swing'} );
 };
function closepopedup_social(id){
  var $ = jQuery;
  	jQuery(".add_social_link").show();
	jQuery(".close_social_link").hide();
	  $("#"+id).slideUp(800);

 };
	
	// Update sermon Title
	function update_title(id){
		var val;
 		val = jQuery('#var_pb_pointtable_team'+id).val();
		//jQuery('#var_pb_pointtable_team'+id).html(val);
	}
	
	
	
	// Google map location search
	function gll_search_map(){
		var vals;
		vals = jQuery('#loc_address').val();
		vals = vals + ", " + jQuery('#loc_city').val();
		vals = vals + ", " + jQuery('#loc_postcode').val();
		//vals = vals + ", " + jQuery('#loc_region').val();
		//vals = vals + ", " + jQuery('#loc_country').val();
		jQuery('.gllpSearchField').val(vals);
	}
	// remove image
	function remove_image(id){
		var $ = jQuery;
		$('#'+id).val('');
		$('#'+id+'_img_div').hide();
		$('#'+id+'-preview').hide();
		$('#'+id+'-preview img').attr('src', '');
	}
	
	// slide out 
	function slideout(){
		setTimeout(function(){
			jQuery(".form-msg").slideUp("slow", function () {
			});
		}, 5000);
	}
	// remove div
	function px_div_remove(id){
		jQuery("#"+id).remove();
	}
	// toggle 
	function px_toggle(id){
		jQuery("#"+id).slideToggle("slow");
	}
	// toggle value
	function toggle_with_value(id, value){
		if ( value == 0 ) jQuery("#"+id).hide("slow");
		else  jQuery("#"+id).show("slow");
		
	}
	function px_upcomingfixture_toggle(value, id){
		if ( value == 'list' ){
		 	jQuery("#upcomingfixtures_"+id).show("slow");
		} else {
			jQuery("#upcomingfixtures_"+id).hide("slow");
		}
	}
	function px_team_toggle(value, id){
		if ( value == 'Grid View' ){
		 	jQuery("#department"+id).show("slow");
		} else {
			jQuery("#department"+id).hide("slow");
		}
	}
	function px_event_toggle(value, id){
		if ( value == 'Grid View' ){
		 	jQuery("#var_pb_featured_post"+id).hide("slow");
			jQuery("#var_pb_featuredevent_title"+id).hide("slow");
			jQuery("#var_pb_featured_post"+id).hide("slow");
			jQuery("#var_pb_event_view_all_title"+id).hide("slow");
			jQuery("#var_pb_event_view_all_link"+id).hide("slow");
		 	jQuery("#var_pb_event_filterable"+id).hide("slow");
			
			jQuery("#var_pb_event_category"+id).show("slow");
			jQuery("#var_pb_event_type"+id).show("slow");
			
		} else if ( value == 'Featured Fixute' ){
			jQuery("#var_pb_event_view_all_title"+id).show("slow");
			jQuery("#var_pb_event_view_all_link"+id).show("slow");
			jQuery("#var_pb_featuredevent_title"+id).show("slow");
			jQuery("#var_pb_featured_post"+id).show("slow");
			
		 	jQuery("#var_pb_event_filterable"+id).hide("slow");
			jQuery("#var_pb_event_category"+id).hide("slow");
			jQuery("#var_pb_event_type"+id).hide("slow");
			jQuery("#var_pb_event_pagination"+id).hide("slow");
			jQuery("#px_featured_post"+id).hide("slow");
		} else {
			jQuery("#department"+id).hide("slow");
			jQuery("#var_pb_event_view_all_title"+id).hide("slow");
			jQuery("#var_pb_event_view_all_link"+id).hide("slow");
		}
	}
	// toggle id
	function px_toggle_tog(id){
		jQuery("#"+id).toggle();
	}
	// toggle sidebar	
	function show_sidebar(id){
		var $ = jQuery;
		jQuery('input[name="px_layout"]').change(function(){
			jQuery(this).parent().parent().find(".check-list").removeClass("check-list");
			jQuery(this).siblings("label").children("#check-list").addClass("check-list");
		});
		if ( id == 'left'){
			jQuery("#sidebar_right").hide();
			jQuery("#sidebar_left").show();
		}
		else if ( id == 'right'){
			jQuery("#sidebar_left").hide();
			jQuery("#sidebar_right").show();
		}
		else if ( id == 'both'){
			jQuery("#sidebar_left").show();
			jQuery("#sidebar_right").show();
		}
		else if ( id == 'none'){
			jQuery("#sidebar_left").hide();
			jQuery("#sidebar_right").hide();
		}
	}
	
	// gallery captions
	function px_toggle_gal(id, counter){
		if (id==0){
			jQuery("#link_url"+counter).hide();
			jQuery("#video_code"+counter).hide();
		}
		else if (id==1){
			jQuery("#link_url"+counter).hide();
			jQuery("#video_code"+counter).show();
		}
		else if (id==2){
			jQuery("#link_url"+counter).show();
			jQuery("#video_code"+counter).hide();
		}
	}
	
	// gallery captions
	function px_blognews_toggle(id, counter){
		if (id=='blog-home'){
			jQuery("#news_featuredcat_"+counter).hide();
			jQuery("#news_pagination"+counter).hide();
			jQuery("#news_featured_post"+counter).show();
		}
		else if (id=='blog-carousel'){
			jQuery("#news_featuredcat_"+counter).hide();
			jQuery("#news_pagination"+counter).hide();
			jQuery("#news_featured_post"+counter).hide();
		} else {
			jQuery("#news_featuredcat_"+counter).show();
			jQuery("#news_pagination"+counter).show();
			
		}
		
	}
	
	// gallery captions
	function px_pointstable_toggle(id){
		for(var counter = 4; counter<=7; counter++){
			jQuery("#points-"+counter+"-col").hide();
		}
		jQuery("#points-"+id).show();
		
	}
		// gallery captions
	function px_blog_reviews_toggle(id){
		if (id=='custom'){
			jQuery("#custom-postion").show();
		} else {
			jQuery("#custom-postion").hide();
			
		}
		
	}

	// map views
	function map_contactus_element(id,counter){
		if (id=="contact us"){
			jQuery("#map_contactustext"+counter).show();
		}
		else jQuery("#map_contactustext"+counter).hide();
	}

		// delete page builder item
		var counter = 0;
		function delete_this(id){
				jQuery('#'+id).remove();
				jQuery('#'+id+'_del').remove();
				count_widget--;
				if (count_widget < 1)jQuery("#add_page_builder_item").addClass("hasclass");
				}
			// page builder items array
		var Data = [{ "Class" : "column_100" , "title" : "100" , "element" : ["gallery", "slider", "blog", "news", "event", "album", "review", "recipe", "testimonial", "team", "client", "contact", "column", "divider", "message_box", "image_frame", "map", "video", "quote", "dropcap", "pricetable","services", "tabs", "accordion", "prayer", "advance_search", "parallax", "pointtable"] },
			{ "Class" : "column_75" , "title" : "75" , "element" : ["gallery", "slider", "blog", "news", "event", "album", "review", "recipe", "testimonial", "team", "client", "contact", "column", "divider", "message_box", "image_frame", "map", "video", "quote", "dropcap", "pricetable","services", "tabs", "accordion", "advance_search", "prayer", "pointtable"] },
			{ "Class" : "column_67" , "title" : "67" , "element" : ["gallery", "slider", "blog", "news", "event", "album", "review", "recipe", "testimonial", "team", "client", "contact", "column", "divider", "message_box", "image_frame", "map", "video", "quote", "dropcap", "pricetable","tabs", "accordion", "advance_search", "prayer", "pointtable"] },
			{ "Class" : "column_50" , "title" : "50" , "element" : ["gallery", "slider", "blog", "news", "event", "album", "review", "recipe", "testimonial", "team", "client", "contact", "column", "divider", "message_box", "image_frame", "map", "video", "quote", "dropcap", "pricetable","services", "tabs", "accordion", "prayer", "pointtable"] },
			{ "Class" : "column_33" , "title" : "33" , "element" : ["gallery", "slider", "blog", "news", "event", "album", "team", "contact", "column",  "message_box", "fixtures", "map", "video", "quote", "dropcap", "pricetable","services", "tabs", "accordion","prayer", "pointtable"] },
			{ "Class" : "column_25" , "title" : "25" , "element" : ["column", "divider", "message_box", "fixtures", "map","pricetable","services","pastor","pointtable"] },
		];
		// 
		function decrement(id){
			var $ = jQuery;
			var parent,ColumnIndex,CurrentWidget,CurrentColumn,module;
			parent = $(id).parent('.column-in');
			parent = $(parent).parent('.column');
			CurrentColumn = parseInt($(parent).attr('data'));
			CurrentWidget = $(parent).attr('widget');
			ColumnIndex = parseInt($(parent).attr('data'));
			module = $(parent).attr('item').toString();
			for(i = ColumnIndex + 1; i < Data.length; i++){
				for(c = 0; c <= Data[i].element.length; c++){
					if(Data[i].element[c] == module ){
						$(parent).removeClass(Data[ColumnIndex].Class)
						$(parent).addClass(Data[i].Class)
						$(parent).find('.ClassTitle').text(Data[i].title);
						$(parent).find('.item').val(Data[i].title);
						$(parent).find('.columnClass').val(Data[i].Class)
						$(parent).attr('data',i);
						return false;
					}
				}
			}
		}
        function increment(id){
			var $ = jQuery;
            var parent,ColumnIndex,CurrentWidget,CurrentColumn,module;
			parent = $(id).parent('.column-in');
			parent = $(parent).parent('.column');
            CurrentColumn = parseInt($(parent).attr('data'));
            CurrentWidget = $(parent).attr('widget');
            ColumnIndex = parseInt($(parent).attr('data'));
            module = $(parent).attr('item').toString();
				if(ColumnIndex > 0){
				for(i = ColumnIndex - 1; i < Data.length; i--){//
					for(c = 0; c <= Data[i].element.length; c++){
						if(Data[i].element[c] == module ){
							$(parent).removeClass(Data[ColumnIndex].Class)
							$(parent).addClass(Data[i].Class)
							$(parent).find('.ClassTitle').text(Data[i].title);
							$(parent).find('.item').val(Data[i].title);
							$(parent).attr('data',i);
							return false;
						}
					}
				}
			}
        }

        function px_to_restore_default(admin_url, theme_url){
			//jQuery(".loading_div").show('');
			var var_confirm = confirm("You current theme options will be replaced with the default theme activation options.");
			if ( var_confirm == true ){
				var dataString = 'action=theme_option_restore_default';
				jQuery.ajax({
					type:"POST",
					url: admin_url,
					data: dataString,
					success:function(response){
						jQuery(".form-msg").show();
						jQuery(".form-msg").html(response);
						jQuery(".loading_div").hide();
 						window.location.reload();
						slideout();
					}
				});
			}
            //return false;
		}

        function px_to_backup(admin_url, theme_url){
			//jQuery(".loading_div").show('');
			var var_confirm = confirm("Are you sure! you want to take your current theme option backup?");
			if ( var_confirm == true ){
				var dataString = 'action=theme_option_backup';
				jQuery.ajax({
					type:"POST",
					url: admin_url,
					data: dataString,
					success:function(response){
						parts = response.split("@");
						jQuery("#last_backup_taken").html(parts[1]);
						jQuery(".form-msg").show();
						jQuery(".form-msg").html(parts[0]);
						jQuery(".loading_div").hide();
						window.location.reload();
						slideout();
					}
				});
			}
            //return false;
		}

        function px_to_backup_restore(admin_url, theme_url){
			//jQuery(".loading_div").show('');
			var var_confirm = confirm("Are you sure! you want to replace your current theme options with your last backup?");
			if ( var_confirm == true ){
				var dataString = 'action=theme_option_backup_restore';
				jQuery.ajax({
					type:"POST",
					url: admin_url,
					data: dataString,
					success:function(response){
						jQuery(".form-msg").show();
						jQuery(".form-msg").html(response);
						jQuery(".loading_div").hide();
						window.location.reload();
						slideout();
					}
				});
			}
            //return false;
		}

        function px_to_import_export(admin_url, theme_url){
			//jQuery(".loading_div").show('');
			var var_confirm = confirm("Are you sure! you want to import this theme options?");
			if ( var_confirm == true ){
				var theme_option_data = jQuery("#theme_option_data_import").val();
				var dataString = 'action=theme_option_import_export&theme_option_data='+theme_option_data;
 				jQuery.ajax({
					type:"POST",
					url: admin_url,
					data: dataString,
					success:function(response){
						jQuery(".form-msg").show();
						jQuery(".form-msg").html(response);
						jQuery(".loading_div").hide();
						window.location.reload();
						slideout();
					}
				});
				//return false;
			}
        }
        function theme_option_save(admin_url, theme_url){
			jQuery(".loading_div").show('');
            jQuery.ajax({
                type:"POST",
                url: admin_url,
				data:jQuery('#frm').serialize(), 
                success:function(response){
                    jQuery(".form-msg").show();
                    jQuery(".form-msg").html(response);
                    jQuery(".loading_div").hide();
					//window.location.reload();
                    slideout();
                }
            });
            //return false;
        }

					jQuery("a.add_accordion") .live('click',function(){
						var mainConitem=jQuery(this) .parents(".wrapptabbox");
						var appendtoItem=mainConitem.find(".clone_append") ;
						var newElement =jQuery("<div class='clone_form'> \
								<a href='#' class='deleteit_node'></a> \
								<label>Tab Title:</label> <input class='txtfield' type='text' name='accordion_title[]' /> \
								<label>Tab Text:</label> <textarea class='txtfield' name='accordion_text[]'></textarea> \
								<label>Title Icon:</label> <input class='txtfield' type='text' name='accordion_title_icon[]' /> \
								<label>Active</label> <select name='accordion_active[]'><option>no</option><option>yes</option></select> \
							</div>")
						appendtoItem.append(newElement);
						newElement.focus();
						newElement.hide().fadeIn(300);
						var totalItemCon = mainConitem.find(".clone_form") .size();
						mainConitem.find(".fieldCounter") .val(totalItemCon);
						return false;
					
					});

					jQuery("a.addedtab") .live('click',function(){
						var mainConitem=jQuery(this) .parents(".wrapptabbox");
						var appendtoItem=mainConitem.find(".clone_append") ;
						var newElement =jQuery("<div class='clone_form'> \
								<a href='#' class='deleteit_node'></a> \
								<label>Tab Title:</label> <input class='txtfield' type='text' name='tab_title[]' /> \
								<label>Tab Text:</label> <textarea class='txtfield' name='tab_text[]'></textarea> \
								<label>Title Icon:</label> <input class='txtfield' type='text' name='tab_title_icon[]' /> \
								<label>Active</label> <select name='tab_active[]'><option>no</option><option>yes</option></select> \
							</div>")
						appendtoItem.append(newElement);
						newElement.focus();
						newElement.hide().fadeIn(300);
						var totalItemCon = mainConitem.find(".clone_form") .size();
						mainConitem.find(".fieldCounter") .val(totalItemCon);
						return false;
					
					});

					// services start
					jQuery("a.add_services") .live('click',function(){
						var mainConitem=jQuery(this) .parents(".wrapptabbox");
						var appendtoItem=mainConitem.find(".clone_append") ;
						var newElement =jQuery("<div class='clone_form'> \
								<a href='#' class='deleteit_node'></a> \
								<label>Service Title:</label> <input class='txtfield' type='text' name='service_title[]' /> \
								<label>Service Icon:</label> <input class='txtfield' type='text' name='service_icon[]' /> \
								<label>Service Bg Image:</label> <input class='txtfield' type='text' name='service_bg_image[]' /> \
								<label>Service Link URL:</label> <input class='txtfield' type='text' name='service_link_url[]' /> \
								<label>Service Text:</label> <textarea class='txtfield' name='service_text[]'></textarea> \
							</div>")
						appendtoItem.append(newElement);
						newElement.focus();
						newElement.hide().fadeIn(300);
						var totalItemCon = mainConitem.find(".clone_form") .size();
						mainConitem.find(".fieldCounter") .val(totalItemCon);
						return false;
					
					});
					// services end
					
					// testimonial start
					jQuery("a.added_testimonial") .live('click',function(){
						var mainConitem=jQuery(this) .parents(".wrapptabbox");
						var appendtoItem=mainConitem.find(".clone_append") ;
						var newElement =jQuery("<div class='clone_form'> \
								<a href='#' class='deleteit_node'></a> \
								<label>Title:</label> <input class='txtfield' type='text' name='testimonial_title[]' /> \
								<label>Text:</label> <textarea class='txtfield' name='testimonial_text[]'></textarea> \
								<label>Company:</label> <input class='txtfield' type='text' name='testimonial_company[]' /> \
								<label>Image:</label> <input class='txtfield' type='text' name='testimonial_img[]' /> \
							</div>")
						appendtoItem.append(newElement);
						newElement.focus();
						newElement.hide().fadeIn(300);
						var totalItemCon = mainConitem.find(".clone_form") .size();
						mainConitem.find(".fieldCounter") .val(totalItemCon);
						return false;
					
					});
					// testimonial end

					// team start
					jQuery("a.added_team") .live('click',function(){
						var mainConitem=jQuery(this) .parents(".wrapptabbox");
						var appendtoItem=mainConitem.find(".clone_append") ;
						var newElement =jQuery("<div class='clone_form'> \
								<a href='#' class='deleteit_node'></a> \
								<label>Name:</label> <input class='txtfield' type='text' name='team_name[]' /> \
								<label>Image URL:</label> <input class='txtfield' type='text' name='team_image_url[]' /> \
								<label>Designation:</label> <input class='txtfield' type='text' name='team_designation[]' /> \
								<label>Facebook:</label> <input class='txtfield' type='text' name='team_fb[]' /> \
								<label>Twitter:</label> <input class='txtfield' type='text' name='team_twitter[]' /> \
								<label>LinkedIn:</label> <input class='txtfield' type='text' name='team_in[]' /> \
							</div>")
						appendtoItem.append(newElement);
						newElement.focus();
						newElement.hide().fadeIn(300);
						var totalItemCon = mainConitem.find(".clone_form") .size();
						mainConitem.find(".fieldCounter") .val(totalItemCon);
						return false;
					
					});
					// team end

					// deleting the accordion start
					jQuery("a.deleteit_node") .live('click',function(){
							var mainConitem=jQuery(this) .parents(".wrapptabbox");
							jQuery(this).parent() .append("<div id='confirmOverlay' style='display:block'> \
								<div id='confirmBox'><div id='confirmText'>Are you sure to do this?</div> \
								<div id='confirmButtons'><div class='button confirm-yes'>Delete</div>\
								<div class='button confirm-no'>Cancel</div><br class='clear'></div></div></div>");
								jQuery(this) .parents(".clone_form").addClass("warning");
						jQuery(".confirm-yes").click(function(){
							var totalItemCon = mainConitem.find(".clone_form").size();
							mainConitem.find(".fieldCounter") .val(totalItemCon-1);
							jQuery(this) .parents(".clone_form").fadeOut(400,function(){
									jQuery(this).remove();								
								});
							
							jQuery("#confirmOverlay") .remove();
						});
				
					jQuery(".confirm-no") .click(function(){
						jQuery(".clone_form") .removeClass("warning");
						jQuery("#confirmOverlay") .remove();	
					});
						return false;
					});

					//page Builder items delete start
					jQuery(".btndeleteit") .live("click",function(){
					jQuery(this) .parents(".parentdelete") .addClass("warning");
							jQuery(this).parent() .append("<div id='confirmOverlay' style='display:block'> \
								<div id='confirmBox'><div id='confirmText'>Are you sure to delete?</div> \
								<div id='confirmButtons'><div class='button confirm-yes'>Delete</div>\
								<div class='button confirm-no'>Cancel</div><br class='clear'></div></div></div>");
								
						jQuery(".confirm-yes").click(function(){
							jQuery(this) .parents(".parentdelete").fadeOut(400,function(){
									jQuery(this).remove();		
														
								});
							jQuery("#confirmOverlay") .remove();
							count_widget--;
							var count_widget = jQuery("#add_page_builder_item .column").length;
							if (count_widget == 1) jQuery("#add_page_builder_item").removeClass("hasclass");
						});
					jQuery(".confirm-no") .click(function(){
					jQuery(this) .parents(".parentdelete") .removeClass("warning");	
					jQuery("#confirmOverlay") .remove();	
					});
					return false;
					});
					//page Builder items delete end
					

// media pop-up start
jQuery(document).ready(function(){
var count_widget = jQuery("#add_page_builder_item .column").length;
	if (count_widget > 0) {
		jQuery("#add_page_builder_item").addClass("hasclass");
	}
	jQuery('input[type=file].file') .bind('change focus click',function(){
     var a =jQuery(this).val();
     jQuery(this).next(".fakefile").find("input[type='text']").val(a);
    });
     jQuery(".uploadfile").live('click',function(){
      jQuery(".loadClass").trigger('click');
      jQuery(this).prev().addClass('pathlink');
      setInterval(watchTextbox, 100); 
     });
     function watchTextbox() {
      var txtInput = jQuery('.headerClass');
      var lastValue = jQuery("input[type=text].pathlink") .val();;
      var currentValue = txtInput.val();
      var popup = jQuery('#TB_overlay') .length;
      if(popup == 0){
       jQuery("input.testing") .removeClass('pathlink');
       return false;  
      }
      if(currentValue == 0){
       return false; 
      }
      if (lastValue != currentValue) {
       jQuery("input[type=text].pathlink") .val(currentValue);
      
      if(currentValue != ""){
       jQuery("input.testing") .removeClass('pathlink');
      }
       jQuery('.headerClass').val(''); 
       clearInterval(setInterval(watchTextbox, 100));
      }
     }
    });
// media pop-up end
// layer slider show / hide
function home_slider_toggle(id){
		if ( id == "custom") {
			jQuery("#post_sliders, #flex_sliders").hide();
			jQuery("#custom_slider").show();
		}
		else if ( id == "post_slider") {
			jQuery("#flex_sliders, #custom_slider").hide();
			jQuery("#post_sliders").show();
		}
		else {
			jQuery("#post_sliders, #custom_slider").hide();
			jQuery("#flex_sliders").show();
		}
	}
// related title on/off start
function related_title_toggle_inside_post(id){
	if(id.checked == true){
		jQuery("#related_post").show();
	}
 	else {
		jQuery("#related_post").hide();
	}
}
	// realated title on/off end
	
// Update sermon Title
	function update_pointtable_title(id){
		var val;
		
		if( jQuery('#var_pb_points_table_value1'+id).length )         // use this if you are using id to check
		{
			var value1 = jQuery("#var_pb_points_table_value1"+id).val();
			
			if(value1 == ''){
				jQuery("#var_pb_points_table_value1"+id).val('-');
			} else {
				jQuery('#var_pb_points_table_value1'+id).html(value1);
			}
			
		}
		if( jQuery('#var_pb_points_table_value2'+id).length )         // use this if you are using id to check
		{
			var value2 = jQuery("#var_pb_points_table_value2"+id).val();
			if(value2 == ''){
				jQuery("#var_pb_points_table_value2"+id).val('-');
			}
		}
		
		if( jQuery('#var_pb_points_table_value3'+id).length )         // use this if you are using id to check
		{
			var value3 = jQuery("#var_pb_points_table_value3"+id).val();
			if(value3 == ''){
				jQuery("#var_pb_points_table_value3"+id).val('-');
			}
		}
		
		if( jQuery('#var_pb_points_table_value4'+id).length )         // use this if you are using id to check
		{
			var value3 = jQuery("#var_pb_points_table_value4"+id).val();
			if(value3 == ''){
				jQuery("#var_pb_points_table_value4"+id).val('-');
			}
		}
		
		if( jQuery('#var_pb_points_table_value5'+id).length )         // use this if you are using id to check
		{
			var value5 = jQuery("#var_pb_points_table_value5"+id).val();
			if(value5 == ''){
				jQuery("#var_pb_points_table_value5"+id).val('-');
			}
		}
		
		if( jQuery('#var_pb_points_table_value6'+id).length )         // use this if you are using id to check
		{
			var value6 = jQuery("#var_pb_points_table_value6"+id).val();
			if(value6 == ''){
				jQuery("#var_pb_points_table_value6"+id).val('-');
			}
		}
		
		if( jQuery('#var_pb_points_table_value7'+id).length )         // use this if you are using id to check
		{
			var value7 = jQuery("#var_pb_points_table_value7"+id).val();
			if(value7 == ''){
				jQuery("#var_pb_points_table_value7"+id).val('-');
			}
		}
		
		if( jQuery('#var_pb_points_table_value8'+id).length )         // use this if you are using id to check
		{
			var value8 = jQuery("#var_pb_points_table_value8"+id).val();
			if(value8 == ''){
				jQuery("#var_pb_points_table_value8"+id).val('-');
			}
		}
		
		
		if( jQuery('#var_pb_points_table_value9'+id).length )         // use this if you are using id to check
		{
			var value9 = jQuery("#var_pb_points_table_value9"+id).val();
			if(value9 == ''){
				jQuery("#var_pb_points_table_value9"+id).val('-');
			}
			
		}
		
		var value1 = jQuery("#var_pb_points_table_value1"+id).val();
		jQuery('#var_pb_points_table_value1'+id).html(value1);
		
		//jQuery('#var_pb_pointtable_team'+id).html(val);
	}
	
	var counter_track = 0;
	function add_track_to_list(admin_url, theme_url){
		counter_track++;
		var dataString = 'counter_track=' + counter_track+'&var_pb_points_table_featured=' + jQuery("#var_pb_points_table_featured").val()+'&var_pb_pointtable_tableheads=' + jQuery("#var_pb_pointtable_tableheads").val()+'&action=px_add_pointtable_to_list';
		if( jQuery('#var_pb_points_table_value1').length )         // use this if you are using id to check
		{
			var dataString = dataString+'&var_pb_points_table_value1=' +jQuery("#var_pb_points_table_value1").val();
		}
		if( jQuery('#var_pb_points_table_value2').length )         // use this if you are using id to check
		{
			var dataString = dataString+'&var_pb_points_table_value2=' +jQuery("#var_pb_points_table_value2").val();
		}
		if( jQuery('#var_pb_points_table_value3').length )         // use this if you are using id to check
		{
			var dataString = dataString+'&var_pb_points_table_value3=' +jQuery("#var_pb_points_table_value3").val();
		}
		if( jQuery('#var_pb_points_table_value4').length )         // use this if you are using id to check
		{
			var dataString = dataString+'&var_pb_points_table_value4=' +jQuery("#var_pb_points_table_value4").val();
		}
		if( jQuery('#var_pb_points_table_value5').length )         // use this if you are using id to check
		{
			var dataString = dataString+'&var_pb_points_table_value5=' +jQuery("#var_pb_points_table_value5").val();
		}
		if( jQuery('#var_pb_points_table_value6').length )         // use this if you are using id to check
		{
			var dataString = dataString+'&var_pb_points_table_value6=' +jQuery("#var_pb_points_table_value6").val();
		}
		if( jQuery('#var_pb_points_table_value7').length )         // use this if you are using id to check
		{
			var dataString = dataString+'&var_pb_points_table_value7=' +jQuery("#var_pb_points_table_value7").val();
		}
		if( jQuery('#var_pb_points_table_value8').length )         // use this if you are using id to check
		{
			var dataString = dataString+'&var_pb_points_table_value8=' +jQuery("#var_pb_points_table_value8").val();
		}
		if( jQuery('#var_pb_points_table_value9').length )         // use this if you are using id to check
		{
			var dataString = dataString+'&var_pb_points_table_value9=' +jQuery("#var_pb_points_table_value9").val();
		}
		
		jQuery("#loading").html("<img src='"+theme_url+"/images/admin/ajax_loading.gif' />");
		jQuery.ajax({
			type:"POST",
			url: admin_url,
			data: dataString,
			success:function(response){
				jQuery('.px-sermon-table').show();
				jQuery("#total_tracks").append(response);
				jQuery("#loading").html("");
				closepopedup('add_track');
				jQuery("#var_pb_points_table_value1").val("");
				/*jQuery("#var_pb_match_played").val("");
				jQuery("#var_pb_pointtable_plusminus_points").val("");
				jQuery("#var_pb_pointtable_totalpoints").val("");*/
			}
		});
		//return false;
	}		
	var counter_subject = 0;
	function add_subject_to_list(admin_url, theme_url){
		counter_subject++;
		var dataString = 'counter_subject=' + counter_subject + 
			'&subject_title=' + jQuery("#subject_title_dummy").val() +
			'&subject_instructor=' + jQuery("#subject_instructor").val() +
			'&subject_credit=' + jQuery("#subject_credit").val() + 
			'&subject_detail=' + jQuery("#subject_detail").val() +'&action=add_subject_to_list';
		jQuery("#loading").html("<img src='"+theme_url+"/images/admin/ajax_loading.gif' />");
			jQuery.ajax({
			type:"POST",
			url: admin_url,
			data: dataString,
			success:function(response){
			jQuery("#total_tracks").append(response);
			jQuery("#loading").html("");
			closepopedup('add_track');
			jQuery("#subject_title_dummy").val("Subject Title");
			jQuery("#subject_instructor").val("");
			jQuery("#subject_credit").val("");
			jQuery("#subject_detail").val("");
			}
		});
	   //return false;
	}
	// adding social network start
	function social_icon_del(id){
		jQuery("#del_"+id).remove();
		jQuery("#"+id).remove();
	}

	var counter_social_network = 0;
	function px_add_social_icon(admin_url){
		counter_social_network++;
		jQuery(".add_social_link").hide();
		jQuery(".close_social_link").show();
		var social_net_icon_path = jQuery("#social_net_icon_path_input").val();
		var social_net_awesome = jQuery("#social_net_awesome_input").val();
		var social_net_url = jQuery("#social_net_url_input").val();
		var social_net_tooltip = jQuery("#social_net_tooltip_input").val();
		if ( social_net_url != "" && (social_net_icon_path != "" || social_net_awesome != "" ) ) {
			var dataString = 'social_net_icon_path=' + social_net_icon_path + 
							'&social_net_awesome=' + social_net_awesome +
							'&social_net_url=' + social_net_url +
							'&social_net_tooltip=' + social_net_tooltip +
							'&counter_social_network=' + counter_social_network +
							'&action=add_social_icon';
			//jQuery("#loading").html("<img src='"+theme_url+"/images/admin/ajax_loading.gif' />");
            jQuery.ajax({
                type:"POST",
                url: admin_url,
				data: dataString,
                success:function(response){
					jQuery("#social_network_area").append(response);
					jQuery("#social_net_icon_path_input").val("");
					jQuery("#social_net_awesome_input").val("");
					jQuery("#social_net_url_input").val("");
					jQuery("#social_net_tooltip_input").val("");
					closepopedup_social('add_social_link');
                }
            });
            //return false;
		}
	}
	
var counter_banners = 0;
function px_add_banner_add(admin_url){
	counter_banners++;
	jQuery(".add_banner_link").hide();
	jQuery(".close_banner_link").show();
	var banner_type_input = jQuery("#banner_type_input").val();
	var banner_title_input = jQuery("#banner_title_input").val();
	var banner_image_url = jQuery("#banner_image_url").val();
	var banner_url_input = jQuery("#banner_url_input").val();
	var adsense_input = jQuery("#adsense_input").val();
	if ( banner_image_url != "" || adsense_input != "" ) {
		var dataString = 'banner_type_input=' + banner_type_input + 
						'&banner_title_input=' + banner_title_input +
						'&banner_image_url=' + banner_image_url +
						'&banner_url_input=' + banner_url_input +
						'&adsense_input=' + adsense_input +
						'&counter_banners=' + counter_banners +
						'&action=add_banner_ad';
		//jQuery("#loading").html("<img src='"+theme_url+"/images/admin/ajax_loading.gif' />");
		jQuery.ajax({
			type:"POST",
			url: admin_url,
			data: dataString,
			success:function(response){
				jQuery("#banner_ads_area").append(response);
				jQuery("#banner_type_input").val("");
				jQuery("#banner_title_input").val("");
				jQuery("#banner_image_url").val("");
				jQuery("#banner_url_input").val("");
				jQuery("#adsense_input").val("");
				closepopedup_social('add_banner_link');
			}
		});
		//return false;
	}
}

var counter_pointstable = 0;
function px_add_pointstable_coloumns(admin_url){
	counter_pointstable++;
	jQuery(".add_pointtbale_link").hide();
	jQuery(".close_pointtbale_link").show();
	var pointtbale_counter_id = jQuery("#pointtbale_counter_id").val();
	var points_table_title = jQuery("#points_table_title").val();
	var points_table_coloumn_field_1 = jQuery("#points_table_coloumn_field_1").val();
	var points_table_coloumn_field_2 = jQuery("#points_table_coloumn_field_2").val();
	var points_table_coloumn_field_3 = jQuery("#points_table_coloumn_field_3").val();
	var points_table_coloumn_field_4 = jQuery("#points_table_coloumn_field_4").val();
	var points_table_coloumn_field_6 = jQuery("#points_table_coloumn_field_6").val();
	var points_table_coloumn_field_5 = jQuery("#points_table_coloumn_field_5").val();
	var points_table_coloumn_field_7 = jQuery("#points_table_coloumn_field_6").val();
	var points_table_coloumn_field_8 = jQuery("#points_table_coloumn_field_8").val();
	var points_table_coloumn_field_9 = jQuery("#points_table_coloumn_field_9").val();
	if ( points_table_title != "" || points_table_coloumn_field_1 != "" ) {
		var dataString = 'points_table_title=' + points_table_title + 
						'&points_table_coloumn_field_1=' + points_table_coloumn_field_1 +
						'&points_table_coloumn_field_2=' + points_table_coloumn_field_2 +
						'&points_table_coloumn_field_3=' + points_table_coloumn_field_3 +
						'&points_table_coloumn_field_4=' + points_table_coloumn_field_4 +
						'&points_table_coloumn_field_5=' + points_table_coloumn_field_5 +
						'&points_table_coloumn_field_6=' + points_table_coloumn_field_6 +
						'&points_table_coloumn_field_7=' + points_table_coloumn_field_7 +
						'&points_table_coloumn_field_8=' + points_table_coloumn_field_8 +
						'&points_table_coloumn_field_9=' + points_table_coloumn_field_9 +
						'&counter_pointstable=' + counter_pointstable +
						'&action=add_pointstable';
		//jQuery("#loading").html("<img src='"+theme_url+"/images/admin/ajax_loading.gif' />");
		jQuery.ajax({
			type:"POST",
			url: admin_url+"/admin-ajax.php",
			data: dataString,
			success:function(response){
				jQuery("#pointtable_area").append(response);

				jQuery("#points_table_title").val("");
				jQuery("#points_table_coloumn_field_1").val("");
				jQuery("#points_table_coloumn_field_2").val("");
				jQuery("#points_table_coloumn_field_3").val("");
				jQuery("#points_table_coloumn_field_4").val("");
				jQuery("#points_table_coloumn_field_5").val("");
				jQuery("#points_table_coloumn_field_6").val("");
				jQuery("#points_table_coloumn_field_7").val("");
				jQuery("#points_table_coloumn_field_8").val("");
				jQuery("#points_table_coloumn_field_9").val("");
				closepopedup_social('add_pointtbale_link');
					jQuery(".add_pointtbale_link").show();
					jQuery(".close_pointtbale_link").hide();
			}
		});
		return false;
	} else {
		alert('Please Enter Title');
		return false;
	}
}


function px_pointtable_heads(var_pb_pointtable_tableheads, admin_url){
	//var var_pb_pointtable_tableheads = jQuery("#var_pb_pointtable_tableheads").val();
	if ( var_pb_pointtable_tableheads != "" || var_pb_pointtable_tableheads != "" ) {
		jQuery(".add-points-table").show();
		var dataString = 'var_pb_pointtable_tableheads=' + var_pb_pointtable_tableheads + 
						'&action=px_pointtable_heads';
		jQuery.ajax({
			type:"POST",
			url: admin_url,
			data: dataString,
			success:function(response){
				jQuery("#pointtbale-points-section").val("");
				jQuery("#pointtbale-points-section").html(response);
			}
		});
		
		var dataString = 'var_pb_pointtable_tableheads=' + var_pb_pointtable_tableheads + 
						'&action=px_pointtable_sort_coloumns';
		jQuery.ajax({
			type:"POST",
			url: admin_url,
			data: dataString,
			success:function(responsedata){
				jQuery("#sortablecoloumn").html(responsedata);
			}
		});
		
		
		
		return false;
	}
}




// background options
function px_toggle_bg_options(id){
			
		for ( var i = 1; i <= 5; i++ ) {
			jQuery("#home_v"+i).hide();
		}
		if (id=="no-image"){
			jQuery("#home_v1").show();
			
		} else if (id=="custom-background-image"){
			jQuery("#home_v3").show();
		
		} else if (id=="background_video"){
			jQuery("#home_v2").show();
		
		} else if (id=="background_gallery"){	
			jQuery("#home_v5").show();
			
		} else if (id=="featured-image"){	
			jQuery("#home_v4").hide();
			
		} else if (id=="default-options"){	
			jQuery("#home_v4").hide();
				
		} else {
			jQuery("#home_v4").show();
		}		
}

	function select_bg(){
		var $ = jQuery;
		jQuery('input[name="bg_img"]').change(function(){
			jQuery(this).parent().parent().find(".check-list").removeClass("check-list");
			jQuery(this).siblings("label").children("#check-list").addClass("check-list");
		});
	}
	function select_bg2(){
		var $ = jQuery;
		jQuery('input[name="default_header"]').change(function(){
			jQuery(this).parents(".to-field").find("span").removeClass("check-list");
			jQuery(this).siblings("label").children("#check-list").addClass("check-list");
		});
	}
	function select_pattern(){
		var $ = jQuery;
		jQuery('input[name="pattern_img"]').change(function(){
			jQuery(this).parent().parent().find(".check-list").removeClass("check-list");
			jQuery(this).siblings("label").children("#check-list").addClass("check-list");
		});
	}
	
	var counter_reviews = 0;
	function add_review_to_list(admin_url, theme_url){
		counter_reviews++;
		var dataString = 'counter_reviews=' + counter_reviews + 
		'&var_pb_review_title=' + jQuery("#var_pb_review_title").val() +
		'&var_pb_review_points=' + jQuery("#var_pb_review_points").val() +'&action=px_add_review_to_list';
	
		jQuery("#loading").html("<img src='"+theme_url+"/images/admin/ajax_loading.gif' />");
		jQuery.ajax({
			type:"POST",
			url: admin_url,
			data: dataString,
			success:function(response){
				jQuery('.px-album-table').show();
				jQuery("#total_tracks").append(response);
				jQuery("#loading").html("");
				closepopedup('add_track');
				jQuery("#var_pb_review_title").val("Title");
				jQuery("#var_pb_review_points").val("");
			}
		});
		//return false;
	}

