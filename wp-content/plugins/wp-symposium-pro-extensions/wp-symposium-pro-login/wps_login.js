jQuery(document).ready(function() {

    jQuery('.wps_registration_meta_list').each(function(i, obj) {

		var extension_id = jQuery(this).attr('rel');

        jQuery(obj).select2({
		    minimumInputLength: 0,
		    query: function (query) {
				jQuery.post(
				    wps_login_ajax.ajaxurl,
				    {
				        action : 'wps_registration_get_meta',
                        term : query.term,
				        id : extension_id,
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
				if (jQuery('#wps_registration_meta_'+extension_id+'_default').length) {
		    	    callback({ text: jQuery('#wps_registration_meta_'+extension_id+'_default').val() });
		    	};
		    },	
		});

	});

    // Search
	jQuery("#wps_mail_search").click(function (event) {
        
    });
    
	// Registration
	jQuery("#wps_register_submit").click(function (event) {

		if (jQuery('#wps_registration_dummy_field').val() == '') {
		
			jQuery('#wps_register_error').hide();

			// Check for mandatory fields
			var found = false;
			jQuery('.wps_register_mandatory').removeClass('wps_field_error');									
			jQuery('.wps_register_mandatory').each(function(i, obj) {
				if (jQuery(this).val() == '') {
					jQuery(this).addClass('wps_field_error');
					found = true;
				}
			});

			if (found) {
				// Missing mandatory fields
			} else {

				jQuery("body").addClass("wps_wait_loading");
				jQuery("#wps_register_submit").attr("disabled", true);
                
                // Get any profile extensions
                var exts = [];
                jQuery('.wps_registration_value').each(function(i, obj) {
                    var id = jQuery(this).attr('rel');
                    if (id != undefined) {
                        var value = jQuery(this).val();
                        if (value) {
                            var ext = {};
                            ext['key'] = jQuery(this).data('key'),
                            ext['id'] = id;
                            ext['value'] = value;
                            exts.push(ext);
                        }
                    }
                });

                jQuery.post(
		            wps_login_ajax.ajaxurl,
		            {
		                action : 'wps_register_check',
		                username : jQuery('#wps_register_username').val(),
		                email : jQuery('#wps_register_email').val(),
		                first_name : jQuery('#wps_register_firstname').val(),
		                last_name : jQuery('#wps_register_familyname').val(),
		                nickname : jQuery('#wps_register_nickname').val(),
		                display_name : jQuery('#wps_register_display_name').val(),
		                captcha : jQuery('#wps_captcha_form').val(),
		                wpspro_home : jQuery('#wps_register_wpspro_home').val(),
		                wpspro_country : jQuery('#wps_register_wpspro_country').val(),
		                password : jQuery('#wps_register_password').val(),
		                password_confirm : jQuery('#wps_register_password_confirm').val(),
                        registration_url : jQuery('#wps_registration_url').val(),
                        register_auto : jQuery('#register_auto').val(),
                        exts: exts,
		            },
		            function(response) {
						jQuery("#wps_register_submit").attr("disabled", false);
		            	if (response.substr(0, 2) != 'OK' && response.substr(0, 3) != 'URL') {
                            jQuery("body").removeClass("wps_wait_loading");
                            // Error, show message
		                	jQuery('#wps_register_error').removeClass('wps_success').addClass('wps_error');
		            		jQuery('#wps_register_error').html(response).slideDown('fast');
		                } else {
                            if (response.substr(0, 3) == 'URL') {
                                // Success, redirect if need be
                                window.location = response.substring(3);
                            } else {
                                jQuery("body").removeClass("wps_wait_loading");
                                // Success, show message
                                jQuery('#wps_register_username').val('');
                                jQuery('#wps_register_email').val('');
                                jQuery('#wps_register_email2').val('');
                                jQuery('#wps_register_firstname').val('');
                                jQuery('#wps_register_familyname').val('');
                                jQuery('#wps_register_error').removeClass('wps_error').addClass('wps_success');
                                jQuery('.wps_registration_row').hide();
                                jQuery('.wps_registration_item').hide();                            
                                jQuery("#wps_register_submit").hide();
                                jQuery('#wps_register_error').html(response.substring(2)).slideDown('fast');
                            }
		                }
		            }   
		        ); 

			}

		}

	});

	// Enter on password to login
	jQuery("#wps_login_password").keyup(function(e) {
	  	if (e.keyCode == 13) { 
			wps_do_login();
	   	};
	});
	// Click submit to login
	jQuery("#wps_login_submit").click(function (event) {
		wps_do_login();
    });

	function wps_do_login() {
        
		jQuery('#wps_login_error').hide();

		if (jQuery('#wps_login_username').val().length) {

			if (jQuery('#wps_login_password').val().length) {

		        jQuery.post(
		            wps_login_ajax.ajaxurl,
		            {
		                action : 'wps_login_check',
		                username : jQuery('#wps_login_username').val(),
		                password : jQuery('#wps_login_password').val(),
		            },
		            function(response) {
		                if (response) {
		                	document.getElementById('wps_login_username').focus();
							jQuery('#wps_login_error').html(response).slideDown('fast');
		                } else {
		                	window.location = jQuery("#wps_login_submit").attr('rel');
		                }
		            }   
		        ); 

		    } else {

		    	jQuery('#wps_login_password').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');
		    }

	    } else {

	    	jQuery('#wps_login_username').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');
	    }

	}

});
