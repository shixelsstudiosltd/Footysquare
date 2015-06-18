jQuery(document).ready(function() {

	// Upgrade to Select2
	if (jQuery("#activity_whoto_select").length) {
		jQuery("#activity_whoto_select").select2({ minimumResultsForSearch: -1, width: '200px' });
	}
	if (jQuery("#wps_activity_recipients").length) {
		jQuery("#wps_activity_recipients").select2();
	}
	if(jQuery("#activity_whoto_select").val() == 'select') {
		jQuery('#activity_whoto_select_list').show();
	}

	jQuery('#activity_whoto_select').change(function(){
	    if(jQuery('#activity_whoto_select').val() != 'select'){
	        jQuery('#activity_whoto_select_list').hide();
	        jQuery('#wps_activity_recipients').select2('data', null);
	    }
	    if(jQuery('#activity_whoto_select').val() == 'select'){
	        jQuery('#activity_whoto_select_list').show();
	    }
	});



});

