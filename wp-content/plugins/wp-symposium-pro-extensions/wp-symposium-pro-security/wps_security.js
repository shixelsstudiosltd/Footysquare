jQuery(document).ready(function() {

	// Upgrade to Select2
	if (jQuery("#wpspro_profile_security").length) {
		jQuery("#wpspro_profile_security").select2({ minimumResultsForSearch: -1, width: '200px' });
	}
	if (jQuery("#wpspro_activity_security").length) {
		jQuery("#wpspro_activity_security").select2({ minimumResultsForSearch: -1, width: '200px' });
	}
	if (jQuery("#wpspro_friends_security").length) {
		jQuery("#wpspro_friends_security").select2({ minimumResultsForSearch: -1, width: '200px' });
	}
	if (jQuery("#wpspro_directory_security").length) {
		jQuery("#wpspro_directory_security").select2({ minimumResultsForSearch: -1, width: '200px' });
	}




});

