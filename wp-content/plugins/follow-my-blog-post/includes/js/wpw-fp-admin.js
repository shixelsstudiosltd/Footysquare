function wpw_fp_popup_indivisual( id, other_type ) {
	
	jQuery('.wpw_fp_indivisual_entries_data').hide();
	jQuery('.wpw_fp_overlay').show();
	
	if( other_type != '' ) {
		
		other_type = other_type + '_'
	}
	
	jQuery('#fp_indivisual_data_'+other_type+id).show();
	wpw_fp_indivisual_entries_done(id,other_type);
}

function wpw_fp_indivisual_entries_done(id,other_type) {
	
	jQuery( document ).on( "click", "#wpw_fp_set_submit_indivisual", function() {
		
		var i=0;
		jQuery('#fp_indivisual_data_'+other_type+id+' input').each(function() {
			
			if(jQuery(this).is(':checked')) {
				i++;
			}
		});
		jQuery('#wpw_fp_selected_indivisual_'+other_type+id).text('( '+i+' ) selected');
		
		jQuery('.wpw_fp_indivisual_entries_data').hide();
		jQuery('.wpw_fp_overlay').hide();
		
		return false;
	});
}

function wpw_fp_checkall(id,other_type) {
	
	if( other_type != '' ) {
		
		other_type = other_type + '_'
	}
	jQuery('#fp_indivisual_data_'+other_type+id+' input').each( function() {
		
		jQuery(this).attr('checked',true);
	});
}

function wpw_fp_uncheckall(id,other_type) {
	
	if( other_type != '' ) {
		
		other_type = other_type + '_'
	}
	
	jQuery('#fp_indivisual_data_'+other_type+id+' input').each( function() {
		
		jQuery(this).attr('checked',false);
	});
}

function wpw_fp_chosen_select(){
	
	jQuery(".chosen-select").each(function (){
		
		jQuery(this).chosen({search_contains:true});
	});
	jQuery(".wpw-fp-form select").each(function (){
		
		jQuery(this).chosen({search_contains:true});
	});
}

