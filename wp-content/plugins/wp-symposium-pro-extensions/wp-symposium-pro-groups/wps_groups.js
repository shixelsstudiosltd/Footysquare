jQuery(document).ready(function() {

	// Admin
	if (jQuery("#wps_member").length) {

		if (jQuery("#wps_member").val() == '') {
			jQuery("#wps_member").select2({
			    minimumInputLength: 1,
			    query: function (query) {
					jQuery.post(
					    wpspro_groups.ajaxurl,
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

	if (jQuery("#wps_group").length) {

		if (jQuery("#wps_group").val() == '') {

			jQuery("#wps_group").select2({
			    minimumInputLength: 1,
			    query: function (query) {
					jQuery.post(
					    wpspro_groups.ajaxurl,
					    {
					        action : 'wps_ajax_get_groups',
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


	// Create group
	if (jQuery("#wps_group_create_form_button").length) {

		jQuery('#wps_group_create_form_button').prop("disabled", false);
		jQuery('#wps_group_create_title').val('');
		jQuery('#wps_group_create_textarea').val('');
		
		jQuery("#wps_group_create_form_button").click(function (event) {

			event.preventDefault();

			if(jQuery('#wps_group_create_form').css('display') == 'none') {

				jQuery('#wps_group_create_form').show();
				document.getElementById('wps_group_create_title').focus();

			} else {

				if (jQuery('#wps_group_create_title').val().length) {

					if (jQuery('#wps_group_create_textarea').val().length) {

						jQuery(this).attr("disabled", true);

						jQuery("body").addClass("wps_wait_loading");

						jQuery.post(
						    wpspro_groups.ajaxurl,
						    {
						        action : 'wps_ajax_group_create',
						        wps_group_create_title : jQuery('#wps_group_create_title').val(),
						        wps_group_create_textarea : jQuery('#wps_group_create_textarea').val()
						    },
						    function(response) {
						    	window.location.href = wpspro_groups.group_page_url+'group_id='+response;
						    }   
						);							

					} else {

						jQuery('#wps_forum_post_textarea').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');

					}

				} else {

					jQuery('#wps_forum_post_title').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');

				}

			}

		});

	}

	// Delete group
	jQuery("#wps_group_delete").click(function (event) {
		var group_id = jQuery(this).attr('rel');

        if (confirm(wpspro_groups.areyousure)) {
            
            jQuery.post(
                wpspro_groups.ajaxurl,
                {
                    action : 'wps_ajax_group_delete',
                    group_id : group_id
                },
                function(response) {
                    window.location.href = wpspro_groups.profile_page_url;
                }   
            );	
            
        };


	});

	// Join group
	jQuery("#wps_group_join").click(function (event) {

		var group_id = jQuery(this).attr('rel');

		jQuery("body").addClass("wps_wait_loading");

		jQuery.post(
		    wpspro_groups.ajaxurl,
		    {
		        action : 'wps_ajax_group_join',
		        group_id : group_id
		    },
		    function(response) {
		    	location.reload();
		    }   
		);		

	});

	// Cancel request
	jQuery("#wps_group_cancel").click(function (event) {

		var group_id = jQuery(this).attr('rel');

		jQuery("body").addClass("wps_wait_loading");

		jQuery.post(
		    wpspro_groups.ajaxurl,
		    {
		        action : 'wps_ajax_group_cancel',
		        group_id : group_id
		    },
		    function(response) {
		    	location.reload();
		    }   
		);		

	});

	// Leave group
	jQuery("#wps_group_leave").click(function (event) {

		var group_id = jQuery(this).attr('rel');	
			
		jQuery("body").addClass("wps_wait_loading");

		jQuery.post(
		    wpspro_groups.ajaxurl,
		    {
		        action : 'wps_ajax_group_leave',
		        group_id : group_id
		    },
		    function(response) {
		    	location.reload();
		    }   
		);		

	});

	// Kick from group
	jQuery(".wps_group_join_kick").click(function (event) {

		var group_id = jQuery(this).attr('rel');
		var member_id = jQuery(this).data("member-id");
			
		jQuery("body").addClass("wps_wait_loading");

		jQuery.post(
		    wpspro_groups.ajaxurl,
		    {
		        action : 'wps_ajax_group_kick',
		        group_id : group_id,
		        member_id : member_id
		    },
		    function(response) {
		    	location.reload();
		    }   
		);		

	});

	// Accept request to join
	jQuery(".wps_group_join_accept").click(function (event) {

		var group_id = jQuery(this).attr('rel');
		var member_id = jQuery(this).data("member-id");

		jQuery("body").addClass("wps_wait_loading");

		jQuery.post(
		    wpspro_groups.ajaxurl,
		    {
		        action : 'wps_ajax_group_accept',
		        group_id : group_id,
		        member_id : member_id
		    },
		    function(response) {
		    	location.reload();
		    }   
		);		

	});

	// Reject request to join
	jQuery(".wps_group_join_reject").click(function (event) {

		var group_id = jQuery(this).attr('rel');
		var member_id = jQuery(this).data("member-id");

		jQuery("body").addClass("wps_wait_loading");

		jQuery.post(
		    wpspro_groups.ajaxurl,
		    {
		        action : 'wps_ajax_group_reject',
		        group_id : group_id,
		        member_id : member_id
		    },
		    function(response) {
		    	location.reload();
		    }   
		);		

	});	


});




