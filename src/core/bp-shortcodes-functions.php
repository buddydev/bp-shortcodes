<?php
/**
 * Core functions file
 *
 * @package BP_Shortcodes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Get user id If context is provided based on it else return loop member id.
 *
 * @param string $context Context.
 *
 * @return int
 */
function bp_shortcodes_get_user_id( $context = '' ) {

	$user_id = 0;

	if ( 'displayed' == $context ) {
		$user_id = bp_displayed_user_id();
	} elseif ( 'logged_in' == $context ) {
		$user_id = bp_loggedin_user_id();
	} else {
		$user_id = bp_get_member_user_id();
	}

	return (int) $user_id;
}