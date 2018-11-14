<?php
/**
 * Foxy framework main class
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen
 * @license GPL-3
 */

/**
 * Foxy class
 */
class Foxy {
	/**
	 * Use foxy traits
	 */
	use Foxy_Option, Foxy_Config, Foxy_Plugin, Foxy_Addon, Foxy_Meta, Foxy_Request, Foxy_Layout, Foxy_Template, Foxy_UI;

	const CORE_VERSION = '1.0.0';

	/**
	 * Foxy main instance
	 *
	 * @var Foxy
	 */
	protected static $instance;

	/**
	 * This method use to get Foxy instance
	 *
	 * @return Foxy
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Foxy overload method to get foxy addon instance
	 *
	 * @param string $method Foxy addon name.
	 * @param array  $args   Arguments is used in method.
	 * @return mixed
	 */
	public function __call( $method, $args ) {
		return call_user_func_array(
			$this->$method,
			$args
		);
	}

	/**
	 * Foxy overload method to get foxy addon instance
	 *
	 * @param string $method Foxy addon name.
	 * @param array  $args   Arguments is used in method.
	 * @return mixed
	 */
	public static function __callStatic( $method, $args ) {
		$instance = self::instance();
		return call_user_func_array(
			array( $instance, $method ),
			$args
		);
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
}
