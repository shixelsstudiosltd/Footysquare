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

		// For initial post
		if (jQuery('#wps_forum_post_textarea').length) {
			jQuery('#wps_forum_post_image_upload').wpsbootstrapFileInput();
		}

		// For comments
		if (jQuery('#wps_forum_comment').length) {
			jQuery('#wps_forum_comment_image_upload').wpsbootstrapFileInput();
		}

		jQuery('.att_single').remove();

	} else {

		jQuery('.att_multiple').remove();
		jQuery('.att_single').show();

	}

	// Delete attachment
	jQuery(".wps_forum_delete_attachment").click(function (event) {
        
		jQuery(this).parent().children('.wps_forum_item_attachment_full').data('width', 0);
        jQuery(this).fadeOut();
		jQuery('#forum_attachment_'+jQuery(this).attr('rel')).fadeOut();
		jQuery.post(
		    wps_forum_attachments_ajax.ajaxurl,
		    {
		        action : 'wps_forum_attachments_delete',
		        attachment_id : jQuery(this).attr('rel')
		    },
		    function(response) {
		    }   
		);	

	});

	// Show attachment
	jQuery(".wps_forum_item_attachment").click(function (event) {
        wps_show_image(jQuery(this).children('.wps_forum_item_attachment_full'));
	});


});

