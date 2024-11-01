=== WP Auditor ===
Contributors: @betacore
Tags: pagespeed, 404, audit
Donate link: https://www.patreon.com/wpaudit
Requires at least: 5.2
Tested up to: 5.5
Requires PHP: 7
Stable tag: 2.1.33
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Run automated Google Pagespeed/Lighthouse audits and keep track of your 404 errors all in one plugin.

== Description ==
Run automated Google Pagespeed/Lighthouse audits and keep track of your 404 errors all in one plugin. 

= Automated Google Pagespeed =
A Pagespeed test can tell you a lot about your websites health. The Google Pagespeed Records is a Weekly Pagespeed check and is ran on our servers so your visitors won't notice it. If you like to read a full report on your website then click the Google Pagespeed Test button or activate the extra link in the page/post-edit list below the title.

= Track your 404 page hits =
Know where your website displays it's 404 errors. This tool logs them so you can take a look at it later. Fully adjustable on the settings page.

= Quick links for checking pagespeed of individual pages or posts =
We all know how painstaking it can be to copy and paste page url’s in Google Pagespeed’s form. This plugin adds two buttons to your WP-admin environment. One “Pagespeed Audit” button to the post/page list where you find all your pages or posts. And conveniently placed button under the publish area. This way you have the tools to ‘quickly check how fast the page is’ to your disposal. This is excellent for people who do SEO work and have to keep an eye out for pagespeed.

= Things it does: =
* When activated an automated weekly pagespeed check is ran in a way that does not affect your site speed
* Keep track of 404 page hits 
* Enable quick buttons for editors so they can tell how fast a page is from the page or post list

== Installation ==
1. Upload the unpacked folder to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Frequently Asked Questions ==
= How simple is it? =
Very simple!

== Screenshots ==
1. A nice list of pagespeed information is provided and grows over time.
2. Settings. There are some options to customise the experience like private 404 logging and the interval for logging or cleaning the logs table.
3. The dashboard. This will be more useful in the future.
4. A log of 404 page hits.
5. Test the pagespeed of individual pages or posts.

== Changelog ==
= 2.1.33 =
* Fixed a bug that when an error occurred and no api connection possible. Continued.
* Also fixed some things on the server side to catch some errors.

= 2.1.29 =
* Fixed a bug that when an error occurred and no api connection possible.

= 2.1.28 =
* Changed the register REST checkup time, less stress on the website...
* Added some info back to the Callback REST so that setting info can be updated at all times (why did I even take this out in the first place?!)
* Also checking the PHP version in the Callback REST
* Fixed a bug where registration didn't fire a callback and a task loop.

= 2.1.25 =
* Foreach error on an empty callback on pagespeed page
* Changed the registration at the API server a bit so that a callback is not mandatory
* Changed the registration checkup times to reflect better
* Notify that the plugin is uninstalled so removal of data can be planned

= 2.1.23 =
* Register errors on the API side and showing them on the plugin so action can be taken.
* Show the status of your account on the server. If there are too many errors the site will be disabled and not be ran trough Google Pagespeed anymore.
* Better error notifications and direct to support buttons.
* Added an invisible beta mode that makes it possible that I can connect a plugin on testing sites to the beta API (build) instead of the live one.
* Showing a html comment in the site footer code so I can check if a plugin is actually active and working like a charm. Users won't notice.

= 2.1.19 =
* Bugs fixed
* Sanitisation functions added
* Plugin ready! Woohoo!

= 2 =
* Testing on my own websites

= 1 =
* Start of building V2

= 0.9.3 =
* WP 5.4 update

= 0.9.2 =
* Small language bug fixed

= 0.9 =
* First live version. 

== Upgrade Notice ==

