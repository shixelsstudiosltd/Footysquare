jQuery(document).ready(function() {

    /* POSTS ------------------------------------------------------- */
    
	// Like
	jQuery('body').on('click', '.wps_like', function() {

        var w = jQuery(this).parent().width();
        if (w<100) { w = 100; }
        jQuery(this).parent().hide().width(w).html(jQuery(this).data('done-text')).fadeIn(300);

        jQuery.post(
            wps_likes_ajax.ajaxurl,
            {
                action : 'wps_like',
                post_id : jQuery(this).attr('rel'),
            },
            function(response) {
                if (response != 'ok') alert(response);
            }   
        );	

    });
    
	// UnLike
	jQuery('body').on('click', '.wps_unlike', function() {

        var w = jQuery(this).parent().width();
        jQuery(this).parent().hide().width(w).html(jQuery(this).data('done-text')).fadeIn(300);

        jQuery.post(
            wps_likes_ajax.ajaxurl,
            {
                action : 'wps_unlike',
                post_id : jQuery(this).attr('rel'),
            },
            function(response) {
                if (response != 'ok') alert(response);
            }   
        );	

    });    

	// DisLike
	jQuery('body').on('click', '.wps_dislike', function() {

        var w = jQuery(this).parent().width();
        jQuery(this).parent().hide().width(w).html(jQuery(this).data('done-text')).fadeIn(300);

        jQuery.post(
            wps_likes_ajax.ajaxurl,
            {
                action : 'wps_dislike',
                post_id : jQuery(this).attr('rel'),
            },
            function(response) {
                if (response != 'ok') alert(response);
            }   
        );	

    });
    
	// UnDisLike
	jQuery('body').on('click', '.wps_undislike', function() {

        var w = jQuery(this).parent().width();
        jQuery(this).parent().hide().width(w).html(jQuery(this).data('done-text')).fadeIn(300);

        jQuery.post(
            wps_likes_ajax.ajaxurl,
            {
                action : 'wps_undislike',
                post_id : jQuery(this).attr('rel'),
            },
            function(response) {
                if (response != 'ok') alert(response);
            }   
        );	

    });    

    /* COMMENTS ------------------------------------------------------- */
    
	// Like
	jQuery('body').on('click', '.wps_like_comment', function() {

        var w = jQuery(this).parent().width();
        if (w<100) { w = 100; }
        jQuery(this).parent().hide().width(w).html(jQuery(this).data('done-text')).fadeIn(300);

        jQuery.post(
            wps_likes_ajax.ajaxurl,
            {
                action : 'wps_like_comment',
                comment_id : jQuery(this).attr('rel'),
            },
            function(response) {
                if (response != 'ok') alert(response);
            }   
        );	

    });
    
	// UnLike
	jQuery('body').on('click', '.wps_unlike_comment', function() {

        var w = jQuery(this).parent().width();
        jQuery(this).parent().hide().width(w).html(jQuery(this).data('done-text')).fadeIn(300);

        jQuery.post(
            wps_likes_ajax.ajaxurl,
            {
                action : 'wps_unlike_comment',
                comment_id : jQuery(this).attr('rel'),
            },
            function(response) {
                if (response != 'ok') alert(response);
            }   
        );	

    });    

	// DisLike
	jQuery('body').on('click', '.wps_dislike_comment', function() {

        var w = jQuery(this).parent().width();
        jQuery(this).parent().hide().width(w).html(jQuery(this).data('done-text')).fadeIn(300);

        jQuery.post(
            wps_likes_ajax.ajaxurl,
            {
                action : 'wps_dislike_comment',
                comment_id : jQuery(this).attr('rel'),
            },
            function(response) {
                if (response != 'ok') alert(response);
            }   
        );	

    });
    
	// UnDisLike
	jQuery('body').on('click', '.wps_undislike_comment', function() {

        var w = jQuery(this).parent().width();
        jQuery(this).parent().hide().width(w).html(jQuery(this).data('done-text')).fadeIn(300);

        jQuery.post(
            wps_likes_ajax.ajaxurl,
            {
                action : 'wps_undislike_comment',
                comment_id : jQuery(this).attr('rel'),
            },
            function(response) {
                if (response != 'ok') alert(response);
            }   
        );	

    });   
    
    /* ADMIN ------------------------------------------------------- */
    
    jQuery('body').on('click', '.wps_remove_like', function() {
                
        jQuery(this).fadeOut('fast');

        jQuery.post(
            wps_likes_ajax.ajaxurl,
            {
                action : 'wps_likes_admin_remove',
                user_id : jQuery(this).data("user-id"),
                post_id : jQuery(this).data("post-id"),
                type : jQuery(this).data("action"),
            },
            function(response) {
                if (response != 'ok') alert(response);
            }   
        );	
        
    });

        
});

