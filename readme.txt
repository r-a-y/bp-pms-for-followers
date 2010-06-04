=== BuddyPress Private Messages for Followers Only ===
Contributors: r-a-y
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=S6EZYK894S99G
Tags: buddypress, message, private message, pm, spam
Requires at least: WP 2.9 & BuddyPress 1.2
Tested up to: WP 2.9.2 & BuddyPress 1.2.4.1
Stable tag: 1.0

Allow members to send private messages only **if** the recipient is following them.  Requires the BuddyPress Followers plugin.

== Description ==

Like Twitter, the [BuddyPress Followers](http://wordpress.org/extend/plugins/buddypress-followers/) plugin adds the ability to "follow" users on your BuddyPress site.

However, by default, any member on your BuddyPress site is allowed to send private messages to anyone in your userbase.

This plugin allows members to send private messages only **if** the recipient is following them.  This will help prevent private messaging spam on your BuddyPress site.


== Installation ==

1. Download, install and activate the [BuddyPress Followers](http://wordpress.org/extend/plugins/buddypress-followers/) plugin.
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

If you're looking for a similar plugin for the BuddyPress friends component only, check out [this plugin](http://wordpress.org/extend/plugins/buddypress-private-message-for-friends-only/) instead.


== Donate! ==

I'm a forum moderator on the buddypress.org forums.  I spend a lot of my free time helping people - pro bono!

If you downloaded this plugin and like it, please:

* [Fund my work soundtrack!](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=S6EZYK894S99G)  Music helps me help you!  A dollar lets me buy a new tune off Amie Street or emusic.com!  Or if you're feeling generous, you can help me buy a whole CD!  If you choose to donate, let me know what songs or which CD you want me to listen to! :)
* Rate this plugin
* Spread the gospel of BuddyPress


== Changelog ==

= 1.0 =
* First version!