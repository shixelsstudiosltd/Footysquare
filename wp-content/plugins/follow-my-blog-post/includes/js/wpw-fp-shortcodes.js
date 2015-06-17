// JavaScript Document
jQuery(document).ready(function($) {
	var wpwfped;
	var wpwfpurl;
	//add the button to tinymce editor
	(function() {
		tinymce.create('tinymce.plugins.wpw_fp_follow_post', {
			
			init : function(ed, url) {
				
				ed.addButton('wpw_fp_follow_post', {
					title : 'Follow',
					image : url+'/shortcode-icon.png',
					onclick : function() {
						
						$( '.wpw-fp-popup-overlay' ).fadeIn();
						$( '.wpw-fp-popup-content' ).fadeIn();
						
						var popupcontent = $( '.wpw-fp-popup-content' );
						popupcontent.fadeIn();
						$( '.wpw-fp-popup-overlay' ).fadeIn();
						$('html, body').animate({ scrollTop: popupcontent.offset().top - 80 }, 500);
						
						$( '#wpw_fp_insert_container' ).hide();
						
						$( '#wpw_fp_post_options' ).hide(); 
						$( '#wpw_fp_term_options' ).hide();
						$( '#wpw_fp_author_options' ).hide();
						
						// Post / Page Follow Button
						$( '#wpw_fp_post_id' ).val( '' );
						$( '#wpw_fp_disable_followers_count' ).attr( 'checked', false );
						$( '.wpw-fp-disable-count-msg-wrap' ).show();
						$( '#wpw_fp_followers_count_msg' ).val( '' );
						$( '#wpw_fp_follow_text' ).val( '' );
						$( '#wpw_fp_following_text' ).val( '' );
						$( '#wpw_fp_unfollow_text' ).val( '' );
						
						// Category / Tags Follow button
						$( '#wpw_fp_term_taxonomy' ).val( '' ).trigger( 'chosen:updated' );
						$( '#wpw_fp_term_term_id' ).html( '' ).trigger( 'chosen:updated' );
						$( '.wpw-fp-term-slug-tr' ).hide();
						$( '#wpw_fp_term_disable_followers_count' ).attr( 'checked', false );
						$( '.wpw-fp-term-disable-count-msg-wrap' ).show();
						$( '#wpw_fp_term_followers_count_msg' ).val( '' );
						$( '#wpw_fp_term_follow_text' ).val( '' );
						$( '#wpw_fp_term_following_text' ).val( '' );
						$( '#wpw_fp_term_unfollow_text' ).val( '' );
						
						 // Author Follow button
						$( '#wpw_fp_author_nm' ).val( '' );
						$( '#wpw_fp_author_disable_followers_count' ).attr( 'checked', false );
						$( '.wpw-fp-author-disable-count-msg-wrap' ).show();
						$( '#wpw_fp_author_followers_count_msg' ).val( '' );
						$( '#wpw_fp_author_follow_text' ).val( '' );
						$( '#wpw_fp_author_following_text' ).val( '' );
						$( '#wpw_fp_author_unfollow_text' ).val( '' );
						
						$( '#wpw_fp_shortcodes' ).val( '' ).trigger( 'chosen:updated' );
					}
				});
			},
			createControl : function(n, cm) {
				return null;
			},
		});
		
		tinymce.PluginManager.add('wpw_fp_follow_post', tinymce.plugins.wpw_fp_follow_post);
		
	})();
	
	//close popup window
	$( document ).on( "click", ".wpw-fp-close-button, .wpw-fp-popup-overlay", function() {
		
		$( '.wpw-fp-popup-overlay' ).fadeOut();
		$( '.wpw-fp-popup-content' ).fadeOut();
		
	});
	
	$( document ).on( "click", "#wpw_fp_disable_followers_count", function() {
		
		$( '.wpw-fp-disable-count-msg-wrap' ).show();
		if( $( this ).is(":checked") ) {
			$( '.wpw-fp-disable-count-msg-wrap' ).hide();
		}
	});
	
	$( document ).on( "click", "#wpw_fp_term_disable_followers_count", function() {
		$( '.wpw-fp-term-disable-count-msg-wrap' ).show();
		if( $( this ).is(":checked") ) {
			$( '.wpw-fp-term-disable-count-msg-wrap' ).hide();
		}
	});
	
	//show insert shortcode buttons
	$( document ).on( "change", "#wpw_fp_shortcodes", function() {
		
		var select_shortcode = $( this ).val();
		
		if( select_shortcode != '') {
			$( '#wpw_fp_insert_container' ).show();
			$( '.wpw-fp-shortcodes-options').hide();
			
			switch ( select_shortcode ) {
				
				case 'wpw_follow_me'	:
						
	                    $( '#wpw_fp_post_id' ).val( '' );
	                    $( '#wpw_fp_followers_count_msg' ).val( '' );
						
						$( '#wpw_fp_post_options' ).show();
						break;
				case 'wpw_follow_term_me'	:
						
	                    $( '.wpw-fp-term-slug-tr' ).hide();
	                    
	                    $( '#wpw_fp_term_taxonomy' ).val( '' ).trigger( 'chosen:updated' );
	                    $( '#wpw_fp_term_term_id' ).html( '' ).trigger( 'chosen:updated' );
	                    $( '#wpw_fp_term_followers_count_msg' ).val( '' );
	                    
						$( '#wpw_fp_term_options' ).show();
						break;
						
				case 'wpw_follow_author_me'	:
						$( '#wpw_fp_author_nm' ).val( '' );
	                    $( '#wpw_fp_term_taxonomy' ).val( '' ).trigger( 'chosen:updated' );
						$( '#wpw_fp_author_options' ).show();
						break;
						
			}
		
		} else {
			
			$( '#wpw_fp_insert_container' ).hide();
			$( '.wpw-fp-shortcodes-options').hide();
		}
	});
	
	$( document ).on( "click", "#wpw_fp_insert_shortcode", function() {
		
		var wpwfpshortcode = $('#wpw_fp_shortcodes').val();
		var wpwfpshortcodestr = '';
			
			if(wpwfpshortcode != '') {
				
				wpwFpSwitchDefaultEditorVisual();
				
				switch(wpwfpshortcode) {
					
					case 'wpw_follow_me'	:
					
								var post_id 			=	$( '#wpw_fp_post_id' ).val();
								var disablecountbox		=	$( '#wpw_fp_disable_followers_count');
								var countermsg			=	$( '#wpw_fp_followers_count_msg').val();
								var followtext			=	$( '#wpw_fp_follow_text').val(); 
								var followingtext		=	$( '#wpw_fp_following_text').val(); 
								var unfollowtext		=	$( '#wpw_fp_unfollow_text').val();
								/*
								var followtext 			= followtext.replace( /\"/g, wpwFpHtmlEntity( '"' ) );
								var followingtext 		= followingtext.replace( /\"/g, wpwFpHtmlEntity( '"' ) );
								var unfollowtext 		= unfollowtext.replace( /\"/g, wpwFpHtmlEntity( '"' ) );
								*/
								wpwfpshortcodestr	+= '['+wpwfpshortcode;
								if(post_id != '') {
									wpwfpshortcodestr	+= ' id="'+post_id+'"';
								}
								if( disablecountbox.is(":checked") ) {
									wpwfpshortcodestr	+= ' disablecount="true"';
								}
								if( countermsg != '' ) {
									wpwfpshortcodestr	+= ' followerscountmsg="'+countermsg+'"';
								}
								if(followtext != '') {
									wpwfpshortcodestr	+= ' followtext="'+followtext+'"';
								}
								if(followingtext != '') {
									wpwfpshortcodestr	+= ' followingtext="'+followingtext+'"';
								}
								if(unfollowtext != '') {
									wpwfpshortcodestr	+= ' unfollowtext="'+unfollowtext+'"';
								}
								wpwfpshortcodestr	+= '][/'+wpwfpshortcode+']';
								break;
					
					case 'wpw_follow_term_me'	:
					
								var posttype 			=	$( '#wpw_fp_term_taxonomy option:selected' ).attr( 'data-posttype' );
								var taxonomy 			=	$( '#wpw_fp_term_taxonomy' ).val();
								var termid 				=	$( '#wpw_fp_term_term_id' ).val();
								var disablecountbox		=	$( '#wpw_fp_term_disable_followers_count');
								var countermsg			=	$( '#wpw_fp_term_followers_count_msg').val(); 
								var followtext			=	$( '#wpw_fp_term_follow_text').val(); 
								var followingtext		=	$( '#wpw_fp_term_following_text').val(); 
								var unfollowtext		=	$( '#wpw_fp_term_unfollow_text').val();
								/*
								var followtext 			= followtext.replace( /\"/g, wpwFpHtmlEntity( '"' ) );
								var followingtext 		= followingtext.replace( /\"/g, wpwFpHtmlEntity( '"' ) );
								var unfollowtext 		= unfollowtext.replace( /\"/g, wpwFpHtmlEntity( '"' ) );
								*/
								wpwfpshortcodestr	+= '['+wpwfpshortcode;
								if(posttype != '') {
									wpwfpshortcodestr	+= ' posttype="'+posttype+'"';
								}
								if(taxonomy != '') {
									wpwfpshortcodestr	+= ' taxonomy="'+taxonomy+'"';
								}
								if(termid != '') {
									wpwfpshortcodestr	+= ' termid="'+termid+'"';
								}
								if( disablecountbox.is(":checked") ) {
									wpwfpshortcodestr	+= ' disablecount="true"';
								}
								if( countermsg != '' ) {
									wpwfpshortcodestr	+= ' followerscountmsg="'+countermsg+'"';
								}
								if(followtext != '') {
									wpwfpshortcodestr	+= ' followtext="'+followtext+'"';
								}
								if(followingtext != '') {
									wpwfpshortcodestr	+= ' followingtext="'+followingtext+'"';
								}
								if(unfollowtext != '') {
									wpwfpshortcodestr	+= ' unfollowtext="'+unfollowtext+'"';
								}
								wpwfpshortcodestr	+= '][/'+wpwfpshortcode+']';
								break;
								
					case 'wpw_follow_author_me'	:
					
								var author_id			= 	$( '#wpw_fp_author_nm').val(); 
								var disablecountbox		=	$( '#wpw_fp_author_disable_followers_count');
								var countermsg			=	$( '#wpw_fp_author_followers_count_msg').val(); 
								var followtext			=	$( '#wpw_fp_author_follow_text').val(); 
								var followingtext		=	$( '#wpw_fp_author_following_text').val(); 
								var unfollowtext		=	$( '#wpw_fp_author_unfollow_text').val();
								
								wpwfpshortcodestr	+= '['+wpwfpshortcode;
								
								if( author_id != '' ) {
									wpwfpshortcodestr	+= ' author_id="'+author_id+'"';
								}
								if( disablecountbox.is(":checked") ) {
									wpwfpshortcodestr	+= ' disablecount="true"';
								}
								if( countermsg != '' ) {
									wpwfpshortcodestr	+= ' followerscountmsg="'+countermsg+'"';
								}
								if(followtext != '') {
									wpwfpshortcodestr	+= ' followtext="'+followtext+'"';
								}
								if(followingtext != '') {
									wpwfpshortcodestr	+= ' followingtext="'+followingtext+'"';
								}
								if(unfollowtext != '') {
									wpwfpshortcodestr	+= ' unfollowtext="'+unfollowtext+'"';
								}
								wpwfpshortcodestr	+= '][/'+wpwfpshortcode+']';
								break;
								
					case 'wpw_follow_post_list' :
					case 'wpw_follow_term_list' :
					case 'wpw_follow_author_list' :
					
								wpwfpshortcodestr	+= '['+wpwfpshortcode+'][/'+wpwfpshortcode+']';
								break;
								
					default:
								break;
				}
			 	
			 	 //send_to_editor(str);
		        tinymce.get('content').execCommand('mceInsertContent',false, wpwfpshortcodestr);
		  		jQuery('.wpw-fp-popup-overlay').fadeOut();
				jQuery('.wpw-fp-popup-content').fadeOut();
		}
	});
	
	$( document ).on( "change", "#wpw_fp_term_taxonomy", function() {
	
		var taxonomy = $( this ).val();
		var posttype = $( '#wpw_fp_term_taxonomy option:selected' ).attr( 'data-posttype' );
		
		if( taxonomy != '' ) {
			
			// Show Loader
			$( '.wpw-fp-follow-loader' ).show();
			
			var data = { 
							action		:	'wpw_fp_terms',
							posttype	:	posttype,
							taxonomy	:	taxonomy
						};
			
			jQuery.post( ajaxurl,data,function(response) {
				
				// Hide Loader
				$( '.wpw-fp-follow-loader' ).hide();
				
				$( '#wpw_fp_term_term_id' ).html( response ).trigger( 'chosen:updated' );
				
				$( '.wpw-fp-term-slug-tr' ).show();
				
			});
		} else {
			$( '.wpw-fp-term-taxonomy-tr' ).hide();
			$( '.wpw-fp-term-slug-tr' ).hide();
		}
	});
	
	
	
	
});

//switch wordpress editor to visual mode
function wpwFpSwitchDefaultEditorVisual() {
	if (jQuery('#content').hasClass('html-active')) {
		switchEditors.go(editor, 'tinymce');
	}
}

//Convert with html entity
function wpwFpHtmlEntity( character ) {
	if( character == '"' ) {
		return '&quot;';
	}
}