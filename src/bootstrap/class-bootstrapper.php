<?php
/**
 * Bootstrapper. Initializes the plugin.
 *
 * @package    BP_Shortcodes
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2018, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh
 * @since      1.0.0
 */

namespace BP_Shortcodes\Bootstrap;

use BP_Shortcodes\Shortcodes\Profile\Profile_Data_Shortcodes;
use BP_Shortcodes\Shortcodes\Profile\Profile_Shortcode;
use BP_Shortcodes\Shortcodes\Profile\Profile_Photo_Shortcode;
use BP_Shortcodes\Shortcodes\User\User_Shortcodes;

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Bootstrapper.
 */
class Bootstrapper {

	/**
	 * Setup the bootstrapper.
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Bind hooks
	 */
	private function setup() {
		add_action( 'bp_loaded', array( $this, 'load' ) );
		add_action( 'bp_init', array( $this, 'load_translations' ) );
	}

	/**
	 * Load core functions/template tags.
	 * These are non auto loadable constructs.
	 */
	public function load() {
		require_once bp_shortcodes()->path . 'src/core/bp-shortcodes-functions.php';

		Assets_Loader::boot();
		User_Shortcodes::boot();

		if ( bp_is_active( 'xprofile' ) ) {
			Profile_Shortcode::boot();
			Profile_Data_Shortcodes::boot();
		}

		if ( ! bp_disable_avatar_uploads() ) {
			Profile_Photo_Shortcode::boot();
		}
	}

	/**
	 * Load translations.
	 */
	public function load_translations() {
		load_plugin_textdomain(
			'bp-shortcodes',
			false,
			basename( dirname( bp_shortcodes()->path ) ) . '/languages'
		);
	}
}
