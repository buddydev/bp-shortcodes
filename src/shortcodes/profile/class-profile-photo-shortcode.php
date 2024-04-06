<?php
/**
 * Profile photo shortcode
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
 * Class Profile_Photo_Shortcode
 */
class Profile_Photo_Shortcode {

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
		add_shortcode( 'bpsc-profile-photo', array( $this, 'shortcode' ) );
	}

	/**
	 * Render profile loop content
	 *
	 * @param array $atts Shortcode atts.
	 *
	 * @return string
	 */
	public function shortcode( $atts, $content = '' ) {
		$default_atts = array(
			'user_id'         => '',
			'username'        => '',
			'link-to-profile' => 1,
			'type'            => 'full',
			'width'           => false,
			'height'          => false,
		);

		$atts = shortcode_atts( $default_atts, $atts, 'bpsc-profile-photo' );

		if ( ! $atts['user_id'] && ! $atts['username'] ) {
			return do_shortcode( $content );
		}

		$user = empty( $atts['user_id'] ) ? get_user_by( 'slug', $atts['username'] ) : get_user_by( 'id', $atts['user_id'] );

		if ( ! $user ) {
			return do_shortcode( $content );
		}

		$avatar_args = array(
			'item_id' => $user->ID,
			'object'  => 'user',
			'type'    => $atts['type'],
			'width'   => $atts['width'],
			'height'  => $atts['height'],
		);

		$avatar = bp_core_fetch_avatar( $avatar_args );

		if ( $atts['link-to-profile'] ) {
			$user_link = function_exists( 'bp_members_get_user_url' ) ? bp_members_get_user_url( $user->ID ) : bp_core_get_user_domain( $user->ID );

			$avatar = sprintf( '<a href="%1$s">%2$s</a>', esc_url( $user_link ), $avatar );
		}

		return '<div class="bpsc-profile-photo">' . $avatar . '</div>' . do_shortcode( $content );
	}
}

