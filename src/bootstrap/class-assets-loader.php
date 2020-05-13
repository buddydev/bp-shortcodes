<?php
/**
 * Assets Loader
 *
 * @package    BP_Shortcodes
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2018, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh, Ravi Sharma
 * @since      1.0.0
 */

namespace BP_Shortcodes\Bootstrap;

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Assets Loader.
 */
class Assets_Loader {

	/**
	 * Data to be send as localized js.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Boot itself
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Setup
	 */
	public function setup() {
		add_action( 'bp_enqueue_scripts', array( $this, 'load_assets' ) );
	}

	/**
	 * Load plugin assets
	 */
	public function load_assets() {
		$this->register();
		$this->enqueue();
	}

	/**
	 * Register assets.
	 */
	public function register() {
		$this->register_vendors();
		$this->register_core();
	}

	/**
	 * Load assets.
	 */
	public function enqueue() {
		wp_enqueue_style( 'bp_shortcodes' );
		//wp_enqueue_script( 'bp_shortcodes' );

		//wp_localize_script( 'bp_shortcodes', 'BP_SHORTCODES', $this->data );
	}

	/**
	 * Register vendor scripts.
	 */
	private function register_vendors() {}

	/**
	 * Register core assets.
	 */
	private function register_core() {
		$bp_shortcode = bp_shortcodes();
		$url          = $bp_shortcode->url;
		$version      = $bp_shortcode->version;

		wp_register_style(
			'bp_shortcodes',
			$url . 'assets/css/bp-shortcodes.css',
			false,
			$version
		);

		/*wp_register_script(
			'bp_shortcodes',
			$url . 'assets/js/bp-shortcodes.js',
			array( 'jquery' ),
			$version
		);*/

		$this->data = array();
	}
}
