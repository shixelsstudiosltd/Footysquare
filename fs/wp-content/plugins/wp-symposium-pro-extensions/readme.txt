=== WP Symposium Pro Extensions plugin ===
Author: WP Symposium Pro
Contributors: Simon Goodchild
Donate link: http://www.wpsymposiumpro.com
Link: http://www.wpsymposiumpro.com
Tags: wp symposium pro, social network, social networking, social media, wpsymposium pro, wp-symposium, wp symposium, symposium
Requires at least: 3.0
Tested up to: 4.1
License: GPLv2 or later
Stable tag: 14.12.2

Extends WP Symposium Pro.

== Description ==

Adds many extensions and features to WP Symposium Pro.

== Installation ==

Go to plugins in your admin dashboard and click Add New. Choose upload and select the wp-symposium-pro-extensions.zip file.

== Frequently Asked Questions ==

The best source of news and information is the WP Symposium Pro blog at http://www.wpsymposiumpro.com/blog, it's constantly kept up-to-date with news, updates, articles and tips.

For more FAQs, please visit http://www.wpsymposiumpro.com/frequently-asked-questions

== Screenshots ==

The best way to see it in action, and try it out for free, is visit http://www.wpsymposiumpro.com !

== Changelog ==

14.12.2  New extension: Show preview of web links in activity, including page title, description and first image on the page (see book)
         Profile Extensions: New option "age" to display date field as an age (see book)
         Private Messages: Added search facility
         Private Messages: Change Quick Start button to include search
         Messages: Renamed duplicate private_msg to private_reply_check_msg ("Only share reply with %s")
         Directory: Changed default view to text box search (use mode="list" for dropdown version)
         Likes/dislikes: Activity posts about likes and dislikes no longer added (alert still generated)
         Calendars: added thumbnails and titles as shortcode options

14.12.1  Private Messages: can now setup site to allow messages to all users (even if not friends)
         Groups: Updated the default layout for the Quick Start "Add Groups" button.
                 See the WPS book Groups chapter for more information, under "Shortcodes (for Group page)" in the index.
         Groups: Removed CSS from wps_group_image to allow more precise use (see Groups chapter in book for how to build a Group Page)
         Groups: Fixed bug where group admin couldn't edit group settings, only when site admin
         Rewards: Added slug to [wps-reward] to show the points only for that specific reward, otherwise total points shown

14.12    Lounge: New option for [wps-lounge]: 'please_wait' (defaults to 'Posting %s, please wait...')
         Alerts: Added flag_src to [wps-alerts-mail] to replace icon, use relative or absolute URL
         Profile Security: Site administrators can now always view a member profile (for security/reports sometimes necessary)
         Forums: Fixed bug if only running core that caused Forum Title to be duplicated
         Forums: When viewing forum (list of posts) subscribed-to posts have email icon after them
         Forum Subscriptions: new option to always send alert/email all members when new topic added to forum (no opt-out). Activate in WPS Pro->Forum Setup->Edit.
         Forum Subscriptions: click on subscription icon to unsubscribe when viewing forum posts

14.11    New extension: Let members save activity posts as favourites, using the [wps-favorites] shortcode to display them
         Messages: can now show new message(s) alert as a flag with count with [wps-alerts-mail]
         Messages: can now mark all as read, added mark_all_read_text to [wps-mail]
         Gallery: Added slideshow feature, new options for [wps-gallery]:
                    show_slideshow (whether default view of album is as slideshow instead of thumbnails/comments, defaults to 0)
                    slideshow_link and slideshow_link_hide (text of links). Set slideshow_link to '' to disable slideshow.
         Login & Register: Added name option to set whether users enter first/family name when registering, default 1.
         Login & Register: Added password option to set whether users enter a password when registering, default 0 (sent via email).
         Login & Register: Added registration_url to redirect users after registration
         Login & Register: Added register_auto to automatically login after registration, default 0
         Groups: Fixed single post view and added back_to to [wps-group-activity] for link back to group, default "Back to %s..."
         Groups: Added header_text as option to [wps-groups], default '<h2>Groups</h2>'
         Groups: New shortcode [wps-my-groups] to display groups current user is a member of, similar parameters as [wps-groups]

14.10.3  Messages: Added add_recipients and cancel_label options to [wps-mail-recipients], set add_recipients=0 to remove
         Messages: List now ordered by unread, last active, created so active conversations go to the top of the list
         Activity: Fix to user_id parameter of [wps-activity]

14.10.2  Gallery: Added edit_text and delete_text to [wps-gallery]

14.10.1  Groups: Can now upload a group image to show on the group page
         Groups: Deleting group now deletes group membership
         Groups: New shortcode [wps-group-image] to show group image
         Groups: Default changed for [wps-group-post]. before='<div style="clear:both">' and after = '</div>'
         Groups: Added width as parameter, set to 0 to hide (replaced redundant avatar_size)
         Profile Extension (YouTube): Fixed broken link

