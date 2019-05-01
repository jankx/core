<?php
/**
 * Foxy option is user configurations in WordPress Admin page
 * itegrate with Setting API, Redux Framework, Jackal Framework
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

namespace Jankx\Core\Traits;
/**
 * Option trait
 */
trait Option {
	/**
	 * Integrate option framework
	 *
	 * @var Option_Base
	 */
	protected static $option_framework;

	/**
	 * Set option framework for Foxy Framework
	 *
	 * @param  Option_Framework_Base $framework Option framework use in theme.
	 * @throws Exception Throw exception if $framework is not instanceof Option_Framework_Base class.
	 * @return void
	 */
	public function set_option_framework( $framework ) {
		if ( ! ( $framework instanceof Option_Framework_Base ) ) {
			throw new Exception(
				sprintf( 'Option Framework must be instance of %s class', 'Option_Framework_Base' ),
				333
			);
		}
		self::$option_framework = $framework;

		$this->option = function() {
			return self::$option_framework;
		};
	}

	/**
	 * Get foxy them option via Option Framework
	 *
	 * @param string $option_name   Option name (key) want to get value.
	 * @param mixed  $default_value Default value if option `$option_name` not exists.
	 * @return mixed
	 */
	public static function get_option( $option_name, $default_value = false ) {
		$pre = apply_filters( 'option_' . $option_name, null );
		if ( ! is_null( $pre ) ) {
			return $pre;
		}

		return call_user_func(
			array( self::$option_framework, 'get_option' ),
			$option_name,
			$default_value
		);
	}

	public static function update_option( $option_name, $option_value ) {
		update_option( $option_name, $option_value );
	}
}
