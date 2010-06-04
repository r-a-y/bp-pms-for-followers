<?php
/*
Plugin Name: BuddyPress Private Messages for Followers Only
Description: Allow members to send private messages only if the recipient is following them.  Requires the BuddyPress Followers plugin.
Author: r-a-y
Author URI: http://buddypress.org/community/members/r-a-y
Plugin URI: http://buddypress.org/community/groups/buddypress-private-messages-for-followers-only
Version: 1.0

License: CC-GNU-GPL http://creativecommons.org/licenses/GPL/2.0/
Donate: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CT8KZGFEVA3R6
*/

function bp_pms_for_followers_init() {
	require( dirname( __FILE__ ) . '/bp-pms-for-followers.php' );
}
add_action( 'bp_init', 'bp_pms_for_followers_init', 99 );
?>