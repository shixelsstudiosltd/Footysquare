jQuery(document).ready(function() {

	// Manage Subs (unsubscribe from ALL posts)
	jQuery("#wps_manage_subs_post_unsubscribe_all").click(function (event) {
		jQuery('#wps_manage_subs_post_all').slideUp('fast');
		jQuery.post(
		    wps_ajax.ajaxurl,
		    {
				action: 'wps_ajax_subs_unsubscribe_all'
		    },
		    function(response) {
		    }   
		);			
	});

	// Manage Subs (unsubscribe from post)
	jQuery(".wps_manage_subs_post_unsubscribe").click(function (event) {
		jQuery(this).parent().hide();
		jQuery.post(
		    wps_ajax.ajaxurl,
		    {
				action: 'wps_ajax_subs_unsubscribe',
				sub_id: jQuery(this).attr('rel')
		    },
		    function(response) {
		    }   
		);			
	});

	// Manage Subs (unsubscribe from forum)
	jQuery(".wps_manage_subs_forum_unsubscribe").click(function (event) {
		jQuery(this).parent().hide();
		jQuery.post(
		    wps_ajax.ajaxurl,
		    {
				action: 'wps_ajax_subs_forum_unsubscribe',
				sub_id: jQuery(this).attr('rel')
		    },
		    function(response) {
		    }   
		);			
	});
    
    // When viewing all posts
	jQuery(".wps_forum_unsubscribe_icon").click(function (event) {
		jQuery(this).fadeOut('fast');
		jQuery.post(
		    wps_ajax.ajaxurl,
		    {
				action: 'wps_ajax_subs_unsubscribe',
				sub_id: jQuery(this).attr('rel')
		    },
		    function(response) {
		    }   
		);			
	});
    
	// For individual posts
	if (jQuery("#wps_add_subscribe_unsubscribe_button").length) {

		jQuery('#wps_add_subscribe_unsubscribe_button').prop("disabled", false);
		
		jQuery("#wps_add_subscribe_unsubscribe_button").click(function (event) {

			event.preventDefault();

			if (jQuery('#wps_add_subscribe_unsubscribe_action').html() == 'wps_ajax_subs_unsubscribe') {
				jQuery(this).html(jQuery('#wps_unsubscribed_msg').html());
			} else {
				jQuery(this).html(jQuery('#wps_subscribed_msg').html());
			}

			var url = jQuery('#wps_subs_plugins_url').html();

			jQuery.post(
			    wps_ajax.ajaxurl,
			    {
			        action : jQuery('#wps_add_subscribe_unsubscribe_action').html(),
			        post_id: jQuery('#wps_add_subscribe_subscribe_post_id').html(),
					sub_id: jQuery('#wps_add_subscribe_unsubscribe_id').html(),
			    },
			    function(response) {
			    }   
			);	

		});

	}

	// For individual forums
	if (jQuery("#wps_add_forum_subscribe_unsubscribe_button").length) {

		jQuery('#wps_add_forum_subscribe_unsubscribe_button').prop("disabled", false);
		
		jQuery("#wps_add_forum_subscribe_unsubscribe_button").click(function (event) {

			event.preventDefault();

			if (jQuery('#wps_add_forum_subscribe_unsubscribe_action').html() == 'wps_ajax_subs_forum_unsubscribe') {
				jQuery(this).html(jQuery('#wps_forum_unsubscribed_msg').html());
			} else {
				jQuery(this).html(jQuery('#wps_forum_subscribed_msg').html());
			}

			var url = jQuery('#wps_subs_plugins_url').html();

			jQuery.post(
			    wps_ajax.ajaxurl,
			    {
					action: jQuery('#wps_add_forum_subscribe_unsubscribe_action').html(),
					forum_id: jQuery('#wps_add_forum_subscribe_subscribe_forum_id').html(),
					sub_id: jQuery('#wps_add_forum_subscribe_unsubscribe_id').html(),
			    },
			    function(response) {
			    }   
			);	

		});

	}	


})
