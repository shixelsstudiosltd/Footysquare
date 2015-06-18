jQuery(document).ready(function() {

	jQuery('.wps_directory_meta_list').each(function(i, obj) {

		var extension_id = jQuery(this).attr('rel');

		jQuery(obj).select2({
		    minimumInputLength: 0,
		    query: function (query) {
				jQuery.post(
				    wps_usermeta.ajaxurl,
				    {
				        action : 'wps_extended_get_meta',
				        term : query.term,
				        id : extension_id,
				        translations: jQuery('#wps_directory_meta_'+extension_id+'_translations').val()
				    },
				    function(response) {
				    	var json = jQuery.parseJSON(response);
				    	var data = {results: []}, i, j, s;
						for(var i = 0; i < json.length; i++) {
					    	var obj = json[i];
					    	data.results.push({id: obj.value, text: obj.label});
						}
						query.callback(data);	    	
				    }   
				);
		    },
		    initSelection: function(element, callback) {
				if (jQuery('#wps_directory_meta_'+extension_id+'_default').length) {
		    	    callback({ text: jQuery('#wps_directory_meta_'+extension_id+'_default').val() });
		    	};
		    },	
		});

	});

	jQuery("#wps_directory_extended_advanced_show_prompt").click(function (event) {

		jQuery(this).hide();
		jQuery('#wps_directory_search_member').hide();
		jQuery('#wps_directory_extended_advanced').show();
		jQuery('#wps_include_meta_show').val('1');

	});

	jQuery("#wps_extension_type").change(function (event) {

        if (jQuery(this).val() == 'url') {
			jQuery('#wps_extension_type_msg').show();
			jQuery('#wps_extension_type_image_msg').hide();
			jQuery('#wps_extension_type_list_msg').hide();
			jQuery('#wps_extension_type_youtube_msg').hide();
		}
		if (jQuery(this).val() == 'list') {
			jQuery('#wps_extension_type_list_msg').show();
			jQuery('#wps_extension_type_msg').hide();
			jQuery('#wps_extension_type_image_msg').hide();
			jQuery('#wps_extension_type_youtube_msg').hide();
		}
		if (jQuery(this).val() == 'image') {
			jQuery('#wps_extension_type_image_msg').show();
			jQuery('#wps_extension_type_msg').hide();
			jQuery('#wps_extension_type_list_msg').hide();
			jQuery('#wps_extension_type_youtube_msg').hide();
		}
		if (jQuery(this).val() == 'youtube') {
			jQuery('#wps_extension_type_youtube_msg').show();
			jQuery('#wps_extension_type_msg').hide();
			jQuery('#wps_extension_type_list_msg').hide();
			jQuery('#wps_extension_type_image_msg').hide();
		}
		if (jQuery(this).val() != 'url' && jQuery(this).val() != 'list' && jQuery(this).val() != 'image' && jQuery(this).val() != 'youtube') {
			jQuery('#wps_extension_type_msg').hide();
			jQuery('#wps_extension_type_image_msg').hide();
			jQuery('#wps_extension_type_list_msg').hide();
			jQuery('#wps_extension_type_youtube_msg').hide();
        }
        
        // Login form (if applicable)
        jQuery('#wps_login_form_options').hide();            
        if (jQuery(this).val() == 'text' || jQuery(this).val() == 'textarea' || jQuery(this).val() == 'list' || jQuery(this).val() == 'divider') {
            jQuery('#wps_login_form_options').show();   
            if (jQuery(this).val() == 'divider') {
                jQuery('#wps_extension_register_mandatory').hide();
            } else {
                jQuery('#wps_extension_register_mandatory').show();
            }
        }
        
	});

	if (jQuery('.wps_extension_date').length) {
		jQuery('.wps_extension_date').datetimepicker({
			format:'Y-m-d',
			timepicker:false,
			closeOnDateSelect:true
		});
	}


});
