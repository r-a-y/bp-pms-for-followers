<?php
class BP_PMs_Follow {

	var $whitelist_ids;

	function init() {
		if ( class_exists( 'BP_Follow' ) ) {
			load_plugin_textdomain( 'bp-pms-follow', false, dirname(plugin_basename(__FILE__)) . '/lang' );

			if( defined( "BP_PM_RECIPIENT_WHITELIST" ) )
				$this->whitelist_ids = explode(',', BP_PM_RECIPIENT_WHITELIST );
			else
				$this->whitelist_ids = array();

			add_action( 'messages_message_before_save', array( &$this, 'check_recipients' ) );
			add_action( 'init', array( &$this, 'override_bp_l10n' ), 9 );
			add_action( 'wp_head', array( &$this, 'remove_pm_btn' ), 99 );
		}
		else {
			add_action( 'admin_notices', array( &$this, 'display_requirement' ) );
		}
	}

	function check_recipients( $message_info ) {
		global $bp;

		$recipients = $message_info->recipients;

		$u = 0; // # of recipients in the message that are not following the sender

		foreach ( $recipients as $key => $recipient ) {
			$is_whitelisted = in_array( $recipient->user_id, $this->whitelist_ids );

			// if recipient is whitelisted, skip check
			if( $is_whitelisted )
				continue;

			// if site admin, skip check
			if( $bp->loggedin_user->is_site_admin == 1 )
				continue;

			// make sure sender is not trying to send to themselves
			if ( $recipient->user_id == $bp->loggedin_user->id ) {
				unset( $message_info->recipients[$key] );
				continue;
			}

			// check if the attempted recipient is following the sender
			// if the recipient isn't following the sender, remove recipient from list
			// if there are no recipients, BP_Messages_Message:send() will return false and thus message isn't sent!
			$is_recipient_following = bp_follow_is_following( 'leader_id='. $bp->loggedin_user->id .'&follower_id='. $recipient->user_id );

			if ( !$is_recipient_following ) {
				unset( $message_info->recipients[$key] );
				$u++;
			}
		}

		// if there are multiple recipients and if one of the recipients is not following the sender, remove everyone from the recipient's list
		// this is done to prevent the message from being sent to *anyone* and is another spam prevention measure
		if ( count( $recipients ) > 1 && $u > 0 )
			unset( $message_info->recipients );
	}

	/**
	 * Override specific gettext values used by BuddyPress.
	 *
	 * Thanks to Paul Gibbs for this technique!
	 */
	function override_bp_l10n() {
		global $l10n;

		$mo = new MO();

		$mo->add_entry( array(
			'singular'     => 'There was an error sending that message, please try again',
			'translations' => array(
				__ ('You cannot private message a user who is not following you', 'bp-pms-follow' )
			)
		) );

		$mo->add_entry( array(
			'singular'     => 'There was a problem sending that reply. Please try again.',
			'translations' => array(
				__ ('You cannot private message a user who is not following you', 'bp-pms-follow'
			)
		) ) );

		$mo->add_entry( array(
			'singular'     => "Send To (Username or Friend's Name)",
			'translations' => array(
				__( "Send To (Username or Followers' Name)", 'bp-pms-follow' )
			)
		) );

		if ( isset( $l10n['buddypress'] ) ) {
			$mo->merge_with( $l10n['buddypress'] );
		}

		$l10n['buddypress'] = &$mo;
		unset( $mo );
	}

	/**
	 * Remove the private message button for the displayed user.
	 *
	 * We remove the PM button if the displayed user is not following the logged-
	 * in user.
	 *
	 * However, if the displayed user is whitelisted or if the logged-in user
	 * has the 'bp_moderate' cap, the PM button does *not* get removed.
	 *
	 * @since 1.1.0
	 */
	function remove_pm_btn() {
		// various conditions where we should bail out!
		if ( ! bp_is_user() || ! bp_is_active( 'messages' ) || ! is_user_logged_in() || bp_current_user_can( 'bp_moderate' ) ) {
			return;
		}

		// if displayed user is whitelisted, allow logged-in user to message this user
		if ( in_array( bp_displayed_user_id(), $this->whitelist_ids ) ) {
			return;
		}

		$is_displayed_following = bp_follow_is_following( array(
			'leader_id'   => bp_loggedin_user_id(),
			'follower_id' => bp_displayed_user_id()
		) );

		// if displayed user is not following the logged-in user, remove PM button
		if ( ! $is_displayed_following ) {
			remove_action( 'bp_member_header_actions', 'bp_send_private_message_button', 20 );
		}

	}

	// should this be translatable?
	function display_requirement() {
		echo '<div class="error fade"><p>BuddyPress Private Messages for Followers Only requires the <strong><a href="http://wordpress.org/extend/plugins/buddypress-followers/">BuddyPress Followers</a></strong> plugin. Please install and <a href="plugins.php">activate BuddyPress Followers</a> now.</p></div>';
	}
}

$pms_follow = new BP_PMs_Follow();
$pms_follow->init();
?>