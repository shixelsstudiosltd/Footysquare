jQuery(document).ready(function() {

	// Check for pre IE 10, if so use old upload file
    var undef,
        v = 3,
        new_browser = false,
        div = document.createElement('div'),
        all = div.getElementsByTagName('i');

    while (
        div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->',
        all[0]
    );

    var ie = v > 4 ? v : undef;
	if (ie == 'undefined' || ie == '' || ie == undef) new_browser = true;

	if (new_browser) {

		if (jQuery('#wps_activity_post').length) {
			jQuery('input[type=file]').wpsbootstrapFileInput();
		}

		jQuery('.att_single').remove();

	} else {

		jQuery('.att_multiple').remove();
		jQuery('.att_single').show();

	} 

	/* Delete attachment */
    
	jQuery(".wps_activity_delete_attachment").click(function (event) {
        
        jQuery(this).parent().children('.wps_activity_item_attachment_full').data('width', 0);
		jQuery(this).fadeOut();
		jQuery('#activity_attachment_'+jQuery(this).attr('rel')).fadeOut();
		jQuery.post(
		    wps_attachments_ajax.ajaxurl,
		    {
		        action : 'wps_activity_attachments_delete',
		        attachment_id : jQuery(this).attr('rel')
		    },
		    function(response) {
		    }   
		);	

	});
        
	/* Click on attachment */

	jQuery(".wps_activity_item_attachment").click(function (event) {
        wps_show_image(jQuery(this).children('.wps_activity_item_attachment_full'));
	});

	/* Clear comments on page load */

	jQuery('.wps_activity_post_comment').val('');

	/* Comment (replaces activity comment processing) - jQuery(".wps_activity_post_comment_button").click(function (event) { */
	
	jQuery(".wps_activity_post_comment_att_button").click(function (event) {
        
		var item_id = jQuery(this).attr('rel');

		if (jQuery('#post_comment_'+item_id).val().length) {

			jQuery("body").addClass("wps_wait_loading");

			var iframe = jQuery('<iframe name="wps_comment_postiframe" id="wps_comment_postiframe" style="width:600px;height:200px;border:1px solid red; xdisplay: none;" />');
	        jQuery("body").append(iframe);

	        var form = jQuery('#thecommentuploadform_'+item_id);
	        form.attr("action", wps_attachments_ajax.plugins_url+"/lib_attachments.php");
	        form.attr("method", "post");
	        form.attr("enctype", "multipart/form-data");
	        form.attr("encoding", "multipart/form-data");
	        form.attr("target", "wps_comment_postiframe");
	        form.attr("file", jQuery('#wps_activity_image_upload_'+item_id).val());
	        form.submit();

	        jQuery("#wps_comment_postiframe").load(function () {
	            iframeContents = jQuery("#wps_comment_postiframe")[0].contentWindow.document.body.innerHTML;

				var d = new Date();
				var n = d.getTime();
                
                if (QueryString.group_id == undefined) {
                    if (QueryString.post != undefined) {
                        if (QueryString.user_id != undefined) {
                            window.location = window.location.pathname+'?user_id='+QueryString.user_id+'&n='+n+'#wps_comment_'+item_id;
                        } else {
                            window.location = window.location.pathname+'?n='+n+'#wps_comment_'+item_id;
                        }
                    } else {
                        window.location = window.location.pathname+'?n='+n+'#wps_comment_'+item_id;
                    }
                } else {
                    // Group
                    window.location = window.location.pathname+'?group_id='+QueryString.group_id+'&n='+n+'#wps_comment_'+item_id;
                }
	        });

	    } else {
	    	jQuery('#post_comment_'+item_id).css('border', '1px solid red');
	    	jQuery('#post_comment_'+item_id).css('background-color', '#faa');
	    	jQuery('#post_comment_'+item_id).css('color', '#000');
	    }

        return false;

    });



});


