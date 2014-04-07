=== Force Password Change ===
Contributors: lumpysimon
Tags: password, passwords, user, users, registration, register, force, require, login, user control
Requires at least: 3.2
Tested up to: 3.9
Stable tag: trunk
License: GPL v2 or later

Require users to change their password on first login.

== Description ==

This plugin redirects newly-registered users to the *Admin -> Edit Profile* page when they first log in. Until they have changed their password, they will not be able to access either the front-end or other admin pages. An admin notice is also displayed informing them that they must change their password.

New administrators must also change their password, but as a safety measure they can also access the *Admin -> Plugins* page.

== Installation ==

To install directly from your WordPress dashboard:

 1. Go to the *Plugins* menu and click *Add New*.
 2. Search for *Force Password Change*.
 3. Click *Install Now* next to the Force Password Change plugin.
 4. Activate the plugin.

Alternatively, see the official WordPress Codex guide to [Manually Installing Plugins](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Download from GitHub =

You can also download this plugin from GitHub at [https://github.com/lumpysimon/wp-force-password-change](https://github.com/lumpysimon/wp-force-password-change)

== Changelog ==

= 0.4 (7th April 2014) =
* Also force administrators to change their password (thanks to [johnbillion](https://github.com/lumpysimon/wp-force-password-change/pull/1))
* French translation (by Franck Fortineau)
* Tested in WordPress 3.9

= 0.3 (November 2012) =
* Prepare for localisation

= 0.2 (November 2012) =
* Complete rewrite of the updated() function

= 0.1 (November 2012) =
* Initial release
