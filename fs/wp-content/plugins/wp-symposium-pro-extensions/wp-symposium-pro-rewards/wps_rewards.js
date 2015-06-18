jQuery(document).ready(function() {

	jQuery("#wps_rewards_type").change(function() {
		if (jQuery(this).val() != 'count') {
			jQuery('#wps_type_action').show();
			jQuery('#wps_type_count').hide();
		} else {
			jQuery('#wps_type_action').hide();
			jQuery('#wps_type_count').show();
		}
	});

});

