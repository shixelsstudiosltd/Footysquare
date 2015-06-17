jQuery(document).ready(function() {

	/* Select upgrade to Select2 */
	if (jQuery("#wps_mail_recipients").length) {
		jQuery("#wps_mail_recipients").select2();
	}

	if (jQuery("#wps_mail_new_recipients").length) {
		jQuery("#wps_mail_new_recipients").select2();
	}

	/* Default form field on page load */
	if (jQuery('.select2-input').length) {
		jQuery('.select2-input').focus();
	} else if (jQuery('#wps_mail_title').length) {
		document.getElementById('wps_mail_title').focus();
	}

    /* Search */
    jQuery("#wps_mail_search").keyup(function(e) {
        if (e.keyCode == 13 && jQuery(this).val()) { 
            var term = jQuery(this).val();
            jQuery(this).attr("disabled", true);
            var d = new Date();
            var n = d.getTime();
            window.location = jQuery(this).data('url')+'n='+n+'&term='+term;
        }
    });
        
    /* Show hidden mail */
    
    jQuery("#wps_mail_show_hidden").click(function (event) {
        jQuery('.wps_mail_post_hidden').slideDown('fast');
        jQuery('#wps_mail_hide_hidden').show();
        jQuery('#wps_mail_show_hidden').hide();
        
        jQuery.post(
            wps_mail_ajax.ajaxurl,
            {
                action : 'wps_mail_show_hidden'
            },
            function(response) {
            }   
        );
        
    });
    
    /* Hide hidden mail */
    
    jQuery("#wps_mail_hide_hidden").click(function (event) {
        jQuery('.wps_mail_post_hidden').slideUp('fast');
        jQuery('#wps_mail_show_hidden').show();
        jQuery('#wps_mail_hide_hidden').hide();

        jQuery.post(
            wps_mail_ajax.ajaxurl,
            {
                action : 'wps_mail_hide_hidden'
            },
            function(response) {
            }   
        );
        
    });
    
    /* Remove mail */

    jQuery(".wps_mail_remove_icon").click(function (event) {

        var id = jQuery(this).attr('rel');
        jQuery('#wps_mail_'+id).slideUp('fast');
        
        jQuery.post(
            wps_mail_ajax.ajaxurl,
            {
                action : 'wps_mail_remove',
                mail_id : id,
            },
            function(response) {
            }   
        );
        
    });

    /* Restore mail */

    jQuery(".wps_mail_restore_icon").click(function (event) {

        var id = jQuery(this).attr('rel');

        // Hide from view (without changing CSS layout)
        jQuery('#wps_mail_'+id+' .wps_mail_restore img').css('opacity', '0');
        jQuery('#wps_mail_'+id+' .wps_mail_restore img').css('filter', 'alpha(opacity=0)');
        jQuery('#wps_mail_'+id+' .wps_mail_restore img').css('-moz-opacity', '0');

        jQuery.post(
            wps_mail_ajax.ajaxurl,
            {
                action : 'wps_mail_restore',
                mail_id : id,
            },
            function(response) {
            }   
        );
        
    });
    
	/* Add Post */

	if (jQuery("#wps_mail_post_close_button").length) {

		jQuery("#wps_mail_post_close_button").click(function (event) {

			event.preventDefault();

			jQuery('.wps_mail_posts').show();
            jQuery('#wps_actions_div').show();
			jQuery('#wps_mail_post_form').hide();
            jQuery('#wps_mail_search').show();
            jQuery('.wps_mail_reset').show();

		})

	}

	if (jQuery("#wps_mail_post_button").length) {

		jQuery('#wps_mail_post_button').prop("disabled", false);

		if (jQuery('#wps_mail_recipients').length) {
			jQuery('#wps_mail_recipients').val('');
			jQuery('#wps_mail_title').val('');
			jQuery('#wps_mail_textarea').val('');
		}
		
		jQuery("#wps_mail_post_button").click(function (event) {

			event.preventDefault();

			if(jQuery('#wps_mail_post_form').css('display') == 'none') {

                jQuery('#wps_mail_search').hide();
                jQuery('.wps_mail_reset').hide();
				jQuery('.wps_mail_posts').hide();
				jQuery('#wps_actions_div').hide();
				jQuery('#wps_mail_post_form').show();

				if (jQuery('#wps_mail_recipients').length) {
					document.getElementById('wps_mail_recipients').focus();
				} else {
                    jQuery('#wps_mail_search').show();
                    jQuery('.wps_mail_reset').show();
					jQuery('.wps_mail_posts').show();
                    jQuery('#wps_actions_div').show();
					jQuery('#wps_mail_post_button').hide();
					jQuery('#wps_mail_post_close_button').hide();
				}

			} else {

				var selected = jQuery('#wps_mail_recipients :selected').length;
				var default_user = jQuery('#wps_default_mail_recipient_user').val();

				if (selected || default_user) {

					if (jQuery('#wps_mail_title').val().length) {

						if (jQuery('#wps_mail_textarea').val().length) {

							jQuery(this).attr("disabled", true);

							jQuery("body").addClass("wps_wait_loading");
					
					        var iframe = jQuery('<iframe name="wps_mail_postiframe" id="wps_mail_postiframe" style="display: none;" />');
					        jQuery("body").append(iframe);

					        var form = jQuery('#wps_mail_post_theuploadform');
					        form.attr("action", jQuery('#wps_mail_plugins_url').val()+"/lib_mail.php");
					        form.attr("method", "post");
					        form.attr("enctype", "multipart/form-data");
					        form.attr("encoding", "multipart/form-data");
					        form.attr("target", "wps_mail_postiframe");
					        form.attr("file", jQuery('#wps_mail_image_upload').val());
					        form.submit();

					        jQuery("#wps_mail_postiframe").load(function () {
					            iframeContents = jQuery("#wps_mail_postiframe")[0].contentWindow.document.body.innerHTML;
								location.reload();
					        });

						} else {

							jQuery('#wps_mail_textarea').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');

						}

					} else {

						jQuery('#wps_mail_title').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');

					}

				} else {

					jQuery('#wps_mail_post_select_recipients_label').slideDown('slow');

				}

			}

		});

	}

	/* Add Comment */
	
	if (jQuery("#wps_mail_comment_button").length) {

		jQuery('#wps_mail_comment_button').prop("disabled", false);
		jQuery('#wps_mail_comment').val('');
		
		jQuery("#wps_mail_comment_button").click(function (event) {

			event.preventDefault();

			if(jQuery('#wps_mail_comment_form').css('display') == 'none') {

				jQuery('#wps_mail_comment_form').show();
				document.getElementById('wps_mail_comment').focus();

			} else {

				if (jQuery('#wps_mail_comment').val().length) {

					jQuery(this).attr("disabled", true);

					jQuery("body").addClass("wps_wait_loading");
			
			        var iframe = jQuery('<iframe name="wps_mail_commentiframe" id="wps_mail_commentiframe" style="display: none;" />');
			        jQuery("body").append(iframe);

			        var form = jQuery('#wps_mail_comment_theuploadform');
			        form.attr("action", jQuery('#wps_mail_plugins_url').val()+"/lib_mail.php");
			        form.attr("method", "post");
			        form.attr("enctype", "multipart/form-data");
			        form.attr("encoding", "multipart/form-data");
			        form.attr("target", "wps_mail_commentiframe");
			        form.attr("file", jQuery('#wps_mail_image_upload').val());
			        form.submit();

			        jQuery("#wps_mail_commentiframe").load(function () {
			            iframeContents = jQuery("#wps_mail_commentiframe")[0].contentWindow.document.body.innerHTML;
						location.reload();
			        });

				} else {

					jQuery('#wps_mail_comment').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');

				}

			}

		});

	}
	
	/* Popup */

	jQuery(".wps_mail_to_user_post_popup").click(function (event) {

		event.preventDefault();
        jQuery("body").addClass("wps_wait_loading");

		var user_id = jQuery(this).attr('rel');

	  	var dialog = jQuery('#wps_mail_to_user_post_popup_div_'+user_id);
	  	var message = jQuery(dialog).children('.wps_mail_popup_message').first();
	  	var recipient = jQuery(dialog).children('.wps_mail_popup_recipient').first();
	  	var button = jQuery(dialog).children('.wps_mail_popup_button').first();

		dialog.css("position","fixed").css("cursor","pointer").css("z-index","100000");
		dialog.css("left", ( (jQuery(window).width()/2) - (dialog.width()/2) )+"px");
		dialog.css("top", ((jQuery(window).height()/2) - (dialog.height()/2))+"px");
        dialog.appendTo("body").show();
	  	jQuery(dialog).children('.wps_mail_popup_message').first().focus();

	});

	// Escape or Enter to hide
	jQuery(document).keyup(function(e) {
	  	if (e.keyCode == 27) { 
			jQuery('.wps_mail_to_user_post_popup_div').hide();
            jQuery('.wps_mail_popup_message').val('');
            jQuery('.wps_mail_popup_message').removeClass('wps_field_error');
            jQuery("body").removeClass("wps_wait_loading");
	   	};
	});
    
    // Cancel
	jQuery(".wps_mail_popup_cancel_button").click(function (event) {

		var id = jQuery(this).attr('rel');
		jQuery(this).parent().children('.wps_mail_popup_message').first().val('');
        jQuery(this).parent().children('.wps_mail_popup_message').first().removeClass('wps_field_error');
        jQuery('#wps_mail_to_user_post_popup_div_'+id).hide();
        jQuery("body").removeClass("wps_wait_loading");

	});

    // Send
	jQuery(".wps_mail_popup_button").click(function (event) {

		if (jQuery(this).parent().children('.wps_mail_popup_message').first().val() != '') {

            var id = jQuery(this).attr('rel');
			var wps_mail_popup_message = jQuery(this).parent().children('.wps_mail_popup_message').first().val();

            jQuery("body").addClass("wps_wait_loading");
            jQuery(this).parent().children('.wps_mail_popup_message').first().val('');
            jQuery('#wps_mail_to_user_post_popup_div_'+id).fadeOut('fast');

			jQuery.post(
			    wps_mail_ajax.ajaxurl,
			    {
			        action : 'wps_mail_popup',
			        recipient : id,
			        message: wps_mail_popup_message
			    },
                function(response) {
                    jQuery("body").removeClass("wps_wait_loading");
			    }   
			);

		} else {

            jQuery(this).parent().children('.wps_mail_popup_message').first().addClass('wps_field_error');
			jQuery(this).parent().children('.wps_mail_popup_message').first().focus();

		}
	});

    // Add new recipient
	jQuery("#wps_add_recipient").click(function (event) {
        jQuery('#wps_mail_new_recipients_div').slideDown('fast');
        jQuery('#wps_mail_new_recipients').select2('open');
    });
    
	jQuery("#wps_mail_new_recipients_submit").click(function (event) {
        event.preventDefault();
        var selected = jQuery('#wps_mail_new_recipients :selected').length;
        if (selected) {
            jQuery("body").addClass("wps_wait_loading");
            jQuery.post(
			    wps_mail_ajax.ajaxurl,
			    {
			        action : 'wps_mail_new_recipients',
                    post_id : jQuery(this).attr('rel'),
			        new_recipients : jQuery('#wps_mail_new_recipients').val(),
			    },
                function(response) {
                    location.reload();
			    }   
			);

        }
    });
    
    // Delete recipient
	jQuery(".wps_mail_delete_recipient").click(function (event) {

        jQuery(this).parent().fadeOut('fast');
        jQuery.post(
            wps_mail_ajax.ajaxurl,
            {
                action : 'wps_mail_delete_recipient',
                post_id : jQuery(this).data('post-id'),
                recipient_login : jQuery(this).attr('rel'),
            },
            function(response) {
            }   
        );

    });

    // Cancel add recipient(s)
	jQuery("#wps_mail_new_recipients_cancel").click(function (event) {
        jQuery('#wps_mail_new_recipients').select2('data', null);
        jQuery('#wps_mail_new_recipients_div').hide();
    });
    
    // Mark all as read
    jQuery("#wps_mail_mark_all_read").click(function (event) {

        jQuery('#wps_mail_mark_all_read').remove();
        jQuery('.wps_mail_post').removeClass('wps_mail_post_unread');
        jQuery.post(
            wps_mail_ajax.ajaxurl,
            {
                action : 'wps_mail_mark_all_read',
            },
            function(response) {
            }   
        );

    }); 
    
    // If flag used, update count after page load
    if (jQuery("#wps_alerts_mail_flag_unread").length) {

        jQuery.post(
            wps_mail_ajax.ajaxurl,
            {
                action : 'wps_alerts_mail_unread',
            },
            function(response) {
                if (response > 0) {
                    jQuery('#wps_alerts_mail_flag_unread').html(response);
                } else {
                    jQuery('#wps_alerts_mail_flag_unread').remove();
                }
            }   
        );

    }; 

    
});