14.10    Login Redirect: renamed as Login and Register
         Login Redirect: Login form upgraded and Lost Password now available
         Login Redirect: Powerful registration form, with optional include of profile extensions (text, textarea, list and divider types)
         Likes/Dislikes: Rewards type added for likes and dislikes
         Forums: Icon now shown after forum names with [wps-forums] shortcode (if subscribed)
         Forum Subscriptions: Can now set new users to automatically subscribe to forums (via WPS Pro->All Forums)
         Forum Security: Now applies to subscription links/buttons
         Mail: Renamed to Messages (to avoid confusion with email and email notifications/alerts)
         Mail: Added Setup->Mail - the Mail page must be selected with this new option
         Mail: Can now delete mail items (icon beside mail message)
         Mail: Added show_hidden_text and hide_hidden_text to [wps-mail]
         Groups: Can now be set as private and join requests must be accepted by group admin
         Groups: Added private_label to [wps-group-edit]
         Groups: Can now remove users from a group
         Groups: When deleting a group, confirmation is asked for
         Groups: Added orderby (active [default], created, title), order (ASC or DESC [default]).
         Groups: Changed date_label default to 'Last active %s ago'
         Groups: Changed default of show_date to 1 (change to 0 to hide last active text)
         Groups: Added text_private to [wps-group-join-button]
         Galleries: Set user_id = "all" with [wps-gallery-grid] and [wps-gallery-list] to show all users galleries
         Profile Extensions (YouTube): can now set height to auto (responsive)
         Profile Extensions (Divider): new type to display title and/or any content on Edit Profile page
         Default Friends: Can now make friends with all existing users via WPS Setup->Default Friends
         Calendar: Added left, right and today as options to [wps-calendar]

14.9     New Extension: Like and Dislike activity posts/comments, with configuration options
         New Extension: Gallery - allows users to create galleries, and upload images/documents
         Forum Security: Added comments as set of permissions, to restrict roles that can comment in a forum

14.8     Mail: New shortcode [wps-mail-to-user] to add "mail this member" button to profile pages
         Mail: New to_label option for [wps-mail-post] if mail page shown after using [wps-mail-to-user] shortcode
         Directory: Added quick_select and placeholder to [wps-directory-search]
         Directory: To set width of drop down, use .wps_directory_search_entry (min-width, etc)
         Directory: Fix to advanced search where database prefix is not wp_
         Forum Security: Using new user roles for deleting posts/comments now works correctly
         Forum Security: New "Forum Administration" screen, via Forum Setup to manage all forums at once

14.7.18  Profile Extensions: Fixed width and height for YouTube extensions
         Rewards: Fixed issue with badges always showing as achieved
         Directory: Users who existed before WPS was added are now shown on directory search

14.7.5   Directory search: Added show_user_login option to [wps-directory-search] to exclude user login from dropdown (default 1)
         Rewards: Added new reward/badge for accepted friendship requests
         Rewards: Added new reward/badge for new WordPress blog posts
         Rewards: Rewards for "count", if using opacity, slowly appear as progress is made
         Rewards: [wps-reward] output now includes comma to seperate thousands (eg. 5,000 not 5000)

14.7.4   Forum Toolbar WYSIWYG editor now uses iframe, to avoid possible browser page-down on space bar press

14.7.3   Release to match core version
14.7.2   Extensions plugin no longer checks that Core plugin is the same version due to arising issues

14.7.1   Extensions plugin will now check that Core plugin is the same version

14.7     New extension: User Lists (create lists of friends to share activity with)
         New extension: Calendars (create site calendars, selecting which roles can view/add events)

         Forum subscriptions: "Receive email when new comments are added" now checked by default
         Forum search: New option for [wps-forum-search-results], show_forum (default to "in %s", set to "" to not show)

         Directory: New option for layout to [wps-directory], can be fluid or list, default list
         Directory: New option to include friendship buttons, include_friendship_action, for [wps-directory]

         Profile Extensions: New option label_prefix to [wps-extended] to add extension title as a prefix

         Show Posts: New option for [wps-show-posts], show_title, set to 0 to hide post title

14.6.20  Profile Security
         A new extension that will allow users to fine-tune privacy for their profile, activity and visibility on the site.
         
         Profile Extensions
         Two new types: image and youtube
         Textarea fields now honour line breaks (http://www.wpsymposiumpro.com/got-an-idea/extension-text-field-paragraphs-return)
         
         Forum Extensions
         Repeat forum extension(s) for re-editing when adding a forum comment (WPS Pro->Forum Extensions)

14.6.11  Rewards
         Added opacity and size to [wps-badge]
         Badges can now be awarded for doing something 'x' number of times
         Changed code, which means rewards must be refreshed, please see http://xxxxx

         Forum Roles Security
         Added option to ignore timeout value on forum by role (WPS Pro->Forum Setup->Edit Forum)

         Forum Extensions
         Can now be made mandatory (WPS Pro->Forum Extensions)

         Profile Extensions
         Set extensions admin only, so can only be edited by site admins (perhaps to label users?)
         
14.6.8   Rewards
         Added: New Extension to add rewards to your site (see http://www.wpsymposiumpro.com/plugins/rewards)

         Profile Extensions >
         Fixed: drop-down not working on Edit Profile or Member Directory
         Fixed: numeric order of extensions on Edit Profile/Member Directory when exceeds 9 (now numerical order)

         Forum Extensions >
         Added: Can now add to forum posts list

         Forum Security >
         Fixed: bug when moving topics between forums

14.05.25 First combined Extensions plugin.

== Upgrade Notice ==

Latest news and information at http://www.wpsymposiumpro.com/blog.
