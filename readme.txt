=== PayPal Security ===
Contributors: angelleye
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SG9SQU2GBXJNA
Tags: paypal, security, payments, standard, buttons, subscriptions, hosted
Requires at least: 3.8
Tested up to: 4.9.8
Stable tag: 1.0.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Developed by an Ace Certified PayPal Developer, official PayPal Partner, PayPal Ambassador, and 3-time PayPal Star Developer Award Winner.

== Description ==

= Introduction =

Scans all site content and returns a report of any insecure PayPal buttons that are found as well as a recommendation on securing your PayPal buttons.

= Localization =
PayPal Security for WordPress was developed with localization in mind and is ready for translation.

If you're interested in helping translate please [let us know](http://www.angelleye.com/contact-us/)!

= Get Involved =
Developers can contribute to the source code on the [PayPal Security for WordPress Git repository on BitBucket](https://github.com/angelleye/paypal-security-for-wordpress).

== Installation ==

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type PayPal Security and click Search Plugins. Once you've found our plugin you can view details about it such as the the rating and description. Most importantly, of course, you can install it by simply clicking Install Now.

= Manual Installation =

1. Unzip the files and upload the folder into your plugins folder (/wp-content/plugins/) overwriting older versions if they exist
2. Activate the plugin in your WordPress admin area.

== Screenshots ==

1. Choose which content types you would like to scan and click the scan button.
2. Progress bar displayed during security scan.
3. Basic security scan summary and site security score.
4. Individual PayPal button security analysis.
5. Button code preview.

== Frequently Asked Questions ==

= Where do I find the PayPal Security scanner after it has been installed and activated? =

* Login to your WordPress admin panel and go to Tools -> PayPal Security.

= What does this security scanner do, exactly? =

* The PayPal Security plugin scans all of your site's content searching for PayPal HTML button code.
* Any button code that is found is analyzed for potential security risks, and the individual button security details are presented in a detailed report when the scanner is complete.
* A general recommendation is also provided for how to secure any insecure buttons the scanner may find on your site.

= Does the security scanner include pages / posts in draft mode? =

* As of now, drafts are not included in the security scan.  We are working to improve this in a future release.

== Changelog ==

= 1.0.4 - 07.09.2019 =
* Tweak - Minor adjustment to JavaScript.

= 1.0.3 - 08.18.2018 =
* [PAYP-1](https://github.com/angelleye/paypal-security-for-wordpress/pull/40) - Tweak - Data sanitization.
* [PAYP-2](https://github.com/angelleye/paypal-security-for-wordpress/pull/41) - Fix - Resolves an undefined error with the scanner results.

= 1.0.2 - 01.09.2016 =
* Tweak - Adjusts Google Prettify. ([#38](https://github.com/angelleye/paypal-security-for-wordpress/issues/38))

= 1.0.1 - 01.08.2016 =
* Tweak - Includes draft content in the security scan. ([#30](https://github.com/angelleye/paypal-security-for-wordpress/issues/30))
* Tweak - Adds scan / button details into scan history. ([#31](https://github.com/angelleye/paypal-security-for-wordpress/issues/31))
* Tweak - General code improvements and bug fixes.

= 1.0.0 =
* Initial stable release.