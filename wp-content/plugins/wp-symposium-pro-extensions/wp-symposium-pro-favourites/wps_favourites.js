jQuery(document).ready(function() {

	// Toggle favourite
	jQuery(".wps_favourite").click(function (event) {
        
        var state = jQuery(this).data('state');
        var change_to = 'on';
        if (state == '_empty') {
            jQuery(this).attr('src', wps_favourites_ajax.fav_on);
            jQuery(this).data('state', '');
        } else {
            if (state == '') {
                jQuery(this).attr('src', wps_favourites_ajax.fav_off);
                jQuery(this).data('state', '_empty');
            } else {
                // from favourites page (state='fav')
                jQuery(this).parent().parent().parent().slideUp('fast');
            }
            change_to = 'off';
        }

        jQuery.post(
		    wps_favourites_ajax.ajaxurl,
		    {
		        action : 'wps_favourites_toggle',
                change_to : change_to,
		        post_id : jQuery(this).attr('rel')
		    },
		    function(response) {
		    }   
		);	

	});    
    
    // Show delete icon on hover
    jQuery(".wps_favourite_content").hover(function (event) {
        jQuery(this).children('.wps_activity_favourite_icon').show();
        jQuery(this).children('.wps_favourite_link').show();
	});	    
    
    // Hide delete icon when mouse leaves
    jQuery(".wps_favourite_content").mouseleave(function (event) {
        jQuery(".wps_activity_favourite_icon").hide();
        jQuery(".wps_favourite_link").hide();
	});	     

	/* Click on attachment */

	jQuery(".wps_activity_item_attachment").click(function (event) {
        wps_show_image(jQuery(this).children('.wps_activity_item_attachment_full'));
	});
    
});
