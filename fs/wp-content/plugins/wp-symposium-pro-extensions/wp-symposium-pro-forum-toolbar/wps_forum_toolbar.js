jQuery(document).ready(function() {

	if (wps_forum_toolbar.mode == 'wysiwyg') {

		if (jQuery("#wps_forum_post_textarea").length) {
			jQuery('#wps_forum_post_textarea').redactor({
				maxHeight: 300,
				buttons: wps_forum_toolbar.icons.split(","),
				plugins: ['fontcolor'],
				iframe: true,
				css: wps_forum_toolbar.iframe_css,
			});
		}
		if (jQuery("#wps_forum_comment").length) {
			jQuery('#wps_forum_comment').redactor({
				maxHeight: 300,
				buttons: wps_forum_toolbar.icons.split(","),
				plugins: ['fontcolor'],
				iframe: true,
				css: wps_forum_toolbar.iframe_css,
			});
		}

		if (jQuery("#wps_forum_post_edit_textarea").length) {
			jQuery('#wps_forum_post_edit_textarea').redactor({
				maxHeight: 300,
				buttons: wps_forum_toolbar.icons.split(","),
				plugins: ['fontcolor'],
				iframe: true,
				css: wps_forum_toolbar.iframe_css,
			});
		}
		if (jQuery("#wps_forum_comment_edit_textarea").length) {
			jQuery('#wps_forum_comment_edit_textarea').redactor({
				maxHeight: 300,
				buttons: wps_forum_toolbar.icons.split(","),
				plugins: ['fontcolor'],
				iframe: true,
				css: wps_forum_toolbar.iframe_css,
			});
		}

	} else {

		__wps__evoke_bbcode_toolbar();

		function __wps__evoke_bbcode_toolbar() {
			if (jQuery(".__wps__toolbar").length) {
				jQuery('body').on('click', '.__wps__toolbar_bold', function() { jQuery('#'+jQuery(this).attr('rel')).wps_insertAtCaret('[b]','[/b]'); });
				jQuery('body').on('click', '.__wps__toolbar_italic', function() { jQuery('#'+jQuery(this).attr('rel')).wps_insertAtCaret('[i]','[/i]'); });
				jQuery('body').on('click', '.__wps__toolbar_underline', function() { jQuery('#'+jQuery(this).attr('rel')).wps_insertAtCaret('[u]','[/u]'); });
				jQuery('body').on('click', '.__wps__toolbar_quote', function() { jQuery('#'+jQuery(this).attr('rel')).wps_insertAtCaret('[quote]','[/quote]'); });
				jQuery('body').on('click', '.__wps__toolbar_code', function() { jQuery('#'+jQuery(this).attr('rel')).wps_insertAtCaret('[code]','[/code]'); });
				jQuery('body').on('click', '.__wps__toolbar_center', function() { jQuery('#'+jQuery(this).attr('rel')).wps_insertAtCaret('[center]','[/center]'); });
				jQuery('body').on('click', '.__wps__toolbar_ul_li', function() { jQuery('#'+jQuery(this).attr('rel')).wps_insertAtCaret('[li]','[/li]'); });
				jQuery('body').on('click', '.__wps__toolbar_url', function() { jQuery('#'+jQuery(this).attr('rel')).wps_insertAtCaret('[url=???]!!!','[/url]'); });

			}
		}

		jQuery.fn.extend({
		  wps_insertAtCaret: function(myValueStart, myValueEnd){
		  var obj;
		  if( typeof this[0].name !='undefined' ) obj = this[0];
		  else obj = this;
		  
		  var url = '';
		  if (myValueStart.indexOf("???") > 0) {
		  	var url=prompt(wps_forum_toolbar.bbcode_url,"");
		  	if (url==null) { url = ''; }
		  	if (url.indexOf("http") < 0) { url = 'http://' + url; }
		  }

		  var text = '';
		  if (myValueStart.indexOf("!!!") > 0) {
		  	var text=prompt(wps_forum_toolbar.bbcode_label,"");
		  	if (text==null) { text = ''; }
		  }

		  myValueStart = myValueStart.replace('???', url);
		  myValueStart = myValueStart.replace('!!!', text);

		  if (jQuery.browser.msie) {
		    obj.focus();
		    sel = document.selection.createRange();
		    sel.text = myValueStart + myValueEnd;
		    obj.focus();
		    }
		  else if (jQuery.browser.mozilla || jQuery.browser.webkit) {
		    var startPos = obj.selectionStart;
		    var endPos = obj.selectionEnd;
		    var scrollTop = obj.scrollTop;
		    var newPos = startPos + myValueStart.length + (endPos-startPos) + myValueEnd.length;
		    obj.value = obj.value.substring(0, startPos)+myValueStart+obj.value.substring(startPos,endPos)+myValueEnd+obj.value.substring(endPos,obj.value.length);
		    obj.focus();
		    obj.selectionStart = newPos;
		    obj.selectionEnd = newPos;
		    obj.scrollTop = scrollTop;
		  } else {
		    obj.value += myValueStart + myValueEnd;
		    obj.focus();
		   }
		 }
		})

	}

});

