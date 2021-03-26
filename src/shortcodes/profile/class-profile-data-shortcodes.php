<?php
/**
 * Profile data shortcode
 *
 * @package    BP_Shortcodes
 * @subpackage Shortcodes\Profile
 * @copyright  Copyright (c) 2021, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh, Ravi Sharma(raviousprime)
 * @since      1.0.0
 */

namespace BP_Shortcodes\Shortcodes\Profile;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Profile Data shortcode.
 */
class Profile_Data_Shortcodes {

	/**
	 * Singleton instance.
	 *
	 * @var Profile_Data_Shortcodes
	 */
	private static $instance = null;

	/**
	 * Boots class
	 *
	 * @return self
	 */
	public static function boot() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			self::$instance->setup();
		}

		return self::$instance;
	}

	/**
	 * Create shortcode
	 */
	private function setup() {
		// for back comoat using bpc, we will use bpsc-(short form of BuddyPress Shortcodes).
		add_shortcode( 'bpc-profile-data', array( $this, 'shortcode_data' ) );
		add_shortcode( 'bpsc-profile-data', array( $this, 'shortcode_data' ) );
	}

	/**
	 * Render profile loop content
	 *
	 * @param array  $atts Shortcode atts.
	 * @param string $content content.
	 *
	 * @return string
	 */
	public function shortcode_data( $atts, $content = '' ) {

		$atts = shortcode_atts(
			array(
				'context'           => '', // logged, displayed, author.
				'user_id'           => 0,
				'field'             => '',
				'multi_option_wrap' => '<span class="bpsc-field-value-item">%s</span>',
				'value_wrap'        => '<span class="bpsc-field-value">%s</span>',
				'separator'         => ',',
			),
			$atts
		);

		if ( empty( $atts['field'] ) ) {
			return '';
		}

		if ( $atts['context'] ) {
			$user_id = bpsc_get_context_user_id( $atts['context'] );
		} elseif ( $atts['user_id'] ) {
			$user_id = $atts['user_id'];
		} else {
			$user_id = bpsc_get_default_user_id();
		}

		if ( ! $user_id ) {
			return '';
		}

		$values = bpsc_xprofile_get_field_data_raw( $atts['field'], $user_id );

		$output = '';
		// is it multi valued field.
		if ( is_array( $values ) ) {
			$formatted_values = array();
			foreach ( $values as $value ) {
				$formatted_values[] = sprintf( $atts['multi_option_wrap'], $value );
			}
			$output = join( $atts['separator'], $formatted_values );
		} else {
			$output = $values;
		}

		return sprintf( $atts['value_wrap'], $output );
	}
}
