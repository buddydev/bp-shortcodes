<?php
/**
 * Plugin Name: BuddyPress Shortcodes
 * Version: 1.0.0
 * Plugin URI: https://buddydev.com/plugins/bp-shortcodes
 * Description: This plugin offers shortcodes for BuddyPress.
 * Author: BuddyDev
 * Author URI: https://buddydev.com/
 * Requires PHP: 5.3
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  bp-shortcodes
 * Domain Path:  /languages
 *
 * @package bp-shortcodes
 **/

use BP_Shortcodes\Bootstrap\Autoloader;
use BP_Shortcodes\Bootstrap\Bootstrapper;

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Class BP_Shortcodes
 *
 * @property-read $path     string Absolute path to the plugin directory.
 * @property-read $url      string Absolute url to the plugin directory.
 * @property-read $basename string Plugin base name.
 * @property-read $version  string Plugin version.
 */
class BP_Shortcodes {

	/**
	 * Plugin Version.
	 *
	 * @var string
	 */
	private $version = '1.0.0';

	/**
	 * Class instance
	 *
	 * @var BP_Shortcodes
	 */
	private static $instance = null;

	/**
	 * Plugin absolute directory path
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Plugin absolute directory url
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Plugin Basename.
	 *
	 * @var string
	 */
	private $basename;

	/**
	 * Protected properties. These properties are inaccessible via magic method.
	 *
	 * @var array
	 */
	private $guarded = array( 'instance' );

	/**
	 * BP_Shortcodes constructor.
	 */
	private function __construct() {
		$this->bootstrap();
	}

	/**
	 * Get Singleton Instance
	 *
	 * @return BP_Shortcodes
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Bootstrap the core.
	 */
	private function bootstrap() {
		$this->path     = plugin_dir_path( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->basename = plugin_basename( __FILE__ );

		// Load autoloader.
		require_once $this->path . 'src/bootstrap/class-autoloader.php';

		$autoloader = new Autoloader( 'BP_Shortcodes\\', __DIR__ . '/src/' );

		spl_autoload_register( $autoloader );

		Bootstrapper::boot();
	}

	/**
	 * Magic method for accessing property as readonly(It's a lie, references can be updated).
	 *
	 * @param string $name property name.
	 *
	 * @return mixed|null
	 */
	public function __get( $name ) {

		if ( ! in_array( $name, $this->guarded, true ) && property_exists( $this, $name ) ) {
			return $this->{$name};
		}

		return null;
	}
}

/**
 * Helper to access singleton instance
 *
 * @return BP_Shortcodes
 */
function bp_shortcodes() {
	return BP_Shortcodes::get_instance();
}

bp_shortcodes();
