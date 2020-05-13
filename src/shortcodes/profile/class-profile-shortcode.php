<?php
/**
 * Profile shortcode
 *
 * @package    BP_Shortcodes
 * @subpackage Shortcodes\Profile
 * @copyright  Copyright (c) 2020, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh, Ravi Sharma(raviousprime)
 * @since      1.0.0
 */

namespace BP_Shortcodes\Shortcodes\Profile;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Profile_Shortcode
 */
class Profile_Shortcode {

	/**
	 * Profile loop args
	 *
	 * @var array
	 */
	private $profile_args = array();

	/**
	 * Boot class
	 */
	public static function boot() {
		$self = new self();

		$self->setup();
	}

	/**
	 * Create shortcode
	 */
	private function setup() {
		add_shortcode( 'bp-shortcodes-profile', array( $this, 'shortcode' ) );
	}

	/**
	 * Render profile loop content
	 *
	 * @param array $atts Shortcode atts.
	 *
	 * @return string
	 */
	public function shortcode( $atts ) {
		$default_atts = apply_filters( 'bp_shortcodes_profile_default_atts', array(
			'group_id' => '',
			'user_id'  => '',
			'context'  => '',
		) );

		$atts = shortcode_atts( $default_atts, $atts, 'bp-shortcodes-profile' );

		if ( ! $atts['user_id'] && ! $atts['context'] ) {
			return '';
		}

		$this->profile_args['profile_group_id'] = $atts['group_id'] ? $atts['group_id'] : false;

		if ( $atts['user_id'] ) {
			$this->profile_args['user_id'] = absint( $atts['user_id'] );
		} elseif ( $atts['context'] && 'display' == $atts['context'] ) {
			$this->profile_args['user_id'] = bp_displayed_user_id();
		} elseif ( $atts['context'] && 'login' == $atts['context'] ) {
			$this->profile_args['user_id'] = bp_loggedin_user_id();
		}

		$content = '';

		add_filter( 'bp_after_has_profile_parse_args', array( $this, 'modify_args' ) );

		ob_start();

		bp_get_template_part( 'members/single/profile/profile-loop' );

		$content = ob_get_clean();

		remove_filter( 'bp_after_has_profile_parse_args', array( $this, 'modify_args' ) );

		return $content;
	}

	/**
	 * Modify profile args
	 *
	 * @param array $args Profile array.
	 *
	 * @return array
	 */
	public function modify_args( $args ) {
		$args = array_merge( $args, $this->profile_args );

		return $args;
	}
}
