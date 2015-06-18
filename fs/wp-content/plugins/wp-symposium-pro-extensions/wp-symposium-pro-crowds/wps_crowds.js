jQuery(document).ready(function() {

	// Close
	jQuery('body').on('click', '#wps_crowd_close', function() {
		jQuery('#manage-crowds').hide();
		location.reload();
	});

	// Cancel
	jQuery('body').on('click', '#wps_crowd_cancel', function() {
		jQuery('#manage-crowds').html('<img style="margin-top:15px;margin-left:15px;" src="'+wps_crowds_ajax.wait+'" />');
		jQuery.post(
		    wps_crowds_ajax.ajaxurl,
		    {
		        action : 'wps_crowds_get_ajax',
		        user_id : wps_crowds_ajax.user_id
		    },
		    function(response) {
		    	jQuery('#manage-crowds').html(response);
		    }   
		);		
	});

	// Click on Create link
	jQuery('body').on('click', '#manage_crowds_create', function() {
		jQuery('#manage-crowds').html('<img style="margin-top:15px;margin-left:15px;" src="'+wps_crowds_ajax.wait+'" />');
		jQuery.post(
		    wps_crowds_ajax.ajaxurl,
		    {
		        action : 'wps_crowds_get_friends',
		        populate : 0,		        
		    },
		    function(response) {
		    	jQuery('#manage-crowds').html(response);
		    	jQuery("#wps_crowd_recipients").select2({ minimumResultsForSearch: 1 });
		    }   
		);
	});

	// Click on Edit link
	jQuery('body').on('click', '.wps_crowds_item_edit', function() {
		var id = jQuery(this).attr('rel');

		jQuery('#manage-crowds').html('<img style="margin-top:15px;margin-left:15px;" src="'+wps_crowds_ajax.wait+'" />');
		jQuery.post(
		    wps_crowds_ajax.ajaxurl,
		    {
		        action : 'wps_crowds_get_friends',
		        populate : id,
		    },
		    function(response) {
		    	jQuery('#manage-crowds').html(response);
		    	jQuery("#wps_crowd_recipients").select2({ minimumResultsForSearch: 1 });
		    }   
		);

	});
	
	// Click on Delete link
	jQuery('body').on('click', '.wps_crowds_item_delete', function() {

		var id = jQuery(this).attr('rel');

		jQuery('#manage-crowds').html('<img style="margin-top:15px;margin-left:15px;" src="'+wps_crowds_ajax.wait+'" />');
		jQuery.post(
		    wps_crowds_ajax.ajaxurl,
		    {
		        action : 'wps_crowds_delete',
		        id : id,
		    },
		    function(response) {

				jQuery.post(
				    wps_crowds_ajax.ajaxurl,
				    {
				        action : 'wps_crowds_get_ajax',
				        user_id : wps_crowds_ajax.user_id
				    },
				    function(response) {
				    	jQuery('#manage-crowds').html(response);
				    }   
				);

		    }   
		);
		

	});
	
	// Do create crowd (button)
	jQuery('body').on('click', '#wps_crowd_create', function() {

		var title = jQuery('#wps_crowd_title').val();
		var recipients = jQuery('#wps_crowd_recipients').val();

		jQuery('#manage-crowds').html('<img style="margin-top:15px;margin-left:15px;" src="'+wps_crowds_ajax.wait+'" />');

		jQuery.post(
		    wps_crowds_ajax.ajaxurl,
		    {
		        action : 'wps_crowds_create',
		        title : title,
		        recipients : recipients,
		    },
		    function(response) {

				jQuery.post(
				    wps_crowds_ajax.ajaxurl,
				    {
				        action : 'wps_crowds_get_ajax',
				        user_id : wps_crowds_ajax.user_id
				    },
				    function(response) {
				    	jQuery('#manage-crowds').html(response);
				    }   
				);

		    }   
		);

	});

	// Do update crowd (button)
	jQuery('body').on('click', '#wps_crowd_update', function() {

		var id = jQuery(this).attr('rel');
		var title = jQuery('#wps_crowd_title').val();
		var recipients = jQuery('#wps_crowd_recipients').val();

		jQuery('#manage-crowds').html('<img style="margin-top:15px;margin-left:15px;" src="'+wps_crowds_ajax.wait+'" />');

		jQuery.post(
		    wps_crowds_ajax.ajaxurl,
		    {
		        action : 'wps_crowds_update',
		        id : id,
		        title : title,
		        recipients : recipients,
		    },
		    function(response) {

				jQuery.post(
				    wps_crowds_ajax.ajaxurl,
				    {
				        action : 'wps_crowds_get_ajax',
				        user_id : wps_crowds_ajax.user_id
				    },
				    function(response) {
				    	jQuery('#manage-crowds').html(response);
				    }   
				);

		    }   
		);

	});


	// Add to Who-To extension
	jQuery('body').on('click', '#wps_crowd_update', function() {
		var option = jQuery('#activity_whoto_select').val();
		if (option.substring(0,6) == 'crowd-') {
	        jQuery('#activity_whoto_select_list').val('').hide();
	    }
	});

	// Select manage crowds...
	jQuery('#activity_whoto_select').change(function(){

		if(jQuery('#activity_whoto_select').val() == 'manage-crowds') {

			jQuery('body').append('<div id="manage-crowds"></div>');
			jQuery('#manage-crowds').css('position', 'fixed');
			jQuery('#manage-crowds').css('border', '1px solid #000');
			jQuery('#manage-crowds').css('background-color', '#fff');
			jQuery('#manage-crowds').css('width', '60%').css('height', '60%');
			jQuery('#manage-crowds').css('top', '20%');
			jQuery('#manage-crowds').css('left', '20%');
			jQuery('#manage-crowds').css('z-index', '900001');
			jQuery('#manage-crowds').css('padding', '8px');

			jQuery('#manage-crowds').html('<img style="margin-top:15px;margin-left:15px;" src="'+wps_crowds_ajax.wait+'" />');

			jQuery.post(
			    wps_crowds_ajax.ajaxurl,
			    {
			        action : 'wps_crowds_get_ajax',
			        user_id : wps_crowds_ajax.user_id
			    },
			    function(response) {
			    	jQuery('#manage-crowds').html(response);
			    }   
			);
	    }

	});

	jQuery('.hastip').tooltipsy({ offset: [0, 5] });

});

