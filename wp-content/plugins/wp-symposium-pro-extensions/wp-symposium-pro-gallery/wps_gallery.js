jQuery(document).ready(function() {
    
	// For initial post
	if (jQuery('#wps_gallery_upload').length) {
		jQuery('input[type=file]').wpsbootstrapFileInput();
	}

    // Slideshow
    jQuery('#wps_slideshow').click(function(event) {
        jQuery('#wps_slideshow_hide').show();
        jQuery('#wps_slideshow_div').show();
        jQuery('.wps_slideshow_image').hide();
        jQuery('#wps_slideshow_0').show();
        jQuery('#wps_slideshow').hide();
        jQuery('#wps_gallery_items').hide();
        jQuery('#wps_gallery_upload_form').hide();
        jQuery('#wps_gallery_comments').hide();
        jQuery('.wps_gallery_activity_post_comment_div').hide();
        jQuery('#wps_slideshow_ptr').val('0');
        jQuery('#wps_gallery_attachment_dialog').hide();
        jQuery('.wps_slideshow_pick img').attr('src', wpspro_gallery.slideshow_page);
        jQuery('#wps_slideshow_pick_0').attr('src', wpspro_gallery.slideshow_page_current);
    });
    jQuery('#wps_slideshow_hide').click(function(event) {
        jQuery('#wps_slideshow_hide').hide();
        jQuery('#wps_slideshow_div').hide();
        jQuery('#wps_slideshow_0').hide();
        jQuery('#wps_slideshow').show();
        jQuery('#wps_gallery_items').show();
        jQuery('#wps_gallery_upload_form').show();
        jQuery('#wps_gallery_comments').show();
        jQuery('.wps_gallery_activity_post_comment_div').show();
    });
    jQuery('#wps_slideshow_previous').click(function(event) {
        wps_move_image('previous');
    });
    jQuery('#wps_slideshow_next').click(function(event) {
        wps_move_image('next');
    });
    jQuery('#wps_slideshow_zoom').click(function(event) {
        wps_show_image(jQuery('#wps_slideshow_'+jQuery('#wps_slideshow_ptr').val()+'_zoom'));
    });
    jQuery('.wps_slideshow_image').click(function(event) {
        wps_show_image(jQuery('#wps_slideshow_'+jQuery('#wps_slideshow_ptr').val()+'_zoom'));
    });    
    jQuery('.wps_slideshow_pick').click(function(event) {
        var this_slide = jQuery('#wps_slideshow_ptr').val();
        jQuery('#wps_slideshow_pick_'+this_slide).attr('src', wpspro_gallery.slideshow_page);
        jQuery('#wps_slideshow_'+this_slide).hide();
        jQuery('#wps_slideshow_ptr').val(jQuery(this).attr('rel'));
        jQuery('#wps_slideshow_'+jQuery(this).attr('rel')).show();
        jQuery('#wps_slideshow_pick_'+jQuery(this).attr('rel')).attr('src', wpspro_gallery.slideshow_page_current);
    });
	// Respond to key presses
	jQuery(document).keyup(function(e) {
	  	if (e.keyCode == 39) wps_move_image('next');
	  	if (e.keyCode == 37) wps_move_image('previous');
	});
    
    
    function wps_move_image(direction) {
        
        if (jQuery('#wps_gallery_attachment_dialog').css('display') != 'block') {

            if (direction == 'next') {

                var this_slide = jQuery('#wps_slideshow_ptr').val();
                var max_slides = jQuery('#wps_slideshow_max').val();
                jQuery('#wps_slideshow_'+this_slide).hide();
                jQuery('#wps_slideshow_pick_'+this_slide).attr('src', wpspro_gallery.slideshow_page);
                if (max_slides - this_slide > 0) {
                    this_slide++;
                    jQuery('#wps_slideshow_ptr').val(this_slide);
                    jQuery('#wps_slideshow_'+this_slide).fadeIn('fast');
                } else {
                    this_slide = 0;
                    jQuery('#wps_slideshow_ptr').val(0);
                    jQuery('#wps_slideshow_0').fadeIn('fast');
                }
                jQuery('#wps_slideshow_pick_'+this_slide).attr('src', wpspro_gallery.slideshow_page_current);

            } else {

                var this_slide = jQuery('#wps_slideshow_ptr').val();
                var max_slides = jQuery('#wps_slideshow_max').val();
                jQuery('#wps_slideshow_'+this_slide).hide();
                jQuery('#wps_slideshow_pick_'+this_slide).attr('src', wpspro_gallery.slideshow_page);
                if (this_slide > 0) {
                    this_slide--;
                    jQuery('#wps_slideshow_ptr').val(this_slide);
                    jQuery('#wps_slideshow_'+this_slide).show();
                } else {
                    this_slide = max_slides;
                    jQuery('#wps_slideshow_ptr').val(max_slides);
                    jQuery('#wps_slideshow_'+max_slides).show();
                }
                jQuery('#wps_slideshow_pick_'+this_slide).attr('src', wpspro_gallery.slideshow_page_current);

            }
            
        }
        
    }
    
    // Create album
    jQuery('#wps_gallery_create_button').click(function(event) {

        event.preventDefault();

        if (jQuery('#wps_gallery_create_title').val() != '') {

            jQuery(this).attr("disabled", true);

            jQuery("body").addClass("wps_wait_loading");

            jQuery.post(
                wpspro_gallery.ajaxurl,
                {
                    action : 'wps_gallery_create',
                    wps_create_album : jQuery('#wps_gallery_create_title').val(),
                },
                function(response) {
                    window.location.assign(wpspro_gallery.gallery_page_url_with_query+response);
                }   
            );	                     
        };
    });
    
    // Upload images
	if (jQuery("#wps_gallery_attachment_button").length) {
		jQuery("#wps_gallery_attachment_button").click(function (event) {

            event.preventDefault();

            if (jQuery('#wps_gallery_upload').val() != '') {

                jQuery(this).attr("disabled", true);

                jQuery("body").addClass("wps_wait_loading");

                var iframe = jQuery('<iframe name="wps_gallery_postiframe" id="wps_gallery_postiframe" style="xdisplay: none;" />');
                jQuery("body").append(iframe);

                var form = jQuery('#wps_gallery_theuploadform');
                form.attr("action", wpspro_gallery.plugins_url+"/lib_gallery.php");
                form.attr("method", "post");
                form.attr("enctype", "multipart/form-data");
                form.attr("encoding", "multipart/form-data");
                form.attr("target", "wps_gallery_postiframe");
                form.attr("file", jQuery('#wps_gallery_upload').val());
                form.submit();

                jQuery("#wps_gallery_postiframe").load(function () {
                    //alert(jQuery("#wps_gallery_postiframe").html());
                    location.reload();
                });
                
            };

		});
	}

    // Click to view
    jQuery(".wps_gallery_item_attachment").click(function (event) {
        wps_show_image(jQuery(this).children('.wps_gallery_item_attachment_full'));
	});
    

    
    // Delete
    jQuery('.wps_gallery_delete_attachment').click(function(event) {
        var id = jQuery(this).attr('rel');
        jQuery('#wps_gallery_item_attachment_by_id_'+id).fadeOut();
		jQuery.post(
		    wpspro_gallery.ajaxurl,
		    {
		        action : 'wps_gallery_item_delete',
		        id : id
		    },
		    function(response) {
                if (response) alert(response);
		    }   
		);	        
    });

    // Set Featured Image
    jQuery('.wps_gallery_feature_attachment').click(function(event) {
        var id = jQuery(this).attr('rel');
        // Clear current displayed featured image
        jQuery(".wps_gallery_item_div").removeClass("wps_gallery_featured");
        jQuery(".wps_gallery_item_div").addClass("wps_gallery_not_featured");
        jQuery("#wps_gallery_item_attachment_by_id_"+id).removeClass("wps_gallery_not_featured");
        jQuery("#wps_gallery_item_attachment_by_id_"+id).addClass("wps_gallery_featured");
		jQuery.post(
		    wpspro_gallery.ajaxurl,
		    {
		        action : 'wps_gallery_set_featured',
                post_id : jQuery('#wps_post_id').val(),
		        id : id
		    },
		    function(response) {
                if (response) alert(response);
		    }   
		);	        
    });
    
    // Create new album (shows form)
    jQuery('#wps_gallery_create_gallery_button').click(function(event) {
        jQuery(this).hide();
        jQuery('.wps_gallery_item').hide();
        jQuery('.wps_gallery_owner').hide();
        jQuery('#wps_gallery_create_title').val('');
        jQuery('#wps_gallery_create_div').show();
        jQuery('#wps_gallery_cancel_button').show();
    });
    jQuery('#wps_gallery_cancel_button').click(function(event) {
        jQuery(this).hide();
        jQuery('#wps_gallery_create_gallery_button').show();
        jQuery('.wps_gallery_item').show();
        jQuery('#wps_gallery_create_div').hide();
        jQuery('.wps_gallery_owner').show();
    });
    
    // Edit album
    jQuery('#wps_gallery_edit').click(function(event) {
        jQuery('#wps_gallery_edit_form').show();
        jQuery('.wps_gallery_owner').hide();
        jQuery('#wps_gallery_edit_cancel_button').show();
        jQuery('#wps_gallery_album').hide();
    });
    jQuery('#wps_gallery_edit_cancel_button').click(function(event) {
        jQuery('#wps_gallery_edit_form').hide();
        jQuery('.wps_gallery_owner').show();
        jQuery('#wps_gallery_edit_cancel_button').hide();
        jQuery('#wps_gallery_album').show();
    });
    
    // Delete album
    jQuery('#wps_gallery_delete').click(function(event) {
        var id = jQuery(this).attr('rel');
		jQuery.post(
		    wpspro_gallery.ajaxurl,
		    {
		        action : 'wps_gallery_delete',
		        id : id
		    },
		    function(response) {
                if (response == 'ok') window.location.assign(wpspro_gallery.gallery_page_url);
		    }   
		);	        
    });
    
	// Add activity comment
	jQuery(".wps_gallery_activity_post_comment_button").click(function (event) {
		var id = jQuery(this).attr('rel');		
		var comment = jQuery('#post_comment_'+id).val();
        
		if (comment.length) {

			jQuery('#post_comment_'+id).val('');
			comment_div = '<div style="clear:both; margin-bottom:20px;">';
			comment_div += comment.replace(/\r?\n/g, '<br />').replace(/</g,"&lt;").replace(/>/g,"&gt;");
			comment_div += '</div>';
			jQuery('#wps_gallery_comments').append(comment_div);

			jQuery.post(
			    wpspro_gallery.ajaxurl,
			    {
			        action : 'wps_gallery_activity_comment_add',
			        post_id : id,
			        comment_content: comment
			    },
			    function(response) {
			    }   
			);

		}

	});
    
	// Activity Settings
	jQuery(".wps_gallery_comment_settings").mouseover(function (event) {
		jQuery('.wps_gallery_comment_settings_delete_option').hide();
		jQuery(this).next('.wps_gallery_comment_settings_delete_option').show();
	});
	jQuery(document).mouseup(function (e) {
		jQuery('.wps_gallery_comment_settings_delete_option').hide();
	});

	// Delete comment from settings
	jQuery(".wps_gallery_comment_settings_delete").click(function (event) {
		var id = jQuery(this).attr('rel');
		jQuery('#wps_comment_'+id).fadeOut('slow');
		jQuery.post(
		    wpspro_gallery.ajaxurl,
		    {
		        action : 'wps_gallery_comment_settings_delete',
		        id : id
		    },
		    function(response) {
		    }   
		);

	});	
    
});






