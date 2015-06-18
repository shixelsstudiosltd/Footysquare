jQuery(document).ready(function() {

	if (typeof SC !== 'undefined') {

	    SC.initialize({
	        client_id: wps_soundcloud_var.client_id,
	    });

	    jQuery('.wps_soundcloud_embed').each(function(){
	        var url = jQuery(this).html();
	        jQuery(this).html('SoundCloud trying to load...');
	        SC.oEmbed(url, {auto_play: false, color: "ff0066"},  document.getElementById(jQuery(this).attr('id')));
	        jQuery(this).parent().show();
	    });

	}

});
