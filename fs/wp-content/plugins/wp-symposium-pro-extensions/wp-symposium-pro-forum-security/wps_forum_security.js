jQuery(document).ready(function() {

	jQuery(".wps_show_forum_security").click(function (event) {
		var id = jQuery(this).attr('rel');
		jQuery('.wps_forum_security_'+id).show();
		jQuery(this).hide();
		jQuery('#wps_hide_forum_security_'+id).show();
	});

	jQuery(".wps_hide_forum_security").click(function (event) {
		var id = jQuery(this).attr('rel');
		jQuery('.wps_forum_security_'+id).hide();
		jQuery(this).hide();
		jQuery('#wps_show_forum_security_'+id).show();
	});


});
