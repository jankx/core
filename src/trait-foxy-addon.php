<?php
/**
 * This trait contains all method of foxy addons
 *
 * @package Foxy/Core
 * @subpackage Addon
 * @author Puleeno Nguyen <puleeno@gmail.com>
 */

/**
 * Foxy_Addon trait
 */
trait Foxy_Addon {
	/**
	 * Foxy addon header
	 * List header info will be used when parse addon info from file comments.
	 *
	 * @var array
	 */
	protected static $addon_headers = array(
		'Name'        => 'Addon Name',
		'PluginURI'   => 'Addon URI',
		'Version'     => 'Version',
		'Description' => 'Description',
		'Author'      => 'Author',
		'AuthorURI'   => 'Author URI',
		'TextDomain'  => 'Text Domain',
		'DomainPath'  => 'Domain Path',
	);
	/**
	 * Foxy addons variable.
	 * This variable is storaged all addons was searched and can be use later.
	 *
	 * @var array
	 */
	protected static $addons = array();

	/**
	 * Addon cached flag
	 * Use this flag for check framework has searched addons before.
	 *
	 * @var boolean
	 */
	protected static $addon_cached_flag = false;

	/**
	 * Get addon header for get addon data from file
	 *
	 * @return array
	 */
	public static function get_addon_headers() {
		return self::$addon_headers;
	}

	/**
	 * Foxy addon directory
	 * Can be changed via filter hooks for custom addon directory or embed to another theme.
	 *
	 * @return string
	 */
	public static function get_addons_directory() {
		return apply_filters( 'foxy_addon_directory', FOXY_ACTIVE_THEME_DIR . 'addons' );
	}

	/**
	 * Get all addons installed in theme
	 *
	 * @param boolean $load_cached Load cached addons dont' need to search addons in theme directory.
	 * @return array
	 */
	public static function get_addons( $load_cached = true ) {
		if ( ! self::$addon_cached_flag || ( self::$addon_cached_flag && ! $load_cached ) ) {
			// List file may be use as foxy addon.
			$searched_addons = glob( self::get_addons_directory() . '/{*,*/*}.php', GLOB_BRACE );
			// List valid foxy addons.
			$addons = array();

			foreach ( $searched_addons as $searched_addon ) {
				$data = get_file_data( $searched_addon, self::get_addon_headers() );
				if ( empty( $data['Name'] ) ) {
					continue;
				}
				$addons[ $searched_addon ] = $data;
			}

			self::$addon_cached_flag = true;
			self::$addons            = $addons;
		}
		return self::$addons;
	}

	/**
	 * Get activated addons for foxy framework.
	 * Activated addons will be loaded and integrate with foxy framework core.
	 *
	 * @return array
	 */
	public static function get_active_addons() {
		/**
		 * Create filter hooks for other integrate with Foxy addons.
		 *
		 * @todo Build foxy addons manager feature
		 */
		$pre_get_active_addons = apply_filters( 'foxy_active_addons', array() );
		if ( ! empty( $pre_get_active_addons ) && is_array( $pre_get_active_addons ) ) {
			return $pre_get_active_addons;
		}
		if ( is_admin() ) {
			add_action( 'foxy_loaded', array( __CLASS__, 'install_addons' ) );
		}
		return array_keys( self::get_addons() );
	}

	public static function get_installed_addons() {
		return get_option( 'foxy_installed_addons', array() );
	}

	public static function is_addon_installed( $addon_file, $installed_addons = null ) {
		if ( is_null( $installed_addons ) ) {
			$installed_addons = self::get_installed_addons();
		}
		return in_array( $addon_file, $installed_addons, true );
	}

	public static function install_addons() {
		$addons = self::get_active_addons();
		$installed_addons = Foxy::get_installed_addons();
		foreach ( $addons as $addon ) {
			if ( ! Foxy::is_addon_installed( $addon, $installed_addons ) ) {
				$activate_hook = sprintf( '%s_activation_hook', $addon );
				do_action( $activate_hook );
				$installed_addons[] = $addon;
			}
		}
		update_option( 'foxy_installed_addons', $installed_addons );
	}
}
