jQuery(document).ready(function() {

	jQuery(".wps_directory_search_entry").select2({
	    minimumInputLength: 2,
	    allowClear: true,
	    query: function (query) {
			jQuery.post(
			    wps_directory_ajax.ajaxurl,
			    {
			        action : 'wps_get_directory_users',
			        show_user_login : jQuery('#wps_show_user_login').val(),
			        term : query.term
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
			if (jQuery('#wps_directory_search_default').length) {
	    	    callback({ text: jQuery('#wps_directory_search_default').val() });
	    	};
	    	jQuery('#wps_directory_search_entry_spinner').hide();
	    	jQuery('#s2class_wps_directory_search_entry').show();
	    },	    
	});

	jQuery(".wps_directory_search_entry").on("change", function() {
		if (jQuery(this).data("quick-select") == 1) {
			jQuery("body").addClass("wps_wait_loading");
			jQuery.post(
			    wps_directory_ajax.ajaxurl,
			    {
			        action : 'wps_get_directory_users_quick_select',
			        user_login : jQuery(this).val()
			    },
			    function(response) {
			    	window.location = response;
			    }   
			);		
		}
	});


});