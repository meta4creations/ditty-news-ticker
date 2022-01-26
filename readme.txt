=== Ditty (formerly Ditty News Ticker) ===
Contributors: metaphorcreations
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FUZKZGAJSBAE6
Tags: ticker, post ticker, news ticker, content aggregator, latest posts, live refresh, rotator, data rotator, lists, data, aggregator
Requires at least: 4.5
Tested up to: 5.9
Stable tag: 3.0.12
License: GPL2

Formerly Ditty News Ticker. Ditty is a multi-functional data display plugin.

== Description ==

Formerly Ditty News Ticker. Ditty is a multi-functional data display plugin. Easily render custom data feeds to your site through a customizable news ticker, list, or slider.

#### Item Types
* Default - Add custom text to your Ditty.
* WP Editor - Add custom text to your Ditty using the WP Editor.
* WP Posts Feed - Add your latest blog posts to your Ditty.

#### Display Types
* Ticker - Create a unique news ticker using the Ticker Display type. Control the direction, spacing, speed, styling and many other options.
* List - Create paged lists of your combined content. Multiple settings give you full control of the look and feel on your lists.

#### Paid Extensions
Additional plugins can be purchased to extend Ditty and it's functionality. Add more Item types, Display types, and utilities to enhance your Ditty. View all [**Extensions**](https://www.metaphorcreations.com/ditty/extensions/).

== Installation ==

1. Upload `ditty-news-ticker` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a Ditty by going to **Ditty > Add New**
4. Insert your Ditty by user the supplied shortcode, widget, or global render option.

