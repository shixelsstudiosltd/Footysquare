
//Normal Call Back Functions
jQuery(document).ready(function($) {


jQuery('.tabs ul li').click(function(){
        jQuery('.bmark_active').addClass('bmark');
        jQuery('.bmark_active').removeClass('bmark_active');
        jQuery(this).addClass('bmark_active');
        jQuery(this).removeClass('bmark');
    });

jQuery(".sub-menu").parent("li").addClass("parentIcon");

// Foucs Blur function for input field 
  jQuery(' textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"]').focus(function() {
    if (!$(this).data("DefaultText")) $(this).data("DefaultText", $(this).val());
    if ($(this).val() != "" && $(this).val() == $(this).data("DefaultText")) $(this).val("");
  }).blur(function() {
    if ($(this).val() == "") $(this).val($(this).data("DefaultText"));
  });
  jQuery('a.btngotop').click(function(event) {
    event.preventDefault();
    jQuery('html, body').animate({scrollTop: 0}, 1000);
    return false;
})
	jQuery("a.btnsearch") .click(function(){
		jQuery("#searcharea").slideToggle(200);
		return false;
	})

// JavaScript Toggle function everywhere click close

 jQuery('html').click(function() {
 jQuery("#searcharea").slideUp();
 });
 jQuery(".close-form").click(function(event) {
  jQuery("#searcharea").slideUp();
  return false;
 });
jQuery('a.btnsearch,#searcharea').click(function(event){
     event.stopPropagation();
 });
// JavaScript Toggle function everywhere click close

	/*jQuery(".twitter_sign .flexslider").flexslider({
	animation: "fade",
	prevText: "",
	nextText: "",
	slideshowSpeed: 3000
	});
	
	jQuery('#main .flexslider').flexslider({
    animation: "slide",
      prevText: "<i class='fa fa-angle-left'></i>",
     nextText: "<i class='fa fa-angle-right'></i>",
     slideshowSpeed: 4000

  });*/
  jQuery('[data-toggle="tooltip"]').tooltip()

    
    
});

function px_flexsliderBannerGallery() {
	jQuery(".flexslider").flexslider({
		animation: "fade",
		prevText: " <i class='fa fa-arrow-left'></i>",
		nextText: " <i class='fa fa-arrow-right'></i>"
	});

}

function px_flexsliderGallery() {
	jQuery(".flexslider").flexslider({
		animation: "fade",
		prevText: " <i class='fa fa-arrow-left'></i>",
		nextText: " <i class='fa fa-arrow-right'></i>"
	});
	
}

function BannerGallery() {
	jQuery("#banner .flexslider").flexslider({
		animation: "fade",
		prevText: " <i class='fa fa-arrow-left'></i>",
		nextText: " <i class='fa fa-arrow-right'></i>"
	});
	
	
}

// event countdown
function px_event_countdown(year_event,month_event,date_event,hours,mints,id,trans_days,trans_hours,trans_minutes,trans_seconds){
	"use strict";
	var austDay = new Date();
	austDay = new Date(year_event,month_event-1,date_event,hours,mints);
	jQuery('#defaultCountdown'+id).countdown({until: austDay,
	layout: '<span>{dn} <small>'+trans_days+'</small></span>  <span>{hn} <small>'+trans_hours+'</small></span>  <span>{mn} <small>'+trans_minutes+'</small></span> <span>{sn} <small>'+trans_seconds+'</small></span> '});
}

// Mailchimp widget 
function px_mailchimp_add_scripts () {
	'use strict';
	(function (a) {
	    a.fn.ns_mc_widget = function (b) {
	        var e, c, d;
	        e = {
	            url: "/",
	            cookie_id: false,
	            cookie_value: ""
	        };
	        d = jQuery.extend(e, b);
	        c = a(this);
	        c.submit(function () {
				var mailchimp_submitvalue = jQuery('.widget_newsletter form input[type="submit"]').val();

				var mailchimp_key_validation = jQuery('#mailchimp_key_validation').val();
				
				if( mailchimp_key_validation  != ""){
					//api_key_error = jQuery("<p class='bad_authentication'>" + mailchimp_key_validation + "</p>");
					//c.prepend(api_key);
					alert(mailchimp_key_validation);
					return false;
				} else {
				
	            var f;
	            f = jQuery("<div class='loader_img'><i class='fa fa-spinner fa-spin fa-1x'></i></div>");
	            f.css({
	                "background-position": "center center",
	                "background-repeat": "no-repeat",
	                height: "25px",
	                right: "20px",
					color: "#000",
	                position: "absolute",
	                top: "109px",
	                width: "100px",
	                "z-index": "100"
	            });
	            c.css({
	                height: "100%",
	                position: "relative",
	                width: "100%"
	            });
				//if(jQuery('.widget_newsletter').hasClass('bad_authentication')){
					jQuery('.bad_authentication').remove();
					//i.remove();
				//}
				
					jQuery('.error').remove();
				
	          //  c.children().hide();
	            c.prepend(f);
	            a.getJSON(d.url, c.serialize(), function (h, k) {
					//alert(h+'======'.k);
	                var j, g, i;
	                if ("success" === k) {
	                    if (true === h.success) {
							if(jQuery('.widget_newsletter span').hasClass('bad_authentication')){
								i.remove();
							}
							
	                        i = jQuery("<p class='bad_authentication'>" + h.success_message + "</p>");
	                        i.hide();
							f.remove();
							
							
	                        c.fadeTo(400, 0, function () {
	                            c.prepend(i);
	                            i.show();
	                            c.fadeTo(400, 1)
	                        });
	                        if (false !== d.cookie_id) {
	                            j = new Date();
	                            j.setTime(j.getTime() + "3153600000");
	                            document.cookie = d.cookie_id + "=" + d.cookie_value + "; expires=" + j.toGMTString() + ";"
	                        }
							jQuery('.loader_img').remove();
	                    } else {
							jQuery('.loader_img').remove();
	                        g = jQuery(".error", c);
	                        if (0 === g.length) {
	                            f.remove();
	                            c.children().show();
	                            g = jQuery('<div class="error"></div>');
	                            g.prependTo(c)
	                        } else {
	                            f.remove();
	                            c.children().show()
	                        }
	                        g.html(h.error)
	                    }
	                }
					jQuery('.widget_newsletter input[type="submit"]').val(mailchimp_submitvalue);
	                return false
	            });
				}
	            return false
	        })
	    }
	}(jQuery));
	
	
	

}


jQuery(document).ready(function($) {
  MenuToggle();
  jQuery(window) .resize(function(event) {
    /* Act on the event */
    MenuToggle()
  });
     jQuery("#menus  li.sub-icon > a") .bind("click",function(){
      jQuery(this) .next() .slideToggle(200);
      return false;
     });
       jQuery( ".cs-click-menu" ).click(function() {
        jQuery(this) .next() .slideToggle(200)
      });
});


function MenuToggle() {
   var a = jQuery(window).width();
 var b = 1000
 if (a <= b) {
 jQuery("#menus ul") .parent('li') .addClass('sub-icon');
  jQuery("#menus ul") .hide();
    } else {
        jQuery("#menus ul,#menus") .show();
    }
}

function px_points_table_sorting(id) {
   jQuery(".table"+id).stupidtable();
}