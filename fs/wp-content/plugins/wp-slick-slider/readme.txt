=== WP Slick Slider ===
Contributors: casben79
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=79RLARW2VBPXU
Tags: cycle, slider, javascript slider, post type, customisable
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 1.8

Utilising the power of custom post types and Custom meta boxes, adds a powerful and customisable slider to your wordpress site.

== Description ==

WP Slick Slider was created as a powerful , customisable and editable jquery cycle plugin.

It uses A custom post type and taxonomy to create a slider, with almost unlimited options and support for multiple sliders on any page and full templating support, the options are endless.

It uses a Template tag:
`<?php if( function_exists( 'wp_slick_slider' ) ){ wp_slick_slider( 'slider-slug' ); } ?>`

And A Shortcode:
`[SlickSlider name="slider-slug"]`

If You Have Any Feedback Or Suggestions, Feel Free To Drop Me A Line At <a href="http://www.tigerstrikemedia.com">Tiger Strike Media</a>

== Installation ==

Upload The wp-slick-slider folder to the /wp-content/plugins/ directory on your server
Activate the plugin in the admin interface
insert the Template Tag `<?php if( function_exists( 'wp_slick_slider' ) ){ wp_slick_slider( 'slider-slug' ); } ?>` Or The Shortcode `[SlickSlider name="slider-slug"]` wherever you want the slider to display.

== Upgrade Notice ==

= 1.8 =
Templating Fix

= 1.7 =
Maintenance release. WordPress 3.8.1 and PHP 5.4 Compatability

= 1.6 =
Adds The Ability To Use Featured Images As Pager Links

= 1.5 =
Fixed A Bug In Full-Width Image Template.

= 1.4 =
Updated Timthumb to 2.8.10

= 1.3 =
Bugfix - Compatability With Various Plugins. 

= 1.2 =
Bugfix - Now Saving Options Correctly

= 1.1 =
Added Slide Ordering

= 1.0 =
Major Update - Timthumb to 2.8.5 - Full Templating Support

= 0.9 =
Minor Update.

= 0.8 =
Update To Timthumb Again.

= 0.7 =
Added a total width option.

= 0.6 =
Update The UI Interface, Timthumb And Various Minor Updates

= 0.4 =
Minor Bugfixes See Changelog For Added Functionality

= 0.3 =
General Bughunt + Custom Fields And Previous / Next Arrows

= 0.2 =
JS Bugfix. And Added Ability To Order Slides

= 0.1 =
Initial Release

== Frequently Asked Questions ==

No FAQ's Just Yet. <a href="http://tigerstrikemedia.com/contact-us">Send Me One!</a>

== Templating ==

WP Slick Slider 1.0+ has full support for templating, If are not comfortable working on minor edits to the code in your site, this feature will not be for you.

To activate templating, simply create a folder called "wpss" in your theme folder.

There are 4 types of slides available, these are fully individually templateable, you can even edit the output for a slide type of a specific slide. 

To activate templating:

1. Create a file in your theme "wpss"
2. copy the files from wp-slick-slider/templates/ to the new theme folder.
3. Edit Away!

You can edit the templates for an individual slider using the following guide:
1. copy the files from wp-slick-slider/templates/ to the wpss folder.
2. Rename The required file to: {$slug}_{$type}.php. EG: If you have a slider called cool_slider and want to edit the output for the Full Width Image type,the filename would be: cool_slider_full-width-image.php.
3. Edit Away.

If Any bugs are found in this system or anyone has any questions, Please feel free to <a href="http://tigerstrikemedia.com/contact-us">drop me a line</a>: 

== Changelog ==

= 1.8 =
* Fixed Output div nesting problem.

= 1.7 =
* Fixed: PHP Strict Notices in PHP 5.4
* Fixed: Wordpress 3.8 admin UI compatability
* Updated: Timthumb to the latest version
* Various other bugfixes.

= 1.6 =
Added The Ability To Use The Featured Images as pager links. Props to Israel Carberry.

= 1.5 =
Fixed A Bug In Full-Width Image Template. - Thanks to Raj for spotting that one

= 1.4 =
Updated Timthumb to version 2.8.10

= 1.3 =
* Bugfix - Plugin Now Supports Any Other Plugin That Inserts Code Automatically After The Content While Using The Shortcode For WP Slick Slider.

= 1.2 =
* Bugfix - Now Saving Options And Label Mismatch Corrected.

= 1.1 =
* Added The Ability To Order The Slides

= 1.0 =
* Major Rewrite - Base Code Refactored Into a Class,
* Default Option bugfixes
* Now Fully supporting the quick slide add functionality in the post editor.
* **NEW** Full Templating Support

= 0.9 =
Minor Update To Keep Inline With Wordpress Regulations.

= 0.8 =
Update To Timthumb Again.

= 0.7 = 
* Added A Total Width Option

= 0.6 =
* Updated Timthumb to account for recent security concerns.
* Updated UI to be a little more friendly

= 0.5 =
* Various Updates

= 0.4 =
* **New** : Pause And Resume Links
* **New** : Ability To Use Or Not Use The Previous/Next Links, Pause And Resume Links and Pager Functionality
* **New** : I18n - If Someone wants to translate the plugin admin options, let me know.
* Bugfix - Saving Options Now Working Correctly

= 0.3 =
* **New** : Previous And Next Arrows On Sliders
* **New** : Custom Fields On Write Screen
* General Bugfix , Disable CSS / JS now working correctly.

= 0.2 =
* Bugfix The Javascript Initialisitiation on certain server setups
* NEW: Added The Ability To Order The Slides.

= 0.1 =
* Initial release