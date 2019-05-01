<?php
/**
 * Jankx framework main class
 *
 * @package Jankx/Core
 * @author Puleeno Nguyen
 * @license GPL-3
 */

use Jankx\Core;

/**
 * Jankx class
 */
class Jankx {
	/**
	 * Use Jankx traits
	 */
	use OptionTrait,
	ConfigTrait,
	PluginTrait,
	AddonTrait,
	Meta_DataTrait,
	RequestTrait,
	LayoutTrait,
	TemplateTrait,
	UITrait;

	const CORE_VERSION = '1.0.0';

	/**
	 * Jankx main instance
	 *
	 * @var Jankx
	 */
	protected static $instance;

	/**
	 * This method use to get Jankx instance
	 *
	 * @return Jankx
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Jankx overload method to get Jankx addon instance
	 *
	 * @param string $method Jankx addon name.
	 * @param array  $args   Arguments is used in method.
	 * @return mixed
	 */
	public function __call( $method, $args ) {
		if ( isset( $this->$method ) && is_callable( $this->$method ) ) {
			return call_user_func_array( $this->$method, $args );
		}
	}

	/**
	 * Jankx overload method to get Jankx addon instance
	 *
	 * @param string $method Jankx addon name.
	 * @param array  $args   Arguments is used in method.
	 * @return mixed
	 */
	public static function __callStatic( $method, $args ) {
		$method = array( self::instance(), $method );
		if ( is_callable( $method ) ) {
			return call_user_func_array( $method, $args );
		}
	}

	/**
	 * Method check and define constant if not defined before
	 *
	 * @param string          $name   Constant name.
	 * @param string|bool|int $val    Constant value.
	 * @return void
	 */
	public static function define( $name, $val ) {
		if ( ! defined( $name ) ) {
			define( $name, $val );
		}
	}

	public static function has_addon( $addon_name ) {
		return isset( self::instance()->$addon_name ) && is_callable( self::instance()->$addon_name );
	}
}
