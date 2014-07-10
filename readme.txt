=== BuddyPress Private Messages for Followers Only ===
Contributors: r-a-y
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=S6EZYK894S99G
Tags: buddypress, message, private message, pm, spam
Requires at least: WP 3.4.1 & BuddyPress 1.6
Tested up to: WP 3.9.1 & BuddyPress 2.x
Stable tag: 1.0

Allow members to send private messages only **if** the recipient is following them.  Requires the BuddyPress Followers plugin.

== Description ==

Like Twitter, the [BuddyPress Followers](http://wordpress.org/extend/plugins/buddypress-followers/) plugin adds the ability to "follow" users on your BuddyPress site.

However, by default, any member on your BuddyPress site is allowed to send private messages to anyone in your userbase.

This plugin allows members to send private messages only **if** the recipient is following them.  This will help prevent private messaging spam on your BuddyPress site.


== Installation ==

1. Download, install and activate the [BuddyPress Follow](http://wordpress.org/plugins/buddypress-followers/) plugin.
1. Download, install and activate this plugin.
1. That's it! :)


== Frequently Asked Questions ==

#### So how does this work? ####

If someone is following you, you can send a private message to that person via the "*Send Private Message*" button on their profile page.
You **cannot** send a private message to a user who is **not** following you.  This is similar to the way direct messages work in Twitter.

It is important to note that site administrators can always send private messages without requiring the recipient to follow them.


#### Can I allow certain user IDs to be private messaged without needing them to follow the sender? ####

You can!  Simply add the following line to /wp-config.php or /wp-content/plugins/bp-custom.php:

`define( 'BP_PM_RECIPIENT_WHITELIST', '1,2' );`

In the example above, anyone can send a message to user ID 1 and 2 without needing them to follow the sender of the message.


#### I noticed that the error message is in English! ####

Yes it is.  But fear not!  You can send me a translation file for inclusion in the next release of the plugin.


== Recommendations ==

I do **not** recommend using the BuddyPress Followers plugin in tandem with the BuddyPress Friends component.

If you're looking for a similar plugin for the BuddyPress friends component only, check out [this plugin](http://wordpress.org/plugins/buddypress-private-message-for-friends-only/) instead.


== Changelog ==

= 1.1 =
* Bump minimum requirements to WP 3.4.1 / BuddyPress 1.6
* Outright remove the private message button on member profiles instead of hiding via CSS
* Override the "Send To" string on the compose private message page

= 1.0 =
* First version!