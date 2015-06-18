jQuery(document).ready(function() {

	if (jQuery("#wps_system_messages_default_from").length) {

		if (jQuery("#wps_system_messages_default_from").val() == '') {
			jQuery("#wps_system_messages_default_from").select2({
			    minimumInputLength: 1,
			    query: function (query) {
					jQuery.post(
					    wps_ajax.ajaxurl,
					    {
					        action : 'wps_get_users',
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
			    }
			});
		}

	}


})