jQuery(document).ready(function($) {
	//code for plugin settings tabs
	$("#wpw_fp_author_nm").chosen();
	jQuery('select#wpw_fp_author_nm').ajaxChosen({
	    method: 		'GET',
	    url: 			ajaxurl,
	    dataType: 		'json',
	    minTermLength: 	1,
	    data: {
	    	action: 'wpw_fp_search_authors'
	    }
	}, function (data) {

		var terms = {};

	    jQuery.each(data, function (i, val) {
	        terms[i] = val;
	    });
		//alert(terms);
	    return terms;
	});
	//  When user clicks on tab, this code will be executed
    $( document ).on( "click", ".nav-tab-wrapper a", function() {
		
        //  First remove class "active" from currently active tab
        $(".nav-tab-wrapper a").removeClass('nav-tab-active');
 
        //  Now add class "active" to the selected/clicked tab
        $(this).addClass("nav-tab-active");
 
        //  Hide all tab content
        $(".wpw-fp-tab-content").hide();
 
        //  Here we get the href value of the selected tab
        var selected_tab = $(this).attr("href");
 
        //  Show the selected tab content
        $(selected_tab).show();
        
        var selected = selected_tab.split('-');
        $(".wpw-fp-tab-content").removeClass('wpw-fp-selected-tab');
 		$( '#wpw_fp_selected_tab' ).val( selected[3] );
 
        //  At the end, we add return false so that the click on the link is not executed
        return false;
    });
    
    //call on click reset options button from settings page
	$( document ).on( "click", "#wpw_fp_reset_settings", function() {
		
		var ans;
		ans = confirm('Click OK to reset all options. All settings will be lost!');

		if(ans){
			return true;
		} else {
			return false;
		}
	});
	
	$( document ).on( "click", ".wpw-fp-delete", function() {
		
		var confirmdelete = confirm('Are you sure want to delete this ?');
		
		if( confirmdelete ) {
			return true;
		} else {
			return false;
		}
		
	});
	
	$( document ).on( "click", ".wpw_fp_overlay", function() {
		jQuery('.wpw_fp_indivisual_entries_data').hide();
		jQuery('.wpw_fp_overlay').fadeOut();
	});
	
	wpw_fp_chosen_select();
	
	//send test email from email settings
	$( document ).on( "click", ".wpw-fp-send-test-email", function() {
		
		$( '.wpw-fp-loader' ).show();
		var email_template = $('.wpw-fp-email-template').val();
		var data = {
						action	:	'wpw_fp_test_email',
						template:	email_template
					};
		$('.wpw-fp-send-email-msg').html('').hide();		
		$.post(ajaxurl,data,function(response) {
			//alert(response);
			$( '.wpw-fp-loader' ).hide();
			if( response == 'success' ) {
				$('.wpw-fp-send-email-msg').removeClass('wpw-fp-email-error').addClass('wpw-fp-email-success');
				$('.wpw-fp-send-email-msg').html( WpwFpSettings.testemailsuccess ).show();
				setTimeout(function(){
					$(".wpw-fp-send-email-msg").fadeOut("slow", function () {
					});
				}, 2000);
			} else {
				$('.wpw-fp-send-email-msg').removeClass('wpw-fp-email-success').addClass('wpw-fp-email-error');
				$('.wpw-fp-send-email-msg').html( WpwFpSettings.testemailerror ).show();
				setTimeout(function(){
					$(".wpw-fp-send-email-msg").fadeOut("slow", function () {
					});
				}, 2000);
			}
		});
	});
	
	// send email show hide post, terms, author onclick followed type
	$( document ).on( "click", ".followed_type", function() {
		
		var selected_followed_type = document.querySelector('input[name="followed_type"]:checked').value;
		
		if(selected_followed_type=="followed_post"){
			$('.followed_type_post').show();
			$('.followed_type_terms').hide();
			$('.wpw-fp-term-slug-tr').hide();
			$('.followed_type_author').hide();
		}
		else if(selected_followed_type=="followed_terms"){
			$('.followed_type_post').hide();
			$('.wpw-fp-post-tr ').hide();
			$('.followed_type_terms').show();
			$('.followed_type_author').hide();			
		}
		else if(selected_followed_type=="followed_authors"){
			$('.followed_type_post').hide();
			$('.wpw-fp-post-tr ').hide();
			$('.wpw-fp-term-slug-tr').hide();
			$('.followed_type_terms').hide();
			$('.followed_type_author').show();
		}
	});
	
	
	$( document ).on( 'click', '.wpw-fp-send-email-submit', function() {
		//var inputid = 'followed_email_body';
		
		
		var selected_followed_type = document.querySelector('input[name="followed_type"]:checked').value;
		
		var flag = 'false';
		if(selected_followed_type == 'followed_post'){
			if( $( '.followed_type_post' ).is( ':visible' ) ) {
					
					$( '.followed_type_post_error' ).html(' ').hide();
					$( '.followed_type_post' ).removeClass('wpw_fp_email_error');
					
					type_post = $( '#followed_type_post' ).val();
					
					if( type_post == '' ) { // Check subject is empty
						$( '.followed_type_post_error' ).html( 'Please select post type.' ).show();
						var flag = 'true';
					}
			}
			if( $( '.followed_type_post_name' ).is( ':visible' ) ) {
					
				$( '.followed_type_post_name_error' ).html(' ').hide();
				$( '.followed_type_post_name' ).removeClass('wpw_fp_email_error');
				
				type_post_name = $( '#followed_type_post_name' ).val();
				
				if( type_post_name == '' ) { // Check subject is empty
					$( '.followed_type_post_name_error' ).html( 'Please select post name.' ).show();
					var flag = 'true';
				}
			}
		}else if(selected_followed_type == 'followed_terms'){
			if( $( '.followed_type_terms' ).is( ':visible' ) ) {
					
					$( '.followed_type_terms_error' ).html('').hide();
					$( '.followed_type_terms' ).removeClass('wpw_fp_email_error');
					
					type_terms = $( '#followed_type_terms' ).val();
					
					if( type_terms == '' ) { // Check subject is empty
						$( '.followed_type_terms_error' ).html( 'Please select taxonomy.' ).show();
						var flag = 'true';
					}
			}
			if( $( '.wpw_fp_term_id' ).is( ':visible' ) ) {
					
				$( '.followed_type_term_id_error' ).html('').hide();
				$( '.wpw_fp_term_id' ).removeClass('wpw_fp_email_error');
				
				term_id = $( '#wpw_fp_term_id' ).val();
				
				if( term_id == '' ) { // Check subject is empty
					$( '.followed_type_term_id_error' ).html( 'Please select term.' ).show();
					var flag = 'true';
				}
			}
		}else if(selected_followed_type == 'followed_authors'){
			if( $( '.followed_type_author' ).is( ':visible' ) ) {
					
				$( '.followed_type_author_error' ).html('').hide();
				$( '.followed_type_author' ).removeClass('wpw_fp_email_error');
				
				type_authors = $( '#followed_type_author' ).val();
				
				if( type_authors == '' ) { // Check subject is empty
					$( '.followed_type_author_error' ).html( 'Please select author.' ).show();
					var flag = 'true';
				}
			}
		}
			
		if( $( '.followed_email_body' ).is( ':visible' ) ) {
			var editorContent = tinyMCE.get('followed_email_body').getContent();
			
			$( '.followed_email_body_error' ).html('').hide();
			$( '.followed_email_body' ).removeClass('wpw_fp_email_error');
			
			//subject = $( '.followed_email_body' ).val();
			
			if( editorContent == '' ) { // Check subject is empty
				$( '.followed_email_body_error' ).html( 'Please enter email body.' ).show();
				var flag = 'true';
				
			}
		}
		
		if( flag == 'true' ){
			
			$("html, body").animate({ scrollTop: $(".post-box-container").offset().top }, 500);
			return false;
			
		} else {
			
			$(".wpw-fp-send-email-submit").attr( 'disabled','disabled' );
			$(".wpw-fp-send-email-submit").val( "Processing..." );
			
		}
			
	});
	
	$( document ).on( "change", "#followed_type_post", function() {
	
		var post = $( this ).val();
		
		var posttype = $( '#followed_type_post option:selected' ).attr( 'data-posttype' );
		
		if( post != '' ) {
			
			// Show Loader
			$( '.wpw-fp-post-follow-loader' ).show();
			
			var data = { 
							action		:	'wpw_fp_post_name',
							posttype	:	posttype
						};
			
			jQuery.post( ajaxurl,data,function(response) {
				// Hide Loader
				$( '.wpw-fp-post-follow-loader' ).hide();
				
				$( '#followed_type_post_name' ).html( response ).trigger( 'chosen:updated' );
				
				$( '.wpw-fp-post-tr' ).show();
				
			});
		} else {
			$( '.wpw-fp-post-tr' ).hide();
		}
	});
	
	$( document ).on( "change", "#followed_type_terms", function() {
	
		var taxonomy = $( this ).val();
		var posttype = $( '#followed_type_terms option:selected' ).attr( 'data-posttype' );
		
		if( taxonomy != '' ) {
			
			// Show Loader
			$( '.wpw-fp-term-follow-loader' ).show();
			
			var data = { 
							action		:	'wpw_fp_custom_terms',
							posttype	:	posttype,
							taxonomy	:	taxonomy
						};
			
			jQuery.post( ajaxurl,data,function(response) {
				
				// Hide Loader
				$( '.wpw-fp-term-follow-loader' ).hide();
				
				$( '#wpw_fp_term_id' ).html( response ).trigger( 'chosen:updated' );
				
				$( '.wpw-fp-term-slug-tr' ).show();
				
			});
		} else {
			$( '.wpw-fp-term-slug-tr' ).hide();
		}
	});
});