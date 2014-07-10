<?php
/**
 * BP PMs for Followers Core.
 *
 * @since 1.0.0
 */
class BP_PMs_Follow {

	/**
	 * Array of user IDs to whitelist from recipient checking.
	 *
	 * @var array See {@link BP_PMs_Follow::init()}
	 */
	protected $whitelist_ids = array();

	/**
	 * Initializer method.
	 */
	public function init() {
		if ( defined( 'BP_PM_RECIPIENT_WHITELIST' ) ) {
			$this->whitelist_ids = explode( ',', BP_PM_RECIPIENT_WHITELIST );
		}

		add_action( 'messages_message_before_save', array( &$this, 'check_recipients' ) );
		add_action( 'init', array( &$this, 'override_bp_l10n' ), 9 );
		add_action( 'wp_head', array( &$this, 'remove_pm_btn' ), 99 );
	}

	/**
	 * Should we block the current user from sending a PM to recipient?
	 *
	 * If recipient is not following the logged-in user, stop the message from
	 * sending.  If the current user has the 'bp_moderate' capability, this user
	 * is exempt from this check.  Also if the recipient user ID is whitelisted,
	 * we allow the message to send.
	 *
	 * Note that we're only doing checks against the logged-in user and not the
	 * sender's user ID.  This is done so plugins can continue to send PMs w/o
	 * the possibility of beign blocked.  Open to changing this though.
	 *
	 * @param BP_Messages_Message $message_info
	 */
	public function check_recipients( $message_info ) {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$recipients = $message_info->recipients;

		$u = 0; // # of recipients in the message that are not following the sender

		foreach ( $recipients as $key => $recipient ) {
			// if recipient is whitelisted, skip check
			if( in_array( $recipient->user_id, $this->whitelist_ids ) ) {
				continue;
			}

			// if logged-in user can moderate, skip check
			if( bp_current_user_can( 'bp_moderate' ) ) {
				continue;
			}

			// make sure sender is not trying to send to themselves
			if ( $recipient->user_id == bp_loggedin_user_id() ) {
				unset( $message_info->recipients[$key] );
				continue;
			}

			// check if the attempted recipient is following the sender.
			$is_recipient_following = bp_follow_is_following( array(
				'leader_id'   => bp_loggedin_user_id(),
				'follower_id' => $recipient->user_id
			) );

			/**
			 * If the recipient isn't following the sender, remove recipient from list
			 * if there are no recipients, BP_Messages_Message:send() will return false
			 * and thus message isn't sent!
			 */
			if ( ! $is_recipient_following ) {
				unset( $message_info->recipients[$key] );
				$u++;
			}
		}

		/**
		 * If there are multiple recipients and if one of the recipients is not
		 * following the sender, remove everyone from the recipient's list.
		 *
		 * This is done to prevent the message from being sent to *anyone* and is
		 * another spam prevention measure.
		 */
		if ( count( $recipients ) > 1 && $u > 0 ) {
			unset( $message_info->recipients );
		}
	}

	/**
	 * Override specific gettext values used by BuddyPress.
	 *
	 * Thanks to Paul Gibbs for this technique!
	 */
	public function override_bp_l10n() {
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
	public function remove_pm_btn() {
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

}

$pms_follow = new BP_PMs_Follow();
$pms_follow->init();
