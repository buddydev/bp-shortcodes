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
function bpsc_get_context_user_id( $context = '' ) {

	switch ( $context ) {
		case 'displayed':
			$user_id = bp_displayed_user_id();
			break;
		case 'list':
			$user_id = bp_get_member_user_id();
			break;
		case 'logged':
		case 'logged_in':
			$user_id = get_current_user_id();
			break;
		case 'author':
			$user_id = get_the_author_meta( 'ID' );
			break;
		default:
			$user_id = 0;
			break;
	}

	return (int) $user_id;
}

/**
 * Returns default user id if the user id is not given.
 *
 * @return int
 */
function bpsc_get_default_user_id() {
	$user_id = 0;

	if ( in_the_loop() ) {
		$user_id = get_the_author_meta( 'ID' );
	} elseif ( bp_is_user() ) {
		$user_id = bp_displayed_user_id();
	} elseif ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
	}

	return $user_id;
}
/**
 * Fetches profile data for a specific field for the user.
 *
 * @param mixed $field        The ID of the field, or the $name of the field.
 * @param int   $user_id      The ID of the user.
 *
 * @return mixed The profile field data.
 */
function bpsc_xprofile_get_field_data_raw( $field, $user_id = 0 ) {

	if ( empty( $user_id ) ) {
		$user_id = bp_displayed_user_id();
	}

	if ( empty( $user_id ) ) {
		return false;
	}

	if ( is_numeric( $field ) ) {
		$field_id = $field;
	} else {
		$field_id = xprofile_get_field_id_from_name( $field );
	}

	if ( empty( $field_id ) ) {
		return false;
	}

	return maybe_unserialize( BP_XProfile_ProfileData::get_value_byid( $field_id, $user_id ) );
}