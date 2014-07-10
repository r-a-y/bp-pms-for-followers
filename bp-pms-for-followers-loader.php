<?php
/*
Plugin Name: BuddyPress Private Messages for Followers Only
Description: Allow members to send private messages only if the recipient is following them.  Requires the BuddyPress Followers plugin.
Author: r-a-y
Author URI: http://profiles.wordpress.org/r-a-y
Plugin URI: http://wordpress.org/plugins/buddypress-private-messages-for-followers-only
Version: 1.0

License: CC-GNU-GPL http://creativecommons.org/licenses/GPL/2.0/
Donate: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CT8KZGFEVA3R6
*/

function bp_pms_for_followers_init() {
	// only supported in BP 1.6+
	if ( version_compare( BP_VERSION, '1.5.99', '>' ) ) {
		// BP Follow exists, continue on!
		if ( class_exists( 'BP_Follow' ) ) {
			require( dirname( __FILE__ ) . '/bp-pms-for-followers.php' );

		// show admin notice saying you need BP Follow
		} else {
			$requirement_notice = sprintf( __( 'BuddyPress Private Messages for Followers Only requires the %s plugin. Please <a href="%s">install</a> and activate BuddyPress Follow now.', 'bp-pms-follow' ), '<strong><a href="https://wordpress.org/plugins/buddypress-followers/">BuddyPress Follow</a></strong>', 'plugin-install.php?tab=search&amp;s=buddypress+follow' );

			add_action( 'admin_notices', create_function( '', "
				echo '<div class=\"error\"><p>' . $requirement_notice . '</p></div>';
			" ) );
		}

	// show admin notice for users < BP 1.6
	} else {
		$older_version_notice = sprintf( __( "Hey! BP Private Messages For Followers Only v1.1 requires BuddyPress 1.6 or higher.  If you don't plan on upgrading BuddyPress, use <a href='%s'>v1.0 instead</a>.", 'bp-pms-follow' ), 'http://downloads.wordpress.org/plugin/buddypress-private-messages-for-followers-only.1.0.zip' );

		add_action( 'admin_notices', create_function( '', "
			echo '<div class=\"error\"><p>' . $older_version_notice . '</p></div>';
		" ) );
	}

}
add_action( 'bp_loaded', 'bp_pms_for_followers_init', 20 );
