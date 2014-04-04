=== Ditty News Ticker ===
Contributors: metaphorcreations
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FUZKZGAJSBAE6
Tags: ticker, news, news ticker, rotator, data rotator, lists, data
Requires at least: 3.2
Tested up to: 3.8.1
Stable tag: /trunk/
License: GPL2

Ditty News Ticker is a multi-functional data display plugin.

== Description ==

Ditty News Ticker is a multi-functional data display plugin. Easily add custom news tickers to your site either through shortcodes, direct functions, or in a custom Ditty News Ticker Widget.

#### There are 3 default ticker modes

* **Scroll Mode** - Scroll the ticker data left, right, up or down
* **Rotate Mode** - Rotate through the ticker data
* **List Mode** - Display your ticker data in a list

[**View samples of each mode.**](http://dittynewsticker.com/ticker-modes/)

#### Extensions
Ditty News Ticker is built to easily be extended to add extra Ticker Types and extra Ticker Modes.
To see a list of all extensions, visit the [**extensions page**](http://dittynewsticker.com/extensions/).

<img src="http://metaphorcreations.com/data/wpml-ready-badge.png" />

== Installation ==

1. Upload `ditty-news-ticker` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create tickers by going to **News Tickers > Add New**
4. Insert your tickers by copying and pasting the provided shortcode into another post.
5. Optionally, insert your tickers by copying and pasting the direct function code directly into your theme or plugin.

[**View full help documentation.**](http://dittynewsticker.com/mc/ditty-news-ticker-doc/)

== Frequently Asked Questions ==

= Are there any settings I need to configure? =

Each individual Ticker post has multiple settings to customize.

[**View full help documentation.**](http://dittynewsticker.com/mc/ditty-news-ticker-doc/)

== Screenshots ==

1. Default Ticker Type options
2. Scroll Mode options
3. Rotate Mode options
4. List Mode options

== Changelog ==

= 1.4.7 =
* Fixed bug in edit page type & mode filters
* CSS adjustments for rotate buttons

= 1.4.6 =
* Added plugin activation/de-activation to flush rewrite rules
* Moved admin files into separate admin directories
* Set file permissions on fontastic files

= 1.4.5 =
* Removed metaboxer code & files, which are no longer used
* Merged the metabox list jQuery functionality
* Removed ajax.php file
* Removed image navigation & buttons. Replaced with icon fonts
* Added type & mode filters to edit screen
* Added option to add aquick edit link to tickers for admins/editors

= 1.4.4 =
* Made ditty_new_ticker post type public
* Included ditty_new_ticker post type in nav menus
* Filtered single post content to display the ticker on ditty_new_ticker single posts
* Added additional metabox jquery for lists and sortables

= 1.4.3 =
* Fixed bug in jQuery setup where extra characters where being written

= 1.4.2 =
* Added WPML Support
* Fixed jquery script issue for multiple same tickers with unique ids

= 1.4.1 =
* Added additional jQuery triggers and listeners
* Fixed force line break issue

= 1.4.0 =
* Added grid display funcitonality
* Added page list functionality to lists
* Updated general settings code
* Updated metabox code
* Added mtphr_dnt_contents_before & mtphr_dnt_contents_after actions
* Moved rotate direction nav to mtphr_dnt_contents_after filter
* Moved rotate control nav to mtphr_dnt_after filter
* Moved global data mtphr_dnt_after filter
* General code cleanup
* Additional admin css
* Added mtphr_dnt_settings_submit_button filter to settings submit button
* Added $mtphr_dnt_ticker_types global to front end
* Added Arabic language
* Added mtphr_dnt_after_load jQuery trigger
* Added mtphr_dnt_before_change jQuery trigger
* Added mtphr_dnt_after_change jQuery trigger
* Added mtphr_dnt_resize jQuery listener

= 1.3.5 =
* Updated menu icon to icon font

= 1.3.4 =
* Removed type & mode selections from accidentally showing up in other post types

= 1.3.3 =
* Moved type & mode selections out of metaboxes
* Added a default tick to the mixed type

= 1.3.2 =
* Removed console.log from script-admin.js

= 1.3.1 =
* Big fix from last update

= 1.3.0 =
* Organized code
* Added "Mixed" type to display tickers mixed with multiple ticker types
* Modified 'after_load', 'before_change', 'after_change' filters for DNT class script
* Added code to remove duplicate tickers

= 1.2.2 =
* Added option to force line breaks on carriage returns.

= 1.2.1 =
* Modifed to allow multiline text
* Updated custom css field to css editor

= 1.2.0 =
* Modified ditty-news-ticker js class due to issues with certain versions of jQuery.
* Changed outerWidth() to with().
* Changed outherHeight() to height().

= 1.1.9 =
* Fixed touchSwipe error that was occurring in Firefox.
* Fixed undefined variable error in functions.php.
* Updated metaboxer files.
* Upated "Show first tick on init" code for better functionality.

= 1.1.8 =
* Added mobile swipe support for rotate mode.
* Added a hidden tick "offset" parameter.
* Added setting to display first scroll tick on init.

= 1.1.7 =
* Modified structure & CSS to move the rotate navigation up a level.

= 1.1.6 =
* Fixed pause on hover functionality.

= 1.1.5 =
* Modified jQuery ticker class loading.
* Fixed load_plugin_textdomain setup.
* Added wysiwyg editor option back.

= 1.1.4 =
* Added nofollow to navigational links.

= 1.1.3 =
* Added option to use visual editors for tick content (on settings page).
* Adjusted DNT jQuery class. Converted height() & width() to outerHeight() & outerWidth()

= 1.1.2 =
* Reworked scroll mode to function better in IE.
* Added a global offset to scrolling and rotating ticks to are hidden.
* Moved rotating tick code into .js file.
* Added css for max-image: 100%.
* Converted .clearfix class to custom .mtrph-dnt-clearfix to avoid conflicts.

= 1.1.1 =
* Minor fix.

= 1.1.0 =
* Added "width" override for scrolling tickers back in... whoops!

= 1.0.9 =
* Added a checkbox to set links as rel="nofollow".
* Updated vertical scrolling for responsive sites.
* Removed "width" override for scrolling tickers.
* Added new ticker classes.

= 1.0.8 =
* Fixed bug in rotator mode with "pause on hover" activated.
* jQuery now loading on (window).load() instead of (document).ready(). Fixes scrolling issue in Safari.
* Added .clearfix to .mtphr-dnt-wrapper.
* Specifically set .mtphr-dnt-tick.clearfix to display:none to override css in certain themes.
* Added filters to jQuery class callbacks.
* Added ticker ID to jQuery class vars.
* Now resizing rotator ticker height on window resize.

= 1.0.7 =
* Fixed jQuery issue in Firefox when adding new ticks.

= 1.0.6 =
* Updated settings scripts for extensibility.
* Minor update to metaboxer css.

= 1.0.5 =
* Updated metaboxer scripts.
* Updated direct function output.
* Updated ticker types link.
* Fixed rotator numbers padding.
* Fixed limited number of DNT in widget drop down issue.

= 1.0.4 =
* Added 'languages' folder for localization.
* Added an 'in_widget' attribute to tickers displayed in widgets.
* Updated 'sort' metaboxer code.

= 1.0.3 =
* Modified post_updated message.
* Add a ticker (auto) width override setting.

= 1.0.2 =
* Added 'mtphr_dnt_tick_before' and 'mtphr_dnt_tick_after' actions.
* Fixed error in 'rotate_scroll_up' script.
* Updated Metabox scripts.

= 1.0.1 =
* Minor code update.

= 1.0.0 =
* Initial upload of Ditty News Ticker - a multi-functional data display plugin.

== Upgrade Notice ==

Fixed bug in edit page type & mode filters. CSS adjustments for rotate buttons