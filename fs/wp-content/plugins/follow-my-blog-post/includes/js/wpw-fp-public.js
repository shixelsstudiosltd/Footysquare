jQuery(document).ready(function($) {
	
	// Code used for follow post popup
	$( document ).on( "click", ".wpw-fp-follow-post-wrapper .wpw-fp-guest-btn", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-follow-post-wrapper' );
		
		follow_wrapper.find( '.wpw-fp-followsemail' ).val( '' ).removeClass( 'wpw_fp_email_error' );
		follow_wrapper.find( '.wpw_fp_follow_email_error' ).hide();
		
		follow_wrapper.find( '.wpw-fp-follow-post-popup' ).slideDown( 'slow' );
		follow_wrapper.find( '.wpw-fp-post-popup-overlay' ).fadeIn( 'slow' );
	});
	
	// Code used for follow post
	$( document ).on( "click", ".wpw-fp-follow-post-wrapper .wpw-fp-follow-btn", function() {
		
		var status 			= $(this).attr('data-status');
		var postid 			= $(this).attr('data-postid');
		var currentpostid 	= $(this).attr('data-current-postid');
		var follow		 	= $(this).attr('data-follow-text');
		var following		= $(this).attr('data-following-text');
		var unfollow		= $(this).attr('data-unfollow-text');
		var email	= '';
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-follow-post-wrapper' );
		
		if( follow_wrapper.find( '.wpw-fp-followsemail' ).is( ':visible' ) ) {
			
			follow_wrapper.find( '.wpw_fp_follow_email_error' ).html('').hide();
			follow_wrapper.find( '.wpw-fp-followsemail' ).removeClass('wpw_fp_email_error');
			
			email 	= follow_wrapper.find( '.wpw-fp-followsemail' ).val();
			
			if( email == '' ) { // Check email is empty
				follow_wrapper.find( '.wpw_fp_follow_email_error' ).html( WpwFpPublic.emailempty ).show();
				follow_wrapper.find( '.wpw-fp-followsemail' ).addClass( 'wpw_fp_email_error' );
				return false;
			} else {
				if( !wpw_fp_valid_email( email ) ) { // Check email is valid or not
					follow_wrapper.find( '.wpw_fp_follow_email_error' ).html( WpwFpPublic.emailinvalid ).show();
					follow_wrapper.find( '.wpw-fp-followsemail' ).addClass( 'wpw_fp_email_error' );
					return false;
				}
			}
		}
		
		if( status == '1' ) {
			follow_wrapper.find( '.wpw-following-text' ).html( following );
			follow_wrapper.find( '.wpw-following-text' ).removeClass( 'wpw-fp-display-none' );
			follow_wrapper.find( '.wpw-unfollowing-text' ).addClass( 'wpw-fp-display-none' ).removeClass( 'wpw-fp-display-inline' );
		} else {
			follow_wrapper.find( '.wpw-fp-follow-btn' ).addClass( 'wpw-fp-follow-button' ).removeClass( 'wpw-fp-following-button' );
			follow_wrapper.find( '.wpw-following-text' ).addClass( 'wpw-fp-display-none' ).removeClass( 'wpw-fp-display-inline' );
			follow_wrapper.find( '.wpw-unfollowing-text' ).removeClass( 'wpw-fp-display-none' ).addClass( 'wpw-fp-display-inline' );
		}
		
		var data = { 
						action			:	'wpw_fp_follow_post',
						status			:	status,
						postid			:	postid,
						currentpostid	:	currentpostid,
						email			:	email
					};
				
		//show loader
		follow_wrapper.find( '.wpw_fp_follow_loader' ).show();
		follow_wrapper.find( '.wpw-following-text' ).html( WpwFpPublic.processing );
		follow_wrapper.find( '.wpw-unfollowing-text' ).html( WpwFpPublic.processing );
		
		jQuery.post( WpwFpPublic.ajaxurl,data,function(response) {
		
			//hide loader
			follow_wrapper.find( '.wpw_fp_follow_loader' ).hide();
			
			if( response != 'confirm' ) {
				
				follow_wrapper.find( '.wpw_fp_followers_count' ).html( response );
				
				if( WpwFpPublic.loginflag == '1' ) {
					
					if( status == '1' ) {
						follow_wrapper.find( '.wpw-following-text' ).html( following );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).attr( 'data-status', '0' );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-follow-button' ).addClass( 'wpw-fp-following-button' );
					} else {
						follow_wrapper.find( '.wpw-following-text' ).html( follow );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).attr( 'data-status', '1' );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-following-button' ).addClass( 'wpw-fp-follow-button' );
					}
				} else {
					//make text of following and unfollowing to as it is "Follow" & "Unfollow"
					follow_wrapper.find( '.wpw-following-text' ).html( follow );
					follow_wrapper.find( '.wpw-unfollowing-text' ).html( unfollow );
					//make follow button text as it is "Follow"
					follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-following-button' ).addClass( 'wpw-fp-follow-button' );
					window.location.reload();
				}
				
			} else { 
				
				if( WpwFpPublic.loginflag == '1' ) {
					//show confirm email message to user
					follow_wrapper.find( '.wpw_fp_follow_message' ).html( '<div class="message_stack_success">' + WpwFpPublic.checkemail + '<div>' );
					//make button text to follow
					follow_wrapper.find( '.wpw-following-text' ).html( follow );
					
				} else {
					
					follow_wrapper.find( '.wpw-following-text' ).html( follow );
					window.location.reload();
				}
			}
			follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-unfollow-button' ).addClass( 'wpw-fp-follow-button' );
			follow_wrapper.find( '.wpw-following-text' ).removeClass( 'wpw-fp-display-none' );
			follow_wrapper.find( '.wpw-unfollowing-text' ).addClass( 'wpw-fp-display-none' ).removeClass( 'wpw-fp-display-inline' );
			//make unfollow button text as it is
			follow_wrapper.find( '.wpw-unfollowing-text' ).html( unfollow );
		});
		
	});
	
	// Code used for follow term popup
	$( document ).on( "click", ".wpw-fp-follow-term-wrapper .wpw-fp-guest-btn", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-follow-term-wrapper' );
		
		follow_wrapper.find( '.wpw-fp-followsemail' ).val( '' ).removeClass( 'wpw_fp_email_error' );
		follow_wrapper.find( '.wpw_fp_follow_email_error' ).hide();
		
		follow_wrapper.find( '.wpw-fp-follow-term-popup' ).slideDown( 'slow' );
		follow_wrapper.find( '.wpw-fp-term-popup-overlay' ).fadeIn( 'slow' );
	});
	
	// Code used for follow author popup
	$( document ).on( "click", ".wpw-fp-follow-author-wrapper .wpw-fp-guest-btn", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-follow-author-wrapper' );
		
		follow_wrapper.find( '.wpw-fp-followsemail' ).val( '' ).removeClass( 'wpw_fp_email_error' );
		follow_wrapper.find( '.wpw_fp_follow_email_error' ).hide();
		
		follow_wrapper.find( '.wpw-fp-follow-author-popup' ).slideDown( 'slow' );
		follow_wrapper.find( '.wpw-fp-author-popup-overlay' ).fadeIn( 'slow' );
	});
	
	// Code start for follow term
	$( document ).on( "click", ".wpw-fp-follow-term-wrapper .wpw-fp-follow-btn", function() {
		
		var status 			= $(this).attr('data-status');
		var posttype 		= $(this).attr('data-posttype');
		var taxonomyslug 	= $(this).attr('data-taxonomy-slug');
		var termid 			= $(this).attr('data-term-id');
		var currentpostid 	= $(this).attr('data-current-postid');
		var follow		 	= $(this).attr('data-follow-text');
		var following		= $(this).attr('data-following-text');
		var unfollow		= $(this).attr('data-unfollow-text');
		var email	= '';
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-follow-term-wrapper' );
		
		if( follow_wrapper.find( '.wpw-fp-followsemail' ).is(':visible') ) {
			
			follow_wrapper.find( '.wpw_fp_follow_email_error' ).html('').hide();
			follow_wrapper.find( '.wpw-fp-followsemail' ).removeClass( 'wpw_fp_email_error' );
			
			email 	= follow_wrapper.find( '.wpw-fp-followsemail' ).val();
			
			if( email == '' ) { // Check email is empty
				follow_wrapper.find( '.wpw_fp_follow_email_error' ).html( WpwFpPublic.emailempty ).show();
				follow_wrapper.find( '.wpw-fp-followsemail' ).addClass( 'wpw_fp_email_error' );
				return false;
			} else {
				if( !wpw_fp_valid_email( email ) ) { // Check email is valid or not
					follow_wrapper.find( '.wpw_fp_follow_email_error' ).html( WpwFpPublic.emailinvalid ).show();
					follow_wrapper.find( '.wpw-fp-followsemail' ).addClass( 'wpw_fp_email_error' );
					return false;
				}
			}
		}
		
		if( status == '1' ) {
			follow_wrapper.find( '.wpw-following-text' ).html( following );
			follow_wrapper.find( '.wpw-following-text' ).removeClass( 'wpw-fp-display-none' );
			follow_wrapper.find( '.wpw-unfollowing-text' ).addClass( 'wpw-fp-display-none' ).removeClass( 'wpw-fp-display-inline' );
		} else {
			follow_wrapper.find( '.wpw-fp-follow-btn' ).addClass( 'wpw-fp-follow-button' ).removeClass( 'wpw-fp-following-button' );
			follow_wrapper.find( '.wpw-following-text' ).addClass( 'wpw-fp-display-none' ).removeClass( 'wpw-fp-display-inline' );
			follow_wrapper.find( '.wpw-unfollowing-text' ).removeClass( 'wpw-fp-display-none' ).addClass( 'wpw-fp-display-inline' );
		}
		
		var data = { 
						action			:	'wpw_fp_follow_term',
						status			:	status,
						posttype		:	posttype,
						taxonomyslug	:	taxonomyslug,
						termid			:	termid,
						currentpostid	:	currentpostid,
						email			:	email
					};
				
		//show loader
		follow_wrapper.find( '.wpw_fp_follow_loader' ).show();
		follow_wrapper.find( '.wpw-following-text' ).html( WpwFpPublic.processing );
		follow_wrapper.find( '.wpw-unfollowing-text' ).html( WpwFpPublic.processing );
		
		jQuery.post( WpwFpPublic.ajaxurl,data,function(response) {
		
			//hide loader
			follow_wrapper.find( '.wpw_fp_follow_loader' ).hide();
			
			if( response != 'confirm' ) {
				
				follow_wrapper.find( '.wpw_fp_followers_count' ).html( response );
				
				if( WpwFpPublic.loginflag == '1' ) {
					
					if( status == '1' ) {
						follow_wrapper.find( '.wpw-following-text' ).html( following );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).attr( 'data-status', '0' );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-follow-button' ).addClass( 'wpw-fp-following-button' );
					} else {
						follow_wrapper.find( '.wpw-following-text' ).html( follow );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).attr( 'data-status', '1' );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-following-button' ).addClass( 'wpw-fp-follow-button' );
					}
				} else {
					//make text of following and unfollowing to as it is "Follow" & "Unfollow"
					follow_wrapper.find( '.wpw-following-text' ).html( follow );
					follow_wrapper.find( '.wpw-unfollowing-text' ).html( unfollow );
					//make follow button text as it is "Follow"
					follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-following-button' ).addClass( 'wpw-fp-follow-button' );
					window.location.reload();
				}
				
			} else { 
				
				if( WpwFpPublic.loginflag == '1' ) {
					//show confirm email message to user
					follow_wrapper.find( '.wpw_fp_follow_message' ).html( '<div class="message_stack_success">' + WpwFpPublic.checkemail + '<div>' );
					//make button text to follow
					follow_wrapper.find( '.wpw-following-text' ).html( follow );
					
				} else {
					
					follow_wrapper.find( '.wpw-following-text' ).html( follow );
					window.location.reload();
				}
			}
			follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-unfollow-button' ).addClass( 'wpw-fp-follow-button' );
			follow_wrapper.find( '.wpw-following-text' ).removeClass( 'wpw-fp-display-none' );
			follow_wrapper.find( '.wpw-unfollowing-text' ).addClass( 'wpw-fp-display-none' ).removeClass( 'wpw-fp-display-inline' );
			//make unfollow button text as it is
			follow_wrapper.find( '.wpw-unfollowing-text' ).html( unfollow );
		});
		
	});
	
	// Code start for follow Author
	$('.wpw-fp-follow-author-wrapper .wpw-fp-follow-btn').live( 'click', function() {
		
		var status 			= $(this).attr('data-status');
		var authorid 		= $(this).attr('data-author-id');
		var currentpostid 	= $(this).attr('data-current-postid');
		var follow		 	= $(this).attr('data-follow-text');
		var following		= $(this).attr('data-following-text');
		var unfollow		= $(this).attr('data-unfollow-text');
		var email	= '';
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-follow-author-wrapper' );
		email 	= follow_wrapper.find( '.wpw-fp-followsemail' ).val();
		
		
		if( follow_wrapper.find( '.wpw-fp-followsemail' ).is(':visible') ) {
			
			follow_wrapper.find( '.wpw_fp_follow_email_error' ).html('').hide();
			follow_wrapper.find( '.wpw-fp-followsemail' ).removeClass( 'wpw_fp_email_error' );
			
			email 	= follow_wrapper.find( '.wpw-fp-followsemail' ).val();
			
			if( email == '' ) { // Check email is empty
				follow_wrapper.find( '.wpw_fp_follow_email_error' ).html( WpwFpPublic.emailempty ).show();
				follow_wrapper.find( '.wpw-fp-followsemail' ).addClass( 'wpw_fp_email_error' );
				return false;
			} else {
				if( !wpw_fp_valid_email( email ) ) { // Check email is valid or not
					follow_wrapper.find( '.wpw_fp_follow_email_error' ).html( WpwFpPublic.emailinvalid ).show();
					follow_wrapper.find( '.wpw-fp-followsemail' ).addClass( 'wpw_fp_email_error' );
					return false;
				}
			}
		}
		
		if( status == '1' ) {
			follow_wrapper.find( '.wpw-following-text' ).html( following );
			follow_wrapper.find( '.wpw-following-text' ).removeClass( 'wpw-fp-display-none' );
			follow_wrapper.find( '.wpw-unfollowing-text' ).addClass( 'wpw-fp-display-none' ).removeClass( 'wpw-fp-display-inline' );
		} else {
			follow_wrapper.find( '.wpw-fp-follow-btn' ).addClass( 'wpw-fp-follow-button' ).removeClass( 'wpw-fp-following-button' );
			follow_wrapper.find( '.wpw-following-text' ).addClass( 'wpw-fp-display-none' ).removeClass( 'wpw-fp-display-inline' );
			follow_wrapper.find( '.wpw-unfollowing-text' ).removeClass( 'wpw-fp-display-none' ).addClass( 'wpw-fp-display-inline' );
		}
		
		var data = { 
						action			:	'wpw_fp_follow_author',
						status			:	status,
						authorid		:	authorid,
						currentpostid	:	currentpostid,
						email			:	email
					};
				
		//show loader
		follow_wrapper.find( '.wpw_fp_follow_loader' ).show();
		follow_wrapper.find( '.wpw-following-text' ).html( WpwFpPublic.processing );
		follow_wrapper.find( '.wpw-unfollowing-text' ).html( WpwFpPublic.processing );
		
		jQuery.post( WpwFpPublic.ajaxurl,data,function(response) {
		
			//hide loader
			follow_wrapper.find( '.wpw_fp_follow_loader' ).hide();
			
			if( response != 'confirm' ) {
				
				follow_wrapper.find( '.wpw_fp_followers_count' ).html( response );
				
				if( WpwFpPublic.loginflag == '1' ) {
					
					if( status == '1' ) {
						follow_wrapper.find( '.wpw-following-text' ).html( following );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).attr( 'data-status', '0' );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-follow-button' ).addClass( 'wpw-fp-following-button' );
					} else {
						follow_wrapper.find( '.wpw-following-text' ).html( follow );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).attr( 'data-status', '1' );
						follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-following-button' ).addClass( 'wpw-fp-follow-button' );
					}
				} else {
					//make text of following and unfollowing to as it is "Follow" & "Unfollow"
					follow_wrapper.find( '.wpw-following-text' ).html( follow );
					follow_wrapper.find( '.wpw-unfollowing-text' ).html( unfollow );
					//make follow button text as it is "Follow"
					follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-following-button' ).addClass( 'wpw-fp-follow-button' );
					window.location.reload();
				}
				
			} else { 
				
				if( WpwFpPublic.loginflag == '1' ) {
					//show confirm email message to user
					follow_wrapper.find( '.wpw_fp_follow_message' ).html( '<div class="message_stack_success">' + WpwFpPublic.checkemail + '<div>' );
					//make button text to follow
					follow_wrapper.find( '.wpw-following-text' ).html( follow );
					
				} else {
					
					follow_wrapper.find( '.wpw-following-text' ).html( follow );
					window.location.reload();
				}
			}
			follow_wrapper.find( '.wpw-fp-follow-btn' ).removeClass( 'wpw-fp-unfollow-button' ).addClass( 'wpw-fp-follow-button' );
			follow_wrapper.find( '.wpw-following-text' ).removeClass( 'wpw-fp-display-none' );
			follow_wrapper.find( '.wpw-unfollowing-text' ).addClass( 'wpw-fp-display-none' ).removeClass( 'wpw-fp-display-inline' );
			//make unfollow button text as it is
			follow_wrapper.find( '.wpw-unfollowing-text' ).html( unfollow );
		});
		
	});
	
	$( document ).on( "click", ".wpw-fp-popup-close, .wpw-fp-popup-overlay", function() {
		$('.wpw-fp-follow-popup').slideUp('slow');
		$('.wpw-fp-popup-overlay').fadeOut('slow');
	});
	
	$( document ).on( "click", "#wpw_fp_unsubscribe_submit", function() {
		
		$( '.wpw-fp-unsubscribe-email-error' ).removeClass( 'message_stack_error' ).html('').hide();
		$( '#wpw_fp_unsubscribe_email' ).removeClass( 'wpw_fp_email_error' );
		$( '.message_stack_success' ).hide();
		$( '.message_stack_error' ).hide();
		
		var email = $( '#wpw_fp_unsubscribe_email' ).val();
		
		if( email == '' ) { // Check email is empty
			$( '.wpw-fp-unsubscribe-email-error' ).addClass( 'message_stack_error' ).html( WpwFpPublic.emailempty ).show();
			$( '#wpw_fp_unsubscribe_email' ).addClass( 'wpw_fp_email_error' ).show();
			return false;
		} else {
			if( !wpw_fp_valid_email( email ) ) { // Check email is valid or not
				$( '.wpw-fp-unsubscribe-email-error' ).addClass( 'message_stack_error' ).html( WpwFpPublic.emailinvalid ).show();
				$( '#wpw_fp_unsubscribe_email' ).addClass( 'wpw_fp_email_error' ).show();
				return false;
			}
		}
	});
	
	$( document ).on( "click", ".wpw-fb-cb-posts", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-manage-follow-posts' );
		
		if( $( this ).is( ':checked' ) ) {
			
			follow_wrapper.find( '.wpw-fp-cb-post' ).each( function() {
				follow_wrapper.find( this ).attr( 'checked', true );
			});
			follow_wrapper.find( '.wpw-fp-cb-posts-1' ).attr( 'checked', true );
			follow_wrapper.find( '.wpw-fp-cb-posts-2' ).attr( 'checked', true );
		} else {
			
			follow_wrapper.find( '.wpw-fp-cb-post' ).each( function() {
				follow_wrapper.find( this ).attr( 'checked', false );
			});
			follow_wrapper.find( '.wpw-fp-cb-posts-1' ).attr( 'checked', false );
			follow_wrapper.find( '.wpw-fp-cb-posts-2' ).attr( 'checked', false );
		}
	});
	
	$( document ).on( "click", ".wpw-fp-cb-post", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-manage-follow-posts' );
		if( !( $( this ).is( ':checked' ) ) ) {
			follow_wrapper.find( '.wpw-fp-cb-posts-1' ).attr( 'checked', false );
			follow_wrapper.find( '.wpw-fp-cb-posts-2' ).attr( 'checked', false );
		}
	});
	
	$( document ).on( "click", ".wpw-fp-bulk-action-post-btn", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-manage-follow-posts' );
		var bulkaction = follow_wrapper.find( '.wpw-fp-bulk-action' ).val();
		var pid = follow_wrapper.find( '.wpw-fp-bulk-paging' ).val();
		var ids = ',';
		
		follow_wrapper.find( '.wpw-fp-cb-post' ).each( function() {
			if( $( this ).is( ':checked' ) ) {
				ids += $( this ).val() + ',';
			}
		});
		 
		if( bulkaction != '' && ids != ',' ) {
			var data = {
							action: 'wpw_fp_bulk_action_post',
							bulkaction: bulkaction,
							ids: ids,
							paging: pid
						};
			
			follow_wrapper.find('.wpw-fp-bulk-action-loader').show();
			
			jQuery.post( WpwFpPublic.ajaxurl, data, function(response) {
				var newresponse = jQuery(response).filter('.wpw-fp-manage-follow-posts').html();
				follow_wrapper.find('.wpw-fp-bulk-action-loader').hide();
				jQuery('.wpw-fp-manage-follow-posts').html(newresponse);
			});
		}
		return false;
	});
	
	$( document ).on( "click", ".wpw-fb-cb-terms", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-manage-follow-terms' );
		
		if( $( this ).is( ':checked' ) ) {
			
			follow_wrapper.find( '.wpw-fp-cb-term' ).each( function() {
				follow_wrapper.find( this ).attr( 'checked', true );
			});
			follow_wrapper.find( '.wpw-fp-cb-terms-1' ).attr( 'checked', true );
			follow_wrapper.find( '.wpw-fp-cb-terms-2' ).attr( 'checked', true );
		} else {
			
			follow_wrapper.find( '.wpw-fp-cb-term' ).each( function() {
				follow_wrapper.find( this ).attr( 'checked', false );
			});
			follow_wrapper.find( '.wpw-fp-cb-terms-1' ).attr( 'checked', false );
			follow_wrapper.find( '.wpw-fp-cb-terms-2' ).attr( 'checked', false );
		}
	});
	
	$( document ).on( "click", ".wpw-fp-cb-term", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-manage-follow-terms' );
		if( !( $( this ).is( ':checked' ) ) ) {
			follow_wrapper.find( '.wpw-fp-cb-terms-1' ).attr( 'checked', false );
			follow_wrapper.find( '.wpw-fp-cb-terms-2' ).attr( 'checked', false );
		}
	});
	
	$( document ).on( "click", ".wpw-fp-bulk-action-term-btn", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-manage-follow-terms' );
		var bulkaction = follow_wrapper.find( '.wpw-fp-bulk-action' ).val();
		var pid = follow_wrapper.find( '.wpw-fp-bulk-paging' ).val();
		var ids = ',';
		
		follow_wrapper.find( '.wpw-fp-cb-term' ).each( function() {
			if( $( this ).is( ':checked' ) ) {
				ids += $( this ).val() + ',';
			}
		});
		 
		if( bulkaction != '' && ids != ',' ) {
			var data = {
							action: 'wpw_fp_bulk_action_term',
							bulkaction: bulkaction,
							ids: ids,
							paging: pid
						};
			
			follow_wrapper.find('.wpw-fp-bulk-action-loader').show();
			
			jQuery.post( WpwFpPublic.ajaxurl, data, function(response) {
				var newresponse = jQuery(response).filter('.wpw-fp-manage-follow-terms').html();
				follow_wrapper.find('.wpw-fp-bulk-action-loader').hide();
				jQuery('.wpw-fp-manage-follow-terms').html(newresponse);
			});
		}
		return false;
	});
	
	$( document ).on( "click", ".wpw-fb-cb-authors", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-manage-follow-authors' );
		
		if( $( this ).is( ':checked' ) ) {
			
			follow_wrapper.find( '.wpw-fp-cb-author' ).each( function() {
				follow_wrapper.find( this ).attr( 'checked', true );
			});
			follow_wrapper.find( '.wpw-fp-cb-authors-1' ).attr( 'checked', true );
			follow_wrapper.find( '.wpw-fp-cb-authors-2' ).attr( 'checked', true );
		} else {
			
			follow_wrapper.find( '.wpw-fp-cb-author' ).each( function() {
				follow_wrapper.find( this ).attr( 'checked', false );
			});
			follow_wrapper.find( '.wpw-fp-cb-authors-1' ).attr( 'checked', false );
			follow_wrapper.find( '.wpw-fp-cb-authors-2' ).attr( 'checked', false );
		}
	});
	
	$( document ).on( "click", ".wpw-fp-cb-author", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-manage-follow-authors' );
		if( !( $( this ).is( ':checked' ) ) ) {
			follow_wrapper.find( '.wpw-fp-cb-authors-1' ).attr( 'checked', false );
			follow_wrapper.find( '.wpw-fp-cb-authors-2' ).attr( 'checked', false );
		}
	});
	
	$( document ).on( "click", ".wpw-fp-bulk-action-author-btn", function() {
		
		var follow_wrapper = $( this ).parents( '.wpw-fp-manage-follow-authors' );
		var bulkaction = follow_wrapper.find( '.wpw-fp-bulk-action' ).val();
		var pid = follow_wrapper.find( '.wpw-fp-bulk-paging' ).val();
		var ids = ',';
		
		follow_wrapper.find( '.wpw-fp-cb-author' ).each( function() {
			if( $( this ).is( ':checked' ) ) {
				ids += $( this ).val() + ',';
			}
		});
		 
		if( bulkaction != '' && ids != ',' ) {
			var data = {
							action: 'wpw_fp_bulk_action_author',
							bulkaction: bulkaction,
							ids: ids,
							paging: pid
						};
			
			follow_wrapper.find('.wpw-fp-bulk-action-loader').show();
			
			jQuery.post( WpwFpPublic.ajaxurl, data, function(response) {
				var newresponse = jQuery(response).filter('.wpw-fp-manage-follow-authors').html();
				follow_wrapper.find('.wpw-fp-bulk-action-loader').hide();
				jQuery('.wpw-fp-manage-follow-authors').html(newresponse);
			});
		}
		return false;
	});
});

