<?php
/**
 * User shortcodes
 *
 * @package    BP_Shortcodes
 * @subpackage Shortcodes\User
 * @copyright  Copyright (c) 2020, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh, Ravi Sharma(raviousprime)
 * @since      1.0.0
 */

namespace BP_Shortcodes\Shortcodes\User;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class User_Shortcodes
 */
class User_Shortcodes {

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
		add_shortcode( 'bpsc-user-id', array( $this, 'render_user_id' ) );
		add_shortcode( 'bpsc-username', array( $this, 'render_username' ) );
		add_shortcode( 'bpsc-user-email', array( $this, 'render_user_user_email' ) );
		add_shortcode( 'bpsc-user-registration-date', array( $this, 'render_user_registration_date' ) );
		add_shortcode( 'bpsc-user-display-name', array( $this, 'render_user_display_name' ) );
		add_shortcode( 'bpsc-user-member-type', array( $this, 'render_user_member_type' ) );
		add_shortcode( 'bpsc-user-avatar', array( $this, 'render_user_avatar' ) );
	}

	/**
	 * Render user id
	 *
	 * @param array $atts Shortcode atts.
	 *
	 * @return string
	 */
	public function render_user_id( $atts ) {

		$atts = shortcode_atts(
			array( 'context' => '' ),
			$atts
		);

		$user_id = bp_shortcodes_get_user_id( $atts['context'] );

		if ( ! $user_id ) {
			return '';
		}

		ob_start();

		echo $user_id;

		return ob_get_clean();
	}

	/**
	 * Render user name
	 *
	 * @param array $atts Shortcode atts.
	 *
	 * @return string
	 */
	public function render_username( $atts ) {

		$atts = shortcode_atts(
			array(
				'user_id'         => '',
				'context'         => '',
				'link_to_profile' => 1
			),
			$atts
		);

		$user_id = empty( $atts['user_id'] ) ? bp_shortcodes_get_user_id( $atts['context'] ) : absint( $atts['user_id'] );
		$user    = $this->get_user( $user_id );

		if ( ! $user ) {
			return '';
		}

		ob_start();

		echo $atts['link_to_profile'] ? sprintf( '<a href="%s">%s</a>', bp_core_get_user_domain( $user->ID ), $user->user_login ) : $user->user_login;

		return ob_get_clean();
	}

	/**
	 * Render user display name
	 *
	 * @param array $atts Shortcode atts.
	 *
	 * @return string
	 */
	public function render_user_display_name( $atts ) {

		$atts = shortcode_atts(
			array(
				'user_id'         => '',
				'context'         => '',
				'link_to_profile' => 1
			),
			$atts
		);

		$user_id = empty( $atts['user_id'] ) ? bp_shortcodes_get_user_id( $atts['context'] ) : absint( $atts['user_id'] );
		$user    = $this->get_user( $user_id );

		if ( ! $user ) {
			return '';
		}

		ob_start();

		echo $atts['link_to_profile'] ? sprintf( '<a href="%s">%s</a>', bp_core_get_user_domain( $user->ID ), $user->display_name ) : $user->display_name;

		return ob_get_clean();
	}

	/**
	 * Render user email
	 *
	 * @param array $atts Shortcode atts.
	 *
	 * @return string
	 */
	public function render_user_user_email( $atts ) {

		$atts = shortcode_atts(
			array(
				'user_id' => '',
				'context' => '',
			),
			$atts
		);

		$user_id = empty( $atts['user_id'] ) ? bp_shortcodes_get_user_id( $atts['context'] ) : absint( $atts['user_id'] );
		$user    = $this->get_user( $user_id );

		if ( ! $user ) {
			return '';
		}

		ob_start();

		echo $user->user_email;

		return ob_get_clean();
	}

	/**
	 * Render user registration data
	 *
	 * @param array $atts Shortcode atts.
	 *
	 * @return string
	 */
	public function render_user_registration_date( $atts ) {

		$atts = shortcode_atts(
			array(
				'user_id' => '',
				'context' => '',
				'format'  => 'F j, Y',
			),
			$atts
		);

		$user_id = empty( $atts['user_id'] ) ? bp_shortcodes_get_user_id( $atts['context'] ) : absint( $atts['user_id'] );
		$user    = $this->get_user( $user_id );

		if ( ! $user ) {
			return '';
		}

		ob_start();

		echo date( $atts['format'], strtotime( $user->user_registered ) );

		return ob_get_clean();
	}

	/**
	 * Render user member types
	 *
	 * @param array $atts Shortcode atts.
	 *
	 * @return string
	 */
	public function render_user_member_type( $atts ) {

		$atts = shortcode_atts(
			array(
				'user_id'  => '',
				'context'  => '',
				'show_all' => 1,
			),
			$atts
		);

		$user_id = empty( $atts['user_id'] ) ? bp_shortcodes_get_user_id( $atts['context'] ) : absint( $atts['user_id'] );

		if ( ! $user_id ) {
			return '';
		}

		ob_start();

		echo $atts['show_all'] ? join( ',', (array) bp_get_member_type( $user_id, false ) ) : bp_get_member_type( $user_id );

		return ob_get_clean();
	}

	/**
	 * Render user registration data
	 *
	 * @param array $atts Shortcode atts.
	 *
	 * @return string
	 */
	public function render_user_avatar( $atts ) {

		$atts = shortcode_atts(
			array(
				'user_id'         => '',
				'context'         => '',
				'type'            => 'thumb',
				'width'           => false,
				'height'          => false,
				'title'           => false,
				'link_to_profile' => 1,
			),
			$atts
		);

		$user_id = empty( $atts['user_id'] ) ? bp_shortcodes_get_user_id( $atts['context'] ) : absint( $atts['user_id'] );

		if ( ! $user_id ) {
			return '';
		}

		$avatar = bp_core_fetch_avatar( array(
			'item_id' => $user_id,
			'type'    => $atts['type'],
			'width'   => $atts['width'],
			'height'  => $atts['height'],
			'title'   => $atts['title'],
		) );

		ob_start();

		echo $atts['link_to_profile'] ? sprintf( '<a href="%s">%s</a>', bp_core_get_user_domain( $user_id ), $avatar ) : $avatar;

		return ob_get_clean();
	}

	/**
	 * Get user
	 *
	 * @param int $user_id User id.
	 *
	 * @return false|\WP_User
	 */
	private function get_user( $user_id ) {
		return get_user_by( 'id', $user_id );
	}
}
