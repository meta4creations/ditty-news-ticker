=== Ditty News Ticker ===
Contributors: metaphorcreations
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FUZKZGAJSBAE6
Tags: ticker, news, news ticker, rotator, data rotator, lists, data
Requires at least: 3.2
Tested up to: 3.5.1
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

= 1.1.6 =
Fixed pause on hover functionality.

= 1.1.5 =
Modified jQuery ticker class loading. Fixed load_plugin_textdomain setup. Added wysiwyg editor option back.

= 1.1.4 =
Added nofollow to navigational links.

= 1.1.3 =
Added option to use visual editors for tick content (on settings page). Adjusted DNT jQuery class. Converted height() & width() to outerHeight() & outerWidth().

= 1.1.2 =
Reworked scroll mode to function better in IE. Added a global offset to scrolling and rotating ticks to are hidden. Moved rotating tick code into .js file. Added css for max-image: 100%. Converted .clearfix class to custom .mtrph-dnt-clearfix to avoid conflicts.

= 1.1.1 =
Minor fix.

= 1.1.0 =
Added "width" override for scrolling tickers back in... whoops!

= 1.0.9 =
Added a checkbox to set links as rel="nofollow". Updated vertical scrolling for responsive sites. Removed "width" override for scrolling tickers. Added new ticker classes.

= 1.0.8 =
Fixed bug in rotator mode with "pause on hover" activated. jQuery now loading on (window).load() instead of (document).ready(). Fixes scrolling issue in Safari. Added .clearfix to .mtphr-dnt-wrapper. Specifically set .mtphr-dnt-tick.clearfix to display:none to override css in certain themes. Added filters to jQuery class callbacks. Added ticker ID to jQuery class vars. Now resizing rotator ticker height on window resize.

= 1.0.7 =
Fixed jQuery issue in Firefox when adding new ticks.

= 1.0.6 =
Updated settings scripts for extensibility. Minor update to metaboxer css.

= 1.0.5 =
Updated metaboxer scripts. Updated direct function output. Updated ticker types link. Fixed rotator numbers padding. Fixed limited number of DNT in widget drop down issue.

= 1.0.4 =
Added 'languages' folder for localization. Added an 'in_widget' attribute to tickers displayed in widgets. Updated 'sort' metaboxer code.

= 1.0.3 =
Updates.

= 1.0.2 =
Added additional actions along with some code updates.

= 1.0.1 =
Minor code update.

= 1.0.0 =
Create a multi-functional data display plugin.