//function for follow post ajax pagination
function wpw_fp_follow_post_ajax_pagination(pid){
	
	var data = {
					action: 'wpw_fp_follow_post_next_page',
					paging: pid
				};
		
	jQuery('.wpw-fp-follow-posts-loader').show();
	jQuery('.wpw-fp-follow-posts-paging').hide();
	
	jQuery.post( WpwFpPublic.ajaxurl, data, function(response) {
		var newresponse = jQuery(response).filter('.wpw-fp-manage-follow-posts').html();
		jQuery('.wpw-fp-follow-posts-loader').hide();
		jQuery('.wpw-fp-manage-follow-posts').html(newresponse);
	});	
	return false;
}

//function for follow author ajax pagination
function wpw_fp_follow_author_ajax_pagination(pid){
	
	var data = {
					action: 'wpw_fp_follow_author_next_page',
					paging: pid
				};
		
	jQuery('.wpw-fp-follow-authors-loader').show();
	jQuery('.wpw-fp-follow-authors-paging').hide();
	
	jQuery.post( WpwFpPublic.ajaxurl, data, function(response) {
		var newresponse = jQuery(response).filter('.wpw-fp-manage-follow-authors').html();
		jQuery('.wpw-fp-follow-authors-loader').hide();
		jQuery('.wpw-fp-manage-follow-authors').html(newresponse);
	});	
	return false;
}
//function for follow term ajax pagination
function wpw_fp_follow_term_ajax_pagination(pid){
	
	var data = {
					action: 'wpw_fp_follow_term_next_page',
					paging: pid
				};
		
	jQuery('.wpw-fp-follow-terms-loader').show();
	jQuery('.wpw-fp-follow-terms-paging').hide();
	
	jQuery.post( WpwFpPublic.ajaxurl, data, function(response) {
		var newresponse = jQuery(response).filter('.wpw-fp-manage-follow-terms').html();
		jQuery('.wpw-fp-follow-terms-loader').hide();
		jQuery('.wpw-fp-manage-follow-terms').html(newresponse);
	});	
	return false;
}

