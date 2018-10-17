<?php
/**
 * Foxy option is user configurations in WordPress Admin page
 * itegrate with Setting API, Redux Framework, Jackal Framework
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_Option trait
 */
trait Foxy_Option {
	/**
	 * Integrate option framework
	 *
	 * @var Foxy_Option_Base
	 */
	protected static $option_framework;

	/**
	 * Set option framework for Foxy Framework
	 *
	 * @param  Foxy_Option_Framework_Base $framework Option framework use in theme.
	 * @throws Exception Throw exception if $framework is not instanceof Foxy_Option_Framework_Base class.
	 * @return void
	 */
	public static function set_option_framework( $framework ) {
		if ( ! ( $framework instanceof Foxy_Option_Framework_Base ) ) {
			throw new Exception(
				sprintf( 'Option Framework must be instance of %s class', 'Foxy_Option_Framework_Base' ),
				333
			);
		}
		self::$option_framework = $framework;
	}

	/**
	 * Get foxy them option via Option Framework
	 *
	 * @param string $option_name   Option name (key) want to get value.
	 * @param mixed  $default_value Default value if option `$option_name` not exists.
	 * @return mixed
	 */
	public static function get_option( $option_name, $default_value = false ) {
		return call_user_func(
			array( self::$option_framework, 'get_option' ),
			$option_name,
			$default_value
		);
	}
}
