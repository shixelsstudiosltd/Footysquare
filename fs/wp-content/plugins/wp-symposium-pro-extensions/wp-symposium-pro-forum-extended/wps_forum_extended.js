jQuery(document).ready(function() {

	jQuery('.wps_forum_extension_list').each(function(i, obj) {

		var extension_id = jQuery(this).attr('rel');

		jQuery(obj).select2({
		    minimumInputLength: 0,
		    width: '300px',
		    query: function (query) {
				jQuery.post(
				    wps_forum_extended.ajaxurl,
				    {
				        action : 'wps_forum_extended_get_meta_ajax',
				        term : query.term,
				        id : extension_id
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
				if (jQuery('#wps_forum_extension_'+extension_id+'_default').length) {
		    	    callback({ text: jQuery('#wps_forum_extension_'+extension_id+'_default').val() });
		    	};
		    },	
		});

	});


});