// validation of email
function wpw_fp_valid_email(emailStr) {
	var checkTLD=1;
	var knownDomsPat=/^(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum)$/;
	var emailPat=/^(.+)@(.+)$/;
	var specialChars="\\(\\)><@,;:\\\\\\\"\\.\\[\\]";
	var validChars="\[^\\s" + specialChars + "\]";
	var quotedUser="(\"[^\"]*\")";
	var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
	var atom=validChars + '+';
	var word="(" + atom + "|" + quotedUser + ")";
	var userPat=new RegExp("^" + word + "(\\." + word + ")*$");
	var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$");
	var matchArray=emailStr.match(emailPat);
	if (matchArray==null) {
		//alert("Email address seems incorrect (check @ and .'s)");
		return false;
	}
	var user=matchArray[1];
	var domain=matchArray[2];
	// Start by checking that only basic ASCII characters are in the strings (0-127).
	for (i=0; i<user.length; i++) {
		if (user.charCodeAt(i)>127) {
			//alert("Ths username contains invalid characters in e-mail address.");
			return false;
		}
	}
	for (i=0; i<domain.length; i++) {
		if (domain.charCodeAt(i)>127) {
			//alert("Ths domain name contains invalid characters in e-mail address.");
			return false;
		}
	}
	if (user.match(userPat)==null) {
		//alert("The username doesn't seem to be valid in e-mail address.");
		return false;
	}
	var IPArray=domain.match(ipDomainPat);
	if (IPArray!=null) {
		for (var i=1;i<=4;i++) {
			if (IPArray[i]>255) {
				alert("Destination IP address is invalid!");
				return false;
	   		}
		}
		return true;
	}
	var atomPat=new RegExp("^" + atom + "$");
	var domArr=domain.split(".");
	var len=domArr.length;
	for (i=0;i<len;i++) {
		if (domArr[i].search(atomPat)==-1) {
			//alert("The domain name does not seem to be valid in e-mail address.");
			return false;
	   }	
	}
	if (checkTLD && domArr[domArr.length-1].length!=2 && 
		domArr[domArr.length-1].search(knownDomsPat)==-1) {
		//alert("The address must end in a well-known domain or two letter " + "country.");
		return false;
	}

	if (len<2) {
		//alert("This e-mail address is missing a hostname!");
		return false;
	}	
	return true;
}
//********************* END of function for email-id validation  ****************************//