[**View full help documentation.**](https://www.metaphorcreations.com/article/ditty/)

== Frequently Asked Questions ==

= Are there any settings I need to configure? =

Each individual Ditty has multiple settings to customize.

= I have added my shortcode but the Ditty is not displaying =

The most common cause for an unresponsive Ditty (when using scroll or rotate mode) is a javascript error on your site coming from another plugin or theme. Any type of javascript error will most likely kill any other javascript that is loaded after it. You will need to resolve any javascript errors before the Ditty will start render.

[**View full help documentation.**](http://www.dittynewsticker.com/documentation/)

== Screenshots ==

1. Ditty post settings
2. Ditty Items list
3. Ditty Item Types list
4. Default Item options
5. WP Editor Item options
6. WP Posts Feed (Lite) Item options
7. Ditty Display Types list
8. Ticker Display options
9. List Display preview
10. General Settings
11. Global Ditty Settings
12. Layout Default Settings
13. Layout Templates Settings
14. Display Templates Settings
15. Advanced Settings

== Changelog ==

= 3.0.12 =
* Modified custom display check when rendering Ditty
* Resolved current Item(s) bug in Ditty Editor
* Resolved current Item Type bug in Ditty Editor
* Modified Default Post Layout and increased version number
* Added init trigger for Ditty_Item_Editor_Panel js file
* Removed jQuery document ready in all js files
* Added link options to Posts Feed Lite
* Added content options to Posts Feed Lite
* Radio field update
* Asset loading optimization
* Added file field type
* Added item_value to layout tag filters
* Removed auto-draft checks
* Customized plugin updater file and references
* Resolved bug in extension updater

= 3.0.11 =
* Resolved bug in ditty_add_scripts function that was causing a javascript error
* Only possibly load cached ditty on live_updates
* Added option to disable fontawesome on front-end

= 3.0.10 =
* Resolved hammer warning
* Added Post Duplicator integration
* Added custom Display settings
* Ticker Display vertical scrolling updates
* dittyUpdateItems javascript bug fix

= 3.0.9 =
* Ditty editor Layout css update bug fix
* Added WPML functionality to Ditty post type
* Ticker Display vertical scrolling updates

= 3.0.8 =
* Minor bug fix in widget.php file
* Added legacy helper for old extension versions
* Added extension license error notices

= 3.0.7 =
* .ditty-item css updates
* Added wp_enqueue_script check to ensure wp_add_inline_script is only added once
* Removed ajax Ditty notification close
* Added php Ditty notification close
* Added Wrap Elements option to Ticker display

= 3.0.6 =
* Ditty Editor Items javascript helper update
* Minor update in legacy ditty-news-ticker.js file

= 3.0.5 =
* Added checks for auto-enabling Ditty News Ticker if old shortcode or function is used on front-end

= 3.0.4 =
* Removed trailing comma in class-ditty-extensions.php file

= 3.0.3 =
* Additional upgrade functionality updates

= 3.0.2 =
* Removed 3.0 upgrade redirect to info screen

= 3.0.1 =
* Upgrade script bug fix

= 3.0 =
* Ditty News Ticker posts have been moved to legacy code.
* Completely new Ditty post type and functionality.
* New Ditty Layout post type.
* New Ditty Display post type.
* New Settings page.
* Added global Ditty rendering.
* Added Layout and Display templates.
* New extensions page for licenses and extension settings.

= 2.3.12 =
* Bug fix in wpml-config.xml file.

= 2.3.11 =
* Updated wpml-config.xml file.

= 2.3.10 =
* Resolved new widgets page bug. Note, all tickers will show in list mode on the widgets page.

= 2.3.9 =
* Modified javascript to force ticker to resume scroll after clicking a link

= 2.3.8 =
* Removed string translation from news ticker post type slug

= 2.3.7 =
* Javascript bug fix
* Added scroll pause option for showing first tick on it

= 2.3.6 =
* Javascript bug fix
* Added scroll pause option for showing first tick on it

= 2.3.5 =
* Javascript updates to scroll functionality

= 2.3.4 =
* Javascript updates to scroll functionality
* Admin tick drag order updates

= 2.3.3 =
* Javascript updates to scroll functionality
* EDD Software Licensing updates

= 2.3.2 =
* Deprecated javascript updates

= 2.3.1 =
* Deprecated javascript updates

= 2.3 =
* Reworked scroll functionality for better performance

= 2.2.19 =
* Added aria-label to previous and next nav elements

= 2.2.18 =
* Javascript update for WP 5.5

= 2.2.17 =
* Bug fix with scrolling ticker

= 2.2.16 =
* Swapped play and pause button icons

= 2.2.15 =
* CSS Fix for list mode

= 2.2.14 =
* Resolved display issue with tickers using external fonts

= 2.2.13 =
* Resolved wp_editor bug when sorting

= 2.2.12 =
* Resolved javascript imagesloaded issue

= 2.2.11 =
* Resolved RTL, scrolling right issues

= 2.2.10 =
* Added settings option to make ticker posts private

= 2.2.9 =
* Updated error codes for license connections

= 2.2.8 =
* Resolved possible missing variable bug in grid display code

= 2.2.7 =
* Constant variable bug fix

= 2.2.6 =
* Resolved undefined variable bug in eddsl.php file

= 2.2.5 =
* Updated extension license check for multisite networks

= 2.2.4 =
* Bug fix

= 2.2.3 =
* Bug fix

= 2.2.2 =
* Bug fix

= 2.2 =
* Code cleanup and maintenance
* Directory structure reconfiguration

= 2.1.26 =
* RTL bug fix for scrolling right tickers

= 2.1.25 =
* Minor admin css update

= 2.1.24 =
* Added 'Reverse the order of the ticks' to the ticker Global Settings panel

= 2.1.23 =
* Escaped $_GET variables for additional security measures

= 2.1.22 =
* Modified how scripts are enqueued

= 2.1.21 =
* Fixed browser resize bug

= 2.1.20 =
* Fixed scroll left jquery bug

= 2.1.19 =
* Fixed wp_query bug when hide ticker option is enabled

= 2.1.18 =
* Fixed bug in extension license page

= 2.1.17 =
* Fixed widget ticker title bug.
* Added a centralized licensing system for all extensions. Extensions will be updated soon to utilize this feature.

= 2.1.16 =
* Minor javascript update for scrolling tickers.

= 2.1.15 =
* Resolved bug in ticker widget.

= 2.1.14 =
* Added shortcode option to reverse the tick order for rotating tickers. Use reverse="1".

= 2.1.13 =
* Added shortcode option to reverse the tick order. Use reverse="1".

= 2.1.12 =
* Resolved bug from last update

= 2.1.11 =
* Resolved custom capabilities bug

= 2.1.10 =
* Added option to hide ticker if no ticks exist
* Added option to hide widget if no ticks exist
* Admin css updates

= 2.1.9 =
* Bug fixes

= 2.1.8 =
* Grid bug fix from last update

= 2.1.7 =
* Removed mtphr_dnt_default_sanitized_tick filter
* Added mtphr_dnt_sanitized_tick filter
* Added mtphr_dnt_mixed_ticks_meta filter
* Added mtphr_dnt_mixed_tick_array filter
* Added mtphr_dnt_list_heading_class filter
* Added mtphr_dnt_list_item filter
* Added mtphr_dnt_list_item_class filter
* Updated mixed ticker function
* Modified default ticker data

= 2.1.6 =
* Added mtphr_dnt_default_sanitized_tick filter

= 2.1.5 =
* Added ditty_news_ticker function check in widgets.php file

= 2.1.4 =
* Removed testing code from last update

= 2.1.3 =
* Added check to ensure VC version is 5 or greater before adding VC shortcode

= 2.1.2 =
* Updated qtip scripts
* Updated paths to enqueued files

= 2.1.1 =
* Bug fix from last update
* Added custom News Tickers Settings capability for admins

= 2.1.0 =
* Added custom capabilities for the ditty_news_ticker post type and settings

= 2.0.18 =
* .mtphr-dnt-image-container css display update
* Metabox text update

= 2.0.17 =
* Image container css updates

= 2.0.16 =
* Image container css updates

= 2.0.15 =
* Tick width and height detection modification in jQuery script

= 2.0.14 =
* Ensured that imageloaded is enqueued when ditty-news-ticker.js is enqueued

= 2.0.13 =
* Bug fixes in updated jquery script
* CSS adjustments for better image rendering across browsers

= 2.0.12 =
* Added static image dimension options to MTPHR_DNT_Image class

= 2.0.11 =
* Updated mtphr_dnt_convert_links to fix latin character bug
* Removed console function in script

= 2.0.10 =
* Bug fix from last update
* MTPHR_DNT_Image container element update

= 2.0.9 =
* Updates to rotate mode script
* Scroll and rotate tickers now load on document ready
* Added imagesLoaded to scroll and rotate tickers
* Added 'nofollow' option to MTPHR_DNT_Image class
* Additional updates to MTPHR_DNT_Image class

= 2.0.8 =
* Bug fix in news ticker output
* Added MTPHR_DTN_Image class for use in extensions
* Added MTPHR_DNT_String_Replacement class for use in extensions
* Added checkboxes field for admin use

= 2.0.7 =
* Adjusted Custom CSS field display
* CSS updates

= 2.0.6 =
* Unyson page builder shortcode integration
* Visual Composer page builder shortcode integration

= 2.0.5 =
* Mixed ticker bug fix
* Dashboard CSS updates

= 2.0.4 =
* Added optional play/pause button for scroll and auto rotate mode
* Removed data-icon css from icon css file
* Small bug fix

= 2.0.3 =
* Fixed visual editor glitch from last update

= 2.0.2 =
* Updated metabox code for flexibility
* Updated some metabox filter names

= 2.0.1 =
* Customized bootstrap affix plugin due to conflicts within dashboard
* Added more social icons to icon font package

= 2.0.0 =
* Updated admin page layout
* Updated metaboxes for new admin page layout
* Newly added WYSIWYG editors now work without having to save your post
* Added javascript error check before saving dynamic fields
* Additional data passed through mtphr_dnt_ticker_class filter

= 1.5.8 =
* Updated widget class name

= 1.5.7 =
* Updated widget class

= 1.5.6 =
* Updated touchSwipe.js
* Fixed inactive links on mobile using touchSwipe

= 1.5.5 =
* Bug fix

= 1.5.4 =
* Modified data sanitization on tick content

= 1.5.3 =
* Modified default options for Rotate Settings
* Added option to disable touchswipe on touch devices

= 1.5.2 =
* Security update

= 1.5.1 =
* Script bug fix

= 1.5.0 =
* Re-organized files
* Added template files for easier customization
* Added shortcode & oEmbed parsing to ticks
* Added option to remove outer padding around grids
* Fixed add_query_arg() and remove_query_arg() usage

= 1.4.15 =
* Admin metabox bug fix

= 1.4.14 =
* Added Italian translation files

= 1.4.13 =
* Fixed metabox bug from last update

= 1.4.12 =
* Modified jQuery script to allow hidden tickers to function correctly after they become visible on screen
* Added the ability to add all ticks from a selected type to the mixed ticker

= 1.4.11 =
* Fixed bug in ticker script filter when using unique_ids

= 1.4.10 =
* Moved plugin files to GitHub

= 1.4.9 =
* Added Author support to post type

= 1.4.8 =
* Removed "data-icon" styles in icon font stylesheet
* Converted icon font characters to PUA
* Moved News Ticker title to "mtphr_dnt_before" action for easier customization

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
* Resolved undefined variable bug in eddsl.php file

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

Resolved bug in extension updater. Multiple other updates.