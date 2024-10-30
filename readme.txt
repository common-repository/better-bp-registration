=== BuddyPress Better Registration ===
Contributors: sooskriszta, webforza
Author URL: https://profiles.wordpress.org/sooskriszta#content-plugins
Donate link: http://tinyurl.com/oc2pspp
Tags: BuddyPress, BuddyPress Registration, BuddyPress Activation, Social Networking, Community, Registration, Account Activation
Requires at least: 4.5.3
Tested up to: 4.9.6
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Make the registration process REALLY simple - reduce user resistance and increase community engagement.

== Description ==

This plugin replaces the standard BuddyPress registration process. The new registration process is simpler, easier and more convenient for users, increasing their willingness to register. Practically all the new process asks users to provide is their email.

Once users have registered for an account and want to activate the account, the new process guides the new member through a multi-stage activation process, each stage of which can be skipped. Research shows that since the member has already invested the time and effort to activate account, he or she is likely to complete most, if not all steps.

Activation steps include providing a profile photo, joining groups, and finding friends! Research has shown that profiles with photos increase community engagement, as do popular groups and interconnections between members.

= Now compatible with WP Social Login =
[WP Social Login](https://wordpress.org/plugins/wordpress-social-login/) enables your users to register/login using their existing social account IDs such as Facebook or Google.

If WP Social Login is enabled (and properly configured), then BP Better Registration gets the basic details and profile photo from the social network used for registration.

To make the most of WP Social Login compatibility, don't forget to:
1. Visit `WordPress Admin > Settings > WP Social Login > {Plug icon}`, and enable BuddyPress.
2. Visit `WordPress Admin > Settings > WP Social Login > BuddyPress tab`, and enable profile mapping, and thereafter, map Social Network fields to BuddyPress x-Profile fields.

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'BP Better Registration'
3. Install and Activate BP Better Registration from your Plugins page.

= From WordPress.org =

1. Download BP Better Registration.
2. Upload the 'better-bp-registration' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate BP Better Registration from your Plugins page.

== Screenshots ==

1. To register, the visitor has to provide ONLY his/her email address.
2. A time-sensitive email is sent to the email address, and can be resent.
3. The email contains a time-sensitive activation link on which the visitor must click to activate membership.
4. On clicking the link, the member arrives at account activation page. Here, (s)he is asked to provide basic details such as name and password.
5. The member can upload or snap a profile photo. Or skip this step.
6. Profile photo editing i.e. panning and zooming is available.
7. Member can complete the X-Profile fields. Or skip this step.
8. Member can find his/her friends by name or email, and add them as Friend within the online community. Or skip the step.

== Frequently Asked Questions ==

= Where are the settings? =

This plugin is pretty much "plug-and-play", and has no settings page. The idea is to "keep it simple".

= When I click on the activation link in email, the resulting page asks for an activation key, and putting in the key doesn't work either. =

This is a BuddyPress "security feature". Nothing to do with this plugin.

What happens is that if a link is resused, or goes stale, then BuddyPress asks for an activation key. But of course, since the activation key has already either been used or expired, therefore it is invalid. Catch 22!

The solution is to ask the webmaster of the site to delete your account, so that you can start the registration process again.

= Can you make it compatible to X? =

I will not put in any work on my side to make it compatible with any paid or non-OpenSource plugins. You should ask the author of that plugin to do so.

If, on the other hand, "X" is a plugin listed here on the WordPress directory AND doesn't require subscribing or connecting to external services, please feel free to make a feature request in the Support seection.

== Changelog ==

= 1.6 =
* Fixed critical error on some server configs.

= 1.5 =
* Added WordPress 4.9.6 compatibility.
* Added BuddyPress 3.1.0 compatibility.
* Added WP Social Login compatibility.
* Added required field enforcement - if admin adds any required fields to the activation process, then the activation step with these fields can't be "skipped" without completing the required field(s).
* Minor bugfixes.

= 1.2 =
* Added .pot file for easier translations.

= 1.1 =
* Changed plugin name. 
* Changed plugin description and added image assets.
* Resolved version number issue.
* Resolved text domain issue.

= 0.0 =
* First public release