jQuery(document).ready(function() {

	// For comment image uploads
	if (jQuery('#wps_event_comment_button').length) {
		jQuery('input[type=file]').wpsbootstrapFileInput();
	}

	/* Delete Comment */

	if (jQuery(".wps_calendar_settings_delete").length) {

		jQuery(".wps_calendar_settings_delete").click(function (event) {

			var comment_id = jQuery(this).attr('rel');
			jQuery('#comment_'+comment_id).slideUp('slow');

			jQuery.post(
			    wps_calendar_ajax.ajaxurl,
			    {
			        action : 'wps_calendar_comment_delete',
			        comment_id : comment_id
			    },
			    function(response) {
			    }   
			);

		});

	}

	/* Add Comment */
	
	if (jQuery("#wps_event_comment_button").length) {

		jQuery('#wps_event_comment_button').prop("disabled", false);
		jQuery('#wps_event_comment').val('');
		
		jQuery("#wps_event_comment_button").click(function (event) {

			event.preventDefault();

			if (jQuery('#wps_event_comment').val().length) {

				jQuery(this).attr("disabled", true);

				jQuery("body").addClass("wps_wait_loading");
		
		        var iframe = jQuery('<iframe name="wps_event_commentiframe" id="wps_event_commentiframe" style="display: none;" />');
		        jQuery("body").append(iframe);

		        var form = jQuery('#wps_event_theuploadform');
		        form.attr("action", jQuery('#wps_calendar_plugins_url').val()+"/lib_calendar.php");
		        form.attr("method", "post");
		        form.attr("enctype", "multipart/form-data");
		        form.attr("encoding", "multipart/form-data");
		        form.attr("target", "wps_event_commentiframe");
		        form.submit();

		        jQuery("#wps_event_commentiframe").load(function () {
		            iframeContents = jQuery("#wps_event_commentiframe")[0].contentWindow.document.body.innerHTML;
					location.reload();
		        });

			} else {

				jQuery('#wps_forum_comment').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');

			}


		});

	}

	// Add/Edit calendar event
	if (jQuery("#wps_calendar_post_button").length) {

		jQuery('#wps_calendar_post_button').prop("disabled", false);
		
		jQuery("#wps_calendar_post_button").click(function (event) {

			event.preventDefault();

			if(jQuery('#wps_calendar_post_form').css('display') == 'none') {

				jQuery('#wps_calendar_post_form').show();
				document.getElementById('wps_calendar_title').focus();

			} else {

				if (jQuery('#wps_calendar_title').val().length) {

					if (jQuery('#wps_calendar_post').val().length) {

						if (jQuery('#wps_event_start').val().length) {

							jQuery(this).attr("disabled", true);

							jQuery("body").addClass("wps_wait_loading");

							var url_redirect = jQuery('#calendar_edit_redirect_url').val();
					
					        var iframe = jQuery('<iframe name="wps_calendar_postiframe" id="wps_calendar_postiframe" style="display:none;" />');
					        jQuery("body").append(iframe);

					        var form = jQuery('#wps_event_theuploadform');
					        form.attr("action", jQuery('#wps_calendar_plugins_url').val()+"/lib_calendar.php");
					        form.attr("method", "post");
					        form.attr("enctype", "multipart/form-data");
					        form.attr("encoding", "multipart/form-data");
					        form.attr("target", "wps_calendar_postiframe");
					        form.attr("file", jQuery('#wps_event_image').val());
					        form.submit();

					        jQuery("#wps_calendar_postiframe").load(function () {
					            iframeContents = jQuery("#wps_calendar_postiframe")[0].contentWindow.document.body.innerHTML;
								window.location.assign(url_redirect);
					        });

						} else {

							jQuery('#wps_event_start').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');

						}

					} else {

						jQuery('#wps_calendar_post').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');

					}

				} else {

					jQuery('#wps_calendar_title').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');

				}

			}

		});

	}

	// Attachments
	jQuery(".wps_calendar_item_attachment").click(function (event) {
		wps_show_image(jQuery(this).children('.wps_calendar_item_attachment_full'));
	});

	// Click to hide
	jQuery("#wps_calendar_attachment_dialog").click(function (event) {
		jQuery(this).hide();
		jQuery("body").removeClass("wps_wait_loading");
	});

	if (jQuery("#wps_event_comment").length) {
		jQuery("#wps_event_comment").html('');
	}
	
	if (jQuery("#wps_event_calendar").length) {
		jQuery("#wps_event_calendar").select2();
	}

	jQuery('#wps_event_start').datetimepicker({
		format:'Y-m-d',
		timepicker:false,
		closeOnDateSelect:true
	});

	jQuery('#wps_event_start_time').datetimepicker({
		format:'H:i',
		datepicker:false
	});

	jQuery('#wps_event_end').datetimepicker({
		format:'Y-m-d',
		timepicker:false,
		closeOnDateSelect:true		
	});

	jQuery('#wps_event_end_time').datetimepicker({
		format:'H:i',
		datepicker:false
	});

	/* Calendar Settings */
	
	jQuery(".wps_calendar_settings").mouseover(function (event) {
		jQuery('.wps_calendar_settings_options').hide();
		jQuery(this).next('.wps_calendar_settings_options').show();
	});	

	jQuery(document).mouseup(function (e) {
		jQuery('.wps_calendar_settings_options').hide();
	});

});
