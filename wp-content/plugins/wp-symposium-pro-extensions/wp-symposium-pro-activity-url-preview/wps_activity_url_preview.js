jQuery(document).ready(function() {

    jQuery(".wps_remote_remove_preview").click(function (event) {
        
        var item_id = jQuery(this).data('item');
		jQuery('#wps_remote_'+item_id).slideUp('fast');
        
		jQuery.post(
		    wps_activity_url_ajax.ajaxurl,
		    {
		        action : 'wps_activity_url_preview_delete',
		        item_id : item_id
		    },
		    function(response) {
		    }   
		);	

	});    

    jQuery(".wps_remote_remove_image").click(function (event) {
        
        var item_id = jQuery(this).data('item');
        jQuery(this).hide();
        jQuery('#wps_remote_image_'+item_id).animate({ width: 0 }, 200, function() {
            jQuery('#wps_remote_'+item_id).animate({ 'padding-left': 6 }, 100, function() {
                jQuery('#wps_remote_image_'+item_id).css('margin-left', '6px');
                jQuery('#wps_remote_container_meta_'+item_id).css('margin-left', '0px');
                jQuery('#wps_remote_meta_'+item_id).css('margin-left', '0px');
            });
        });
        
		jQuery.post(
		    wps_activity_url_ajax.ajaxurl,
		    {
		        action : 'wps_activity_url_image_delete',
		        item_id : item_id
		    },
		    function(response) {
		    }   
		);	

	});     

});
