/**
 * AJAX File Upload
 * http://github.com/davgothic/AjaxFileUpload
 * 
 * Copyright (c) 2010-2013 David Hancock (http://davidhancock.co)
 *
 * Thanks to Steven Barnett for his generous contributions
 *
 * Licensed under the MIT license ( http://www.opensource.org/licenses/MIT )
 */

;(function(jQuery) {
	jQuery.fn.AjaxFileUpload = function(options) {
		
		var defaults = {
			action:     "/fs/wp-content/themes/footysquare/upload.php",
			onChange:   function(filename) {},
			onSubmit:   function(filename) {},
			onComplete: function(filename, response) {}
		},
		settings = jQuery.extend({}, defaults, options),
		randomId = (function() {
			var id = 0;
			return function () {
				return "_AjaxFileUpload" + id++;
			};
		})();
		
		return this.each(function() {
			var jQuerythis = jQuery(this);
			if (jQuerythis.is("input") && jQuerythis.attr("type") === "file") {
				jQuerythis.bind("change", onChange);
			}
		});
		
		function onChange(e) {
			var jQueryelement = jQuery(e.target),
				id       = jQueryelement.attr('id'),
				jQueryclone   = jQueryelement.removeAttr('id').clone().attr('id', id).AjaxFileUpload(options),
				filename = jQueryelement.val().replace(/.*(\/|\\)/, ""),
				iframe   = createIframe(),
				form     = createForm(iframe);

			// We append a clone since the original input will be destroyed
			jQueryclone.insertBefore(jQueryelement);

			settings.onChange.call(jQueryclone[0], filename);

			iframe.bind("load", {element: jQueryclone, form: form, filename: filename}, onComplete);
			
			form.append(jQueryelement).bind("submit", {element: jQueryclone, iframe: iframe, filename: filename}, onSubmit).submit();
		}
		
		function onSubmit(e) {
			var data = settings.onSubmit.call(e.data.element, e.data.filename);

			// If false cancel the submission
			if (data === false) {
				// Remove the temporary form and iframe
				jQuery(e.target).remove();
				e.data.iframe.remove();
				return false;
			} else {
				// Else, append additional inputs
				for (var variable in data) {
					jQuery("<input />")
						.attr("type", "hidden")
						.attr("name", variable)
						.val(data[variable])
						.appendTo(e.target);
				}
			}
		}
		
		function onComplete (e) {
			var jQueryiframe  = jQuery(e.target),
				doc      = (jQueryiframe[0].contentWindow || jQueryiframe[0].contentDocument).document,
				response = doc.body.innerHTML;

			if (response) {
				response = jQuery.parseJSON(response);
			} else {
				response = {};
			}

			settings.onComplete.call(e.data.element, e.data.filename, response);
			
			// Remove the temporary form and iframe
			e.data.form.remove();
			jQueryiframe.remove();
		}

		function createIframe() {
			var id = randomId();

			// The iframe must be appended as a string otherwise IE7 will pop up the response in a new window
			// http://stackoverflow.com/a/6222471/268669
			jQuery("body")
				.append('<iframe src="javascript:false;" name="' + id + '" id="' + id + '" style="display: none;"></iframe>');

			return jQuery('#' + id);
		}
		
		function createForm(iframe) {
			return jQuery("<form />")
				.attr({
					method: "post",
					action: settings.action,
					enctype: "multipart/form-data",
					target: iframe[0].name
				})
				.hide()
				.appendTo("body");
		}
	};
})(jQuery);


