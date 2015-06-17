jQuery(document).ready(function() {

	// Hide if no friends
	if (!jQuery("#wps_mail_recipients").length && jQuery('#wps_mail_image_upload').length) {
		jQuery('#wps_mail_image_upload').hide();
	}

	// For initial post
	if (jQuery('#wps_mail_textarea').length) {
		jQuery('input[type=file]').wpsbootstrapFileInput();
	}

	// For comments
	if (jQuery('#wps_mail_comment').length) {
		jQuery('input[type=file]').wpsbootstrapFileInput();
	}

	jQuery(".wps_mail_item_attachment").click(function (event) {
		wps_show_image(jQuery(this).children('.wps_mail_item_attachment_full'));
	});

	// Click to hide
	jQuery("#wps_mail_attachment_dialog").click(function (event) {
		jQuery(this).hide();
		jQuery("body").removeClass("wps_wait_loading");
	});



});

